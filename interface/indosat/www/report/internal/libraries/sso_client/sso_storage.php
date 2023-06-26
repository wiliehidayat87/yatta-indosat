<?php
class storageException extends Exception {}

abstract class Sso_Storage {
	var $storageKey = NULL;

	abstract protected function get($key, $expiration = false);
	abstract protected function set($key, $value);
	abstract protected function delete($key);
}


