<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_maturity extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_maturitylist";
		$this->viewdetail = "panelbackend/mt_maturitydetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Maturity Level';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Maturity Level';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Maturity Level';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Maturity Level';
		}

		$this->load->model("Mt_maturityModel","model");
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
				'name'=>'target', 
				'label'=>'Target', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'realisasi', 
				'label'=>'Realisasi', 
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'tahun'=>$this->post['tahun'],
			'target'=>$this->post['target'],
			'realisasi'=>$this->post['realisasi'],
		);
	}

	protected function Rules(){
		return array(
			"tahun"=>array(
				'field'=>'tahun', 
				'label'=>'Tahun', 
				'rules'=>"numeric|required",
			),
			"target"=>array(
				'field'=>'target', 
				'label'=>'Target', 
				'rules'=>"numeric|required",
			),
			"realisasi"=>array(
				'field'=>'realisasi', 
				'label'=>'Realisasi', 
				'rules'=>"numeric",
			),
		);
	}

}