<?php

class History_model extends CI_Model {

    public $db;

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('xmp', TRUE);
    }

    public function getOperator() {
        $this->db = $this->load->database('xmp', TRUE);

        write_log("info", __METHOD__ . ", Start Query");

        if ($query = $this->db->get('operator')) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getService() {
        $this->db = $this->load->database('xmp', TRUE);

        write_log("info", __METHOD__ . ", Start Query");
        $this->db->select('distinct(name)');
        if ($query = $this->db->get('service')) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getAdn() {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->select('DISTINCT adn', FALSE);
        write_log("info", __METHOD__ . ", Start Query");

        if ($query = $this->db->get('service')) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getSubject() {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->select('DISTINCT SUBJECT', FALSE);
        write_log("info", __METHOD__ . ", Start Query");

        if ($query = $this->db->get('tbl_msgtransact')) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getSearch($data = array()) {
        $this->db = $this->load->database('xmp', TRUE);

        $param = array();
        if ($data['date'] != '') {
            $param[] = "MSGTIMESTAMP = '" . $data['date'] . "' ";
        }
        if ($data['adn'] != '') {
            $param[] = "ADN = '" . $data['adn'] . "' ";
        }
        if ($data['msisdn'] != '') {
            $param[] = "MSISDN = '" . $data['msisdn'] . "' ";
        }
        if ($data['msgdata'] != '') {
            $param[] = "MSGDATA = '" . $data['msgdata'] . "' ";
        }
        if ($data['operator'] != '') {
            $param[] = "OPERATORID = '" . $data['operator'] . "' ";
        }
        if ($data['service'] != '') {
            $param[] = "SERVICE = '" . $data['service'] . "' ";
        }
        if ($data['subject'] != '') {
            $param[] = "SUBJECT = '" . $data['subject'] . "' ";
        }
        $sql_query = "SELECT trx.*, opt.name operator FROM tbl_msgtransact trx ";
        $sql_query .= "LEFT JOIN operator opt ON opt.id = trx.OPERATORID ";
        if (count($param) > 0) {
            $sql_query .= "WHERE " . implode("AND ", $param);
        }
        $sql_query .= "GROUP BY MSGTIMESTAMP, MSISDN, SUBJECT ";
        $sql_query .= "ORDER BY MSGTIMESTAMP DESC ";
	if (!$data['limit']) $data['limit'] = 5;
        $sql_limit .= "LIMIT " . $data['offset'] . ", " . $data['limit'];

        write_log("info", __METHOD__ . ", Start Query");
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

    public function getTotalSearch($adn, $msisdn, $msgdata, $operator, $service, $date) {
        $this->db = $this->load->database('xmp', TRUE);

        if ($date != '') {
            $this->db->like('MSGTIMESTAMP', $date);
        }
        if ($adn != '') {
            $this->db->where('ADN', $adn);
        }
        if ($msisdn != '') {
            $this->db->where('MSISDN', $msisdn);
        }
        if ($msgdata != '') {
            $this->db->like('MSGDATA', $msgdata);
        }
        if ($operator != '') {
            $this->db->where('OPERATORID', $operator);
        }
        if ($service != '') {
            $this->db->where('SERVICE', $service);
        }

        $this->db->from('tbl_msgtransact');
        $this->db->join('operator', 'operator.id = tbl_msgtransact.OPERATORID');
        $this->db->order_by('MSGTIMESTAMP', 'desc');

        write_log("info", __METHOD__ . ", Start Query");

        if ($query = $this->db->get()) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function checkPassword($userid) {
        $this->db = $this->load->database('xmp', TRUE);

        $query = $this->db->get_where('users', array('id_user' => $userid))->result();
        if ($query) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query;
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return false;
        }
    }

    public function updatePassword($userid, $newpwd) {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->where('id_user', $userid);

        if ($this->db->update('users', array('password' => $newpwd))) {
            write_log("info", __METHOD__ . ", Query Success ");
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
        }
    }

}
