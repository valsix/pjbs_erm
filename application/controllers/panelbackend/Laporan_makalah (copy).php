<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Laporan_makalah extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout4";
		$this->viewprint = "panelbackend/laporanmakalahrint";
		$this->viewindex = "panelbackend/laporanmakalahindex";

		if ($this->mode == 'print_detail') {
			$this->data['page_title'] = 'Laporan Makalah';
		}else{
			$this->data['page_title'] = 'Laporan Makalah';
		}

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","mscorecard");
		
	}


	function Index($page=1){
		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		unset($this->data['mtjeniskajianrisikoarr']['']);
		$this->data['mtjeniskajianrisikoarr'] = array('semua'=>'-semua-')+$this->data['mtjeniskajianrisikoarr'];

		$this->data['row'] = $this->post;

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($this->data['row']['tahun']){
			$thn = $this->data['row']['tahun'];
			$bln = '12';
		}else{
			$this->data['row']['tahun'] = $thn;
		}

		$this->data['row']['top'] = $this->config->item('risk_top_risiko');

		$this->data['scorecardarr'] = $this->model->GetComboDashboard($this->data['row']['id_kajian_risiko'], $tgl_efektif);

		if($this->data['row']['id_scorecard'])
			$this->data['scorecardsubarr'] = $scorecardsubarr = $this->mscorecard->GetComboChild($this->data['row']['id_scorecard']);

		$this->View($this->viewindex);
	}

	public function go_print(){
		$this->load->library("word");
		$word = $this->word;
		$word->template('./assets/file/KAJIANRISIKOSTRATEGIS.docx');
		$temp = $word->templateProcessor;


		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}


		$id_scorecard = $this->get['id_scorecard'];
		$id_kajian_risiko = $this->get['id_kajian_risiko'];
		$id_scorecard_sub = $this->get['id_scorecard_sub'];
		$tahun = $this->get['tahun'];
		$top = $this->get['top'];

		$scorecardarr = $this->model->GetComboDashboard($id_kajian_risiko, $tgl_efektif);

		$id_scorecardarr = array();
		if($id_scorecard){

			$scorecardsubarr = $this->mscorecard->GetComboChild($id_scorecard);

			if($scorecardsubarr[$id_scorecard_sub] && $id_scorecard_sub){
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard_sub);
			}else{
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard);
			}
		}

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($tahun){
			$thn = $tahun;
			$bln = '12';
		}else{
			$this->data['tahun'] = $thn;
		}

		if(!$top)
			$top = $this->config->item('risk_top_risiko');

		if(!$top)
			$top = 10;

		$order = $this->config->item('risk_order_risiko');

		if(!$order)
			$order = 'c';

		$param = array(
			"rating"=>"icr",
			"id_kajian_risiko"=>$id_kajian_risiko,
			"top"=>$top,
			"all"=>(!(bool)$id_scorecardarr),
			"id_scorecard"=>$id_scorecardarr,
			"tahun"=>$thn,
			"bulan"=>$bln,
			"order"=>$order
		);

		$rows = $this->model->getListRiskProfile($param);

		if($rows){
			$rows1 = $this->conn->GetArray("select a.*, b.prosentase from risk_mitigasi a
				join mt_status_progress b on a.id_status_progress = b.id_status_progress
				where id_risiko in (".$this->conn->GetKeysStr($rows, 'id_risiko').")
				order by a.nama asc");

			$rowsmitigasi = array();
			foreach($rows1 as $r){
				$rowsmitigasi[$r['id_risiko']][] = $r;
			}
		}

		if($rows){
			$rows1 = $this->conn->GetArray("select a.* from risk_control a
				where id_risiko in (".$this->conn->GetKeysStr($rows, 'id_risiko').")
				order by a.nama asc");

			$rowscontrol = array();
			foreach($rows1 as $r){
				$rowscontrol[$r['id_risiko']][] = $r;
			}
		}

		//dpr($rows,1);
		
		// Simple table
		$temp->cloneRow('riskno', $rows);
		foreach ($rows as $key => $value) {
			$temp->setValue('riskno#'.($key+1), $key+1);
			foreach($value as $k=>$v){
				$temp->setValue('risk'.$k.'#'.($key+1), $v);
			}
		}

		$temp->setValue('risk_profile', $scorecardarr[$id_scorecard]);
		$temp->setValue('tahun', $tahun);

	/*	// Simple table
		$temp->cloneRow('riskno', $rows);
		foreach ($rows as $key => $value) {
			$temp->setValue('riskno#'.($key+1), $key+1);
			foreach($value as $k=>$v){
				$temp->setValue('risk'.$k.'#'.($key+1), $v);
			}
		}*/
		$word->download($scorecardarr[$id_scorecard].' '.$tahun.'.docx');
	}
}
