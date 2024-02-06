<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_evaluasi extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_evaluasilist";
		$this->viewdetail = "panelbackend/risk_evaluasidetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risiko';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Risiko';
		}

		$this->load->model("Risk_risikoModel","model");

		$this->load->model("Mt_risk_kriteria_dampakModel",'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nomor',
				'label'=>'Nomor',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama',
				'label'=>'Nama Risiko',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			// array(
			// 	'name'=>'current_risk',
			// 	'label'=>'Risiko Saat Ini',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['rowheader1'],
			// ),
			array(
				'name'=>'id_status_pengajuan',
				'label'=>'Status',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtstatusarr'],
			),
		);
	}

	protected function Record($id=null){

		$record =  array(
			'progress_capaian_kinerja'=>$this->post['progress_capaian_kinerja'],
			'progress_capaian_sasaran'=>$this->post['progress_capaian_sasaran'],
			'penyesuaian_tindakan_mitigasi'=>$this->post['penyesuaian_tindakan_mitigasi'],
			'hambatan_kendala'=>$this->post['hambatan_kendala'],
			'residual_dampak_evaluasi'=>$this->post['residual_dampak_evaluasi'],
			'residual_kemungkinan_evaluasi'=>$this->post['residual_kemungkinan_evaluasi'],
		);

		$record['id_status_pengajuan'] = 1;

		if($this->post['status_risiko']!=="" && $this->post['status_risiko']!==null){
			$record = array();
			$record['status_risiko'] = $this->post['status_risiko'];
			$record['status_keterangan'] = $this->post['status_keterangan'];
		}

		return $record;
	}

	protected function Rules(){
		$return = array(
			"residual_dampak_evaluasi"=>array(
				'field'=>'residual_dampak_evaluasi',
				'label'=>'Tingkat Dampak',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtdampakrisikoarr']))."]|required",
			),
			"residual_kemungkinan_evaluasi"=>array(
				'field'=>'residual_kemungkinan_evaluasi',
				'label'=>'Tingkat Kemungkinan',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtkemungkinanarr']))."]|required",
			),
			"progress_capaian_kinerja"=>array(
				'field'=>'progress_capaian_kinerja',
				'label'=>'Progress Capaian Kinerja',
				'rules'=>"required|max_length[200]",
			),
			/*"progress_capaian_sasaran"=>array(
				'field'=>'progress_capaian_sasaran',
				'label'=>'Progress Capaian Sasaran',
				'rules'=>"required|max_length[200]",
			),*/
			"penyesuaian_tindakan_mitigasi"=>array(
				'field'=>'penyesuaian_tindakan_mitigasi',
				'label'=>'Penyesuaian Tindakan Mitigasi',
				'rules'=>"max_length[4000]|required",
			),
			"hambatan_kendala"=>array(
				'field'=>'hambatan_kendala',
				'label'=>'Hambatan Kendala',
				'rules'=>"max_length[4000]|required",
			),
		);

		if($this->post['status_risiko']!==null){
			$return= array();
		}

		return $return;
	}

	public function Index($id_scorecard=null, $id=null){
		redirect("panelbackend/risk_evaluasi/detail/$id_scorecard/$id");
	}

	public function Add($id_scorecard = null){
		$this->Error403();
	}

	public function Edit($id_scorecard=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard, $id);
		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();
		
		$this->data['rowheader1'] = $this->data['row'];
		
		// $this->isLock();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change' && $this->post['act']<>'set_tgl_risiko'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);


			$this->data['row'] = array_merge($this->data['row'],$record);
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {
			$record['id_scorecard'] = $id_scorecard;

			$this->_isValid($record,false);

			if($this->post['status_risiko']!==null){
				if($this->post['tgl_risiko'])
					$record['tgl_close'] = $this->post['tgl_risiko'];
				else
					$record['tgl_close'] = date('d-m-Y');
				
				if($this->post['status_risiko']=='0'){
					if($this->post['tgl_close']){
						$record['tgl_close'] = $this->post['tgl_close'];
						$record['status_risiko'] = '0';
					}else{
						SetFlash('err_msg', "Tgl. Close tidak boleh kosong");
						redirect(current_url());
					}
				}
				elseif($this->post['status_risiko']=='2'){
					$record['status_risiko'] = '2';
					if(!$this->post['tgl_risiko']){
						SetFlash('err_msg', "Tgl. Risiko tidak boleh kosong");
						redirect(current_url());
					}
				}
			}

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

            	$this->model->conn->trans_commit();

				$this->_afterEditSucceed($id);

				if($record['status_risiko']===null)
					$this->backtodraft($id);

				SetFlash('suc_msg', $return['success']);
				if($this->data['id_risiko_new']){
					$id = $this->data['id_risiko_new'];
					$this->ctrl = 'risk_risiko';
					$id_scorecard_new = $this->data['id_scorecard_new'];
					redirect("panelbackend/risk_risiko/detail/$id_scorecard_new/$id");
				}else{
					redirect("$this->page_ctrl/detail/$id_scorecard/$id");
				}


			} else {

            	$this->model->conn->trans_rollback();
            	
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}


	public function Detail($id_scorecard=null,$id=null){
		$this->_beforeDetail($id_scorecard,$id);

		$this->data['row'] = $this->model->GetByPk($id);
		
		$this->data['rowheader1'] = $this->data['row'];
		
		//cek mitigasi open - tambahan erick
		//$this->load->model("Risk_mitigasiModel",'mitigasi');
		$this->data['mitigasi_open'] = $this->conn->GetRow("select count(*) as jml from risk_mitigasi where id_risiko=".$this->data['row']['id_risiko']." and not(is_control = '1' or is_close = '1')");
		
		// $this->isLock();

		if (!$this->data['row'])
			$this->NoData();

		if(!$this->data['row']['progress_capaian_kinerja'] && $this->access_role['edit']){
			redirect("panelbackend/risk_evaluasi/edit/$id_scorecard/$id");
			die();
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

		if(!$this->access_role['delete'])
			$this->Error403();

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

		if($this->post['act']=='status_risiko'){
			if(!$this->Access('close','panelbackend/risk_risiko'))
				$this->Error403();

			$this->post['act'] = 'save';
			$this->Edit($id, $id_risiko);
			return;
		}
		// if($this->post['act']=='reopen'){
		// 	$this->post['act'] = 'save';
		// 	$this->post['status_risiko'] = 1;
		// 	$this->Edit($id, $id_risiko);
		// 	return;
		// }

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel",'riskscorecard');
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

		$this->data['add_param'] .= $id."/".$id_risiko;
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
		
		$this->data['editedheader1'] = $this->data['edited'];

		$this->data['rowheader1'] = $this->data['row'];

		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['edited']);



		$tgl_risiko = $this->data['rowheader1']['tgl_risiko'];

		if($this->post['tgl_risiko'])
			$tgl_risiko = $this->post['tgl_risiko'];

		$this->data['scorecardarr'] = $this->riskscorecard->GetCombo(null,null,null,$this->data['rowheader']['id_kajian_risiko'], $tgl_risiko);

	}

	protected function _afterEditSucceed($id=null){
		if($this->post['status_risiko']===null)
			return true;

		$id_scorecard = $this->data['row']['id_scorecard'];
		$id_risiko = $this->data['row']['id_risiko'];

		if($this->post['status_risiko']=='2'){
			$id_risiko_new = $this->data['id_risiko_new'];
			$id_scorecard_new = $this->data['id_scorecard_new'];
			$record = array(
				'page'=>'risiko',
				'deskripsi'=>"Risiko berlanjut, silahkan lengkapi kembali data risiko Anda",
				'id_status_pengajuan'=>4,
				'id_risiko'=>$id_risiko_new,
				'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko_new"
			);
		}else{
			$record = array(
				'page'=>'risiko',
				'deskripsi'=>"Risiko closed",
				'id_status_pengajuan'=>5,
				'id_risiko'=>$id_risiko,
				'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
			);
		}

		$this->InsertTask($record);
	}

	protected function _beforeEdit(&$record=array(), $id){
		$this->_validAccessTask('panelbackend/risk_risiko',$this->data['row'],$this->data['edited']);

		if($this->post['status_risiko']=='2'){
			return $this->RisikoBerlanjut($id);
		}

		return true;
	}

	protected function _beforeDelete($id){
		$this->_validAccessTask('panelbackend/risk_risiko',$this->data['row'],$this->data['edited']);
		return true;
	}

	protected function _afterUpdate($id){
		return true;
	}

	private function RisikoBerlanjut($id){

		$risiko = $this->conn->GetRow("select * 
			from risk_risiko 
			where id_risiko=".$this->conn->escape($id));

		$id_scorecard_old = $risiko['id_scorecard'];

		if($risiko['status_risiko']=='2')
			return true;

		$return['success'] = false;

		$this->load->model("Risk_risikoModel","mrisiko");
		$this->load->model("Risk_risiko_filesModel","mrisikofiles");
		$this->load->model("Risk_controlModel","mcontrol");
		$this->load->model("Risk_control_efektifitasModel","mcontrolefektifitas");
		$this->load->model("Risk_control_efektifitas_filesModel","mcontrolefektifitasfiles");
		$this->load->model("Risk_mitigasiModel","mmitigasi");
		$this->load->model("Risk_mitigasi_filesModel","mmitigasifiles");

		$this->conn->StartTrans();

		if(($this->post['id_scorecard']) && is_array($this->post['id_scorecard'])){

			$return['success'] = true;

			$control_dampak_penurunan = $risiko['residual_dampak_evaluasi'];
			$control_kemungkinan_penurunan = $risiko['residual_kemungkinan_evaluasi'];

			foreach($this->post['id_scorecard'] as $id_scorecard){
				if(!$return['success'])
					break;

				$risiko['id_scorecard'] = $id_scorecard;

				list($tgl,$bulan,$tahun) = explode("-",$risiko['tgl_risiko']);

				$thnsekarang = date("Y");

				$this->_setLogRec($risiko);

				unset($risiko['tgl_close']);

				$risiko['tgl_risiko'] = "01-01-".($thnsekarang==$tahun?$thnsekarang+1:$thnsekarang);
				
				if($this->post['tgl_risiko'])
					$risiko['tgl_risiko'] = $this->post['tgl_risiko'];

				list($tgl,$bulan,$tahun1) = explode("-",$risiko['tgl_risiko']);

				if($tahun==$tahun1){
					$format = $this->mrisiko->getNomorRisiko($risiko['id_scorecard'], $risiko['id_sasaran_strategis'], $risiko['tgl_risiko'], $risiko['kode_aktifitas'],true);
					$nomor = trim(str_replace($format, "", $risiko['nomor']));
					list($nomor, $anti) = explode(".", $nomor);
					$anti = (int)$anti+1;
					$risiko['nomor'] = $format.' '.$nomor.".".$anti;
				}else
					$risiko['nomor'] = $risiko['nomor_asli'] = $this->mrisiko->getNomorRisiko($risiko['id_scorecard'], $risiko['id_sasaran_strategis'], $risiko['tgl_risiko'], $risiko['kode_aktifitas']);

				$risiko['id_status_pengajuan'] = 1;
				$risiko['control_dampak_penurunan'] = $control_dampak_penurunan;
				$risiko['control_kemungkinan_penurunan'] = $control_kemungkinan_penurunan;
				$risiko['id_risiko_sebelum'] = $id;

				unset($risiko['residual_dampak_evaluasi']);
				unset($risiko['residual_kemungkinan_evaluasi']);
				unset($risiko['progress_capaian_kinerja']);
				unset($risiko['progress_capaian_sasaran']);
				unset($risiko['hambatan_kendala']);
				unset($risiko['penyesuaian_tindakan_mitigasi']);

				$return = $this->mrisiko->Insert($risiko);

				$id_risiko = $return['data']['id_risiko'];

				$owner = $this->conn->GetOne("select owner from risk_scorecard where id_scorecard = ".$this->conn->escape($id_scorecard));

				$this->data['id_risiko_new'] = $id_risiko;
				$this->data['id_scorecard_new'] = $risiko['id_scorecard'];

				if($return['success'] && $id_risiko){
					$risikofiles = $this->conn->GetArray("select * from risk_risiko_files 
						where id_risiko = ".$this->conn->escape($id));

					foreach($risikofiles as $risikofile){
						if(!$return['success'])
							break;

						$this->_setLogRec($risikofile);
						$risikofile['id_risiko'] = $id_risiko;

						$return = $this->mrisikofiles->Insert($risikofile);
					}
				}

				if($id_risiko && $return['success']){
					$controls = $this->conn->GetArray("select * from
					risk_control 
					where id_risiko=".$this->conn->escape($id));

					foreach ($controls as $control) {
						if(!$return['success'])
							break;

						$id_control_old = $control['id_control'];

						$this->_setLogRec($control);

						$penanggung_jawab = $this->conn->GetOne("select id_jabatan from
						mt_sdm_jabatan
						where id_jabatan = ".$this->conn->escape($control['penanggung_jawab']));

						if($penanggung_jawab)
							$owner = $penanggung_jawab;
						
						$control['penanggung_jawab'] = $owner;
						$control['id_risiko'] = $id_risiko;
						$mitigasi['id_control_sebelum'] = $id_control_old;

						$return = $this->mcontrol->Insert($control);

						$id_control =  $return['data']['id_control'];

						if($return['success'] && $id_control){
							$efektifitass = $this->conn->GetArray("select * from risk_control_efektifitas 
								where id_control = ".$this->conn->escape($id_control_old));

							foreach($efektifitass as $efektifitas){
								if(!$return['success'])
									break;

								$this->_setLogRec($efektifitas);
								$efektifitas['id_control'] = $id_control;

								$return = $this->mcontrolefektifitas->Insert($efektifitas);
							}

							if($return['success'] && $id_control){
								$efektifitasfiles = $this->conn->GetArray("select *
									from risk_control_efektifitas_files 
									where id_control=".$this->conn->escape($id_control_old));

								foreach ($efektifitasfiles as $efektifitasfile) {
									if(!$return['success'])
										break;

									$this->_setLogRec($efektifitasfile);
									$efektifitasfile['id_control'] = $id_control;

									$return = $this->mcontrolefektifitasfiles->Insert($efektifitasfile);
								}
							}
						}
					}
				}
				
				if($id_risiko && $return['success']){
					$mitigasis = $this->conn->GetArray("select *
						from risk_mitigasi 
						where (is_close <> '1' or is_close is null)
						and id_risiko=".$this->conn->escape($id));

					foreach ($mitigasis as $mitigasi) {
						if(!$return['success'])
							break;

						$id_mitigasi_old = $mitigasi['id_mitigasi'];

						$this->_setLogRec($mitigasi);
						$mitigasi['id_risiko'] = $id_risiko;

						$penanggung_jawab = $this->conn->GetOne("select id_jabatan from
						mt_sdm_jabatan
						where id_jabatan = ".$this->conn->escape($mitigasi['penanggung_jawab']));

						if($penanggung_jawab)
							$owner = $penanggung_jawab;

						$mitigasi['penanggung_jawab'] = $owner;
						$mitigasi['id_mitigasi_sebelum'] = $id_mitigasi_old;

						$return = $this->mmitigasi->Insert($mitigasi);

						$id_mitigasi =  $return['data']['id_mitigasi'];

						if($return['success'] && $id_mitigasi){
							$mitigasifiles = $this->conn->GetArray("select * from risk_mitigasi_files 
								where id_mitigasi = ".$this->conn->escape($id_mitigasi_old));

							foreach($mitigasifiles as $mitigasifile){
								if(!$return['success'])
									break;

								$this->_setLogRec($mitigasifile);
								$mitigasifile['id_mitigasi'] = $id_mitigasi;

								$return = $this->mmitigasifiles->Insert($mitigasifile);
							}
						}
					}
				}
			}
		}
		
		$this->conn->CompleteTrans();
		if(!$return['success']){
			SetFlash('err_msg', "Proses berlanjut gagal");
			redirect("$this->page_ctrl/detail/$id_scorecard_old/$id");
		}
		return (boolean)$return['success'];
	}

	private function _setLogRec(&$record=array(), $is_edit = false){

		unset($record['created_date']);
		unset($record['created_by']);
		unset($record['modified_date']);
		unset($record['modified_by']);
		unset($record['is_lock']);
		unset($record['id_control']);
		unset($record['id_risiko']);
		unset($record['id_risiko_files']);
		unset($record['id_mitigasi']);
		unset($record['id_control_efektifitas_files']);
		unset($record['id_mitigasi_files']);

		$this->_setLogRecord($record, $is_edit);
	}

	/*
	protected function _afterUpdate($id){

		if($this->post['status_risiko']=='2'){
			$data = array();
			$data['risiko'] = $this->conn->GetRow("select r.id_scorecard, r.nomor, r.nama, r.deskripsi, r.inheren_dampak, r.inheren_kemungkinan, r.control_dampak_penurunan, r.control_kemungkinan_penurunan, r.penyebab, r.dampak, r.residual_target_dampak, r.residual_target_kemungkinan, r.residual_dampak_evaluasi, r.residual_kemungkinan_evaluasi,r.progress_capaian_sasaran, r.progress_capaian_kinerja, r.hambatan_kendala, r.penyesuaian_tindakan_mitigasi, r.status_risiko, r.status_keterangan, sk.nama as nsk, sk.kpi as ksk, ss.nama as nss, ss.kpi as kss, kd.nama as nk
				from risk_risiko r
				left join risk_sasaran_kegiatan sk on r.id_sasaran_kegiatan = sk.id_sasaran_kegiatan
				left join risk_sasaran_strategis ss on r.id_sasaran_strategis = ss.id_sasaran_strategis
				left join mt_risk_kriteria_dampak kd on r.id_kriteria_dampak = kd.id_kriteria_dampak
				where r.id_risiko=".$this->conn->escape($id));

			$data['scorecard'] = $this->conn->GetRow("select s.nama, s.scope, j.nama as nj, k.nama as nkr
				from risk_scorecard s
				left join mt_sdm_jabatan j on trim(s.owner) = trim(j.id_jabatan)
				left join mt_risk_kajian_risiko k on s.id_kajian_risiko = k.id_kajian_risiko
				where id_scorecard = ".$this->conn->escape($data['risiko']['id_scorecard']));


			$data['control'] = $this->conn->GetArray("select c.nama, c.deskripsi, c.remark, c.is_efektif, c.menurunkan_dampak_kemungkinan, i.nama as interval
				from risk_control c
				left join mt_interval i on c.id_interval = i.id_interval
				where id_risiko=".$this->conn->escape($id));
			
			$data['mitigasi'] = $this->conn->GetArray("select m.nama, m.deskripsi, m.dead_line, m.menurunkan_dampak_kemungkinan, m.biaya, m.revenue, m.is_efektif, m.cba, j.nama as jabatan, p.nama as status_progress
				from risk_mitigasi m
				left join mt_sdm_jabatan j on trim(m.penanggung_jawab) = trim(j.id_jabatan)
				left join mt_status_progress p on m.id_status_progress = p.id_status_progress
				where id_risiko=".$this->conn->escape($id));
			$tgl_close = date('d-m-Y');

			$record = array(
				'jenis_close'=>'2',
				'id_risiko'=>$id,
				'tgl_close'=>$tgl_close,
				'content'=>json_encode($data),
			);

            $this->_setLogRecord($record);

			$this->load->model("Risk_risiko_historyModel",'mhistory');

			$cek = $this->conn->GetOne("select 1 from risk_risiko_history where jenis_close = '2' and to_char(tgl_close,'DD-MM-YYYY') = '$tgl_close' and id_risiko = ".$this->conn->escape($id));

			if($cek){
				$return = $this->mhistory->Update($record, "jenis_close = '2' and to_char(tgl_close,'DD-MM-YYYY') = '$tgl_close' and id_risiko = ".$this->conn->escape($id));
			}else{
				$return = $this->mhistory->Insert($record);
			}

			if($return['success']){
				$ret = $this->conn->Execute("update risk_risiko set control_dampak_penurunan = residual_dampak_evaluasi, control_kemungkinan_penurunan = residual_kemungkinan_evaluasi, is_lock=0 where id_risiko = ".$this->conn->escape($id));

				$return['success'] = $ret;
			}

			return (boolean)$return['success'];
		}
		return true;
	} */
}
