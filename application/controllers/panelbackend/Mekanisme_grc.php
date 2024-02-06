<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mekanisme_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mekanisme_grclist";
		$this->viewdetail = "panelbackend/mekanisme_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Scorecard';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Scorecard';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Scorecard';
			$this->data['edited'] = false;
		}else{
			$this->mode = 'index';
			$this->data['mode'] = 'index';
			$this->data['page_title'] = 'Lingkup Mekanisme GRC';
		}

		$this->load->model("Risk_scorecardModel","model");
		$this->data['configfile'] = $this->config->item('file_upload_config');


		$this->load->model("Mt_sdm_unitModel","munit");
		$this->data['unitarr'] = $this->munit->GetCombo();

		$this->load->model("Mt_pb_nama_prosesModel","mtpbnamaproses");
		$this->data['mtpbnamaprosesarr'] = $this->mtpbnamaproses->GetCombo();

		$this->load->model("Mt_status_proyekModel","mtstatusproyek");
		$this->data['mtstatusproyekarr'] = $this->mtstatusproyek->GetCombo();

		$this->load->model("Mt_status_unitModel","mtstatusunit");
		$this->data['mtstatusunitarr'] = $this->mtstatusunit->GetCombo();	

		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);

		$this->load->model("Mt_status_progress_grcModel","mtprogressgrc");
		$this->data['pregressarr'] = $this->mtprogressgrc->GetComboNew("", " status = 1");

		$this->data['tipescorecard']= array('1'=>'Folder','2'=>'Sub Folder','0'=>'Risiko');

		$this->load->model("Mt_risk_kajian_risikoModel","mtjeniskajian");

		$this->load->model("Mt_level_grcModel","mtlevelgrc");
		$this->data['mtlevelgrcarr'] = $this->mtlevelgrc->GetComboNew("", " status = 1");

		$this->load->model("Mt_pic_rthModel","mtpicrth");
		$this->data['mtpicrtharr'] = $this->mtpicrth->GetComboNew("", " status = 1");

		$this->load->model("Mt_folder_dokgrcModel","mtdokgrc");
		$this->data['mtdokgrcarr'] = $this->mtdokgrc->selectbyparamfolder("list");

		
	}

	public function daftarscorecard($id_kajian_risiko=0){

		if($id_kajian_risiko)
			redirect("panelbackend/mekanisme_grc/index/".$id_kajian_risiko);

		$owner = $this->data['owner'];

		$this->data['kajiankuarr'] = array();

		if($owner)
			$this->data['kajiankuarr'] = array_keys($this->conn->GetList("select distinct id_kajian_risiko as key, 1 as val from risk_scorecard where owner=".$this->conn->escape($owner)." order by id_kajian_risiko"));

	
		$this->viewlist = "panelbackend/mekanisme_grcindex";
		$this->View($this->viewlist);
	}

	private function goSync($id_kajian_risiko=0, $id_parent_scorecard=0){
		$pos = array();
		$return = $this->reqpromis("get_proyek", $pos);
		$rows = $return['data'];

		$ret = (bool)$rows;

		$id_proyekarr = array();
		foreach($rows as $r){
			if(!$ret)
				break;

			list($tgl, $bln, $thn) = explode("-",$r['tgl_rencana_mulai']);

			$tgl_mulai_efektif = "01-01-".$thn;

			list($tgl1, $bln1, $thn1) = explode("-",$r['tgl_realisasi_selesai']);
			
			if(!$thn1)
				$thn1 = $thn;

			$tgl_realisasi_selesai = null;

			if($thn)
				$tgl_realisasi_selesai = "31-12-".$thn;

			$id_proyekarr[] = $r['id_proyek'];

			$record = array(
				'owner'=>21,
				'nama'=>$r['nama_proyek'],
				'id_proyek'=>$r['id_proyek'],
				'id_unit'=>'KP',
				'tgl_mulai_efektif'=>$tgl_mulai_efektif,
				'tgl_akhir_efektif'=>$tgl_realisasi_selesai,
				'id_parent_scorecard'=>$id_parent_scorecard,
				'id_kajian_risiko'=>$id_kajian_risiko,
				'id_status_proyek'=>$r['id_status_proyek'],
				'is_aktif'=>1,
				'id_visi_misi'=>1,
				'navigasi'=>0,
				'is_info'=>1,
			);

			if($r['id_status_proyek']==1){
				$this->_getKpi($r['id_proyek'] , $record);
			}

			$row = $this->conn->GetRow("select * from risk_scorecard where id_proyek = ".$this->conn->escape($r['id_proyek']));

			if($record['id_status_proyek']=='1' && $row['id_status_proyek']<>'1'){
				$record['on_spec'] = 100;
				$record['on_safety'] = 100;
			}

			if($row){
				$ret = $this->conn->goUpdate('risk_scorecard',$record, "id_proyek = ".$this->conn->escape($r['id_proyek']));
			}
			else{
				$ret = $this->conn->goInsert('risk_scorecard',$record);
			}

			/*if($ret)
				$ret = $this->getPekerjaan($r['id_proyek']);*/
		}

		if($id_proyekarr && $ret){
			$ret = $this->conn->goUpdate("risk_scorecard",array("tgl_akhir_efektif"=>"01-01-1970"),"id_parent_scorecard = ".$this->conn->escape($id_parent_scorecard)." and (id_proyek not in (".implode(", ",$id_proyekarr).") or id_proyek is null)");
		}


		if($ret){
			SetFlash("suc_msg","Sinkronisasi berhasil");
		}else{
			SetFlash("err_msg","Sinkronisasi gagal");
		}

		redirect(current_url());
	}

	private function _getKpi($id_proyek , &$rec){
		$pos = array();
		$pos['data']['id_proyek'] = $id_proyek;
		$return = $this->reqpromis("get_kpi", $pos);
		$rs = $return['data'];
		$rec['on_spec'] = round($rs['on_spec']);
		$rec['on_cost'] = round($rs['on_cost']);
		$rec['on_time'] = round($rs['on_time']);
		return $rec;
	}

	public function Index($id_kajian_risiko=0, $id_parent_scorecard=0, $id_filter=null){
		if(in_array($id_parent_scorecard, array(528, 527, 526))){
			unset($this->access_role['edit']);
			unset($this->access_role['delete']);
		}

		if($id_parent_scorecard == 528){
			$this->data['is_allow_tahun_efektif'] = true;
			$this->data['is_allow_tgl_efektif'] = false;
		}

		$this->_beforeDetail($id_kajian_risiko);

		if($this->post['act']=='sync'){
			$this->goSync($id_kajian_risiko, $id_parent_scorecard);
		}

		$this->data['id_kajian_risiko'] = $id_kajian_risiko;

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
		
		$this->data['tgl_efektif'] = $tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		if($this->data['is_allow_tahun_efektif'])
			$tahun_efektif = $_SESSION[SESSION_APP]['tahun_efektif'];

		$this->data['rows']=$this->model->GetList($id_kajian_risiko, $tgl_efektif, $id_parent_scorecard, false, $tahun_efektif, false, " and a.is_grc is not null");

		if($id_parent_scorecard == 528){
			foreach($this->data['rows'] as &$r){

				$r['jumlah_risiko'] = $this->conn->GetOne("select count(1) from risk_risiko where '$tgl_efektif' between nvl(tgl_risiko, '$tgl_efektif')and nvl(tgl_close-1,'$tgl_efektif') and id_scorecard = ".$this->conn->escape($r['id_scorecard']));
			}
		}

		$this->data['id_parent_scorecard'] = $id_parent_scorecard;
		$this->data['id_kajian_risiko'] = $id_kajian_risiko;

		$this->data['broadcrum'] = $this->model->GetComboParent($id_parent_scorecard);

		if($id_parent_scorecard==526){
			$where = null;
			if($id_kategori)
				$where = "where id_kategori = ".$this->conn->escape($id_filter);

			$rowskategori = $this->conn->GetArray("select * 
				from mt_pb_kategori
				$where
				order by kode");

			if($id_filter){
				$this->data['broadcrum'][0] = $rowskategori[0]['nama'];
			}

			$rows = $this->conn->GetArray("select * from mt_pb_kelompok_proses order by kode");
			foreach($rows as $r){
				$rowskelompok[$r['id_kategori']][$r['id_kelompok_proses']] = $r;
			}

			$rows = $this->conn->GetArray("select * from mt_pb_nama_proses order by kode");
			foreach($rows as $r){
				$rowsnama[$r['id_kelompok_proses']][$r['id_nama_proses']] = $r;
			}

			$rows = $this->data['rows'];
			foreach($rows as $r){
				$rowssc[$r['id_nama_proses']] = $r;
			}

			$this->data['rows'] = array();
			foreach($rowskategori as $r){
				$r['id'] = $r['id_kategori'].$id_parent_scorecard;
				$r['id_parent'] = $id_parent_scorecard;

				$this->data['rows'][] = $r;
				if($rowskelompok[$r['id_kategori']])
				foreach($rowskelompok[$r['id_kategori']] as $r1){
					$r1['id'] = $r1['id_kelompok_proses'].$r1['id_kategori'].$id_parent_scorecard;
					$r1['id_parent'] = $r1['id_kategori'].$id_parent_scorecard;

					$this->data['rows'][] = $r1;
					if($rowsnama[$r1['id_kelompok_proses']])
					foreach($rowsnama[$r1['id_kelompok_proses']] as $r2){
						$rn = $rowssc[$r2['id_nama_proses']];
						if($rn){
							$rn['id'] = $r2['id_nama_proses'].$r1['id_kelompok_proses'].$r1['id_kategori'].$id_parent_scorecard;
							$rn['id_parent'] = $r1['id_kelompok_proses'].$r1['id_kategori'].$id_parent_scorecard;

							$this->data['rows'][] = $rn;
						}
					}
				}
			}
		}

		$this->View($this->viewlist);
	}

	protected function Record($id=null){

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
		
		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		$nomor_ba_grc= null;
		if ($this->post['nomor_ba_grc_inp']!='') 
		{
			$nomor_ba_grc= $this->post['nomor_ba_grc_inp'].$this->post['nomor_ba_grc_din'];
		}

		$record = array(
			'navigasi'=>(int)$this->post['navigasi'],
			'id_unit'=>$this->post['id_unit'],
			'owner'=>$this->post['owner'],
			'nama'=>$this->post['nama'],
			'id_parent_scorecard'=>$this->post['id_parent_scorecard'],
			'tgl_mulai_efektif'=>$this->post['tgl_mulai_efektif'],
			'tgl_akhir_efektif'=>$this->post['tgl_akhir_efektif'],

			'nomor_ba_grc_inp'=>$this->post['nomor_ba_grc_inp'],
			'nomor_ba_grc_din'=>$this->post['nomor_ba_grc_din'],
			'nomor_ba_grc'=>$nomor_ba_grc,
			'tanggal_dok_ba'=>$this->post['tanggal_dok_ba'],
			'sasaran_kpi'=>$this->post['sasaran_kpi'],
			'klasifikasi_program'=>$this->post['klasifikasi_program'],
			'estimasi_biaya'=>$this->post['estimasi_biaya'],
			'sumber_dana'=>$this->post['sumber_dana'],
			'keterangan'=>$this->post['keterangan'],
			'hasil_keputusan_radir'=>$this->post['hasil_keputusan_radir'],
			'id_level_grc'=>$this->post['id_level_grc'],
			'divisi_pemrakarsa_id'=>$this->post['divisi_pemrakarsa_id'],
			'id_pic_rth'=>$this->post['id_pic_rth'],
			'id_status_progress_grc'=>$this->post['id_status_progress_grc'],
			'progress_mitigasi'=>$this->post['progress_mitigasi'],
			'is_grc'=>1,
			'reqdatatable'=>$this->post['reqdatatable'],
		);

		if(!$this->access_role['view_all_direktorat']){
			unset($record['owner']);
			unset($record['id_kajian_risiko']);
			unset($record['nama']);
			unset($record['id_unit']);
			unset($record['on_spec']);
			unset($record['on_cost']);
			unset($record['on_time']);
			unset($record['on_safety']);

		}

		return $record;
	}

	protected function Rules(){
		$return = array(
			// "scope"=>array(
			// 	'field'=>'scope',
			// 	'label'=>'Scope',
			// 	'rules'=>"required|max_length[4000]",
			// ),
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Nama Scorecard',
				'rules'=>"required|max_length[300]",
			),
			"owner"=>array(
				'field'=>'owner',
				'label'=>'Owner',
				'rules'=>"callback_inlistjabatan",
			),
			"id_unit"=>array(
				'field'=>'id_unit',
				'label'=>'Unit',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['unitarr']))."]"
			),
			"id_level_grc"=>array(
				'field'=>'id_level_grc',
				'label'=>'Unit',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtlevelgrcarr']))."]"
			),
			"tgl_mulai_efektif"=>array(
				'field'=>'tgl_mulai_efektif', 
				'label'=>'Tgl. Mulai Efektif', 
				'rules'=>"required",
			),
			"nomor_ba_grc_inp"=>array(
				'field'=>'nomor_ba_grc_inp', 
				'label'=>'Nomor Berita Acara GRC', 
				'rules'=>"required",
			),
			// "id_nama_proses"=>array(
			// 	'field'=>'id_nama_proses',
			// 	'label'=>'Nama Proses',
			// 	'rules'=>"in_list[".implode(",", array_keys($this->data['mtpbnamaprosesarr']))."]"
			// ),
			// "id_status_proyek"=>array(
			// 	'field'=>'id_status_proyek',
			// 	'label'=>'Status Proyek',
			// 	'rules'=>"in_list[".implode(",", array_keys($this->data['mtstatusproyekarr']))."]"
			// ),
		);

		if($this->data['row']['navigasi']){
			unset($return['owner']);
			unset($return['id_unit']);
			unset($return['scope']);
			unset($return['id_level_grc']);
			unset($return['nomor_ba_grc_inp']);
		}


		if(!$this->access_role['view_all_direktorat']){
			unset($return['owner']);
			unset($return['nama']);
			unset($return['id_unit']);
			unset($return['tgl_mulai_efektif']);
		}
		
		return $return;
	}

    public function inlistjabatan($str)
    {
    	if(!$str)
    		return true;
    		
		$result = $this->mjabatan->GetCombo($str);

    	if(!$result[$str]){
            $this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
            return FALSE;
    	}

    	return true;
    }
	public function Add($id_kajian_risiko=null){
		$this->Edit($id_kajian_risiko);
	}

	public function Edit($id_kajian_risiko=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_kajian_risiko, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if(($this->data['row']['id_jabatan'])){

			$id_mtsdmjabatanarr = $this->data['row']['id_jabatan'];
			$id_jabatanstr = "'".implode("','", $id_mtsdmjabatanarr)."'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where id_jabatan in ($id_jabatanstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['mtsdmjabatanarr'][$r['id_jabatan']] = $r['nama'];
			}
		}

		if (!$this->data['row'] && $id)
			$this->NoData();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);

			if($this->data['row']['owner'] && $id_kajian_risiko==3){
				$rekom_nama = $this->conn->GetOne("select case when subdit_ket is not null then 'DIVISI ' || subdit_ket else 'DIREKTORAT ' || direktorat_ket end from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($this->data['row']['owner']));
			}
			if($this->data['row']['id_nama_proses']){
				$kode = $this->conn->GetOne("select nvl(a.kode,'')
					from mt_pb_nama_proses a 
					where a.id_nama_proses = ".$this->conn->escape($this->data['row']['id_nama_proses']));
				$rekom_nama = $kode.' '.$this->data['mtpbnamaprosesarr'][$this->data['row']['id_nama_proses']];
			}

			if($this->data['row']['nama']<>$rekom_nama && $rekom_nama)
				$this->data['row']['nama'] = $rekom_nama;
		}

		$record['id_kajian_risiko'] = $this->data['row']['id_kajian_risiko'] = $id_kajian_risiko;

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

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

				$return = $this->_beforeInsert($record);

				if($return){
					if(!$_SESSION[SESSION_APP]['tgl_efektif'])
						$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
					
					$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

					$record['id_visi_misi']=$this->conn->GetOne("select id_visi_misi from risk_visi_misi where '$tgl_efektif' between nvl(tgl_mulai_efektif,'$tgl_efektif')and nvl(tgl_akhir_efektif,'$tgl_efektif')");

					if(!$record['id_visi_misi']){
						$this->data['err_msg'] = "Visi misi pada tanggal efektif belum di isi. ";
						$return = false;
					}
					else
						$return = true;
				}

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

            $this->model->conn->CompleteTrans();

			if ($return['success']) {

				$this->_afterEditSucceed($id);

				if(!$this->post['navigasi']){
					$this->ctrl = 'risk_risiko_grc';
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id_kajian_risiko/$id");
				}else{
					SetFlash('suc_msg', $return['success']);
					if($this->post['id_parent_scorecard'])
						redirect("$this->page_ctrl/index/$id_kajian_risiko/{$this->post['id_parent_scorecard']}");
					else
						redirect("$this->page_ctrl/index/$id_kajian_risiko/$id");
				}

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeEdit(&$record=array(), $id)
	{
		$nomor_ba_grc_inp= $record['nomor_ba_grc_inp'];
		if ($nomor_ba_grc_inp && $this->mode=='add') 
		{
			$cek = $this->conn->GetOne("select 1 from risk_scorecard where nomor_ba_grc_inp = ".$this->conn->escape($nomor_ba_grc_inp));

			if ($cek) 
			{
				$this->data['err_msg'] = "Nomor Berita Acara GRC terdapat kesamaan nomor dalam database. Silahkan input dengan nilai yang berbeda. ";
				$this->data['row']= $record;
				$this->_afterDetail($id);
				$this->View($this->viewdetail);
				die();
			}
		}
	}

	protected function _beforeDelete($id_kajian_risiko = null, $id=null){

		if(!$this->access_role['delete'])
			return false;

		$cek = $this->conn->GetOne("select 1 from risk_risiko where status_risiko <> '2' and id_scorecard = ".$this->conn->escape($id));

		if($cek){
			$this->ctrl = 'risk_risiko_grc';
			SetFlash('err_msg',"Data tidak bisa dihapus karena masih ada risiko yang belum dihapus");
			redirect("$this->page_ctrl/detail/$id_kajian_risiko/$id");
			die();
		}

		$cek = $this->conn->GetListStr("select 
			case when tgl_akhir_efektif is not null then 
			nama ||' yang 
			efektif '||tgl_mulai_efektif||' sampai '||tgl_akhir_efektif
			else
			nama end as val 
			from risk_scorecard where id_parent_scorecard = ".$this->conn->escape($id));

		if($cek){
			$this->ctrl = 'risk_risiko_grc';
			SetFlash('err_msg',"Data tidak bisa dihapus karena masih ada scorecard ".$cek." dibawahnya, silahkan hapus terlebih dahulu");
			redirect("$this->page_ctrl/detail/$id_kajian_risiko/$id");
			die();
		}

		$id_scorecard = $id;

		$ret = $this->conn->Execute("delete from risk_log where id_scorecard=".$this->conn->escape($id));
		if($ret){
			$rowsrisiko = $this->conn->GetArray("select id_risiko from risk_risiko where status_risiko = '2' and id_scorecard = ".$this->conn->escape($id));

			foreach($rowsrisiko as $ror){
				if(!$ret)
					return $ret;
				
				$id = $ror['id_risiko'];

				$this->conn->Execute("delete from risk_task where id_risiko = ".$this->conn->escape($id));
				$this->conn->Execute("delete from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($id));
				$this->conn->Execute("delete from risk_risiko_dampak where id_risiko = ".$this->conn->escape($id));

				$this->conn->Execute("delete from risk_review where id_risiko = ".$this->conn->escape($id));

				$rows = $this->conn->GetRows("select id_mitigasi from risk_mitigasi where id_risiko = ".$this->conn->escape($id));
				foreach ($rows as $r) {
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
				$ret = $this->conn->Execute("delete from risk_risiko where id_risiko = ".$this->conn->escape($id));
			}
		}

		if($ret)
			$ret = $this->conn->Execute("delete from risk_scorecard_view where id_scorecard = ".$this->conn->escape($id_scorecard));

		$full_path = $this->data['configfile']['upload_path']."scorecard_proses".$id.'.'.ext($row['proses']);
		@unlink($full_path);

		$full_path = $this->data['configfile']['upload_path']."scorecard_template_laporan".$id.'.'.ext($row['template_laporan']);
		@unlink($full_path);

		return $ret;
	}

	public function Delete($id_kajian_risiko=null, $id=null){

        $this->model->conn->StartTrans();

		$this->_beforeDetail($id_kajian_risiko, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id_kajian_risiko, $id);

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
			redirect("$this->page_ctrl/index/$id_kajian_risiko");
		}
		else {
			$this->ctrl = 'risk_risiko_grc';
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_kajian_risiko/$id");
		}

	}

	function _uploadFile($id=null){
		if(!$_FILES['template_laporan']['name'])
			return true;

		$return = array('success'=>true);

		if($_FILES['template_laporan']['name']){

			$this->data['configfile']['file_name'] = "scorecard_template_laporan".$id;
			$this->data['configfile']['allowed_types'] = "doc|docx";
			$this->load->library('upload', $this->data['configfile']);
			$this->upload->overwrite = true;

	        if ( ! $this->upload->do_upload('template_laporan'))
	        {
	            $return = array('error' => $this->upload->display_errors());
	        }
	        else
	        {
	    		$upload_data = $this->upload->data();
	            $return = array('success' => "Upload ".$upload_data['client_name']." berhasil");

				$record = array();
				$ret = $this->conn->Execute("update risk_scorecard set template_laporan = ".$this->conn->escape($upload_data['client_name'])." where id_scorecard = ".$this->conn->escape($id));

				if(!$ret)
				{
					@unlink($upload_data['full_path']);
					$return["success"] = false;
					$return["error"] = "Upload berhasil";
				}

	        }
	    }

		if ($return['success']) {

			SetFlash('suc_msg', $return['success']);

			$this->post['act'] = 'save';

			return true;

		}else {
			SetFlash('err_msg', $return['error']);
			redirect(current_url());
		}

	}

	function delete_file($id=null){
		$row = $this->model->GetByPk($id);
		if(!$row['template_laporan'])
			$this->Error404();

		$return = $this->conn->Execute("update risk_scorecard set template_laporan = null where id_scorecard = ".$this->conn->escape($id));;

		if ($return) {
			$full_path = $this->data['configfile']['upload_path']."scorecard_template_laporan".$id.'.'.ext($row['template_laporan']);
			@unlink($full_path);

			SetFlash('suc_msg', "File berhasil dihapus");
		}


		if(!$return){
			SetFlash('err_msg',"Data gagal didelete");
		}
		redirect("panelbackend/mekanisme_grc/edit/$row[id_kajian_risiko]/$id");
	}

	function preview_file($id,$is_pdf=false){
		$row = $this->model->GetByPk($id);

		if($is_pdf){
			if(!$row['proses'])
				die();

			$full_path = $this->data['configfile']['upload_path']."scorecard_proses".$id.'.'.ext($row['proses']);
			header("Content-Type: application/pdf");
			header("Content-Disposition: inline; filename='{$row['proses']}'");
			echo file_get_contents($full_path);

			die();
		}
		if(!$row['template_laporan'])
			$this->Error404();

		$full_path = $this->data['configfile']['upload_path']."scorecard_template_laporan".$id.'.'.ext($row['template_laporan']);
		header("Content-Type: application/msword");
		header("Content-Disposition: inline; filename='{$row['template_laporan']}'");
		echo file_get_contents($full_path);
		die();
	}


	public function Detail($id_kajian_risiko=null, $id=null){

		$this->_beforeDetail($id_kajian_risiko, $id);

		$this->data['row'] = $this->model->GetByPk($id);

		if($this->data['row']['owner'])
			redirect('/panelbackend/risk_risiko_grc/index/'.$id);
		else if($this->data['row']['navigasi'])
			redirect('/panelbackend/mekanisme_grc/index/'.$id_kajian_risiko."/".$id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);


		$this->View($this->viewdetail);
	}

	protected function _afterDetail($id){
		$this->data['editedheader'] = $this->data['edited'];

		$this->data['rowheader'] = $this->data['row'];

		if($this->data['rowheader']['id_unit']){
			$this->data['ownerarr'] = $this->conn->GetList("select 
				id_jabatan as key, 
				nama||' - '||position_id||' ('||nvl(id_unit,'')||')' as val
				from mt_sdm_jabatan a
				where id_unit = ".$this->conn->escape($this->data['rowheader']['id_unit']));
		}

		if($this->data['rowheader']['owner'])
			$owner = $this->data['rowheader']['owner'];

		if($owner)
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));


		if(!($this->data['row']['id_jabatan'])){
			$id_jabatanarr = array();

			$mtsdmjabatanarr = $this->conn->GetArray("select id_jabatan from risk_scorecard_view where id_scorecard = ".$this->conn->escape($id));

			foreach ($mtsdmjabatanarr as $key => $value) {
				$id_jabatanarr[]=$value['id_jabatan'];
			}

			$this->data['row']['id_jabatan'] = $id_jabatanarr;
		}

		if(($this->data['row']['id_jabatan'])){

			$id_mtsdmjabatanarr = $this->data['row']['id_jabatan'];
			$id_jabatanstr = "'".implode("','", $id_mtsdmjabatanarr)."'";

			$mtsdmjabatanarr = $this->conn->GetArray("select * from mt_sdm_jabatan where id_jabatan in ($id_jabatanstr)");
			foreach ($mtsdmjabatanarr as $r) {
				$this->data['mtsdmjabatanarr'][$r['id_jabatan']] = $r['nama'];
			}
		}

		$id_level_grc= $this->data['row']['id_level_grc'];

		$statement= $statementjoin= "";
		if ($id_level_grc!='' && $this->mode=='add') 
		{
			$statementjoin= " and b.id_level_grc = ".$id_level_grc;

			$rowsdetil= $this->mtlevelgrc->selectbyparamfolderlevelgrc($statement, $statementjoin, "array");
			$infojumlahdata= count($rowsdetil);
			$this->data['row']['reqtable']= $infojumlahdata;
			if($infojumlahdata > 0)
			{
				// print_r($rowsdetil);exit;
				foreach($rowsdetil as $key=>$v)
				{
					$inforowid= $v['id_level_grcdetil'];
					$statuschecked= "";
					if(!empty($inforowid))
						$statuschecked= "1";

					$this->data['row']['reqdatatable'][$key]['statuschecked']= $statuschecked;
					// $this->data['row']['reqdatatable'][$key]['id_pengirimantooldetil']= $inforowid;
					$this->data['row']['reqdatatable'][$key]['id_dok_pendukung_grc']= $v['id_dok_pendukung_grc'];
				}
			}
		}

		if ($id) 
		{
			$statementjoin= " and b.id_scorecard = ".$id;	

			$rowsdetil= $this->mtlevelgrc->selectbyparamfolderscorecard($statement, $statementjoin, "array");
			$infojumlahdata= count($rowsdetil);
			$this->data['row']['reqtable']= $infojumlahdata;
			if($infojumlahdata > 0)
			{
				// print_r($rowsdetil);exit;
				foreach($rowsdetil as $key=>$v)
				{
					$inforowid= $v['id_scorecard_folder'];
					$statuschecked= "";
					if(!empty($inforowid))
						$statuschecked= "1";

					$this->data['row']['reqdatatable'][$key]['statuschecked']= $statuschecked;
					// $this->data['row']['reqdatatable'][$key]['id_pengirimantooldetil']= $inforowid;
					$this->data['row']['reqdatatable'][$key]['id_dok_pendukung_grc']= $v['id_dok_pendukung_grc'];
				}
			}
		}

		
		// if($this->data['row']['reqdatatable'])
		// {
		// 	// $rowsdetil= $this->data['row']['reqdatatable'];
		// 	// foreach($rowsdetil as $key=>$v)
		// 	// {
		// 	// 	// untuk no katalog arr sesuai dengan grouptool terpilih
		// 	// 	$idkatalogtool= $v['id_katalogtool'];
		// 	// 	if ($idkatalogtool) 
		// 	// 	{
		// 	// 		$infosql= "select id_grouptool from mt_katalogtool where id_katalogtool = ".$this->conn->escape($idkatalogtool);
		// 	//     	$datanya= $this->conn->GetRow($infosql);
		// 	//     	$this->data['row']['reqdatatable'][$key]['id_grouptool']= $datanya['id_grouptool'];

		// 	//     	$this->data['mtkatalogtoolgeneralarr']['reqdatatable'][$key] = $this->mtkatalogtool->selectbyparamkatalogtoolowner(null, "and a.is_delete = '0' and a.id_grouptool = ".$datanya['id_grouptool'], "list", $idlokasikatalog);
		// 	// 	}
		// 	// }
		// }
		// else
		// {
			
		// }
		
		// print_r($this->data['row']);exit;
	}

	protected function _beforeInsert($record){
		$this->riskchangelog($record);

		return true;
	}

	protected function _beforeUpdate($record=array(), $id=null){

		$row = $this->model->GetByPk($id);

		$this->riskchangelog($record, $row);

		return true;
	}

	protected function _beforeDetail($id_kajian_risiko=null, $id=null){

		if(!$id_kajian_risiko)
			redirect("panelbackend/mekanisme_grc/daftarscorecard");

		$this->data['scorecardarr'] = $this->model->GetComboGrc(null,null,null,$id_kajian_risiko);

		$this->data['add_param'] .= $id_kajian_risiko;

		$datajeniskajian= $this->mtjeniskajian->selectbyparamkajianrisikodetil($id_kajian_risiko, "", " and a.status_aktif = 1");
		$this->data['no_dinamis']="";
		if ($datajeniskajian['no_dinamis']) 
		{
			$this->data['no_dinamis']= $datajeniskajian['no_dinamis'];
		}
	}

	protected function _afterUpdate($id){
		$ret = $this->_delSertView($id);

		return $ret;
	}

	protected function _afterInsert($id){
		$ret = $this->_delSertView($id);

		return $ret;
	}

	private function _delSertView($id){
		$return = $this->conn->Execute("delete from risk_scorecard_view where id_scorecard = ".$this->conn->escape($id));

		if(is_array($this->post['id_jabatan'])){
			foreach ($this->post['id_jabatan'] as $key => $value) {
				if($return){
					if(!$value)
						continue;

					$record = array();
					$record['id_scorecard'] = $id;
					$record['id_jabatan'] = $value;

					$sql = $this->conn->InsertSQL("risk_scorecard_view", $record);

	        		if($sql){
					    $return = $this->conn->Execute($sql);
					}
				}
			}
		}

		if($return)
			$return = $this->_uploadFile($id);

		return $return;
	}

	protected function _afterEditSucceed($id=null)
	{
		$ret = true;
		
		if($ret)
		{
			$ret = $this->_insertdetil($id);
			// $ret.= $this->_delsertFiles($id);
		}
		
		return $ret;
	}

	private function _insertdetil($id = null)
	{
		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}
		// print_r($record);exit;

		// main hapus all, selama masih draft atau return
		$ret = $this->conn->Execute("delete from risk_scorecard_folder where id_scorecard = ".$this->conn->escape($id));

		$infodatatable= $record['reqdatatable'];
		if(!empty($infodatatable)){
			$this->setinsertdetil($id, $infodatatable);
		}

		return $ret;
	}

	function setinsertdetil($id, $infodatatable)
	{
		foreach ($infodatatable as $key => $v) {
			$statuschecked= $v['statuschecked'];

			$recorddetil = array();
			$recorddetil['id_scorecard']= $id;
			$recorddetil['id_dok_pendukung_grc']= $v['id_dok_pendukung_grc'];

			if($statuschecked == "1")
			{
				$ret = $this->conn->goInsert('risk_scorecard_folder', $recorddetil);
			}
		}
	}
}
