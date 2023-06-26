<?php
class Login_model extends CI_Model {
    function __construct() {
        parent::__construct();

        $this->load->database('smswebtool');
        $this->load->library('session');
    }

    function login($username, $password) {
        $password = md5($password);

        $sql    = "SELECT a.id, a.username, a.u_group, b.group_name, b.group_menu ";
        $sql   .= "FROM wp_users a ";
        $sql   .= "LEFT JOIN wp_group b ON a.u_group = b.id ";
        $sql   .= "WHERE a.username = ? AND a.password = ? ";
        $query  = $this->db->query($sql, array ($username, $password));

        if ($query->num_rows() != 0) {
            $row     = $query->result_array();
            $newdata = array (
                'wap_username'  => $row[0]['username'],
                'wap_group'     => $row[0]['u_group'],
                'wap_groupname' => $row[0]['group_name'],
                'wap_groupmenu' => $row[0]['group_menu']
            );

            $this->session->set_userdata($newdata);

            return TRUE;
        }

        return FALSE;
    }
}