<?php

class Download_content_model extends CI_Model{
    
    public  $db,
            $db_wap     = "newwap",
            $db_xmp     = "xmp_new",
            $db_table   = "wap_content_download",
            $db_table2  = "service";
    
    function __construct() {
        parent::__construct();
    }
    
    public function getDownloadContentList($id, $offset, $limit, $search = "") {
        $this->db   = $this->load->database('wap', TRUE);

        $sql_query  = "SELECT a.id, a.wap_service_download_id, a.code, a.title, a.image, a.price, a.sort, a.limit ";
        $sql_query .= "FROM ".$this->db_table." a ";
        $sql_query .= (!empty($id) ||!empty ($search))?"WHERE ":"";
        $sql_query .= (!empty($id))?"a.wap_service_download_id='$id' ":"";
        $sql_query .= (!empty($id) && !empty($search))?"AND ":"";            
        $sql_query .= (!empty ($search)) ? "(UPPER(a.code) LIKE '%" . $search . "%' OR UPPER(a.title) LIKE '%" . $search . " %'OR UPPER(a.image) LIKE '%" . $search . "%'OR UPPER(a.price) LIKE '%" . $search . "%'OR UPPER(a.limit) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.sort ";
        $sql_limit  = "LIMIT $offset, $limit ";
	
        error_log(print_r($sql_query,1),3,'/tmp/content');
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
    
    public function deleteDownloadContent($id) {
        $this->db   = $this->load->database('wap', TRUE);

        write_log("info", __METHOD__.", Start Query ");   
        try {
            $this->db->where('id', $id);
            $this->db->delete($this->db_table);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__.", Query Success ");
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
            write_log("info", __METHOD__.", Query Failed ");
        }

        return $result;
    }

}
