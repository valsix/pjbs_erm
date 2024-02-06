<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_task extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_tasklist";
		$this->viewdetail = "panelbackend/risk_taskdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout4";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Task';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Task';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Task';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Task';
		}

		$this->load->model("Risk_taskModel","model");
		

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	function Edit($id=null){
		$this->Error403();
	}

	function Add(){
		$this->Error403();
	}
}