<?php 
class Sso_Auth 
{
	static private $instances;

	public static function storage( )
	{
		return self::$instances;
	}
   
	public static function startStorage( )
	{
		
		$authStore = "Hybrid_Storage_".SSO_AUTH_STORAGE_TYPE;

		switch( SSO_AUTH_STORAGE_TYPE )
		{
			case "Session"	:	self::$instances = new $authStore();
								break;
			case "File"		:	self::$instances = new $authStore( SSO_AUTH_STORAGE_PATH );
								break;
			case "Apc"		:
			case "Memcache"	:	self::$instances = new $authStore( SSO_AUTH_STORAGE_HOST,SSO_AUTH_STORAGE_PASS); 
								break;
			default			: 	throw new Exception( "Unknwon Storage type [SSO_AUTH_STORAGE_TYPE], check " .
								"\Cek SSO_AUTH_STORAGE_TYPE value in  configuration file!" );
		}

		self::$instances->storageKey = session_id();

		return TRUE;
	}

	// --------------------------------------------------------------------

   /**
	* expire some saved vars for the current connected user
	*
	* @see    Hybrid_Storage 
	*/
	public static function expireStorage()
	{
		Sso_Auth::storage()->delete( "hauth_session.auth_token"        );
		Sso_Auth::storage()->delete( "hauth_session.auth_time"         );

		Sso_Auth::storage()->delete( "hauth_session.is_logged_in"       );
		Sso_Auth::storage()->delete( "hauth_session.user.data"          );

		Sso_Auth::storage()->delete( "hauth_session.error.status"       );
		Sso_Auth::storage()->delete( "hauth_session.error.message"      );
		Sso_Auth::storage()->delete( "hauth_session.error.code"         );
		Sso_Auth::storage()->delete( "hauth_session.error.trace"        );

		Sso_Auth::storage()->delete( "hauth_session.warning"            );

		Sso_Auth::storage()->delete( "hauth_session.id_provider_id"     );
		Sso_Auth::storage()->delete( "hauth_session.id_provider_params" );

		Sso_Auth::storage()->delete( "hauth_session.auth_endpoint"     );
		Sso_Auth::storage()->delete( "hauth_session.auth_return_to"    );
	}

   /**
	* Factory for SSO_IdProvider classes.
	*/ 
	public static function setup( $providerId, $params = NULL )
	{
		# instantiate a new IDProvider Adapter
		$provider   = new Sso_Provider_Adapter();

		try
		{
			$provider->factory( $providerId, $params );
		}
		catch( Exception $e )
		{
			Sso_Auth::setError( $e->getMessage(), $e->getCode(), $e->getTraceAsString() ); 
			return NULL;
		}

		return $provider;
	}

	// --------------------------------------------------------------------

   /**
	* Wakeup the current user session if true == SSO_client::hasSession() 
	*/
	public static function wakeup( $hauthSession = NULL )
	{
		# if user has a session and loggedin IDP service, 
		if( ! self::hasSession() )
		{
			return NULL;
		}

		$params     = self::storage()->get( "auth_session.id_provider_params" );
		$providerId = self::storage()->get( "auth_session.id_provider_id"     );

		# try to re setup the IDProvider Adapter instance
		$provider = new Sso_Provider_Adapter();

		return $provider->factory( $providerId, $params );
	}

	// --------------------------------------------------------------------

   /**
	* Checks to see if there is a session in this PHP page request.
	*/
	public static function has_session()
	{
		# if hauth_session.hauth_token is set on storage system &
		# if hauth_session.hauth_token is equal to current session_id() &
		# if user is loggedin hauth_session.is_logged_in = TRUE
		return 
			( self::storage()->get( "auth_session.hauth_token" ) == session_id() )
			&& 
			self::storage()->get( "auth_session.is_logged_in" ) ;
	}

	// --------------------------------------------------------------------

   /**
	* Checks to see if there is a stored warning.
	*/
	public static function has_warning()
	{
		return 
			(bool) self::storage()->get( "auth_session.warning" );
	}

	// --------------------------------------------------------------------

   /**
	* Add a warning message to warns stak.  
	*/
	public static function add_warning( $message )
	{
		$_warn = self::storage()->get( "auth_session.warning" );

		$_warn[$message] = time();

		Sso_Auth::storage()->set( "auth_session.warning", $_warn );
	}

	// --------------------------------------------------------------------

   /**
	* a naive warning message getter
	*/
	public static function get_warning_message()
	{
		$_warn = self::storage()->get( "auth_session.warning" );
		$_mesg = "";

		if( is_array( $_warn ) )
		{
			foreach( $_warn as $m => $t )
			{
				$_mesg .= "@$t: $m\n";
			}
		}

		return $_mesg;
	}

	// --------------------------------------------------------------------

   /**
	* store error in Auth cache system
	*/
	public static function set_error( $message, $code = NULL, $trace = NULL )
	{
		Sso_Auth::storage()->set( "auth_session.error.status" , 1        );
		Sso_Auth::storage()->set( "auth_session.error.message", $message );
		Sso_Auth::storage()->set( "auth_session.error.code"   , $code    );
		Sso_Auth::storage()->set( "auth_session.error.trace"  , $trace   );
	}

	// --------------------------------------------------------------------

   /**
	* Checks to see if there is a an error.
	* @return boolean True if there is an error.
	*/
	public static function has_error()
	{
		return 
			(bool) self::storage()->get( "auth_session.error.status" );
	}

	// --------------------------------------------------------------------

   /**
	* a naive error message getter
	*/
	public static function get_error_message()
	{
		return
			self::storage()->get( "auth_session.error.message" );
	}

	// --------------------------------------------------------------------

   /**
	* a naive error code getter
	*/
	public static function get_error_code()
	{
		return 
			self::storage()->get( "auth_session.error.code" );
	}

	// --------------------------------------------------------------------

   /**
	* a naive error backtrace getter
	*/
	public static function get_error_trace()
	{
		return 
			self::storage()->get( "auth_session.error.trace" );
	}

	// --------------------------------------------------------------------

   /**
	* Checks to see if there is a an error.
	*/
	public static function redirect( $url )
	{
		if( SSO_AUTH_REDIRECT_MODE == "PHP" )
		{
			header( "Location: $url" ) ;
		}
		elseif( SSO_HYBRID_AUTH_REDIRECT_MODE == "JS" )
		{
			echo '<html>';
			echo '<head>';
			echo '<script type="text/javascript">';
			echo 'function redirect(){ window.top.location.href="' . $url . '"; }';
			echo '</script>';
			echo '</head>';
			echo '<body onload="redirect()">';
			echo '</body>';
			echo '</html>'; 
		}

		exit( 0 );
	}

	// --------------------------------------------------------------------

   /**
	* @return string the current url for requested PHP page.
	*/
	public static function get_current_url() 
	{
		$scheme = 'http';

		if ( isset( $_SERVER['HTTPS'] ) and $_SERVER['HTTPS'] == 'on' )
		{
			$scheme .= 's';
		}

		return sprintf
		(
			"%s://%s:%s%s"				, 
			$scheme						, 
			$_SERVER['SERVER_NAME']		, 
			$_SERVER['SERVER_PORT']		, 
			$_SERVER['PHP_SELF']
		); 
	}

	// --------------------------------------------------------------------

}