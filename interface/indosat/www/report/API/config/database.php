<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
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
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "newrpt";
$db['default']['password'] = "p4SsRpt";
$db['default']['database'] = "reports";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = false;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

$db['traffic']['hostname'] = "localhost";
$db['traffic']['username'] = "newxmp";
$db['traffic']['password'] = "123456";
$db['traffic']['database'] = "xmp";
$db['traffic']['dbdriver'] = "mysql";
$db['traffic']['dbprefix'] = "";
$db['traffic']['pconnect'] = TRUE;
$db['traffic']['db_debug'] = false;
$db['traffic']['cache_on'] = FALSE;
$db['traffic']['cachedir'] = "";
$db['traffic']['char_set'] = "utf8";
$db['traffic']['dbcollat'] = "utf8_general_ci";

$db['archive']['hostname'] = "localhost";
$db['archive']['username'] = "root";
$db['archive']['password'] = "1234567";
$db['archive']['database'] = "mmscms";
$db['archive']['dbdriver'] = "mysql";
$db['archive']['dbprefix'] = "";
$db['archive']['pconnect'] = TRUE;
$db['archive']['db_debug'] = false;
$db['archive']['cache_on'] = FALSE;
$db['archive']['cachedir'] = "";
$db['archive']['char_set'] = "utf8";
$db['archive']['dbcollat'] = "utf8_general_ci";

/* End of file database.php */
/* Location: ./system/application/config/atabase.php */
