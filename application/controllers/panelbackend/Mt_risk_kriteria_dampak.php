<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_kriteria_dampak extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_kriteria_dampaklist";
		$this->viewdetail = "panelbackend/mt_risk_kriteria_dampakdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kriteria';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kriteria';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kriteria';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Kriteria';
		}

		$this->load->model("Mt_risk_kriteria_dampakModel","model");
		$this->data['mtkriteriadampakarr'] = array("0"=>"-kosong-")+$this->model->GetCombo();

		

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}
	public function Index($page=0){
		$this->layout = "panelbackend/layout2";

		parent::Index($page);
	}

	protected function Record($id=null){
		$ret = array(
			'nama'=>$this->post['nama'],
			'id_induk'=>$this->post['id_induk'],
			'kode'=>$this->post['kode'],
		);
		if($this->post['id_induk']=='0'){
			$ret['id_induk']="{{null}}";
			unset($_POST['id_induk']);
			unset($this->post['id_induk']);
		}
		return $ret;
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|max_length[20]",
			),
			"id_induk"=>array(
				'field'=>'id_induk', 
				'label'=>'Induk', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkriteriadampakarr']))."]",
			),
		);
	}

	protected function _afterUpdate($id){
		$ret = $this->_delSertKeterangan($id);
		return $ret;
	}

	protected function _afterInsert($id){
		$ret = $this->_delSertKeterangan($id);
		return $ret;
	}

	private function _delSertKeterangan($id){

		$return = $this->conn->Execute("delete from mt_risk_kriteria_dampak_detail where id_kriteria_dampak = ".$this->conn->escape($id));

		if(is_array($this->post['keterangan'])){
			foreach ($this->post['keterangan'] as $key => $value) {
				if($return){
					if(!$value)
						continue;

					$record = array();
					$record['id_kriteria_dampak'] = $id;
					$record['id_dampak'] = $key;
					$record['keterangan'] = $value;

					$sql = $this->conn->InsertSQL("mt_risk_kriteria_dampak_detail", $record);

	        		if($sql){
					    $return = $this->conn->Execute($sql);
					}
				}
			}
		}

		return $return;
	}

	protected function _beforeDelete($id){
		$return = $this->model->Execute("delete from mt_risk_kriteria_dampak_detail where id_kriteria_dampak = ".$this->conn->escape($id));

		return $return;
	}


	protected function _afterDetail($id){
		if(!($this->data['row']['keterangan'])){
			$keteranganarr = array();

			$keteranganarr = $this->conn->GetArray("select id_dampak, keterangan from mt_risk_kriteria_dampak_detail where id_kriteria_dampak = ".$this->conn->escape($id));

			foreach ($keteranganarr as $key => $value) {
				$keteranganarr[$value['id_dampak']]=$value['keterangan'];
			}

			$this->data['row']['keterangan'] = $keteranganarr;
		}
	}
}