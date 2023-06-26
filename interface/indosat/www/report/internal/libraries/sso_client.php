<?php
include 'sso_client/sso_gateway.php';
include 'sso_client/sso_transport.php';
include 'sso_client/sso_response.php';
include 'sso_client/sso_encryption.php';
include 'sso_client/sso_logger.php';

class sso_client
{

	private $CI;

	public $sso_server;
	public $sso_encryption;
	public $logger;

	public function __construct($autologin=1)
    {
		//session_save_path(SSO_AUTH_STORAGE_PATH);
		$application_name 		= SSO_APP_NAME;
		$application_password 	= SSO_APP_PASSWORD;

		$this->sso_server 		= new sso_gateway($application_name, $application_password);
		$this->logger 			= new sso_logger();
		$this->sso_encryption 	= new sso_encryption();
		$this->sso_encryption->set_key($application_password);

		if ($autologin)
			$this->restrict();
    	else
      		$this->client_handler(true);

    }

    function restrict()
    {
    	if (isset($_SESSION[SSO_APP_NAME]['server_session_id']))
        	$this->server_data = $_SESSION[SSO_APP_NAME]['server_session_id'];

		if (!($this->is_login()) && !isset($_GET[session_name()]))
        {
        	$url  = $this->sso_server->get_login_url();
        	$url .= (stripos($url, '?') !== false) ? '&' : '?';

            header("Location: " . $url . "&redirect=". ($this->get_current_url()), true, 307);
            exit;
        }
       $this->client_handler();
    }

    public function is_login()
    {
		return isset($_SESSION[SSO_APP_NAME]['user']);
	}

	public function login()
	{
		$url  = $this->sso_server->get_login_url();
        $url .= (stripos($url, '?') !== false) ? '&' : '?';
    	header("Location: " . $url . "&redirect=". ($this->get_current_url()), true, 307);

	}

	public function get_login_url()
	{
		$url  = $this->sso_server->get_login_url();
        $url .= (stripos($url, '?') !== false) ? '&' : '?';
    	$url  =  $url . "&redirect=". ($this->get_current_url());
		return $url;
	}


    public function get_permission ($str_id)
    {
    	if($_SESSION[SSO_APP_NAME]['permission'])
		foreach ($_SESSION[SSO_APP_NAME]['permission'] as $perm)
		{
			if ($perm->permission_name === $str_id)
				return true;
		}
		return false;
	}

	public function get_user()
	{
		return $_SESSION[SSO_APP_NAME]['user'];
	}

	private function client_handler($force=false)
    {
		/**
		 * KONDISI  : SUDAH LOGIN DAN SERVER AKAN MENGOVERRIDE
		 * INPUT	: Sebuah encrypted string berisi session
		 *
		 */
    	if (isset($_GET[session_name()]) )
        {
			//remove existing data
			unset($_SESSION[SSO_APP_NAME]['server_data']);
			$sso_session 			= $this->sso_encryption->decrypt($_GET[session_name()]);
			$sso_data 				= explode(' ',$sso_session);

			$this->sso_server->set_cookie($sso_data[1]);
			$server_data 			= $this->sso_server->send_request('validate', array('vid'=>$sso_data[1]));
			$server_session_data = (array)json_decode($server_data->body);

			if ($server_session_data['user'])
			{

				//$_SESSION['session_token'] = $sso_data[1];
				$_SESSION[SSO_APP_NAME]['server_session_id'] = $sso_data[1];

				$this->flush_session();
				$_SESSION[SSO_APP_NAME] = array_merge($_SESSION[SSO_APP_NAME],$server_session_data);
				//var_dump($_SESSION);

				if (count($_SESSION[SSO_APP_NAME]['permission'])<=0)
				{
					redirect (SSO_APP_URL.'?error=forbidden');
				}

			}
			//
			$url =  "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];;
			header('location:'.$url);
			die ();

		}

		else
		{
			/*
			 * KONDISI : Jika ada session id terbentuk
			 */
			if (isset($_GET['check']) )
			{
				$_SESSION[SSO_APP_NAME]['server_session_id'] =$this->sso_encryption->decrypt( $_GET['check']);
			}

			if (@$_SESSION[SSO_APP_NAME]['server_session_id'])
			{
				$this->flush_session();
				$server_data 			= $this->sso_server->send_request('validate', array('vid'=>$_SESSION[SSO_APP_NAME]['server_session_id']));
			}
			else
			{
				header ("Location: " . $this->sso_server->get_url('check') . "&redirect=". ($this->get_current_url()) ,true, 307);
				exit;

			}
			$server_session_data 	= (array)json_decode($server_data->body);

			if ($server_session_data['user'])
			{

				$this->flush_session();
				$_SESSION[SSO_APP_NAME] = array_merge($_SESSION[SSO_APP_NAME],$server_session_data);
				if (count($_SESSION[SSO_APP_NAME]['permission'])<=0)
				{

					redirect (SSO_APP_URL.'?error=forbidden');
				}

			}
			else
			{


			    if (!$force){
					header("Location: " . $this->sso_server->get_login_url() . "&redirect=". ($this->get_current_url()), true, 307);
					exit;
			    }

 			}


		}

	}


    function logout()
    {
			unset($_SESSION);
			session_destroy();
			header("Location: " . $this->sso_server->get_logout_url() . "&redirect=". ($this->get_current_url()), true, 307);
			exit;

	}

	function get_current_url() {
		 $pageURL = 'http';
		 if (@$_SERVER["HTTPS"])
		 if ($_SERVER["HTTPS"] == "on") {
		 		$pageURL .= "s";
		}
		 $pageURL .= "://";
		 	if ($_SERVER["SERVER_PORT"] != "80") {
		  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 	} else {
		  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 	}
		 return $pageURL;
	}

	function login_url()
	{
		return 	( $this->sso_server->get_login_url() . "&redirect=". urlencode("http://{$_SERVER["SERVER_NAME"]}{$_SERVER["REQUEST_URI"]}"));

	}
	function flush_session()
	{
		unset($_SESSION[SSO_APP_NAME]['user']);
		unset($_SESSION[SSO_APP_NAME]['permission']);
		unset($_SESSION[SSO_APP_NAME]['server_last_update']);
		unset($_SESSION[SSO_APP_NAME]['return_code']);
		unset($_SESSION[SSO_APP_NAME]['message']);
	}
	function call($resource, $param=NULL, $out_type='xml')
	{
		if ($out_type!='xml')
		{
			$resource = $resource.'/format/'.$out_type;
		}
		$out = $this->sso_server->call_api($resource, $param);
		if ($out_type=='xml')
		{

			$this->logger->debug('data',$out	);
			$data_out =  simplexml_load_string($out->body);
		}
		else if($out_type = 'json')
		{
			$data_out =  json_decode($out->body);
		}
		return $data_out;
	}

}

if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}

		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

