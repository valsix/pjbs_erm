<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_sdm_struktur extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_sdm_strukturlist";
		$this->viewdetail = "panelbackend/mt_sdm_strukturdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Struktur Organisasi';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Struktur Organisasi';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Struktur Organisasi';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Struktur Organisasi';
		}

		$this->load->model("Mt_sdm_strukturModel","model");

		$this->load->model("Mt_sdm_strukturModel","mtsdmstruktur");
		$this->data['mtsdmstrukturarr'] = $this->mtsdmstruktur->GetCombo();	

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
				'label'=>'Nama Organisasi', 
				'width'=>"auto",
				'type'=>"varchar2",
			)
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
			'id_struktur_parent'=>$this->post['id_struktur_parent'],
			'urutan'=>$this->post['urutan'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|is_unique[MT_SDM_STRUKTUR.KODE]|max_length[18]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"tgl_mulai_efektif"=>array(
				'field'=>'tgl_mulai_efektif', 
				'label'=>'Tgl. Mulai Efektif', 
				'rules'=>"required",
			),
			"id_struktur_parent"=>array(
				'field'=>'id_struktur_parent', 
				'label'=>'Struktur Parent', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtsdmstrukturarr']))."]",
			),
		);
	}
	public function Index($page=0){
		$this->layout = "panelbackend/layout2";

		if($this->post['act']=='sort_up' && $this->post['key']){
			$this->conn->Execute("update mt_sdm_struktur set urutan=nvl(urutan,0)where id_struktur = ".$this->conn->escape($this->post['key']));

			$this->model->sort($this->post['key']);
			redirect(current_url());
		}

		if($this->post['act']=='sort_down' && $this->post['key']){
			$this->conn->Execute("update mt_sdm_struktur set urutan=nvl(urutan,0)+1 where id_struktur = ".$this->conn->escape($this->post['key']));

			$this->model->sort($this->post['key']);
			redirect(current_url());
		}

		if($this->post['act']=='sync' && $this->access_role['add']){
			$ret = $this->model->sync();

			if($ret)
				SetFlash("suc_msg","Sync Berhasil, langkah selanjutnya : <br/>1. Cek jabatan <br/>2. Mappingkan ulang scorecard dan risiko");
			else
				SetFlash("err_msg","Masih sama dengan ellipse");

			redirect(current_url());
		}

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif') ");

		}

		parent::Index($page);
	}

	protected function _afterUpdate($id){
		$ret = $this->_delSertHistory($id);
		return $ret;
	}

	protected function _afterInsert($id){
		$ret = $this->_delSertHistory($id);
		return $ret;
	}

	private function _delSertHistory($id){
		$return = $this->conn->Execute("delete from mt_sdm_struktur_history where id_struktur = ".$this->conn->escape($id));

		if(is_array($this->post['id_struktur_history'])){
			foreach ($this->post['id_struktur_history'] as $key => $value) {
				if($return){
					if(!$value)
						continue;

					$record = array();
					$record['id_struktur'] = $id;
					$record['id_struktur_history'] = $value;

					$sql = $this->conn->InsertSQL("mt_sdm_struktur_history", $record);

	        if($sql){
					    $return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}

	protected function _beforeDelete($id){
		$error = array();

		$cek = $this->conn->GetListStr("select nama from risk_scorecard where id_struktur=".$this->conn->escape($id));
		if($cek){
			$error[]= "Scorecard : $cek";
		}

		$cek = $this->conn->GetListStr("select nama from mt_sdm_jabatan where id_struktur=".$this->conn->escape($id));
		if($cek){
			$error[]= "Jabatan : $cek";
		}

		if(($error)){
			$errorstr = implode("<br/>", $error);
			SetFlash('err_msg',"Data masih dipakai di <br/>".$errorstr."<br/> silahkan dihapus terlebih dahulu data-data tersebut.");
			redirect("$this->page_ctrl/detail/$id");
		}

		$return = $this->conn->Execute("delete from mt_sdm_struktur_history where id_struktur = ".$this->conn->escape($id));
		return $return;
	}

	protected function _afterDetail($id){
		if(!($this->data['row']['id_struktur_history'])){
			$id_struktur_historyarr = array();

			$mtsdmstrukturarr = $this->conn->GetArray("select id_struktur_history from mt_sdm_struktur_history where id_struktur = ".$this->conn->escape($id));

			foreach ($mtsdmstrukturarr as $key => $value) {
				$id_struktur_historyarr[]=$value['id_struktur_history'];
			}

			$this->data['row']['id_struktur_history'] = $id_struktur_historyarr;
		}

		$this->data['mtsdmstrukturarrhistory'] = $this->data['mtsdmstrukturarr'];
		unset($this->data['mtsdmstrukturarrhistory']['']);
		
		if(($this->data['row']['id_struktur_history'])){

			$id_mtsdmstrukturarr = $this->data['row']['id_struktur_history'];
			$id_struktur_historystr = "'".implode("','", $id_mtsdmstrukturarr)."'";

			$mtsdmstrukturarr = $this->conn->GetArray("select * from mt_sdm_struktur_history where id_struktur_history in ($id_struktur_historystr)");
			foreach ($mtsdmstrukturarr as $r) {
				$this->data['mtsdmstrukturarrhistory'][$r['id_struktur_history']] = $r['nama'];
			}
		}
	}
}