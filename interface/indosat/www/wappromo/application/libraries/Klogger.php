<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/* Finally, A light, permissions-checking logging class. 
	 * 
	 * Author	: Kenneth Katzgrau < katzgrau@gmail.com >
	 * Date	: July 26, 2008
	 * Comments	: Originally written for use with wpSearch
	 * Website	: http://codefury.net
	 * Version	: 1.0
	 *
	 * Usage: 
	 *		$log = new KLogger ( "log.txt" , KLogger::INFO );
	 *		$log->LogInfo("Returned a million search results");	//Prints to the log file
	 *		$log->LogFATAL("Oh dear.");				//Prints to the log file
	 *		$log->LogDebug("x = 5");					//Prints nothing due to priority setting
	*/
	
	class Klogger
	{
		
		const DEBUG 	= 1;	// Most Verbose
		const INFO 		= 2;	// ...
		const WARN 		= 3;	// ...
		const ERROR 	= 4;	// ...
		const FATAL 	= 5;	// Least Verbose
		const OFF 		= 6;	// Nothing at all.
		
		const LOG_OPEN 		= 1;
		const OPEN_FAILED 	= 2;
		const LOG_CLOSED 	= 3;
		
		/* Public members: Not so much of an example of encapsulation, but that's okay. */
		public $Log_Status 	= KLogger::LOG_CLOSED;
		public $DateFormat	= "Y-m-d H:i:s";
		public $MessageQueue;
	
		private $log_file;
		private $log_dir;
		private $priority = KLogger::INFO;
		
		private $file_handle;
		private $is_active;
		protected $time;
		
		public function __construct( $filepath = '', $priority = KLogger::INFO)
		{
			if ( $priority == KLogger::OFF ) return;
			
            $CI =& get_instance();
            
            $this->is_active = $CI->config->item('klog');
            
			$this->log_dir = FCPATH.'logs';
			$this->log_file = $this->log_dir.'/log_'.date("Ymd").$filepath.".log";
			$this->MessageQueue = array();
			$this->priority = $priority;
            $this->time = microtime ();
                
            if($this->is_active == 1){
                if ( !is_dir( $this->log_dir ) )
                {
                    @mkdir($this->log_dir);
                    if ( !is_dir( $this->log_dir ) )
                    {
                        $this->MessageQueue[] = "Can't create logs folder.";
                    }
                }
                
                if ( !is_writable($this->log_dir) )
                {
                    //echo $this->log_dir;
                    @chmod($this->log_dir, 0777);
                    if ( !is_writable($this->log_dir) )
                    {
                        $this->MessageQueue[] = "The folder exists, but could not be used for writing. Check that appropriate permissions have been set.";
                    }
                }
                
                if ( file_exists( $this->log_file ) )
                {
                    if ( !is_writable($this->log_file) )
                    {
                        chmod($this->log_file, 0777); 
                        
                        if ( !is_writable($this->log_file) )
                        {
                            $this->Log_Status = KLogger::OPEN_FAILED;
                            $this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
                            return;
                        }
                    }else{
                        $this->MessageQueue[] = "The file exists and writable";
                    }
                }
                
                if ( $this->file_handle = fopen( $this->log_file , "a" ) )
                {
                    $this->Log_Status = KLogger::LOG_OPEN;
                    $this->MessageQueue[] = "The log file was opened successfully.";
                }
                else
                {
                    $this->Log_Status = KLogger::OPEN_FAILED;
                    $this->MessageQueue[] = "The file could not be opened. Check permissions.";
                }
			}
			return;
		}
		
		public function __destruct()
		{
			if ( $this->file_handle )
				fclose( $this->file_handle );
		}
		
		public function LogInfo($line)
		{
			$this->Log( $line , KLogger::INFO );
		}
		
		public function LogDebug($line)
		{
			$this->Log( $line , KLogger::DEBUG );
		}
		
		public function LogWarn($line)
		{
			$this->Log( $line , KLogger::WARN );	
		}
		
		public function LogError($line)
		{
			$this->Log( $line , KLogger::ERROR );		
		}

		public function LogFatal($line)
		{
			$this->Log( $line , KLogger::FATAL );
		}
		
		public function Log($line, $priority = KLogger::INFO)
		{
			if($this->is_active == 1){
                if ( $this->priority <= $priority )
                {
                    $status = $this->getTimeLine( $priority );
                    
                    $debug = debug_backtrace ();
                    $file = $debug [0] ['file'];
                    $fsplit = explode('/', $file);
                    $k = count($fsplit)-1;
                    $file = $fsplit[$k];
                    $class = $debug [1] ['class'];
                    $function = $debug [1] ['function'];
                    $runtime = substr ( (microtime () - $this->time), 0, 8);
                    
                    $this->WriteFreeFormLine ( "$status r:$runtime f:$file c:$class fn:$function tx:$line \n" );
                }
            }
		}
		
		public function WriteFreeFormLine( $line )
		{
			if ( $this->Log_Status == KLogger::LOG_OPEN && $this->priority != KLogger::OFF )
			{
			    if (fwrite( $this->file_handle , $line) === false) {
			        $this->MessageQueue[] = "The file could not be written to. Check that appropriate permissions have been set.";
			    }
			}
		}
		
		private function getTimeLine( $level )
		{
			$time = date( $this->DateFormat );
		
			switch( $level )
			{
				case KLogger::INFO:
					return "$time - INFO  -->";
				case KLogger::WARN:
					return "$time - WARN  -->";				
				case KLogger::DEBUG:
					return "$time - DEBUG -->";				
				case KLogger::ERROR:
					return "$time - ERROR -->";
				case KLogger::FATAL:
					return "$time - FATAL -->";
				default:
					return "$time - LOG   -->";
			}
		}
		
	}


?>
