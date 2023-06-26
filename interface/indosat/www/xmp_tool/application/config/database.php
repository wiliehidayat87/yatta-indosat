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
$db['default']['username'] = 'newxmp';
$db['default']['password'] = '123456';
$db['default']['database'] = 'xmp_tool';
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

$db['xmp']['hostname'] = 'localhost';
$db['xmp']['username'] = 'newxmp';
$db['xmp']['password'] = '123456';
$db['xmp']['database'] = 'xmp';
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

$db['wap']['hostname'] = 'localhost';
$db['wap']['username'] = 'newwap';
$db['wap']['password'] = 'pa55Wap';
$db['wap']['database'] = 'wap';
$db['wap']['dbdriver'] = 'mysql';
$db['wap']['dbprefix'] = '';
$db['wap']['pconnect'] = TRUE;
$db['wap']['db_debug'] = TRUE;
$db['wap']['cache_on'] = FALSE;
$db['wap']['cachedir'] = '';
$db['wap']['char_set'] = 'utf8';
$db['wap']['dbcollat'] = 'utf8_general_ci';
$db['wap']['swap_pre'] = '';
$db['wap']['autoinit'] = TRUE;
$db['wap']['stricton'] = FALSE;

$db['reports']['hostname'] = "localhost";
$db['reports']['username'] = "newrpt";
$db['reports']['password'] = "p4SsRpt";
$db['reports']['database'] = "reports";
$db['reports']['dbdriver'] = "mysql";
$db['reports']['dbprefix'] = "";
$db['reports']['pconnect'] = TRUE;
$db['reports']['db_debug'] = TRUE;
$db['reports']['cache_on'] = FALSE;
$db['reports']['cachedir'] = "";
$db['reports']['char_set'] = "utf8";
$db['reports']['dbcollat'] = "utf8_general_ci";

$db['push']['hostname'] = 'localhost';
$db['push']['username'] = 'newpush';
$db['push']['password'] = '123456';
$db['push']['database'] = 'dbpush';
$db['push']['dbdriver'] = 'mysql';
$db['push']['dbprefix'] = '';
$db['push']['pconnect'] = TRUE;
$db['push']['db_debug'] = TRUE;
$db['push']['cache_on'] = FALSE;
$db['push']['cachedir'] = '';
$db['push']['char_set'] = 'utf8';
$db['push']['dbcollat'] = 'utf8_general_ci';
$db['push']['swap_pre'] = '';
$db['push']['autoinit'] = TRUE;
$db['push']['stricton'] = FALSE;

$db['crepo']['hostname'] = 'localhost';
$db['crepo']['username'] = 'crepo';
$db['crepo']['password'] = '123456';
$db['crepo']['database'] = 'contentrepo';
$db['crepo']['dbdriver'] = 'mysql';
$db['crepo']['dbprefix'] = '';
$db['crepo']['pconnect'] = TRUE;
$db['crepo']['db_debug'] = TRUE;
$db['crepo']['cache_on'] = FALSE;
$db['crepo']['cachedir'] = '';
$db['crepo']['char_set'] = 'utf8';
$db['crepo']['dbcollat'] = 'utf8_general_ci';
$db['crepo']['swap_pre'] = '';
$db['crepo']['autoinit'] = TRUE;
$db['crepo']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */
