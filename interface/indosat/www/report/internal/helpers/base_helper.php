<?php
function write_log($type, $message){
    $logger = &load_class('Log');
    $existed_functions = array('error','info','debug','warning');
    if(in_array($type,$existed_functions))
        $logger->$type(str_replace("\n"," ","APP: ".$message));
}

function toString($array) {
    $pattern = array(
        "(\s+)",
        "(\t+)"
    );

    return preg_replace($pattern, ' ', print_r($array, 1));
}

function setDataSession($module,$data){
	$module = sprintf("%s",$module);
	if( isset($_SESSION['record']) ){
		foreach($_SESSION['record'] as $key => $row){
			if($key != $module){
				unset($_SESSION['record'][$key]);
			}
		}
	}
	$_SESSION['record'][$module] = $data;
}

function getDataSession($module){
	$module = sprintf("%s",$module);
	if(isset($_SESSION['record'][$module])){
		return $_SESSION['record'][$module];
	}
	else{
		return false;
	}
}