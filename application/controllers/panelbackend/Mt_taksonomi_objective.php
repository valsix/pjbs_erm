<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_taksonomi_objective extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_taksonomi_objectivelist";
		$this->viewdetail = "panelbackend/mt_taksonomi_objectivedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Taksonomi';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Taksonomi';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_taksonomi_objectiveModel","model");
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
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	public function Index($page=0){
		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif') ");

		}

		parent::Index($page);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|max_length[5]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
		);
	}

	public function Detail($id=null){

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		redirect("panelbackend/mt_taksonomi_area/index/$id");
	}

}