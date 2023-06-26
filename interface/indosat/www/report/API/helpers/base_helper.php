<?php
function write_log($type, $message){
    $logger = & load_class('Log');
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

/*
 * array_unique equvalent but can cast to multidimentional array
 */
function array_unique_multi($array) {
	$result = array_map("unserialize", array_unique(array_map("serialize", $array)));

  	foreach ($result as $key => $value) {
  		if ( is_array($value) ){
      		$result[$key] = array_unique_multi($value);
    	}
  	}

  	return $result;
}

function response($status,$message,$data=''){
	$response = json_encode(array(
		'status' 	=> $status,
		'message'	=> $message,
		'data'		=> $data
	));

//	write_log('info','RESPONSE: '.toString($response));
	return $response;
}

function respOK($data,$message='Success'){
	return response('OK',$message,$data);
}

function respNOK($message){
	return response('NOK',$message);
}

function auth($username,$password){
	write_log('info','checking authentication');
	$ci = get_instance();
	$auth = false;
	foreach($ci->config->item('auth') as $ac){
		if($ac['username']==$username && $ac['password']==$password) $auth = true;
	}

	if($auth == true){
		write_log('info','authentication valid');
	}
	else{
		write_log('error','authentication invalid');
	}
	return $auth;
}

function checkAuth($username,$password){
	if(!auth($username,$password)){
		echo respNOK('invalid authentication');
		exit;
	}
	return true;
}