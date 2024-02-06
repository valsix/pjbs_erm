<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_high_info extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_high_infolist";
		$this->viewdetail = "panelbackend/risk_high_infodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Informasi Pendukung';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Informasi Pendukung';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Informasi Pendukung';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Informasi Pendukung';
		}

		$this->load->model("Risk_high_infoModel","model");
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);

		unset($this->access_role['index']);
		unset($this->access_role['lst']);
	}

	public function Edit($id=null){

		$this->addbuttons = array("detail");

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$id)
			$this->NoData();

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
			$record['id_risiko'] = $id;
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
				redirect("$this->page_ctrl/detail/$id");

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);
		$this->data['row']['id_risiko'] = $id;

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id=null){

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);
		if(!$this->data['rowheader1'])
			$this->NoData();

		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['edited']);

		$this->data['editedheader1'] = $this->data['edited'];

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));
		}
		$this->isLock();
	}

	public function Delete($id=null){

        $this->model->conn->StartTrans();

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if($return){
			$return = $this->model->delete("$this->pk = ".$this->conn->qstr($id));
		}

		if($return){
			$return1 = $this->_afterDelete($id);
			if(!$return1)
				$return = false;
		}

        $this->model->conn->CompleteTrans();

		if ($return) {

			$this->log("menghapus $id");

			redirect("panelbackend/home");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		}

	}

	public function Detail( $id=null){
		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$id)
			$this->NoData();

		$this->_afterDetail($id);
		$this->data['row']['id_risiko'] = $id;

		$this->View($this->viewdetail);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_risiko', 
				'label'=>'Risiko', 
				'width'=>"auto",
				'type'=>"number",
			),
			array(
				'name'=>'keterangan', 
				'label'=>'Keterangan', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'keterangan'=>$this->post['keterangan'],
		);
	}

	protected function Rules(){
		return array(
			"keterangan"=>array(
				'field'=>'keterangan', 
				'label'=>'Keterangan', 
				'rules'=>"max_length[4000]",
			),
		);
	}

}