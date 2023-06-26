<?php
class MY_Controller extends CI_Controller {

	var $parameters = NULL;
        
        function __construct() {
            parent::__construct();
        }

	protected function createParameters($mandatoryParams, $optionalParams) {
		$params = array();
		foreach ($mandatoryParams as $param) {
			if ($this->input->get_post($param,1)==FALSE && strlen($this->input->get_post($param,1)) == 0) {
				throw new Exception(sprintf(RESPONSE_ERROR_MISSINGPARAM, $param));
			}
//			$$param = $this->input->get_post($param,1);
			$params[$param] = $this->input->get_post($param,1);
		}
		foreach ($optionalParams as $key=>$value) {
			if ($this->input->get_post($key,1)==FALSE) {
//				$$key = $value;
				$params[$key] = $value;
			} else {
//				$$key = $this->input->get_post($key,1);
				$params[$key] = $this->input->get_post($key,1);
			}
		}
		$this->parameters = $params;
		write_log('debug','Parameters: ' . toString($params));
		return $params;
	}

	protected function splitPeriod($key) {
		$data = explode("-", $this->getParam($key));
		if (count($data)!=2) {
			throw new Exception(sprintf(RESPONSE_ERROR_INVALIDPARAM, "$key", $this->getParam($key)));
		}
		$year = $data[0];
		$month = $data[1];
		if (!is_numeric($year) || !is_numeric($month)) {
			throw new Exception(sprintf(RESPONSE_ERROR_INVALIDPARAM, "$key", $this->getParam($key)));
		}
		$this->parameters['year'] = $year;
		$this->parameters['month'] = $month;
	}

	public function getParam($key) {
		if (isset($this->parameters[$key]))
			return $this->parameters[$key];
		return NULL;
	}

	public function setParam($key, $value) {
		$this->parameters[$key] = $value;
	}

	public function securityCheckArray($key) {
		$value = $this->getParam($key);
		if (isset($value)) {
			$dataArray=explode(",",$value);
			$valueFinal="";
			foreach ($dataArray as $data) {
				$data=trim($data);
				if (!is_numeric($data)) {
					throw new Exception(sprintf(RESPONSE_ERROR_INVALIDPARAM, 'operatorId', $value));
				}
				$valueFinal.="$data,";
			}
			$value = substr($valueFinal, 0, -1);
		}
		$this->setParam($key,$value);
	}
	
	public function splitOperatorId($key) {
	   $value= explode(",", $this->getParam($key));
	   $this->parameters[$key] = $value;
	}

	public function securityCheckArrayString($key) {
		$value = $this->getParam($key);
		if (isset($value)) {
			$dataArray=explode(",",$value);
			$valueFinal="";
			foreach ($dataArray as $data) {
				$data=trim($data);
				if (!is_numeric($data)) {
					throw new Exception(sprintf(RESPONSE_ERROR_INVALIDPARAM, 'operatorId', $value));
				}
				$valueFinal.="'$data',";
			}
			$value = substr($valueFinal, 0, -1);
		}
		$this->setParam($key,$value);
	}

	public function numericTypeCheck($key) {
		$value = $this->getParam($key);
		if (isset($value) && !is_numeric($value)) {
			throw new Exception(sprintf(RESPONSE_ERROR_NOTNUMERIC, $key, $value));
		}
	}

	public function jsonToArray($key) {
		$value = $this->getParam($key);
//		if (isset($value)) {
//			throw new Exception(sprintf(RESPONSE_ERROR_NOTNUMERIC, $key, $value));
//		}

		$this->setParam($key,json_decode($value,1));
	}

	public function arrayTypeCheck($key) {
		$value = $this->getParam($key);
		if (isset($value) && !is_array($value)) {
			throw new Exception(sprintf(RESPONSE_ERROR_NOTNUMERIC, $key, $value));
		}
	}

	public function orderTypeCheck($key) {
		$value = $this->getParam($key);
		if (isset($value) && !is_numeric($value)) {
			throw new Exception(sprintf(RESPONSE_ERROR_NOTORDERTYPE, $key, $value));
		}
	}

	public function messageTypeCheck($key) {
		$value = $this->getParam($key);
		if ($value!='MT' && $value!='MO' && $value!='DELIVERED') {
			throw new Exception(sprintf(RESPONSE_ERROR_NOTMSGTYPE, $key, $value));
		}
	}

}
