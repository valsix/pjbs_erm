<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_dampak_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_dampak_risikolist";
		$this->viewdetail = "panelbackend/mt_dampak_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Dampak Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Dampak Risiko';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Dampak Risiko';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Dampak Risiko';
		}

		$this->load->model("Mt_dampak_risikoModel","model");

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
				'name'=>'keterangan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'keterangan'=>$this->post['keterangan'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[300]",
			),
			"keterangan"=>array(
				'field'=>'keterangan', 
				'label'=>'Keterangan', 
				'rules'=>"max_length[4000]",
			),
		);
	}

}