<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Public_sys_setting extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/public_sys_settinglist";
		$this->viewdetail = "panelbackend/public_sys_settingdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Konfigurasi';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Konfigurasi';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Konfigurasi';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Konfigurasi';
		}

		$this->load->model("Public_sys_settingModel","model");

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
			/*array(
				'name'=>'is_show', 
				'label'=>'Show', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),*/
			array(
				'name'=>'isi', 
				'label'=>'ISI', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'is_show'=>1,
			'isi'=>$this->post['isi'],
		);
	}

	protected function Rules(){
		$return = array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
			/*"is_show"=>array(
				'field'=>'is_show', 
				'label'=>'IS Show', 
				'rules'=>"integer|max_length[10]",
			),*/
			"isi"=>array(
				'field'=>'isi', 
				'label'=>'ISI', 
				'rules'=>"max_length[1000]",
			),
		);

		if($this->data['row']['id_public_sys_setting'])
			unset($return['nama']);

		return $return;
	}

}