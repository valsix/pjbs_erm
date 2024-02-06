<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_issue extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_issuelist";
		$this->viewdetail = "panelbackend/mt_issuedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Issue';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Issue';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Issue';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Issue';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_issueModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'tahun', 
				'label'=>'Tahun', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'tahun'=>Rupiah2Number($this->post['tahun']),
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"tahun"=>array(
				'field'=>'tahun', 
				'label'=>'Tahun', 
				'rules'=>"numeric|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[2000]",
			),
		);
	}

}