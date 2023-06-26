<?php
class Content_model extends CI_Model {
    public  $db_newwap,
            $db_xmp,
            $db_table   = "wap_service_content",
            $db_table2  = "wap_site",
            $db_table3  = "wap_content";
   
    function __construct() {
		parent::__construct();

     //   $this->load->database();
    }
    public function getContentList($offset, $limit, $search = "") {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        $sql_query  = "select a.id, a.site_id, a.code, a.price, a.sort, b.name as site_id, c.title as code_title FROM ".$this->db_table." as a  ";
        $sql_query .= "LEFT JOIN ".$this->db_table2." as b ON a.site_id = b.id ";
        $sql_query .= "LEFT JOIN ".$this->db_table3." as c ON a.code = c.code ";
        $sql_query .= "WHERE a.status='1' ";
        $sql_query .= (!empty ($search)) ? "AND (UPPER(b.name) LIKE '%" . $search . "%' OR UPPER(c.title) LIKE '%" . $search . "%' OR UPPER(a.code) LIKE '%" . $search . "%' OR UPPER(a.price) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY b.name ASC, a.sort ASC ";
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
    
    public function readWapSite(){
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $sql    = "SELECT * FROM ".$this->db_table2." ";
        $sql   .= "WHERE status='1' ";
        $sql   .= "ORDER BY id ";
        $query	= $this->db_newwap->query($sql);
		
        return $query->result_array();
    }
    
    public function readWapContent(){
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $sql	= "SELECT * FROM ".$this->db_table3." ";
        $sql   .= "ORDER BY id ";
        $query	= $this->db_newwap->query($sql);
		
        return $query->result_array();
    }  
    
    function addNewContent($site, $code, $price)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $result = array ();
        
        $sql        = "SELECT * FROM ".$this->db_table." WHERE site_id = $site AND status = '1' ";
        $query      = $this->db_newwap->query($sql);
        $sort_num   = $query->num_rows();

        try {
            $data = array ('site_id'       => $site,
                           'code'          => $code,
                           'price'         => $price,
                           'status'        => '1'
                   );
            $this->db_newwap->set('sort', $sort_num + '1', FALSE);
            $this->db_newwap->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    function check_content_code($code, $site)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $sql   = "SELECT * FROM $this->db_table WHERE code=? AND site_id=? AND status = '1' "; 
  	$query = $this->db_newwap->query($sql, array($code, $site));
  	
        if($query->num_rows() != 0)
        {
            return TRUE;
        }
      return FALSE;
              
    }
    
    public function editContent($id) {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $this->db_newwap->select('site_id, code, price, sort');
        $this->db_newwap->where('id', $id);

        $query = $this->db_newwap->get($this->db_table);

        return $query->result_array();
    }
    
    public function updateContent($id, $site, $code, $price, $sort, $sort_old)
    {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
        $result = array ();        
        
        if ($sort==$sort_old){
            try {
                $data = array ( 'site_id'   => $site, 
                                'code'      => $code,
                                'price'     => $price,
                                'sort'      => $sort
                );

                $this->db_newwap->where('id', $id);                        
                $this->db_newwap->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
            }

            catch (Exception $e) {
                $result = array ('status' => FALSE, 'message' => $e->getMessage());
            }
            
        }else{
            $sql   = "SELECT id FROM ".$this->db_table." WHERE sort=? AND site_id=?"; 
            $query = $this->db_newwap->query($sql, array($sort, $site));
            $data  = $query->result_array();
            
            foreach ($data as $_data){
                $id_old = $_data['id'];
            }
            
            if($query->num_rows() != 0)
            {
                try {
                $data = array ( 
                                'sort'      => $sort_old
                );

                $this->db_newwap->where('id', $id_old);                        
                $this->db_newwap->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
                }

                catch (Exception $e) {
                    $result = array ('status' => FALSE, 'message' => $e->getMessage());
                }
            }
            
            try {
                $data = array ( 'site_id'   => $site, 
                                'code'      => $code,
                                'price'     => $price,
                                'sort'      => $sort
                );

                $this->db_newwap->where('id', $id);                        
                $this->db_newwap->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
            }

            catch (Exception $e) {
                $result = array ('status' => FALSE, 'message' => $e->getMessage());
            }
        }
        return $result;
    }
    
    public function deleteContent($id) {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        $result = array ();
        
        try {
            $data = array ('status' => '0');
            
            $this->db_newwap->where('id', $id);
            $this->db_newwap->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    } 
 }