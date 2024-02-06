<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_review extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_reviewlist";
		$this->viewdetail = "panelbackend/risk_reviewdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Review';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Review';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Review';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Review';
		}

		$this->load->model("Risk_reviewModel","model");

		$this->SetAccess(array('panelbackend/risk_scorecard','panelbackend/risk_risiko'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Record($id=null){
		return array(
			'review'=>$this->post['review'],
		);
	}

	protected function Rules(){
		return array(
			"review"=>array(
				'field'=>'review',
				'label'=>'Review',
				'rules'=>"required|max_length[4000]",
			),
		);
	}

	public function Index($id_risiko=null, $page=0){

		if ($this->post['act'] === 'save') {
			$this->Edit($id_risiko);
		}

		$this->_beforeDetail($id_risiko);
		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);

		$this->View($this->viewlist);
	}
	protected function _order(){
		return "created_date asc";
	}
	protected function _limit(){
		return -1;
	}

	public function Add($id_risiko = null){
		$this->Edit($id_risiko);
	}

	public function Edit($id_risiko=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_risiko);
		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
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

			$record['id_risiko'] = $id_risiko;
			$record['group_id'] = $_SESSION[SESSION_APP]['group_id'];

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

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				SetFlash('err_msg', "Data gagal disimpan");
			}
		}

		$this->_afterDetail($id);

		redirect("$this->page_ctrl/index/$id_risiko");
		die();
	}

	protected function _isValid($record=array(), $show_error=true){
		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE)
		{
			if($show_error){
				$this->data['err_msg'] = validation_errors();
			}

			$this->data['row'] = array_merge($this->data['row'],$record);

			$this->_afterDetail($this->data['row'][$this->pk]);

			$id_risiko = $record['id_risiko'];

			redirect("$this->page_ctrl/index/$id_risiko");

			die();
		}
	}


	public function Detail($id_risiko=null, $id=null){
		redirect("$this->page_ctrl/index/$id_risiko");
	}

	public function Delete($id_risiko=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_risiko);

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

			SetFlash('suc_msg', $return['success']);
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
		}

		redirect("$this->page_ctrl/index/$id_risiko");
		die();
	}

	protected function _beforeDetail($id){
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);

		if(!$this->data['rowheader1'])
			$this->NoData();
		
		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['editedheader1']);

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));
		}

		$this->data['add_param'] .= $id;
	}

}
