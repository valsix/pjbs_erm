<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_jabatan extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_jabatanlist";
		$this->viewdetail = "panelbackend/mt_sdm_jabatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jabatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jabatan';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jabatan';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Jabatan';
		}

		$this->load->model("Mt_sdm_jabatanModel","model");

		$this->load->model("Mt_sdm_strukturModel","mtsdmstruktur");
		$this->data['mtsdmstrukturarr'] = $this->mtsdmstruktur->GetCombo();		

		$this->load->model("Mt_sdm_unitModel","mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();		

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
				'name'=>'id_unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmunitarr'],
			),
			array(
				'name'=>'is_pimpinan', 
				'label'=>'Pimpinan', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-','0'=>'Tidak','1'=>'Iya'),
			),
			array(
				'name'=>'position_id', 
				'label'=>'Kode ELLIPSE', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'id_unit'=>$this->post['id_unit'],
			'id_struktur'=>$this->post['id_struktur'],
			'is_pimpinan'=>(int)$this->post['is_pimpinan'],
			'position_id'=>(int)$this->post['position_id'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"id_unit"=>array(
				'field'=>'id_unit', 
				'label'=>'Unit', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtsdmunitarr']))."]|max_length[18]",
			),
			"id_struktur"=>array(
				'field'=>'id_struktur', 
				'label'=>'Struktur', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtsdmstrukturarr']))."]",
			),
			"is_pimpinan"=>array(
				'field'=>'is_pimpinan', 
				'label'=>'IS Pimpinan', 
				'rules'=>"max_length[1]",
			),
		);
	}

	public function Index($page=0){
		$this->layout = "panelbackend/layout2";

		if($this->post['act']=='filter_tgl_efektif'){
			if(!$this->post['tgl_efektif'])
				$this->post['tgl_efektif'] = date('d-m-Y');
			
			$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $this->post['tgl_efektif'];
		}

		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif') and nvl(tgl_akhir_efektif,'$tgl_efektif') ");

		}

		parent::Index($page);
	}

	protected function _beforeDelete($id=null){

		if(!$this->access_role['delete'])
			return false;

		$cek = $this->conn->GetOne("select s.nama
			from RISK_SASARAN_STRATEGIS_PIC sp
			join risk_sasaran_strategis s on sp.id_sasaran_strategis = s.id_sasaran_strategis
			where sp.ID_JABATAN = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran strategis ".$cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select s.nama
			from RISK_SASARAN_KEGIATAN s
			where OWNER = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data tidak bisa dihapus karena jabatan sudah menjadi PIC di sasaran kegiatan ".$cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select username
			from PUBLIC_SYS_USER 
			where ID_JABATAN = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data tidak bisa dihapus karena jabatan sudah ada di user dengan username ".$cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetRow("select m.nama, n.nama as risiko
			from RISK_MITIGASI m
			join risk_risiko n on m.id_risiko = n.id_risiko
			where PENANGGUNG_JAWAB = ".$this->conn->escape($id));

		if(count($cek)){
			SetFlash('err_msg',"Data tidak bisa dihapus karena jabatan sudah menjadi penanggung jawab mitigasi dengan nama kegiatan ".$cek['nama'].", di risiko ".$cek['risiko']. " (silahkan dicari di status risiko)");
			redirect("$this->page_ctrl/detail/$id");
			die();
		}

		$cek = $this->conn->GetOne("select nama
			from RISK_SCORECARD 
			where OWNER = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Data tidak bisa dihapus karena jabatan sudah menjadi owner di scorecard ".$cek);
			redirect("$this->page_ctrl/detail/$id");
			die();
		}
		
		$ret = $this->conn->Execute("delete 
			from RISK_TASK where UNTUK = ".$this->conn->escape($id));

		return $ret;
	}


}