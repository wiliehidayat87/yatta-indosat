<?php
class default_mo_parser extends mo_parser{
    
        private static $instance;
	
	//-----------------------------------------
	
        public static function getInstance() {
		
		if (! self::$instance) {
			self::$instance = new self ( );
		}
		
		return self::$instance;
	}
        
        //----------------------------------------
        
        public function parseMessage($mo_data){}
        
}
?>