<?php
class Sso_gateway {

	//private $CI;
	private $transport;

	public $url = "http://sso-server/index.php/service/";
	public $username;
	public $password;
	public $service_url;
	public $type;
	public $session_expire = 1800;

	public $server_session;
	public $application_session;
	public $request_token;


	public function __construct($username,$password)
    {
        //$this->CI =& get_instance();
        //log_message('debug', 'SSO-GATEWAY Class Initialized');

        $this->username = $username;
        $this->password = $password;
        $this->url		= SSO_APP_URL.'/service/';
        $this->transport = new sso_transport();
        //populate gateway data
        if (@$_SESSION[SSO_APP_NAME]['request_token'] )
			$this->request_token = $_SESSION[SSO_APP_NAME]['request_token'] ;
    }


	/**
	 * Generate request token based on
	 * application sessin + application username + application passwoed
	 * @public
	 **/
	public function generate_request_token()
	{
		if (!$this->request_token)
		{
			$this->request_token 		= md5(session_id().' '.$this->username.' '.$this->password);
			$_SESSION[SSO_APP_NAME]['request_token'] 	= $this->request_token;
		}

		return $this->request_token ;
	}

	public function get_url($cmd)
    {
		$token = $this->generate_request_token();
		$checksum = md5("{$cmd}{$token}{$this->password}");
        return "{$this->url}?cmd={$cmd}&application_name={$this->username}&token=$token&checksum=$checksum&sid=".session_id();
    }
	/**
	* get Login Url
	* @return string
	*/
    public function get_login_url()
    {
		$token = $this->generate_request_token();
		$checksum = md5("login{$token}{$this->password}");
        return "{$this->url}?cmd=login&application_name={$this->username}&token=$token&checksum=$checksum&sid=".session_id();
    }
	 public function get_logout_url()
    {
		$token = $this->generate_request_token();
		$checksum = md5("logout{$token}{$this->password}");
        return "{$this->url}?cmd=logout&application_name={$this->username}&token=$token&checksum=$checksum&sid=".session_id();
    }
	public function set_cookie($cookie)
	{
		$this->transport->cookie = $cookie;
	}
	public function send_request ($command, $data = array())
	{
        $token = $this->generate_request_token();
        $cmd='';
		$checksum = md5("{$cmd}{$token}{$this->password}");

		$data['cmd'] 				= $command;
		$data['application_name'] 	= $this->username;
		$data['token'] 				= $token;
		$data['ses_id']				= session_id();

		return $this->transport->get($this->url,$data);
	}

	function call_api($resource,$param)
	{
		$param['token'] 			=  	$this->generate_request_token();
		$param['application_name'] 	= 	$this->username;
		$param['sid']				= 	session_id();
		return $this->transport->post(SSO_APP_URL.'/api/'.$resource,$param);
	}

}

