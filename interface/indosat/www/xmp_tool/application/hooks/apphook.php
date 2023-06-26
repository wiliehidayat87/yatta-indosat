<?php
class AppHook{
	function pre_controller(){
	    $logger = & load_class('Log');
	    $logger->start_counter();
	    $logger->info('(SOR) Start Of Request');
	}
	function post_controller(){
	    $logger = & load_class('Log');
	    $logger->info('(EOR) End Of Request');
	}
}
