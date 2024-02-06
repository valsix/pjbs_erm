<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_strategi_map extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_strategi_maplist";
		$this->viewdetail = "panelbackend/risk_strategi_mapdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Strategi Map';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Strategi Map';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Strategi Map';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Strategi Map';
		}

		$this->load->model("Risk_strategi_mapModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function _afterEditSucceed($id=null){
		file_put_contents(APPPATH."/views/panelbackend/_strategimap".$id.".php",$_POST['strategi_map']);
	}

	protected function _afterDetail($id){
		if(!$this->data['row']['strategi_map'])
			$this->data['row']['strategi_map'] = file_get_contents(APPPATH."/views/panelbackend/_strategimap".$id.".php");
	}

	protected function Header(){
		return array(
			array(
				'name'=>'tgl_mulai_efektif', 
				'label'=>'Tgl. Mulai Efektif', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'tgl_akhir_efektif', 
				'label'=>'Tgl. Akhir Efektif', 
				'width'=>"auto",
				'type'=>"date",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
		);
	}

	protected function Rules(){
		return array(
			"tgl_mulai_efektif"=>array(
				'field'=>'tgl_mulai_efektif',
				'label'=>'Tgl. Mulai Efektif',
				'rules'=>"required",
			),
			"tgl_akhir_efektif"=>array(
				'field'=>'tgl_akhir_efektif',
				'label'=>'Tgl. Akhir Efektif',
				'rules'=>"required",
			),
		);
	}

}