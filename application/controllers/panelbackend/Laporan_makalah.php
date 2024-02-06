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
			$this->data['page_title'] = 'Laporan Dokumen';
		}else{
			$this->data['page_title'] = 'Laporan Dokumen';
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
		$this->data['isefektifarr'] = array('1'=>'Iya','2'=>'Tidak');
		$this->data['isefektifarr1'] = array('1'=>'Iya','0'=>'Tidak');
		$this->load->model("Mt_risk_kriteria_dampakModel",'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();
		$this->data['kriteriakemungkinanarr'] = array(''=>'','1'=>'Deskripsi Kualitatif','2'=>'Probabilitas','3'=>'Insiden Sebelumnya');
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

		$this->data['header'] = $this->Header();

		$this->View($this->viewindex);
	}

	function Header(){
		$return1 = array(
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
			"risiko_inheren"=>array(
				"Inheren Risk"=>
				array(
					"inheren_kemungkinan"=>"Tingkat Kemungkinan",
					"kategori_kemungkinan"=>"Kriteria Kemungkinan",
					"inheren_dampak"=>"Tingkat Dampak",
					"kategori"=>"Kriteria Dampak",
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

		foreach ($this->data['efektifarr'] as $key => $value) {
			$return2['pengendalian_risiko_saat_ini']['Pengendalian Current Risk']['efektif_'.$key] = $value;
		}

		$return2['pengendalian_risiko_saat_ini']['Pengendalian Current Risk']['control_efektif'] = "Control Efektif (Ya&nbsp;/&nbsp;Tidak)";

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


		$return = array_merge($return1, $return2, $return3);

		$id_kajian_risiko = $this->data['row']['id_kajian_risiko'];

		if($id_kajian_risiko){
			if(!$this->mtkajianrisiko->isKegiatan($id_kajian_risiko))
				unset($return['sasaran_kegiatan']);
		}

		if($id_kajian_risiko<>4){
			unset($return['proses_bisnis']);
		}
		return $return;
	}

	private function loadTemplate($id=null){
		$row = $this->mscorecard->GetByPk($id);
		if($row['template_laporan'])
			return $this->data['configfile']['upload_path']."scorecard_template_laporan".$id.'.'.ext($row['template_laporan']);
		elseif($row['id_parent_scorecard'])
			return $this->loadTemplate($row['id_parent_scorecard']);
		else
			return false;
	}

	public function go_print(){
		// dpr($this->get['header'],1);$
		$this->data['configfile'] = $this->config->item('file_upload_config');
		$this->load->library("word");
		$word = $this->word;

		$tgl_efektif = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){
			$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		}

		$this->data['id_scorecard'] = $id_scorecard = $this->get['id_scorecard'];
		$this->data['id_kajian_risiko'] = $id_kajian_risiko = $this->get['id_kajian_risiko'];
		$this->data['id_scorecard_sub'] = $id_scorecard_sub = $this->get['id_scorecard_sub'];
		$this->data['tahun'] = $tahun = $this->get['tahun'];
		$this->data['bulan'] = $bulan = $this->get['bulan'];
		$this->data['tanggal'] = $tanggal = $this->get['tanggal'];
		$top = $this->get['top'];
		if(!$this->get['header']){
			$this->get['header'] = array(
				"kode_risiko"=>"Kode",
				"sasaran_strategis"=>"Sasaran Strategis",
				"sasaran_kegiatan"=>"Sasaran Kegiatan",
				"kategori_proses"=>"Kategori",
				"kelompok_proses"=>"Kelompok Proses",
				"nama_proses"=>"Nama Proses",
				"aktifitas"=>"Aktivitas",
				"risiko"=>"Risiko",
				"penyebab"=>"Penyebab",
				"dampak"=>"Dampak",
				"risk_owner"=>"Pemilik Risiko",
				"level_risiko_inheren"=>"Lavel Risiko",
				"nama_kontrol"=>"Aktivitas yang sudah ada untuk Pencegahan dan Pemulihan",
				"level_risiko_paskakontrol"=>"Level Risiko",
				"nama_mitigasi"=>"Rencana Penanganan Risiko (Mitigasi)",
				"level_risiko_residual"=>"Level Risiko",
			);
		}

		$template = $this->loadTemplate($id_scorecard);

		if(!file_exists($template))
			die("File template tidak ditemukan");

		$word->template($template);
		$temp = $word->templateProcessor;
		$phpWord = $word->phpword();
		
		$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$section = $phpWord->addSection();

		$scorecardarr = $this->model->GetComboDashboard($id_kajian_risiko, $tgl_efektif);

		$id_scorecardarr = array();
		if($id_scorecard){

			$scorecardsubarr = $this->mscorecard->GetComboChild($id_scorecard,false);

			if($scorecardsubarr[$id_scorecard_sub] && $id_scorecard_sub){
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard_sub);
			}else{
				$id_scorecardarr = $this->mscorecard->GetChild($id_scorecard);
			}
		}

		list($tgl, $bln, $thn) = explode("-",$tgl_efektif);

		if($tahun<>$thn && $tahun){
			$thn = $tahun;
			$bln = '12';
			$tgl = '31';
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
			"tanggal"=>$tgl,
			"order"=>$order
		);
		$this->data['tahun'] = $thn;
		$this->data['bulan'] = $bln;
		$this->data['tanggal'] = $tgl;

		$rows = $this->model->getListRiskProfile($param);
		$is_strategis = false;
		$is_kegiatan = false;
		$is_proses = false;
		foreach($rows as $r){
			if($r['sasaran_strategis'])
				$is_strategis = true;

			if($r['sasaran_kegiatan'])
				$is_kegiatan = true;

			if($r['id_nama_proses'])
				$is_proses = true;
		}

		if(!$is_strategis){
			unset($this->get['header']['sasaran_strategis']);
		}
		if(!$is_kegiatan){
			unset($this->get['header']['sasaran_kegiatan']);
		}
		if(!$is_proses){
			unset($this->get['header']['kategori_proses']);
			unset($this->get['header']['kelompok_proses']);
			unset($this->get['header']['nama_proses']);
			unset($this->get['header']['aktifitas']);
		}else{
			unset($this->get['header']['nama_mitigasi']);
			unset($this->get['header']['level_risiko_residual']);
		}
		
		if($rows){
			$rows1 = $this->conn->GetArray("select a.*, b.nama as nama_progress, b.prosentase, c.nama as nama_penanggung_jawab
				from risk_mitigasi a
				join mt_status_progress b on a.id_status_progress = b.id_status_progress
				join mt_sdm_jabatan c on a.penanggung_jawab = c.id_jabatan
				where id_risiko in (".$this->conn->GetKeysStr($rows, 'id_risiko').")
				order by a.no, a.nama, a.id_mitigasi asc");

			$rowsmitigasi = array();
			foreach($rows1 as $r){
				$rowsmitigasi[$r['id_risiko']][] = $r;
			}
		}

		if($rows){
			$rows1 = $this->conn->GetArray("select a.* from risk_control a
				where id_risiko in (".$this->conn->GetKeysStr($rows, 'id_risiko').")
				order by a.no, a.nama, a.id_control asc");



			$str = get_key_str($rows1, 'id_control',"','");

			$rows2 = $this->conn->GetArray("select *
				from risk_control_efektifitas ce
				where ce.id_control in ('$str')");

			$rows_efektifitas = array();
			foreach ($rows as $r) {
				$rows_efektifitas[$r['id_control']]["efektif_".$r['id_efektifitas']] = $r['is_iya'];
			}

			$rowscontrol = array();
			foreach($rows1 as $r){
				$rowscontrol[$r['id_risiko']][] = $r;
			}
		}

		$warna = array();
	    foreach($this->data['mtriskmatrix'] as $k => $v){
	      $warna[$v['id_kemungkinan'].$v['id_dampak']] = array('bgColor'=>$v['warna']);
	    }
		
	
		$kertaskerjatable = 'Kertas Kerja';
		$tablestyle = array('cellMarginTop' => 30, 'cellMarginBottom' => 30, 'cellMarginLeft' => 50, 'cellMarginRight' => 50);
		$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,'spaceAfter'=>0);
		$cellBCentered = array('spaceAfter'=>0);
		$kertaskerjaheader = array('valign' => 'center', 'borderSize' => 3, 'borderColor' => '000000', 'bgColor'=>'0000ff');
		$kertaskerjabody = array('borderSize' => 3, 'borderColor' => '000000');
		$kertaskerjatextheader = array('bold'=>true,'size'=>10, 'name'=>'Arial');
		$kertaskerjatextbody = array('size'=>10, 'name'=>'Arial');
		$table = $section->addTable($tablestyle);


		$row = $table->addRow();
		$row->addCell(400, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("No",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['kode_risiko'])
			$row->addCell(800, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Kode",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['sasaran_strategis'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Sasaran Strategis",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['sasaran_kegiatan'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Sasaran Kegiatan",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['kategori_proses'])
			$cmerge++;
		if($this->get['header']['kelompok_proses'])
			$cmerge++;
		if($this->get['header']['nama_proses'])
			$cmerge++;
		if($this->get['header']['aktifitas'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Proses Bisnis",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['risiko'])
			$cmerge++;
		if($this->get['header']['penyebab'])
			$cmerge++;
		if($this->get['header']['dampak'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Identifikasi Risiko",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['risk_owner'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Pemilik Risiko",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['inheren_kemungkinan'])
			$cmerge++;
		if($this->get['header']['kategori_kemungkinan'])
			$cmerge++;
		if($this->get['header']['inheren_dampak'])
			$cmerge++;
		if($this->get['header']['kategori_dampak'])
			$cmerge++;
		if($this->get['header']['level_risiko_inheren'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Inheren Risk",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['nama_kontrol'])
			$cmerge++;
		if($this->get['header']['control_menurunkan'])
			$cmerge++;
		if($this->get['header']['control_efektif'])
			$cmerge++;

		foreach($this->data['efektifarr'] as $key=>$v){
			if($this->get['header']['efektif_'.$key])
				$cmerge++;
		}

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Pengendalian Current Risk",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['kemungkinan_paskakontrol'])
			$cmerge++;
		if($this->get['header']['dampak_paskakontrol'])
			$cmerge++;
		if($this->get['header']['level_risiko_paskakontrol'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Current Risk",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['nama_mitigasi'])
			$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Rencana Penangan Risiko (Mitigasi)",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['mitigasi_menurunkan'])
			$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Menurunkan Dampak atau Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['waktu_pelaksanaan'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Due Date Mitigasi (Action Plan)",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['biaya_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Biaya Mitigasi",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['cba_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Cost Benefit Analysis (CBA) atas Rencana Penanganan Risiko",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['penanggungjawab_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Penanggung Jawab Rencana Mitigasi",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['capaian_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Capaian / Progress Pelaksanaan Rencana Mitigasi",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['kemungkinan_rdual'])
			$cmerge++;
		if($this->get['header']['dampak_rdual'])
			$cmerge++;
		if($this->get['header']['level_risiko_residual'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Residual Risk yang Ditargetkan",$kertaskerjatextheader, $cellHCentered);

		$cmerge = 0;
		if($this->get['header']['kemungkinan_paskamitigasi'])
			$cmerge++;
		if($this->get['header']['dampak_paskamitigasi'])
			$cmerge++;
		if($this->get['header']['level_risiko_paskamitigasi'])
			$cmerge++;

		if($cmerge)
			$row->addCell(1600, $kertaskerjaheader+array('gridSpan' => $cmerge))->addText("Residual Risk Hasil Evaluasi",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['capaian_mitigasi_evaluasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Progress Capaian Kinerja",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['hambatan_kendala'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Hambatan / Kendala Pelaksanaan Tindakan Mitigasi / Capaian Kinerja / Isu",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['penyesuaian_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'restart'))->addText("Penyesuaian Tindakan Mitigasi (jika diperlukan)",$kertaskerjatextheader, $cellHCentered);


		$row = $table->addRow();
		$row->addCell(400, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['kode_risiko'])
			$row->addCell(800, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['sasaran_strategis'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['sasaran_kegiatan'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));

		if($this->get['header']['kategori_proses'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Kategori",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['kelompok_proses'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Kelompok Proses",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['nama_proses'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Nama Proses",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['aktifitas'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Aktivitas",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['risiko'])
			$row->addCell(1600, $kertaskerjaheader)->addText("Risiko",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['penyebab'])
			$row->addCell(1600, $kertaskerjaheader)->addText("Penyebab",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['dampak'])
			$row->addCell(1600, $kertaskerjaheader)->addText("Dampak",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['risk_owner'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));

		if($this->get['header']['inheren_kemungkinan'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['kategori_kemungkinan'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Kriteria Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['inheren_dampak'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Dampak",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['kategori_dampak'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Kriteria Dampak",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['level_risiko_inheren'])
			$row->addCell(700, $kertaskerjaheader)->addText("Level Risiko",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['nama_kontrol'])
			$row->addCell(1600, $kertaskerjaheader)->addText("Aktivitas yang sudah ada untuk Pencegahan dan Pemulihan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['control_menurunkan'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Menurunkan Dampak atau Kemungkinan ?",$kertaskerjatextheader, $cellHCentered);
		foreach ($this->data['efektifarr'] as $key => $value) {
			if($this->get['header']['efektif_'.$key])
				$row->addCell(1000, $kertaskerjaheader)->addText($value,$kertaskerjatextheader, $cellHCentered);
		}
		if($this->get['header']['control_efektif'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Control Efektif (Ya/Tidak)",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['kemungkinan_paskakontrol'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['dampak_paskakontrol'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Dampak",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['level_risiko_paskakontrol'])
			$row->addCell(700, $kertaskerjaheader)->addText("Level Risiko",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['nama_mitigasi'])
			$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['mitigasi_menurunkan'])
			$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['waktu_pelaksanaan'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['biaya_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['cba_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['penanggungjawab_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['capaian_mitigasi'])
			$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));

		if($this->get['header']['kemungkinan_rdual'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['dampak_rdual'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Dampak",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['level_risiko_residual'])
			$row->addCell(700, $kertaskerjaheader)->addText("Level Risiko",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['kemungkinan_paskamitigasi'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Kemungkinan",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['dampak_paskamitigasi'])
			$row->addCell(1000, $kertaskerjaheader)->addText("Tingkat Dampak",$kertaskerjatextheader, $cellHCentered);
		if($this->get['header']['level_risiko_paskamitigasi'])
			$row->addCell(700, $kertaskerjaheader)->addText("Level Risiko",$kertaskerjatextheader, $cellHCentered);

		if($this->get['header']['capaian_mitigasi_evaluasi'])
			$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['hambatan_kendala'])
			$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));
		if($this->get['header']['penyesuaian_mitigasi'])
			$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));

		$no=1;
		if(is_array($rows))
			foreach ($rows as $r) {
			if(!$rowscontrol[$r['id_risiko']])
				$rowscontrol[$r['id_risiko']] = array();
			if(!$rowsmitigasi[$r['id_risiko']])
				$rowsmitigasi[$r['id_risiko']] = array();

			$cmitigasi = count($rowsmitigasi[$r['id_risiko']]);
			$cmitigasi1 = $cmitigasi-1;
			$ccontrol = count($rowscontrol[$r['id_risiko']]);
			$ccontrol1 = $ccontrol-1;

			if($cmitigasi>$ccontrol)
				$rloop = $cmitigasi;
			else
				$rloop = $ccontrol;

			if(!$rloop)
				$rloop = 1;

			for($i=0;$i<$rloop;$i++){
				$rc = $rowscontrol[$r['id_risiko']][$i];
				$rm = $rowsmitigasi[$r['id_risiko']][$i];

				$row = $table->addRow();

				if($i>0){
					$row->addCell(400, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['kode_risiko'])
						$row->addCell(800, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['sasaran_strategis'])
						$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['sasaran_kegiatan'])
						$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['kategori_proses'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['kelompok_proses'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['nama_proses'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['aktifitas'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['risiko'])
						$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['penyebab'])
						$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['dampak'])
						$row->addCell(1600, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['risk_owner'])
						$row->addCell(1200, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['inheren_kemungkinan'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['kategori_kemungkinan'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['inheren_dampak'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['kategori_dampak'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['level_risiko_inheren'])
						$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));



					if($ccontrol1>$i){
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody)->addText($rc['no'].'. '.$rc['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($rc['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody)->addText($isefektifarr[$is_efektif],$kertaskerjatextbody,$cellHCentered);
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($this->data['isefektifarr1'][$rc['is_efektif']],$kertaskerjatextbody,$cellBCentered);

					}elseif($ccontrol1==$i){
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rc['no'].'. '.$rc['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rc['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($isefektifarr[$is_efektif],$kertaskerjatextbody,$cellHCentered);
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['isefektifarr1'][$rc['is_efektif']],$kertaskerjatextbody,$cellBCentered);
					}else{
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'continue'));

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
						
					}


					if($this->get['header']['kemungkinan_paskakontrol'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['dampak_paskakontrol'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['level_risiko_paskakontrol'])
						$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));


					if($cmitigasi1>$i){
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody)->addText($rm['no'].'. '.$rm['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($rm['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText(Eng2Ind($rm['dead_line']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText(rupiah($rm['biaya']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['cba'].'%',$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['nama_penanggung_jawab'],$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['prosentase'].'%',$kertaskerjatextbody,$cellBCentered);
					}elseif($cmitigasi1==$i){
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['no'].'. '.$rm['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText(Eng2Ind($rm['dead_line']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText(rupiah($rm['biaya']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['cba'].'%',$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['nama_penanggung_jawab'],$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['prosentase'].'%',$kertaskerjatextbody,$cellBCentered);
					}else{
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'continue'));

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
					}


					if($this->get['header']['kemungkinan_rdual'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['dampak_rdual'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['level_risiko_residual'])
						$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));
					
					if($this->get['header']['kemungkinan_paskamitigasi'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['dampak_paskamitigasi'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['level_risiko_paskamitigasi'])
						$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));

					if($this->get['header']['capaian_mitigasi_evaluasi'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['hambatan_kendala'])
						$row->addCell(1000, $kertaskerjaheader+array('vMerge' => 'continue'));
					if($this->get['header']['penyesuaian_mitigasi'])
						$row->addCell(700, $kertaskerjaheader+array('vMerge' => 'continue'));
				}else{

					$row->addCell(400, $kertaskerjabody+array('vMerge' => 'restart'))->addText($no++,$kertaskerjatextbody,$cellHCentered);

					if($this->get['header']['kode_risiko'])
						$row->addCell(800, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['nomor'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['sasaran_strategis'])
						$row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['sasaran_strategis'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['sasaran_kegiatan'])
						$row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['sasaran_kegiatan'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['kategori_proses'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['kategori_proses'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['kelompok_proses'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['kelompok_proses'],$kertaskerjatextbody,$cellBCentered);
					
					if($this->get['header']['nama_proses'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['nama_proses'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['aktifitas'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['aktifitas'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['risiko'])
						$row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['nama'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['penyebab'])
						$row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addTextNewLine($r['penyebab'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['dampak'])
						$row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addTextNewLine($r['dampak'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['risk_owner'])
						$row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['risk_owner'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['inheren_kemungkinan'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtkemungkinanarr'][$r['inheren_kemungkinan']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['kategori_kemungkinan'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['kriteriakemungkinanarr'][$r['id_kriteria_kemungkinan']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['inheren_dampak'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtdampakrisikoarr'][$r['inheren_dampak']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['kategori_dampak'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['kriteriaarr'][$r['id_kriteria_dampak']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['level_risiko_inheren']){
						$wrn = $warna[$r['inheren_kemungkinan'].$r['inheren_dampak']];
						if(!$wrn)
							$wrn = array();

						$row->addCell(700, $kertaskerjabody+$wrn+array('vMerge' => 'restart'))->addText($r['level_risiko_inheren'],$kertaskerjatextheader,$cellHCentered);
					}

					if($ccontrol1>$i){
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody)->addText($rc['no'].'. '.$rc['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($rc['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody)->addText($isefektifarr[$is_efektif],$kertaskerjatextbody,$cellHCentered);
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($this->data['isefektifarr1'][$rc['is_efektif']],$kertaskerjatextbody,$cellBCentered);

					}elseif($ccontrol1==$i){
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rc['no'].'. '.$rc['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rc['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($isefektifarr[$is_efektif],$kertaskerjatextbody,$cellHCentered);
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['isefektifarr1'][$rc['is_efektif']],$kertaskerjatextbody,$cellBCentered);
					}else{
						if($this->get['header']['nama_kontrol'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'continue'));

						if($this->get['header']['control_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));

						foreach ($this->data['efektifarr'] as $key => $value) {
							if($this->get['header']['efektif_'.$key]){
								$is_efektif = $rows_efektifitas[$rc['id_control']]["efektif_".$key];
								$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
							}
						}

						if($this->get['header']['control_efektif'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
						
					}

					if($this->get['header']['kemungkinan_paskakontrol'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtkemungkinanarr'][$r['control_kemungkinan_penurunan']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['dampak_paskakontrol'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtdampakrisikoarr'][$r['control_dampak_penurunan']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['level_risiko_paskakontrol']){
						$wrn = $warna[$r['control_kemungkinan_penurunan'].$r['control_dampak_penurunan']];
						if(!$wrn)
							$wrn = array();

						$row->addCell(700, $kertaskerjabody+$wrn+array('vMerge' => 'restart'))->addText($r['level_risiko_control'],$kertaskerjatextheader,$cellHCentered);
					}


					if($cmitigasi1>$i){
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody)->addText($rm['no'].'. '.$rm['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody)->addText($rm['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText(Eng2Ind($rm['dead_line']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText(rupiah($rm['biaya']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['cba'].'%',$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['nama_penanggung_jawab'],$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody)->addText($rm['prosentase'].'%',$kertaskerjatextbody,$cellBCentered);
					}elseif($cmitigasi1==$i){
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['no'].'. '.$rm['nama'],$kertaskerjatextbody,$cellBCentered);

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['menurunkan_kemungkinan_dampak'],$kertaskerjatextbody,$cellHCentered);
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText(Eng2Ind($rm['dead_line']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText(rupiah($rm['biaya']),$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['cba'].'%',$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['nama_penanggung_jawab'],$kertaskerjatextbody,$cellBCentered);
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'restart'))->addText($rm['prosentase'].'%',$kertaskerjatextbody,$cellBCentered);
					}else{
						if($this->get['header']['nama_mitigasi'])
							$cell = $row->addCell(1600, $kertaskerjabody+array('vMerge' => 'continue'));

						if($this->get['header']['mitigasi_menurunkan'])
							$cell = $row->addCell(1000, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['waktu_pelaksanaan'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['biaya_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['cba_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['penanggungjawab_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
						
						if($this->get['header']['capaian_mitigasi'])
							$cell = $row->addCell(1200, $kertaskerjabody+array('vMerge' => 'continue'));
					}

					if($this->get['header']['kemungkinan_rdual'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtkemungkinanarr'][$r['residual_target_kemungkinan']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['dampak_rdual'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtdampakrisikoarr'][$r['residual_target_dampak']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['level_risiko_residual']){
						$wrn = $warna[$r['residual_target_kemungkinan'].$r['residual_target_dampak']];
						if(!$wrn)
							$wrn = array();

						$row->addCell(700, $kertaskerjabody+$wrn+array('vMerge' => 'restart'))->addText($r['level_residual_evaluasi'],$kertaskerjatextheader,$cellHCentered);
					}

					if($this->get['header']['kemungkinan_paskamitigasi'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtkemungkinanarr'][$r['residual_kemungkinan_evaluasi']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['dampak_paskamitigasi'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($this->data['mtdampakrisikoarr'][$r['residual_dampak_evaluasi']],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['level_risiko_paskamitigasi']){
						$wrn = $warna[$r['residual_kemungkinan_evaluasi'].$r['residual_dampak_evaluasi']];
						if(!$wrn)
							$wrn = array();

						$row->addCell(700, $kertaskerjabody+$wrn+array('vMerge' => 'restart'))->addText($r['level_residual_evaluasi1'],$kertaskerjatextheader,$cellHCentered);
					}

					if($this->get['header']['capaian_mitigasi_evaluasi'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['progress_capaian_kinerja'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['hambatan_kendala'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['hambatan_kendala'],$kertaskerjatextbody,$cellBCentered);

					if($this->get['header']['penyesuaian_mitigasi'])
						$row->addCell(1000, $kertaskerjabody+array('vMerge' => 'restart'))->addText($r['penyesuaian_tindakan_mitigasi'],$kertaskerjatextbody,$cellBCentered);
				}
			}
		}

		$tabletext = $writer->getWriterPart('document')->getTableAsText($table);
		$temp->setElement('tablekertaskerja', $tabletext);

		$risikotable = 'Risiko';
		$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,'spaceAfter'=>0);
		$cellBCentered = array('spaceAfter'=>0);
		$risikoheader1 = array('borderSize' => 0, 'borderColor' => 'ffffff');
		$risikoheader = array('borderSize' => 6, 'borderColor' => '000000');
		$risikotextheader = array('bold'=>true,'size'=>10, 'name'=>'Arial');
		$risikotextbody = array('size'=>10, 'name'=>'Arial');
		$risikocneter = array('valign' => 'center');
		$table = $section->addTable($tablestyle);
		$no=1;
		if(is_array($rows))
			foreach ($rows as $r) {

			// dpr($r,1);

			$row = $table->addRow();
			$cell = $row->addCell(1200, $risikoheader+$risikocneter)->addText($no++,$risikotextheader+array('size'=>18),$cellHCentered);

			$cell = $row->addCell(10000, $risikoheader+array('gridSpan' => 5));

			if($is_strategis)
				$cell->addText("Sasaran Strategis \t : $r[sasaran_strategis]",$risikotextheader,$cellBCentered);

			if($is_kegiatan)
				$cell->addText("Sasaran Kegiatan \t : $r[sasaran_kegiatan]",$risikotextheader,$cellBCentered);

			if($is_proses){
				$cell->addText("Kelompok Proses \t : $r[kode_kelompok_proses] $r[nama_kelompok_proses]",$risikotextheader,$cellBCentered);
				$cell->addText("Nama Proses \t : $r[kode_nama_proses] $r[nama_nama_proses]",$risikotextheader,$cellBCentered);
				$cell->addText("Aktivitas \t\t : $r[kode_aktifitas] $r[nama_aktifitas]",$risikotextheader,$cellBCentered);
			}

			$cell->addText("Risiko \t \t : $r[nama]",$risikotextheader,$cellBCentered);
			$cell->addText("Pemilik Risiko \t : $r[risk_owner] ",$risikotextheader,$cellBCentered);

			$row = $table->addRow();
			$cell = $row->addCell(5100, $risikoheader+array('gridSpan' => 3));
			$cell->addText("Penyebab : ",$risikotextheader,$cellHCentered);
			$cell->addTextNewLine($r['penyebab'],$risikotextbody,$cellBCentered);

			$cell = $row->addCell(5100, $risikoheader+array('gridSpan' => 3),$cellBCentered);
			$cell->addText("Dampak : ",$risikotextheader,$cellHCentered);
			$cell->addTextNewLine($r['dampak'],$risikotextbody,$cellBCentered);

			$row = $table->addRow();

			$wrn = $warna[$r['inheren_kemungkinan'].$r['inheren_dampak']];
			if(!$wrn)
				$wrn = array();

			$row->addCell(1200, $risikoheader+$wrn+$risikocneter)->addText("$r[level_risiko_inheren]",$risikotextheader,$cellHCentered);

			$row->addCell(9000, $risikoheader+array('gridSpan' => 5)+$risikocneter)->addText("Level Inherent Risk",$risikotextheader,$cellBCentered);

			$row = $table->addRow();
			$cell = $row->addCell(10200, $risikoheader+array('gridSpan' => 6));
			$cell->addText("Aktivitas Yang Sudah Ada Untuk Pencegahan dan Pemulihan :",$risikotextheader,$cellBCentered);

			if(is_array($rowscontrol[$r['id_risiko']]))
				foreach($rowscontrol[$r['id_risiko']] as $r1){
				$cell->addText($r1['no'].'. '.$r1['nama'],$risikotextbody,$cellBCentered);
			}

			$row = $table->addRow();

			$wrn = $warna[$r['control_kemungkinan_penurunan'].$r['control_dampak_penurunan']];
			if(!$wrn)
				$wrn = array();

			$row->addCell(1200, $risikoheader+$wrn+$risikocneter)->addText("$r[level_risiko_control]",$risikotextheader,$cellHCentered);

			$row->addCell(9000, $risikoheader+array('gridSpan' => 5)+$risikocneter)->addText("Level Current Risk",$risikotextheader,$cellBCentered);

			$row = $table->addRow();
			$row->addCell(3400, $risikoheader+array('gridSpan' => 3))->addText("Rencana Penanganan Risiko (Mitigasi) :",$risikotextheader,$cellHCentered);
			$row->addCell(4700, $risikoheader+array('gridSpan' => 2))->addText("Penanggung Jawab Rencana Mitigasi :",$risikotextheader,$cellHCentered);
			$row->addCell(1400, $risikoheader)->addText("Progres :",$risikotextheader,$cellHCentered);

			if(!$is_proses or $is_strategis or $is_kegiatan){

				if(is_array($rowsmitigasi[$r['id_risiko']]))
					foreach($rowsmitigasi[$r['id_risiko']] as $r1){
					$row = $table->addRow();
					$row->addCell(3400, $risikoheader+array('gridSpan' => 3))->addText($r1['nama'],$risikotextbody,$cellBCentered);
					$row->addCell(4700, $risikoheader+array('gridSpan' => 2))->addText($r1['nama_penanggung_jawab'],$risikotextbody,$cellBCentered);
					$row->addCell(1400, $risikoheader)->addText($r1['prosentase']."%",$risikotextbody,$cellBCentered);
				}

				$wrn = $warna[$r['residual_target_kemungkinan'].$r['residual_target_dampak']];
				if(!$wrn)
					$wrn = array();

				$row = $table->addRow();
				$row->addCell(1200, $risikoheader+$wrn+$risikocneter)->addText("$r[level_residual_evaluasi]",$risikotextheader,$cellHCentered);

				$row->addCell(9000, $risikoheader+array('gridSpan' => 5)+$risikocneter)->addText("Level Residual Risk Yang Ditargetkan",$risikotextheader,$cellBCentered);
			}

		$row = $table->addRow(1);
		$row->addCell(700);
		$row->addCell(2600);
		$row->addCell(1700);
		$row->addCell(1700);
		$row->addCell(2000);
		$row->addCell(1400);
		}

		$tabletext = $writer->getWriterPart('document')->getTableAsText($table);
		$temp->setElement('tablerisiko', $tabletext);


		$this->getKesimpulan();
		if($id_scorecard_sub)
			$temp->setValue('risk_profile', $scorecardsubarr[$id_scorecard_sub]);
		else
			$temp->setValue('risk_profile', $scorecardarr[$id_scorecard]);

		$temp->setValue('tahun', $tahun);
		$temp->setValue('kesimpulan', $this->data['kesimpulan']['keterangan']);
		$namafile = time().".jpg";
		system("wkhtmltoimage --crop-w 500 \"".site_url("panelbackend/ajax/matrix?id_kajian_risiko=$id_kajian_risiko&tahun=$tahun&bulan=$bulan&tanggal=$tanggal&id_scorecard=$id_scorecard&id_scorecard_sub=$id_scorecard_sub")."\" $namafile");

		if(file_exists($namafile)){
			$source = file_get_contents($namafile);
			$temp->setImage("matrix",array('src'=>$namafile));
		}
		if($id_scorecard_sub)
			$word->download($scorecardarr[$id_scorecard].' '.$scorecardsubarr[$id_scorecard_sub].' '.$tahun.'.docx');
		else
			$word->download($scorecardarr[$id_scorecard].' '.$tahun.'.docx');

		@unlink($namafile);	
	}

	private function getKesimpulan(){
		if($this->data['id_scorecard_sub'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard = ".$this->conn->escape($this->data['id_scorecard_sub'])." and tahun = ".$this->conn->escape($this->data['tahun']));
		
		if(($this->data['id_scorecard'] && !$this->data['kesimpulan'] && !$this->data['id_scorecard_sub']) or !$this->data['kesimpulan'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard = ".$this->conn->escape($this->data['id_scorecard'])." and tahun = ".$this->conn->escape($this->data['tahun']));

		if(($this->data['id_kajian_risiko'] && !$this->data['kesimpulan'] && !$this->data['id_scorecard_sub'] && !$this->data['id_scorecard']) or !$this->data['kesimpulan'])
			$this->data['kesimpulan'] = $this->conn->GetRow("select * from risk_kesimpulan where id_scorecard is null and id_kajian_risiko = ".$this->conn->escape($this->data['id_kajian_risiko'])." and tahun = ".$this->conn->escape($this->data['tahun']));

		if(!$this->data['kesimpulan'])
			$this->data['kesimpulan'] = array("keterangan"=>$this->config->item("default_condition"), "status"=>"default");
	}
}
