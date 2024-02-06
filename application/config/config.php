<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['base_url'] = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'indonesia';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = '_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 0;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = '';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = true;
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= null;
$config['cookie_secure']	= false;
$config['cookie_httponly'] 	= FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

$config['title'] = "PJBS MANAJEMEN RISIKO";
$config['copyright'] = 'Copyright &copy;'.date('Y');
$config['date_format'] = "DD-MM-YYYY";

$config['company_name'] = "PT PJB Services";
$config['company_address'] = "Jl. Raya Juanda No. 17 Sidoarjo, Jawa Timur - Indonesia 61253";
$config['company_telp'] = "(031) 8548391, (031) 8557909";
$config['company_email'] = "info@pjbservices.com";
$config['company_fax'] = "(031) 8548360";

$config['SYNOLOGY_URL'] 	= "files.pjbservices.com";
$config['SYNOLOGY_USER'] 	= "user_api";
$config['SYNOLOGY_PASS'] 	= "123456";
$config['SYNOLOGY_FOLDER'] 	= "/datafile/erm";

$config['file_upload_config']['upload_path']          = './uploads/';
$config['file_upload_config']['allowed_types']        = 'pdf|doc|docx|xls|xlsx|zip|ppt|pptx|jpg|png';
$config['file_upload_config']['max_size']             = 5120; //kb

$config['sso']['auth_page'] = true;

// config untuk testing email development
// $config['email_config'] = array(
// 	'protocol' 	=> 'smtp',
//     'smtp_host' => 'tls://server42536x.maintenis.com',
//     'smtp_port' => 465,
//     'smtp_user' => 'admin@ketonggo.com',
//     'smtp_pass' => 'admin33',
//     'mailtype'  => 'html',
// 	'from'		=>'solikul.arip@gmail.com',
// 	'fromlabel'	=>'ERM NOTIFICATION',
// 	'reply_to'	=>'solikul.arip@gmail.com',
// 	'replylabel'=>'PJBS',
//     'charset'   => 'iso-8859-1',
// 	'extra'		=>'arips4@gmail.com',
// 	'recipients'		=>'arips4@gmail.com'
// );

// config untuk testing email dari pjbs
$config['email_config'] = array(
	'protocol' 	=> 'smtp',
    'smtp_host' => 'http://mail.ptpjbs.com/',
    'smtp_port' => 465,
    'smtp_user' => 'erm@ptpjbs.com',
    'smtp_pass' => 'ytqwnqsg8',
    'mailtype'  => 'html',
	'from'		=>'erm@ptpjbs.com',
	'fromlabel'	=>'ERM NOTIFICATION',
	'reply_to'	=>'erm@ptpjbs.com',
	'replylabel'=>'PJBS',
    'charset'   => 'iso-8859-1',
	'extra'		=>'susilo.j8@gmail.com',
	'recipients'		=>'susilo.j8@gmail.com'
);

$config['url_promis'] = "http://promis.pjbservices.com/panelbackend/ws/";
$config['auth_promis'] = array("username"=>"abud","password"=>"e9e5ba4f570e8056dfc4c451c4ac0f3d4aad2451");
$config['url_portal'] = "http://portal.pjbservices.com";
$config['id_portal'] = 22;