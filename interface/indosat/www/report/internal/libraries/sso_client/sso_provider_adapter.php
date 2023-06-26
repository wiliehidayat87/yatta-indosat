<?php

class Sso_Provider_Adapter
{
   /**
	* IDp ID (or unique name)
	*/
	var $id       = NULL ;

   /**
	* IDp adapter config on hybrid.config.php
	*/
	var $config   = NULL ;

   /**
	* IDp adapter requireds params
	*/
	var $params   = NULL ; 

   /**
	* IDp adapter path
	*/
	var $wrapper  = NULL ;

   /**
	* IDp adapter instance
	*/
	var $adapter  = NULL ;

    /**
     * create a new adapter switch IDp name or ID
     *
     * @param string  $id      The id or name of the IDp
     * @param array   $params  (optional) required parameters by the adapter 
     */
	function factory( $id, $params = NULL )
	{
		$GLOBAL_HYBRID_AUTH_PATH_CORE; 

		# init the adapter config and params
		$this->id     = $id;
		$this->params = $params;
		$this->config = $this->getConfigById( $this->id );

		# check the IDp config
		if( ! $this->config )
		{
			throw new Exception( "Unknown IdProvider [{$this->id}], check your configuration file!" ); 
		}

		# check the IDp adapter is enabled
		if( ! $this->config["enabled"] )
		{
			throw new Exception( "IdProvider [{$this->id}] is not enabled!" );
		}

		# check the IDp has a configured wrapper 
		if( empty( $this->config["wrapper"] ) )
		{
			throw new Exception( "Path to the provider wrapper of [{$this->id}] is set to NULL!" );
		}

		# include the adapter wrapper
		require_once $GLOBAL_HYBRID_AUTH_PATH_CORE . "/" . $this->config["wrapper"] ;

		$this->wrapper = "Hybrid_Providers_" . $this->id;

		# create the adapter instance, and pass the current params and config
		$this->adapter = new $this->wrapper( $this->id, $this->config, $this->params );

		return $this;
	}

	// --------------------------------------------------------------------

   /**
	* Naive getter of the current adapter instance
	*/
	function adapter()
	{
		return $this->adapter->adapter;
	}

	// --------------------------------------------------------------------

   /**
	* Naive getter of the current loggedin user
	*/
	function user()
	{
		return $this->adapter->user;
	}

   /**
	* Naive getter of the current connected IDp API client
	*/
	function api()
	{
		return $this->adapter->api;
	}

	// --------------------------------------------------------------------

    /**
     * This is the methode that should be specified when a user requests a sign in whith an IDp.
     * 
     * Hybrid_Provider_Adapter::login(), prepare the user session and the authentification request
	 * for hybrid.endpoint.php
     */
	function login()
	{
		GLOBAL $GLOBAL_HYBRID_AUTH_URL_EP;  
		GLOBAL $GLOBAL_HYBRID_AUTH_BYPASS_EP_MODE; 

		if( ! $this->adapter )
		{
			return NULL;
		}

		# we make use of session_id() as storage hash to identify the current user
		# using session_regenerate_id() will be a problem, but ..
		$hauth_token = session_id();

		# set request timestamp
		$hauth_time  = time();

		# set start login URL and Login Callback URL,
		# developer are free to change default HybridAuth endpoint urls by using
		# hauth_login_start_url and hauth_login_done_url parameters

		# for default HybridAuth endpoint url hauth_login_start_url
		# 	auth.start  required  the IDp ID
		# 	auth.token  optional  the user seesion_id
		# 	auth.time   optional  login request timestamp

		# for default HybridAuth endpoint url hauth_login_done_url
		# 	auth.done   required  the IDp ID
		# 	auth.token  optional  the user seesion_id
		# 	auth.time   optional  login request timestamp
		$login_start = isset( $this->params["hauth_login_start_url"] ) && $this->params["hauth_login_start_url"]
							? $this->params["hauth_login_start_url"] : 
								(
									$GLOBAL_HYBRID_AUTH_URL_EP . ( strpos( $GLOBAL_HYBRID_AUTH_URL_EP, '?' ) ? '&' : '?' ) .
										"hauth.start={$this->id}&hauth.token={$hauth_token}&hauth.time={$hauth_time}"
								);

		$login_done  = isset( $this->params["hauth_login_done_url"] ) && $this->params["hauth_login_done_url"]
							? $this->params["hauth_login_done_url"] : 
								(
									$GLOBAL_HYBRID_AUTH_URL_EP . ( strpos( $GLOBAL_HYBRID_AUTH_URL_EP, '?' ) ? '&' : '?' ) .
										"hauth.done={$this->id}&hauth.token={$hauth_token}&hauth.time={$hauth_time}"
								);

		# reset the saved session for the current user 
		Hybrid_Auth::expireStorage();
		Hybrid_Auth::storage()->set( "hauth_session.hauth_token"		, $hauth_token  );
		Hybrid_Auth::storage()->set( "hauth_session.hauth_time"		    , $hauth_time  );
		Hybrid_Auth::storage()->set( "hauth_session.hauth_return_to"	, $this->params["hauth_return_to"] );
		Hybrid_Auth::storage()->set( "hauth_session.hauth_endpoint"	    , $login_done   );
		Hybrid_Auth::storage()->set( "hauth_session.id_provider_id"		, $this->id     );
		Hybrid_Auth::storage()->set( "hauth_session.id_provider_params"	, $this->params ); 

		# if by pass endpoint, we return to hauth_return_to
		# //! only used for testing purpose !\\
		if( $GLOBAL_HYBRID_AUTH_BYPASS_EP_MODE )
		{
			Hybrid_Auth::addWarning( Hybrid_Auth::text( "BYPASS_EP_MODE_ACTIVATED" ) ); 

			Hybrid_Auth::storage()->set( "hauth_session.is_logged_in", 1 );

			# Populate the user profile with some fucked data, if $GLOBAL_HYBRID_AUTH_BYPASS_EP_MODE = TRUE 
			$this->adapter->user->populateProfile();

			Hybrid_Auth::storage()->set( "hauth_session.user.data", $this->adapter->user );

			# callback the return_to callback url
			Hybrid_Auth::redirect( $this->params["hauth_return_to"] );
		} 

		Hybrid_Auth::redirect( $login_start );
	}

	// --------------------------------------------------------------------

   /**
	* just expire local user session, but dont logout the user from the IDp
	*/
	function deconnect()
	{
		$this->adapter->deconnect();
	} 

	// --------------------------------------------------------------------

   /**
	* sign out the user from the IDp if possible, and erase his local session, 
	*/
	function logout()
	{
		$this->adapter->logout();

		$this->deconnect(); 
	}

	// --------------------------------------------------------------------

   /**
	* Refresh the user data (profile/contacts) from the IDp API if $fromCache is FALSE
	* else 
	* if we return the stored user data in HybridAuth storage system
	*   
	* Note: HybridAuth dont provide users contats on this version
	*/
	function getUserData( $fromCache = TRUE )
	{
		# if we are asked to recuperate the cached user data
		if( $fromCache && Hybrid_Auth::storage()->get( "hauth_session.user.data" ) )
		{
			$this->adapter->user = Hybrid_Auth::storage()->get( "hauth_session.user.data" );
		}
		# else, we force the IDp adapter to refresh them by using their api clients
		else
		{
			$this->adapter->getUserProfile();

			# for future use
			#    $this->adapter->getUserContacts();

			# re store the user data for future uses
			Hybrid_Auth::storage()->set( "hauth_session.user.data", $this->adapter->user );
		}

		return $this->adapter->user;
	}

	// --------------------------------------------------------------------

    /**
     * find the IDp config on hybrid.config.php HybridAuth configuration file
     *
     * @param string  $id      The id or name of the IDp
     *
     * @return array if the IDp config founded on $GLOBAL_HYBRID_AUTH_IDPROVIDERS
     * @return NULL if no match
     */
	function getConfigById( $id )
	{
		GLOBAL $GLOBAL_HYBRID_AUTH_IDPROVIDERS; 

		if( isset( $GLOBAL_HYBRID_AUTH_IDPROVIDERS[$id] ) )
		{ 
			return $GLOBAL_HYBRID_AUTH_IDPROVIDERS[$id];
		}

		return NULL;
	}

	// --------------------------------------------------------------------

   /**
	* redirect the user to hauth_return_to (the callback url)
	*/
	function returnToCallbackUrl()
	{
		Hybrid_Auth::redirect( Hybrid_Auth::storage()->get( "hauth_session.hauth_return_to" ) );
	}
}
