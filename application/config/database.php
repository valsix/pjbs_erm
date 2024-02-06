<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	//'hostname' => '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.30.128)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = pjbs)))',
	'hostname' => '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1522)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = orcl11)))',
	'username' => 'ERM_USER',
	'password' => '123456',
	'database' => 'ERM_USER',
	'dbdriver' => 'oci8',
	'dbprefix' => '',
	'pconnect' => true,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => (ENVIRONMENT !== 'production')
);

####
