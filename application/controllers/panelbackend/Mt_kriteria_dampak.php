<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_kriteria_dampak extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_kriteria_dampaklist";
		$this->viewdetail = "panelbackend/mt_kriteria_dampakdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kriteria';
		}

		$this->load->model("Mt_kriteria_dampakModel","model");

		$this->load->model("Mt_kriteria_dampakModel","mtkriteriadampak");
		$mtkriteriadampak = $this->mtkriteriadampak;
		$rsmtkriteriadampak = $mtkriteriadampak->GArray();

		$mtkriteriadampakarr = array(''=>'');
		foreach($rsmtkriteriadampak as $row){
			$mtkriteriadampakarr[$row['id_kriteria_dampak']] = $row['nama'];
		}

		$this->data['mtkriteriadampakarr'] = $mtkriteriadampakarr;

		

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
				'name'=>'id_induk', 
				'label'=>'Induk', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtkriteriadampakarr'],
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'id_induk'=>$this->post['id_induk'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[200]",
			),
			"id_induk"=>array(
				'field'=>'id_induk', 
				'label'=>'Induk', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkriteriadampakarr']))."]",
			),
		);
	}

}