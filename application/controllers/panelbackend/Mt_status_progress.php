<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_status_progress extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_status_progresslist";
		$this->viewdetail = "panelbackend/mt_status_progressdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Status Progress';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Status Progress';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Status Progress';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Status Progress';
		}

		$this->load->model("Mt_status_progressModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'prosentase', 
				'label'=>'%', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'nama', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'prosentase'=>$this->post['prosentase'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Keterangan', 
				'rules'=>"required|max_length[20]",
			),
			"prosentase"=>array(
				'field'=>'prosentase', 
				'label'=>'%', 
				'rules'=>"required|numeric",
			),
		);
	}

}