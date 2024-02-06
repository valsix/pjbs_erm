<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_log_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_loglist";
		$this->viewdetail = "panelbackend/risk_logdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk LOG';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk LOG';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risk LOG';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Risk LOG';
		}

		$this->load->model("Risk_logModel","model");

		$this->load->model("Public_sys_groupModel","publicsysgroup");
		$publicsysgroup = $this->publicsysgroup;
		$rspublicsysgroup = $publicsysgroup->GArray();

		$publicsysgrouparr = array(''=>'');
		foreach($rspublicsysgroup as $row){
			$publicsysgrouparr[$row['group_id']] = $row['name'];
		}

		$this->data['publicsysgrouparr'] = $publicsysgrouparr;

		$this->SetAccess(array('panelbackend/risk_scorecard','panelbackend/risk_risiko'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama_user',
				'field'=>'u.name',
				'label'=>'User',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'group_id',
				'field'=>'group_id',
				'label'=>'Akses',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['publicsysgrouparr'],
			),
			array(
				'name'=>'created_date1',
				'field'=>'t.activity_time',
				'label'=>'Waktu',
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'deskripsi',
				'label'=>'Aktivitas',
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	public function Index($id_risiko=null, $page=0){

		$this->_beforeDetail($id_risiko);
		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_risiko"),
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
		$this->Error404();
	}

	public function Edit($id_scorecard=null, $id=null){
		$this->Error404();
	}


	public function Detail($id_scorecard=null, $id=null){
		$this->Error404();
	}

	public function Delete($id_scorecard=null, $id=null){
		$this->Error404();
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
