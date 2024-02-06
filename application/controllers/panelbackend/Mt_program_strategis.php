<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_program_strategis extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_program_strategislist";
		$this->viewdetail = "panelbackend/mt_program_strategisdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Program Strategis';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Program Strategis';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Program Strategis';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Program Strategis';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_program_strategisModel","model");
		$this->load->model("Mt_issueModel","mtissue");
		$this->data['mtissuearr'] = $this->mtissue->GetCombo();

		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_issue', 
				'label'=>'Issue', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtissuearr'],
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'jenis', 
				'label'=>'Jenis', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_issue'=>Rupiah2Number($this->post['id_issue']),
			'nama'=>$this->post['nama'],
			'jenis'=>$this->post['jenis'],
		);
	}

	protected function Rules(){
		return array(
			"id_issue"=>array(
				'field'=>'id_issue', 
				'label'=>'Issue', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtissuearr']))."]|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[2000]",
			),
			"jenis"=>array(
				'field'=>'jenis', 
				'label'=>'Jenis', 
				'rules'=>"max_length[4]",
			),
		);
	}

}