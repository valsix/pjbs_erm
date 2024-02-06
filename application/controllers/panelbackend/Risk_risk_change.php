<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_risk_change extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_risk_changelist";
		$this->viewdetail = "panelbackend/risk_risk_changedetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk Risk Change';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk Risk Change';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risk Risk Change';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Risk Risk Change';
		}

		$this->load->model("Risk_risk_changeModel","model");

		$this->load->model("Risk_risikoModel","riskrisiko");
		$riskrisiko = $this->riskrisiko;
		$rsriskrisiko = $riskrisiko->GArray();

		$riskrisikoarr = array(''=>'');
		foreach($rsriskrisiko as $row){
			$riskrisikoarr[$row['id_risiko']] = $row['nama'];
		}

		$this->data['riskrisikoarr'] = $riskrisikoarr;

		

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_risiko', 
				'label'=>'Risiko', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['riskrisikoarr'],
			),
			array(
				'name'=>'deskripsi', 
				'label'=>'Deskripsi', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'inheren_dampak', 
				'label'=>'Inheren Dampak', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'inheren_kemungkinan', 
				'label'=>'Inheren Tingkat', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'current_dampak', 
				'label'=>'Current Dampak', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'current_tingkat', 
				'label'=>'Current Tingkat', 
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_risiko'=>$this->post['id_risiko'],
			'deskripsi'=>$this->post['deskripsi'],
			'inheren_dampak'=>$this->post['inheren_dampak'],
			'inheren_kemungkinan'=>$this->post['inheren_kemungkinan'],
			'current_dampak'=>$this->post['current_dampak'],
			'current_tingkat'=>$this->post['current_tingkat'],
		);
	}

	protected function Rules(){
		return array(
			"id_risiko"=>array(
				'field'=>'id_risiko', 
				'label'=>'Risiko', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['riskrisikoarr']))."]",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi', 
				'label'=>'Deskripsi', 
				'rules'=>"required|max_length[4000]",
			),
			"inheren_dampak"=>array(
				'field'=>'inheren_dampak', 
				'label'=>'Inheren Dampak', 
				'rules'=>"integer",
			),
			"inheren_kemungkinan"=>array(
				'field'=>'inheren_kemungkinan', 
				'label'=>'Inheren Tingkat', 
				'rules'=>"integer",
			),
			"current_dampak"=>array(
				'field'=>'current_dampak', 
				'label'=>'Current Dampak', 
				'rules'=>"integer",
			),
			"current_tingkat"=>array(
				'field'=>'current_tingkat', 
				'label'=>'Current Tingkat', 
				'rules'=>"integer",
			),
		);
	}

}