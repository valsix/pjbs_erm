<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_folder_dokgrc extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_folder_dokgrclist";
		$this->viewdetail = "panelbackend/mt_folder_dokgrcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Folder Dokumen GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Folder Dokumen GRC';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Folder Dokumen GRC';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Folder Dokumen GRC';
		}

		$this->load->model("Mt_folder_dokgrcModel","model");

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
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'alias', 
				'label'=>'Alias', 
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
			'nama'=>$this->post['nama'],
			'alias'=>$this->post['alias'],
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
			"alias"=>array(
				'field'=>'alias', 
				'label'=>'Alias', 
				'rules'=>"required|max_length[100]",
			),
		);
	}

}