<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Laporan_kertas_kerja extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout4";
		$this->viewprint = "panelbackend/laporankertaskerjaprint";
		$this->viewindex = "panelbackend/laporankertaskerjaindex";

		if ($this->mode == 'print_detail') {
			$this->data['page_title'] = 'Laporan Kertas Kerja';
		}else{
			$this->data['page_title'] = 'Laporan Kertas Kerja';
		}

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","mscorecard");
		$this->load->model("Mt_risk_efektifitasModel","mefektif");

		$this->data['efektifarr'] = $this->mefektif->GetCombo();
		unset($this->data['efektifarr']['']);

		$this->load->model("Mt_status_progressModel","mtprogress");
		$mtprogress = $this->mtprogress;
		$this->data['pregressarr'] = $mtprogress->GetCombo();

		$this->load->model("Mt_risk_tingkatModel","mttingkat");
		$this->data['tingkatarr'] = $this->mttingkat->GetCombo();
		unset($this->data['tingkatarr']['']);
		krsort($this->data['tingkatarr']);

		$this->data['mtbidangarr'] = $this->conn->GetList("select distinct kd_subdit as key, subdit_ket as val from mt_sdm_jabatan order by kd_subdit");

		$this->load->model("Risk_sasaran_strategisModel","sasaranstrategis");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker','select2'
		);
		
	}

	function Header(){
		$norowspan_control = array();

		$this->data['type_header'] = array(
			'id_status_pengajuan'=>array(
				'list'=>$this->data['mtstatusarr']
			),
			'control_efektif'=>array(
				'list'=>array('1'=>'Ya','2'=>'Tidak')
			),
			'capaian_mitigasi'=>array(
				'list'=>$this->data['pregressarr']
			),
			'kategori_kemungkinan'=>array(
				'list'=>array(''=>'','1'=>'Deskripsi Kualitatif','2'=>'Probabilitas','3'=>'Insiden Sebelumnya')
			),
			'waktu_pelaksanaan'=>'date',
			'biaya_mitigasi'=>'rupiah',
			'level_risiko_inheren'=>'rating',
			'level_risiko_paskakontrol'=>'rating',
			'level_risiko_residual'=>'rating',
			'level_risiko_paskamitigasi'=>'rating',
			'progress'=>'persen',
			'ratarataprogress'=>'persen',
			'ratarataefektif'=>'persen',
		);

		$return1 = array(
			"scorecard"=>"Scorecard",
			"kode_risiko"=>"Kode",
			"sasaran_strategis"=>"Sasaran Strategis",
			"sasaran_kegiatan"=>"Sasaran Kegiatan",
			"proses_bisnis"=>array(
				"Proses Bisnis"=>
				array(
					"kategori_proses"=>"Kategori",
					"kelompok_proses"=>"Kelompok Proses",
					"nama_proses"=>"Nama Proses",
					"aktifitas"=>"Aktivitas",
				)
			),
			"identifikasi_risiko"=>array(
				"Identifikasi Risiko"=>
				array(
					"risiko"=>"Risiko",
					"penyebab"=>"Penyebab",
					"dampak"=>"Dampak"
				)
			),
			"risk_owner"=>"Pemilik Risiko",
			"id_status_pengajuan"=>"Status Pengajuan",
			"risiko_inheren"=>array(
				"Inheren Risk"=>
				array(
					"inheren_kemungkinan"=>"Tingkat Kemungkinan",
					"kategori_kemungkinan"=>"Kriteria Kemungkinan",
					"inheren_dampak"=>"Tingkat Dampak",
					"kategori_dampak"=>"Kriteria Dampak",
					"level_risiko_inheren"=>"Lavel Risiko"
				)
			)
		);

		$return2 = array(
			"pengendalian_risiko_saat_ini"=>array(
				"Pengendalian Current Risk"=>array(
					"nama_kontrol"=>"Aktivitas yang sudah ada untuk Pencegahan dan Pemulihan",
					"control_menurunkan"=>"Menurunkan Dampak atau Kemungkinan ?"
				)
			)
		);

		$norowspan_control[] = "nama_kontrol";
		$norowspan_control[] = "control_menurunkan";

		foreach ($this->data['efektifarr'] as $key => $value) {
			$return2['pengendalian_risiko_saat_ini']['Pengendalian Current Risk']['efektif_'.$key] = $value;

			$this->data['type_header']['efektif_'.$key] = array(
				'list'=>array('1'=>'Iya','0'=>'Tidak')
			);

			$norowspan_control[] = 'efektif_'.$key;
		}

		$return2['pengendalian_risiko_saat_ini']['Pengendalian Current Risk']['control_efektif'] = "Control Efektif (Ya&nbsp;/&nbsp;Tidak)";
		$return2['ratarataefektif'] = "Rata-rata Efektifitas";

		$norowspan_control[] = "control_efektif";

		$return3 = array(
			"risiko_paska_kontrol"=>array(
				"Current Risk"=>array(
					"kemungkinan_paskakontrol"=>"Tingkat Kemungkinan",
					"dampak_paskakontrol"=>"Tingkat Dampak",
					"level_risiko_paskakontrol"=>"Level Risiko"
				)
			),
			"nama_mitigasi"=>"Rencana Penanganan Risiko (Mitigasi)",
			"mitigasi_menurunkan"=>"Menurunkan Dampak atau Kemungkinan ?",
			"waktu_pelaksanaan"=>"Due Date Mitigasi (Action&nbsp;Plan)",
			"biaya_mitigasi"=>"Biaya Mitigasi",
			"cba_mitigasi"=>"Cost Benefit Analysis (CBA) atas Rencana Penanganan Risiko",
			"penanggungjawab_mitigasi"=>"Penanggung Jawab Rencana Mitigasi",
			"ratarataprogress"=>"Rata-rata Progress Mitigasi",
			"progress"=>"Progress Mitigasi",
			"capaian_mitigasi"=>"Capaian / Progress Pelaksanaan Rencana Mitigasi",
			"risiko_residual"=>array(
				"Residual Risk yang Ditargetkan"=>array(
					"kemungkinan_rdual"=>"Tingkat Kemungkinan",
					"dampak_rdual"=>"Tingkat Dampak",
					"level_risiko_residual"=>"Level Risiko"
				)
			),
			"risiko_evaluasi"=>array(
				"Residual Risk Hasil Evaluasi"=>array(
					"kemungkinan_paskamitigasi"=>"Tingkat Kemungkinan",
					"dampak_paskamitigasi"=>"Tingkat Dampak",
					"level_risiko_paskamitigasi"=>"Level Risiko"
				)
			),
			"capaian_mitigasi_evaluasi"=>"Progress Capaian Kinerja",
			"hambatan_kendala"=>"Hambatan / Kendala Pelaksanaan Tindakan Mitigasi / Capaian Kinerja / Isu",
			"penyesuaian_mitigasi"=>"Penyesuaian Tindakan Mitigasi (jika diperlukan)",
		);
		$norowspan_mitigasi = array();
		$norowspan_mitigasi[] = "nama_mitigasi";
		$norowspan_mitigasi[] = "mitigasi_menurunkan";
		$norowspan_mitigasi[] = "waktu_pelaksanaan";
		$norowspan_mitigasi[] = "biaya_mitigasi";
		$norowspan_mitigasi[] = "cba_mitigasi";
		$norowspan_mitigasi[] = "penanggungjawab_mitigasi";
		$norowspan_mitigasi[] = "capaian_mitigasi";
		$norowspan_mitigasi[] = "progress";

		$this->data['norowspan_control'] = $norowspan_control;
		$this->data['norowspan_mitigasi'] = $norowspan_mitigasi;
		$this->data['norowspan'] = array_merge($norowspan_control, $norowspan_mitigasi);

		$return = array_merge($return1, $return2, $return3);

		$id_kajian_risiko = $this->data['row']['id_kajian_risiko'];

		if($id_kajian_risiko){
			if(!$this->mtkajianrisiko->isKegiatan($id_kajian_risiko))
				unset($return['sasaran_kegiatan']);
		}
		return $return;
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

		$this->data['header'] = $this->Header();

		$this->View($this->viewindex);
	}

	public function go_print(){
		if(!$this->get['header'])
			$this->get['header'] = array();

		$this->data['no_header'] = true;
		//$bulanarr = ListBulan();
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

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

		$this->data['warnarr'] = array();
		$rowswarna = $this->conn->GetArray("select k.kode k, d.kode d, t.warna from mt_risk_matrix mx 
			join mt_risk_kemungkinan k on mx.id_kemungkinan = k.id_kemungkinan
			join mt_risk_dampak d on mx.id_dampak = d.id_dampak
			join mt_risk_tingkat t on mx.id_tingkat = t.id_tingkat
			");
		foreach ($rowswarna as $r) {
			$this->data['warnarr'][$r['k'].$r['d']] = $r['warna'];
		}

		$header = $this->Header();

		$this->data['header1']  = array();
		$this->data['header2']  = array();

		foreach ($header as $key => $value) {
			if(is_array($value)){
				$label = key($value);
				$header1 = $value[$label];


				$colspan = 0;
				foreach ($header1 as $k => $v) {

					if(!$this->get['header'][$k])
						continue;

					$this->data['header2'][$k]["label"] = $v;
					$this->data['header2'][$k]["rowspan"] = 1;
					$this->data['header2'][$k]["colspan"] = 1;
					$colspan++;
				}

				if($colspan){
					$this->data['header1'][$key]["label"] = $label;
					$this->data['header1'][$key]["rowspan"] = 1;
					$this->data['header1'][$key]["colspan"] = $colspan;
				}
				
				unset($this->get['header'][$key]);
			}else{
				if(!$this->get['header'][$key])
					continue;

				$this->data['header1'][$key]["label"] = $value;
				$this->data['header1'][$key]["rowspan"] = 2;
				$this->data['header1'][$key]["colspan"] = 1;
			}
		}

		$this->data['paramheader'] = array_keys($this->get['header']);
	/*	dpr($this->data['header1']);	
		dpr($this->data['header2']);	
		dpr($this->data['paramheader']);*/	
		$param = $this->get;

		$this->data['rows'] = $this->model->getListKertasKerja($param);
		
		$this->data['mtriskkemungkinan'] = $this->conn->GetArray("select * from mt_risk_kemungkinan order by id_kemungkinan desc");
		$this->data['mtriskdampak'] = $this->conn->GetArray("select * from mt_risk_dampak order by id_dampak asc");
		$this->data['mtriskmatrix'] = $this->conn->GetArray("select mrm.*, mrt.NAMA, mrt.WARNA
			from mt_risk_matrix mrm
			join MT_RISK_TINGKAT mrt on mrt.ID_TINGKAT = mrm.ID_TINGKAT");

		$this->View($this->viewprint);
	}
}
