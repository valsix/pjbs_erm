<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_mitigasi_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_mitigasi_grclist";
		$this->viewdetail = "panelbackend/risk_mitigasi_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard_grc";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk Action Plan GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk Action Plan GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risk Action Plan GRC';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Risk Action Plan GRC';
			$this->data['edited'] = true;
		}

		$this->load->model("Risk_mitigasiModel","model");

		$this->load->model("Risk_mitigasi_filesModel","modelfile");
		$this->data['configfile'] = $this->config->item('file_upload_config');

		$this->load->model("Mt_risk_efektifitasModel","mtefektifitas");
		$mtefektifitas = $this->mtefektifitas;
		$this->data['mtefektifitasarr'] = $mtefektifitas->GetCombo();

		$this->load->model("Mt_status_progressModel","mtprogress");
		$mtprogress = $this->mtprogress;
		$this->data['pregressarr'] = $mtprogress->GetCombo();
		$this->data['pregressarr1'] = $this->conn->GetList("select id_status_progress as key, prosentase as val from mt_status_progress");

		$this->SetAccess(array('panelbackend/mekanisme_grc','panelbackend/risk_risiko_grc'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker',
			'myautocomplete',
			'upload'
		);

		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");

		$this->data['penanggung_jawabarr'] = $this->mtsdmjabatan->GetCombo();

		$this->data['revenue'] = $this->conn->GetOne("select revenue from mt_revenue where tahun = '".date('Y')."'");
	}

	protected function Header(){
		if($this->data['is_peluang']){
		return array(
			array(
				'name'=>'no',
				'label'=>'No',
				'width'=>"18px",
				'nofilter'=>true,
				'type'=>"numeric",
			),
			array(
				'name'=>'nama_aktifitas',
				'field'=>'m____nama',
				'label'=>'Aktivitas Tindak Lanjut',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'dead_line',
				'label'=>'Dead Line',
				'width'=>"auto",
				'type'=>"date",
			),
			array(
				'name'=>'nama_pic',
				'label'=>'Penanggung Jawab',
				'width'=>"auto",
				'type'=>"varchar2",
				'field'=>"j_____nama"
			),
			array(
				'name'=>'id_status_progress',
				'label'=>'Progress',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['pregressarr'],
			),
			// array(
			// 	'name'=>'id_status_pengajuan',
			// 	'label'=>'Status',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['mtstatusarr'],
			// ),
			// array(
			// 	'name'=>'is_efektif',
			// 	'label'=>'Efektif ?',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-')+listefektifitas(),
			// ),
		);
		}else{
		return array(
			array(
				'name'=>'no',
				'label'=>'No',
				'width'=>"18px",
				'nofilter'=>true,
				'type'=>"numeric",
			),
			array(
				'name'=>'nama_aktifitas',
				'field'=>'m____nama',
				'label'=>'Aktivitas Mitigasi',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'dead_line',
				'label'=>'Dead Line',
				'width'=>"auto",
				'type'=>"date",
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
			array(
				'name'=>'cba',
				'label'=>'CBA',
				'add_label'=> UI::createInfo("info_cba","Info CBA (Cost Baseline Analisis)", "<ul style='padding-left: 15px;'><li>CBA digunakan untuk acuan mitigasi yang dilaksanakan layak atau tidak.</li><li>Apabila CBA Ratio > 100% berarti penanganan risiko tersebut memiliki manfaat lebih besar daripada biaya sehingga layak untuk diterapkan.</li></ol>",'model-xs',1),
				'width'=>"70px",
				'type'=>"numeric"
			),
			array(
				'name'=>'id_status_progress',
				'label'=>'Progress',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['pregressarr'],
			),
			// array(
			// 	'name'=>'id_status_pengajuan',
			// 	'label'=>'Status',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>$this->data['mtstatusarr'],
			// ),
			// array(
			// 	'name'=>'is_efektif',
			// 	'label'=>'Efektif ?',
			// 	'width'=>"auto",
			// 	'type'=>"list",
			// 	'value'=>array(''=>'-pilih-')+listefektifitas(),
			// ),
		);
	}
	}

	protected function Record($id=null){
		$record = array(
			'no'=>$this->post['no'],
			'nama'=>$this->post['nama'],
			'penanggung_jawab'=>$this->post['penanggung_jawab'],
			'id_status_progress'=>$this->post['id_status_progress'],
			'menurunkan_dampak_kemungkinan'=>$this->post['menurunkan_dampak_kemungkinan'],
			'id_taksonomi_mitigasi'=>$this->post['id_taksonomi_mitigasi'],
			'remark'=>$this->post['remark'],
			'biaya'=>Rupiah2Number($this->post['biaya']),
			'revenue'=>Rupiah2Number($this->post['revenue']),
			'dead_line'=>$this->post['dead_line'],
			'is_efektif'=>(int)$this->post['is_efektif'],
		);

		if(!$this->data['is_allow_edit_penanggung_jawab']){
			unset($record['penanggung_jawab']);
		}

		if(!$this->data['is_allow_edit_progress']){
			unset($record['id_status_progress']);
		}

		if(!$this->data['is_allow_edit_mitigasi']){
			unset($record['nama']);
			unset($record['menurunkan_dampak_kemungkinan']);
			unset($record['remark']);
			unset($record['biaya']);
			unset($record['revenue']);
			unset($record['dead_line']);
			unset($record['is_efektif']);
		}

		if(!$this->post['biaya'])
			unset($record['biaya']);

		if(!$this->post['revenue'])
			unset($record['revenue']);


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
				'label'=>'Nama Aktivitas Mitigasi',
				'rules'=>"required|max_length[4000]",
			),
			"dead_line"=>array(
				'field'=>'dead_line',
				'label'=>'Dead Line',
				'rules'=>"required|date",
			),
			"rating"=>array(
				'field'=>'rating',
				'label'=>'Rating',
				'rules'=>"numeric",
			),
			"biaya"=>array(
				'field'=>'biaya',
				'label'=>'Biaya',
				'rules'=>"required|numeric",
			),
			"revenue"=>array(
				'field'=>'revenue',
				'label'=>'Dasar Perhitungan Dampak Finansial',
				'rules'=>"required|numeric",
			),
			"penanggung_jawab"=>array(
				'field'=>'penanggung_jawab',
				'label'=>'Penanggung Jawab',
				'rules'=>"callback_inlistjabatan|required",
			),
			"id_status_progress"=>array(
				'field'=>'id_status_progress',
				'label'=>'Progress',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['pregressarr']))."]",
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

		if(!$this->data['is_allow_edit_progress']){
			unset($return['id_status_progress']);
		}

		if(!$this->data['is_allow_edit_mitigasi']){
			unset($return['nama']);
			unset($return['menurunkan_dampak_kemungkinan']);
			unset($return['remark']);
			unset($return['biaya']);
			unset($return['revenue']);
			unset($return['dead_line']);
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

		if($this->data['is_peluang']){
			unset($return['menurunkan_dampak_kemungkinan']);
			unset($return['remark']);
			unset($return['revenue']);
			unset($return['review_kepatuhan']);
			unset($return['rekomendasi_keterangan']);
		}	

		return $return;
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

	public function Index($id_risiko=null, $page=0, $edited=0){

		if($this->post['act']=='reset'){
			redirect("panelbackend/risk_mitigasi_grc/index/$id_risiko/0");
		}

		if($this->post['act']=='jadikan_control' && $this->post['key']){
			$this->jadikan_control($this->post['key']);
		}

		if($this->post['act']=='close' && $this->post['key']){
			$this->conn->Execute("update risk_mitigasi set is_close = '1' where id_mitigasi = ".$this->conn->escape($this->post['key']));
			redirect(current_url());
			exit();
		}

		if($this->post['act']=='unclose' && $this->post['key']){
			$this->conn->Execute("update risk_mitigasi set is_close = '0' where id_mitigasi = ".$this->conn->escape($this->post['key']));
			redirect(current_url());
			exit();
		}

		$this->_beforeDetail($id_risiko,$id);

		if($this->data['editedheader1'] && !$edited)
			$this->data['editedheader1'] = 0;

		$this->_onDetail(null);
		
		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);
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

	private function isAllowEditMitigasi(){
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

	private function isAllowEditProgress(){
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

	public function Edit($id_risiko=null, $id=null){
		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$action_approve = false;
		if($this->post['act']=='approve_mitigasi'){
			$this->post['act']='save';
			$action_approve = true;
		}

		$this->_beforeDetail($id_risiko,$id, true);
		$this->data['row'] = $this->data['rowinterdependent'] = $this->model->GetByPk($id);

		if (!$this->data['row'] && $id)
			$this->NoData();

		if($this->data['row']['no']==null)
			$this->data['row']['no'] = $this->model->GetNo($id_risiko);

		$this->data['is_allow_edit_penanggung_jawab'] = $this->isAllowEditPenanggungJawab();
		$this->data['is_allow_edit_progress'] = $this->isAllowEditProgress();
		$this->data['is_allow_edit_mitigasi'] = $this->isAllowEditMitigasi();

		if(
			$this->data['is_allow_edit_penanggung_jawab'] or 
			$this->data['is_allow_edit_progress'] or 
			$this->data['is_allow_edit_mitigasi'] or
			$action_approve
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

			if($this->post['biaya'])
				$this->post['biaya'] = Rupiah2Number($this->post['biaya']);
			else
				unset($this->post['biaya']);

			if($this->post['revenue'])
				$this->post['revenue'] = Rupiah2Number($this->post['revenue']);
			else
				unset($this->post['revenue']);

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

			$nilai_cr = (double)$this->data['rowheader1']['rating_kemungkinancr']*(double)$this->data['rowheader1']['rating_dampakcr'];
			$nilai_rr = (double)$this->data['rowheader1']['rating_tingkatrisikors']*(double)$this->data['rowheader1']['rating_dampakrisikors'];

			if($nilai_rr){
				$revenue = $this->data['row']['revenue'];
				$implement_cost = $this->data['row']['biaya'];
				$rs_cba = HitungCBA($nilai_cr,$nilai_rr,$revenue,$implement_cost);
				$cba = (double)$rs_cba;

				$record['cba'] = $cba;
			}

			$this->_isValid($record,true);
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

            $this->model->conn->CompleteTrans();


			if ($return['success']) {

				$this->_afterEditSucceed($id);

				$this->backtodraft($id_risiko);

				if($action_approve){
					$this->post['id_status_pengajuan']['mitigasi'] = 5;
					$this->post['id']['risiko'] = $id_risiko;
					$this->post['id']['mitigasi'] = $id;
					$this->post['keterangan']['mitigasi'] = "Interdependent menyetujui";

					$this->_actionKonfirmasi(true);
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
					        text: "Apakah Anda ingin menambah '.($this->data['is_peluang']?'Tindak lanjut':'Mitigasi').' lagi ?",
					        type: "success",
					        showCancelButton: true,
					        confirmButtonColor: "#2b982b",
					        confirmButtonText: "Iya",
					        cancelButtonText: "Tidak",
					        cancelButtonColor: "#DD6B55",
					        closeOnConfirm: false
					    }, function (isConfirm) {
					    	if(isConfirm){
						        window.location = "'.site_url("panelbackend/risk_mitigasi_grc/add/$id_risiko").'";
						    }else{
					       	 window.location = "'.site_url("panelbackend/risk_mitigasi_grc/index/$id_risiko/0/1").'";
						    }
					    });
					})</script>';
				}

				SetFlash('suc_msg', $return['success'].$addmsg);
				redirect("$this->page_ctrl/detail/$id_risiko/$id");

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

		if(!$this->data['is_peluang']){
			$this->data['taksonomirr'] = array();
			if($this->data['rowheader1']['id_taksonomi_risiko']){
				$rows = $this->conn->GetArray("select id_taksonomi_mitigasi as id, nama as text from mt_taksonomi_mitigasi where id_taksonomi_risiko = ".$this->data['rowheader1']['id_taksonomi_risiko']);

				$this->data['taksonomirr'] = array();
				foreach($rows as $r){
					$this->data['taksonomirr'][$r['id']] = $r;
				}
			}
		}

		if($this->post['act']=='get_lampiran')
			$this->_lampiran();
	}

	private function _lampiran(){
		$rows = $this->conn->GetArray("select 
		id_mitigasi_files as id, client_name as name
		from risk_mitigasi_files a 
		where id_mitigasi = ".$this->conn->escape($this->post['id_mitigasi']));

		foreach($rows as $r){
	      echo UI::createUpload('file['.$value['id_mitigasi'].']', $r, $this->page_ctrl, false);
	      echo "<br/>";
		}

		die();
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
			$this->TaskInterdependentMitigasi($id_risiko, $id_mitigasi, "Anda Ditunjuk Langsung Sebagai Penanggung Jawab Mitigasi", $row['penanggung_jawab'], 6);
		}
	}

	public function Delete($id_risiko=null, $id=null){

        $this->model->conn->StartTrans();

		$this->_beforeDetail($id_risiko,$id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->isLock();

		if($this->data['row']['status_konfirmasi']=='1' && $this->isAccessInterdependent()){
			$this->access_role['delete'] = false;
		}
		
		
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

	private function _notifRekomReview($id_risiko=null,$id=null){
		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>"Review dan rekomendasi dari ".$_SESSION[SESSION_APP]['nama_group'],
			'id_mitigasi'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_mitigasi_grc/edit/$id_risiko/$id"
		);
		return $this->InsertTask($record);
	}

	private function _kirimRekom($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'3'),"id_mitigasi=".$this->conn->escape($id));

		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>"mitigasi sudah ditindaklanjuti",
			'id_mitigasi'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['row']['rekomendasi_jabatan'],
			'url'=>"panelbackend/risk_mitigasi_grc/detail/$id_risiko/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _kirimReview($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'3'),"id_mitigasi=".$this->conn->escape($id));

		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>"mitigasi sudah ditindaklanjuti",
			'id_mitigasi'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['row']['review_jabatan'],
			'url'=>"panelbackend/risk_mitigasi_grc/detail/$id_risiko/$id"
		);
		$this->InsertTask($record);

		if($return['success'])
			SetFlash("suc_msg","Notif telah dikirim");
		else
			SetFlash("err_msg","Notif gagal dikirim");

		redirect(current_url());
	}

	private function _verifiedRekom($id_risiko=null,$id=null){
		$return = $this->model->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'1'),"id_mitigasi=".$this->conn->escape($id));

		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>"mitigasi telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_mitigasi'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_mitigasi_grc/detail/$id_risiko/$id"
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
		$return = $this->model->Update(array('is_lock'=>'1','review_is_verified'=>'1'),"id_mitigasi=".$this->conn->escape($id));

		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>"mitigasi telah diverifikasi oleh ".$_SESSION[SESSION_APP]['nama_group'],
			'id_mitigasi'=>$id,
			'id_risiko'=>$id_risiko,
			'untuk'=>$this->data['rowheader']['owner'],
			'url'=>"panelbackend/risk_mitigasi_grc/detail/$id_risiko/$id"
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

		if(($this->isAccessInterdependent() && $this->data['row']['status_konfirmasi']=='1') or $this->isAccessInterdependent(false))
			$this->access_role['edit'] = true; 

		if($this->isAccessInterdependent() && $this->data['row']['status_konfirmasi']=='2')
			$this->access_role['edit'] = false; 

		if($this->data['row']['status_konfirmasi']=='1' && $this->isAccessInterdependent()){
			$this->access_role['delete'] = false;
		}
		
		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _afterUpdate($id){
		$ret = $this->_afterInsert($id);

		return $ret;
	}

	protected function _afterInsert($id){
		$ret = true;
		$id_risiko = $this->data['rowheader1']['id_risiko'];

		if($ret)
			$ret = $this->_delsertFiles($id);	
		
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

	function actionSimpanRisiko($id_mitigasi, $id){
		if(!$this->Access("edit",'panelbackend/risk_risiko_grc'))
			$this->Error403();

		$this->load->model("Risk_risikoModel","risiko");

		$record = array(
			'residual_target_kemungkinan'=>$this->post['residual_target_kemungkinan'],
			'residual_target_dampak'=>$this->post['residual_target_dampak'],
		);

		$row = $this->conn->GetRow("select * from risk_risiko where id_risiko = ".$this->conn->escape($id));
		$this->riskchangelog($record, $row);

		$return = $this->risiko->Update($record, "id_risiko = ".$this->conn->qstr($id));

		if($return['success'])
			$return = $this->HitungCBAMitigasi($id);

		if ($return['success']){

			$this->backtodraft($id);

			SetFlash('suc_msg', "Data berhasil disimpan");
			redirect("panelbackend/risk_mitigasi_grc/index/$id");
		}
		else{
			SetFlash('err_msg',"Data gagal disimpan");
			redirect(current_url());
		}

		die();
	}

	private function HitungCBAMitigasi($id_risiko=null){

		$this->load->model("Risk_risikoModel",'riskrisiko');

		$this->data['rowheader1']  = $this->riskrisiko->GetRatingDKRisiko($id_risiko);

		$nilai_cr = (double)$this->data['rowheader1']['rating_kemungkinancr']*(double)$this->data['rowheader1']['rating_dampakcr'];

		$nilai_rr = (double)$this->data['rowheader1']['rating_tingkatrisikors']*(double)$this->data['rowheader1']['rating_dampakrisikors'];

		//$revenue = $this->data['revenue'];

		$rows = $this->conn->GetArray("select * from risk_mitigasi where id_risiko = ".$this->conn->escape($id_risiko));

		$ret = array("success"=>true);
		foreach($rows as $row){
			if(!$ret['success'])
				return false;

			$implement_cost = $row['biaya'];

			$revenue = $row['revenue'];

			$rs_cba = HitungCBA($nilai_cr,$nilai_rr,$revenue,$implement_cost);
			$cba = (double)$rs_cba;

			$record = array();
			$record['cba'] = $cba;
			$record['revenue'] = $revenue;

			$ret = $this->model->Update($record, "$this->pk = ".$this->conn->qstr($row['id_mitigasi']));
		}

		return $ret;
	}

	protected function _beforeDetail($id=null, $id_mitigasi=null, $is_edit=false){

		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetRatingDKRisiko($id);
		if(!$this->data['rowheader1'])
			$this->NoData();

		if($this->post['act']=='save' && !$is_edit)
			$this->actionSimpanRisiko($id_mitigasi, $id);


		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['edited']);

		$this->data['editedheader1'] = $this->data['edited'];

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));
		}
		
		$this->data['add_param'] .= $id;
	}

	protected function _beforeUpdate($record=array(), $id=null){
		// $this->data['files'] = $this->modelfile->GArray('*',"where id_mitigasi = ".$this->conn->escape($id));

		$row = $this->model->GetByPk($id);

		$this->riskchangelog($record, $row);


		if($this->post['residual']){
			$id_risiko = $record['id_risiko'];

			$record = array(
				'mitigasi_dampak_penurunan'=>$this->post['mitigasi_dampak_penurunan'],
				'mitigasi_kemungkinan_penurunan'=>$this->post['mitigasi_kemungkinan_penurunan'],
				);
			$ret = $this->riskrisiko->Update($record, "id_risiko = ".$this->conn->qstr($id_risiko));

			return (bool) $ret['success'];
		}

		return true;
	}

	protected function _beforeInsert($record=array()){
		$this->riskchangelog($record);
		return true;
	}

	protected function _afterDetail($id){

		if(!$this->data['row']['file']['id'] && $id){
			$rows = $this->conn->GetArray("select id_mitigasi_files as id, client_name as name
				from risk_mitigasi_files
				where jenis = 'file' and id_mitigasi = ".$this->conn->escape($id));

			foreach($rows as $r){
				$this->data['row']['file']['id'][] = $r['id'];
				$this->data['row']['file']['name'][] = $r['name'];
			}
		}

		if(!$this->data['row']['filerekomendasi']['id'] && $id){
			$rows = $this->conn->GetArray("select id_mitigasi_files as id, client_name as name
				from risk_mitigasi_files
				where jenis = 'filerekomendasi' and id_mitigasi = ".$this->conn->escape($id));

			foreach($rows as $r){
				$this->data['row']['filerekomendasi']['id'][] = $r['id'];
				$this->data['row']['filerekomendasi']['name'][] = $r['name'];
			}
		}

		if($this->data['row']['interdependent']===null && $id)
			$this->data['row']['interdependent'] = $this->isInterdependent(true);

		if($this->data['row']['penanggung_jawab']){

			$pejabat = $this->data['row']['penanggung_jawab'];

			if(!$this->data['penanggung_jawabarr'][$pejabat]){
				$nama = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($pejabat));

				$this->data['penanggung_jawabarr'][$pejabat] = $nama;
			}

		}
		$this->isLock();

		$this->_getListTask('mitigasi', $this->data['row']);
	}


	protected function _beforeEdit(&$record=array(), $id){
		$this->isLock();

		$this->_validAccessTask('panelbackend/risk_risiko_grc',$this->data['rowheader1'],$this->data['edited']);

		if(!$record['id_taksonomi_mitigasi'] && !$this->data['is_peluang'] && $record['nama']){
			$record1 = array();
			$maxkode = $this->conn->GetOne("select max(kode) from mt_taksonomi_mitigasi where id_taksonomi_risiko = ".$this->conn->escape($this->data['rowheader1']['id_taksonomi_risiko']));

			$arr = explode(".", $maxkode);
			$arr[count($arr)-1] = ((int)$arr[count($arr)-1])+1;
			$kode = implode(".", $arr);

			$record1['kode'] = $kode;
			$record1['nama'] = $record['nama'];
			$record1['id_taksonomi_risiko'] = $this->data['rowheader1']['id_taksonomi_risiko'];
			$this->conn->goInsert("mt_taksonomi_mitigasi", $record1);

			$record['id_taksonomi_mitigasi'] = $this->conn->GetOne("select max(id_taksonomi_mitigasi) from mt_taksonomi_mitigasi where id_taksonomi_risiko = ".$this->conn->escape($record1['id_taksonomi_risiko']));

			$this->data['row']['id_taksonomi_mitigasi'] = $record['id_taksonomi_mitigasi'];
		}

		return true;
	}

	protected function _beforeDelete($id){

		$this->isLock();

		$this->_validAccessTask('panelbackend/risk_risiko_grc',$this->data['rowheader1'],$this->data['edited']);

		$this->isLock();
		
		$return = $this->conn->Execute("delete from risk_task where id_mitigasi = ".$this->conn->escape($id));
		
		$return = $this->conn->Execute("delete from risk_mitigasi_files where id_mitigasi = ".$this->conn->escape($id));
		
		$return = $this->conn->Execute("delete from risk_control where ID_MITIGASI_SUMBER = ".$this->conn->escape($id));

		return $return;
	}

	private function jadikan_control($id_mitigasi){

		$this->conn->Execute("update risk_mitigasi set is_control = 1 where id_mitigasi = ".$this->conn->escape($id_mitigasi));

		$mitigasi = $this->conn->GetRow("select * from risk_mitigasi where id_mitigasi = ".$this->conn->escape($id_mitigasi));

		if(!$this->Access('add','panelbackend/risk_control_grc') or $mitigasi['id_status_progress']!='4'){
			$this->Error403();
		}

		$record = array(
			'id_risiko'=>$mitigasi['id_risiko'],
			'nama'=>$mitigasi['nama'],
			'deskripsi'=>$this->post['deskripsi'],
			'is_efektif'=>'1',
			'id_interval'=>'6',
			'id_mitigasi_sumber'=>$mitigasi['id_mitigasi'],
			'menurunkan_dampak_kemungkinan'=>$mitigasi['menurunkan_dampak_kemungkinan']
		);

		$this->load->model("Risk_controlModel",'mcontrol');

		$return = $this->mcontrol->Insert($record);

		$id_control = $return['data']['id_control'];

		$this->actionEfektifitas($id_control, $mitigasi['id_risiko'], $id_mitigasi);

		SetFlash('suc_msg', $return['success']);

		$id_risiko = $mitigasi['id_risiko'];

		$this->backtodraft($id_risiko);

		redirect("panelbackend/risk_control_grc/detail/$id_risiko/$id_control");
	}

	private function actionEfektifitas($id_control, $id_risiko, $id_mitigasi){

		$this->load->model("Mt_risk_efektifitasModel","mtefektifitas");
		$mtefektifitas = $this->mtefektifitas;
		$this->data['mtefektifitasarr'] = $mtefektifitas->getKetEfektifitas();

		$this->load->model("Risk_control_efektifitasModel","controlefektif");

		$suc = array('success'=>1);

		$is_efektif = true;
		unset($this->data['mtefektifitasarr']['']);
		foreach ($this->data['mtefektifitasarr'] as $key => $value) {
			if(!$suc['success'])
				break;

			$record = array();
			$record['id_control'] = $id_control;
			$id_efektifitas = $record['id_efektifitas'] = $value['id_efektifitas'];
			$record['is_iya'] = 1;

			if($value['need_explanation'] && $record['is_iya']){
				$record['keterangan'] = "mitigasi 100% -> move to control";
			}

        	$cek = $this->conn->GetOne("select 1 from risk_control_efektifitas where id_control = ".$this->conn->escape($id_control)." and id_efektifitas = ".$this->conn->escape($value['id_efektifitas']));

        	if($cek)
        		$suc = $this->controlefektif->Update($record, "id_control = ".$this->conn->escape($id_control)." and id_efektifitas = ".$this->conn->escape($value['id_efektifitas']));
        	else
				$suc = $this->controlefektif->Insert($record);

			if($suc['success'] && $value['need_lampiran'] && $record['is_iya']){

				$rows = $this->conn->GetArray("select * from risk_mitigasi_files where id_mitigasi = ".$this->conn->escape($id_mitigasi));

				$this->load->model("Risk_control_efektifitas_filesModel","modelfile1");

				foreach($rows as $upload_data){
					$record = array();
					$record['client_name'] = $upload_data['client_name'];
					$record['file_name'] = $upload_data['file_name'];
					$record['file_type'] = $upload_data['file_type'];
					$record['file_size'] = $upload_data['file_size'];
					$record['id_control'] = $id_control;
					$record['id_efektifitas'] = $id_efektifitas;
					$ret = $this->modelfile1->Insert($record);
				}
			}
		}

		return $suc;
	}

	function isLock(){
		if($this->data['rowheader1']['status_risiko']!='1' && $this->data['rowheader1']['status_risiko']){
			$this->data['editedheader1'] = false;
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			$this->access_role['add'] = false;
			$this->data['row']['is_lock']=0;
			$this->data['is_allow_edit_mitigasi']=false;
			$this->data['is_allow_edit_penanggung_jawab']=false;
			$this->data['is_allow_edit_progress']=false;
			return;
		}

		if($this->Access("view_all_direktorat","panelbackend/risk_risiko_grc"))
			return;
		
		if($this->data['row']['is_lock']=='1' && $this->data['rowheader1']['is_lock']=='1'){
			$this->access_role['delete'] = false;
		}

		if($this->data['rowheader1']['is_lock']=='1'){
			$this->data['editedheader1'] = false;
		}
	}
}
