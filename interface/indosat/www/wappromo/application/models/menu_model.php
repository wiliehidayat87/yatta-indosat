<?php
class Menu_model extends CI_Model {
	function __construct() {
		parent::__construct();

        $this->load->database('smswebtool');
	}

    public function getMenuList($offset, $limit, $search = "") {
        $sql_query  = "SELECT a.menu, a.link, a.sort, a.status, b.menu as parent FROM wp_menu as a ";
        $sql_query .= "LEFT JOIN wp_menu as b on a.parent=b.id ";
        $sql_query .= (!empty ($search)) ? "WHERE (UPPER(a.menu) LIKE '%" . $search . "%' OR UPPER(a.link) LIKE '%" . $search . "%' OR UPPER(a.sort) LIKE '%" . $search . "%' OR UPPER(a.status) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = "LIMIT $offset, $limit ";

        try {
            $query     = $this->db->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
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
}