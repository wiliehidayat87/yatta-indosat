<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '123456';
$db['default']['database'] = 'mmscms';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['newwap']['hostname'] = 'localhost';
$db['newwap']['username'] = 'root';
$db['newwap']['password'] = '123456';
$db['newwap']['database'] = 'yatta_wap';
$db['newwap']['dbdriver'] = 'mysql';
$db['newwap']['dbprefix'] = '';
$db['newwap']['pconnect'] = TRUE;
$db['newwap']['db_debug'] = TRUE;
$db['newwap']['cache_on'] = FALSE;
$db['newwap']['cachedir'] = '';
$db['newwap']['char_set'] = 'utf8';
$db['newwap']['dbcollat'] = 'utf8_general_ci';
$db['newwap']['swap_pre'] = '';
$db['newwap']['autoinit'] = TRUE;
$db['newwap']['stricton'] = FALSE;

$db['xmp']['hostname'] = '192.168.24.87';
$db['xmp']['username'] = 'johan';
$db['xmp']['password'] = 'johanferdianto';
$db['xmp']['database'] = 'wap_xmp_new';
$db['xmp']['dbdriver'] = 'mysql';
$db['xmp']['dbprefix'] = '';
$db['xmp']['pconnect'] = TRUE;
$db['xmp']['db_debug'] = TRUE;
$db['xmp']['cache_on'] = FALSE;
$db['xmp']['cachedir'] = '';
$db['xmp']['char_set'] = 'utf8';
$db['xmp']['dbcollat'] = 'utf8_general_ci';
$db['xmp']['swap_pre'] = '';
$db['xmp']['autoinit'] = TRUE;
$db['xmp']['stricton'] = FALSE;


$db['smswebtool']['hostname'] = '192.168.24.17';
$db['smswebtool']['username'] = 'root';
$db['smswebtool']['password'] = '';
$db['smswebtool']['database'] = 'wap_smswebtool';
$db['smswebtool']['dbdriver'] = 'mysql';
$db['smswebtool']['dbprefix'] = '';
$db['smswebtool']['pconnect'] = TRUE;
$db['smswebtool']['db_debug'] = TRUE;
$db['smswebtool']['cache_on'] = FALSE;
$db['smswebtool']['cachedir'] = '';
$db['smswebtool']['char_set'] = 'utf8';
$db['smswebtool']['dbcollat'] = 'utf8_general_ci';
$db['smswebtool']['swap_pre'] = '';
$db['smswebtool']['autoinit'] = TRUE;
$db['smswebtool']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */
