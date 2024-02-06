<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_status_progress_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_status_progress_grclist";
		$this->viewdetail = "panelbackend/mt_status_progress_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Status Progress GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Status Progress GRC';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Status Progress GRC';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Status Progress GRC';
		}

		$this->load->model("Mt_status_progress_grcModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->data['status'] = array("1"=>"Aktif", "0"=>"Non Aktif");
	}

	protected function Header(){
		return array(
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'status',
				'label'=>'Status',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['status'],
			),
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'status'=>$this->post['status'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'%', 
				'rules'=>"required|max_length[50]",
			),
		);
	}

}