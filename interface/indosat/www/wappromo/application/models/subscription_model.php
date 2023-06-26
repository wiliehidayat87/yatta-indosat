<?php
class Subscription_model extends CI_Model
{
    public  $db_newwap,
            $db_xmp,
            $db_table   = "wap_site",
            $db_table2  = "service",
            $db_table3  = "adn";

    function __construct() {
                parent::__construct();

     //   $this->load->database();
    }
    
    public function getSubscriptionList($offset, $limit, $search = "") {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        $sql_query = "SELECT a.id, a.wap_name, a.service, a.wap_title, a.ak_time as ak_time, a.confirmation_page_enable, DATE_FORMAT(created, '%d-%m-%Y %T') as datecreated ";
        $sql_query .= "FROM dev_wapreg a ";
        $sql_query .= "WHERE status='1' ";
        $sql_query .= (!empty ($search)) ? "AND (UPPER(a.wap_name) LIKE '%" . $search . "%' OR UPPER(a.service) LIKE '%" . $search . "%' OR UPPER(a.wap_title) LIKE '%" . $search . "%' OR UPPER(a.ak_time) LIKE '%" . $search . "%' ) " : "";
        $sql_query .= "ORDER BY id DESC, a.wap_name ASC ";
        $sql_limit = "LIMIT $offset, $limit ";
        try {
            $query = $this->db_newwap->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db_newwap->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                        'query' => $sql_query . $sql_limit,
                        'total' => $total,
                        'result' => array (
                        'data' => $queryData->result_array(),
                        'total' => $totalData
                    )
                );
            }
       catch (Exception $e) {
            $result = array ();
            }

            return $result; 
    }
	
	function create_new_wapreg($data){
		 $this->db_newwap = $this->load->database('newwap', TRUE);
		 $sql="INSERT INTO dev_wapreg (id,wap_name,service,wap_title,ak_id,ak_time,always_enable,clickable_header,
		 clickable_header_text,clickable_footer,clickable_footer_text,status_auto_redirect,time_auto_redirect,
		 url_auto_redirect,confirmation_page_enable,wap_setting_msisdn,wap_setting_javaapps,bg_color,bg_picture) VALUES(
		 NULL,'".$data['wap_name']."','".$data['wap_service']."','".$data['wap_title']."','".$data['ak_schedule']."','".$data['ak_timer']."',
		 '".$data['always_enable']."','".$data['clickable_header']."','".$data['clickable_header_text']."','".$data['clickable_footer']."','".$data['clickable_footer_text']."',
		 '".$data['thankyou_page_auto_redirect']."','".$data['thankyou_page_time']."','".$data['thankyou_page_url']."','".$data['confirmation_page_enable']."','".$data['wap_setting_msisdn']."',
		 '".$data['wap_setting_javaapps']."','".$data['bg_color']."','".strtolower($data['bg_picture'])."')";
		 $this->db_newwap->query($sql);
		 $id=$this->db_newwap->insert_id();
		 for($i=0;$i<count($data['comp_land']);$i++){
			 if($data['comp_land'][$i]['landing_page_name']!=''){
				$image = (empty($data['comp_land'][$i]['landing_page_image'])) ? '' : "landing_".$data['wap_name']."_".$data['comp_land'][$i]['landing_page_image'];
				 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_land'][$i]['landing_page_name']."','".$data['comp_land'][$i]['landing_page_type']."',
				 '".$data['comp_land'][$i]['landing_page_value']."' ,'".$data['comp_land'][$i]['landing_page_clickable']."',
				 '".$image."','".$data['comp_land'][$i]['landing_page_sort']."',
				 now(),now(),1,'home','".$data['comp_land'][$i]['landing_page_msgtext']."')";
				 $this->db_newwap->query($sql);
			 }
		 }
		 for($i=0;$i<count($data['comp_conf']);$i++){
			 if($data['comp_conf'][$i]['confirmation_page_name']!=''){
				$image = (empty($data['comp_conf'][$i]['confirmation_page_image'])) ? '' : "confirmation_".$data['wap_name']."_".$data['comp_conf'][$i]['confirmation_page_image'];
				 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_conf'][$i]['confirmation_page_name']."','".$data['comp_conf'][$i]['confirmation_page_type']."',
				 '".$data['comp_conf'][$i]['confirmation_page_value']."' ,'".$data['comp_conf'][$i]['confirmation_page_clickable']."',
				 '".$image."','".$data['comp_conf'][$i]['confirmation_page_sort']."',
				 now(),now(),1,'confirm','')";
				 $this->db_newwap->query($sql);
			 }
		 }
		 for($i=0;$i<count($data['comp_tq']);$i++){
			 if($data['comp_tq'][$i]['thankyou_page_name']!=''){
                             $image = (empty($data['comp_tq'][$i]['thankyou_page_image'])) ? '' : "thankyou_".$data['wap_name']."_".$data['comp_tq'][$i]['thankyou_page_image'];

				 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_tq'][$i]['thankyou_page_name']."','".$data['comp_tq'][$i]['thankyou_page_type']."',
				 '".$data['comp_tq'][$i]['thankyou_page_value']."' ,'".$data['comp_tq'][$i]['thankyou_page_clickable']."',
				 '".$image."','".$data['comp_tq'][$i]['thankyou_page_sort']."',
				 now(),now(),1,'thanks','')";
				 $this->db_newwap->query($sql);
			 }
		 }
		 for($i=0;$i<count($data['comp_info']);$i++){
			 if($data['comp_info'][$i]['info_page_name']!=''){
                             $image = (empty($data['comp_info'][$i]['info_page_image'])) ? '' : "info_".$data['wap_name']."_".$data['comp_info'][$i]['info_page_image'];

				 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_info'][$i]['info_page_name']."','".$data['comp_info'][$i]['info_page_type']."',
				 '".$data['comp_info'][$i]['info_page_value']."' ,'".$data['comp_info'][$i]['info_page_clickable']."',
				 '".$image."','".$data['comp_info'][$i]['info_page_sort']."',
				 now(),now(),1,'info','')";
				 $this->db_newwap->query($sql);
			 }
		 }
	}
	
	function update_new_wapreg($data){
		$this->db_newwap = $this->load->database('newwap', TRUE);
		$sql="UPDATE dev_wapreg SET
			wap_name='".$data['wap_name']."',
			service='".$data['wap_service']."',
			wap_title='".$data['wap_title']."',
			ak_id='".$data['ak_schedule']."',
			ak_time='".$data['ak_timer']."',
			always_enable='".$data['always_enable']."',
			clickable_header='".$data['clickable_header']."',
		 	clickable_header_text='".$data['clickable_header_text']."',
			clickable_footer='".$data['clickable_footer']."',
			clickable_footer_text='".$data['clickable_footer_text']."',
			status_auto_redirect='".$data['thankyou_page_auto_redirect']."',
			time_auto_redirect='".$data['thankyou_page_time']."',
			url_auto_redirect='".$data['thankyou_page_url']."',
			confirmation_page_enable='".$data['confirmation_page_enable']."',
			wap_setting_msisdn='".$data['wap_setting_msisdn']."',
			wap_setting_javaapps='".$data['wap_setting_javaapps']."' ,
			bg_color='".$data['bg_color']."',
			bg_picture='".strtolower($data['bg_picture'])."'
		WHERE id='".$data['id']."'";
		$this->db_newwap->query($sql);
		$id=$data['id'];
		$sql="delete from dev_wapreg_component where wap_id='".$id."'";
		$this->db_newwap->query($sql);
		for($i=0;$i<count($data['comp_land']);$i++){
                        //$data['comp_land'][$i]['landing_page_name'] = ($data['comp_land'][$i]['landing_page_type'] == 'url') ? urlencode($data['comp_land'][$i]['landing_page_name']) : $data['comp_land'][$i]['landing_page_name'];
			if($data['comp_land'][$i]['landing_page_name']!=''){
				$sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				NULL,'".$id."','". $data['comp_land'][$i]['landing_page_name']."','".$data['comp_land'][$i]['landing_page_type']."',
				'".$data['comp_land'][$i]['landing_page_value']."' ,'".$data['comp_land'][$i]['landing_page_clickable']."',
				'".$data['comp_land'][$i]['landing_page_image']."','".$data['comp_land'][$i]['landing_page_sort']."',
				now(),now(),1,'home','".$data['comp_land'][$i]['landing_page_msgtext']."')";
				$this->db_newwap->query($sql);
			}
		 }
		 for($i=0;$i<count($data['comp_conf']);$i++){
			 if($data['comp_conf'][$i]['confirmation_page_name']!=''){
                                 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_conf'][$i]['confirmation_page_name']."','".$data['comp_conf'][$i]['confirmation_page_type']."',
				 '".$data['comp_conf'][$i]['confirmation_page_value']."' ,'".$data['comp_conf'][$i]['confirmation_page_clickable']."',
				 '".$data['comp_conf'][$i]['confirmation_page_image']."','".$data['comp_conf'][$i]['confirmation_page_sort']."',
				 now(),now(),1,'confirm','')";
				 $this->db_newwap->query($sql);
			 }
		 }
		 for($i=0;$i<count($data['comp_tq']);$i++){
			 if($data['comp_tq'][$i]['thankyou_page_name']!=''){
				 $sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_tq'][$i]['thankyou_page_name']."','".$data['comp_tq'][$i]['thankyou_page_type']."',
				 '".$data['comp_tq'][$i]['thankyou_page_value']."' ,'".$data['comp_tq'][$i]['thankyou_page_clickable']."',
				 '".$data['comp_tq'][$i]['thankyou_page_image']."','".$data['comp_tq'][$i]['thankyou_page_sort']."',
				 now(),now(),1,'thanks','')";
				 $this->db_newwap->query($sql);
			 }
		 }
		 for($i=0;$i<count($data['comp_info']);$i++){
			 if($data['comp_info'][$i]['info_page_name']!=''){
				$sql="INSERT INTO dev_wapreg_component(id,wap_id,name,type,value,is_link,image,sort,created,modified,status,page_component,msg_text) VALUES(
				 NULL,'".$id."','".$data['comp_info'][$i]['info_page_name']."','".$data['comp_info'][$i]['info_page_type']."',
				 '".$data['comp_info'][$i]['info_page_value']."' ,'".$data['comp_info'][$i]['info_page_clickable']."',
				 '".$data['comp_info'][$i]['info_page_image']."','".$data['comp_info'][$i]['info_page_sort']."',
				 now(),now(),1,'info','')";
				 $this->db_newwap->query($sql);
			 }
		 }
	}

	function RemoveComponent($id){
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "DELETE FROM  dev_wapreg_component WHERE id = ? LIMIT 1";
            $this->db_newwap->query($sql, $id);
            
//            try {
//                $data = array ('status' => '0');
//
//                $this->db_newwap->where('id', $id);
//                $this->db_newwap->set('datemodified', 'NOW()', FALSE);
//                $this->db_newwap->update($this->db_table, $data);
//
//                $result = array ('status' => TRUE, 'message' => '');
//            }
//            catch (Exception $e) {
//                $result = array ('status' => FALSE, 'message' => $e->getMessage());
//            }

            $result = array ('status' => TRUE, 'message' => 'Success Delete');
            return $result;

            //return $query;
    }

    function read_all_wapreg($page, $row)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM dev_wapreg WHERE status = '1' LIMIT ?,?";
            $query	= $this->db_newwap->query($sql,array($page, $row));

            if($query->num_rows() != 0)
            {
              return $query->result_array();
            }
            else
            {
              return FALSE;
            }
    }

    function count_all_wapreg()
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM dev_wapreg WHERE status = '1'";
            $query	= $this->db_newwap->query($sql);

            return $query->num_rows();
    }

    function read_wapreg_by_id($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM dev_wapreg WHERE id = ? AND status = '1' LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);

            if($query->num_rows() != 0)
            {
              return $query->result_array();
            }
            else
            {
              return FALSE;
            }
    }
	
	function read_subscription_component($id){
		$this->db_newwap = $this->load->database('newwap', TRUE);
		$component=array();
		$sql="select * from dev_wapreg_component where wap_id='".$id."' and page_component='home' order by sort asc";
		$query= $this->db_newwap->query($sql);
		$component['landing_page']=$query->result_array();
		$sql="select * from dev_wapreg_component where wap_id='".$id."' and page_component='confirm' order by sort asc";
		$query= $this->db_newwap->query($sql);
		$component['confirmation_page']=$query->result_array();
		$sql="select * from dev_wapreg_component where wap_id='".$id."' and page_component='thanks' order by sort asc";
		$query= $this->db_newwap->query($sql);
		$component['thanks_page']=$query->result_array();
		$sql="select * from dev_wapreg_component where wap_id='".$id."' and page_component='info' order by sort asc";
		$query= $this->db_newwap->query($sql);
		$component['info_page']=$query->result_array();
		return $component;
	}

    function read_wapreg_by_name($name){
		$this->db_newwap = $this->load->database('newwap', TRUE);
		$sql		= "SELECT * FROM dev_wapreg WHERE wap_name = ? AND status = '1' LIMIT 1";
		$query	= $this->db_newwap->query($sql, $name);
		if($query->num_rows() != 0){
		  return $query->result_array();
		}else{
		  return FALSE;
		}
    }

    function read_wapreg_by_id_name($id, $name)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM dev_wapreg WHERE id != ? AND wap_name = ? AND status = '1' LIMIT 1";
            $query	= $this->db_newwap->query($sql,array($id, $name));

            if($query->num_rows() != 0)
            {
              return $query->result_array();
            }
            else
            {
              return FALSE;
            }
    }

    function create_wapreg($wap_name, $wap_service, $wap_title, $auto_reg, $ak_schedule, $homepage, 
                           $conf_page, $conf_text, $unavailable_text, $success_text, 
                           $url_promo, $service_promo_text, $xts_token)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "INSERT INTO dev_wapreg(
                            wap_name,service, wap_title, ak_time, ak_id, homepage, 
                            confirmation_page, confirmation_text, unavailable_text, success_text, service_promo, service_promo_text,
                            xts_token, created, last_modified, status)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), '1')";

            $query	= $this->db_newwap->query($sql,array($wap_name,$wap_service, $wap_title, $auto_reg, $ak_schedule, $homepage, 
                           $conf_page, $conf_text, $unavailable_text, $success_text, 
                           $url_promo, $service_promo_text, $xts_token));
            return $query;
    }

    function update_wapreg($id, $wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage, $confirmation_page, 
                           $confirmation_text, $unavailable_text, $success_text, $service_promo, 
                           $service_promo_text, $xts_token)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql = "UPDATE dev_wapreg SET wap_name = ?,  service = ?, wap_title = ?, ak_time = ?,
                    ak_id = ?, homepage = ?, 
                    confirmation_page = ?, confirmation_text = ?, unavailable_text = ?, success_text = ?,
                    service_promo = ?, service_promo_text = ?, xts_token = ?, last_modified = NOW()
                    WHERE id = ? LIMIT 1";
                    //echo $sql;exit;
            $query = $this->db_newwap->query($sql,array(
                            $wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage, $confirmation_page, 
                            $confirmation_text, $unavailable_text, $success_text, $service_promo, 
                            $service_promo_text, $xts_token,$id
                        )
                     );															
            return $query;
    }

    function unactivate_wapreg($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE dev_wapreg SET status = '0' WHERE id = ? LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);
            
            try {
                $data = array ('status' => '0');

                $this->db_newwap->where('id', $id);
                $this->db_newwap->set('datemodified', 'NOW()', FALSE);
                $this->db_newwap->update($this->db_table, $data);

                $result = array ('status' => TRUE, 'message' => '');
            }
            catch (Exception $e) {
                $result = array ('status' => FALSE, 'message' => $e->getMessage());
            }

            return $result;

            //return $query;
    }

    /* --------------------- wapreg_params ---------------------- */

    function read_all_wapreg_params($wapreg_id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM wapreg_params WHERE wapreg_id = ? AND status = '1'";
            $query	= $this->db_newwap->query($sql, $wapreg_id);

            if($query->num_rows() != 0)
            {
              return $query->result_array();
            }
            else
            {
              return FALSE;
            }
    }

    function read_wapreg_params_by_id($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "SELECT * FROM wapreg_params WHERE id = ? AND status = '1' LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);

            if($query->num_rows() != 0)
            {
              return $query->result_array();
            }
            else
            {
              return FALSE;
            }
    }

    function create_wapreg_params($wapreg_id, $key_name, $key_value, $key_description)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "INSERT INTO wapreg_params(wapreg_id, key_name, key_value, key_description, status)
                                                            VALUES(?, ?, ?, ?, '1')";

            $query	= $this->db_newwap->query($sql, array($wapreg_id, $key_name, $key_value, $key_description));

            return $query;
    }

    function update_wapreg_params($id, $wapreg_id, $key_name, $key_value, $key_description)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE wapreg_params SET key_name = ?, key_value = ?, key_description = ? WHERE id = ? AND wapreg_id = ? LIMIT 1";

            $query	= $this->db_newwap->query($sql, array($key_name, $key_value, $key_description, $id, $wapreg_id));

            return $query;
    }

    function unactivate_wapreg_params_by_id($id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE wapreg_params SET status = '0' WHERE id = ? LIMIT 1";
            $query	= $this->db_newwap->query($sql, $id);

            return $query;
    }

    function unactivate_wapreg_params_by_wapregid($wapreg_id)
    {
            $this->db_newwap = $this->load->database('newwap', TRUE);
            $sql		= "UPDATE wapreg_params SET status = '0' WHERE wapreg_id = ?";
            $query	= $this->db_newwap->query($sql, $wapreg_id);

            return $query;
    }
}

?>
