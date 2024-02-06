<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pb_kategori extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pb_kategorilist";
		$this->viewdetail = "panelbackend/mt_pb_kategoridetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kategori';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kategori';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kategori';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kategori';
		}

		$this->load->model("Mt_pb_kategoriModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function _afterDetail($id){

	}

	protected function Header(){

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif') ");

		}
		
		return array(
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"70px",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama', 
				'label'=>'Kategori', 
				'width'=>"auto",
				'type'=>"varchar2",
			),/*
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
			),*/
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
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
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]",
			),
		);
	}

}