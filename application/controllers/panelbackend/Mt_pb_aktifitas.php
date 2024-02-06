<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pb_aktifitas extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pb_aktifitaslist";
		$this->viewdetail = "panelbackend/mt_pb_aktifitasdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Aktivitas';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Aktivitas';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Aktivitas';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Aktivitas';
		}

		$this->load->model("Mt_pb_aktifitasModel","model");
		$this->load->model("Mt_pb_nama_prosesModel","mtpbnamaproses");
		$this->data['mtpbnamaprosesarr'] = $this->mtpbnamaproses->GetCombo();

		
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
				'name'=>'id_nama_proses', 
				'label'=>'Nama Proses', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtpbnamaprosesarr'],
			),
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"70px",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'id_nama_proses'=>$this->post['id_nama_proses'],
			'kode'=>$this->post['kode'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"id_nama_proses"=>array(
				'field'=>'id_nama_proses', 
				'label'=>'Nama Proses', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtpbnamaprosesarr']))."]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]",
			),
		);
	}

}