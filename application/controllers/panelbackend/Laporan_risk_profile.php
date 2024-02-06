<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Laporan_risk_profile extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout4";
		$this->viewprint = "panelbackend/laporanriskprofileprint";
		$this->viewindex = "panelbackend/laporanriskprofileindex";

		if ($this->mode == 'print_detail') {
			$this->data['page_title'] = 'Laporan Matriks Risiko';
		}else{
			$this->data['page_title'] = 'Laporan Matriks Risiko';
		}

		$this->load->model("Risk_risikoModel","model");

		$this->load->model("Risk_scorecardModel","mscorecard");

		$this->load->model("Risk_sasaran_strategisModel","sasaranstrategis");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker','select2'
		);

		$this->data['excel'] = false;
	}

	function Index($page=1){
		unset($this->data['mtjeniskajianrisikoarr']['']);
		$this->data['mtjeniskajianrisikoarr'] = array('semua'=>'-semua-')+$this->data['mtjeniskajianrisikoarr'];

		$this->data['row'] = $this->post;

		/*if(!$this->data['row']['id_kajian_risiko'])
			$this->data['row']['id_kajian_risiko'] = key($this->data['mtjeniskajianrisikoarr']);*/
		$tahun = date('Y');

		if($this->data['row']['tahun'])
			$tahun = $this->data['row']['tahun'];

		$this->data['sasaranarr'] = $this->sasaranstrategis->GetCombo(null, null, $tahun);
		unset($this->data['sasaranarr']['']);
		$this->data['sasaranarr'] = array('semua'=>'-semua-')+$this->data['sasaranarr'];

		if($this->data['row']['id_kajian_risiko']=='semua')
			unset($this->data['row']['id_kajian_risiko']);

		if($id_kajian_risiko = $this->data['row']['id_kajian_risiko']){
			$this->data['rowscorecards']=$this->mscorecard->GetList($id_kajian_risiko, null, null, 1, $tahun);
		}

		$this->View($this->viewindex);
	}

	public function go_print(){
		//$bulanarr = ListBulan();
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		$this->data['report']=1;

		$this->data['width_page']="1000px";

		$this->data['page_title'] .= "<br/>".$this->data['mtjeniskajianrisikoarr'][$this->get['id_kajian_risiko']];

		if($this->get['id_scorecard']==1){
			$this->conn->escape_string($this->get['id_scorecard']);
			$row_score = $this->conn->GetRow("select id_parent_scorecard, nama from risk_scorecard where id_scorecard in ('".implode("','", $this->get['id_scorecard'])."')");

			$id_parent_scorecard = $row_score['id_parent_scorecard'];
			$nama_scorecard = $row_score['nama'];

			if($id_parent_scorecard){
				$nama_parent = $this->conn->GetOne("select nama from risk_scorecard where id_scorecard = ".$this->conn->escape($id_parent_scorecard));
				if($nama_parent)
					$this->data['page_title'] .= " ".$nama_parent." ";
			}

			if($nama_scorecard)
				$this->data['page_title'] .= " ".$nama_scorecard." ";
		}

		$this->data['page_title'] .= " ".($this->get['tahun']?" Tahun ".$this->get['tahun']:"");

		$this->data['page_title'] = strtoupper($this->data['page_title']);

		$param = $this->get;
		foreach (str_split($param['rating']) as $key => $value) {
			$this->data['rating'][$value] = 1;
		}

		$this->data['rows'] = $this->model->getListRiskProfile($param);
		
		$periode= $param['tahun'].$param['bulan'];

		$this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan where to_date('".$periode."', 'YYYYMM') between sk_tanggal_awal and sk_tanggal_akhir order by id_kemungkinan desc");

		$this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak where to_date('".$periode."', 'YYYYMM') between sk_tanggal_awal and sk_tanggal_akhir order by id_dampak asc");
		
		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.NAMA, mrt.WARNA
			from mt_risk_matrix mrm
			join MT_RISK_TINGKAT mrt on mrt.ID_TINGKAT = mrm.ID_TINGKAT");

    $this->View($this->viewprint);
	}
}
