<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_kemungkinan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_kemungkinanlist";
		$this->viewdetail = "panelbackend/mt_risk_kemungkinandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Tingkat Kemungkinan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Tingkat Kemungkinan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Tingkat Kemungkinan';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Tingkat Kemungkinan';
		}

		$this->load->model("Mt_risk_kemungkinanModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'kode',
				'label'=>'Kode',
				'width'=>"70px",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama',
				'label'=>'Tingkat',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'rating',
				'label'=>'Rating',
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'deskripsi_kualitatif',
				'label'=>'Deskripsi Kualitatif',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'probabilitas',
				'label'=>'Probabilitas',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'insiden_sebelumnya',
				'label'=>'Insiden Sebelumnya',
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'deskripsi_kualitatif'=>$this->post['deskripsi_kualitatif'],
			'probabilitas'=>$_POST['probabilitas'],
			'insiden_sebelumnya'=>$this->post['insiden_sebelumnya'],
			'kode'=>$this->post['kode'],
			'rating'=>$this->post['rating'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode',
				'label'=>'Kode Tingkat',
				'rules'=>"required|max_length[20]",
			),
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Tingkat Kemungkinan',
				'rules'=>"required|max_length[300]",
			),
			"rating"=>array(
				'field'=>'rating',
				'label'=>'Rating Tingkat',
				'rules'=>"required|numeric",
			),
			"deskripsi_kualitatif"=>array(
				'field'=>'deskripsi_kualitatif',
				'label'=>'Deskripsi Kualitatif',
				'rules'=>"max_length[4000]",
			),
			"probabilitas"=>array(
				'field'=>'probabilitas',
				'label'=>'Probabilitas',
				'rules'=>"max_length[50]",
			),
			"insiden_sebelumnya"=>array(
				'field'=>'insiden_sebelumnya',
				'label'=>'Insiden Sebelumnya',
				'rules'=>"max_length[4000]",
			),
		);
	}

}
