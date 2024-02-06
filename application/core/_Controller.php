<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class _Controller extends CI_Controller {

	public $data = array();
	public $post=array();
	public $conn;
	public $model;
	public $ctrl;
	public $page_ctrl;
	public $method;
	public $mode;
	public $session;
	public $get=array();
	public $addbuttons = array();
	protected $xss_clean = true;
	protected $escape_html = false;
	public $url = "";
	public $urlaccess = "";
	public $viewpath = "";
	public $auth;
	public $private = true;
	static $referer = false;
	public $pk;
	public $limit = 5;
	public $limit_arr = array('5','10','15');
	public $arrNoquote = array();
	public $base;
	protected $layout = "";
	protected $viewdetail = "";
	protected $viewlist = "";
	protected $filter = " 1=1 ";
	public $access_role = array();
	public $page_escape = array();
	public $is_super_admin = false;
	protected $plugin_arr = array();

	public function __construct()
	{
		parent::__construct();

		$this->template = "main";
		$this->layout = "layout1";

		$router = $this->router;

		$this->ctrl = $router->class;
		$this->page_ctrl = $router->directory.$router->class;
		$this->method = $router->method;
		$this->mode = $router->method;
		$this->data['ctrl'] = $this->ctrl;
		$this->data['method'] = $this->method;
		$this->data['page_ctrl'] = $this->page_ctrl;
		$this->data['mode'] = $this->mode;
		$this->data['base'] = $this->base = base_url();

		// $this->load->library('session');

		// $this->data['session'] = $this->session;
		session_start();

		$this->FilterRequest();

		if(ENVIRONMENT=='production')
			ob_start();

		$this->load->database();
		$this->db->debug = 0;
		$this->conn = $this->db;
		
		if(ENVIRONMENT=='production')
			ob_end_clean();

		if($this->conn){
        	$date_format = $this->config->item("date_format");
        	$this->conn->Execute("alter session set nls_date_format='$date_format'");
		}
	}

	protected function FilterRequest(){
		$this->post = $this->input->post(null, $this->xss_clean);
		$this->escape_html($this->post);
		$this->get = $this->input->get(null, $this->xss_clean);
		$this->escape_html($this->get);;
	}

	protected function escape_html(&$data){
		if(!$this->escape_html)
			return true;

		$temp = $data;
		if(is_array($temp)){
			foreach ($temp as $key => $value) {
				$this->escape_html($value);
				$data[$key] = $value;
			}
		}else{
			$data = htmlspecialchars($temp);
		}
	}

	protected function SetToken(){
		$this->data['token_name'] = $_SESSION[SESSION_APP][$this->page_ctrl]['token_name'] = str_shuffle('aabcdefghijklmnopqrstuvwxyz');
		$this->data['token_value'] = $_SESSION[SESSION_APP][$this->page_ctrl]['token_value'] = md5(uniqid(rand(), true));
	}

	protected function CekToken(){
		$token_name = $_SESSION[SESSION_APP][$this->page_ctrl]['token_name'];
		$token_value = $_SESSION[SESSION_APP][$this->page_ctrl]['token_value'];
		if(!$this->post[$token_name] or $this->post[$token_name]!=$token_value){
			die("=))");
		}
	}

	protected function Plugin(){
		if(!($this->plugin_arr))
			return;

		#select2
		$plugin['select2'] .= '<script src="'.base_url().'assets/js/select2/select2.full.min.js"></script>';
		$plugin['select2'] .= "<script>$(function() {
        \$(\".select2, select.form-control\").select2();
      	});</script>";
		//$plugin['select2'] .= '<link rel="stylesheet" href="'.base_url().'assets/js/select2/select2.min.css" />';
		$plugin['select2'] .= '<link rel="stylesheet" href="'.base_url().'assets/css/select2-materialize.css" />';

		#chosen
		$plugin['chosen'] .= '<script src="'.base_url().'assets/js/chosen.jquery.js"></script>';
		$plugin['chosen'] .= "<script>$(function() {
        \$('.chosen-select').chosen({width:'100%'});
        \$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });</script>";
		$plugin['chosen'] .= '<link rel="stylesheet" href="'.base_url().'assets/css/bootstrap-chosen.css" />';
/*
		#date picker
		$plugin['datepicker'] .= '<script src="'.base_url().'assets/js/datepicker/js/moment.min.js"></script>';
		$date_format = $this->config->item("date_format");

		$plugin['datepicker'] .= '<script src="'.base_url().'assets/js/datepicker/js/bootstrap-datetimepicker.js"></script>';
		$plugin['datepicker'] .= '<script>$(function(){$(".datepicker").datetimepicker({format: "'.$date_format.'",useCurrent:false});});</script>';
		$plugin['datepicker'] .= '<script>$(function(){$(".datetimepicker").datetimepicker({format: "'.$date_format.' HH:mm:ss",useCurrent:false});});</script>';
		$plugin['datepicker'] .= '<link rel="stylesheet" href="'.base_url().'assets/js/datepicker/css/bootstrap-datetimepicker.min.css" />';*/


		#date picker
		$plugin['datepicker'] .= '<script src="'.base_url().'assets/js/datepicker/js/moment.min.js"></script>';
		$date_format = $this->config->item("date_format");

		$plugin['datepicker'] .= '<script src="'.base_url().'assets/template/backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>';

		$plugin['datepicker'] .= '<script>$(function(){

			$(".datetimepicker").bootstrapMaterialDatePicker({
		        format: "'.$date_format.' HH:mm:ss",
		        clearButton: true,
		        weekStart: 1
		    });
		    $(".datepicker").bootstrapMaterialDatePicker({
		        format: "'.$date_format.'",
		        clearButton: true,
		        weekStart: 1,
		        time: false
		    });
		    $(".datepickerstart").bootstrapMaterialDatePicker({
		        format: "'.$date_format.'",
		        clearButton: true,
		        weekStart: 1,
		        time: false,
		        minDate : new Date()
		    });

	        $(".timepicker").bootstrapMaterialDatePicker({
		        format: "HH:mm",
		        clearButton: true,
		        date: false
		    });

		});</script>';

		$plugin['datepicker'] .= '<link href="'.base_url().'assets/template/backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />';


		$plugin['autocomplete'] = '<script src="'.base_url().'assets/js/bootstrap3-typeahead.min.js"></script>';

		$plugin['myautocomplete'] = '<script src="'.base_url().'assets/js/myautocomplete/myautocomplete.js"></script>';
		$plugin['myautocomplete'] .= '<link href="'.base_url().'assets/js/myautocomplete/myautocomplete.css" rel="stylesheet" />';

		#upload
		$plugin['upload'] .= '<link rel="stylesheet" href="'.base_url().'assets/js/upload/css/jquery.fileupload.css" />';
		$plugin['upload'] .= '<script src="'.base_url().'assets/js/upload/js/vendor/jquery.ui.widget.js"></script>'; 
		$plugin['upload'] .= '<script src="'.base_url().'assets/js/upload/js/jquery.iframe-transport.js"></script>'; 
		$plugin['upload'] .= '<script src="'.base_url().'assets/js/upload/js/jquery.fileupload.js"></script>'; 


		$plugin_arr=array_unique(array_values($this->plugin_arr));
		foreach($plugin_arr as $k=>$v){
			$this->data['add_plugin'] .= $plugin[$v]."\n";
		}
	}

	protected function Helper($filename)
	{
		$this->load->helper($filename);
	}

	protected function Library($filename)
	{
		$this->load->library($filename);
	}

	public function NoData($str='Data tidak ditemukan.'){
		echo "<h2 align='center' style='margin-top:20%;color:#444'>Informasi</h2>$str";
		exit();
	}

	public function Error404($str=''){
		$this->data['error_str']=$str;
		$this->view("error404");
		exit();
	}

	public function Error403($str=''){
		$this->data['error_str']=$str;
		$this->view("error403");
		exit();
	}

	//load view with template
	protected function View($view='')
	{

		$this->Plugin();

		$this->data['content'] = $this->load->view($view, $this->data, TRUE);
		echo $this->load->view($this->template, $this->data, true);
	}

	//load view without template
	protected function PartialView($view='',$string=false){

		//$this->Plugin();

		if($string)
			return $this->load->view($view, $this->data, true);
		else
			echo $this->load->view($view, $this->data, true);
	}

	protected function log($act=""){
		if(!$act)$act = "mengakses";

		$record = array();
		$record['page'] = $this->page_ctrl;
		$record['activity'] = $act;
		$record['ip'] = $_SERVER['REMOTE_ADDR'];
		$record['activity_time'] = "{{sysdate}}";
		$record['user_id'] = $_SESSION[SESSION_APP]['user_id'];

		$this->conn->goInsert("public_sys_log",$record);
	}
}
