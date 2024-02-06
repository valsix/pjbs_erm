<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_control_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_control_grclist";
		$this->viewdetail = "panelbackend/risk_control_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard_grc";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Kontrol GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Kontrol GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Kontrol GRC';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Kontrol GRC';
			$this->data['edited'] = true;
		}

		$this->load->model("Risk_controlModel","model");

		$this->load->model("Risk_control_efektifitas_filesModel","modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->load->model("Mt_intervalModel","mtinterval");
		$mtinterval = $this->mtinterval;
		$this->data['mtintervalarr'] = $mtinterval->GetCombo();

		$this->load->model("Mt_risk_efektifitasModel","mtefektifitas");
		$mtefektifitas = $this->mtefektifitas;
		$this->data['mtefektifitasarr'] = $mtefektifitas->getKetEfektifitas();

		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");

		$this->data['penanggung_jawabarr'] = $this->mtsdmjabatan->GetCombo();

		$this->SetAccess(array('panelbackend/mekanisme_grc','panelbackend/risk_risiko_grc'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'myautocomplete','upload'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'no',
				'label'=>'No',
				'width'=>"18px",
				'nofilter'=>true,
				'type'=>"numeric",
			),
			array(
				'name'=>'nama',
				'label'=>'Nama Aktivitas Kontrol',
				'width'=>"auto",
				'field'=>"m_____nama",
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
				'width'=>"10px",
				'type'=>"list",
				'value'=>array(''=>'-pilih-')+$this->data['menurunkanrr'],
			),
			// array(
			// 	'name'=>'id_status_pengajuan',
			// 	'label'=>'Status',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['mtstatusarr'],
			// ),
			array(
				'name'=>'is_efektif',
				'label'=>'Efektif ?',
				'width'=>"10px",
				'type'=>"list",
				'value'=>array(''=>'-pilih-')+listefektifitas(),
			),
		);
	}

	protected function Record($id=null){
		$record = array(
			'no'=>$this->post['no'],
			'nama'=>$this->post['nama'],
			'penanggung_jawab'=>$this->post['penanggung_jawab'],
			'biaya'=>Rupiah2Number($this->post['biaya']),
			'deskripsi'=>$this->post['deskripsi'],
			'id_efektifitas'=>$this->post['id_efektifitas'],
			'id_control_parent'=>$this->post['id_control_parent'],
			'id_interval'=>$this->post['id_interval'],
			'id_taksonomi_control'=>$this->post['id_taksonomi_control'],
			'menurunkan_dampak_kemungkinan'=>$this->post['menurunkan_dampak_kemungkinan'],
			'remark'=>$this->post['remark'],
		);

		if(!$this->data['is_allow_edit_penanggung_jawab']){
			unset($record['penanggung_jawab']);
		}

		if(!$this->data['is_allow_edit_control']){
			unset($record['nama']);
			unset($record['biaya']);
			unset($record['deskripsi']);
			unset($record['id_interval']);
			unset($record['menurunkan_dampak_kemungkinan']);
			unset($this->post['nama']);
			unset($this->post['biaya']);
			unset($this->post['deskripsi']);
			unset($this->post['id_interval']);
			unset($this->post['menurunkan_dampak_kemungkinan']);
		}

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

    public function inlistjabatan($str)
    {
		$result = $this->mjabatan->GetCombo($str);

    	if(!$result[$str]){
            $this->form_validation->set_message('inlistjabatan', 'Jabatan tidak ditemukan');
            return FALSE;
    	}

    	return true;
    }

	protected function Rules(){
		$return = array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[4000]",
			),
			"id_interval"=>array(
				'field'=>'id_interval',
				'label'=>'Interval',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['mtintervalarr']))."]",
			),
			"id_control_parent"=>array(
				'field'=>'id_control_parent',
				'label'=>'Sub Dari',
				'rules'=>"in_list[".implode(",", array_keys($this->data['mtcontrolarr']))."]",
			),
			"penanggung_jawab"=>array(
				'field'=>'penanggung_jawab',
				'label'=>'Penanggung Jawab',
				'rules'=>"callback_inlistjabatan|required",
			),
			"menurunkan_dampak_kemungkinan"=>array(
				'field'=>'menurunkan_dampak_kemungkinan',
				'label'=>'Menurunkan',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['menurunkanrr']))."]",
			),
			"remark"=>array(
				'field'=>'remark',
				'label'=>'Remark',
				'rules'=>"max_length[4000]",
			),
		);
		if(!$this->data['is_allow_edit_penanggung_jawab']){
			unset($return['penanggung_jawab']);
		}

		if($this->access_role['review']){
			// $return['review_kepatuhan'] = array(
			// 	'field'=>'review_kepatuhan',
			// 	'label'=>'Review Kepatuhan',
			// 	'rules'=>"required",
			// );

		}

		if($this->access_role['rekomendasi']){
			// $return['rekomendasi_keterangan'] = array(
			// 	'field'=>'rekomendasi_keterangan',
			// 	'label'=>'Dasar Penetapan Risiko',
			// 	'rules'=>"required",
			// );

		}

		if($this->data['edited'] && $this->data['row']['is_lock'] && !$this->access_role_custom['panelbackend/risk_risiko_grc']['view_all_direktorat'])
			$return = true;

		return $return;
	}

	public function Index($id_risiko=null, $page=0, $edited=0){

		if($this->post['act']=='reset'){
			redirect("panelbackend/risk_control_grc/index/$id_risiko/0");
		}
		
		if($this->post['act']=='close' && $this->post['key']){
			$this->conn->Execute("update risk_control set is_close = 1 where id_control = ".$this->conn->escape($this->post['key']));
			redirect(current_url());
			exit();
		}

		$this->_beforeDetail($id_risiko);

		if($this->data['editedheader1'] && !$edited)
			$this->data['editedheader1'] = 0;

		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);
		foreach($this->data['list']['rows'] as &$r){
			$this->_getInfoLampiran($r);
		}
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$id_risiko"),
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
		
		$this->isLock();

		$this->View($this->viewlist);
	}
	public function Add($id_risiko = null){
		$this->Edit($id_risiko);
	}
		

	public function Edit($id_risiko=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$action_approve = false;
		if($this->post['act']=='approve_control'){
			$this->post['act']='save';
			$action_approve = true;
		}

		$this->_beforeDetail($id_risiko, $id, true);
		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		if($this->data['row']['no']==null)
			$this->data['row']['no'] = $this->model->GetNo($id_risiko);

		$this->data['is_allow_edit_penanggung_jawab'] = $this->isAllowEditPenanggungJawab();
		$this->data['is_allow_edit_control'] = $this->isAllowEditControl();
		$this->data['is_allow_edit_lampiran'] = $this->isAllowEditLampiran();

		if(
			$this->data['is_allow_edit_penanggung_jawab'] or 
			$this->data['is_allow_edit_control'] or 
			$action_approve or
			$this->data['is_allow_edit_lampiran']
		){
			$this->access_role['edit'] = true;
			$this->data['edited'] = true;
			$this->access_role_custom['panelbackend/risk_risiko_grc']['edit'] = true;
		}else{
			$this->access_role['edit'] = false;
			$this->access_role_custom['panelbackend/risk_risiko_grc']['edit'] = false;
			$this->data['edited'] = false;
		}

		if($this->data['row']['status_konfirmasi']=='1' && $this->isAccessInterdependent()){
			$this->access_role['delete'] = false;
		}

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("","");

		if($this->post && $this->post['act']<>'change'){
			if(!$this->data['row'])
				$this->data['row'] = array();

			$record = $this->Record($id);

			$this->data['row'] = array_merge($this->data['row'],$record);
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}
		$this->_onDetail($id);

		$this->data['rules'] = $this->Rules();

		if($this->post['act']=='set_value'){
			if(!$this->post['interdependent']){
				$this->data['row']['penanggung_jawab'] = $this->data['rowheader']['owner'];
			}
		}

		## EDIT HERE ##
		if ($this->post['act'] === 'save' or $this->post['act']=='save_rekomendasi' or $this->post['act']=='save_review') {

			if($this->data['row']['is_lock']=='2' && $this->data['row']['review_is_verified']<>'2' && $this->data['row']['rekomendasi_is_verified']<>'2')
				$record['is_lock'] = 1;

			$record['id_risiko'] = $id_risiko;

			if($this->post['act'] === 'save'){
				if($record['penanggung_jawab'] && $record['penanggung_jawab']<>$this->data['rowinterdependent']['penanggung_jawab']){

					if($this->isInterdependent(true))
						$record['status_konfirmasi'] = 0;
					else
						$record['status_konfirmasi'] = 1;
				}elseif($this->isInterdependent(true) && $this->data['rowinterdependent']['status_konfirmasi']=='2'){
					$record['status_konfirmasi'] = 0;
				}

				if($this->access_role_custom['panelbackend/risk_risiko_grc']['view_all_direktorat']){
					$record['status_konfirmasi'] = 1;
					$record['is_lock'] = 1;
				}
			}

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
				$is_insert = 1;
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

				$this->backtodraft($id_risiko);

				if($action_approve){
					$this->post['id_status_pengajuan']['control'] = 5;
					$this->post['id']['risiko'] = $id_risiko;
					$this->post['id']['control'] = $id;
					$this->post['keterangan']['control'] = "Interdependent menyetujui";

					$this->_actionKonfirmasiControl(true);
				}

				$addmsg = "";

				if($this->post['act']=='save_rekomendasi' && $this->access_role['rekomendasi']){
					$this->_notifRekomReview($id_risiko,$id);
					redirect("$this->page_ctrl/edit/$id_risiko/$id");
				}elseif($this->post['act']=='save_review' && $this->access_role['review']){
					$this->_notifRekomReview($id_risiko,$id);
					SetFlash('suc_msg', $return['success']);
					redirect("$this->page_ctrl/detail/$id_risiko/$id");
				}elseif($is_insert){
					$addmsg = '
					<script>$(function(){
						swal({
					        title: "Data berhasil disimpan",
					        text: "Apakah Anda ingin menambah kontrol lagi ?",
					        type: "success",
					        showCancelButton: true,
					        confirmButtonColor: "#2b982b",
					        confirmButtonText: "Iya",
					        cancelButtonText: "Tidak",
					        cancelButtonColor: "#DD6B55",
					        closeOnConfirm: false
					    }, function (isConfirm) {
					    	if(isConfirm){
						        window.location = "'.site_url("panelbackend/risk_control_grc/add/$id_risiko").'";
						    }else{
					       	 window.location = "'.site_url("panelbackend/risk_control_grc/index/$id_risiko/0/1").'";
						    }
					    });
					})</script>';
				}

				SetFlash('suc_msg', $return['success'].$addmsg);
				redirect("$this->page_ctrl/detail/$id_risiko/$id");

			} else {


            	$this->model->conn->trans_rollback();

				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] .= "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterEditSucceed($id=null){
		if(!$this->access_role_custom['panelbackend/risk_risiko_grc']['view_all_direktorat'])
			return true;

		$id_risiko = $this->data['rowheader1']['id_risiko'];
		$id_mitigasi = $id;

		$cek = $this->checkApproveInterdependent($id_risiko);

		if($cek===1 && $this->data['rowheader1']['id_status_pengajuan']==6){
			$id_status_pengajuan = 3;
			$this->conn->Execute("update 
				risk_risiko set id_status_pengajuan = $id_status_pengajuan 
				where id_risiko = ".$this->conn->escape($id_risiko));
		}

		$row = $this->model->GetByPk($id);

		if($row['status_konfirmasi']=='1' 
			&& $row['penanggung_jawab']<>$this->data['rowinterdependent']['penanggung_jawab'] 
			&& $this->data['rowheader']['owner']<>$row['penanggung_jawab']){
			$this->TaskInterdependentControl($id_risiko, $id_mitigasi, "Anda Ditunjuk Langsung Sebagai Penanggung Jawab Mitigasi", $row['penanggung_jawab'], 6);
		}
	}

	private function _notifRekomReview($id_risiko=null,$id=null){
		$record = array(
			'page'=>'control',
			'deskripsi'=>"Review dan rekomendasi dari ".$_SESSION[SESSION_APP]['nama_group'],
			'id_control'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_control_grc/edit/$id_risiko/$id"
		);
		return $this->InsertTask($record);
	}

	private function _kirimRekom($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'3'),"id_control=".$this->conn->escape($id));

		$record = array(
			'page'=>'control',
			'deskripsi'=>"Control sudah ditindaklanjuti",
			'id_control'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['row']['rekomendasi_jabatan'],
			'url'=>"panelbackend/risk_control_grc/detail/$id_risiko/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _kirimReview($id_risiko=null,$id=null){
		$this->conn->Execute("update risk_risiko set id_status_pengajuan = 7 where id_risiko = ".$this->conn->escape($id_risiko));
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'3'),"id_control=".$this->conn->escape($id));

		$record = array(
			'page'=>'control',
			'deskripsi'=>"Control sudah ditindaklanjuti",
			'id_control'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['row']['review_jabatan'],
			'url'=>"panelbackend/risk_control_grc/detail/$id_risiko/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _verifiedRekom($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'1'),"id_control=".$this->conn->escape($id));

		$record = array(
			'page'=>'control',
			'deskripsi'=>"Control telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_control'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_control_grc/detail/$id_control/$id"
		);
		$this->InsertTask($record);

		$this->conn->Execute("update risk_risiko set id_status_pengajuan = 7 where id_risiko = ".$this->conn->escape($id_risiko));
		$id_scorecard = $this->data['rowheader']['id_scorecard'];
		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko membutuhkan validasi",
			'id_status_pengajuan'=>7,
			'id_risiko'=>$id_risiko,
			'url'=>"panelbackend/risk_risiko_grc/detail/$id_scorecard/$id_risiko"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _verifiedReview($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'1'),"id_control=".$this->conn->escape($id));

		$record = array(
			'page'=>'control',
			'deskripsi'=>"Control telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_control'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_control_grc/detail/$id_risiko/$id"
		);
		$this->InsertTask($record);

		$this->conn->Execute("update risk_risiko set id_status_pengajuan = 7 where id_risiko = ".$this->conn->escape($id_risiko));
		$id_scorecard = $this->data['rowheader']['id_scorecard'];
		$record = array(
			'page'=>'risiko',
			'deskripsi'=>"Risiko membutuhkan validasi",
			'id_status_pengajuan'=>7,
			'id_risiko'=>$id_risiko,
			'url'=>"panelbackend/risk_risiko_grc/detail/$id_scorecard/$id_risiko"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}


	public function Detail($id_risiko=null, $id=null){

		$this->_beforeDetail($id_risiko,$id);

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'])
			$this->NoData();

		$this->_onDetail($id);

		if($this->post['act']=='kirim_rekomendasi'){
			$this->_kirimRekom($id_risiko,$id);
		}

		if($this->post['act']=='kirim_review'){
			$this->_kirimReview($id_risiko,$id);
		}

		if($this->post['act']=='save_rekomendasi_verified'){
			$this->_verifiedRekom($id_risiko,$id);
		}

		if($this->post['act']=='save_review_verified'){
			$this->_verifiedReview($id_risiko,$id);
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_risiko=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_risiko);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id);

		if(!$this->access_role['delete'])
			$this->Error403();

		if($this->data['row']['id_mitigasi_sumber'] && $return){
			$return = $this->conn->Execute("update risk_mitigasi 
				set is_control=0
				where id_mitigasi = ".$this->conn->escape($this->data['row']['id_mitigasi_sumber']));
		}

		if($return){
			$return = $this->model->delete("$this->pk = ".$this->conn->qstr($id));
		}

		if($return){
			$return1 = $this->_afterDelete($id);
			if(!$return1)
				$return = false;
		}

		if ($return) {

			$this->backtodraft($id_risiko);

			$this->log("menghapus $id");

        	$this->conn->trans_commit();

			SetFlash('suc_msg', $return['success']);
			redirect("$this->page_ctrl/index/$id_risiko");
		}
		else {
			
        	$this->conn->trans_rollback();

			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_risiko/$id");
		}

	}



	protected function _afterUpdate($id){
		return $this->_afterInsert($id);
	}

	protected function _afterInsert($id){
		$ret = true;
		$id_control = $id;
		$id_risiko = $this->data['row']['id_risiko'];

		$suc = $this->actionEfektifitas($id_control, $id_risiko);
		if(!$suc['success'])
			$ret = false;

		if($ret)
			$ret = $this->_delsertFiles($id);

		return (bool)$ret;
	}

	private function _delsertFiles($id = null){
		$ret = true;

		if(!empty($this->post['file'])){
			foreach($this->post['file'] as $id_aktifits=>$rows){
				foreach($rows as $periode=>$rs){
					foreach($rs as $v){
						if(!$ret)
							break;
						
						$return = $this->_updateFiles(array($this->pk=>$id), $v['id']);

						$ret = $return['success'];
					}
				}
			}
		}


		if($ret && $this->access_role['rekomendasi']){

        	$cek = $this->conn->GetOne("select 1 from risk_mitigasi_files where jenis = 'filerekomendasi' and id_mitigasi = ".$this->conn->escape($id));

        	// if(!$cek && empty($this->post['filerekomendasi']))
        	// {
			// 	$this->data['err_msg'] .= "File dasar penetapan mitigasi wajib di isi. ";
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

	private function actionEfektifitas($id_control, $id_risiko){
		$this->load->model("Risk_control_efektifitasModel","controlefektif");

		$suc = array('success'=>1);

		$is_efektif = true;
		unset($this->data['mtefektifitasarr']['']);
		foreach ($this->data['mtefektifitasarr'] as $key => $value) {
	/*		if($this->data['row']['is_lock'] && $this->data['edited'] && !$this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat'] && !$value['need_lampiran'])
				continue;*/

			if(!$suc['success'])
				break;

			$record = array();
			$record['id_control'] = $id_control;
			$record['id_efektifitas'] = $value['id_efektifitas'];
			$record['is_iya'] = (int)$this->post['efektif'][$value['id_efektifitas']]['is_iya'];

			unset($_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['keterangan']);

			$record['keterangan'] = null;

			if($value['need_explanation'] && $record['is_iya']){
				$record['keterangan'] = $this->post['efektif'][$value['id_efektifitas']]['keterangan'];


				if(!$record['keterangan']){
					$_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['keterangan'] = "Penjelasan efektifitas wajib di isi.";
					return false;
				}
			}


			if($is_efektif)
				$is_efektif = (int)$this->post['efektif'][$value['id_efektifitas']]['is_iya'];

        	$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas where id_control = ".$this->conn->escape($id_control)." and id_efektifitas = ".$this->conn->escape($value['id_efektifitas']));

        	if($cek)
        		$suc = $this->controlefektif->Update($record, "id_control = ".$this->conn->escape($id_control)." and id_efektifitas = ".$this->conn->escape($value['id_efektifitas']));
        	else
				$suc = $this->controlefektif->Insert($record);

			unset($_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['lampiran']);

			if($suc['success'] && $value['need_lampiran'] && $record['is_iya']){
				if(!$suc['success']){
					$_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['lampiran'] = $suc['error'];
					return false;
				}
			}
		}

		if($suc['success']){
			if($is_efektif)
				$r = array("is_efektif"=>1);
			else
				$r = array("is_efektif"=>2);

			$this->riskchangelog($r, array('is_efektif'=>$this->data['row']['is_efektif']));

			$suc = $this->model->Update($r ,"$this->pk = ".$this->conn->qstr($id_control));
		}

		return $suc;
	}

	function actionSimpanRisiko($id_control, $id){
		if(!$this->Access("edit",'panelbackend/risk_risiko_grc'))
			$this->Error403();

		$this->load->model("Risk_risikoModel","risiko");

		$record = array(
			'control_dampak_penurunan'=>$this->post['control_dampak_penurunan'],
			'control_kemungkinan_penurunan'=>$this->post['control_kemungkinan_penurunan']
		);
		
		$this->riskchangelog($record, $this->data['rowheader1']);

		$return = $this->risiko->Update($record, "id_risiko = ".$this->conn->qstr($id));


		if ($return['success']){

			$this->backtodraft($id);

			if($this->data['rowheader']['id_nama_proses']){
				SetFlash('suc_msg', "Data berhasil disimpan");
				redirect(current_url());
			}else{
			
				$cek = $this->conn->GetOne("select 1 from risk_mitigasi where id_risiko = ".$this->conn->escape($id));

				if($cek){

					SetFlash('suc_msg', "Data berhasil disimpan");
					redirect(current_url());
				
				}else{
						$this->ctrl = 'risk_mitigasi';
						SetFlash('suc_msg', "Data berhasil disimpan");
						redirect("panelbackend/risk_mitigasi_grc/add/$id");
				}
			}
		}
		else{
			SetFlash('err_msg',"Data gagal disimpan");
			redirect(current_url());
		}

		die();
	}

	protected function _beforeDetail($id=null, $id_control=null, $is_edit=false){


		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);

		if(!$this->data['rowheader1'])
			$this->NoData();

		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['edited']);

		$this->data['editedheader1'] = $this->data['edited'];

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		if($this->post['act']=='save' && !$is_edit)
			$this->actionSimpanRisiko($id_control, $id);

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));
		}

		$this->data['mtcontrolarr'] = $this->model->GetCombo($id, $id_control);

		$this->data['add_param'] .= $id;
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

	private function _lamiranEfektifitas(){
		$rows = $this->conn->GetArray("select 
		a.*,
		id_control_efektifitas_files as id, client_name as name
		from risk_control_efektifitas_files a 
		where id_control = ".$this->conn->escape($this->post['id_control'])." 
		and jenis = 'file' 
		and id_efektifitas = ".$this->conn->escape($this->post['id_efektifitas'])."order by periode asc");
		
		
		$temparr = array();
		$no=1;
		foreach($rows as $r){
			$parr = explode("_", $r['periode']);
			$i = $parr[0].$parr[count($parr)-1].$no++;
			//$i = $parr[0].$parr[1];
			$temparr[$i] = $r;
			//echo $i.'<-->';
		}

		ksort($temparr); 
		

		$files = array();
		$periodearr = array();
		foreach($temparr as $r){
			$periodearr[$r['periode']] = array("id_efektifitas"=>$r['id_efektifitas']);
			$files[$r['periode']]['name'][] = $r['name'];
			$files[$r['periode']]['id'][] = $r['id'];
		}

		echo "<table border='1' class='tbefektif' width='100%'>";
		foreach($periodearr as $periode=>$r){
	      list($tahun, $id_interval, $i) = explode("_",$periode);

	      if(!$indayarr[$id_interval])
	      	$indayarr[$id_interval] = $this->conn->GetOne("select inday from mt_interval where id_interval = ".$this->conn->escape($id_interval));

	      $label = "File ";

	      $inday = $indayarr[$id_interval];
	      if($inday){
			  	$totp = round(365/$inday);

				$label = substr($this->data['mtintervalarr'][$id_interval],0, -2)." ".$i." tahun ".$tahun;
				if($totp==1)
					$label = "Tahun ".$tahun;
				elseif($totp==12)
					$label = "Bulan ".GetBulan($i)." tahun ".$tahun;
			  	elseif($totp==365)
			  		$label = Eng2Ind(date('d-m-Y', strtotime('01-01-'.$tahun.' + '.($i-1).' days')));
			}

	      echo "<tr><td style='min-width:200px'>$label &nbsp;</td><td>";
	      echo UI::createUploadMultiple('file['.$r['id_efektifitas'].']['.$periode.']', $files[$periode], $this->page_ctrl, false);
	      echo "</td></tr>";
		}

		echo "</table>";
		die();
	}

	private function _getInfoLampiran(&$row=array()){
		foreach($this->data['mtefektifitasarr'] as $r){
			if($r['need_lampiran']){
				$cek = $this->conn->GetOne("select 1
				from risk_control_efektifitas 
				where is_iya = '1' and id_control =  ".$this->conn->escape($row['id_control'])." 
				and id_efektifitas = ".$this->conn->escape($r['id_efektifitas']));
				if($cek){
					$inday = $this->conn->GetOne("select inday from mt_interval where id_interval = ".$this->conn->escape($row['id_interval']));

					if($inday){
					  $totp = round(365/$inday);
					  for($i=1; $i<=$totp; $i++){
					    $daysaatini = (time()-strtotime(date('Y').'-01-01'))/(60 * 60 * 24);
					    $dayrun = ($i*$inday)-$inday;

					    $is_edited = false;
					    if($daysaatini>=$dayrun)
					      $is_edited = true;

					    if(!$is_edited)
					      continue;

					  	$periode = date('Y').'_'.$row['id_interval'].'_'.$i;
					  }
					}else{
						$periode = date('Y').'_'.$row['id_interval'];
					}

					$cek = $this->conn->GetOne("select 1 
						from risk_control_efektifitas_files 
						where jenis = 'file'  
						and periode = '$periode'
						and id_control =  ".$this->conn->escape($row['id_control'])." 
						and id_efektifitas = ".$this->conn->escape($r['id_efektifitas']));

					if(!$cek)
						$row['no_lampiran'] = 1;
				}
			}
		}
	}

	protected function _afterDetail($id){
		$id_control = $this->data['row']['id_control'];

		$periodearr = array();
		$inday = $this->conn->GetOne("select inday from mt_interval where id_interval = ".$this->conn->escape($this->data['row']['id_interval']));

		if($inday){
		  $totp = round(365/$inday);

		  $this->data['totp'] = $totp;

		  for($i=1; $i<=$totp; $i++){
		    $label = substr($this->data['mtintervalarr'][$this->data['row']['id_interval']],0, -2)." ".$i." tahun ".date('Y');
		    if($totp==1)
		      $label = "Tahun ".date('Y');
		    elseif($totp==12)
		      $label = "Bulan ".GetBulan($i)." tahun ".date('Y');
		  	elseif($totp==365)
		  		$label = Eng2Ind(date('d-m-Y', strtotime('01-01-'.date('Y').' + '.($i-1).' days')));


		    $is_edited = false;
		    $daysaatini = (time()-strtotime(date('Y').'-01-01'))/(60 * 60 * 24);
		    $dayrun = ($i*$inday)-$inday;

		    $is_edited = false;
		    if($daysaatini>=$dayrun)
		      $is_edited = true;

		    if(!$is_edited)
		      continue;

		  	if(!$label)
		  		$label = "File ";

		  	$periode = date('Y').'_'.$this->data['row']['id_interval'].'_'.$i;
		    $periodearr[$periode] = array(
		    	"label"=>$label, 
		    	'is_edited'=>$is_edited, 
		    	"periode"=>$periode
		    );
		  }
		}else{
			$periode = date('Y').'_'.$this->data['row']['id_interval'];
		  	$periodearr[$periode] = array(
		  		"label"=>"File ", 
		  		'is_edited'=>true,
		  		"periode"=>$periode
		  	);
		}


		if($this->post['id_interval']<>$this->post['id_interval_before'])
			$this->data['row']['file'] = array();

		foreach($this->data['mtefektifitasarr'] as $r){
			if($r['need_lampiran']){
				$rs = $this->conn->GetArray("select 
				a.*,
				id_control_efektifitas_files as id, client_name as name
				from risk_control_efektifitas_files a where id_control = ".$this->conn->escape($id)." and jenis = 'file' and id_efektifitas = ".$this->conn->escape($r['id_efektifitas'])."
				and periode in ('".implode("','",array_keys($periodearr))."')");

				$rsfile = array();
				foreach($rs as $r1){
					if($r1['periode']<>$periode){
						unset($periodearr[$r1['periode']]);
					}else{
						$rsfile[$r1['periode']]['name'][] = $r1['name'];
						$rsfile[$r1['periode']]['id'][] = $r1['id'];
					}
				}

				foreach($rsfile as $pe=>$v){
					if(!$this->data['row']['file'][$r['id_efektifitas']][$pe])
						$this->data['row']['file'][$r['id_efektifitas']][$pe] = $v;
				}
			}
		}

		if($id && !isset($this->data['row']['efektif'])){

			$rowsefektif = $this->conn->GetArray("select * from risk_control_efektifitas where id_control = ".$this->conn->escape($id_control));

			$this->data['row']['efektif'] = array();
			foreach ($rowsefektif as $r) {
				$this->data['row']['efektif'][$r['id_efektifitas']] = $r;
			}
		}

		if(!$this->data['row']['filerekomendasi']['id'] && $id){
			$rows = $this->conn->GetArray("select id_control_efektifitas_files as id, client_name as name
				from risk_control_efektifitas_files
				where jenis = 'filerekomendasi' and id_control = ".$this->conn->escape($id));

			foreach($rows as $r){
				$this->data['row']['filerekomendasi']['id'][] = $r['id'];
				$this->data['row']['filerekomendasi']['name'][] = $r['name'];
			}
		}

		$this->isLock();

		if($this->post['act']=='set_val'){
			$this->data['row'] = array_merge($this->data['row'],$this->post);
		}

		$this->data['filerekomendasi'] = $this->modelfile->GArray('*',"where jenis='filerekomendasi' and id_control = ".$this->conn->escape($id));

		if($this->data['row']['interdependent']===null && $id)
			$this->data['row']['interdependent'] = $this->isInterdependent(true);

		if($this->data['row']['penanggung_jawab']){

			$pejabat = $this->data['row']['penanggung_jawab'];

			if(!$this->data['penanggung_jawabarr'][$pejabat]){
				$nama = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($pejabat));

				$this->data['penanggung_jawabarr'][$pejabat] = $nama;
			}

		}

		$this->data['periodearr'] = $periodearr;

		$this->_getListTask('control', $this->data['row']);

		$this->_getInfoLampiran($this->data['row']);
	}

	protected function _onDetail($id, &$record=array()){
		$this->data['taksonomirr'] = array();
		if($this->data['rowheader1']['id_taksonomi_risiko']){
			$rows = $this->conn->GetArray("select id_taksonomi_control as id, nama as text from mt_taksonomi_control where id_taksonomi_risiko = ".$this->data['rowheader1']['id_taksonomi_risiko']);

			$this->data['taksonomirr'] = array();
			foreach($rows as $r){
				$this->data['taksonomirr'][$r['id']] = $r;
			}
		}

		if($this->post['act']=='get_lampiran')
			$this->_lamiranEfektifitas();
	}

	protected function _beforeEdit(&$record=array(), $id){
		$this->_validAccessTask('panelbackend/risk_risiko_grc',$this->data['rowheader1'],$this->data['edited']);

		$this->isLock();

		if(!$record['id_taksonomi_control'] && $record['nama']){
			$record1 = array();
			$maxkode = $this->conn->GetOne("select max(kode) from mt_taksonomi_control where id_taksonomi_risiko = ".$this->conn->escape($this->data['rowheader1']['id_taksonomi_risiko']));

			$arr = explode(".", $maxkode);
			$arr[count($arr)-1] = ((int)$arr[count($arr)-1])+1;
			$kode = implode(".", $arr);

			$record1['kode'] = $kode;
			$record1['nama'] = $record['nama'];
			$record1['id_taksonomi_risiko'] = $this->data['rowheader1']['id_taksonomi_risiko'];
			$this->conn->goInsert("mt_taksonomi_control", $record1);

			$record['id_taksonomi_control'] = $this->conn->GetOne("select max(id_taksonomi_control) from mt_taksonomi_control where id_taksonomi_risiko = ".$this->conn->escape($record1['id_taksonomi_risiko']));

			$this->data['row']['id_taksonomi_control'] = $record['id_taksonomi_control'];
		}

		return true;
	}

	protected function _beforeDelete($id){
		$this->_validAccessTask('panelbackend/risk_risiko_grc',$this->data['rowheader1'],$this->data['edited']);

		$this->isLock();

		$return  = $this->conn->Execute("delete from risk_control_efektifitas_files where id_control = ".$this->conn->escape($id));

		if($return)
			$return = $this->conn->Execute("delete from risk_control_efektifitas where id_control = ".$this->conn->escape($id));

		return $return;
	}

	function isLock(){
		if($this->data['rowheader1']['status_risiko']!='1' && $this->data['rowheader1']['status_risiko']){
			$this->data['editedheader1'] = false;
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			$this->access_role['add'] = false;
			$this->data['row']['is_lock']=0;
			$this->data['is_allow_edit_control']=false;
			$this->data['is_allow_edit_penanggung_jawab']=false;
			return;
		}

		if($this->Access("view_all_direktorat","panelbackend/risk_risiko_grc"))
			return;

		if($this->ctrl == "risk_control" and $this->data['row']['is_lock']=='1'){
			$this->access_role['delete'] = false;
		}elseif($this->data['row']['is_lock']=='1'){
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
		}
		if($this->data['rowheader1']['is_lock']=='1'){
			$this->data['editedheader1'] = false;
		}
	}

	private function isAllowEditLampiran(){
		if(!$this->data['rowinterdependent'])
			$this->data['rowinterdependent'] = $this->data['row'];
		
		if($this->data['mode']!='edit' && $this->mode != 'add')
			return false;

		if($this->access_role['edit'] && !isset($this->data['rowinterdependent']))
			return true;

		if($this->Access("view_all_direktorat", "panelbackend/risk_risiko_grc"))
			return true;

		if($this->data['rowheader1']['id_status_pengajuan']==3)
			return false;

		$is_interdependent = $this->isAccessInterdependent();
		$is_interdependent1 = $this->isAccessInterdependent(false);

		if($this->access_role['edit'] && !$is_interdependent)
			return true;

		if($is_interdependent1 && $this->data['rowinterdependent']['status_konfirmasi']=='0' && $this->data['rowheader1']['id_status_pengajuan']==6)
			return true;

		if($is_interdependent && $this->data['rowinterdependent']['status_konfirmasi']=='1')
			return true;

		if($is_interdependent && $this->data['rowinterdependent']['status_konfirmasi']=='2')
			return false;

		return false;
	}

	private function isAllowEditControl(){
		if(!$this->data['rowinterdependent'])
			$this->data['rowinterdependent'] = $this->data['row'];

		if($this->data['mode']!='edit' && $this->mode != 'add')
			return false;
		
		if($this->access_role['edit'] && !isset($this->data['rowinterdependent']))
			return true;

		if($this->Access("view_all_direktorat", "panelbackend/risk_risiko_grc"))
			return true;

		if($this->data['rowinterdependent']['is_lock']=='1')
			return false;

		if($this->data['rowheader1']['id_status_pengajuan']==3)
			return false;

		$is_interdependent = $this->isAccessInterdependent(false);

		if($this->access_role['edit'] && !$is_interdependent)
			return true;

		if($is_interdependent && $this->data['rowinterdependent']['status_konfirmasi']=='0' && $this->data['rowheader1']['id_status_pengajuan']==6)
			return true;

		return false;
	}

	protected function _uploadFiles($jenis_file=null, $id=null){

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
			$jenis_file1 = $jenis_file = str_replace("upload","",$jenis_file);
			$jenisarr = explode("_",$jenis_file);
			if(count($jenisarr)>1){
				$record['periode'] = substr(str_replace("file", "", $jenis_file),1);
				$record['id_efektifitas'] = substr(str_replace("file", "", $jenis_file),0,1);
				$jenis_file = "file";

			}
			$record['jenis_file'] = $record['jenis'] = $jenis_file;
			$record[$this->pk] = $id;

			$ret = $this->modelfile->Insert($record);
			if($ret['success'])
			{
				$return = array('file'=>array("id"=>$ret['data'][$this->modelfile->pk],"name"=>$upload_data['client_name']));
			}else{
				unlink($upload_data['full_path']);
				$return = array('errors'=>"File $name gagal upload");
			}

        }

        return $return;

	}
}
