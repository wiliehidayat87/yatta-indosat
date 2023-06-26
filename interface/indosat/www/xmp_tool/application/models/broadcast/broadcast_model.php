<?php

/*
 * 
 *  Broadcast tool for XMP
 *  Broadcast db model
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-11-19 16:21:46 +0700 (Mon, 19 Nov 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2989 $
 * 
 */

class Broadcast_model extends CI_Model {

    public $db,
           $db_table_schedule = "push_schedule",
           $db_table_content = "push_content";

    function __construct() {
        parent::__construct();        
    }

    function getSchedule($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();
        
        $sql_query = "
            SELECT
                id,
                service,
                adn,
                operator,
                push_time,
                status,
                recurring_type,
                content_label,
                content_select,
                service_type,
                price,
                handlerfile,
                notes,
                last_content_id,
                modified,
                created
            FROM
                ".$this->db_table_schedule."
            WHERE 1 
           ";
        if ($param['id']!= "null" && $param['id']) {$sql_query .= "AND id='".$param['id']."' "; $param['rec']=1;}
        if ($param['svc_id']!= "null" && $param['svc_id']) $sql_query .= "AND service='".$param['svc_id']."' ";
        if ($param['opr_id']!= "null" && $param['opr_id']) $sql_query .= "AND operator='".$param['opr_id']."' ";
        $sql_query .= "ORDER BY id DESC ";
        
        $page = ($param['pg']) ? $param['pg'] - 1 : 0;
        $offset = $page * $param['rec'];
        $limit = $param['rec'];
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
   
    function addNewSchedule($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $param['push_time'] = sprintf ("%s %s", $_POST['push_time_date'], $_POST['push_time_hour']);
            $param['service_type'] = "TEXT";
            $param['created'] = date("Y-m-d H:i:s");
            $data = array(
                'service'           => $param['service'],
                'operator'          => $param['operator'],
                'adn'               => $param['adn'],
                'recurring_type'    => $param['recurring_type'],
                'handlerfile'       => $param['handlerfile'],
                'push_time'         => $param['push_time'],
                'status'            => $param['status'],
                'content_select'    => $param['content_select'],
                'content_label'     => $param['content_label'],
                'service_type'      => $param['service_type'],
                'price'             => $param['price'],
                'notes'             => $param['notes'],
                'created'           => date("Y-m-d H:i:s")
            );
            //var_dump($data); exit;
            $this->db->insert($this->db_table_schedule, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
        
    }

    function editSchedule($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();
        $push_time = sprintf ("%s %s", $_POST['push_time_date'], $_POST['push_time_hour']);

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                "operator"          => $param['operator'], 
                "adn"               => $param['adn'], 
                "recurring_type"    => $param['recurring_type'], 
                "service"           => $param['service'], 
                "push_time"         => $param['push_time'], 
                "handlerfile"       => $param['handlerfile'], 
                "status"            => $param['status'], 
                "price"             => $param['price'], 
                "notes"             => $param['notes'], 
                "content_select"    => $param['content_select'], 
                "content_label"     => $param['content_label'],
                'modified'          => date("Y-m-d H:i:s") 
            );
            
            $this->db->where('id', $param['id']);
            $this->db->update($this->db_table_schedule, $data);
            
            //$query = $this->db->query($sql_query);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
        
    }

    function deleteSchedule($param) {
        $this->db = $this->load->database('push', TRUE);
        $ids = implode(",", $param);
        $sql_query = "DELETE FROM ".$this->db_table_schedule." WHERE id IN  (".$ids.")";
        var_dump($SQL);
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;          
    }

    function getContent($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();
        $param['datepublish'] = $_POST['push_date'];
        
        $sql_query = "
            SELECT
                id,
                service,
                content_label,
                content,
                author,
                notes,
                datepublish,
                lastused,
                modified,
                created
            FROM
                ".$this->db_table_content."
            WHERE 1 
           ";
        if ($param['id']!= "null" && $param['id']) {$sql_query .= "AND id='".$param['id']."' "; $param['rec']=1;}
        if ($param['service']!= "null" && $param['service']) $sql_query .= "AND service='".$param['service']."' ";
        if ($param['content']!= "null" && $param['content']) $sql_query .= "AND content LIKE '%".$param['content']."%' ";
        if ($param['datepublish']!= "null" && $param['datepublish']) $sql_query .= "AND datepublish >= '".$param['datepublish']." 00:00:00' ";
        if ($param['datepublish']!= "null" && $param['datepublish']) $sql_query .= "AND datepublish <= '".$param['datepublish']." 23:59:59' ";
        $sql_query .= "ORDER BY id DESC ";
        
        //var_dump($param, $sql_query);
        $page = ($param['pg']) ? $param['pg'] - 1 : 0;
        $offset = $page * $param['rec'];
        $limit = $param['rec'];
        
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
    
    function addNewContent($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $datepublish = sprintf ("%s %s", $_POST['datepublish_date'], $_POST['datepublish_hour']);
            $data = array(
                'content'       => $param['content'],
                'author'        => $param['author'],
                'service'       => $param['service'],
                'datepublish'   => $param['datepublish'],
                'notes'         => $param['notes'],
                'content_label' => $param['content_label'],
                'created'       => date("Y-m-d H:i:s")
            );
            $this->db->insert($this->db_table_content, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
        
    }

    function editContent($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $result = array();

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                'content'       => $param['content'],
                'author'        => $param['author'],
                'service'       => $param['service'],
                'datepublish'   => $param['datepublish'],
                'notes'         => $param['notes'],
                'content_label' => $param['content_label'],
                'modified'      => date("Y-m-d H:i:s")
            );
            $this->db->where('id', $param['id']);
            $this->db->update($this->db_table_content, $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
        
    }

    function deleteContent($param) {
        $this->db = $this->load->database('push', TRUE);
        
        $ids = implode(",", $param);
        $sql_query = "DELETE FROM ".$this->db_table_content." WHERE id IN  (".$ids.")";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;          
        
    }

    function runQuery($sql_query) {
        $this->db = $this->load->database('push', TRUE);
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;          
        
    }
    
    function isContentExist ($service, $label, $date) {
        $this->db = $this->load->database('push', TRUE);
        
        $sql_query = sprintf("SELECT id FROM push_content WHERE service='%s' AND content_label = '%s' AND datepublish='%s'", $service, $label, $date);

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
}

?>
