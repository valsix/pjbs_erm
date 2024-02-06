<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_matrix extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_matrixlist";
		$this->viewdetail = "panelbackend/mt_risk_matrixdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Matriks Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Matriks Risiko';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Matriks Risiko';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Matriks Risiko';
		}

		$this->load->model("Mt_risk_matrixModel","model");

		$this->load->model("Mt_risk_tingkatModel","mttingkat");
		$this->data['mttingkatarr'] = $this->mttingkat->GetCombo();		

		$this->load->model("v2/SkMatriksResiko","skmatriks");
		$this->data['skmatriks'] = $this->skmatriks->GetComboNew("nomor");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Detail($id_kemungkinan=null, $id_dampak=null){

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id_kemungkinan, $id_dampak);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);


		$this->data['add_param'] = $id_kemungkinan;

		$this->View($this->viewdetail);
	}

	public function Delete($id_kemungkinan=null, $id_dampak=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id_kemungkinan, $id_dampak);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if($return){
			$return = $this->model->delete("id_kemungkinan = ".$this->conn->escape($id_kemungkinan).
            	" and id_dampak = ".$this->conn->escape($id_dampak));
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
			redirect("$this->page_ctrl");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}

	}

	public function Edit($id_kemungkinan=null, $id_dampak=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id_kemungkinan, $id_dampak);

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			if(!$record['id_dampak'])
				unset($record['id_dampak']);

			if(!$record['id_kemungkinan'])
				unset($record['id_kemungkinan']);

			$this->data['row'] = array_merge($this->data['row'],$record);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $this->model->conn->StartTrans();

            $cek = $this->conn->GetOne("select 1 from mt_risk_matrix 
            	where id_kemungkinan = ".$this->conn->escape($this->data['row']['id_kemungkinan']).
            	" and id_dampak = ".$this->conn->escape($this->data['row']['id_dampak']));

			if ($cek) {

				$return = $this->_beforeUpdate($record, $id);

				if($return){
					$return = $this->model->Update($record, "id_kemungkinan = ".$this->conn->escape($this->data['row']['id_kemungkinan']).
            	" and id_dampak = ".$this->conn->escape($this->data['row']['id_dampak']));
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
				redirect("$this->page_ctrl/detail/$id_kemungkinan/$id_dampak");

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);
		
		$this->data['add_param'] .= $id_kemungkinan;

		$this->View($this->viewdetail);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'id_kemungkinan', 
				'label'=>'Kemungkinan', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'')+$this->data['mtkemungkinanarr'],
			),
			array(
				'name'=>'id_dampak', 
				'label'=>'Dampak', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'')+$this->data['mtdampakrisikoarr'],
			),
			array(
				'name'=>'id_tingkat', 
				'label'=>'Tingkat Risiko', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'')+$this->data['mttingkatarr'],
			),
		);
	}

	protected function Record($id=null){
		return array(
			'id_tingkat'=>$this->post['id_tingkat'],
			'id_kemungkinan'=>$this->post['id_kemungkinan'],
			'id_dampak'=>$this->post['id_dampak'],
			'css'=>$this->post['css'],
		);
	}

	protected function Rules(){
		return array(
			"id_tingkat"=>array(
				'field'=>'id_tingkat', 
				'label'=>'Tingkat', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mttingkatarr']))."]",
			),
			"id_kemungkinan"=>array(
				'field'=>'id_kemungkinan', 
				'label'=>'Kemungkinan', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]",
			),
			"id_dampak"=>array(
				'field'=>'id_dampak', 
				'label'=>'Dampak', 
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]",
			),
		);
	}

}