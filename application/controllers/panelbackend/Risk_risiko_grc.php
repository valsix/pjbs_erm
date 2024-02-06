<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_risiko_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_risikolistgrc";
		$this->viewdetail = "panelbackend/risk_risikodetailgrc";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard_grc";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risiko GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risiko GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risiko GRC';
			$this->data['edited'] = false;
		}else{
			$this->data['notab'] = true;
			$this->data['page_title'] = 'Daftar Risiko GRC';
		}

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->load->model("Risk_scorecard_folderModel",'riskscorecardfolder');

		$this->load->model("Mt_risk_kriteria_dampakModel",'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();
		$this->data['kriteriakemungkinanarr'] = array(''=>'','1'=>'Probabilitas','2'=>'Deskripsi Kualitatif','3'=>'Insiden Sebelumnya');

		$this->data['jenisarr'] = array(0=>'Default',1=>'Internal',2=>'Eksternal');

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker','myautocomplete','upload'
		);
		$this->data['configfile'] = $this->config->item('file_upload_config');

		if(
			($this->mode == 'proses' or $_FILES['fileprosesupload'] or $this->post['name']=='fileproses') 
			or 
			($_FILES['fileakhirprojectupload'] or $this->post['name']=='fileakhirproject')){

			$this->load->model("Risk_scorecard_filesModel","modelfile");
			$this->data['configfile']['allowed_types'] = 'pdf';
			$this->data['configfile']['max_size'] = '10000';
			$this->config->set_item('file_upload_config',$this->data['configfile']);
			$this->pk = "id_scorecard";
			$this->data['pk'] = $this->pk;
		}else{
			$this->load->model("Risk_risiko_filesModel","modelfile");
		}

		$this->load->model("Risk_scorecardgrc_filesModel","modelfilegrc");
		
		$this->load->model("Mt_risk_efektifitasModel","mefektif");
		$this->data['efektifarr'] = $this->mefektif->GetCombo();
		unset($this->data['efektifarr']['']);

		$this->data['isefektifarr'] = array('1'=>'Iya','2'=>'Tidak');
		$this->data['isefektifarr1'] = array('1'=>'Iya','0'=>'Tidak');
	}

	protected function Header(){

		if($_SESSION[SESSION_APP][$this->data['rowheader']['id_scorecard']]=='peluang'){
			$this->_setFilter("is_peluang = 1");
			return array(
			array(
				'name'=>'nama',
				'label'=>'Nama Peluang',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'id_status_pengajuan',
				'label'=>'Status Pengajuan',
				'width'=>"70px",
				'type'=>"list",
				'value'=>$this->data['mtstatusarr'],
			),
			array(
				'name'=>'status_risiko',
				'label'=>'Status Peluang',
				'width'=>"70px",
				'type'=>"list",
				'value'=>array(''=>'','0'=>'Close','1'=>'Open','2'=>'Berlanjut'),
			));
		}
		$this->_setFilter("(is_peluang <> 1 or is_peluang is null)");

		$return = array(
			array(
				'name'=>'nomor',
				'label'=>'Kode',
				'width'=>"auto",
				'type'=>"varchar2",
			)
		);

		if($this->data['rowheader']['id_nama_proses']){
			$return = array_merge($return, array(
				array(
					'name'=>'nama_aktifitas',
					'label'=>'Aktivitas',
					'width'=>"auto",
					'type'=>"varchar2",
				)
			));
		}

		$return = array_merge($return, array(
			array(
				'name'=>'nama',
				'label'=>'Nama Risiko',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'inheren',
				'label'=>'Inheren Risk',
				'width'=>"50px",
				'type'=>"list",
				'value'=>$this->data['mttingkatdampakarr'],
			),
			array(
				'name'=>'control',
				'label'=>'Current Risk',
				'width'=>"50px",
				'type'=>"list",
				'value'=>$this->data['mttingkatdampakarr'],
			))
		);

		if(!$this->data['rowheader']['id_nama_proses']){
			$return = array_merge($return,array(
				array(
					'name'=>'risidual',
					'label'=>'Targeted Risidual Risk',
					'width'=>"60px",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),));
		}
		$return = array_merge($return, array(
			array(
				'name'=>'id_status_pengajuan',
				'label'=>'Status Pengajuan',
				'width'=>"70px",
				'type'=>"list",
				'value'=>$this->data['mtstatusarr'],
			),
			array(
				'name'=>'status_risiko',
				'label'=>'Status Risiko',
				'width'=>"70px",
				'type'=>"list",
				'value'=>array(''=>'','0'=>'Close','1'=>'Open','2'=>'Berlanjut'),
			)
		));

			

		return $return;
	}


	protected function HeaderLampiran($id_scorecard){
		$statement="";
		if ($id_scorecard) 
		{
			$statement= " and a.id_scorecard = ".$id_scorecard;
		}
		$dataaaa= $this->riskscorecardfolder->selectbyparam("array", $statement);

		$return= array();
		foreach ($dataaaa as $key => $val) 
		{
			// $return= array("nama".$key=>$val['nama']);
			// array_push($return, $val['nama']);
			$return[$key]['id_dok_pendukung_grc']= $val['id_dok_pendukung_grc'];

			$statementfiles= " and a.id_scorecard = ".$id_scorecard." and a.id_dok_pendukung_grc = ".$val['id_dok_pendukung_grc'];
			$dataupload= $this->modelfilegrc->selectbyparam("array", $statementfiles);

			if ($dataupload) 
			{
				$return[$key]['nama']= $val['nama'];
				foreach ($dataupload as $keys => $values) 
				{
					$return[$key]['dataupload'][$keys]['client_name']= $values['client_name'];
					$return[$key]['dataupload'][$keys]['id_scorecardgrc_files']= $values['id_scorecardgrc_files'];
				}
			}
			else
			{
				$return[$key]['nama']= $val['nama'];
			}
		}
		return $return;
		print_r($return);exit;

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

	private function AddOption(){

		$ada = $this->data['mtaktifitasarrtemp'][$this->post['id_aktifitas']];
		if(!$ada && $this->post['id_aktifitas']){
			$record = array();
			$record['nama'] = $this->post['id_aktifitas'];
			$record['id_nama_proses'] = $this->data['rowheader']['id_nama_proses'];
			$id = $this->conn->GetOne("select id_aktifitas from mt_pb_aktifitas where nama = '{$record['nama']}'");

			if(!$id){
				$sql = $this->conn->InsertSQL("mt_pb_aktifitas", $record);
				$this->conn->Execute($sql);
				
				$id = $this->conn->GetOne("select id_aktifitas from mt_pb_aktifitas where nama = '{$record['nama']}'");
			}

			$this->post['id_aktifitas'] = $_POST['id_aktifitas'] = $id;

			$this->data['mtaktifitasarr'][$id] = $record['nama'];

			unset($this->data['mtaktifitasarr'][$record['nama']]);
		}
	}

	protected function Record($id=null){

		$this->AddOption();
		$record =  array(
			'nama'=>$this->post['nama'],
			'nama_aktifitas'=>$this->post['nama_aktifitas'],
			'kode_aktifitas'=>$this->post['kode_aktifitas'],
			'deskripsi'=>$this->post['deskripsi'],
			'inheren_dampak'=>$this->post['inheren_dampak'],
			'inheren_kemungkinan'=>$this->post['inheren_kemungkinan'],
			'residual_target_dampak'=>$this->post['residual_target_dampak'],
			'residual_target_kemungkinan'=>$this->post['residual_target_kemungkinan'],
		/*	'penyebab'=>$this->post['penyebab'],
			'dampak'=>$this->post['dampak'],*/
			'id_sasaran_kegiatan'=>$this->post['id_sasaran_kegiatan'],
			'id_sasaran_strategis'=>$this->post['id_sasaran_strategis'],
			'control_dampak_penurunan'=>$this->post['control_dampak_penurunan'],
			'control_kemungkinan_penurunan'=>$this->post['control_kemungkinan_penurunan'],
			'mitigasi_dampak_penurunan'=>$this->post['mitigasi_dampak_penurunan'],
			'mitigasi_kemungkinan_penurunan'=>$this->post['mitigasi_kemungkinan_penurunan'],
			'current_risk_dampak'=>$this->post['current_risk_dampak'],
			'current_risk_kemungkinan'=>$this->post['current_risk_kemungkinan'],
			'id_kriteria_dampak'=>$this->post['id_kriteria_dampak'],
			'id_kriteria_kemungkinan'=>$this->post['id_kriteria_kemungkinan'],
			'id_taksonomi_objective'=>$this->post['id_taksonomi_objective'],
			'id_taksonomi_area'=>$this->post['id_taksonomi_area'],
			'id_taksonomi_risiko'=>$this->post['id_taksonomi_risiko'],
		);

		if(!$record['id_sasaran_strategis'])
			$record['id_sasaran_strategis'] = $this->conn->GetOne("select id_sasaran_strategis from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($record['id_sasaran_kegiatan']));

		if(!$id)
			$record['id_status_pengajuan'] = 1;

		if($this->access_role['view_all_direktorat'] && $id){
			$record['nomor']=$this->post['nomor'];
		}
		if($this->access_role['view_all_direktorat']){
			$record['tgl_risiko']=$this->post['tgl_risiko'];
		}

		if($this->data['rowheader']['id_nama_proses'])
			$record['id_aktifitas'] = $this->post['id_aktifitas'];

		if($this->access_role['rekomendasi']){
			$record['rekomendasi_keterangan'] = $this->post['rekomendasi_keterangan'];

			if($this->data['row']['is_lock']==1)
				$record['is_lock'] = 2;
			
			$record['rekomendasi_is_verified'] = 2;
			$record['rekomendasi_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['rekomendasi_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['rekomendasi_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['rekomendasi_date'] = "{{sysdate}}";
		}

		if($this->access_role['review']){
			$record['review_kepatuhan']=$this->post['review_kepatuhan'];

			if($this->data['row']['is_lock']==1)
				$record['is_lock'] = 2;
			
			$record['review_is_verified'] = 2;
			$record['review_nid'] = $_SESSION[SESSION_APP]['nid'];
			$record['review_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
			$record['review_group'] = $_SESSION[SESSION_APP]['nama_group'];
			$record['review_date'] = "{{sysdate}}";
		}

		return $record;
	}

	protected function Rules(){
		$return = array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"id_kpi[]"=>array(
				'field'=>'id_kpi[]',
				'label'=>'KPI',
				'rules'=>"required",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi',
				'label'=>'Deskripsi',
				'rules'=>"max_length[4000]",
			),
			"inheren_dampak"=>array(
				'field'=>'inheren_dampak',
				'label'=>'Tingkat Dampak Inheren',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]|required",
			),
			"inheren_kemungkinan"=>array(
				'field'=>'inheren_kemungkinan',
				'label'=>'Tingkat Kemungkinan Inheren',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]|required",
			),
			"control_dampak_penurunan"=>array(
				'field'=>'control_dampak_penurunan',
				'label'=>'Dampak',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]",
			),
			"control_kemungkinan_penurunan"=>array(
				'field'=>'control_kemungkinan_penurunan',
				'label'=>'Tingkat Kemungkinan Inheren',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]",
			),
			"mitigasi_dampak_penurunan"=>array(
				'field'=>'mitigasi_dampak_penurunan',
				'label'=>'Tingkat Dampak Inheren',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]",
			),
			"mitigasi_kemungkinan_penurunan"=>array(
				'field'=>'mitigasi_kemungkinan_penurunan',
				'label'=>'Tingkat',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]",
			),
			"current_risk_dampak"=>array(
				'field'=>'current_risk_dampak',
				'label'=>'Dampak',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]",
			),
			"current_risk_kemungkinan"=>array(
				'field'=>'current_risk_kemungkinan',
				'label'=>'Kemungkinan',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]",
			),
			"residual_target_dampak"=>array(
				'field'=>'residual_target_dampak',
				'label'=>'Tingkat Dampak Residual',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]",
			),
			"residual_target_kemungkinan"=>array(
				'field'=>'residual_target_kemungkinan',
				'label'=>'Tingkat Kemungkinan Residual',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]",
			),
			"id_sasaran_strategis"=>array(
				'field'=>'id_sasaran_strategis',
				'label'=>'Sasaran Strategis',
				'rules'=>"in_list[".implode(",", array_keys($this->data['sasaranarr']))."]|required",
			),
			"id_sasaran_kegiatan"=>array(
				'field'=>'id_sasaran_kegiatan',
				'label'=>'Kegiatan',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkegiatanarr']))."]|required",
			),
			"id_kriteria_dampak"=>array(
				'field'=>'id_kriteria_dampak',
				'label'=>'Kriteria Dampak',
				'rules'=>"in_list[".implode(",", array_keys($this->data['kriteriaarr']))."]|required",
			),
			"id_kriteria_kemungkinan"=>array(
				'field'=>'id_kriteria_kemungkinan',
				'label'=>'Kriteria Kemungkinan',
				'rules'=>"in_list[".implode(",", array_keys($this->data['kriteriakemungkinanarr']))."]|required",
			),
			/*"id_taksonomi_objective"=>array(
				'field'=>'id_taksonomi_objective',
				'label'=>'Taksonomi',
				'rules'=>"in_list[".implode(",", array_keys($this->data['objectivearr']))."]",
			),
			"id_taksonomi_area"=>array(
				'field'=>'id_taksonomi_area',
				'label'=>'Area',
				'rules'=>"in_list[".implode(",", array_keys($this->data['areaarr']))."]",
			),
			"id_taksonomi_risiko"=>array(
				'field'=>'id_taksonomi_risiko',
				'label'=>'Risiko',
				'rules'=>"in_list[".implode(",", array_keys($this->data['taksonomirr']))."]",
			),*/
			"penyebab"=>array(
				'field'=>'penyebab[]',
				'label'=>'Penyebab',
				'rules'=>"required",
			),
			"dampak"=>array(
				'field'=>'dampak[]',
				'label'=>($this->data['is_peluang']?'Keuntungan':'Dampak'),
				'rules'=>"required",
			),
		);

		if($this->data['rowheader']['jenis_sasaran']!='2'){
			unset($return['id_sasaran_kegiatan']);
		}

		if($this->access_role['view_all_direktorat']){
			$return['nomor'] = array(
				'field'=>'nomor',
				'label'=>'Nomor',
				'rules'=>"required",
			);
			$return['tgl_risiko'] = array(
				'field'=>'tgl_risiko',
				'label'=>'Tgl. Risiko',
				'rules'=>"required",
			);
		}

		if($this->access_role['rekomendasi']){
			// $return['rekomendasi_keterangan'] = array(
			// 	'field'=>'rekomendasi_keterangan',
			// 	'label'=>'Dasar Penetapan Risiko',
			// 	'rules'=>"required",
			// );

		}

		if($this->access_role['review']){
			// $return['review_kepatuhan'] = array(
			// 	'field'=>'review_kepatuhan',
			// 	'label'=>'Review Kepatuhan',
			// 	'rules'=>"required",
			// );

		}

		if($this->data['rowheader']['id_nama_proses']){
			unset($return['id_sasaran_kegiatan']);
			unset($return['id_kpi[]']);
			unset($return['id_sasaran_strategis']);
			
			$return['kode_aktifitas'] = array(
				'field'=>'kode_aktifitas',
				'label'=>'Kode Aktivitas',
				'rules'=>"required",
			);
			
			$return['nama_aktifitas'] = array(
				'field'=>'nama_aktifitas',
				'label'=>'Nama Aktivitas',
				'rules'=>"required",
			);

			/*$return['id_aktifitas'] = array(
				'field'=>'id_aktifitas', 
				'label'=>'id_aktifitas', 
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtaktifitasarr']))."]",
			);*/
		}

		if($this->post['act']=='save_rekomendasi'){
			unset($return['id_kriteria_dampak']);
			unset($return['id_kriteria_kemungkinan']);
			unset($return['inheren_dampak']);
			unset($return['inheren_kemungkinan']);
		}

		if($this->data['is_peluang']){
			unset($return['id_kriteria_dampak']);
			unset($return['id_kriteria_kemungkinan']);
			unset($return['inheren_dampak']);
			unset($return['inheren_kemungkinan']);
			unset($return['rekomendasi_keterangan']);
			unset($return['review_kepatuhan']);
			unset($return['penyebab']);
		}

		return $return;
	}

	public function inlistjabatan($str)
	{
		$result = $this->mjabatan->GetCombo($str);

		if(!$result[$str]){
	      $this->form_validation->set_message('inlistjabatan', 'Bidang tidak ditemukan');
	      return FALSE;
		}

		return true;
	}

	public function Listdata($page=0){

		$this->viewlist = "panelbackend/risk_risikolist";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
		
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/list"),
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

	public function Index($id_scorecard=null, $page=0){

		
        if(!$_SESSION[SESSION_APP][$id_scorecard])
            $_SESSION[SESSION_APP][$id_scorecard] = 'risiko';

        if($this->post['act']=='set_risiko'){
            $_SESSION[SESSION_APP][$id_scorecard]='risiko';
            redirect(current_url());
        }
        else if($this->post['act']=='set_peluang'){
            $_SESSION[SESSION_APP][$id_scorecard]='peluang';
            redirect(current_url());
        }
        else if($this->post['act']=='set_lampiran'){
            $_SESSION[SESSION_APP][$id_scorecard]='lampiran';
            redirect(current_url());
        }

		$this->_beforeDetail($id_scorecard);

		if($this->post['act']=='close_all'){
			$this->_closeAll($id_scorecard);
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'])
			$this->data['risiko_sendiri'] = $_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'];

		if($this->post['act']=="filter_sendiri"){
			$_SESSION[SESSION_APP][$this->page_ctrl]['risiko_sendiri'] = $this->post['risiko_sendiri'];
			redirect(current_url());
		}
		
		$this->data['ischild'] = array(1,2);

		if($this->data['risiko_sendiri'])
			$this->_setFilter("id_scorecard = ".$this->conn->qstr($id_scorecard));
		else{
			$this->data['ischild'] = $ret = $this->riskscorecard->GetChild($id_scorecard);
			if(($ret))
				$this->_setFilter("id_scorecard in (".implode(",", $ret).")");
			else
				$this->_setFilter("id_scorecard = ".$this->conn->qstr($id_scorecard));
		}

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		if($_SESSION[SESSION_APP]['tgl_efektif']){

			$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

			$this->_setFilter(" '$tgl_efektif' between nvl(tgl_risiko, '$tgl_efektif')and nvl(tgl_close-1,'$tgl_efektif') ");

		}
		$this->data['headerlampiran']=$this->HeaderLampiran($id_scorecard);

		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getList($page);

		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_scorecard"),
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

		if($this->data['rowheader']['id_proyek']){
			$this->data['row'] = $this->data['rowheader'];

			$this->data['row']['fileakhirproject'] = $this->conn->GetRow("select 
				a.id_scorecard_files as id,
				client_name as name
				from risk_scorecard_files a
				where jenis = 'fileakhirproject' and a.id_scorecard = ".$this->conn->escape($id_scorecard));

			$this->data['is_open_risiko'] = $this->conn->GetOne("select 1 from risk_risiko where status_risiko <> '0' and id_scorecard = ".$this->conn->escape($id_scorecard));
		}

		$this->View($this->viewlist);
	}

	private function _closeAll($id_scorecard=null){

		$ret = true;

		$this->conn->StartTrans();

		$rowsmitigasi = $this->conn->GetArray("select id_mitigasi from risk_mitigasi m where exists (select 1 from risk_risiko r where m.id_risiko = r.id_risiko and r.id_scorecard = ".$this->conn->escape($id_scorecard).")");

		foreach($rowsmitigasi as $r){
			if(!$ret)
				break;

			$ret = $this->conn->goUpdate("risk_mitigasi",array("id_status_progress"=>4),"id_mitigasi = ".$this->conn->escape($r['id_mitigasi']));
		}
		
		$rowscontrol = $this->conn->GetArray("select id_control from risk_control m where exists (select 1 from risk_risiko r where m.id_risiko = r.id_risiko and r.id_scorecard = ".$this->conn->escape($id_scorecard).")");

		foreach($rowscontrol as $r){
			if(!$ret)
				break;

			$ret = $this->conn->goUpdate("risk_control",array("is_efektif"=>1),"id_control = ".$this->conn->escape($r['id_control']));

			$rowsefektif = $this->conn->GetList("select id_efektifitas val from mt_risk_efektifitas");

			foreach($rowsefektif as $k=>$v){
				if(!$ret)
					break;

				$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas where id_control = ".$this->conn->escape($r['id_control'])." and id_efektifitas = ".$this->conn->escape($v));

				if($cek){
					$ret = $this->conn->goUpdate("risk_control_efektifitas",array("is_iya"=>1),"id_control = ".$this->conn->escape($r['id_control'])." and id_efektifitas = ".$this->conn->escape($v));
				}else{
					$ret = $this->conn->goInsert("risk_control_efektifitas",array("is_iya"=>1, "id_control"=>$r['id_control'],"id_efektifitas"=>$v));
				}
			}
		}

		if($ret)
			$ret = $this->conn->goUpdate("risk_risiko",array("status_risiko"=>'0'),"id_scorecard=".$this->conn->escape($id_scorecard));

		if($ret)
			$this->conn->trans_commit();
		else
			$this->conn->trans_rollback();

		redirect(current_url());

		exit();
	}

	public function Add($id_scorecard = null, $id_sasaran_strategis=null, $id_sasaran_kegiatan=null, $id_kpi=null){

		if(!$this->post['id_sasaran_strategis'] && $id_sasaran_strategis)
			$this->post['id_sasaran_strategis'] = $id_sasaran_strategis;

		if(!$this->post['id_sasaran_kegiatan'] && $id_sasaran_kegiatan)
			$this->post['id_sasaran_kegiatan'] = $id_sasaran_kegiatan;

		if($id_kpi){
			$id_kpi = explode("-", $id_kpi);
			foreach ($id_kpi as $rkpi) {
				$this->post['id_kpi'][$rkpi] = $rkpi;
			}
		}

		if(!$this->post['id_sasaran_strategis'])
			redirect("panelbackend/risk_sasaran_kegiatan_grc/index/$id_scorecard");

		$this->Edit($id_scorecard);
	}

	public function Edit($id_scorecard=null, $id=null){
		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard, $id);
		$this->data['row'] = $this->data['rowheader1'] = $this->model->GetByPk($id);
		
		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");
		$kode_aktifitas = $this->data['row']['id_aktifitas'];
		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
			if(!$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']])
				$this->data['mtaktifitasarr'][$this->data['row']['id_aktifitas']] = $this->data['row']['id_aktifitas'];
		}

		if($this->post['id_scorecard'] && $this->data['scorecardarr'][$this->post['id_scorecard']]){
			$id_scorecard = $this->post['id_scorecard'];
			$record['id_jabatan'] = $_SESSION[SESSION_APP]['id_jabatan'];
		}

		if(!$id or $kode_aktifitas<>$this->data['row']['kode_aktifitas']){
			$this->data['row']['nomor'] = $this->data['no_risiko'] = $this->model->getNomorRisiko($id_scorecard, $this->data['row']['id_sasaran_strategis'], $this->data['row']['tgl_risiko'], $this->data['row']['kode_aktifitas']);
		}

		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		if($this->data['is_peluang']){
			$record['is_peluang'] = 1;
		}

		## EDIT HERE ##
		if ($this->post['act'] === 'save' or $this->post['act']=='save_rekomendasi' or $this->post['act']=='save_review') {

			if($this->data['row']['is_lock']=='2' && $this->data['row']['review_is_verified']<>'2' && $this->data['row']['rekomendasi_is_verified']<>'2')
				$record['is_lock'] = 1;

			$record['id_scorecard'] = $id_scorecard;
			
			if(!$id)
				$record['nomor'] = $record['nomor_asli'] = $this->data['no_risiko'];

			if(!$record['tgl_risiko'] && !$id)
				$record['tgl_risiko'] = date('d-m-Y');


			if($this->access_role['view_all_direktorat'] && $id){

				list($tgl,$bulan,$tahun) = explode("-",$record['tgl_risiko']);
				list($tgl,$bulan,$tahun1) = explode("-",$this->data['rowheader1']['tgl_risiko']);

				$id_risiko_sebelum = $this->data['rowheader1']['id_risiko_sebelum'];

				if($tahun<>$tahun1 && $id_risiko_sebelum){
					$nomor_asli = $this->conn->GetOne("select nomor_asli 
						from risk_risiko
						where to_char(tgl_risiko, 'YYYY') = ".$this->conn->escape($tahun)."
						and id_risiko = ".$this->conn->escape($id_risiko_sebelum));

					if($nomor_asli){
						$record['nomor_asli'] = $nomor_asli;
					}
				}
			}

			$this->_isValid($record,true);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $record['penyebab'] = "{{null}}";

            if(!$this->data['is_peluang'])
            	$record['dampak'] = "{{null}}";
            else
            	$record['dampak'] = $this->post['dampak'];

            $this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]) {

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

				$is_insert = true;

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


			if ($return['success']) {
				$this->conn->trans_commit();
				$this->backtodraft($id);

				$this->_afterEditSucceed($id);

				if($this->post['act']=='save_rekomendasi' && $this->access_role['rekomendasi']){
					$this->_notifRekomReview($id_scorecard,$id);
					redirect("$this->page_ctrl/edit/$id_scorecard/$id");
				}elseif($this->post['act']=='save_review' && $this->access_role['review']){
					$this->_notifRekomReview($id_scorecard,$id);
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id_scorecard/$id");
				}elseif($is_insert){
					if($this->data['is_peluang']){
						$this->ctrl = 'risk_mitigasi';
						SetFlash('suc_msg', $return['success']);
						redirect("panelbackend/risk_mitigasi/add/$id");
					}else{

						$this->ctrl = 'risk_control';
						SetFlash('suc_msg', $return['success']);
						redirect("panelbackend/risk_control/add/$id");
					}
				}
				else{
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id_scorecard/$id");
				}

			} else {
				$this->conn->trans_rollback();
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= " Data gagal disimpan.";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	private function _notifRekomReview($id_scorecard=null,$id=null){
		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Review dan rekomendasi dari ".$_SESSION[SESSION_APP]['nama_group'],
			'id_risiko'=>$id,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_risiko/edit/$id_scorecard/$id"
		);
		return $this->InsertTask($record);
	}

	private function _kirimRekom($id_scorecard=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'3'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko sudah ditindaklanjuti",
			'id_risiko'=>$id,
			'untuk'=>$this->data['row']['rekomendasi_jabatan'],
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _kirimReview($id_scorecard=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'3'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko sudah ditindaklanjuti",
			'id_risiko'=>$id,
			'untuk'=>$this->data['row']['review_jabatan'],
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _verifiedRekom($id_scorecard=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'1'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_risiko'=>$id,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		$this->conn->Execute("update risk_risiko set id_status_pengajuan = 7 where id_risiko = ".$this->conn->escape($id));
		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko membutuhkan validasi",
			'id_status_pengajuan'=>7,
			'id_risiko'=>$id,
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _verifiedReview($id_scorecard=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'1'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_risiko'=>$id,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		$this->conn->Execute("update risk_risiko set id_status_pengajuan = 7 where id_risiko = ".$this->conn->escape($id));
		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko membutuhkan validasi",
			'id_status_pengajuan'=>7,
			'id_risiko'=>$id,
			'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	protected function _onDetail($id, &$record=array()){

		$this->data['is_peluang'] = false;
		$this->data['label_risk'] = "Risiko";

		if($this->data['rowheader1']['id_risiko']){
			if($this->data['rowheader1']['is_peluang']){
				$this->data['is_peluang'] = true;
				$this->data['label_risk'] = "Peluang";
			}
		}
		elseif($_SESSION[SESSION_APP][$this->data['rowheader']['id_scorecard']]=='peluang'){
			$this->data['is_peluang'] = true;
			$this->data['label_risk'] = "Peluang";
		}
		elseif($_SESSION[SESSION_APP][$this->data['rowheader']['id_scorecard']]=='lampiran'){
			// $this->data['is_peluang'] = true;
			$this->data['label_risk'] = "Lampiran";
		}

		if(!$this->data['is_peluang']){

			if(!$this->data['row']['id_taksonomi_area'])
				$this->data['row']['id_taksonomi_area'] = $this->conn->GetOne("select id_taksonomi_area from mt_taksonomi_risiko where id_taksonomi_risiko = ".$this->conn->escape($this->data['row']['id_taksonomi_risiko']));

			if(!$this->data['row']['id_taksonomi_objective'])
				$this->data['row']['id_taksonomi_objective'] = $this->conn->GetOne("select id_taksonomi_objective from mt_taksonomi_area where id_taksonomi_area = ".$this->conn->escape($this->data['row']['id_taksonomi_area']));

			$this->load->model("Mt_taksonomi_objectiveModel",'objective');
			$this->data['objectivearr'] = $this->objective->GetCombo();

			$this->data['areaarr'] = array();
			if($this->data['row']['id_taksonomi_objective'])
				$this->data['areaarr'] = array(""=>"")+$this->conn->GetList("select id_taksonomi_area as key, nama as val from mt_taksonomi_area where id_taksonomi_objective = ".$this->data['row']['id_taksonomi_objective']);

			$this->data['taksonomirr'] = array();
			if($this->data['row']['id_taksonomi_area']){
				$rows = $this->conn->GetArray("select id_taksonomi_risiko as id, nama as text from mt_taksonomi_risiko where id_taksonomi_area = ".$this->data['row']['id_taksonomi_area']);

				$this->data['taksonomirr'] = array();
				foreach($rows as $r){
					$this->data['taksonomirr'][$r['id']] = $r;
				}
			}

			if($this->post['act']=="set_value"){
				if($this->data['rowheader1']['id_taksonomi_risiko']<>$this->data['row']['id_taksonomi_risiko'])
					$this->data['row']['nama'] = $this->data['taksonomirr'][$this->data['row']['id_taksonomi_risiko']]['text'];
			}

			$rows = $this->conn->GetArray("select id_taksonomi_penyebab as id, nama as text, jenis 
				from mt_taksonomi_penyebab 
				where id_taksonomi_risiko = ".$this->conn->escape($this->data['row']['id_taksonomi_risiko']));

			$this->data['penyebabarr'] = array();
			foreach($rows as $r){
				$this->data['penyebabarr'][(int)$r['jenis']][$r['id']] = $r;
			}

			$rows = $this->conn->GetArray("select id_taksonomi_dampak as id, nama as text from mt_taksonomi_dampak where id_taksonomi_risiko = ".$this->conn->escape($this->data['row']['id_taksonomi_risiko']));

			$this->data['dampakarr'] = array();
			foreach($rows as $r){
				$this->data['dampakarr'][$r['id']] = $r;
			}
		}
	}


	public function Detail($id_scorecard=null,$id=null){
		$this->_beforeDetail($id_scorecard,$id);

		$this->data['rowheader1'] = $this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		if($this->post['act']=='kirim_rekomendasi'){
			$this->_kirimRekom($id_scorecard,$id);
		}

		if($this->post['act']=='kirim_review'){
			$this->_kirimReview($id_scorecard,$id);
		}

		if($this->post['act']=='save_rekomendasi_verified'){
			$this->_verifiedRekom($id_scorecard,$id);
		}

		if($this->post['act']=='save_review_verified'){
			$this->_verifiedReview($id_scorecard,$id);
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_scorecard=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_scorecard,$id);

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
			redirect("$this->page_ctrl/index/$id_scorecard");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id");
		}

	}

	protected function _beforeDetail($id=null, $id_risiko=null){
		
		if(!$id)
			redirect('panelbackend/risk_scorecard/daftarscorecard');
		#mengambil dari model karena sudah difilter sesuai akses
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));

			$this->load->model("Risk_sasaran_kegiatanModel",'kegiatan');

			if($this->post['id_sasaran_strategis'])
				$id_sasaran_strategis = $this->post['id_sasaran_strategis'];
			elseif($id_risiko)
				$id_sasaran_strategis = $this->conn->GetOne("select id_sasaran_strategis from risk_risiko where id_risiko = ".$this->conn->escape($id_risiko));

			$this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_sasaran_strategis);
			
			$this->load->model("Risk_sasaran_strategisModel","msasaran");

			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);
			
			$this->data['sasaranarr'][$id_sasaran_strategis] = $this->msasaran->GetNama($id_sasaran_strategis);
		}

		$this->data['add_param'] .= $id;

		/*if($this->data['rowheader']['id_nama_proses']){

			$this->load->model("Mt_pb_aktifitasModel","mtaktifitas");
			$this->data['mtaktifitasarrtemp'] = $this->data['mtaktifitasarr'] = $this->mtaktifitas->GetCombo($this->data['rowheader']['id_nama_proses']);
			$this->viewdetail = "panelbackend/risk_risikodetailprobis";
		}*/

		$this->data['scorecardarr'] = $this->riskscorecard->GetComboChild($id);


		if($_SESSION[SESSION_APP][$this->data['rowheader']['id_scorecard']]=='peluang'){
			$this->data['page_title'] = str_replace("Risiko", "Peluang", $this->data['page_title']);
		}
		if($_SESSION[SESSION_APP][$this->data['rowheader']['id_scorecard']]=='lampiran'){
			$this->data['page_title'] = str_replace("Risiko", "Lampiran", $this->data['page_title']);
		}
	}

	protected function _beforeEdit(&$record=array(), $id){
		$this->_validAccessTask('panelbackend/risk_risiko',$this->data['row'],$this->data['edited']);

		$this->isLock();

		if(!$record['id_taksonomi_risiko'] && !$this->data['is_peluang']){
			$record1 = array();
			$maxkode = $this->conn->GetOne("select max(kode) from mt_taksonomi_risiko where id_taksonomi_area = ".$this->conn->escape($record['id_taksonomi_risiko']));

			$arr = explode(".", $maxkode);
			$arr[count($arr)-1] = ((int)$arr[count($arr)-1])+1;
			$kode = implode(".", $arr);

			$record1['kode'] = $kode;
			$record1['nama'] = $record['nama'];
			$record1['id_taksonomi_area'] = $record['id_taksonomi_area'];
			$this->conn->goInsert("mt_taksonomi_risiko", $record1);

			if($record1['id_taksonomi_area'])
				$sql = "select max(id_taksonomi_risiko) from mt_taksonomi_risiko where id_taksonomi_area = ".$this->conn->escape($record1['id_taksonomi_area']);
			else
				$sql = "select max(id_taksonomi_risiko) from mt_taksonomi_risiko where id_taksonomi_area is null";

			$this->post['id_taksonomi_risiko'] = $record['id_taksonomi_risiko'] = $this->conn->GetOne($sql);

			$this->data['rowheader1']['id_taksonomi_risiko'] = $record['id_taksonomi_risiko'];
		}

		return true;
	}

	protected function _beforeDelete($id){
		$this->_validAccessTask('panelbackend/risk_risiko',$this->data['row'],$this->data['edited']);

		$this->isLock();



		if($this->access_role['delete']){

			$this->conn->Execute("delete from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($id));
			$this->conn->Execute("delete from risk_risiko_dampak where id_risiko = ".$this->conn->escape($id));


			$this->model->Execute("delete from risk_risiko_kpi where id_risiko = ".$this->conn->escape($id));

			$this->conn->Execute("delete from risk_task where id_risiko = ".$this->conn->escape($id));

			$this->conn->Execute("delete from risk_review where id_risiko = ".$this->conn->escape($id));

			$rows = $this->conn->GetRows("select id_mitigasi from risk_mitigasi where id_risiko = ".$this->conn->escape($id));
			foreach ($rows as $r) {
				$this->conn->Execute("delete from risk_task where id_mitigasi = ".$this->conn->escape($r['id_mitigasi']));		
				$this->conn->Execute("delete from risk_control where ID_MITIGASI_SUMBER = ".$this->conn->escape($r['id_mitigasi']));
				$this->conn->Execute("delete from risk_mitigasi_files where id_mitigasi = ".$this->conn->escape($r['id_mitigasi']));
			}

			$this->conn->Execute("delete from risk_mitigasi where id_risiko = ".$this->conn->escape($id));

			$rows = $this->conn->GetRows("select id_control from risk_control where id_risiko = ".$this->conn->escape($id));

			foreach ($rows as $r) {
				$this->conn->Execute("delete from risk_control_efektifitas_files where id_control = ".$this->conn->escape($r['id_control']));
				$this->conn->Execute("delete from risk_control_efektifitas where id_control = ".$this->conn->escape($r['id_control']));
			}

			$this->conn->Execute("delete from risk_control where id_risiko = ".$this->conn->escape($id));
			$this->conn->Execute("delete from risk_log where id_risiko = ".$this->conn->escape($id));
		}

		return true;
	}

	protected function _beforeUpdate($record=array(), $id=null){

		$row = $this->model->GetByPk($id);

		$this->riskchangelog($record, $row);

		return true;
	}

	protected function _beforeInsert($record=array()){
		$this->riskchangelog($record);


		return true;
	}

	protected function _afterDetail($id){
		$id_sasaran_strategis = $this->data['row']['id_sasaran_strategis'];
		$id_sasaran_kegiatan = $this->data['row']['id_sasaran_kegiatan'];

		$this->data['row']['kpi_strategis'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_strategis_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
			where id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis));

		$this->data['row']['kpi_kegiatan'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_kegiatan_kpi s 
			join risk_kpi k on s.id_kpi = k.id_kpi 
			where id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan));

		$this->isLock();

		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['editedheader1']);

		if(!$this->data['is_peluang']){
			if(!$this->data['row']['file']['id'] && $id){
				$rows = $this->conn->GetArray("select id_risiko_files as id, client_name as name
					from risk_risiko_files
					where jenis = 'file' and id_risiko = ".$this->conn->escape($id));

				foreach($rows as $r){
					$this->data['row']['file']['id'][] = $r['id'];
					$this->data['row']['file']['name'][] = $r['name'];
				}
			}

			if(!$this->data['row']['filerekomendasi']['id'] && $id){
				$rows = $this->conn->GetArray("select id_risiko_files as id, client_name as name
					from risk_risiko_files
					where jenis = 'filerekomendasi' and id_risiko = ".$this->conn->escape($id));

				foreach($rows as $r){
					$this->data['row']['filerekomendasi']['id'][] = $r['id'];
					$this->data['row']['filerekomendasi']['name'][] = $r['name'];
				}
			}

			if(!$this->post){
				if(!is_array($this->data['rowheader1']['penyebab']) && $this->data['rowheader1']['penyebab']){
					$temp = array(array('nama'=>$this->data['rowheader1']['penyebab']));
					$this->data['rowheader1']['penyebab'] = array();
					$this->data['rowheader1']['penyebab'][0] = $temp;
				}else
					$this->data['rowheader1']['penyebab'] = array();

				$rows = $this->conn->GetArray("select * from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($id));

				foreach($rows as $r){
					$this->data['rowheader1']['penyebab'][(int)$r['jenis']][] = $r;
				}

			}

			if(!$this->post){
				if(!is_array($this->data['rowheader1']['dampak']) && $this->data['rowheader1']['dampak']){
					$temp = array(array('nama'=>$this->data['rowheader1']['dampak']));
					$this->data['rowheader1']['dampak'] = array();
					$this->data['rowheader1']['dampak'] = $temp;
				}else
					$this->data['rowheader1']['dampak'] = array();

				$rows = $this->conn->GetArray("select * from risk_risiko_dampak where id_risiko = ".$this->conn->escape($id));

				foreach($rows as $r){
					$this->data['rowheader1']['dampak'][] = $r;
				}
			}

			if($this->data['rowheader1']['id_taksonomi_risiko']){
				$r = $this->conn->GetRow("select b.id_taksonomi_objective, a.id_taksonomi_area, b.nama as nama_area, c.nama as nama_objective
					from mt_taksonomi_risiko a
					join mt_taksonomi_area b on a.id_taksonomi_area = b.id_taksonomi_area
					join mt_taksonomi_objective c on b.id_taksonomi_objective = c.id_taksonomi_objective
					where a.id_taksonomi_risiko = ".$this->conn->escape($this->data['rowheader1']['id_taksonomi_risiko']));

				$this->data['objectivearr'][$r['id_taksonomi_objective']] = $r['nama_objective'];
				$this->data['areaarr'][$r['id_taksonomi_area']] = $r['nama_area'];

				$this->data['rowheader1']['id_taksonomi_objective'] = $r['id_taksonomi_objective'];
				$this->data['rowheader1']['id_taksonomi_area'] = $r['id_taksonomi_area'];
			}
		}
	}

	private function rentetan($id_risiko){
		$data = array();
		$data['risiko'] = $this->conn->GetRow("select r.id_risiko_sebelum, r.id_scorecard, r.nomor, r.nama, r.deskripsi, r.inheren_dampak, r.inheren_kemungkinan, r.control_dampak_penurunan, r.control_kemungkinan_penurunan, r.penyebab, r.dampak, r.residual_target_dampak, r.residual_target_kemungkinan, r.residual_dampak_evaluasi, r.residual_kemungkinan_evaluasi,r.progress_capaian_sasaran, r.progress_capaian_kinerja, r.hambatan_kendala, r.penyesuaian_tindakan_mitigasi, r.status_risiko, r.status_keterangan, sk.nama as nsk, sk.kpi as ksk, ss.nama as nss, ss.kpi as kss, kd.nama as nk, r.tgl_risiko, r.tgl_close
			from risk_risiko r
			left join risk_sasaran_kegiatan sk on r.id_sasaran_kegiatan = sk.id_sasaran_kegiatan
			left join risk_sasaran_strategis ss on r.id_sasaran_strategis = ss.id_sasaran_strategis
			left join mt_risk_kriteria_dampak kd on r.id_kriteria_dampak = kd.id_kriteria_dampak
			where r.id_risiko=".$this->conn->escape($id_risiko)."
			and (status_risiko = '0' or status_risiko = '2')");

		if(!$data['risiko'])
			return false;

		$data['scorecard'] = $this->conn->GetRow("select s.nama, s.scope, j.nama as nj, k.nama as nkr
			from risk_scorecard s
			left join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)
			left join mt_risk_kajian_risiko k on s.id_kajian_risiko = k.id_kajian_risiko
			where id_scorecard = ".$this->conn->escape($data['risiko']['id_scorecard']));


		$data['control'] = $this->conn->GetArray("select c.id_control, c.nama, c.deskripsi, c.remark, c.is_efektif, c.menurunkan_dampak_kemungkinan, i.nama as interval
			from risk_control c
			left join mt_interval i on c.id_interval = i.id_interval
			where id_risiko=".$this->conn->escape($id_risiko)."
			order by c.nama asc");
		
		$data['mitigasi'] = $this->conn->GetArray("select m.id_mitigasi, m.nama, m.deskripsi, m.dead_line, m.menurunkan_dampak_kemungkinan, m.biaya, m.revenue, m.is_efektif, m.cba, j.nama as jabatan, p.prosentase||'% '||p.nama as status_progress
			from risk_mitigasi m
			left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
			left join mt_status_progress p on m.id_status_progress = p.id_status_progress
			where id_risiko=".$this->conn->escape($id_risiko)." and is_control = '0' order by m.nama asc");

		return $data;
	}

	private function risikoSebelum(&$ret, $id_risiko_sebelum){
		if(!$id_risiko_sebelum)
			return;

		$risiko = $this->conn->GetRow("select id_risiko_sebelum 
			from risk_risiko 
			where id_risiko = ".$this->conn->escape($id_risiko_sebelum));

		if($risiko['id_risiko_sebelum']){
			$this->risikoSebelum($ret, $risiko['id_risiko_sebelum']);
			$retet = $this->rentetan($risiko['id_risiko_sebelum']);
			if($retet)
				$ret[] = $retet;
		}
	}

	private function risikoSesudah(&$ret, $id_risiko_sebelum){
		if(!$id_risiko_sebelum)
			return;

		$risiko = $this->conn->GetRow("select id_risiko 
			from risk_risiko 
			where id_risiko_sebelum = ".$this->conn->escape($id_risiko_sebelum));

		if($risiko['id_risiko']){
			$retet = $this->rentetan($risiko['id_risiko']);
			if($retet){
				$ret[] = $retet;
				$this->risikoSesudah($ret, $risiko['id_risiko']);
			}
		}
	}

	function log_history($id_risiko=null){
		$this->data['width_page'] = "900px";
		$this->template = "panelbackend/main3";
		$this->layout = "panelbackend/layout3";

		$this->data['id_risiko'] = $id_risiko;

		$this->data['excel'] = false;

		$this->data['page_title'] = 'Riwayat Risiko';

		$data = array();

		$this->risikoSebelum($data, $id_risiko);

		$retet = $this->rentetan($id_risiko);
		if($retet)
			$data[] = $retet;

		$this->risikoSesudah($data, $id_risiko);

		$this->data['rows'] = $data;

		$this->data['efektifitasarr'] = $this->conn->GetArray("select id_efektifitas from mt_risk_efektifitas where need_lampiran = 1");

		$this->View("panelbackend/risk_risiko_log");
	}

	protected function _afterUpdate($id){
		$ret = $this->_afterInsert($id);

		return $ret;
	}

	protected function _afterInsert($id){
		$ret = $this->_delSertKpi($id);

		if($ret)
			$ret = $this->_delsertFiles($id);	

		if(!$this->data['is_peluang']){
			if($ret)
				$ret = $this->_delSertPenyebab($id);	

			if($ret)
				$ret = $this->_delSertDampak($id);	
		}	
		
		return $ret;
	}

	private function _delsertFiles($id = null){
		$ret = true;

		if(!empty($this->post['file'])){
			foreach($this->post['file']['id'] as $k=>$v){
				if(!$ret)
					break;

				$return = $this->_updateFiles(array($this->pk=>$id), $v);

				$ret = $return['success'];
			}
		}

		if($ret && $this->access_role['rekomendasi'] && !$this->data['is_peluang']){

        	$cek = $this->conn->GetOne("select 1 from risk_mitigasi_files where jenis = 'filerekomendasi' and id_mitigasi = ".$this->conn->escape($id));

        	// if(!$cek && empty($this->post['filerekomendasi']))
        	// {
			// 	$this->data['err_msg'] .= "File dasar penetapan control wajib di isi. ";
        	// 	return false;
        	// }

			if(!empty($this->post['filerekomendasi'])){
				foreach($this->post['filerekomendasi']['id'] as $k=>$v){
					if(!$ret)
						break;

					$return = $this->_updateFiles(array($this->pk=>$id), $v);

					$ret = $return['success'];
				}
			}
		}

		return $ret;
	}

	protected function _delSertPenyebab($id){
		$ret = $this->conn->Execute("delete from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($id));


		if($this->post['penyebab']){
			foreach($this->post['penyebab'] as $jenis=>$rows){
				if(!$ret)
					break;

				foreach($rows as $r){
					if(!$ret)
						break;

					if(!$r['id_taksonomi_penyebab']){
						$record1 = array();
						$record1['nama'] = $r['nama'];
						$record1['jenis'] = $jenis;
						$record1['id_taksonomi_risiko'] = $this->post['id_taksonomi_risiko'];
						$ret = $this->conn->goInsert("mt_taksonomi_penyebab",$record1);

						if($ret)
							$r['id_taksonomi_penyebab'] = $this->conn->GetOne("select max(id_taksonomi_penyebab) 
							from mt_taksonomi_penyebab 
							where jenis = ".$this->conn->escape($record1['jenis']));
					}

					$r['jenis'] = $jenis;
					$r['id_risiko'] = $id;

					$ret = $this->conn->goInsert("risk_risiko_penyebab", $r);
				}
			}
			unset($this->post['penyebab']);
		}

		return $ret;
	}

	protected function _delSertDampak($id){
		$ret = $this->conn->Execute("delete from risk_risiko_dampak where id_risiko = ".$this->conn->escape($id));


		if($this->post['dampak']){
			foreach($this->post['dampak'] as $r){
				if(!$ret)
					break;


				if(!$r['id_taksonomi_dampak']){
					$record1 = array();
					$record1['nama'] = $r['nama'];
					$record1['id_taksonomi_risiko'] = $this->post['id_taksonomi_risiko'];
					$ret = $this->conn->goInsert("mt_taksonomi_dampak",$record1);

					if($ret)
						$r['id_taksonomi_dampak'] = $this->conn->GetOne("select max(id_taksonomi_dampak) 
						from mt_taksonomi_dampak");
				}

				$r['id_risiko'] = $id;

				$ret = $this->conn->goInsert("risk_risiko_dampak", $r);
			}
			unset($this->post['dampak']);
		}

		return $ret;
	}

	private function _delSertKpi($id){
		$return = $this->conn->Execute("delete from risk_risiko_kpi where id_risiko = ".$this->conn->escape($id));

		if(is_array($this->post['id_kpi'])){
			foreach ($this->post['id_kpi'] as $key => $value) {
				if($return){
					if(!$value)
						continue;

					$record = array();
					$record['id_risiko'] = $id;
					$record['id_kpi'] = $value;

					$sql = $this->conn->InsertSQL("risk_risiko_kpi", $record);

	        		if($sql){
					    $return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
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

		if($this->post['act']){
			
			if($this->data['add_param']){
				$add_param = '/'.$this->data['add_param'];
			}
		}

		$respon = $this->model->SelectGrid(
			$param
		);

		return $respon;
	}


	public function proses($id_scorecard=null, $id=null){
		$this->_beforeDetail($id_scorecard,$id);

		if($id){
			$this->data['row'] = $this->model->GetByPk($id);

			if (!$this->data['row'])
				$this->NoData();

			$this->data['notab'] = false;
		}
		else{
			$this->data['notab'] = true;
		}

		$this->_afterDetail($id);

		$this->pk = "id_scorecard";

		$this->data['row']['fileproses'] = $this->conn->GetRow("select 
			a.id_scorecard_files as id,
			client_name as name
			from risk_scorecard_files a
			where jenis = 'fileproses' and a.id_scorecard = ".$this->conn->escape($id_scorecard));

		$this->View("panelbackend/risk_risiko_proses");
	}

	function open_file($id=null, $nameid=null){
		$this->_openFiles($id, $nameid);
	}

	private function _openFiles($id=null, $nameid=null){
		$this->load->model("Risk_scorecard_filesModel","modelfile1");
		if($nameid=='fileproses' or $nameid=='fileakhirproject')
			$row = $this->modelfile1->GetByPk($id);
		else
			$row = $this->modelfile->GetByPk($id);

		if($row){
			$full_path = $this->data['configfile']['upload_path'].$row['file_name'];
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename='{$row['client_name']}'");
			echo file_get_contents($full_path);
			die();
		}else{
			echo '';
		}
	}

	protected function _isValidImport($record){
		$this->data['rules'] = $this->Rules();

		unset($this->data['rules']['id_kriteria_dampak']);
		unset($this->data['rules']['id_kriteria_kemungkinan']);

		/*dpr($record);
		dpr($this->data['rules'],1);*/

		$rules = array_values($this->data['rules']);

		if($record){
			$this->form_validation->set_data($record);
		}
		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE)
		{
			return validation_errors();
		}
	}

	public function HeaderExport(){
		$this->data['penanggungjawabarr'] = $this->conn->GetList("select id_jabatan as key, nama as val from mt_sdm_jabatan a where exists (select 1 from risk_scorecard r where a.id_jabatan = r.owner)  order by key");

		$this->data['mtkegiatanarr'] = $this->data['sasaranarr'] = $this->conn->GetList("select id_sasaran_kegiatan as key, nama as val from risk_sasaran_kegiatan a order by key");

		$this->data['kpiarr'] = $this->conn->GetList("select id_kpi as key, nama as val from risk_kpi a  order by key");

		$this->data['kemungkinanarr'] = $this->conn->GetList("select id_kemungkinan as key, kode as val from mt_risk_kemungkinan order by kode");

		$this->data['dampakarr'] = $this->conn->GetList("select id_dampak as key, kode as val from mt_risk_dampak order by kode");

		return array(
			array(
				'name'=>'kemungkinan',
				'label'=>'Kemungkinan',
				'width'=>"50px",
				'type'=>"listinverst",
				'value'=>$this->data['kemungkinanarr'],
			),
			array(
				'name'=>'dampak',
				'label'=>'Dampak',
				'width'=>"50px",
				'type'=>"listinverst",
				'value'=>$this->data['dampakarr'],
			),
			array(
				'name'=>'penanggung_jawab',
				'label'=>'Penanggung jawab mitigasi',
				'width'=>"50px",
				'type'=>"listinverst",
				'value'=>$this->data['penanggungjawabarr'],
			),
			array(
				'name'=>'id_sasaran_kegiatan',
				'label'=>'Sasaran Kegiatan',
				'width'=>"50px",
				'type'=>"listinverst",
				'value'=>$this->data['sasaranarr'],
			),
			array(
				'name'=>'id_kpi',
				'label'=>'KPI',
				'width'=>"50px",
				'type'=>"listinverst",
				'value'=>$this->data['kpiarr'],
			),
		);
	}

	public function import_list($id_scorecard=null){

		$file_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','application/wps-office.xls','application/wps-office.xlsx');

		if(in_array($_FILES['importupload']['type'], $file_arr)){

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters("","");
		
			$this->load->library('Factory');
			$inputFileType = Factory::identify($_FILES['importupload']['tmp_name']);
			$objReader = Factory::createReader($inputFileType);
			$excel = $objReader->load($_FILES['importupload']['tmp_name']);
			$sheet = $excel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
            $this->model->conn->StartTrans();

			#header export
			$header=array(
				array(
					'name'=>$this->model->pk
				)
			);
			$header=array_merge($header,$this->HeaderExport());

			$kemungkinanarr = array();
			foreach($this->data['kemungkinanarr'] as $k=>$v){
				$kemungkinanarr[strtolower(trim($v))] = $k;
			}

			$dampakarr = array();
			foreach($this->data['dampakarr'] as $k=>$v){
				$dampakarr[strtolower(trim($v))] = $k;
			}

			$penanggungjawabarr = array();
			foreach($this->data['penanggungjawabarr'] as $k=>$v){
				$penanggungjawabarr[strtolower(trim($v))] = $k;
			}

			$sasaranarr = array();
			foreach($this->data['sasaranarr'] as $k=>$v){
				$sasaranarr[strtolower(trim($v))] = $k;
			}

			$kpiarr = array();
			foreach($this->data['kpiarr'] as $k=>$v){
				$kpiarr[strtolower(trim($v))] = $k;
			}

			// $this->conn->debug = 1;

			for ($row = 3; $row <= $highestRow; $row++){ 

				$rrr = $this->conn->GetRow("select id_risiko, tgl_risiko, nomor, is_lock
					from risk_risiko 
					where id_scorecard = ".$this->conn->escape($id_scorecard)."
					and substr(nomor,-2) = ".$this->conn->escape(str_pad($sheet->getCell('B'.$row)->getValue(), 2, '0', STR_PAD_LEFT)));

				if($rrr['is_lock']=='1')
					continue;

				$id_risiko = $rrr['id_risiko'];

				$id_sasaran_kegiatan = $sasaranarr[strtolower(trim($sheet->getCell('A'.$row)->getValue()))];
				$id_sasaran_strategis = $this->conn->GetOne("select id_sasaran_strategis from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan));

				$this->data['sasaranarr'][$id_sasaran_strategis] = $id_sasaran_strategis;

				$id_kpi = array();
				$arrkpi = explode("\n",$sheet->getCell('B'.$row)->getValue());
				if(!is_array($arrkpi))
					$arrkpi = array($arrkpi);

				foreach($arrkpi as $vkpi){
					$idkpi = $kpiarr[strtolower(trim($vkpi))];

					$cek = $this->conn->GetOne("select 1 from risk_sasaran_kegiatan_kpi where id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan)." and id_kpi = ".$this->conn->escape($idkpi));

					if(!$cek)
						$this->conn->goInsert("risk_sasaran_kegiatan_kpi",array("id_kpi"=>$idkpi,"id_sasaran_kegiatan"=>$id_sasaran_kegiatan));

					$id_kpi[] = $idkpi;
				}

				$record =  array(
					'nama'=>$sheet->getCell('D'.$row)->getValue(),
					'inheren_dampak'=>(string)$dampakarr[strtolower(trim($sheet->getCell('H'.$row)->getValue()))],
					'inheren_kemungkinan'=>(string)$kemungkinanarr[strtolower(trim($sheet->getCell('G'.$row)->getValue()))],
					'residual_target_dampak'=>(string)$dampakarr[strtolower(trim($sheet->getCell('Q'.$row)->getValue()))],
					'residual_target_kemungkinan'=>(string)$kemungkinanarr[strtolower(trim($sheet->getCell('P'.$row)->getValue()))],
					'id_sasaran_kegiatan'=>(string)$id_sasaran_kegiatan,
					'id_sasaran_strategis'=>(string)$id_sasaran_strategis,
					'control_dampak_penurunan'=>(string)$dampakarr[strtolower(trim($sheet->getCell('K'.$row)->getValue()))],
					'control_kemungkinan_penurunan'=>(string)$kemungkinanarr[strtolower(trim($sheet->getCell('J'.$row)->getValue()))],
					/*'id_taksonomi_objective'=>$this->post['id_taksonomi_objective'],
					'id_taksonomi_area'=>$this->post['id_taksonomi_area'],
					'id_taksonomi_risiko'=>$this->post['id_taksonomi_risiko'],*/
				);

				$record['id_status_pengajuan'] = '5';
				$record['id_risiko'] = (string)$id_risiko;
				$record['id_scorecard'] = (string)$id_scorecard;


				if($id_risiko){
					$record = array_merge($record, $rrr);
				}else{
					$record['tgl_risiko'] = ($_SESSION[SESSION_APP]['tgl_efektif']?$_SESSION[SESSION_APP]['tgl_efektif']:date('d-m-Y'));
					$record['nomor_asli'] = $record['nomor'] = $this->model->getNomorRisiko($id_scorecard, $id_sasaran_strategis, $record['tgl_risiko']);
				}

				if($this->access_role['rekomendasi'])
					$record['rekomendasi_keterangan'] = "Import";

				if($this->access_role['review'])
					$record['review_kepatuhan'] = "Import";


				$record['penyebab'] = array();
				$temp = explode("\n", $sheet->getCell('E'.$row)->getValue());

				if(!is_array($temp))
					$temp = array($temp);

				foreach($temp as $v){
					if($v)
						$record['penyebab'][0][]['nama'] = trim(trim(strstr($v,"."),"."));
				}


				$record['dampak'] = array();
				$temp = explode("\n", $sheet->getCell('F'.$row)->getValue());

				if(!is_array($temp))
					$temp = array($temp);

				foreach($temp as $v){
					if($v)
						$record['dampak'][]['nama'] = trim(trim(strstr($v,"."),"."));
				}


				$recordc = array();
				$temp = explode("\n", $sheet->getCell('I'.$row)->getValue());

				if(!is_array($temp))
					$temp = array($temp);

				foreach($temp as $k=>$v){
					if($v){
						$recordc[$k]['no'] = trim(trim(strstr($v,".", true),"."));
						$recordc[$k]['nama'] = trim(trim(strstr($v,"."),"."));
					}
				}


				$recordm = array();
				$temp1 = explode("\n", $sheet->getCell('L'.$row)->getValue());
				$temp2 = explode("\n", $sheet->getCell('M'.$row)->getValue());
				$temp3 = explode("\n", $sheet->getCell('N'.$row)->getValue());
				$temp4 = explode("\n", $sheet->getCell('O'.$row)->getValue());

				if(!is_array($temp1))
					$temp1 = array($temp1);
				if(!is_array($temp2))
					$temp2 = array($temp2);
				if(!is_array($temp3))
					$temp3 = array($temp3);
				if(!is_array($temp4))
					$temp4 = array($temp4);

				foreach($temp1 as $k=>$v){
					if($v){
						$recordm[$k]['no'] = trim(trim(strstr($v,".", true),"."));
						$recordm[$k]['nama'] = trim(trim(strstr($v,"."),"."));
						$recordm[$k]['biaya'] = (float)trim(trim(strstr($temp2[$k],"."),"."));
						$recordm[$k]['penanggung_jawab'] = $penanggungjawabarr[trim(strtolower(trim(strstr($temp3[$k],"."),".")))];
						$recordm[$k]['dead_line'] = trim(trim(strstr($temp4[$k],"."),"."));
					}
				}

				$record['id_kpi'] = $id_kpi;

		    	$this->post = $this->data['row'] = $record;

				$this->access_role['rekomendasi'] = false;

		    	$error = $this->_isValidImport($record);
		    	if($error){
		    		$return['error'] = $error;
		    	}else{
		    		unset($record['id_kpi']);
		    		unset($record['penyebab']);
		    		unset($record['dampak']);
			    	if($record[$this->model->pk]){
			    		$return = $this->model->Update($record, $this->model->pk."=".$record[$this->model->pk]);
			    		$id = $record[$this->model->pk];

				    	if($return['success']){
				    		$ret = $this->_afterUpdate($id);

				    		if(!$ret){
				    			$return['success'] = false;
				    			$return['error'] = "Gagal update";
				    		}
				    	}
			    	}else{
			    		$return = $this->model->Insert($record);
			    		$id = $return['data'][$this->model->pk];

				    	if($return['success']){
				    		$ret = $this->_afterInsert($id);

				    		if(!$ret){
				    			$return['success'] = false;
				    			$return['error'] = "Gagal insert";
				    		}
				    	}
			    	}
			    }

			    if($return['success']){
			    	$controlarr = $this->conn->GetList("select no key, id_control as val from risk_control where no is not null and id_risiko = ".$this->conn->escape($id));

			    	$ret = $return['success'];

			    	if($recordc)
			    		foreach($recordc as $rc){
		    			if(!$ret)
		    				break;

			    		$rc['id_risiko'] = $id;
			    		$rc['status_konfirmasi'] = 1;
			    		$id_control = $controlarr[$rc['nama']];

			    		if($id_control)
			    			$ret = $this->conn->goUpdate("risk_control",$rc,"id_risiko = ".$this->conn->escape($id)." and id_control = ".$this->conn->escape($id_control));
			    		else
			    			$ret = $this->conn->goInsert("risk_control",$rc);
		    		}

					if(!$ret){
		    			$return['success'] = false;
		    			$return['error'] = "Gagal insert";
		    		}
			    }

			    if($return['success']){
			    	$mitigasiarr = $this->conn->GetList("select no key, id_mitigasi as val from risk_mitigasi where no is not null and id_risiko = ".$this->conn->escape($id));

			    	$ret = $return['success'];
			    	if($recordm)
			    		foreach($recordm as $rm){
		    			if(!$ret)
		    				break;

			    		$rm['id_risiko'] = $id;
			    		$rm['status_konfirmasi'] = 1;
			    		$id_mitigasi = $mitigasiarr[$rm['nama']];

			    		if($id_mitigasi)
			    			$ret = $this->conn->goUpdate("risk_mitigasi",$rm,"id_risiko = ".$this->conn->escape($id)." and id_mitigasi = ".$this->conn->escape($id_mitigasi));
			    		else
			    			$ret = $this->conn->goInsert("risk_mitigasi",$rm);
		    		}
		    		
					if(!$ret){
		    			$return['success'] = false;
		    			$return['error'] = "Gagal insert";
		    		}
			    }

				if(!$return['success'])
					break;				
			}


			if (!$return['error'] && $return['success']) {
            	$this->model->conn->trans_commit();
				SetFlash('suc_msg', $return['success']);
			}else{
            	$this->model->conn->trans_rollback();
				$return['error'] = "Gagal import. ".$return['error'];
				$return['success'] = false;
			}
		}else{
			$return['error'] = "Format file tidak sesuai";
		}

		echo json_encode($return);
	}

	public function export_list($id_scorecard=null){
		$this->load->library('PHPExcel');
		$this->load->library('Factory');
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);	
		$excelactive = $excel->getActiveSheet();


		#header export
		$header=array(
			array(
				'name'=>$this->model->pk
			)
		);
		$header=array_merge($header,$this->HeaderExport());

		$row = 1;
		$excelactive->setCellValue('A'.$row,"Sasaran Kegiatan");
		$excelactive->setCellValue('B'.$row,"KPI");
		$excelactive->setCellValue('C'.$row,"No");
		$excelactive->setCellValue('D'.$row,"Identifikasi Risiko");
		$excelactive->setCellValue('G'.$row,"Risiko Inheren");
		$excelactive->setCellValue('I'.$row,"Kontrol Yang Telah Ada");
		$excelactive->setCellValue('J'.$row,"Risiko Pasca Control");
		$excelactive->setCellValue('L'.$row,"Penanganan (Mitigasi)");
		$excelactive->setCellValue('P'.$row,"Risiko Residual");
		$excelactive->mergeCells('A1:A2');
		$excelactive->mergeCells('B1:B2');
		$excelactive->mergeCells('C1:C2');
		$excelactive->mergeCells('D1:F1');
		$excelactive->mergeCells('G1:H1');
		$excelactive->mergeCells('J1:K1');
		$excelactive->mergeCells('L1:O1');
		$excelactive->mergeCells('P1:Q1');
		$row++;
		$excelactive->setCellValue('D'.$row,"Risiko");
		$excelactive->setCellValue('E'.$row,"Penyebab");
		$excelactive->setCellValue('F'.$row,"Dampak");
		$excelactive->setCellValue('G'.$row,"Tingkat Kemungkinan");
		$excelactive->setCellValue('H'.$row,"Skala Dampak");
		$excelactive->setCellValue('I'.$row,"Pencegahan / Pemulihan");
		$excelactive->setCellValue('J'.$row,"Tingkat Kemungkinan");
		$excelactive->setCellValue('K'.$row,"Skala Dampak");
		$excelactive->setCellValue('L'.$row,"Program Mitigasi");
		$excelactive->setCellValue('M'.$row,"Biaya Mitigasi");
		$excelactive->setCellValue('N'.$row,"Penanggung jawab mitigasi");
		$excelactive->setCellValue('O'.$row,"Waktu Pelaksanaan");
		$excelactive->setCellValue('P'.$row,"Tingkat Kemungkinan");
		$excelactive->setCellValue('Q'.$row,"Skala Dampak");

		$col = 'Q';
		$excelactive->getStyle('A1:'.$col.$row)->getFont()->setBold(true);
        $excelactive
		    ->getStyle('A1:'.$col.$row)
		    ->getFill()
		    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		    ->getStartColor()
		    ->setARGB('eff0f1');

	     $style = array(
        	'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        ),
	        'borders' => array(
	            'allborders' => array(
	                'style' => PHPExcel_Style_Border::BORDER_THIN,
	                'color' => array('rgb' => '333333')
	            )
	        )
	    );

	    $excelactive->getStyle('A1:'.$col.$row)->applyFromArray($style);
		
		$this->_setFilter("id_scorecard = ".$this->conn->escape($id_scorecard));

	    #data
		$respon = $this->model->SelectGrid(
			array(
			'limit' => -1,
			'order' => "no, nomor",
			'filter' => $this->_getFilter()
			)
		);
		$rows = $respon['rows'];

		$kemungkinanarr = $this->data['kemungkinanarr'];
		$dampakarr = $this->data['dampakarr'];

		$no1=0;

		$row = 3;
        foreach($rows as $r){
        	$no1++;

        	// if(!$r['no'])
    		$r['no'] = (int)substr($r['nomor'],-2);

        	if(!$r['no'])
        		$r['no'] = $no1;

        	$$no1 = $r['no'];

        	$nama_kpi = $this->conn->GetListStr("select nama as val from risk_risiko_kpi a join risk_kpi b on a.id_kpi = b.id_kpi where a.id_risiko = ".$this->conn->escape($r['id_risiko']));

        	$nama_kegiatan = $this->conn->GetListStr("select nama as val from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($r['id_sasaran_kegiatan']));

        	$rs = $this->conn->GetArray("select * from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($r['id_risiko']));
        	$penyebab = null;
        	$no=1;
        	foreach($rs as $r1){

    			$nostr = ($no++).'. ';

        		$penyebab .= $nostr.$r1['nama']."\n";
        	}

        	$rs = $this->conn->GetArray("select * from risk_risiko_dampak where id_risiko = ".$this->conn->escape($r['id_risiko']));
        	$dampak = null;
        	$no=1;
        	foreach($rs as $r1){
        		
    			$nostr = ($no++).'. ';

        		$dampak .= $nostr.$r1['nama']."\n";
        	}

        	$rs = $this->conn->GetArray("select * from risk_control where id_risiko = ".$this->conn->escape($r['id_risiko'])." order by no");
        	$control = null;
        	$no=1;
        	foreach($rs as $r1){

        		if($r1['no'])
        			$no=$r1['no'];
        		
    			$nostr = ($no++).'. ';

        		$control .= $nostr.$r1['nama']."\n";
        	}

        	$rs = $this->conn->GetArray("select m.*, j.nama as penanggung_jawab
        		from risk_mitigasi m 
        		join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan
        		where id_risiko = ".$this->conn->escape($r['id_risiko'])." order by no");

        	$mitigasi = null;
        	$biaya = null;
        	$penanggung_jawab_mitigasi = null;
        	$dead_line_mitigasi = null;
        	$no=1;
        	foreach($rs as $r1){

        		if($r1['no'])
        			$no=$r1['no'];
        		
    			$nostr = ($no++).'. ';

        		$mitigasi .= $nostr.$r1['nama']."\n";
        		$biaya .= $nostr.$r1['biaya']."\n";
        		$penanggung_jawab_mitigasi .= $nostr.$r1['penanggung_jawab']."\n";
        		$dead_line_mitigasi .= $nostr.$r1['dead_line']."\n";
        	}

        	if(!$penyebab)
        		$penyebab = $r['penyebab'];

        	if(!$dampak)
        		$dampak = $r['dampak'];

	    	$excelactive->setCellValue('A'.$row,$nama_kegiatan);
	    	$excelactive->setCellValue('B'.$row,$nama_kpi);
	    	$excelactive->setCellValue('C'.$row,$r['no']);
	    	$excelactive->setCellValue('D'.$row,$r['nama']);
	    	$excelactive->setCellValue('E'.$row,$penyebab);
	    	$excelactive->setCellValue('F'.$row,$dampak);
	    	$excelactive->setCellValue('G'.$row,$kemungkinanarr[$r['inheren_kemungkinan']]);
	    	$excelactive->setCellValue('H'.$row,$dampakarr[$r['inheren_dampak']]);
	    	$excelactive->setCellValue('I'.$row,$control);
	    	$excelactive->setCellValue('J'.$row,$kemungkinanarr[$r['control_kemungkinan_penurunan']]);
	    	$excelactive->setCellValue('K'.$row,$dampakarr[$r['control_dampak_penurunan']]);
	    	$excelactive->setCellValue('L'.$row,$mitigasi);
	    	$excelactive->setCellValue('M'.$row,$biaya);
	    	$excelactive->setCellValue('N'.$row,$penanggung_jawab_mitigasi);
	    	$excelactive->setCellValue('O'.$row,$dead_line_mitigasi);
	    	$excelactive->setCellValue('P'.$row,$kemungkinanarr[$r['residual_target_kemungkinan']]);
	    	$excelactive->setCellValue('Q'.$row,$dampakarr[$r['residual_target_dampak']]);
            $row++;
        }


	    $objWriter = Factory::createWriter($excel,'Excel2007');
	    ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->ctrl.date('Ymd').'.xls"');
		header('Cache-Control: max-age=0');
		ob_end_clean();
		$objWriter->save('php://output');
		exit();
	}

	function upload_file_grc($id=null, $id_dok_pendukung_grc=null){
		
		$jenis_file = key($_FILES);

		$ret = $this->_uploadFilesGrc($jenis_file, $id, $id_dok_pendukung_grc);		

		echo json_encode($ret);
	}

	function delete_file_grc($id=null){
		$ret = $this->_deleteFilesGrc($this->post['id']);
		
		echo json_encode($ret);
	}

	function open_file_grc($id=null, $nameid=null){
		$this->_openFilesGrc($id, $nameid);
	}
	
	protected function _updateFilesGrc($record=array(), $id=null){
		return $this->modelfilegrc->Update($record, $this->modelfilegrc->pk."=".$this->conn->escape($id));
	}

	private function _deleteFilesGrc($id){
		
		$row = $this->modelfilegrc->GetByPk($id);

		if(!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$return = $this->modelfilegrc->Delete($this->modelfilegrc->pk." = ".$this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'].$file_name;
			unlink($full_path);

			return array("success"=>true);
		}else{
			return array("error"=>"File ".$row['client_name']." gagal dihapus");
		}
	}

	private function _openFilesGrc($id=null, $nameid=null){
		$row = $this->modelfilegrc->GetByPk($id);
		if($row ){
			$full_path = $this->data['configfile']['upload_path'].$row['file_name'];
			header("Content-Type: {$row['file_type']}");
			header("Content-Disposition: inline; filename=\"{$row['client_name']}\"");
			echo file_get_contents($full_path);
			die();
		}else{
			$this->Error404();
		}
	}

	protected function _uploadFilesGrc($jenis_file=null, $id=null, $id_dok_pendukung_grc=null){
// echo $id."<br>".$id_dok_pendukung_grc." okk";exit;
		$name = $_FILES[$jenis_file]['name'];

		$this->data['configfile']['file_name'] = $jenis_file.time().$name;

		$this->load->library('upload', $this->data['configfile']);

        if ( ! $this->upload->do_upload($jenis_file))
        {
            $return = array('error' => "File $name gagal upload, ".strtolower(str_replace(array("<p>","</p>"),"",$this->upload->display_errors())));
        }
        else
        {
    		$upload_data = $this->upload->data();

			$record = array();
			$record['client_name'] = $upload_data['client_name'];
			$record['file_name'] = $upload_data['file_name'];
			$record['file_type'] = $upload_data['file_type'];
			$record['file_size'] = $upload_data['file_size'];
			$record['jenis_file'] = $record['jenis'] = str_replace("upload","",$jenis_file);
			$record['id_dok_pendukung_grc'] = $id_dok_pendukung_grc;
			$record['id_scorecard'] = $id;

			$ret = $this->modelfilegrc->Insert($record);
			if($ret['success'])
			{
				$return = array('file'=>array("id"=>$ret['data'][$this->modelfilegrc->pk],"name"=>$upload_data['client_name']));
			}else{
				unlink($upload_data['full_path']);
				$return = array('errors'=>"File $name gagal upload");
			}

        }

        return $return;

	}

	// protected function _uploadFiles($jenis_file=null, $id=null)
	// {
	// 	$reqLampiran= $_FILES[$jenis_file];

	// 	/* START UPLOAD FILE */
	// 	$FILE_DIR = "uploads/";
	// 	$FILE_PERMOHONAN = "PERMOHONAN_CUTI_LAINNYA/";
	// 	$FILE_DIR = $FILE_DIR.$FILE_PERMOHONAN;

	// 	// if(!file_exists($FILE_DIR))
	// 	// {
	// 	// 	mkdir($FILE_DIR, 0755);
	// 	// }


	// 	/* START SYNOLOGY */
	// 	$this->load->library('settingSynology');
	// 	$synologyAPI = new settingSynology();
	// 	// $folderName, $subFolder=""
	// 	$successCreateFlder= $synologyAPI->createFolder(str_replace("/", "", $FILE_PERMOHONAN), str_replace("/", "", $FILE_PERMOHONAN));

	// 	print_r($successCreateFlder);exit;

	// 	for($i=0;$i<count($reqLampiran);$i++)
	// 	{	
	// 		if($reqLampiran['name'][$i] == "")
	// 		{}
	// 		else			
	// 		{
	// 			$renameFile = str_replace("-", "", $reqTanggalAwal)."_".$this->ID."_".str_replace("/","", $reqNomor).$i.".".getExtension($reqLampiran['name'][$i]);
	// 			if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
	// 			{
	// 				if($i == 0)	
	// 					$insertLinkFile = $FILE_PERMOHONAN.$renameFile;
	// 				else
	// 					$insertLinkFile .= ",".$FILE_PERMOHONAN.$renameFile;
					
	// 				/* SYNOLOGY*/
	// 				$successUpload = $synologyAPI->uploadFile($FILE_DIR.$renameFile, str_replace("/", "", $FILE_PERMOHONAN));
	// 				if($successUpload)
	// 					unlink($FILE_DIR.$renameFile);

	// 			}			
	// 		}	
	// 	}



	// 	$permohonan_cuti_bersalin->setField("LAMPIRAN", $insertLinkFile);
	// 	/* END UPLOAD FILE */

	// 	$name = $_FILES[$jenis_file]['name'];

	// 	$this->data['configfile']['file_name'] = $jenis_file.time().$name;

	// 	// $this->load->library('upload', $this->data['configfile']);
	// 	$this->load->library('settingSynology');

	// 	// Contoh penggunaan: mengunggah file ke folder yang ditentukan
	// 	if (isset($_FILES[$jenis_file]))
	// 	{
	// 		$_file = $_FILES[$jenis_file];
	// 	}
	// 	// print_r($_file);exit;
	// 	$fileToUpload = $_file; // Ganti dengan path file yang ingin diunggah
	// 	$destinationFolder = '/datafile/erm'; // Ganti dengan path folder tujuan

	// 	$result = settingSynology::do_upload($fileToUpload, $destinationFolder);

	// 	return "Response: " . $result; // Menampilkan respons dari API Synology

    //     // if ( ! $this->upload->do_upload($jenis_file))
    //     // {
    //     //     $return = array('error' => "File $name gagal upload, ".strtolower(str_replace(array("<p>","</p>"),"",$this->upload->display_errors())));
    //     // }
    //     // else
    //     // {
    // 	// 	$upload_data = $this->upload->data();

	// 	// 	$record = array();
	// 	// 	$record['client_name'] = $upload_data['client_name'];
	// 	// 	$record['file_name'] = $upload_data['file_name'];
	// 	// 	$record['file_type'] = $upload_data['file_type'];
	// 	// 	$record['file_size'] = $upload_data['file_size'];
	// 	// 	$record['jenis_file'] = $record['jenis'] = str_replace("upload","",$jenis_file);
	// 	// 	$record[$this->pk] = $id;

	// 	// 	$ret = $this->modelfile->Insert($record);
	// 	// 	if($ret['success'])
	// 	// 	{
	// 	// 		$return = array('file'=>array("id"=>$ret['data'][$this->modelfile->pk],"name"=>$upload_data['client_name']));
	// 	// 	}else{
	// 	// 		unlink($upload_data['full_path']);
	// 	// 		$return = array('errors'=>"File $name gagal upload");
	// 	// 	}

    //     // }

    //     // return $return;

	// }

}
