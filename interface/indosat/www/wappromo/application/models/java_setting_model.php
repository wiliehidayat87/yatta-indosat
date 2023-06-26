<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Java_setting_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database('newwap');
	}
	
	public function getService($ver,$way='='){
		$sql = "select id,wap_name from dev_wapreg";
		$sql .= " where javaapps $way '".$ver."' and status=1";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function getServiceNull($way){
		$sql = "select id,wap_name from dev_wapreg";
		if($way=='not'){
			$sql .= " where not isnull(javaapps) or javaapps !='' ";
		}else{
			$sql .=" where isnull(javaapps) or javaapps=''";
		}
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function changeVersion($id,$version){
               
		$sql = "update dev_wapreg set javaapps='".$version."' where id='".$id."'";
		return $this->db->query($sql);
	}
}
?>
