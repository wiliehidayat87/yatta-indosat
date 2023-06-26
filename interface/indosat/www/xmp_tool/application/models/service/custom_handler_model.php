<?php

class Custom_handler_model extends CI_Model{
    public $db_xmp,
           $db_table   = 'mechanism',
           $db_table2  = 'service',
           $db_table3 = 'operator';

    function __construct() {
        parent::__construct();

        $this->db_xmp = $this->load->database('xmp', TRUE);
    }

    public function getCustomHandlerList($offset, $limit, $search="") {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = "SELECT m.id, m.pattern, m.operator_id, m.operator_id, m.service_id, m.handler, m.status, o.name as operator, s.name as service ";
        $sql_query .= "FROM $this->db_table as m ";
        $sql_query .= "LEFT JOIN ". $this->db_table2. " as s ON m.service_id = s.id ";
        $sql_query .= "LEFT JOIN ". $this->db_table3. " as o ON m.operator_id = o.id ";
        $sql_query .= "WHERE 1=1 ";
        $sql_query .= (!empty ($search)) ? "AND (UPPER(m.pattern) LIKE '%". $search. "%' OR UPPER(s.name) LIKE '%". $search. "%'
                            OR UPPER(o.name) LIKE '%". $search. "%' OR UPPER(m.handler) LIKE '%". $search. "%') " : "";
        $sql_query .= "AND handler <> 'service_custom_handler' " ;
        $sql_query .= "AND status = '1' ";
        $sql_query .= "ORDER BY m.id ASC ";
        $sql_limit  = (!empty ($limit))?"LIMIT $offset, $limit ":"" ;

         write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
         try {
            $query     = $this->db_xmp->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_xmp->query($sql_query . $sql_limit);
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

    public function getOperator() {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql	= "SELECT * FROM ".$this->db_table3." ORDER BY id";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getService() {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql	= "SELECT * FROM ".$this->db_table2." ORDER BY id";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function saveCustomHandler( $pattern, $operator, $service, $handler ) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (
                'pattern' => $pattern,
                'operator_id' => $operator,
                'service_id' => $service,
                'handler' => $handler,
                'status' => '1',
            );
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->insert($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editCustomHandler($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->select('operator_id as operator, pattern, service_id as service, handler');
        $this->db_xmp->where('id', $id);

        write_log("info", __METHOD__ . ", Start Query" );
        if ($query = $this->db_xmp->get($this->db_table)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function updateCustomHandler( $id, $pattern, $operator, $service, $handler ) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (
                'pattern' => $pattern,
                'operator_id' => $operator,
                'service_id' => $service,
                'handler' => $handler,
            );
            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function deleteCustomHandler($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array (
                'status' => '0',
            );
            $this->db_xmp->where('id', $id);
            $this->db_xmp->update($this->db_table, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        }
        catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

}

