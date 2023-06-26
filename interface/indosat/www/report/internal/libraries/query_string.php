<?php
class Query_String{
    public function __construct()
    {
        if(stripos($_SERVER['REQUEST_URI'],'?')!==false){
            list(,$query_string)= explode('?',$_SERVER['REQUEST_URI']);
            parse_str($query_string, $_GET);
            $_CI =& get_instance();
            foreach ($_GET as $key=>$val) {
              $_GET[$key] = $_CI->input->xss_clean($val);
            }
        }
    }   
}