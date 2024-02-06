<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Audit_masukan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/masukan_audit_list";
		$this->viewprint = "panelbackend/masukan_audit_print";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		$this->data['page_title'] = 'Masukan Audit';

		$this->load->model("Risk_mitigasiModel","model");
		$this->load->model("Risk_risikoModel","modelrisiko");
		$this->load->model("Risk_scorecardModel","modelscorecard");

		$this->load->model("Mt_status_progressModel","mtprogress");
		$mtprogress = $this->mtprogress;
		$this->data['pregressarr'] = $mtprogress->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->access_role['list_print'] = 1;
	}
	protected function Header(){
		return array(
			array(
				'name'=>'kode_risiko',
				'field'=>'r____nomor',
				'label'=>'Kode',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama_risiko',
				'field'=>'r____nama',
				'label'=>'Nama Risiko',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama_aktifitas',
				'field'=>'m____nama',
				'label'=>'Aktivitas Mitigasi',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama_pic',
				'label'=>'Penanggung Jawab',
				'width'=>"auto",
				'type'=>"varchar2",
				'field'=>"j_____nama"
			),
			array(
				'name'=>'menurunkan_dampak_kemungkinan',
				'label'=>'K / D',
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-')+$this->data['menurunkanrr'],
			),
			array(
				'name'=>'dead_line',
				'label'=>'Dead Line',
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'id_status_progress',
				'label'=>'Progress',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['pregressarr'],
			),
		);
	}

	public function Add(){
		redirect("panelbackend/risk_scorecard");
	}


	protected function _getListPrint(){
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param=array(
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$respon = $this->model->SelectGridOverdue($param);

		return $respon;
	}
	public function Index($tipe = 0, $page=0){

		if($this->post['id_scorecard'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'] = $this->post['id_scorecard'];

		if($this->post['id_kajian_risiko']){
			if($this->post['id_kajian_risiko']<>$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'])
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']);
			
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'] = $this->post['id_kajian_risiko'];
		}



		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko']){
			$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'];

			$this->data['scorecardarr'] = $this->modelrisiko->GetComboDashboard($id_kajian_risiko);
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
			$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);
		}elseif($id_kajian_risiko){
			$id_scorecardarr = $this->modelscorecard->GetCombo(null, null, null, $id_kajian_risiko);
			$id_scorecardarr = array_keys($id_scorecardarr);
		}

		$this->data['id_kajian_risiko'] = $id_kajian_risiko;
		$this->data['id_scorecard'] = $id_scorecard;

		if($id_kajian_risiko)
			$this->_setFilter("s.id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko));

		if($id_scorecardarr)
			$this->_setFilter("r.id_scorecard in ('".implode("','", $id_scorecardarr)."')");
		
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

	protected function _getList($page=0){
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko']){
			$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'];
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
			$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);
		}elseif($id_kajian_risiko){
			$id_scorecardarr = $this->modelscorecard->GetCombo(null, null, null, $id_kajian_risiko);
			$id_scorecardarr = array_keys($id_scorecardarr);
		}

		$this->data['id_kajian_risiko'] = $id_kajian_risiko;
		$this->data['id_scorecard'] = $id_scorecard;

		if($id_kajian_risiko)
			$this->_setFilter("s.id_kajian_risiko = ".$this->conn->escape($id_kajian_risiko));

		if($id_scorecardarr)
			$this->_setFilter("r.id_scorecard in ('".implode("','", $id_scorecardarr)."')");

		$param=array(
			'page' => $page,
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if($this->post['act']){
			if($this->data['add_param']){
				$add_param = '/'.$this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(),"/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGridOverdue(
			$param
		);

		return $respon;
	}
}
