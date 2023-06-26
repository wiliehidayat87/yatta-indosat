<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class subscriber_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getSubscriber($username, $password, $period, $shortCode='', $operatorId='', $service='') {
        $serviceName = 'getSubscriberReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('service', $service);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }

//    public function getSubscriber($username, $password, $period, $shortCode='', $operatorId='', $service='', $subject=''){
//		$data = array(
//			0 => array(
//				'service' => 'ACAK',
//				'subject' => array(
//					0 => array(
//						'name' => 'REG',
//						'total'=> 30,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					),
//					1 => array(
//						'name' => 'UNREG',
//						'total'=> 33,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					)
//				)
//			),
//			1 => array(
//				'service' => 'AMOR',
//				'subject' => array(
//					0 => array(
//						'name' => 'REG',
//						'total'=> 1030,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					),
//					1 => array(
//						'name' => 'UNREG',
//						'total'=> 73463,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					)
//				)
//			),
//			2 => array(
//				'service' => 'ANGEL',
//				'subject' => array(
//					0 => array(
//						'name' => 'REG',
//						'total'=> 30,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					),
//					1 => array(
//						'name' => 'UNREG',
//						'total'=> 33,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					)
//				)
//			),
//			3 => array(
//				'service' => 'ASIK',
//				'subject' => array(
//					0 => array(
//						'name' => 'REG',
//						'total'=> 30,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					),
//					1 => array(
//						'name' => 'UNREG',
//						'total'=> 33,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					)
//				)
//			),
//			4 => array(
//				'service' => 'ADA DEH',
//				'subject' => array(
//					0 => array(
//						'name' => 'REG',
//						'total'=> 30,
//						'daily'=> array(
//							0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19
//						)
//					)
//				)
//			)
//		);
//
//		return array(
//			'status' => 'OK',
//			'message'=> '',
//			'data'	=> array(4,$data)
//		);
//    }
}