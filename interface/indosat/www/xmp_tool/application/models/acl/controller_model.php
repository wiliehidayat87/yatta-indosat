<?php

class Controller_model extends CI_Model {

    public $db,
    $db_table = "menu";

    public function __construct() {
        parent::__construct();
    }

    public function getControllerList($offset, $limit, $search = "") {
        $this->db = $this->load->database('default', TRUE);

        $sql_query = "SELECT a.id, a.menu, a.link, a.sort, a.status, b.menu as parent FROM " . $this->db_table . " as a ";
        $sql_query .= "LEFT JOIN " . $this->db_table . " as b on a.parent=b.id ";
        $sql_query .= ( !empty($search)) ? "WHERE (UPPER(a.menu) LIKE '%" . $search . "%' OR UPPER(a.link) LIKE '%" . $search . "%' OR UPPER(a.sort) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.parent ASC, a.sort ";
        $sql_limit = "LIMIT $offset, $limit ";

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

    public function readParentList() {
        $this->db = $this->load->database('default', TRUE);

        $sql = "SELECT * FROM " . $this->db_table . " ";
        $sql .= "WHERE parent='0' ";
        $sql .= "ORDER BY id ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function check_menu_name($menu_name) {
        $this->db = $this->load->database('default', TRUE);

        $sql = "SELECT * FROM " . $this->db_table . " WHERE menu=? ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, array($menu_name))) {
            write_log("info", __METHOD__ . ", Query Success ");
            if ($query->num_rows() != 0) {
                return TRUE;
            }
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }

        return FALSE;
    }

    public function addController($menu_name, $parent, $controller_link, $status) {
        $this->db = $this->load->database('default', TRUE);

        $result = array();

        $sql = "SELECT * FROM " . $this->db_table . " WHERE parent = $parent ";
        $query = $this->db->query($sql);
        $sort_num = $query->num_rows();

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        try {
            $data = array('menu' => $menu_name,
                'parent' => $parent,
                'link' => $controller_link,
                'status' => $status
            );
            $this->db->set('sort', $sort_num + '1', FALSE);
            $this->db->insert($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editController($id) {
        $this->db = $this->load->database('default', TRUE);

        $this->db->select('menu, parent, link, sort, status');
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

    public function updateController($id, $menu_name, $parent, $controller_link, $sort, $sort_old, $status) {
        $this->db = $this->load->database('default', TRUE);

        $result = array();

        if ($sort == $sort_old) {
            write_log("info", __METHOD__ . ", Start Query ");
            try {
                $data = array('menu' => $menu_name,
                    'parent' => $parent,
                    'link' => $controller_link,
                    'sort' => $sort,
                    'status' => $status
                );

                $this->db->where('id', $id);
                $this->db->update($this->db_table, $data);

                $result = array('status' => TRUE, 'message' => '');
                write_log("info", __METHOD__ . ", Query Success ");
            } catch (Exception $e) {
                write_log("info", __METHOD__ . ", Query Failed ");
                $result = array('status' => FALSE, 'message' => $e->getMessage());
            }
        } else {
            $sql = "SELECT id FROM " . $this->db_table . " WHERE sort=? AND parent=?";

            write_log("info", __METHOD__ . ", Start Query ");
            $query = $this->db->query($sql, array($sort, $parent));
            $data = $query->result_array();

            foreach ($data as $_data) {
                $id_old = $_data['id'];
            }

            if ($query->num_rows() != 0) {
                try {
                    $data = array(
                        'sort' => $sort_old
                    );

                    $this->db->where('id', $id_old);
                    $this->db->update($this->db_table, $data);

                    $result = array('status' => TRUE, 'message' => '');
                    write_log("info", __METHOD__ . ", Query Success ");
                } catch (Exception $e) {
                    write_log("info", __METHOD__ . ", Query Failed ");
                    $result = array('status' => FALSE, 'message' => $e->getMessage());
                }
            }

            try {
                $data = array('menu' => $menu_name,
                    'parent' => $parent,
                    'link' => $controller_link,
                    'sort' => $sort,
                    'status' => $status
                );

                $this->db->where('id', $id);
                $this->db->update($this->db_table, $data);

                $result = array('status' => TRUE, 'message' => '');

                write_log("info", __METHOD__ . ", Query Success ");
            } catch (Exception $e) {
                write_log("info", __METHOD__ . ", Query Failed ");
                $result = array('status' => FALSE, 'message' => $e->getMessage());
            }
        }
        return $result;
    }

    public function deleteController($id) {
        $this->db = $this->load->database('default', TRUE);
        
        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('status' => '0');

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

}
