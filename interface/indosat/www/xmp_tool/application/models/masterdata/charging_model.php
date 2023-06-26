<?php

class Charging_model extends CI_Model{
    public $db_xmp,
           $db_table1 = 'charging',
           $db_table2 = 'operator',
           $db_table3 = 'adn';
   
    public function __construct() {
        parent::__construct();
        
        $this->db_xmp = $this->load->database('xmp', TRUE);
    }
    
    public function getChargingList($offset = 0, $limit = 0, $search = "") {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql_query  = "SELECT a.id, a.operator, a.adn, a.charging_id, a.gross, a.netto, a.username, a.password, a.sender_type, a.message_type, b.name as operator, c.name as adn FROM ".$this->db_table1." as a ";
        $sql_query .= "LEFT JOIN ".$this->db_table2." as b ON a.operator=b.id ";
        $sql_query .= "LEFT JOIN ".$this->db_table3." as c ON a.adn=c.name ";
        $sql_query .= (!empty($search)) ? "WHERE (UPPER(a.adn) LIKE '%" . $search . "%' OR UPPER(a.charging_id) LIKE '%" . $search . "%' 
                      OR UPPER(a.gross) LIKE '%" . $search . "%' OR UPPER(a.netto) LIKE '%" . $search . "%' OR UPPER(a.sender_type) LIKE '%" . $search . "%' 
                      OR UPPER(a.message_type) LIKE '%" . $search . "%' OR UPPER(b.name) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.operator ";
        $sql_limit  = "LIMIT $offset, $limit ";
         
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

    public function readOperator(){
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql	= "SELECT * FROM ".$this->db_table2." ORDER BY id";
                
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
          
    }
    
    public function readAdn(){
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql	= "SELECT * FROM ".$this->db_table3." ORDER BY id";
        $query	= $this->db_xmp->query($sql);
		
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
        
        
    }    

    public function saveCharging($operator, $adn, $charging_id, $gross, $netto, $username, $password, $sender_type, $message_type) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (
                'operator'      => $operator,
                'adn'           => $adn,
                'charging_id'   => $charging_id,
                'gross'         => $gross,
                'netto'         => $netto,
                'username'      => $username,
                'password'      => $password,
                'sender_type'   => $sender_type,
                'message_type'  => $message_type,
            );
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->insert($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editCharging($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $this->db_xmp->select('operator, adn, charging_id, gross, netto, username, password, sender_type, message_type');
        $this->db_xmp->where('id', $id);

        write_log("info", __METHOD__ . ", Start Query" );
        if ($query = $this->db_xmp->get($this->db_table1)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
        
        
    }

    public function updateCharging($id, $operator, $adn, $charging_id, $gross, $netto, $username, $password, $sender_type, $message_type) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (
                'operator'      => $operator,
                'adn'           => $adn,
                'charging_id'   => $charging_id,
                'gross'         => $gross,
                'netto'         => $netto,
                'username'      => $username,
                'password'      => $password,
                'sender_type'   => $sender_type,
                'message_type'  => $message_type
            );

            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function deleteCharging($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $this->db_xmp->where('id', $id);
            $this->db_xmp->delete($this->db_table1);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function checkCharging($adn, $charging_id, $operator,$id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table1 WHERE adn='$adn' AND charging_id='$charging_id' AND operator='$operator' ";
        $sql  .= (!empty($id))?"AND id!='$id' ":"";
      	
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
}
?>
