<?php

class Handler_model extends CI_Model {

    public $db_xmp,
    $db_table = 'custom_handler';

    public function __construct() {
        parent::__construct();
        $this->db_xmp = $this->load->database('xmp', TRUE);
    }

    public function getHandlerList($params) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $offset = $params['offset'];
        $limit = $params['limit'];
        $search = $params['search'];

        $sql_query = "SELECT a.id, a.name, a.description, a.status ";
        $sql_query .= sprintf("FROM %s a ", $this->db_table);
        $sql_query .= "WHERE a.status != '0' ";
        $sql_query .= ( !empty($search)) ? sprintf("AND (UPPER(a.name) LIKE '%s' OR UPPER(a.description) LIKE '%s') ", "%" . $search . "%", "%" . $search . "%") : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit = (!empty($limit)) ? sprintf("LIMIT %d, %d ", $offset, $limit) : "";

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
        } catch (Exception $e) {
            $result = array();
        }

        return $result;
    }

    public function saveHandler($handler_name, $handler_description) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        try {
            $data = array(
                'name' => $handler_name,
                'description' => $handler_description,
                'status' => '1'
            );
            $this->db_xmp->set('date_created', 'NOW()', FALSE);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->insert($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
        } catch (Exception $e) {
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editHandler($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->select('name, description');
        $this->db_xmp->where('id', $id);

        $query = $this->db_xmp->get($this->db_table);

        return $query->result_array();
    }

    public function updateHandler($id, $handler_name, $handler_description, $status) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        try {
            $data = array(
                'name' => $handler_name,
                'description' => $handler_description,
            );
            if ($status == "restore")
                $data['status'] = '1';

            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
        } catch (Exception $e) {
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function deleteHandler($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $result = array();

        try {
            $data = array(
                'status' => '0',
            );

            $this->db_xmp->where('id', $id);
            $this->db_xmp->set('date_modified', 'NOW()', FALSE);
            $this->db_xmp->update($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
        } catch (Exception $e) {
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    function check_handler($handler_name, $id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "SELECT * FROM $this->db_table WHERE lower(name)='$handler_name' ";
        $sql .= ( !empty($id)) ? "AND id !='$id' " : "";
        $query = $this->db_xmp->query($sql);
        $status = array();
        if ($query->num_rows() != 0) {
            foreach ($query->result_array() as $row) {
                $status['status'] = $row['status'];
                $status['id'] = $row['id'];
            }
            if ($status['status'] == 0) {
                $status['status'] = "restore";
                return $status;
            } else {
                $status['status'] = "name_already_exist";
                return $status;
            }
        }

        $status['status'] = "add";
        return $status;
    }

}

?>
