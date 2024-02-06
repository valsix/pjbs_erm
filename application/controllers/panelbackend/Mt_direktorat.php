
		$this->load->model("Mt_direktoratModel","mtdirektorat");
		$this->data['mtdirektoratarr'] = $this->mtdirektorat->GetCombo();<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_direktorat extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_direktoratlist";
		$this->viewdetail = "panelbackend/mt_direktoratdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Direktorat';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Direktorat';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Direktorat';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Direktorat';
		}
		$this->load->model("Mt_direktoratModel","model");
		
		$this->load->model("Mt_direktoratModel","mtdirektorat");
		$this->data['mtdirektoratarr'] = $this->mtdirektorat->GetCombo();

		$this->list_order = "nvl(id_parent_direktorat, id_direktorat) asc, id_direktorat asc";

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
		);
	}

	protected function Record($id=null){
		return array(
			'id_parent_direktorat'=>$this->post['id_parent_direktorat'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"id_parent_direktorat"=>array(
				'field'=>'id_parent_direktorat', 
				'label'=>'Parent Direktorat', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdirektoratarr']))."]|max_length[20]",
			),
			"id_direktorat"=>array(
				'field'=>'id_direktorat', 
				'label'=>'Kode', 
				'rules'=>"max_length[20]|is_unique[MT_DIREKTORAT.ID_DIREKTORAT]required",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[200]|required",
			),
		);
	}

}