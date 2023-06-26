<?php 
abstract class Singleton
{
	static abstract public function getInstance();
	static final protected function getClassInstance($klass, $args=NULL)
	{
		if(self::$instances === NULL)
			self::$instances = array();
		
		if(!array_key_exists($klass, self::$instances))
			self::$instances[$klass] = array();
		
		$key = serialize($args);

		// jika gak ada di instann
		if(!array_key_exists($key, self::$instances[$klass]))
		{
			 self::$instances[$klass][$key] = new $klass($args);
		}
       // Return instance $klass
		return self::$instances[$klass][$key];
	}
	//menyimpan object yang telah diinstant berdasrkan ID
	static private $instances;
}