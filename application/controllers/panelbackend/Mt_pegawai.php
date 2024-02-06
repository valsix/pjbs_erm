<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pegawai extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pegawailist";
		$this->viewdetail = "panelbackend/mt_pegawaidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Pegawai';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Pegawai';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Pegawai';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Pegawai';
		}

		$this->load->model("Mt_pegawaiModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
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
				'name'=>'unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'direktorat', 
				'label'=>'Direktorat', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'subdit', 
				'label'=>'Subdit', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'unit'=>$this->post['unit'],
			'direktorat'=>$this->post['direktorat'],
			'subdit'=>$this->post['subdit'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[45]",
			),
			"unit"=>array(
				'field'=>'unit', 
				'label'=>'Unit', 
				'rules'=>"max_length[3]",
			),
			"direktorat"=>array(
				'field'=>'direktorat', 
				'label'=>'Direktorat', 
				'rules'=>"max_length[6]",
			),
			"subdit"=>array(
				'field'=>'subdit', 
				'label'=>'Subdit', 
				'rules'=>"max_length[6]",
			),
		);
	}

}