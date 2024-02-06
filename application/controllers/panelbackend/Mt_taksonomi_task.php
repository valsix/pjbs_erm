<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_taksonomi_task extends _adminController{
	public $limit = -1;

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_taksonomi_tasklist";
		$this->viewdetail = "panelbackend/mt_taksonomi_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_taksonomi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi ';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi ';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Taksonomi ';
			$this->data['edited'] = false;	
		}else{
			$this->layout = "panelbackend/layout1";
			$this->data['page_title'] = 'Daftar Taksonomi ';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_taksonomi_risikoModel","model");
		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Index($page=0){
		if($this->post['act']=='save_taksonomi'){
			foreach($this->post['id_taksonomi_area'] as $id_taksonomi_risiko=>$id_taksonomi_area){
				$this->conn->goUpdate("mt_taksonomi_risiko", 
					array("id_taksonomi_area"=>$id_taksonomi_area), 
					"id_taksonomi_risiko = ".$this->conn->escape($id_taksonomi_risiko)
				);
			}
			redirect(current_url());
		}
		$this->data['objectivearr'] = array(''=>'')+$this->conn->GetList("select id_taksonomi_objective as key, nama val from mt_taksonomi_objective");

		$rows = $this->conn->GetArray("select * from mt_taksonomi_area");

		$this->data['areaarr'] = array();
		foreach($rows as $r){
			$this->data['areaarr'][$r['id_taksonomi_objective']][$r['id_taksonomi_area']] = $r['nama'];
		}

		$this->_setFilter("id_taksonomi_area is null");
		parent::Index($page);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_taksonomi_objective', 
				'label'=>'Taksonomi', 
				'width'=>"auto",
				'type'=>"varchar2",
				'nofilter'=>true
			),
			array(
				'name'=>'id_taksonomi_area', 
				'label'=>'Area', 
				'width'=>"auto",
				'type'=>"varchar2",
				'nofilter'=>true
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
				'nofilter'=>true
			),
		);
	}

}