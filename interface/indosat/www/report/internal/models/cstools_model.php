<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cstools_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

	public function getOperator(){
		$query = $this->db->get('operator');
		return $query->result();
	}
	
	public function getService(){
		$query = $this->db->get('service');
		return $query->result();
	}
	
	public function getSearch($adn,$msisdn,$operator,$service,$limit,$offset){
		
		if($adn){$this->db->where('ADN',$adn);}
		if($msisdn){$this->db->where('ADN',$msisdn);}
		if($operator){$this->db->where('ADN',$operator);}
		if($service){$this->db->where('ADN',$service);}
		
		$this->db->limit($limit,$offset);
		$this->db->from('tbl_msgtransact');
		$this->db->join('operator', 'operator.id = tbl_msgtransact.OPERATORID');
		$query = $this->db->get();
		return $query->result();
	}
}

