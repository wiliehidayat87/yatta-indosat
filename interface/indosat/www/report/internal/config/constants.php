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
define('DOMAIN', 'http://202.53.250.242:20001/report/internal/');
define('API_URL', 'http://192.168.0.21:20001/report/API/internal/');
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
 * SSO specific configuration.
 */
// define('SSO_CLIENT_VERSION', '1.0-beta');
// define('SSO_APP_NAME', 'XMS_FO_INTERNAL_REPORT');
// define('SSO_APP_PASSWORD', 'xmsfointernalreport');
// define('SSO_APP_URL', 'http://192.168.24.30/sso_server');
// define('SSO_AUTH_DEBUG_MODE', false);
// define('SSO_AUTH_DEBUG_FILE', '/app/xms_fo/logs/sso/sso_debug_file');
// define('SSO_AUTH_STORAGE_TYPE', 'Session') ;
// define('SSO_AUTH_STORAGE_PATH', '/app/xms_fo/logs/sso/') ;
// define('SSO_AUTH_STORAGE_HOST', null);
// define('SSO_AUTH_STORAGE_PASS', null);

/**
 * Interface related configuration.
 */
define('CSS_PATH',    DOMAIN . 'asset/css/');
define('IMAGE_PATH',  DOMAIN . 'asset/image/');
define('JS_PATH',     DOMAIN . 'asset/js/');
define('PLUGIN_PATH', DOMAIN . 'asset/plugin/');

/**
 * Smarty related configuration.
 */
define('COMPILE_PATH',  '/tmp/');
define('TEMPLATE_PATH', FCPATH . 'views');
define('TMP_PATH',      FCPATH . 'tmp/');

/**
 * Log related configuration.
 */
date_default_timezone_set('Asia/Jakarta');
define('LOG_PACKAGE',     APPPATH . 'libraries/pear_log/Log.php');
define('LOG_PATH',        '/app/xmp2012/interface/telkomsel/www/report/logs/');
define('LOG_LEVEL',       7);
define('LOG_FILENAME',    'internal-' . date("Ymd"));
define('LOG_TIME_FORMAT', '%d-%m-%Y %H:%M:%S');
define('LOG_LINE_FORMAT', '%1$s [%3$s] %{file} %{class} %{function} line:%6$s, %4$s');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */

