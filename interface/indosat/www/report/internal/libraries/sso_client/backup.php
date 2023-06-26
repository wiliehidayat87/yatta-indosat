<?php


class sso_client
{
	/**
	* Pass 401 http response of the server to the client
	*/
	public $pass401=false;
 
	/**
	* Url of SSO server
	* @var string
	*/
    public $url = "http://sso-server/index.php/service/";
    
    /**
	* My identifier, given by SSO provider.
	* @var string
	*/
    public $application_name = "application1";
 
    /**
	* My secret word, given by SSO provider.
	* @var string
	*/
	public $secret = "application1";
 
    /**
	* Need to be shorter than session expire of SSO server
	* @var string
	*/
    public $session_expire = 1800;
    
    /**
	* Session hash
	* @var string
	*/
    protected $session_token;
    protected $request_token;
    protected $request_session;
    
    /**
	* User info recieved from the server.
	* @var array
	*/
    protected $userinfo;
    
    
    /**
	* Class constructor
	*/
    public function __construct($auto_attach=true)
    {
		session_start();
		$this->CI =& get_instance();
		//var_dump($_SESSION);
		$this->session_start();
        if (isset($_SESSION['session_token']))
        	$this->session_token = $_SESSION['session_token'];
	
        if (!($this->session_token)) {
            header("Location: " . $this->get_login_url() . "&redirect=". urlencode("http://{$_SERVER["SERVER_NAME"]}{$_SERVER["REQUEST_URI"]}"), true, 307);
            exit;
        }
        
    }
    
    public function session_start()
    {
		//jika menerima session
		$this->CI->load->library('crypt');
		$this->CI->crypt->set_key( $this->secret);
			
		if (isset($_GET[session_name()]) ) 
        {
			$sso_session 			= $this->CI->crypt->decrypt($_GET[session_name()]);
			$sso_data 				= explode(' ',$sso_session);
		//	if ($sso_data[2]!=session_id())die ('hacker?');
			$_SESSION['session_token'] = $sso_data[1];
			$this->request_token 	= $sso_data[0];
			//echo "call server <br>";
			$this->session_token = $_SESSION['session_token'];
			$server_session_data 	= $this->validateSession($sso_data[1]);
			//echo var_dump($server_session_data);
			if ($server_session_data[1])
			{
				
				//$_SESSION['session_token'] = $sso_data[1];
				$this->session_token = $_SESSION['session_token'];
				unset($_SESSION['user']);
				unset($_SESSION['server_last_update']);
				unset($_SESSION['return_code']);
				unset($_SESSION['message']);
				$_SESSION = array_merge($_SESSION,(array)json_decode($server_session_data[1]));
				//echo var_dump($_SESSION);

			}
			header('location:http://'.$_SERVER["SERVER_NAME"].'/');
			die ();

		}
		//cek curren login status
        else if (isset($_SESSION['session_token']))
        { 
			$this->session_token = $_SESSION['session_token'];
			//cek validity
			$delta = time() - $_SESSION['server_last_update'];
			echo $delta;
			if (!$_SESSION['server_last_update'] || ($delta>60) )
			{
				unset($_SESSION['user']);
				unset($_SESSION['server_last_update']);
				unset($_SESSION['return_code']);
				unset($_SESSION['message']);
				$server_session = $this->validateSession($_SESSION['session_token']);
				//echo var_dump($server_session);
				if ($server_session[1])
				{
					//$_SESSION['session_token'] = $_GET[session_name()];
					//$this->session_token = $_SESSION['session_token'];
					$_SESSION = array_merge($_SESSION,(array)json_decode($server_session[1]));
					if (!$_SESSION['user']) unset($_SESSION['session_token']);
				}			
			}
			
		}
	}
    /**
	* Get session token
	* @return string
	*/
    public function get_session_token()
    {

		//die($IP);
        return md5(session_id().' '.$this->application_name.' '.$this->secret.' '. $_SERVER['HTTP_USER_AGENT']);
    }
    
    /**
	* Generate session id from session key
	*
	* @return string
	*/
    protected function get_session_id()
    {
		if (!isset($this->session_token)) return null;
			return "SSO-{$this->application_name}-{$this->session_token}-" . md5('session' . $this->session_token . $_SERVER['REMOTE_ADDR'] . $this->secret);
    }
 
    /**
	* Get URL to attach session at SSO server
	*
	* @return string
	*/
    public function get_attach_url()
    {
		$token = $this->get_session_token();
		$checksum = md5("attach{$token}{$this->secret}");
        return "{$this->url}?cmd=attach&application_name={$this->application_name}&token=$token&checksum=$checksum";
    }
    
    /**
	* get Login Url
	* @return string
	*/
    public function get_login_url()
    {
		$token = $this->get_session_token();
		$checksum = md5("login{$token}{$this->secret}");
        return "{$this->url}?cmd=login&application_name={$this->application_name}&token=$token&checksum=$checksum&sid=".session_id();
    }
    
    /**
	* get Login Url
	* @return string
	*/
    public function get_logout_url()
    {
		$token = $this->get_session_token();
		$checksum = md5("logout{$token}{$_SERVER['REMOTE_ADDR']}{$this->secret}");
        return "{$this->url}?cmd=logout&application_name={$this->application_name}&token=$token&checksum=$checksum&sid=".session_id();
    }
    
    /**
	* Login at sso server.
	*
	* @param string $username
	* @param string $password
	* @return boolean
	*/
    public function login($username=null, $password=null)
    {
        if (!isset($username) && isset($_REQUEST['username'])) $username=$_REQUEST['username'];
        if (!isset($password) && isset($_REQUEST['password'])) $password=$_REQUEST['password'];
        
        list($ret, $body) = $this->server_cmd('login', array('username'=>$username, 'password'=>$password));
        
        switch ($ret) {
            case 200: $this->parse_info($body);
                      return 1;
            case 401: if ($this->pass401) header("HTTP/1.1 401 Unauthorized");
                      return 0;
            default: throw new Exception("SSO failure: The server responded with a $ret status" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) .'".' : '.'));
        }
    }
    
    /**
	* Logout at sso server.
	*/
    public function logout()
    {
        list($ret, $body) = $this->server_cmd('logout');
        if ($ret != 200) throw new Exception("SSO failure: The server responded with a $ret status" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) .'".' : '.'));
        
        return true;
    }
    
    
    /**
	* Set user info from user XML
	*
	* @param string $xml
	*/
    protected function parse_info($xml)
    {
        $sxml = json_decode($xml,session_name());
        echo var_dump($xml );
        $this->userinfo['identity'] = $sxml['identity'];
        foreach ($sxml as $key=>$value) $this->userinfo[$key] = (string)$value;
    }
    
    /**
	* Get user information.
	*/
    public function get_info()
    {
        if (!isset($this->userinfo)) {
            list($ret, $body) = $this->server_cmd('info');
 
            switch ($ret) {
                case 200: $this->parse_info($body); break;
                case 401: if ($this->pass401) header("HTTP/1.1 401 Unauthorized");
                          $this->userinfo = false; break;
                default: throw new Exception("SSO failure: The server responded with a $ret status" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) .'".' : '.'));
            }
        }
        
        return $this->userinfo;
    }
    
    /**
	* Ouput user information as XML
	*/
    public function info()
    {
        $this->get_info();
        
		 if (!$this->userinfo) {
		 header("HTTP/1.0 401 Unauthorized");
		 echo "Not logged in";
		 exit;
		 }
		
			header('Content-type: text/xml; charset=UTF-8');
		 echo '<?xml version="1.0" encoding="UTF-8" ?>', "\n";
		 echo '<user identity="' . htmlspecialchars($this->userinfo['identity'], ENT_COMPAT, 'UTF-8') . '">', "\n";
		
		 foreach ($this->userinfo as $key=>$value) {
		 if ($key == 'identity') continue;
		 echo "<$key>", htmlspecialchars($value, ENT_COMPAT, 'UTF-8'), "</$key>", "\n";
		 }
		
		 echo '</user>';
    }
    
 
    /**
	* Execute on SSO server.
	*
	* @param string $cmd Command
	* @param array $vars Post variables
	* @return array
	*/
    protected function server_cmd($cmd, $vars=null)
    {
        $curl = curl_init($this->url . '?cmd=' . urlencode($cmd));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, "PHPSESSID=" . $this->get_session_id());
 
        if (isset($vars)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $vars);
        }
        
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $body = curl_exec($curl);
        $ret = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl) != 0) throw new Exception("SSO failure: HTTP request to server failed. " . curl_error($curl));
        
        return array($ret, $body);
    }
    
    /**
    * Execute on SSO server.
	*/
    protected function validateSession($session)
    {
		$token1= $this->get_session_token();
		$token = $this->request_token;
	
		$checksum = md5("validate{$token}{$this->secret}");
        $real_url = "{$this->url}?cmd=validate&application_name={$this->application_name}&token=$token&checksum=$checksum&sid=".session_id()."&vid=".$_SESSION['session_token'];

        $curl = curl_init($real_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, "PHPSESSID=" . $this->get_session_id());
 
        if (isset($vars)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $vars);
        }
        
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $body = curl_exec($curl);
        $ret = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl) != 0) throw new Exception("SSO failure: HTTP request to server failed. " . curl_error($curl));
        
        return array($ret, $body);
    }
}
