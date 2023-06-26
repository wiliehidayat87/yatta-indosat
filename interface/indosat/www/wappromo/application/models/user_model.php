<?php
class User_model extends CI_Model {
    public $db_waptool;
    
    function __construct() {
        parent::__construct();

        $this->db_waptool = $this->load->database('smswebtool', TRUE);
    }

    public function getUserList($offset = 0, $limit = 0, $search = "") {
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $sql_query  = "SELECT a.id, a.username, a.u_group, a.status, b.group_name as u_group FROM wp_users as a ";
        $sql_query .= "LEFT JOIN wp_group as b ON a.u_group=b.id ";
        $sql_query .= "WHERE a.id != '1' AND a.status != '0' ";
        $sql_query .= (!empty($search)) ? "AND (UPPER(a.username) LIKE '%" . $search . "%' OR UPPER(b.group_name) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = "LIMIT $offset, $limit ";

        try {
            $query     = $this->db_waptool->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_waptool->query($sql_query . $sql_limit);
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
    
    public function checkUserList($username) {
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $this->db_waptool->select('username');
        $this->db_waptool->where('username', $username);

        $query = $this->db_waptool->get('wp_users');

        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    public function addNewUser($username, $password, $group){
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $result = array ();
        
        try {
            $data = array (
                'username'  => $username,
                'password'  => md5($password),
                'u_group'   => $group,
                'status'    => "1"
            );
            $this->db_waptool->set('created', 'NOW()', FALSE);
            $this->db_waptool->insert('wp_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editUser($id) {
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $this->db_waptool->select('username, password, u_group');
        $this->db_waptool->where('id', $id);

        $query = $this->db_waptool->get('wp_users');

        return $query->result_array();
    }

    public function updateUser($id, $username, $password, $group) {
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $result = array ();

        try {
            $data = array (
                'username'  => $username,
                'password'  => md5($password),
                'u_group'   => $group
            );

            $this->db_waptool->where('id', $id);
            $this->db_waptool->update('wp_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function deleteUser($id) {
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        try {
            $data = array (
                'status' => '0'
            );
            
            $this->db_waptool->where('id', $id);
            $this->db_waptool->update('wp_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function readGroup(){
        $this->db_waptool = $this->load->database('smswebtool', TRUE);
        
        $sql	= "SELECT * FROM wp_group WHERE id != '1' ORDER BY id";
        $query	= $this->db_waptool->query($sql);
		
        return $query->result_array();
    }
       
}
?>
