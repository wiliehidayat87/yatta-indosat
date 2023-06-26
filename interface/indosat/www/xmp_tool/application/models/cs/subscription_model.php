<?php
class Subscription_model extends CI_Model {
	public $db="";
	public function __construct() {
		parent::__construct();
		$this->load->library('my_curl');
	}
	
	public function getOperator(){
		$this->db = $this->load->database('xmp', TRUE);
		
		write_log("info", __METHOD__ . ", Start Query");
		$this->db->select('DISTINCT name', FALSE);
		$this->db->select('long_name', FALSE);
		
		if($query = $this->db->get('operator')){
			write_log("info", __METHOD__ . ", Query Success ");
			return $query->result();
		}else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return array();
		}
	}
	
	public function getService(){
		$this->db = $this->load->database('xmp', TRUE);
		$this->db->select('DISTINCT name', FALSE);
		$query = $this->db->get('service');
		return $query->result();
	}
	
	public function getAdn(){
		$this->db = $this->load->database('xmp', TRUE);
		$this->db->select('DISTINCT adn', FALSE); 
		$query = $this->db->get('service');
		return $query->result();
	}
	
	public function getSearch($msisdn,$operator,$adn,$service,$limit,$offset){
		$this->db = $this->load->database('xmp', TRUE);
		if($adn!=''){$this->db->where('adn',$adn);}
		if($msisdn!=''){$this->db->where('msisdn',$msisdn);}
		if($operator!=''){$this->db->where('operator',$operator);}
		if($service!=''){$this->db->where('service',$service);}
		
		$this->db->limit($limit,$offset);
		$this->db->from('subscription');
		$this->db->order_by('time_updated','desc');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function getTotalSearch($msisdn,$operator,$adn,$service){
		$this->db = $this->load->database('xmp', TRUE);
		if($adn!=''){$this->db->where('adn',$adn);}
		if($msisdn!=''){$this->db->where('msisdn',$msisdn);}
		if($operator!=''){$this->db->where('operator',$operator);}
		if($service!=''){$this->db->where('service',$service);}
		
		$this->db->from('subscription');
		$this->db->order_by('time_updated','desc');
		$query = $this->db->get();
		return $query->result();
	}
		
	public function getChecked($id){
		$this->db = $this->load->database('xmp', TRUE);
		$this->db->where('id',$id);
		$query = $this->db->get('subscription');
		return $query->result();
	}
	
	public function getInactiveChecked($username, $password, $msisdn, $adn, $service, $operator, $channel) {
     	$url = 'http://localhost/xmp_tool_new/cs/fakeapi';
	
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('msisdn', $msisdn);
        $this->my_curl->addParameter('adn', $adn);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('operator', $operator);
        $this->my_curl->addParameter('channel', $channel);

        return $this->my_curl->execute($url);
    }
}
