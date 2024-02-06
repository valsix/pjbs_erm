<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Loginas extends _adminController{


	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/loginaslist";
		$this->viewdetail = "panelbackend/sysuserdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		$this->data['show_button'] = false;
		
		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Login Sebagai User Lain';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Login Sebagai User Lain';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Login Sebagai User Lain';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Login Sebagai User Lain';
		}

		$this->load->model("Public_sys_userModel","model");
		$this->load->model("Public_sys_groupModel");
		$publicsysgroup = $this->Public_sys_groupModel;

		$rspublicsysgroup = $publicsysgroup->GArray();

		$publicsysgrouparr = array(''=>'');
		foreach($rspublicsysgroup as $row){
			$publicsysgrouparr[$row['group_id']] = $row['name'];
		}

		$this->data['publicsysgrouparr'] = $publicsysgrouparr;
		
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['jabatanarr'] = array(''=>'-pilih-')+$this->mtsdmjabatan->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header(){
		$this->_setFilter("is_active = 1 and user_id <> 1");
		return array(
			array(
				'name'=>'username', 
				'label'=>'Username', 
				'width'=>"auto",
				'type'=>"varchar",
			),
			array(
				'name'=>'group_id', 
				'label'=>'Group ', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['publicsysgrouparr'],
			),
			array(
				'name'=>'id_jabatan', 
				'label'=>'Jabatan ', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['jabatanarr'],
			),
			array(
				'name'=>'name', 
				'label'=>'Name', 
				'width'=>"auto",
				'type'=>"varchar",
			)
		);
	}

	function Index($page=0){
		$this->data['mode'] = 'blank';
		if($this->post['act']=='loginas'){
			$this->log("Login As");
			$return = $this->auth->LoginAs($this->post['user_id']);
			if($return['success']){
				$this->log("Mode Login As");
				redirect('panelbackend');
			}else{
				$this->SetFlash('err_msg', "Login Sebagai User Lain Gagal !");
				redirect("$this->page_ctrl/index");
			}
		}
		
		parent::Index($page);
	}

}