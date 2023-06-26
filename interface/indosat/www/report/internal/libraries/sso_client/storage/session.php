<?php
class Sso_Storage_Session extends Sso_Storage
{
	var $storageKey = NULL;
 
	public function get($key, $expiration = false) 
	{
		$key = $this->storageKey . ":" . $key;

		if( isset( $_SESSION[$key] ) ) 
		{ 
			return $_SESSION[$key] ; 
		}

		return NULL; 
	}

	public function set($key, $value)
	{
		$key = $this->storageKey . ":" . $key;

		$_SESSION[$key] = $value;
	}

	function delete($key)
	{
		if( isset( $_SESSION[$key] ) ) 
		{ 
			unset( $_SESSION[$key] );
		} 
	} 
}
