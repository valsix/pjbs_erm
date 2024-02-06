<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_evaluasi_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_evaluasi_risikolist";
		$this->viewdetail = "panelbackend/risk_evaluasi_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";
		$this->data['page_title'] = 'Evaluasi Risiko';

		$this->load->model("Risk_risikoModel","model");
		$this->load->model("Risk_scorecardModel","modelscorecard");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->access_role['edit'] = true;
	}

	public function Index($page=0){

		if($this->post['act']=='get_mitigasi'){
			$id_risiko = $this->post['id_risiko'];

			$this->data['rows'] = $this->conn->GetArray("select a.nama, id_risiko, c.nama as jabatan, b.prosentase, b.nama as progress, a.dead_line
				from risk_mitigasi a
				join mt_status_progress b on a.id_status_progress = b.id_status_progress
				join mt_sdm_jabatan c on a.penanggung_jawab = c.id_jabatan
				where id_risiko = ".$this->conn->escape($id_risiko)." order by no");


			$this->PartialView("panelbackend/risk_mitigasiajax");
			exit();
		}

		if($this->post['act']=='save_all'){

			$this->conn->StartTrans();

			$this->load->model("Risk_risikoModel","mrisiko");
			$this->load->model("Risk_risiko_filesModel","mrisikofiles");
			$this->load->model("Risk_controlModel","mcontrol");
			$this->load->model("Risk_control_efektifitasModel","mcontrolefektifitas");
			$this->load->model("Risk_control_efektifitas_filesModel","mcontrolefektifitasfiles");
			$this->load->model("Risk_mitigasiModel","mmitigasi");
			$this->load->model("Risk_mitigasi_filesModel","mmitigasifiles");

			$post = $this->post;
			$ret = true;
			foreach($this->post['id_risiko'] as $id_risiko=>$id_risiko){
				if(!$ret)
					break;

				$post['status_risiko'] = $this->post['status_risiko_'.$id_risiko];

				if($post['status_risiko']===null)
					$post['status_risiko'] = '2';

				$post['tgl_risiko'] = $this->post['tgl_risiko_'.$id_risiko];
				$post['tgl_close'] = $this->post['tgl_close_'.$id_risiko];
				$post['id_scorecard_berlanjut'] = $this->post['id_scorecard_berlanjut_'.$id_risiko];

				if(!$post['tgl_risiko'] && !$post['tgl_close'])
					continue;

				$record = array();
				$record['status_risiko'] = $post['status_risiko'];
				$record['status_keterangan'] = $post['status_keterangan'];

				$record['tgl_close'] = date('d-m-Y');

				if($post['status_risiko']=='0'){
					$record["tgl_close"]=$post['tgl_close'];
				}else if($post['status_risiko']=='2'){
					$ret = $this->RisikoBerlanjut($id_risiko, $post);
				}

				if($ret)
					$ret = $this->conn->goUpdate("risk_risiko",$record," id_risiko = ".$this->conn->escape($id_risiko));

				if($ret){
					$row = $this->conn->GetRow("select * from risk_risiko where id_risiko = ".$this->conn->escape($id_risiko));

					$id_scorecard = $row['id_scorecard'];
					$id_risiko = $row['id_risiko'];

					if($post['status_risiko']=='2'){
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

					$ret = $this->InsertTask($record);
				}
			}

			if($ret){
				$this->conn->trans_commit();
				SetFlash("suc_msg","Berhasil");
			}
			else{
				$this->conn->trans_rollback();
				SetFlash("err_msg","Gagal");
			}

			redirect(current_url());
		}

		if($this->post['act']=='get_detail'){
			$id_risiko = $this->post['id_risiko'];

			$record = $this->post;
			unset($record['status_risiko']);
			unset($record['tgl_risiko']);
			unset($record['tgl_close']);
			$this->conn->goUpdate("risk_risiko", $record, "id_risiko = ".$this->conn->escape($id_risiko));

			$r = $this->conn->GetRow("select * from risk_risiko where id_risiko = ".$this->conn->escape($id_risiko));


			$r['kpi'] = $this->conn->GetListStr("select nama as val from risk_kpi a where exists (select 1 from risk_risiko_kpi b where a.id_kpi = b.id_kpi and b.id_risiko = ".$this->conn->escape($r['id_risiko']).")");
			$r['sasaran_strategis'] = $this->conn->GetOne("select nama from risk_sasaran_strategis where id_sasaran_strategis = ".$this->conn->escape($r['id_sasaran_strategis']));
			$r['sasaran_kegiatan'] = $this->conn->GetOne("select nama from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($r['id_sasaran_kegiatan']));

			$r['ratamitigasi'] = $this->conn->GetOne("select avg(PROSENTASE)
				from risk_mitigasi m
				join mt_status_progress p on m.id_status_progress = p.id_status_progress
				where id_risiko = ".$this->conn->escape($r['id_risiko']));

			$this->data['rowheader1'] = $r;

			$this->data['r'] = $r;

			$id_scorecard_child = $r['id_scorecard'];

			$this->data['rowheader'] = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($id_scorecard_child));

			if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard']){
				$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
				$this->data['scorecardchildarr'] = $this->modelscorecard->GetComboChild($id_scorecard, true, $this->post['tgl_risiko']);
			}

			$this->PartialView("panelbackend/risk_evaluasi_risikodetail");
			exit();
		}

		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');
		
		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];
		$top = 10;
		$id_kajian_risiko = null;
		$id_scorecard = null;
		$order = null;
		$id_scorecardarr = array();

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

		if($this->post)
			redirect(current_url());

		$id_scorecard_child = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard_child'];
		$id_scorecard = $_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'];
		$id_kajian_risiko = $_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'];

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_kajian_risiko'])
			$this->data['scorecardarr'] = $this->model->GetComboDashboard($id_kajian_risiko);

		if($_SESSION[SESSION_APP][$this->page_ctrl]['id_scorecard'])
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

		$this->data['rowheader'] = $this->conn->GetRow("select * from risk_scorecard where id_scorecard = ".$this->conn->escape($id_scorecard_child));

		$tgl_efektif = $_SESSION[SESSION_APP]['tgl_efektif'];

		if(!$id_scorecardarr)
			$id_scorecardarr = array(0);

		$param = array(
			'limit' => -1,
			'order' => $this->_order(),
			'filter' => "status_risiko = '1' and is_lock <> 0 and is_lock is not null and id_scorecard in (".implode(", ", $id_scorecardarr).") and '$tgl_efektif' between nvl(tgl_risiko, '$tgl_efektif')and nvl(tgl_close-1,'$tgl_efektif') "
		);

		$rows = $this->model->SelectGrid($param);

		$this->data['rows'] = $rows['rows'];

		$this->View($this->viewlist);
	}

	private function RisikoBerlanjut($id, $post=array()){

		$risiko = $this->conn->GetRow("select * 
			from risk_risiko 
			where id_risiko=".$this->conn->escape($id));

		$id_scorecard_old = $risiko['id_scorecard'];

		if($risiko['status_risiko']=='2')
			return true;

		$return['success'] = false;

		if(($post['id_scorecard_berlanjut']) && is_array($post['id_scorecard_berlanjut'])){

			$return['success'] = true;

			$control_dampak_penurunan = $risiko['residual_dampak_evaluasi'];
			$control_kemungkinan_penurunan = $risiko['residual_kemungkinan_evaluasi'];

			foreach($post['id_scorecard_berlanjut'] as $id_scorecard){
				if(!$return['success'])
					break;

				$risiko['id_scorecard'] = $id_scorecard;

				list($tgl,$bulan,$tahun) = explode("-",$risiko['tgl_risiko']);

				$thnsekarang = date("Y");

				$this->_setLogRec($risiko);

				unset($risiko['tgl_close']);

				$risiko['tgl_risiko'] = "01-01-".($thnsekarang==$tahun?$thnsekarang+1:$thnsekarang);
				
				if($post['tgl_risiko'])
					$risiko['tgl_risiko'] = $post['tgl_risiko'];

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
						where is_close <> '1' 
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
}