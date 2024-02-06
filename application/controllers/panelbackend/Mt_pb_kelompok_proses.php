<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_pb_kelompok_proses extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_pb_kelompok_proseslist";
		$this->viewdetail = "panelbackend/mt_pb_kelompok_prosesdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kelompok Proses';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kelompok Proses';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kelompok Proses';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kelompok Proses';
		}

		$this->load->model("Mt_pb_kelompok_prosesModel","model");
		$this->load->model("Mt_pb_kategoriModel","mtpbkategori");
		$this->data['mtpbkategoriarr'] = $this->mtpbkategori->GetCombo();

		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
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
				'name'=>'id_kategori', 
				'label'=>'Kategori', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtpbkategoriarr'],
			),
			array(
				'name'=>'nama', 
				'label'=>'Kelompok Proses', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			/*array(
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
			'id_kategori'=>$this->post['id_kategori'],
		);
	}

	protected function Rules(){
		if($this->data['row']['id_kategori'])
			$this->data['mtpbkategoriarr'][$this->data['row']['id_kategori']] = $this->conn->GetOne("select nama from mt_pb_kategori where id_kategori = ".$this->conn->escape($this->data['row']['id_kategori']));
		
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
			"id_kategori"=>array(
				'field'=>'id_kategori', 
				'label'=>'Kategori', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtpbkategoriarr']))."]",
			),
		);
	}

}