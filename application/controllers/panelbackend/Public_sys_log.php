<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Public_sys_log extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/public_sys_loglist";
		$this->viewdetail = "panelbackend/public_sys_logdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah LOG';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit LOG';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail LOG';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar LOG';
		}

		$this->load->model("Public_sys_logModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'page', 
				'label'=>'Page', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'activity', 
				'label'=>'Activity', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'ip', 
				'label'=>'IP', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'activity_time', 
				'label'=>'Activity Time', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'name', 
				'field'=>"u.name",
				'label'=>'User', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

}