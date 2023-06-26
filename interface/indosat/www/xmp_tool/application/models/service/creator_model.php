<?php

class Creator_model extends CI_Model {

    public $db,
    $db_table = "mechanism",
    $db_table2 = "operator",
    $db_table3 = "service";

    function __construct() {
        parent::__construct();
    }

    public function getCreatorList($offset, $limit, $search = "", $serviceid = "", $operatorid = "") {
        $this->db = $this->load->database('xmp', TRUE);
        if ($limit < 1)
            $limit = 5;

        $sql_query = "SELECT a.id, a.pattern, a.date_created, a.handler, b.name operator_name, c.name service_name, c.id as service_id ";
        $sql_query .= "FROM $this->db_table a ";
        $sql_query .= "INNER JOIN $this->db_table2 b ON a.operator_id = b.id ";
        $sql_query .= "INNER JOIN $this->db_table3 c ON a.service_id = c.id ";
        $sql_query .= (!empty($search) or !empty($search) or !empty($search) ) ? "WHERE" : "";

        $sql_and .= (!empty($search)) ? "AND (UPPER(a.pattern) LIKE '%" . $search . "%' OR UPPER(b.name) LIKE '%" . $search . "%' OR UPPER(c.name) LIKE '%" . $search . "%' ) " : "";
        $sql_and .= (!empty($serviceid)) ? "AND a.service_id='" . $serviceid . "' " : "";
        $sql_and .= (!empty($operatorid)) ? "AND a.operator_id='" . $operatorid . "' " : "";

        $sql_and = ltrim($sql_and, "AND");

        $sql_query = $sql_query . $sql_and;

        $sql_query .= "GROUP BY c.name ";
        $sql_query .= "ORDER BY a.date_created DESC ";

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

    public function addNewCreator($param) {
        $this->db = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('pattern' => $param['pattern'],
                'operator_id' => $param['operator_id'],
                'service_id' => $param['service_id']
            );
            $this->db->set('date_created', 'NOW()', FALSE);
            $this->db->insert($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function check_creator_name($param) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT * FROM $this->db_table WHERE pattern=? AND operator_id=? AND service_id=?";

        $data = array($param['pattern'], $param['operator_id'], $param['service_id']);
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, $data)) {
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

    public function updateCreator($param) {
        $this->db = $this->load->database('xmp', TRUE);

        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('pattern' => $param['pattern'],
                'operator_id' => $param['operator_id'],
                'service_id' => $param['service_id']
            );
            $this->db->where('id', $param['id']);
            $this->db->set('date_modified', 'NOW()', FALSE);
            $this->db->update($this->db_table, $data);

            $result = array('status' => TRUE, 'message' => '', 'id' => $param['id']);
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editCreator($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->select('pattern, operator_id, service_id');
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

    public function selectIdCreator() {
        $this->db = $this->load->database('xmp', TRUE);
        $sql_query = "SELECT id FROM $this->db_table where id in(SELECT MAX(id) FROM $this->db_table) ";

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $queryData = $this->db->query($sql_query);

            $result = array('result' => array('data' => $queryData->result_array()));
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    public function getOperatorList($arr = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = "SELECT a.id, a.name ";
        $sql_query .= "FROM operator a ";
        $sql_query .= "ORDER BY a.name ";

        try {
            $query = $this->db_xmp->query($sql_query);
            //$total  = $query->num_rows();
            $result = $query->result_array();
        } catch (Exception $e) {
            $result = array();
        }

        return $result;
    }

    public function getServiceList($arr = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = "SELECT a.id, a.name ";
        $sql_query .= "FROM service a ";
        $sql_query .= "ORDER BY a.name ";

        try {
            $query = $this->db_xmp->query($sql_query);
            //$total  = $query->num_rows();
            $result = $query->result_array();
        } catch (Exception $e) {
            $result = array();
        }

        return $result;
    }

    public function getAdnList($arr = array()) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = "SELECT a.id, a.name ";
        $sql_query .= "FROM adn a ";
        $sql_query .= "ORDER BY a.name ";

        try {
            $query = $this->db_xmp->query($sql_query);
            //$total  = $query->num_rows();
            $result = $query->result_array();
        } catch (Exception $e) {
            $result = array();
        }

        return $result;
    }

    public function insertMechanism($pattern, $operator_id, $service_id, $handler, $status) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "INSERT INTO `mechanism` (`id` ,`pattern` ,`operator_id` ,`service_id` ,`handler` ,`date_created` ,`date_modified` ,`status`)
                VALUES (NULL , ?, ?, ?, ?, NOW(), NOW(), ?)";

        $result = $this->db_xmp->query($sql, array($pattern, $operator_id, $service_id, $handler, $status));

        if ($result !== FALSE)
            return $this->db_xmp->insert_id();

        return FALSE;
    }

    public function updateMechanism($pattern, $operator_id, $service_id, $handler, $status, $mechanism_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "UPDATE `mechanism`
                SET `pattern` = '".$pattern."' ,`operator_id` = '".$operator_id."' ,`service_id` = '".$service_id."' ,
                `handler` = '".$handler."' ,`date_modified` = NOW() ,`status` = '".$status."'
                WHERE id='".(int)$mechanism_id."'";

        $result = $this->db_xmp->query($sql);
        if ($result !== FALSE)
            return TRUE;

        return FALSE;
    }

    public function getModuleIdByName($module_name) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT id FROM module WHERE name='$module_name' LIMIT 1 ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db_xmp->query($sql)) {
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function insertReply($mechanism_id, $module_id, $subject, $message, $charging_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "INSERT INTO `reply` (`mechanism_id` ,`module_id` ,`id` ,`subject` ,`message` ,`charging_id`)
                VALUES (?, ?, NULL , ?, ?, ?)";

        $result = $this->db_xmp->query($sql, array($mechanism_id, $module_id, $subject, $message, $charging_id));

        if ($result !== FALSE)
            return $this->db_xmp->insert_id();

        return FALSE;
    }

    public function insertReplyAttribute($attribute_id, $value, $reply_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "INSERT INTO `reply_attribute` (`id` ,`attribute_id` ,`value` ,`reply_id`)
                VALUES (NULL, ?, ?, ?)";

        $result = $this->db_xmp->query($sql, array($attribute_id, $value, $reply_id));

        if ($result !== FALSE)
            return $this->db_xmp->insert_id();

        return FALSE;
    }

    public function getListKeywordsByServiceId($service_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT id, pattern FROM mechanism WHERE service_id='" . (int) $service_id . "' GROUP BY pattern ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db_xmp->query($sql)) {
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getListKeywordsByPattern($pattern, $service_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT a.id, a.pattern, a.operator_id, b.name operator_name FROM mechanism a
            INNER JOIN operator b ON a.operator_id=b.id
            WHERE a.pattern='" . $pattern . "' AND service_id='" . (int) $service_id . "' ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db_xmp->query($sql)) {
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getServiceById($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT * FROM service WHERE id='" . (int) $id . "' LIMIT 1 ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db_xmp->query($sql)) {
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getOperatorByServiceId($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql = "SELECT DISTINCT o.name FROM mechanism m, operator o WHERE m.service_id='" . (int) $id . "' AND m.operator_id = o.id ";

        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db_xmp->query($sql)) {
            return $query->result();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getCharging($operator, $adn, $module) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        if ($module == 'wappush')
            $sql = "SELECT c.*, o.name FROM charging c, operator o WHERE o.name='" . $operator . "' AND c.adn='" . $adn . "' AND c.message_type='wappush' AND c.operator=o.id";
        else
            $sql = "SELECT c.*, o.name FROM charging c, operator o WHERE o.name='" . $operator . "' AND c.adn='" . $adn . "' AND c.message_type!='wappush' AND c.operator=o.id";
        //echo $sql;

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getCustomHandler() {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $sql = "SELECT * FROM custom_handler ORDER BY name asc";
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db_xmp->query($sql)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function delKeywordById($id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->trans_start();
        $sql = "SELECT id FROM `mechanism` WHERE id = ?";
        $query = $this->db_xmp->query($sql, array($pattern, $service_id));
        $result_mecha = $query->result_array();

        if(count($result_mecha) > 0) {
            foreach($result_mecha as $mechanism) {
                $mechanism_id = $mechanism['id'];

                $sql = "SELECT id FROM `reply` WHERE mechanism_id = ?";
                $query = $this->db_xmp->query($sql, array($mechanism_id));
                $result = $query->result_array();

                if(count($result) > 0) {
                    foreach ($result as $reply) {
                        $reply_id =  (int)$reply['id'];
                        $sql = "SELECT id FROM `reply_attribute` WHERE reply_id = ?";
                        $query = $this->db_xmp->query($sql, array($reply_id));
                        $result_attrib = $query->result_array();

                        if(count($result_attrib)  > 0) {
                            foreach($result_attrib as $attribute) {
                                $attribute_id = (int) $attribute['id'];
                                $sql = "DELETE FROM `reply_attribute` where id = ?";
                                $result = $this->db_xmp->query($sql, array($attribute_id));
                            }
                        }

                        $attribute_id = (int) $attribute['id'];
                        $sql = "DELETE FROM `reply` where id = ?";
                        $result = $this->db_xmp->query($sql, array($reply_id));
                    }
                }

                $sql = "DELETE FROM `mechanism` where id = ?";
                $result = $this->db_xmp->query($sql, array($mechanism_id));
            }
        }
        $this->db_xmp->trans_complete();

        if ($this->db_xmp->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;

    }

    public function delKeywordByPattern($pattern, $service_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->trans_start();
        $sql = "SELECT id FROM `mechanism` WHERE pattern = ? AND service_id = ?";
        $query = $this->db_xmp->query($sql, array($pattern, $service_id));
        $result_mecha = $query->result_array();

        if(count($result_mecha) > 0) {
            foreach($result_mecha as $mechanism) {
                $mechanism_id = $mechanism['id'];

                $sql = "SELECT id FROM `reply` WHERE mechanism_id = ?";
                $query = $this->db_xmp->query($sql, array($mechanism_id));
                $result = $query->result_array();

                if(count($result) > 0) {
                    foreach ($result as $reply) {
                        $reply_id =  (int)$reply['id'];
                        $sql = "SELECT id FROM `reply_attribute` WHERE reply_id = ?";
                        $query = $this->db_xmp->query($sql, array($reply_id));
                        $result_attrib = $query->result_array();

                        if(count($result_attrib)  > 0) {
                            foreach($result_attrib as $attribute) {
                                $attribute_id = (int) $attribute['id'];
                                $sql = "DELETE FROM `reply_attribute` where id = ?";
                                $result = $this->db_xmp->query($sql, array($attribute_id));
                            }
                        }

                        $attribute_id = (int) $attribute['id'];
                        $sql = "DELETE FROM `reply` where id = ?";
                        $result = $this->db_xmp->query($sql, array($reply_id));
                    }
                }

                $sql = "DELETE FROM `mechanism` where id = ?";
                $result = $this->db_xmp->query($sql, array($mechanism_id));
            }
        }
        $this->db_xmp->trans_complete();

        if ($this->db_xmp->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;

        /*
         *
        $this->db->trans_start();
        $selectReplyQuery = $this->db->query("SELECT id FROM reply WHERE mechanism_id = '".(int)$mechanism_id."'");
        foreach ($selectReplyQuery->result() as $row) {
            $reply_id = $row->id;
            $this->db->query("DELETE FROM reply_attribute WHERE reply_id = '".(int)$reply_id."'");
            $this->db->query("DELETE FROM reply WHERE id = '".(int)$reply_id."'");
        }
        //delete mechanism
        //$this->db->query("DELETE FROM mechanism WHERE id = '".(int)$mechanism_id."'");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        // ERROR
            return FALSE;
        }
        return TRUE;
         *
         */
    }

    public function getCreatorById($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT a.*,b.name service_name,b.adn,c.name operator FROM mechanism a
            INNER JOIN service b ON a.service_id=b.id
            INNER JOIN operator c ON a.operator_id=c.id
            WHERE a.id=? LIMIT 1";

        $data = array($id);
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, $data)) {
            $result = $query->result_array();
            return $result[0];
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getReplyByMechaId($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT a.*,b.name module_name FROM reply a
            INNER JOIN module b ON a.module_id=b.id
            INNER JOIN charging c ON a.charging_id=c.id
            WHERE a.mechanism_id=? ORDER BY id";

        $data = array($id);
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, $data)) {
            $result = $query->result_array();
            return $result;
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getAttributeByReplyId($id) {
        $this->db = $this->load->database('xmp', TRUE);

        $sql = "SELECT a.*,b.name attribute_name FROM
            reply_attribute a
            INNER JOIN attribute b ON a.attribute_id=b.id
            WHERE a.reply_id=? ORDER BY id";

        $data = array($id);
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, $data)) {
            $result = $query->result_array();
            return $result;
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function removeReplyAndAttributes($mechanism_id) {
        $this->db = $this->load->database('xmp', TRUE);

        $this->db->trans_start();
        $selectReplyQuery = $this->db->query("SELECT id FROM reply WHERE mechanism_id = '".(int)$mechanism_id."'");
        foreach ($selectReplyQuery->result() as $row) {
            $reply_id = $row->id;
            $this->db->query("DELETE FROM reply_attribute WHERE reply_id = '".(int)$reply_id."'");
            $this->db->query("DELETE FROM reply WHERE id = '".(int)$reply_id."'");
        }
        //delete mechanism
        //$this->db->query("DELETE FROM mechanism WHERE id = '".(int)$mechanism_id."'");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        // ERROR
            return FALSE;
        }
        return TRUE;
    }
}