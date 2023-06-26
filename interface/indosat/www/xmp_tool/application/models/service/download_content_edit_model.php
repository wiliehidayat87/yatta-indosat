<?php

class Download_content_edit_model extends CI_Model{
    
    public $db_wap,
           $db_xmp,
           $db_table    = "service",
           $db_table2   = "wap_content_download";
    
    function __construct() {
        parent::__construct();
    }
    
    public function updateContent($id, $c_sort, $c_contentcode, $c_title, $c_price, $c_limit, $c_idService, $sImage){
      $this->db_wap = $this->load->database('wap', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                'wap_service_download_id'   => $c_idService,
                'code'                      => $c_contentcode,
                'title'                     => $c_title,
                'image'                     => $sImage,
                'price'                     => $c_price,
                'sort'                      => $c_sort,
                'limit'                     => $c_limit,                
            );
            $this->db_wap->where('id', $id);
            $this->db_wap->update($this->db_table2, $data);

            $result = array('status' => TRUE, 'message' => '', 'id' => $id);
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;  
    }
    
    public function getDataContent($id){
        $this->db_wap = $this->load->database('wap', TRUE);
        
        $sql_query  = "SELECT a.id, a.wap_service_download_id, a.code, a.title, a.image, a.price, a.sort, a.limit ";
        $sql_query .= "FROM " . $this->db_table2 . " a ";
        $sql_query .= "WHERE a.id=".$id." ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_wap->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function checkDownloadTitle($c_code, $c_title){
        $this->db_wap = $this->load->database('wap', TRUE);

        $sql = "SELECT * FROM $this->db_table2 WHERE code=? AND title=? ";

        $data = array($c_code, $c_title);
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        $query = $this->db_wap->query($sql, $data);
        if ($query) {
            write_log("info", __METHOD__ . ", Query Success ");
            if ($query->num_rows() != 0) {
                return TRUE;
            }
            return FALSE;
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }
}
