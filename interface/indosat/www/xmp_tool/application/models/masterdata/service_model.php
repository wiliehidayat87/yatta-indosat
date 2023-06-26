<?php
class Service_model extends CI_Model {
    public  $db,
            $db_table = "service";
   
    function __construct() {
		parent::__construct();

    }
    public function getServiceList($offset, $limit, $search = "") {
        $this->db = $this->load->database('xmp', TRUE);
        
        $sql_query  = "SELECT a.id, a.name,' description' ,a.adn, DATE_FORMAT(date_created, '%d-%m-%Y %T') as date_created  ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= (!empty ($search)) ? "WHERE (UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.adn) LIKE '%" . $search . " %'OR UPPER(a.description) LIKE '%" . $search . "%' ) " : "";
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
    
    public function addNewService($name, $adn) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array ('name' => $name, 
                    'adn'=> $adn
                   
                   );
            $this->db->set('date_created', 'NOW()', FALSE);
            $this->db->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => $this->db->insert_id());
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function check_service_name($service_name,$adn) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table WHERE name=? AND adn=?"; 
				
		write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db->query($sql,array($service_name,$adn))){
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
    
    public function updateService($name, $adn,$id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array ('name' => $name, 
                    'adn'=> $adn,
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
    
    public function editService($id) {
        $this->db = $this->load->database('xmp', TRUE);
        
        $this->db->select('name, adn, date_created');
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
         
    public function selectIdService() {
        $this->db = $this->load->database('xmp', TRUE);
        $sql_query  = "SELECT id FROM $this->db_table where id in(SELECT MAX(id) FROM $this->db_table) ";
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $queryData = $this->db->query($sql_query);
            
            $result = array ('result' => array ('data'  => $queryData->result_array()));
			write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ();
        }

        return $result;
    }
    
 }

?>
