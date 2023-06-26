<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


/**
 * Reporting application specific configuration.
 */
define('DOMAIN', 'http://localhost:20001/xmp_tool/report/');
define('API_URL', 'http://localhost:20001/API/internal/');
define('LIMIT', 10);
define('API_USERNAME', 'kb');
define('API_PASSWORD', '123456');
define('DEFAULT_SHORTCODE', '43430');
define('CONTENT_TYPE', '{"TRUE":"Truetone","POLY":"Poly","MONO":"Mono","GAMES":"Games","COLOR":"Wallpaper","COLORTXT":"Tattoo","VIDEO":"Video"}');

define('MAX_DASHBOARD_CHART', 8);
define('X_AXIS_POINT', 10);
define('X_AXIS_STEP', 1);
define('Y_AXIS_STEP', 5);


/**
 * Log related configuration.
 */
date_default_timezone_set('Asia/Jakarta');
define('LOG_PACKAGE', APPPATH . 'libraries/pear_log/Log.php');
define('LOG_PATH', FCPATH . APPPATH . 'logs/');
define('LOG_LEVEL', 6);
define('LOG_FILENAME', 'base-' . date("Ymd"));
define('LOG_TIME_FORMAT', '%d-%m-%Y %H:%M:%S');
define('LOG_LINE_FORMAT', '%1$s [%3$s] %{file} %{class} %{function} line:%6$s, %4$s');


/**
 * Default Error messages (Reporting)
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
/* Location: ./application/config/constants.php */
