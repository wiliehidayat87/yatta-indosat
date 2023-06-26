<?php
class Module_model extends CI_Model {
    public  $db,
            $db_table = "module";
   
    function __construct() {
		parent::__construct();

    }
    public function getModuleList($offset, $limit, $search = "") {
        $this->db = $this->load->database('xmp', TRUE);
        
        $sql_query  = "SELECT a.id, a.name, a.description, a.handler ";
        $sql_query .= "FROM $this->db_table a WHERE status = '1' ";
        $sql_query .= (!empty ($search)) ? "AND (UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.description) LIKE '%" . $search . "%' OR UPPER(a.handler) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = "LIMIT $offset, $limit ";
		
		write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query     = $this->db->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                'query'  => $sql_query . $sql_limit,
                'total'  => $total,
                'result' => array (
                    'data'  => $queryData->result_array(),
                    'total' => $totalData
                )
            );
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ();
        }

        return $result;
    }
    
    public function addModule($name, $description,$handler) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (	'name' 			=> $name, 
							'description'	=> $description,
							'handler'		=> $handler,
							'status'		=>'1'
							);
            $this->db->set('date_created', 'NOW()', FALSE);
            $this->db->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function checkModule($handler,$id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $sql	= "SELECT * FROM $this->db_table WHERE handler='$handler' "; 
		$sql   .= (!empty($id))?" AND id!='$id' ":"";
				
		write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db->query($sql)){
            write_log("info", __METHOD__ . ", Query Success ");
            if ($query->num_rows() != 0) {
                return TRUE;
            }
            return FALSE;
        }
        else{
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
                 
    } 
    
    public function updateModule($name, $description,$handler,$id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (	'name' 			=> $name, 
							'description'	=> $description,
							'handler'		=> $handler
						  );
            $this->db->where('id', $id);
            $this->db->set('date_modified', 'NOW()', FALSE);
            $this->db->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '', 'id'=>$id);
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editModule($id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $this->db->select('name, description,handler');
        $this->db->where('id', $id);

		write_log("info", __METHOD__ . ", Start Query ");
        if($query = $this->db->get($this->db_table)){
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        }
        else{
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }

    }
         
   public function deleteModule($id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (	'status' => '0');
            
            $this->db->where('id', $id);
            $this->db->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '', 'id'=>$id);
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
 }

?>
