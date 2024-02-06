<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Public_sys_user extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/public_sys_userlist";
		$this->viewdetail = "panelbackend/public_sys_userdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah User';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit User';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail User';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar User';
		}

		$this->load->model("Public_sys_userModel","model");

		$this->load->model("Public_sys_groupModel","publicsysgroup");
		$publicsysgroup = $this->publicsysgroup;
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
			'select2'
		);
	}

	/*protected function _afterDetail($id=null){

		$nid = $this->data['row']['nid'];
		$this->load->model("Mt_sdm_karyawanModel","mtpegawai");
		$this->data['nidarr'][$nid] = $this->mtpegawai->GOne("nama","where nid = ".$this->conn->qstr($nid));

	}*/

	protected function Header(){
		return array(
			array(
				'name'=>'username', 
				'label'=>'Username', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'group_id', 
				'label'=>'Group', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['publicsysgrouparr'],
			),
			array(
				'name'=>'id_jabatan', 
				'label'=>'Jabatan', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['jabatanarr'],
			),
			array(
				'name'=>'name', 
				'label'=>'Name', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		/*	array(
				'name'=>'last_ip', 
				'label'=>'Last IP', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'last_login', 
				'label'=>'Last Login', 
				'width'=>"auto",
				'type'=>"number",
			),*/
			array(
				'name'=>'is_active', 
				'label'=>'Active', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
		);
	}

	protected function Record($id=null){
		$return = array(
			'nid'=>$this->post['nid'],
			'email'=>$this->post['email'],
			'username'=>$this->post['username'],
			'group_id'=>$this->post['group_id'],
			'id_jabatan'=>$this->post['id_jabatan'],
			'name'=>$this->post['name'],
			'last_ip'=>$this->post['last_ip'],
			'last_login'=>$this->post['last_login'],
			'is_notification'=>(int)$this->post['is_notification'],
			'is_active'=>(int)$this->post['is_active']
		);

		if(!$id or ($id && $this->post['password'])){
			$return['password']=sha1(md5($this->post['password']));
		}

		return $return;
	}

	protected function Rules(){
		$return = array(
			"nid"=>array(
				'field'=>'nid', 
				'label'=>'NID', 
				'rules'=>"max_length[100]",
			),
			"username"=>array(
				'field'=>'username', 
				'label'=>'Username', 
				'rules'=>"is_unique[PUBLIC_SYS_USER.USERNAME]|required|max_length[100]",
			),
			"email"=>array(
				'field'=>'email', 
				'label'=>'Email', 
				'rules'=>"required|valid_email|max_length[200]",
			),
			"id_jabatan"=>array(
				'field'=>'id_jabatan', 
				'label'=>'Jabatan', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['jabatanarr']))."]|max_length[10]",
			),
			"group_id"=>array(
				'field'=>'group_id', 
				'label'=>'Group ID', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['publicsysgrouparr']))."]|max_length[10]",
			),
			"name"=>array(
				'field'=>'name', 
				'label'=>'Name', 
				'rules'=>"required|max_length[200]",
			),
			"confirmpassword"=>array(
				'field'=>'confirmpassword', 
				'label'=>'Confirm Password', 
				'rules'=>"max_length[100]|matches[password]",
			),
		);

		if($this->data['row'][$this->pk]){
			$return["password"]= array(
				'field'=>'password', 
				'label'=>'Password', 
				'rules'=>"max_length[100]",
			);
		}else{
			$return["password"]= array(
				'field'=>'password', 
				'label'=>'Password', 
				'rules'=>"required|max_length[100]",
			);
		}

		return $return;
	}


	protected function _beforeDelete($id=null){
		$cek = $this->conn->GetOne("select 1 from risk_task 
			where untuk = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data sudah terpakai di tabel task silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select 1 from risk_log 
			where created_by = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data sudah terpakai di tabel log silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select 1 from risk_msg_penerima 
			where id_user = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data sudah terpakai di tabel penerima pesan silahkan hubungi Admin Database apabila Anda ingin menghapus");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		return true;
	}
}