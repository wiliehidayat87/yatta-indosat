<?php

class User_model extends CI_Model {

    public $db_xmptool;

    function __construct() {
        parent::__construct();
        // $this->load->database('');
    }

    public function login($username, $password) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $password = md5($password);

        $sql = "SELECT a.id, a.username, a.u_group, grp.group_name, grp.group_menu ";
        $sql .= "FROM users as a ";
        $sql .= "LEFT JOIN `group` as grp ON a.u_group = grp.id ";
        $sql .= "WHERE a.username = ? AND a.password = ? ";
		
		write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db_xmptool->query($sql, array($username, $password))){
			write_log("info", __METHOD__ . ", Query Success ");
			if ($query->num_rows() != 0) {
				$result = $query->row();
            return $result;
			} else {
				return FALSE;
			}
		}
		else{
			write_log("info", __METHOD__ . ", Query Success ");
			return FALSE;
		}
    }

    // Change Password

    public function CheckPassword($currentpass) {
        $this->db_xmptool = $this->load->database('default', TRUE);
        $username = $this->session->userdata('userName');
        $currentpass = md5($currentpass);

        $sql = "SELECT password FROM users WHERE username=? and password=?";
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db->query($sql, array($username, $currentpass))){
			write_log("info", __METHOD__ . ", Query Success ");
			if ($query->num_rows() != 0) {
				return TRUE;
			}
			return FALSE;
		}
		else{
			write_log("info", __METHOD__ . ", Query Success ");
			return FALSE;
		}
	}

    public function ChangePassword($newpassword, $newpasswordconfirm) {
        $this->db_xmptool = $this->load->database('default', TRUE);
        if ($newpassword === $newpasswordconfirm) {
            $username = $this->session->userdata('userName');
            $newpassword = md5($newpassword);

            $sql = "UPDATE users SET password=? WHERE username=?";
			write_log("info", __METHOD__ . ", Start Query: " . $sql);
            if ($this->db->query($sql, array($newpassword, $username))) {
				write_log("info", __METHOD__ . ", Query Success ");
                return TRUE;
            }
            else{
				write_log("info", __METHOD__ . ", Query Success ");
				return FALSE;
			}
        }
        else
            return FALSE;
    }

    // End of Change Password //
    
    // Get User Data //
    
    public function getUserData($uid) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $sql_query  = "SELECT a.id, a.f_name, a.l_name, a.email, a.phone, a.u_group, b.group_name as u_group FROM users as a ";
        $sql_query .= "LEFT JOIN `group` as b ON a.u_group=b.id ";
        $sql_query .= "WHERE a.id = ".$uid." AND a.status != '0' ";
		
		write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query      = $this->db_xmptool->query($sql_query);
            $total      = $query->num_rows();
            $queryData  = $this->db_xmptool->query($sql_query);
            $totalData  = $queryData->num_rows();

            $result = array(
                'query' => $sql_query,
                'total' => $total,
                'result'=> array(
                'data'  => $queryData->result_array(),
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
    
    public function ChangeProfile($uid, $f_name, $l_name, $email, $phone) {
        $this->db_xmptool = $this->load->database('default', TRUE);       
               
        $sql = "UPDATE users SET f_name=?, l_name=?, email=?, phone=? WHERE id=?";
		
		write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($this->db->query($sql, array($f_name, $l_name, $email, $phone, $uid))) {
			write_log("info", __METHOD__ . ", Query Success ");
            return TRUE;
        }else{
			write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }        
    }
    
    /**
     * @param String $username
     * @access public 
     */
    public function checkUsername($username) {
        $sql = "SELECT COUNT(users.id) AS total_row FROM users WHERE users.username = ?";
        write_log("info", __METHOD__ . ", Start Query " );
        $query = $this->db_xmptool->query($sql, array($username));

        if ($query) {
            $data = $query->row();
            write_log("info", __METHOD__ . ", Query Success ");
            return $data->total_rows;
        } else {
			write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    /**
     * @param String $username
     * @access public 
     */
    public function getUser($username) {
        $sql = "SELECT users.id, users.username, users.u_group, 
                    groups.group_name FROM users 
                    LEFT JOIN groups ON users.u_group = groups.id WHERE username = '{$username}'";
		write_log("info", __METHOD__ . ", Start Query " );
        $query = $this->db_xmptool->query($sql);

        if ($query) {
            $result = $query->row();

            write_log("info", __METHOD__ . ", Query Success ");
            return $result;
        } else {

            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getUserList($offset = 0, $limit = 0, $search = "") {
        $this->db_xmptool = $this->load->database('default', TRUE);
        
        $sql_query  = "SELECT a.id, a.username, a.u_group, a.status, b.group_name as u_group FROM users as a ";
        $sql_query .= "LEFT JOIN `group` as b ON a.u_group=b.id ";
        $sql_query .= "WHERE a.id != '1' AND a.status != '0' ";
        $sql_query .= (!empty($search)) ? "AND (UPPER(a.username) LIKE '%" . $search . "%' OR UPPER(b.group_name) LIKE '%" . $search . "%') " : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit = "LIMIT $offset, $limit ";
		
		write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmptool->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db_xmptool->query($sql_query . $sql_limit);
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

    public function checkUserList($username) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $this->db_xmptool->select('username');
        $this->db_xmptool->where('username', $username);

		write_log("info", __METHOD__ . ", Start Query ");
        if($query = $this->db_xmptool->get('users')){
			write_log("info", __METHOD__ . ", Query Success ");
			if ($query->num_rows() > 0)
				return TRUE;
			else
				return FALSE;
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return FALSE;
		}
    }

    public function addNewUser($username, $password, $group) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $result = array();
		
		write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                'username' => $username,
                'password' => md5($password),
                'u_group' => $group,
                'status' => "1"
            );
            $this->db_xmptool->set('created', 'NOW()', FALSE);
            $this->db_xmptool->insert('users', $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editUser($id) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $this->db_xmptool->select('username, password, u_group');
        $this->db_xmptool->where('id', $id);
		write_log("info", __METHOD__ . ", Start Query ");
        if($query = $this->db_xmptool->get('users')){
			write_log("info", __METHOD__ . ", Query Success ");
			return $query->result_array();
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return array();
		}
    }

    public function updateUser($id, $username, $password, $group) {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $result = array();

		write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array(
                'username' => $username,
                'password' => md5($password),
                'u_group' => $group
            );

            $this->db_xmptool->where('id', $id);
            $this->db_xmptool->update('users', $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function deleteUser($id) {
        $this->db_xmptool = $this->load->database('default', TRUE);

		write_log("info", __METHOD__ . ", Start Query ");
        try {
            $data = array('status' => '0');

            $this->db_xmptool->where('id', $id);
            $this->db_xmptool->update('users', $data);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
			write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function readGroup() {
        $this->db_xmptool = $this->load->database('default', TRUE);

        $sql = "SELECT * FROM `group` WHERE id != '1' ORDER BY id";
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if($query = $this->db_xmptool->query($sql)){
			write_log("info", __METHOD__ . ", Query Success ");
			return $query->result_array();
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return array();
		}
    }

}
