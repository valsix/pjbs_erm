<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_dampak extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_dampaklist";
		$this->viewdetail = "panelbackend/mt_risk_dampakdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tingkat Dampak';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tingkat Dampak';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Tingkat Dampak';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Tingkat Dampak';
		}

		$this->load->model("Mt_risk_dampakModel","model");

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
				'label'=>'Tingkat Dampak',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kode',
				'label'=>'Kode',
				'width'=>"70px",
				'type'=>"varchar2",
			),
			array(
				'name'=>'rating',
				'label'=>'Rating',
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'kode'=>$this->post['kode'],
			'keterangan'=>$this->post['keterangan'],
			'rating'=>$this->post['rating'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Tingkat Dampak',
				'rules'=>"required|max_length[300]",
			),
			"kode"=>array(
				'field'=>'kode',
				'label'=>'Kode',
				'rules'=>"required|max_length[300]",
			),
			"rating"=>array(
				'field'=>'rating',
				'label'=>'Rating',
				'rules'=>"required|numeric",
			),
			"keterangan"=>array(
				'field'=>'keterangan',
				'label'=>'Keterangan',
				'rules'=>"max_length[4000]",
			),
		);
	}

}
