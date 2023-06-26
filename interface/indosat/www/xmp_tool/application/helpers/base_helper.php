<?php

/*
 * 
 *  Base helper for XMP
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-10-03 12:13:25 +0700 (Wed, 03 Oct 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2937 $
 * 
 */

function write_log($type, $message) {
    $logger = &load_class('Log');
    $existed_functions = array('error','info','debug','warning');

    if(in_array($type,$existed_functions)) {
        $logger->$type(str_replace("\n"," ","APP: ".$message));
        //var_dump($type, $message);
    }
}

/*
 * Begin - Helper for Report API
 */
function response($status,$message,$data=''){
    $response = json_encode(array(
        'status'    => $status,
        'message'   => $message,
        'data'      => $data
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

/*
 * End - Helper for Report API
 */
