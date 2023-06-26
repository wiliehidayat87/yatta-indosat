<?php
class AppHook{
	function pre_controller(){
	    $logger = & load_class('Log');
	    $logger->start_counter();
	    $logger->info('(SOR) Start Of Request');
	    
		if(stripos($_SERVER['REQUEST_URI'],'?')!==false){
            list(,$query_string)= explode('?',$_SERVER['REQUEST_URI']);
            parse_str($query_string, $_GET);
            foreach ($_GET as $key=>$val) {
              $_GET[$key] = $val;
            }
        }
	}
	function post_controller(){
	    $logger = & load_class('Log');
	    $logger->info('(EOR) End Of Request');
	}
}
