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
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
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

$isProdServer = 0;

$active_group = '';
$active_record = TRUE;

switch (ENVIRONMENT)
{
	case 'development':
		$isProdServer = 0;
	$active_group = 'MKTFAB1';
	break;

	case 'testing':
	case 'production':
		$isProdServer = 1;
		$active_group = 'MKT01';
	break;

	default:
		exit('The application environment is not set correctly.');
}



if($isProdServer == 1){
//——————————————————————————————————————E
$dbhost = '10.49.2.24';    
$dbport = '1525';    
$dbname= 'MBSHO';    
$dbConnString = ' (DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = '. $dbhost .')(PORT = '. $dbport .')) ) (CONNECT_DATA = (SERVICE_NAME = '. $dbname .') ))';
$db['makroho']['username'] = 'SOLUCIONES';
$db['makroho']['password'] = 'm4nng3g3nm4nn';
//————————————————————————————————————————
} else {
//——————————————————————————————————————E
$dbhost = '10.49.3.94';    
$dbport = '1525';    
$dbname= 'ARMHODE1';    
$dbConnString = ' (DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = '. $dbhost .')(PORT = '. $dbport .')) ) (CONNECT_DATA = (SERVICE_NAME = '. $dbname .') ))';
$db['makroho']['username'] = 'dba_ho';
$db['makroho']['password'] = 'Externo1';
//————————————————————————————————————————
}
$db['makroho']['hostname'] = $dbConnString;
$db['makroho']['database'] = '';
$db['makroho']['dbdriver'] = 'oci8';
$db['makroho']['dbprefix'] = '';
$db['makroho']['pconnect'] = TRUE;
$db['makroho']['db_debug'] = TRUE;
$db['makroho']['cache_on'] = FALSE;
$db['makroho']['cachedir'] = '';
$db['makroho']['char_set'] = 'utf8';
$db['makroho']['dbcollat'] = 'utf8_general_ci';
$db['makroho']['swap_pre'] = '';
$db['makroho']['autoinit'] = TRUE;
$db['makroho']['stricton'] = FALSE;
$db['makroho']['active_r'] = TRUE;
$db['makroho']['pconnect'] = TRUE;
$db['makroho']['db_debug'] = TRUE;
$db['makroho']['cache_on'] = FALSE;
$db['makroho']['cachedir'] = dirname(realpath($_SERVER['SCRIPT_FILENAME'])) .  DIRECTORY_SEPARATOR .'cache';



//————————————————————————————————————————
// Tiendas Makro
//——————————————————————————————————————E
$arts = array();
$arts['01']   = array ( 'dbhost' => '10.49.101.5', 'dbport' => '1521', 'dbname'=>'ART01' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['02']   = array ( 'dbhost' => '10.49.102.5', 'dbport' => '1521', 'dbname'=>'ART02' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['03']   = array ( 'dbhost' => '10.49.103.5', 'dbport' => '1521', 'dbname'=>'ART03' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['04']   = array ( 'dbhost' => '10.49.104.5', 'dbport' => '1521', 'dbname'=>'ART04' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['05']   = array ( 'dbhost' => '10.49.105.5', 'dbport' => '1521', 'dbname'=>'ART05' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['06']   = array ( 'dbhost' => '10.49.106.5', 'dbport' => '1521', 'dbname'=>'ART06' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['07']   = array ( 'dbhost' => '10.49.107.5', 'dbport' => '1521', 'dbname'=>'ART07' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['08']   = array ( 'dbhost' => '10.49.108.5', 'dbport' => '1521', 'dbname'=>'ART08' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['09']   = array ( 'dbhost' => '10.49.109.5', 'dbport' => '1521', 'dbname'=>'ART09' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['10']   = array ( 'dbhost' => '10.49.110.5', 'dbport' => '1521', 'dbname'=>'ART10' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['11']   = array ( 'dbhost' => '10.49.111.5', 'dbport' => '1521', 'dbname'=>'ART11' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['12']   = array ( 'dbhost' => '10.49.112.5', 'dbport' => '1521', 'dbname'=>'ART12' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['13']   = array ( 'dbhost' => '10.49.113.5', 'dbport' => '1521', 'dbname'=>'ART13' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['14']   = array ( 'dbhost' => '10.49.114.5', 'dbport' => '1521', 'dbname'=>'ART14' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['15']   = array ( 'dbhost' => '10.49.115.5', 'dbport' => '1521', 'dbname'=>'ART15' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['16']   = array ( 'dbhost' => '10.49.116.5', 'dbport' => '1521', 'dbname'=>'ART16' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['17']   = array ( 'dbhost' => '10.49.117.5', 'dbport' => '1521', 'dbname'=>'ART17' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['18']   = array ( 'dbhost' => '10.49.118.5', 'dbport' => '1521', 'dbname'=>'ART18' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['19']   = array ( 'dbhost' => '10.49.119.5', 'dbport' => '1521', 'dbname'=>'ART19' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['20']   = array ( 'dbhost' => '10.49.120.5', 'dbport' => '1521', 'dbname'=>'ART20' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['22']   = array ( 'dbhost' => '10.49.122.10','dbport' => '1525', 'dbname'=>'ART22' , 'username'=>'dba_st', 'password'=>'<Enkma2>' );
$arts['FAB1'] = array ( 'dbhost' => '10.49.3.94','dbport' => '1525'  , 'dbname'=>'ARMSTDE1', 'username'=>'dba_st', 'password'=>'Externo1' );
$arts['FAB2'] = array ( 'dbhost' => '10.49.3.95','dbport' => '1525'  , 'dbname'=>'ARMSTDE2', 'username'=>'dba_st', 'password'=>'Externo1' );
$arts['FAB3'] = array ( 'dbhost' => '10.49.3.96','dbport' => '1525'  , 'dbname'=>'ARMSTDE3', 'username'=>'dba_st', 'password'=>'Externo1' );

foreach ($arts as $art => $value) {
$dbhost = $value['dbhost'];
$dbport = $value['dbport'];
$dbname = $value['dbname'];
$dbConnString = ' (DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = '. $dbhost .')(PORT = '. $dbport .')) ) (CONNECT_DATA = (SERVICE_NAME = '. $dbname .') ))';




$db['MKT'.$art]['hostname'] = 'Driver={SQL Server};tcp:ar-mk-db-test1.database.windows.net,1433;Database=ar-mk-cust-app';
$db['MKT'.$art]['username'] = 'lmilmnada';
$db['MKT'.$art]['password'] = 'Makro2016';
$db['MKT'.$art]['database'] = 'r-mk-cust-app';
$db['MKT'.$art]['dbdriver'] = 'odbc';
$db['MKT'.$art]['dbprefix'] = '';
$db['MKT'.$art]['pconnect'] = FALSE;
$db['MKT'.$art]['db_debug'] = TRUE;
$db['MKT'.$art]['cache_on'] = FALSE;
$db['MKT'.$art]['cachedir'] = '';
$db['MKT'.$art]['char_set'] = 'utf8';
$db['MKT'.$art]['dbcollat'] = 'utf8_general_ci';
$db['MKT'.$art]['swap_pre'] = '';
$db['MKT'.$art]['autoinit'] = TRUE;
$db['MKT'.$art]['stricton'] = FALSE;
$db['MKT'.$art]['active_r'] = TRUE;
$db['MKT'.$art]['db_debug'] = TRUE;
$db['MKT'.$art]['cache_on'] = FALSE;
$db['MKT'.$art]['cachedir'] = dirname(realpath($_SERVER['SCRIPT_FILENAME'])) .  DIRECTORY_SEPARATOR .'cache';


}



/* End of file database.php */
/* Location: ./application/config/database.php */