<?php

class Sso_Logger
{
    /**
     * Log a message at the debug level.
     *
     * @param $message The message to log.
     */
    public static function debug($message, $object = NULL)
	{
        
		if( SSO_AUTH_DEBUG_MODE )
		{
			$datetime = new DateTime();
		    $datetime =  $datetime->format(DATE_ATOM);
    
			file_put_contents
			( 
				SSO_AUTH_DEBUG_FILE, 
				"DEBUG -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
        }
    }

	// --------------------------------------------------------------------

    /**
     * Log a message at the info level.
     *
     * @param $message The message to log.
     */
    public static function info($message, $object = NULL)
	{
        
		if( SSO_AUTH_DEBUG_MODE )
		{
		    $datetime = new DateTime();
		    $datetime =  $datetime->format(DATE_ATOM);
    
			file_put_contents
			( 
				SSO_AUTH_DEBUG_FILE, 
				"DEBUG -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
        }
    }

	// --------------------------------------------------------------------

    /**
     * Log a message at the error level.
     *
     * @param $message The message to log.
     */
    public static function error($message, $object = NULL)
	{
       	if( SSO_AUTH_DEBUG_MODE )
		{
		    $datetime = new DateTime();
		    $datetime =  $datetime->format(DATE_ATOM);
    
			file_put_contents
			( 
				$GLOBAL_HYBRID_AUTH_DEBUG_FILE, 
				"ERROR -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $datetime . " -- " . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
        }
    }

}
