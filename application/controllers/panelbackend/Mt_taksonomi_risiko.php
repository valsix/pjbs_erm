<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_taksonomi_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_taksonomi_risikolist";
		$this->viewdetail = "panelbackend/mt_taksonomi_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_taksonomi";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Taksonomi ';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Taksonomi ';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Taksonomi ';
			$this->data['edited'] = false;	
		}else{
			$this->layout = "panelbackend/layout1";
			$this->data['page_title'] = 'Daftar Taksonomi ';
		}

		$this->data['width'] = "800px";

		$this->load->model("Mt_taksonomi_risikoModel","model");
		$this->load->model("Mt_taksonomi_areaModel","area");
		$this->data['areaarr'] = $this->area->GetCombo();
		
		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Detail($id_taksonomi_area=null,$id=null){

		$this->_beforeDetail($id_taksonomi_area, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDetail($id_taksonomi_area=null, $id=null){
		$this->load->model("Mt_taksonomi_areaModel",'area');
		$this->load->model("Mt_taksonomi_objectiveModel",'objective');
		$this->data['rowheader1']  = $this->area->GetByPk($id_taksonomi_area);		
		$id_taksonomi_objective = $this->data['rowheader1']['id_taksonomi_objective'];
		$this->data['rowheader']  = $this->objective->GetByPk($id_taksonomi_objective);		
		$this->data['add_param'] .= $id_taksonomi_area;
		$this->data['page_title'] .= " \"".$this->data['rowheader1']['nama']."\"";
		$this->data['str_left'] .= '
		<ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 10px;margin: 0px;">
	    	<li><a  href="'.site_url("panelbackend/mt_taksonomi_objective").'"><i class="material-icons">home</i></a></li>
	    	<li><a  href="'.site_url("panelbackend/mt_taksonomi_area/index/$id_taksonomi_objective").'">'.strtoupper($this->data['rowheader']['kode']." ".$this->data['rowheader']['nama']).'</a></li>';

		if($id){
			$row = $this->conn->GetRow("select * from mt_taksonomi_risiko where id_taksonomi_risiko = ".$this->conn->escape($id));
			$this->data['str_left'] .= '
			<li><a  href="'.site_url("panelbackend/mt_taksonomi_risiko/index/$id_taksonomi_area").'">'.strtoupper($this->data['rowheader1']['kode']." ".$this->data['rowheader1']['nama']).'</a></li>
			<li >'.strtoupper($row['kode']." ".$row['nama']).'</li>';
		}else{
			$this->data['str_left'] .= '
			<li >'.strtoupper($this->data['rowheader1']['kode']." ".$this->data['rowheader1']['nama']).'</li>';
		}

		$this->data['str_left'] .= '</ol>';
	}

	protected function _afterDetail($id=null){
		$this->data['rowheader2'] = $this->data['row'];
		$this->data[$this->data['row']['id_taksonomi_area']] = $this->conn->GetOne("select nama from mt_taksonomi_area where id_taksonomi_area = ".$this->conn->escape($this->data['row']['id_taksonomi_area']));
	}

	public function Index($id_taksonomi_area=null, $page=0){
		$this->_beforeDetail($id_taksonomi_area,$id);

		$this->_setFilter("id_taksonomi_area = ".$this->conn->qstr($id_taksonomi_area));
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_taksonomi_area"),
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

	public function Add($id_taksonomi_area = null){
		$this->Edit($id_taksonomi_area);
	}

	public function Edit($id_taksonomi_area=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_taksonomi_area, $id);

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
			$id_taksonomi_area = $record['id_taksonomi_area'];

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
				redirect("$this->page_ctrl/detail/$id_taksonomi_area/$id");

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

	protected function _afterUpdate($id){
		$ret = true;

		$ret = $this->conn->goUpdate("risk_risiko",array("nama"=>$this->post['nama']),"id_taksonomi_risiko = ".$this->conn->escape($id));

		return $ret;
	}

	public function Delete($id_taksonomi_area=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_taksonomi_area,$id);

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
			redirect("$this->page_ctrl/index/$id_taksonomi_area");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_taksonomi_area/$id");
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
			),
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'id_taksonomi_area'=>$this->post['id_taksonomi_area'],
			'penyebab'=>$this->post['penyebab'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[200]",
			),
		);
	}

}