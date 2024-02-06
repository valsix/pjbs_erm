<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_kriteria_dampak_detail extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_kriteria_dampak_detaillist";
		$this->viewdetail = "panelbackend/mt_kriteria_dampak_detaildetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria Dampak (impact rating)';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria Dampak (impact rating)';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kriteria Dampak (impact rating)';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kriteria Dampak (impact rating)';
		}

		$this->load->model("Mt_kriteria_dampak_detailModel","model");

		$this->load->model("Mt_dampak_risikoModel","mtdampakrisiko");
		$mtdampakrisiko = $this->mtdampakrisiko;
		$rsmtdampakrisiko = $mtdampakrisiko->GArray();

		$mtdampakrisikoarr = array(''=>'');
		foreach($rsmtdampakrisiko as $row){
			$mtdampakrisikoarr[$row['id_dampak_risiko']] = $row['nama'];
		}

		$this->data['mtdampakrisikoarr'] = $mtdampakrisikoarr;

		

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
				'name'=>'keterangan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'keterangan'=>$this->post['keterangan'],
		);
	}

	protected function Rules(){
		return array(
			"keterangan"=>array(
				'field'=>'keterangan', 
				'label'=>'Keterangan', 
				'rules'=>"max_length[400]",
			),
		);
	}

}