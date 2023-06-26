<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class my_curl
{
    public $curlHandler;
    public $destination;
    public $parameters;

    public function __construct() {
        $this->parameters = array();

       // write_log('debug', 'Initializing cURL');
        //write_log('info', 'Initializing cURL');
        $this->curlHandler = curl_init();
        //write_log('info', 'cURL successfully initialized');
        //write_log('debug', 'cURL successfully initialized');
    }

    public function addParameter($key, $value) {
      //  write_log('debug', 'Adding parameter: ' . $key . ' with the value ' . toString($value, true));
        $this->parameters[$key] = $value;
    }

    public function execute($destination) {
        curl_setopt($this->curlHandler, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($this->curlHandler, CURLOPT_POST, true);
        curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $this->parameters);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandler, CURLOPT_URL, $destination);
        
        //write_log('debug', 'Executing cURL to ' . $destination . ' with the following parameters: ' . toString($this->parameters, true));
        $result = curl_exec($this->curlHandler);
        //write_log('debug', 'cURL result: ' . toString($result, true));
        return $result;
    }

    public function __destruct() {
        curl_close($this->curlHandler);
    }
}
