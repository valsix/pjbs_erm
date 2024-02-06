<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pic_rth extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pic_rthlist";
		$this->viewdetail = "panelbackend/mt_pic_rthdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah PIC RTH';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit PIC RTH';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail PIC RTH';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar PIC RTH';
		}

		$this->load->model("Mt_pic_rthModel","model");

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
				'name'=>'nid', 
				'label'=>'NID', 
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
			'nid'=>$this->post['nid'],
			'nama'=>$this->post['nama'],
			'status'=>$this->post['status'],
		);
	}

	protected function Rules(){
		return array(
			"nid"=>array(
				'field'=>'nid', 
				'label'=>'NID', 
				'rules'=>"required|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
		);
	}

}