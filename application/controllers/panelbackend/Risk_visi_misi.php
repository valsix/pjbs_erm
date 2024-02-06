<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_visi_misi extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_visi_misilist";
		$this->viewdetail = "panelbackend/risk_visi_misidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Visi & Misi';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Visi & Misi';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Visi & Misi';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Visi & Misi';
		}

		$this->load->model("Risk_visi_misiModel","model");

		$this->access_role['view_all_direktorat'] = $this->Access("view_all_direktorat","panelbackend/risk_scorecard");
		$this->data['view_all_direktorat'] = $this->access_role['view_all_direktorat'];

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		unset($this->access_role['lst']);
	}

	public function Index($page=0){
		redirect('panelbackend/risk_visi_misi/detail');
	}

	public function Edit($id=null){

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
		
		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		if($id)
			redirect('panelbackend/risk_visi_misi/edit');

		$this->addbuttons = array("detail");

		$id = $this->conn->GetOne("select id_visi_misi from risk_visi_misi where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif')");

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if(!$this->data['row']['tgl_mulai_efektif'])
			$this->data['row']['tgl_mulai_efektif'] = $tgl_efektif;
/*
		if (!$this->data['row'] && $id)
			$this->NoData();*/

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]) {

				$return = $this->_beforeUpdate($record, $id);

				if($return){
					$return = $this->model->Update($record, "$this->pk = ".$this->conn->qstr($id));
				}

				if ($return['success']) {

					$this->log("mengubah ".$record['nama']);

					$return1 = $this->_afterUpdate($id);

					if(!$return1){
						$return = false;
					}
				}
			}else {

				$return = $this->_beforeInsert($record);

				if($return){
					$return = $this->model->Insert($record);
					$id = $return['data'][$this->pk];
				}

				if ($return['success']) {

					$this->log("menambah ".$record['nama']);

					$return1 = $this->_afterInsert($id);

					if(!$return1){
						$return = false;
					}
				}
			}

            $this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/edit/$id");

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Detail( $id=null){

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
		
		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		$id = $this->conn->GetOne("select id_visi_misi from risk_visi_misi where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif')");

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $this->access_role['edit']){
				redirect("panelbackend/risk_visi_misi/edit");
		}
		
		$this->data['id_strategi_map'] = $this->conn->GetOne("select 
			id_strategi_map
			from risk_strategi_map
			where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif') and nvl(tgl_akhir_efektif,'$tgl_efektif')
			");

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'visi',
				'label'=>'Visi',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'misi',
				'label'=>'Misi',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'konteks_internal',
				'label'=>'Konteks Internal',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'konteks_eksternal',
				'label'=>'Konteks Eksternal',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'strength',
				'label'=>'Strength',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'weakness',
				'label'=>'Weakness',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'opportunity',
				'label'=>'Opportunity',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'threat',
				'label'=>'Threat',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'unit',
				'label'=>'Unit',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtunitarr'],
			),
		);
	}

	protected function Record($id=null){
		return array(
			'visi'=>$this->post['visi'],
			'misi'=>$this->post['misi'],
			'strength'=>$this->post['strength'],
			'weakness'=>$this->post['weakness'],
			'opportunity'=>$this->post['opportunity'],
			'threat'=>$this->post['threat'],
			'unit'=>$this->post['unit'],
			'konteks_internal'=>$this->post['konteks_internal'],
			'konteks_eksternal'=>$this->post['konteks_eksternal'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
		);
	}

	protected function Rules(){
		return array(
			"visi"=>array(
				'field'=>'visi',
				'label'=>'Visi',
				'rules'=>"required|max_length[4000]",
			),
			"misi"=>array(
				'field'=>'misi',
				'label'=>'Misi',
				'rules'=>"required|max_length[4000]",
			),
			"konteks_internal"=>array(
				'field'=>'konteks_internal',
				'label'=>'Konteks Internal',
				'rules'=>"max_length[4000]",
			),
			"konteks_eksternal"=>array(
				'field'=>'konteks_eksternal',
				'label'=>'Konteks Eksternal',
				'rules'=>"max_length[4000]",
			),
			"tgl_mulai_efektif"=>array(
				'field'=>'tgl_mulai_efektif', 
				'label'=>'Tgl. Mulai Efektif', 
				'rules'=>"required",
			),
			"strength"=>array(
				'field'=>'strength',
				'label'=>'Strength',
				'rules'=>"max_length[4000]",
			),
			"weakness"=>array(
				'field'=>'weakness',
				'label'=>'Weakness',
				'rules'=>"max_length[4000]",
			),
			"opportunity"=>array(
				'field'=>'opportunity',
				'label'=>'Opportunity',
				'rules'=>"max_length[4000]",
			),
			"threat"=>array(
				'field'=>'threat',
				'label'=>'Threat',
				'rules'=>"max_length[4000]",
			),
		);
	}

}
