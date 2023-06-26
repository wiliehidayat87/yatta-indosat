<?php

class Sso_transport {
    
    public $cookie;
    public $follow_redirects 	= true;
    public $headers 			= array();
    public $options 			= array();
    public $referer;
    public $user_agent;
    protected $error 			= '';
    protected $request;
    
    function __construct() {
		/*
		log_message('debug', 'sso_transport Class Initialized');
        
        if (!function_exists('curl_init')) {
            log_message('error', 'No CURL Pluggin installer.') ;
        }
        * */
       // $this->cookie_file = APPPATH.'temp/cookies/'.session_id();
        $this->user_agent = 'SSO-CLIENT USER AGENT';
    }
    
    /**
     * Returns the error string of the current request if one occurred
     *
     * @return string
    **/
    function error() {
        return $this->error;
    }
    
    /**
     * Makes an HTTP GET request to the specified $url with an optional array or string of $vars
     *
     * Returns a sso_response object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars 
     * @return sso_response
    **/
    function get($url, $vars = array()) {
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
        }
       // echo var_dump($vars);
        //echo $url;
        return $this->request('GET', $url);
    }
    
    /**
     * Makes an HTTP HEAD request to the specified $url with an optional array or string of $vars
     *
     * Returns a sso_response object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars
     * @return sso_response
    **/
    function head($url, $vars = array()) {
        return $this->request('HEAD', $url, $vars);
    }
    
    /**
     * Makes an HTTP POST request to the specified $url with an optional array or string of $vars
     *
     * @param string $url
     * @param array|string $vars 
     * @return sso_response|boolean
    **/
    function post($url, $vars = array()) {
        return $this->request('POST', $url, $vars);
    }
    
    /**
     * Makes an HTTP PUT request to the specified $url with an optional array or string of $vars
     *
     * Returns a sso_response object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars 
     * @return sso_response|boolean
    **/
    function put($url, $vars = array()) {
        return $this->request('PUT', $url, $vars);
    }
    
    /**
     * Makes an HTTP request of the specified $method to a $url with an optional array or string of $vars
     *
     * Returns a sso_response object if the request was successful, false otherwise
     *
     * @param string $method
     * @param string $url
     * @param array|string $vars
     * @return sso_response|boolean
    **/
    function request($method, $url, $vars = array()) {
        $this->error = '';
        $this->request = curl_init();
        if (is_array($vars)) $vars = http_build_query($vars, '', '&');
        
        $this->set_request_method($method);
        $this->set_request_options($url, $vars);
        $this->set_request_headers();
        
        $response = curl_exec($this->request);
        
        if ($response) {
            $response = new sso_response($response);
        } else {
            $this->error = curl_errno($this->request).' - '.curl_error($this->request);
        }
        
        curl_close($this->request);
        
        return $response;
    }
    
    /**
     * Formats and adds custom headers to the current request
     *
     * @return void
     * @access protected
    **/
    protected function set_request_headers() {
        $headers = array();
        foreach ($this->headers as $key => $value) {
            $headers[] = $key.': '.$value;
        }
        curl_setopt($this->request, CURLOPT_HTTPHEADER, $headers);
    }
    
    /**
     * Set the associated CURL options for a request method
     *
     * @param string $method
     * @return void
     * @access protected
    **/
    protected function set_request_method($method) {
        switch (strtoupper($method)) {
            case 'HEAD':
                curl_setopt($this->request, CURLOPT_NOBODY, true);
                break;
            case 'GET':
                curl_setopt($this->request, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
        }
    }
    
    /**
     * Sets the CURLOPT options for the current request
     *
     * @param string $url
     * @param string $vars
     * @return void
     * @access protected
    **/
    protected function set_request_options($url, $vars) {
        curl_setopt($this->request, CURLOPT_URL, $url);
        if (!empty($vars)) curl_setopt($this->request, CURLOPT_POSTFIELDS, $vars);
        
        # Set some default CURL options
        curl_setopt($this->request, CURLOPT_HEADER, false);
        curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->request, CURLOPT_USERAGENT, $this->user_agent);
        /*
        if ($this->cookie_file) {
            curl_setopt($this->request, CURLOPT_COOKIEFILE, $this->cookie_file);
            curl_setopt($this->request, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        * */
        if ($this->cookie){
			$strCookie = 'PHPSESSID=' . session_name() . '; path=/';
			curl_setopt( $this->request, CURLOPT_COOKIE, $strCookie );
        
        }
        if ($this->follow_redirects) curl_setopt($this->request, CURLOPT_FOLLOWLOCATION, true);
        if ($this->referer) curl_setopt($this->request, CURLOPT_REFERER, $this->referer);
        
        # Set any custom CURL options
        foreach ($this->options as $option => $value) {
            curl_setopt($this->request, constant('CURLOPT_'.str_replace('CURLOPT_', '', strtoupper($option))), $value);
        }
    }

}
