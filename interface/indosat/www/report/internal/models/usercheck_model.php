<?php
class Usercheck_model extends CI_Model {
	function __construct() {
		parent::__construct();

        $this->load->database('service_ui');
	}
}

function check_username(){
    $username = $_GET['q'];

    $sql_query = "select username from sc_users where username='$username'";
    $query     = $this->db->query($sql_query);
    
    if(mysql_num_rows($query)==0){
        return "$username bisa digunakan";
    }else{
        return "$username sudah ada yang menggunakan";
    }
}

?>