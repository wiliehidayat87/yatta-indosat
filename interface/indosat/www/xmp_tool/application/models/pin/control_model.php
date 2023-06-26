<?php

class Control_model extends CI_Model {

    public $db_xmp,
            $db_table1 = 'spring_control_pin',
            $db_table2 = 'operator';

    public function __construct() {
        parent::__construct();

        $this->db_xmp = $this->load->database('xmp', TRUE);
    }

    public function getControlList($offset = 0, $limit = 0, $search = "") {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = "SELECT * FROM " . $this->db_table1 . " ";
        $sql_query .= (!empty($search)) ? "WHERE (UPPER(b.name) LIKE '%" . $search . "%' OR UPPER(name) LIKE '%" . $search . "%' 
                      OR UPPER(desc) LIKE '%" . $search . "%' OR UPPER(active) LIKE '%" . $search . "%' OR UPPER(mon) LIKE '%" . $search . "%' 
                      OR UPPER(tue) LIKE '%" . $search . "%' OR UPPER(wed) LIKE '%" . $search . "%' OR UPPER(thu) LIKE '%" . $search . "%' OR UPPER(fri) LIKE '%" . $search . "%' OR UPPER(sat) LIKE '%" . $search . "%' OR UPPER(sun) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY active DESC ";
        $sql_limit = "LIMIT $offset, $limit ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmp->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db_xmp->query($sql_query . $sql_limit);
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
            write_log("info", __METHOD__ . ", Query Failed " . $e->getMessage());
            $result = array();
        }

        return $result;
    }

    public function readOperator() {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT * FROM " . $this->db_table2 . " ORDER BY id";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        $query = $this->db_xmp->query($sql);
        if ($query) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function saveControl($data = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $param = array(
                'operator' => $data['operator'],
                'name' => $data['name'],
                'desc' => $data['desc'],
                'active' => $data['active'],
                'mon' => $data['mon'],
                'tue' => $data['tue'],
                'wed' => $data['wed'],
                'thu' => $data['thu'],
                'fri' => $data['fri'],
                'sat' => $data['sat'],
                'sun' => $data['sun'],
            );
            $this->db_xmp->insert($this->db_table1, $param);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editControl($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->where('id', $id);

        write_log("info", __METHOD__ . ", Start Query");
        $query = $this->db_xmp->get($this->db_table1);
        if ($query) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function updateControl($data = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $param = array(
                'operator' => $data['operator'],
                'name' => $data['name'],
                'desc' => $data['desc'],
                'active' => $data['active'],
                'mon' => $data['mon'],
                'tue' => $data['tue'],
                'wed' => $data['wed'],
                'thu' => $data['thu'],
                'fri' => $data['fri'],
                'sat' => $data['sat'],
                'sun' => $data['sun']
            );

            $this->db_xmp->where('id', $data['id']);
            $this->db_xmp->update($this->db_table1, $param);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed " . $e->getMessage());
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function updateStatusByOperator($data = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $param = array(
                'active' => '0'
            );

            $this->db_xmp->where('operator', $data['operator']);
            $this->db_xmp->update($this->db_table1, $param);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed " . $e->getMessage());
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function deleteControl($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $this->db_xmp->where('id', $id);
            $this->db_xmp->delete($this->db_table1);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed " . $e->getMessage());
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function checkControl($data = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT * FROM $this->db_table1 WHERE operator='" . $data['operator'] . "' AND name='" . $data['name'] . "' ";
        $sql .= (!empty($id)) ? "AND id!='$id' " : "";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        $query = $this->db_xmp->query($sql);
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