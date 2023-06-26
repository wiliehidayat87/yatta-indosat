<?php

class Download_edit_model extends CI_Model{
    
    public $db_wap,
           $db_xmp,
           $db_table    = "service",
           $db_table2   = "wap_service_download";
    
    function __construct() {
        parent::__construct();
    }
    
    public function updateService($id,$d_name,$d_title,$d_disclaim,$d_desc,$d_type,$sHeader1,$sHeader2, $sHeader3,$sFooter1, $sFooter2, $sFooter3,$sPromo1, $sPromo2, $sPromo3,$sBg1, $sBg2, $sBg3){
      $this->db_wap = $this->load->database('wap', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                'service_id'    => $d_name,
                'title'         => $d_title,
                'type'          => $d_type,
                'disclaimer'    => $d_disclaim,
                'description'   => $d_desc,
                'header1'       => $sHeader1,
                'header2'       => $sHeader2,
                'header3'       => $sHeader3,
                'footer1'       => $sFooter1,
                'footer2'       => $sFooter2,
                'footer3'       => $sFooter3,
                'promo1'        => $sPromo1,
                'promo2'        => $sPromo2,
                'promo3'        => $sPromo3,
                'background1'   => $sBg1,
                'background2'   => $sBg2,
                'background3'   => $sBg3,
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
    
    public function getDataService($id){
        $this->db_wap = $this->load->database('wap', TRUE);
        
        $sql_query  = "SELECT a.id, a.service_id, a.title, a.type, a.disclaimer, a.description, ";
        $sql_query .= "a.header1, a.header2, a.header3, a.footer1, a.footer2, a.footer3, "; 
        $sql_query .= "a.promo1, a.promo2, a.promo3, a.background1, a.background2, a.background3 ";
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
    
    public function getNameService($idService){
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query  = "SELECT DISTINCT a.id, a.name, a.adn ";
        $sql_query .= "FROM " . $this->db_table . " a ";
        $sql_query .= "WHERE a.id=".$idService." ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmp->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function checkDownloadTitle($d_name, $d_title){
        $this->db_wap = $this->load->database('wap', TRUE);

        $sql = "SELECT * FROM $this->db_table2 WHERE service_id=? AND title=? ";

        $data = array($d_name, $d_title);
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
    
    public function getNameList() {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query  = "SELECT DISTINCT a.id, a.name, a.adn ";
        $sql_query .= "FROM " . $this->db_table . " a ";
        $sql_query .= "ORDER BY a.name ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmp->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function getTypeList() {
        $this->db_wap = $this->load->database('wap', TRUE);

        $sql    = "SHOW COLUMNS FROM `wap_service_download` WHERE field = 'type' ";
      
        try {
            $query  = $this->db_wap->query($sql);
                       
            $data=$query->result_array();
            $result = str_replace(array("enum('", "')", "''"), array('', '', "'"), $data[0]['Type']);
            $result = explode("','", $result);
        }
        
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }
}
