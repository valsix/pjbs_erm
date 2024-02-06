<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Monitoring_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/monitoring_grclist";
		$this->viewprint = "panelbackend/monitoring_grcprint";
		// $this->viewdetail = "panelbackend/mt_folder_dokgrcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Monitoring GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Monitoring GRC';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Monitoring GRC';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Monitoring GRC';
		}

		$this->load->model("Risk_scorecardModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->data['status'] = array("1"=>"Aktif", "0"=>"Non Aktif");
	}

	public function Index($page=0){

		$tahun = date("Y");

		if($this->post['tahun']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun'] = $this->post['tahun'];
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['tahun'])
			$tahun = $_SESSION[SESSION_APP][$this->page_ctrl]['tahun'];

		$this->data['layout_header'] .= "Tahun ".UI::createTextNumber('tahun',$tahun,'','',true,'form-control',"style='text-align:left; width: 90px; display: inline; font-size: 15px;' step='any' onchange='goSubmit(\"set_tahun\")'");

		$this->data['tahun'] = $tahun;

		$this->data['header']=$this->Header();

		$this->_setFilter(" to_char(tgl_mulai_efektif, 'YYYY')=".$this->conn->escape($tahun));

		$this->data['list']=$this->_getList($page);

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

	protected function _getList($page=0){
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param=array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if($this->post['act'] && $this->post['act']<>'save'){
			
			if($this->data['add_param']){
				$add_param = '/'.$this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(),"/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGridGrc(
			$param
		);

		return $respon;
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'status'=>$this->post['status'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
		);
	}

	protected function _getListPrint(){
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param=array(
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$respon = $this->model->SelectGridPrintGrc($param);

		return $respon;
	}

	public function go_print($id=null){
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		// $this->_setFilter("a.id_pengurangantool = ".$this->conn->escape($id));

		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getListPrint();

		$this->data['width'] = "900px";
		$this->data['no_header'] =true;

		$this->data['row'] = $this->model->GetByPk($id);
		$this->_afterDetail($id);

		// print_r($this->data);exit;
		// $this->View("panelbackend/form_pengurangantoolprint");

		$this->View($this->viewprint);
	}

}