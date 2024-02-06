<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_sasaran_kegiatan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_sasaran_kegiatanlist";
		$this->viewdetail = "panelbackend/risk_sasaran_kegiatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sasaran Kegiatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sasaran Kegiatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Sasaran Kegiatan';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Sasaran Kegiatan';
		}

		$this->load->model("Risk_sasaran_kegiatanModel","model");

		$this->load->model("Risk_sasaran_strategisModel","sasaranstrategis");

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
				'name'=>'id_sasaran_strategis',
				'label'=>'Sasaran Strategis',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['sasaranarr'],
			),
			array(
				'name'=>'nama',
				'label'=>'Sasaran Kegiatan',
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		$record =  array(
			'nama'=>$this->post['nama'],
			'deskripsi'=>$this->post['deskripsi'],
			'kpi'=>$this->post['kpi'],
			'owner'=>$this->post['owner'],
			'deskripsi_kpi'=>$this->post['deskripsi_kpi'],
			'id_sasaran_strategis'=>$this->post['id_sasaran_strategis'],
		);

		return $record;
	}

	protected function Rules(){
		return array(
			"name"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"owner"=>array(
				'field'=>'owner',
				'label'=>'Owner',
				'rules'=>"required|callback_inlistjabatan",
			),
			"kpi"=>array(
				'field'=>'kpi',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"deskripsi_kpi"=>array(
				'field'=>'deskripsi_kpi',
				'label'=>'Deskripsi KPI',
				'rules'=>"max_length[500]",
			),
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
			"id_sasaran_strategis"=>array(
				'field'=>'id_sasaran_strategis',
				'label'=>'Sasaran Strategis',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['sasaranarr']))."]",
			),
		);
	}

    public function inlistjabatan($str)
    {
		$result = $this->mjabatan->GetCombo($str);

    	if(!$result[$str]){
            $this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
            return FALSE;
    	}

    	return true;
    }
	public function Index($id_scorecard=null){
		$this->_beforeDetail($id_scorecard);
	}

	public function Index1($page=0){

		if($this->post['act']=='setpic'){
			$_SESSION[SESSION_APP][$this->page_ctrl]['pic'] = $this->post['pic'];
			redirect(current_url());
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['pic'])
			$this->data['pic'] = $_SESSION[SESSION_APP][$this->page_ctrl]['pic'];
		else
			 $_SESSION[SESSION_APP][$this->page_ctrl]['pic'] = $this->data['pic'] = $_SESSION[SESSION_APP]['pic'];

		$this->layout = "panelbackend/layout2";

		$owner = $this->data['pic'];
		
		$this->data['sasaranarr'] = $this->sasaranstrategis->GetCombo($owner);

		$this->data['jabatanarr'] = array();
		if(!$this->Access('view_all_direktorat',$this->page_ctrl)){
			$this->data['jabatanarr'] += $this->mjabatan->GetCombo();
		}

		if($this->data['pic']){
			$pic = $this->data['pic'];
			$this->data['jabatanarr'] += array($pic=>$this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($pic)));
			$this->_setFilter("owner = ".$this->conn->qstr($pic));

			$kajian = $this->conn->GetArray("select s.id_scorecard, k.* from risk_scorecard s join mt_risk_kajian_risiko k on s.id_kajian_risiko = k.id_kajian_risiko
				where s.owner = ".$this->conn->escape($pic));

			$this->data['kajian'] = array();
			foreach ($kajian as $r) {
				$this->data['kajian'][$r['jenis_sasaran']][]=$r;
			}
		}
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

	protected function _beforeDetail($id){
		if(!$id && $_SESSION[SESSION_APP][$this->page_ctrl]['pic'] && !$this->post['owner'])
			$this->post['owner'] = $_SESSION[SESSION_APP][$this->page_ctrl]['pic'];

		if(!$this->post['owner'] && $id){
			$owner = $_SESSION[SESSION_APP][$this->page_ctrl]['pic'] = $this->conn->GetOne("select owner from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($id));
		}else{
			$owner = $this->post['owner'];
		}

		$this->data['sasaranarr'] = $this->sasaranstrategis->GetCombo($owner);
	}

	protected function _afterDetail($id){

		$owner = $this->data['row']['owner'];

		$result = $this->mjabatan->GetCombo($owner);

		if(!$result[$owner])
			$this->access_role['edit'] = $this->access_role['delete'] = false;

		if($owner)
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));
	}
    

	protected function _beforeEdit(&$record=array(), $id){

		$owner = $this->data['row']['owner'];

		$result = $this->mjabatan->GetCombo($owner);

		if(!$result[$owner])
			$this->NoData();

		$_SESSION[SESSION_APP][$this->page_ctrl]['pic'] = $owner;
	}

	protected function _beforeDelete($id){

		$owner = $this->data['row']['owner'];

		$result = $this->mjabatan->GetCombo($owner);

		if(!$result[$owner])
			$this->NoData();

		return true;
	}
}
