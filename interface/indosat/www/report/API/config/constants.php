<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',                            'rb');
define('FOPEN_READ_WRITE',                      'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',        'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',   'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',                    'ab');
define('FOPEN_READ_WRITE_CREATE',               'a+b');
define('FOPEN_WRITE_CREATE_STRICT',             'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',        'x+b');


/**
 * Application specific configuration.
 */
define('APPLICATION_NAME', 'reporting_api');
//define('DOMAIN', 'http://' . $_SERVER['SERVER_NAME'] . '/xms_fo/api/');
define('DOMAIN', 'http://localhost:8012/report/');
define('DIFF_PERCENTAGE', 20);

/**
 * Coloring 
 */
define('GREEN', '#5FEC96');
define('RED', 	'#FA9999');
define('GREY', 	'#EEE');

/**
 * Log related configuration.
 */
date_default_timezone_set('Asia/Jakarta');
define('LOG_PACKAGE',     APPPATH . 'libraries/pear_log/Log.php');
define('LOG_PATH',        '/app/xmp2012/interface/telkomsel/www/report/logs/api/');
define('LOG_LEVEL',       7);
define('LOG_FILENAME',    APPLICATION_NAME . '-' . date("Ymd"));
define('LOG_TIME_FORMAT', '%d-%m-%Y %H:%M:%S');
define('LOG_LINE_FORMAT', '%1$s [%3$s] %{file} %{class} %{function} line:%6$s, %4$s');


/**
 * Default Error messages
 */
define('RESPONSE_ERROR_MISSINGPARAM',	'Parameter %s is empty');
define('RESPONSE_ERROR_INVALIDPARAM',	'Parameter %s format is not right : %s');
define('RESPONSE_ERROR_NOTNUMERIC',	'Parameter %s should be numeric : %s');
define('RESPONSE_ERROR_NOTORDERTYPE',	'Parameter %s should be ASC or DESC : %s');
define('RESPONSE_ERROR_NOTMSGTYPE',	'Parameter %s should be MO, MT or DELIVERED : %s');
define('RESPONSE_ERROR_UNEXPECTEDQUERYRESULT',	'Unexpected query result : %s');
define('RESPONSE_ERROR_MYSQLERROR',	'Mysql error : %s');
define('RESPONSE_ERROR_UNKNOWNERROR',	'Unknown error : %s');

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */

