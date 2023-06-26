<?php
class Sso_Storage_Memcache extends Sso_Storage { 
  private $connection = false;

  public function __construct($host, $port) {
    if (! function_exists('memcache_connect')) {
      throw new storageException("Memcache functions not available");
    }
    if ($host == '' || $port == '') {
      throw new storageException("You need to supply a valid memcache host and port");
    }
    $this->host = $host;
    $this->port = $port;
  }

  private function is_locked($key) {
    $this->check();
    if ((@memcache_get($this->connection, $key . '.lock')) === false) {
      return false;
    }
    return true;
  }

  private function create_lock($key) {
    $this->check();
     @memcache_add($this->connection, $key . '.lock', '', 0, 5);
  }

  private function remove_lock($key) {
    $this->check();
    @memcache_delete($this->connection, $key . '.lock');
  }

  private function wait_for_lock($key) {
    $this->check();
    // 20 x 250 = 5 seconds
    $tries = 20;
    $cnt = 0;
    do {
      usleep(250);
      $cnt ++;
    } while ($cnt <= $tries && $this->is_locked());
    if ($this->is_locked()) {
      $this->remove_lock($key);
    }
  }

 	private function connect() {
    	if (! $this->connection = @memcache_pconnect($this->host, $this->port)) {
      		throw new storageException("Couldn't connect to memcache server");
    	}
  	}

	private function check() {
    	if (! $this->connection) {
			$this->connect();
    	}
  	}

  	/**
   	* @inheritDoc
   	*/
  	public function get($key, $expiration = false) {
		$key = $this->storageKey . ":" . $key;

    	$this->check();
    	if (($ret = @memcache_get($this->connection, $key)) === false) {
     		return false;
    	}
    	if (! $expiration || (time() - $ret['time'] > $expiration)) {
			$this->delete($key);
			return false;
		}
		return $ret['data'];
	}

  /**
   * @inheritDoc
   */
  public function set($key, $value) {
	$key = $this->storageKey . ":" . $key;

    $this->check();
    // we store it with the cache_time default expiration so objects will atleast get cleaned eventually.
    if (@memcache_set($this->connection, $key, array('time' => time(), 
        'data' => $value), false) == false) {
      throw new storageException("Couldn't store data in cache");
    }
  }

  /**
   * @inheritDoc
   */
  public function delete($key) {
	$key = $this->storageKey . ":" . $key;

    $this->check();
    @memcache_delete($this->connection, $key);
  }
}