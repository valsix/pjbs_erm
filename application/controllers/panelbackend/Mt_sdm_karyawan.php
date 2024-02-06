<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_karyawan extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_karyawanlist";
		$this->viewdetail = "panelbackend/mt_sdm_karyawandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Karyawan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Karyawan';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Karyawan';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Karyawan';
		}

		$this->load->model("Mt_sdm_karyawanModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
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
				'name'=>'unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'unitket', 
				'label'=>'Unitket', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'direktorat', 
				'label'=>'Direktorat', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'direktoratket', 
				'label'=>'Direktoratket', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'subdit', 
				'label'=>'Subdit', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'subditket', 
				'label'=>'Subditket', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'fungsi', 
				'label'=>'Fungsi', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'fungsiket', 
				'label'=>'Fungsiket', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kdstaff', 
				'label'=>'Kdstaff', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'staff', 
				'label'=>'Staff', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kdjabatan', 
				'label'=>'Kdjabatan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'jabatan', 
				'label'=>'Jabatan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kdjenjang', 
				'label'=>'Kdjenjang', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'jenjang', 
				'label'=>'Jenjang', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kdstatus', 
				'label'=>'Kdstatus', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'status', 
				'label'=>'Status', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'gender', 
				'label'=>'Gender', 
				'width'=>"auto",
				'type'=>"char",
			),
			array(
				'name'=>'email', 
				'label'=>'Email', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'birth_date', 
				'label'=>'Birth Date', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'hire_date', 
				'label'=>'Hire Date', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'suspend_date', 
				'label'=>'Suspend Date', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'term_date', 
				'label'=>'Term Date', 
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'emp_status', 
				'label'=>'EMP Status', 
				'width'=>"auto",
				'type'=>"char",
			),
			array(
				'name'=>'jabatan2', 
				'label'=>'Jabatan2', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kdstaff2', 
				'label'=>'Kdstaff2', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'staff2', 
				'label'=>'Staff2', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'unit'=>$this->post['unit'],
			'unitket'=>$this->post['unitket'],
			'direktorat'=>$this->post['direktorat'],
			'direktoratket'=>$this->post['direktoratket'],
			'subdit'=>$this->post['subdit'],
			'subditket'=>$this->post['subditket'],
			'fungsi'=>$this->post['fungsi'],
			'fungsiket'=>$this->post['fungsiket'],
			'kdstaff'=>$this->post['kdstaff'],
			'staff'=>$this->post['staff'],
			'kdjabatan'=>$this->post['kdjabatan'],
			'jabatan'=>$this->post['jabatan'],
			'kdjenjang'=>$this->post['kdjenjang'],
			'jenjang'=>$this->post['jenjang'],
			'kdstatus'=>$this->post['kdstatus'],
			'status'=>$this->post['status'],
			'gender'=>$this->post['gender'],
			'email'=>$this->post['email'],
			'birth_date'=>$this->post['birth_date'],
			'hire_date'=>$this->post['hire_date'],
			'suspend_date'=>$this->post['suspend_date'],
			'term_date'=>$this->post['term_date'],
			'emp_status'=>$this->post['emp_status'],
			'jabatan2'=>$this->post['jabatan2'],
			'kdstaff2'=>$this->post['kdstaff2'],
			'staff2'=>$this->post['staff2'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"max_length[43]",
			),
			"unit"=>array(
				'field'=>'unit', 
				'label'=>'Unit', 
				'rules'=>"max_length[3]",
			),
			"unitket"=>array(
				'field'=>'unitket', 
				'label'=>'Unitket', 
				'rules'=>"max_length[50]",
			),
			"direktorat"=>array(
				'field'=>'direktorat', 
				'label'=>'Direktorat', 
				'rules'=>"max_length[6]",
			),
			"direktoratket"=>array(
				'field'=>'direktoratket', 
				'label'=>'Direktoratket', 
				'rules'=>"max_length[50]",
			),
			"subdit"=>array(
				'field'=>'subdit', 
				'label'=>'Subdit', 
				'rules'=>"max_length[6]",
			),
			"subditket"=>array(
				'field'=>'subditket', 
				'label'=>'Subditket', 
				'rules'=>"max_length[50]",
			),
			"fungsi"=>array(
				'field'=>'fungsi', 
				'label'=>'Fungsi', 
				'rules'=>"max_length[6]",
			),
			"fungsiket"=>array(
				'field'=>'fungsiket', 
				'label'=>'Fungsiket', 
				'rules'=>"max_length[50]",
			),
			"kdstaff"=>array(
				'field'=>'kdstaff', 
				'label'=>'Kdstaff', 
				'rules'=>"max_length[6]",
			),
			"staff"=>array(
				'field'=>'staff', 
				'label'=>'Staff', 
				'rules'=>"max_length[50]",
			),
			"kdjabatan"=>array(
				'field'=>'kdjabatan', 
				'label'=>'Kdjabatan', 
				'rules'=>"max_length[10]",
			),
			"jabatan"=>array(
				'field'=>'jabatan', 
				'label'=>'Jabatan', 
				'rules'=>"max_length[558]",
			),
			"kdjenjang"=>array(
				'field'=>'kdjenjang', 
				'label'=>'Kdjenjang', 
				'rules'=>"max_length[4]",
			),
			"jenjang"=>array(
				'field'=>'jenjang', 
				'label'=>'Jenjang', 
				'rules'=>"max_length[50]",
			),
			"kdstatus"=>array(
				'field'=>'kdstatus', 
				'label'=>'Kdstatus', 
				'rules'=>"max_length[3]",
			),
			"status"=>array(
				'field'=>'status', 
				'label'=>'Status', 
				'rules'=>"max_length[50]",
			),
			"gender"=>array(
				'field'=>'gender', 
				'label'=>'Gender', 
				'rules'=>"max_length[1]",
			),
			"email"=>array(
				'field'=>'email', 
				'label'=>'Email', 
				'rules'=>"required|email|max_length[1280]",
			),
			"emp_status"=>array(
				'field'=>'emp_status', 
				'label'=>'EMP Status', 
				'rules'=>"max_length[1]",
			),
			"jabatan2"=>array(
				'field'=>'jabatan2', 
				'label'=>'Jabatan2', 
				'rules'=>"max_length[40]",
			),
			"kdstaff2"=>array(
				'field'=>'kdstaff2', 
				'label'=>'Kdstaff2', 
				'rules'=>"max_length[3]",
			),
			"staff2"=>array(
				'field'=>'staff2', 
				'label'=>'Staff2', 
				'rules'=>"max_length[50]",
			),
		);
	}

}