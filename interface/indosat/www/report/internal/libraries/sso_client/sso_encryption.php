<?
    
class sso_encryption {
    /**
     * Hashed value of the user provided encryption key
     * @var    string
     **/
    var $hash_key;
    /**
     * String length of hashed values using the current algorithm
     * @var    int
     **/    
    var $hash_lenth;
    /**
     * Switch base64 enconding on / off
     * @var    bool    true = use base64, false = binary output / input
     **/    
    var $base64;
    /**
     * Secret value added to randomize output and protect the user provided key
     * @var    string    Change this value to add more randomness to your encryption
     **/    
    var $salt = 'd41d8cd98f00b204e9800998ecf8427e';
    
    function __construct() 
    {
		//log_message('debug', 'SSO_encription Class Initialized');
        
    }

    /**
     * Set encription key
     */
    function set_key($key,$base64 = true) 
    {
        $this->base64 = $base64;
        $this->hash_key = $this->_hash($key);
        $this->hash_length = strlen($this->hash_key);
    }
        
    /**
     * Method used for encryption
     */
    function encrypt($string) 
    {
        $iv = $this->_generate_iv();
        
        $out = '';
        
        for($c=0;$c < $this->hash_length;$c++) {
            $out .= chr(ord($iv[$c]) ^ ord($this->hash_key[$c]));
        }

        $key = $iv;
        $c = 0;

        while($c < strlen($string)) {
            if(($c != 0) and ($c % $this->hash_length == 0)) 
            {
                $key = $this->_hash($key . substr($string,$c - $this->hash_length,$this->hash_length));
            }
    
            $out .= chr(ord($key[$c % $this->hash_length]) ^ ord($string[$c]));
            $c++;
        }
    
        if($this->base64) $out = $this->url_base64_encode($out);
        return $out;
    }
    
    /**
     * Method used for decryption
     */
    function decrypt($string) {
        
        if($this->base64) $string = $this->url_base64_decode($string);
        
        $tmp_iv = substr($string,0,$this->hash_length);
        
        $string = substr($string,$this->hash_length,strlen($string) - $this->hash_length);
        $iv = $out = '';
        
        for($c=0;$c < $this->hash_length;$c++) {
            $iv .= chr(ord($tmp_iv[$c]) ^ ord($this->hash_key[$c]));
        }
        
        $key = $iv;
        $c = 0;
        
        while($c < strlen($string)) {
            if(($c != 0) and ($c % $this->hash_length == 0)) {
                $key = $this->_hash($key . substr($out,$c - $this->hash_length,$this->hash_length));
            }
            $out .= chr(ord($key[$c % $this->hash_length]) ^ ord($string[$c]));
            $c++;
        }
        return $out;
    }

   function _hash($string) {
        if(function_exists('sha1')) {
            $hash = sha1($string);
        } else {
            $hash = md5($string);
        }
        $out ='';
        
        for($c=0;$c<strlen($hash);$c+=2) 
        {
            $out .= $this->_hex2chr($hash[$c] . $hash[$c+1]);
        }
        return $out;
    }
    
    /**
     * Generate a random string to initialize encryption
     **/
    private function _generate_iv() {
        srand ((double)microtime()*1000000);
        
        $iv  = $this->salt;
        $iv .= rand(0,getrandmax());
     
        $iv .= serialize($GLOBALS);
        return $this->_hash($iv);
    }
	
	private function url_base64_encode(&$str="")
	{
		return strtr(
				base64_encode($str),
				array(
					'+' => '.',
					'=' => '-',
					'/' => '~'
				)
			);
	}

	private function url_base64_decode(&$str="")
	{
		return base64_decode(strtr(
				$str,
				array(
					'.' => '+',
					'-' => '=',
					'~' => '/'
				)
			));
	}


    private function _hex2chr($num) {
        return chr(hexdec($num));
    }
}
