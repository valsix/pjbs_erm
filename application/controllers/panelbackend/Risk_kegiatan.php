<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_kegiatan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_kegiatanlist";
		$this->viewdetail = "panelbackend/risk_kegiatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kegiatan dalam Kajian Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kegiatan dalam Kajian Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kegiatan dalam Kajian Risiko';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Kegiatan dalam Kajian Risiko';
		}

		$this->load->model("Risk_kegiatanModel","model");

		$this->SetAccess('panelbackend/risk_scorecard');

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
				'label'=>'Nama Kegiatan',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'deskripsi',
				'label'=>'Deskripsi',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'id_status', 
				'label'=>'Status', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtstatusarr'],
			),
		);
	}

	protected function Record($id=null){
		$record =  array(
			'nama'=>$this->post['nama'],
			'deskripsi'=>$this->post['deskripsi'],
		);

		if(!$id)
			$record['id_status'] = 1;

		return $record;
	}

	protected function Rules(){
		return array(
			"name"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi',
				'label'=>'Deskripsi',
				'rules'=>"max_length[4000]",
			),
		);
	}

	public function Index($id_scorecard=null, $page=0){
		$this->_beforeDetail($id_scorecard);
		$this->_setFilter("id_scorecard = ".$this->conn->qstr($id_scorecard));
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index"),
			'cur_page'=>$page,
			'total_rows'=>$this->data['list']['total'],
			'per_page'=>$this->limit,
			'first_tag_open'=>'<li>',
			'first_tag_close'=>'</li>',
			'last_tag_open'=>'<li>',
			'last_tag_close'=>'</li>',
			'cur_tag_open'=>'<li class="active"><a href="#">',
			'cur_tag_close'=>'</a></li>',
			'next_tag_open'=>'<li>',
			'next_tag_close'=>'</li>',
			'prev_tag_open'=>'<li>',
			'prev_tag_close'=>'</li>',
			'num_tag_open'=>'<li>',
			'num_tag_close'=>'</li>',
			'anchor_class'=>'pagination__page',

		);
		$this->load->library('pagination');

		$paging = $this->pagination;

		$paging->initialize($param_paging);

		$this->data['paging']=$paging->create_links();

		$this->data['limit']=$this->limit;

		$this->data['limit_arr']=$this->limit_arr;

		$this->View($this->viewlist);
	}

	public function Add($id_scorecard = null){
		$this->Edit($id_scorecard);
	}

	public function Edit($id_scorecard=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard);
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

			$record['id_scorecard'] = $id_scorecard;

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
				redirect("$this->page_ctrl/detail/$id_scorecard/$id");

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


	public function Detail($id_scorecard=null, $id=null){

		$this->_beforeDetail($id_scorecard,$id);

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_scorecard=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_scorecard);

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
			redirect("$this->page_ctrl/index/$id_scorecard");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		}

	}

	protected function _beforeEdit(&$record=array(), $id){
		$this->_validAccessTask();
	}

	protected function _beforeDelete($id){
		$this->_validAccessTask();
		return true;
	}

	protected function _beforeDetail($id){
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);

		if(!$this->data['rowheader'])
			$this->Error403();

		$this->_getListTask("scorecard", $this->data['rowheader'], $this->data['editedheader']);

		$this->data['add_param'] .= $id;
	}

	protected function _afterDetail($id){

		$this->_getListTask("kegiatan", $this->data['row'], $this->data['edited']);
	}
}
