<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_unit extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_unitlist";
		$this->viewdetail = "panelbackend/mt_sdm_unitdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Unit';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Unit';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Unit';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Unit';
		}

		$this->load->model("Mt_sdm_unitModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'table_code', 
				'label'=>'Table Code', 
				'width'=>"auto",
				'type'=>"char",
			),
			array(
				'name'=>'table_desc', 
				'label'=>'Table Desc', 
				'width'=>"auto",
				'type'=>"char",
			),
		);
	}

	protected function Record($id=null){
		$ret = array(
			'table_desc'=>$this->post['table_desc'],
			'table_code'=>$this->post['table_code'],
		);

		if($this->data['row']['table_code']){
			$ret['table_code'] = $this->data['row']['table_code'];
		}

		return $ret;
	}

	protected function Rules(){
		$ret = array(
			"table_desc"=>array(
				'field'=>'table_desc', 
				'label'=>'Table Desc', 
				'rules'=>"required|max_length[50]",
			),
			"table_code"=>array(
				'field'=>'table_code', 
				'label'=>'Table Code', 
				'rules'=>"is_unique[MT_SDM_UNIT.TABLE_CODE]required|max_length[18]",
			)
		);

		return $ret;
	}

}