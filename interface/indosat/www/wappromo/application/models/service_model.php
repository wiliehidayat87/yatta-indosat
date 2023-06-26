<?php
class Service_model extends CI_Model {
    public  $db_newwap,
            $db_xmp,
            $db_table   = "wap_site",
            $db_table2  = "service",
            $db_table3  = "adn";
   
    function __construct() {
		parent::__construct();

     //   $this->load->database();
    }
    public function getServiceList($offset, $limit, $search = "") {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        $sql_query  = "SELECT a.id, a.service, a.name, a.adn, a.mechanism, DATE_FORMAT(datecreated, '%d-%m-%Y %T') as datecreated  ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= "WHERE status='1' ";
        $sql_query .= (!empty ($search)) ? "AND (UPPER(a.service) LIKE '%" . $search . "%' OR UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.adn) LIKE '%" . $search . "%' OR UPPER(a.mechanism) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.service ASC ";
        $sql_limit  = "LIMIT $offset, $limit ";

        try {
            $query     = $this->db_newwap->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_newwap->query($sql_query . $sql_limit);
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
    
    public function readWapService(){
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql	= "SELECT * FROM ".$this->db_table2." ORDER BY id DESC";
        $query	= $this->db_xmp->query($sql);
		
        return $query->result_array();
    }
    
    public function readAdn(){
        $this->db_xmp = $this->load->database('xmp', TRUE);
        
        $sql	= "SELECT * FROM ".$this->db_table3." ORDER BY id";
        $query	= $this->db_xmp->query($sql);
		
        return $query->result_array();
    }  
    
    function addNewService($wap_service, $wap_name, $adn, $mechanism)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $result = array ();
        
        try {
            $data = array ('service'    => $wap_service,
                           'name'       => $wap_name,
                           'adn'        => $adn,
                           'mechanism'  => $mechanism,
                   );
            $this->db_newwap->set('datecreated', 'NOW()', FALSE);
            $this->db_newwap->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    function check_wap_name($wap_name)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table WHERE name=? "; 
  	$query = $this->db_newwap->query($sql, $wap_name);
  	
        if($query->num_rows() != 0)
        {
            return TRUE;
        }
      return FALSE;
              
    }
    
    public function editService($id) {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $this->db_newwap->select('service, name, adn, mechanism, datecreated');
        $this->db_newwap->where('id', $id);

        $query = $this->db_newwap->get($this->db_table);

        return $query->result_array();
    }
    
    public function updateService($id, $wap_service, $wap_name ,$adn, $mechanism)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $result = array ();
        
        try {
            $data = array ( 'service'   => $wap_service, 
                            'name'      => $wap_name,
                            'adn'       => $adn,
                            'mechanism' => $mechanism 
            );
            
            $this->db_newwap->where('id', $id);
            $this->db_newwap->set('datemodified', 'NOW()', FALSE);            
            $this->db_newwap->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function deleteService($id) {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        $result = array ();
        
        try {
            $data = array ('status' => '0');
            
            $this->db_newwap->where('id', $id);
            $this->db_newwap->set('datemodified', 'NOW()', FALSE);
            $this->db_newwap->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    } 
 }