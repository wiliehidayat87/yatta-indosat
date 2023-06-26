<?php

class Operator_model extends CI_Model {

    public $db,
    $db_table = "operator";

    function __construct() {
        parent::__construct();

        //   $this->load->database();
    }

    public function getOperatorId($operator_name) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT id FROM $this->db_table WHERE name='$operator_name' LIMIT 1 ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db->query($sql)) {
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getOperatorList($offset, $limit, $search = "") {
        $this->db = $this->load->database('xmp', TRUE);
        $sql_query = "SELECT a.id, a.name,a.long_name ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= ( !empty($search)) ? "WHERE (UPPER(a.name) LIKE '%" . $search . "%' OR UPPER(a.long_name) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit = (!empty($limit)) ? "LIMIT $offset, $limit " : "";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array(
                'query' => $sql_query . $sql_limit,
                'total' => $total,
                'result' => array(
                    'data' => $queryData->result_array(),
                    'total' => $totalData
                )
            );
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    function addOperator($name, $long_name) {
        $this->db = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('name' => $name,
                'long_name' => $long_name,
            );

            $this->db->insert($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    function check_operator($name, $id) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT * FROM $this->db_table WHERE name='$name' ";
        $sql .= ( !empty($id)) ? "AND id!='$id' " : "";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db->query($sql)) {
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

    function updateOperator($name, $long_name, $id) {
        $this->db = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('name' => $name,
                'long_name' => $long_name,
            );
            $this->db->where('id', $id);
            $this->db->update($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editOperator($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->select('name, long_name');
        $this->db->where('id', $id);

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db->get($this->db_table)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function deleteOperator($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $result = array();
		
		write_log("info", __METHOD__ . ", Start Query ");
        try {
            $this->db->where('id', $id);
            $this->db->delete($this->db_table);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

}

?>
