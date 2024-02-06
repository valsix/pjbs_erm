<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_sort extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_risiko_sortlist";
		$this->viewdetail = "panelbackend/risk_risiko_sortdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";
		$this->data['page_title'] = 'Urutan Tingkat Risiko';

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","modelscorecard");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	public function Index($page=0){

		$tgl_efektif = date('d-m-Y');
		$top = 10;
		$id_kajian_risiko = null;
		$id_scorecard = null;
		$order = null;
		$id_scorecardarr = array();

		if($this->post['top']){
			$this->UpdateConfig("risk_top_risiko", $this->post['top']);
		}

		if($this->post['order']){
			$this->UpdateConfig("risk_order_risiko", $this->post['order']);
		}

		if($this->post['id_scorecard']!==null)
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child'] = $this->post['id_scorecard_child'];

		if($this->post['id_scorecard']){
			if($this->post['id_scorecard']<>$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child']);
			}

			$_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'] = $this->post['id_scorecard'];
		}

		if($this->post['id_kajian_risiko']){
			if($this->post['id_kajian_risiko']<>$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko']){
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']);
				unset($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child']);
			}
			
			$_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'] = $this->post['id_kajian_risiko'];
		}

		if($this->post['act']=='sort_up')
			$this->conn->Execute("update risk_risiko set urutan = nvl(urutan,0)-1 where id_risiko = ".$this->conn->escape($this->post['key']));

		if($this->post['act']=='sort_down')
			$this->conn->Execute("update risk_risiko set urutan = nvl(urutan,0)+1 where id_risiko = ".$this->conn->escape($this->post['key']));

		if($this->post['act']=='set_merge'){
			$mergearr = array_keys($this->post['merge']);
			$mergestr = $mergearr[0];
			foreach($mergearr as $id_risiko){
				$this->conn->Execute("update risk_risiko set merge = ".$this->conn->escape($mergestr)." where id_risiko = ".$this->conn->escape($id_risiko));
			}

			redirect(current_url());
		}

		if($this->post['act']=='set_unmerge'){
			$mergearr = array_keys($this->post['merge']);
			foreach($mergearr as $merge){
				$this->conn->Execute("update risk_risiko set merge = null, nama_merge = null where merge = ".$this->conn->escape($merge));
			}

			redirect(current_url());
		}

		if($this->post['act']=='save_nama'){
			$this->conn->Execute("update risk_risiko set nama_merge = ".$this->conn->escape($this->post['nama_merge'])." where merge = ".$this->conn->escape($this->post['key']));
			redirect(current_url());
		}

		if($this->post)
			redirect(current_url());

		if($_SESSION[SESSION_APP]['tgl_efektif'])
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		if($this->config->item('risk_top_risiko'))
			$top = $this->config->item('risk_top_risiko');

		if($this->config->item('risk_order_risiko'))
			$order = $this->config->item('risk_order_risiko');
		else
			$order = "c";

		$id_scorecard_child = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child'];
		$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
		$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'];

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'])
			$this->data['scorecardarr'] = $this->model->GetComboDashboard($id_kajian_risiko);

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'] && $id_kajian_risiko<>3)
			$this->data['scorecardchildarr'] = $this->modelscorecard->GetComboChild($id_scorecard, true);

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child']){
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard_child);
		}elseif($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
			$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
			$id_scorecardarr = $this->modelscorecard->GetChild($id_scorecard);
		}elseif($id_kajian_risiko){
			$id_scorecardarr = $this->modelscorecard->GetCombo(null, null, null, $id_kajian_risiko);

			unset($id_scorecardarr['']);
			$id_scorecardarr = array_keys($id_scorecardarr);
		}

		$this->data['id_kajian_risiko'] = $id_kajian_risiko;
		$this->data['id_scorecard'] = $id_scorecard;
		$this->data['id_scorecard_child'] = $id_scorecard_child;
		$this->data['top'] = $top;
		$this->data['order'] = $order;

		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.NAMA, mrt.WARNA
			from mt_risk_matrix mrm
			join MT_RISK_TINGKAT mrt on mrt.ID_TINGKAT = mrm.ID_TINGKAT");

		$this->load->model("Risk_risikoModel","model");

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);
		$param = array(
			"rating"=>"icr",
			"id_kajian_risiko"=>$id_kajian_risiko,
			"top"=>$top,
			"all"=>false,
			"id_scorecard"=>$id_scorecardarr,
			"tahun"=>$thn,
			"bulan"=>$bln,
			"tanggal"=>$tgl,
			"order"=>$order
		);

		foreach (str_split($param['rating']) as $key => $value) {
			$this->data['rating'][$value] = 1;
		}

		$this->data['rows'] = $this->model->getListRiskProfile($param);

		$this->View($this->viewlist);
	}
}