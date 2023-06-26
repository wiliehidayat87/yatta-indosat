<?php
class Users_model extends CI_Model {
	public $db_reports='';
    function __construct() {
        parent::__construct();

        $this->db_reports = $this->load->database('reports', TRUE);
    }

    public function getUserList($offset = 0, $limit = 0, $search = "") {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql_query  = "SELECT a.id, a.username, a.u_group, a.status, b.group_name as u_group FROM acc_users as a ";
        $sql_query .= "LEFT JOIN acc_group as b ON a.u_group=b.id ";
        $sql_query .= "WHERE a.id != '1' AND a.status != '0' ";
        $sql_query .= (!empty($search)) ? "AND (UPPER(a.username) LIKE '%" . $search . "%'  
                       OR UPPER(b.group_name) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = "LIMIT $offset, $limit ";

        try {
            $query     = $this->db_reports->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db_reports->query($sql_query . $sql_limit);
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
    
    public function checkUserList($username,$id) {
        $this->db_reports = $this->load->database('reports', TRUE);
                      
		$sql   = "SELECT * FROM acc_users WHERE username='$username' ";
        $sql   .=(!empty($id))?"AND id!='$id' ":""; 
        $query = $this->db_reports->query($sql);
                        	
        if($query->num_rows() != 0){
			return TRUE;
        }
            return FALSE;
              
    }
    
    public function addNewUser($username, $password, $group){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $result = array ();
        
        try {
            $data = array (
                'username'  => $username,
                'password'  => md5($password),
                'u_group'   => $group,
                'status'    => "1"
            );
            $this->db_reports->set('created', 'NOW()', FALSE);
            $this->db_reports->insert('acc_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function editUser($id) {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $this->db_reports->select('username, password, u_group');
        $this->db_reports->where('id', $id);

        $query = $this->db_reports->get('acc_users');

        return $query->result_array();
    }

    public function updateUser($id, $username, $password, $group) {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $result = array ();

        try {
            $data = array (
                'username'  => $username,
                'password'  => md5($password),
                'u_group'   => $group
            );

            $this->db_reports->where('id', $id);
            $this->db_reports->update('acc_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function deleteUser($id) {
        $this->db_reports = $this->load->database('reports', TRUE);
        
        try {
            $data = array (
                'status' => '0'
            );
            
            $this->db_reports->where('id', $id);
            $this->db_reports->update('acc_users', $data);

            $result = array ('status' => TRUE, 'message' => '');
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function readGroup(){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql	= "SELECT * FROM acc_group WHERE id != '1' ORDER BY id";
        $query	= $this->db_reports->query($sql);
		
        return $query->result_array();
    }
       
}
?>
