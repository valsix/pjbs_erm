<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_taksonomi_area extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_taksonomi_arealist";
		$this->viewdetail = "panelbackend/mt_taksonomi_areadetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Area';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Area';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Area';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Area';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_taksonomi_areaModel","model");
		$this->load->model("Mt_taksonomi_objectiveModel",'objective');
		$this->data['objectivearr'] = $this->objective->GetCombo();
		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function _beforeDetail($id_taksonomi_objective=null, $id=null){
		$this->data['rowheader']  = $this->objective->GetByPk($id_taksonomi_objective);		
		$this->data['add_param'] .= $id_taksonomi_objective;
		$this->data['page_title'] .= " \"".$this->data['rowheader']['nama']."\"";
		$this->data['str_left'] .= '
		<ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 10px;margin: 0px;">
	    	<li><a  href="'.site_url("panelbackend/mt_taksonomi_objective").'"><i class="material-icons">home</i></a></li>
			<li >'.strtoupper($this->data['rowheader']['kode']." ".$this->data['rowheader']['nama']).'</li>
		</ol>';
	}

	public function Index($id_taksonomi_objective=null, $page=0){
		$this->_beforeDetail($id_taksonomi_objective,$id);

		$this->_setFilter("id_taksonomi_objective = ".$this->conn->qstr($id_taksonomi_objective));

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_mulai_efektif, '$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif') ");

		}
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_taksonomi_objective"),
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
		
		$this->isLock(); 

		$this->View($this->viewlist);
	}

	public function Add($id_taksonomi_objective = null){
		$this->Edit($id_taksonomi_objective);
	}

	public function Edit($id_taksonomi_objective=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_taksonomi_objective, $id);

		$this->data['idpk'] = $id;

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
			$id_taksonomi_objective = $record['id_taksonomi_objective'];

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $this->model->conn->StartTrans();
			if (trim($this->data['row'][$this->pk])==trim($id) && trim($id)) {

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
				redirect("$this->page_ctrl/detail/$id_taksonomi_objective/$id");

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

	protected function _afterDetail($id=null){
		$this->data[$this->data['row']['id_taksonomi_objective']] = $this->conn->GetOne("select nama from mt_taksonomi_objective where id_taksonomi_objective = ".$this->conn->escape($this->data['row']['id_taksonomi_objective']));
	}

	public function Detail($id_taksonomi_objective=null,$id=null){

		$this->_beforeDetail($id_taksonomi_objective, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		redirect("panelbackend/mt_taksonomi_risiko/index/$id");
	}

	public function Delete($id_taksonomi_objective=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_taksonomi_objective,$id);

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
			redirect("$this->page_ctrl");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_taksonomi_objective/$id");
		}

	}

	protected function Header(){
		return array(
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"70px",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			)
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'id_taksonomi_objective'=>$this->post['id_taksonomi_objective'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|max_length[5]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
		);
	}

}