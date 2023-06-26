<?php
class Adn_model extends CI_Model {
    public $db_xmp,
           $db_table = "adn";
   
    public function __construct() {
		parent::__construct();
    }
    
    public function getAdnList($offset, $limit, $search = "") {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql_query  = "SELECT a.id, a.name,a.description,DATE_FORMAT(date_created, '%d-%m-%Y %T') as date_created  ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= "WHERE status='1' ";
        $sql_query .= (!empty ($search)) ? "AND(UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.description) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = (!empty ($limit))?"LIMIT $offset, $limit ":"" ;
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query     = $this->db_xmp->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_xmp->query($sql_query . $sql_limit);
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
    
    public function addAdn($name, $description) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array ('name' => $name, 
                    'description'=> $description,
                    'status'=>'1'
                    );
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
             write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
   
    public function check_adn_name($adn_name, $id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table WHERE name='$adn_name' ";
        $sql   .=(!empty($id))?"AND id!='$id' ":""; 
      	
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db_xmp->query($sql)){
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
    
    public function updateAdn($name, $description, $id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array ('name' => $name, 
                    'description'=> $description
                    );
            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editAdn($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $this->db_xmp->select('name, description, date_created');
        $this->db_xmp->where('id', $id);
        
        write_log("info", __METHOD__ . ", Start Query ");
        if($query = $this->db_xmp->get($this->db_table)){
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        }
        else{
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
          
    }
  
    public function deleteAdn($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
                
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array ('status' => '0');
            
            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
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
