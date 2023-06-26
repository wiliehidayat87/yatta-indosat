<?php
class Adn_model extends CI_Model {
    public $db_xmp,
           $db_table = "service";
   
    public function __construct() {
		parent::__construct();
    }
    
    public function getAdnList($offset, $limit, $search = "") {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql_query  = "SELECT a.id, a.name, a.shortcode, DATE_FORMAT(date_created, '%d-%m-%Y %T') as date_created  ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= "WHERE status='1' ";
        $sql_query .= (!empty ($search)) ? "AND(UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.description) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = (!empty ($limit))?"LIMIT $offset, $limit ":"" ;

        try {
            $query     = $this->db_xmp->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_xmp->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                'query'  => $sql_query . $sql_limit,
                'total'  => $total,
                'result' => array (
                                    'data'   => $queryData->result_array(),
                                    'total'  => $totalData
                )
            );
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }
    
    public function addAdn($name, $description) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $result = array ();
        
        try {
            $data = array ('name' => $name, 
                    'description'=> $description,
                    'status'=>'1'
                    );
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
   
    public function check_adn_name($adn_name, $id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table WHERE name='$adn_name' ";
        $sql   .=(!empty($id))?"AND id!='$id' ":""; 
      	$query = $this->db_xmp->query($sql);
  	
        if ($query->num_rows() != 0) {
            return TRUE;
        }

        return FALSE;
    } 
    
    public function updateAdn($name, $description, $id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $result = array ();
        
        try {
            $data = array ('name' => $name, 
                    'description'=> $description
                    );
            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editAdn($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $this->db_xmp->select('name, description, date_created');
        $this->db_xmp->where('id', $id);

        $query = $this->db_xmp->get($this->db_table);

        return $query->result_array();
    }
  
    public function deleteAdn($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $result = array ();
        
        try {
            $data = array ('status' => '0');
            
            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
 }