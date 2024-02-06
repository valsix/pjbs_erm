<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_revenue extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_revenuelist";
		$this->viewdetail = "panelbackend/mt_revenuedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Revenue';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Revenue';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Revenue';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Revenue';
		}

		$this->load->model("Mt_revenueModel","model");

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
				'type'=>"varchar",
			),
			array(
				'name'=>'revenue', 
				'label'=>'Revenue', 
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'revenue'=>Rupiah2Number($this->post['revenue']),
			'tahun'=>$this->post['tahun'],
		);
	}

	protected function Rules(){
		return array(
			"revenue"=>array(
				'field'=>'revenue', 
				'label'=>'Revenue', 
				'rules'=>"numeric|max_length[20]",
			),
			"tahun"=>array(
				'field'=>'tahun', 
				'label'=>'Tahun', 
				'rules'=>"numeric|max_length[4]",
			),
		);
	}

}