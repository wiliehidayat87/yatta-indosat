<?php

class Download_model extends CI_Model{
    
    public  $db,
            $db_wap     = "newwap",
            $db_xmp     = "xmp_new",
            $db_table   = "wap_service_download",
            $db_table2  = "service";
    
    function __construct() {
        parent::__construct();
    }
    
    public function getDownloadPageList($offset, $limit, $search = "") {
        $this->db   = $this->load->database('wap', TRUE);
        
        $sql_query  = "SELECT a.id, a.service_id, a.title, a.type, b.name ";
        $sql_query .= "FROM $this->db_wap.$this->db_table a LEFT JOIN ";
        $sql_query .= "$this->db_xmp.$this->db_table2 b ON b.id = a.service_id ";
        $sql_query .= (!empty ($search)) ? "WHERE (UPPER(a.title) LIKE '%" . $search . "%' OR UPPER(a.type) LIKE '%" . $search . " %'OR UPPER(b.name) LIKE '%" . $search . "%' ) " : "";
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
    
    public function deleteDownload($id) {
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
