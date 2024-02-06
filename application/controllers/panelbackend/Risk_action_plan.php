<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_action_plan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_action_planlist";
		$this->viewdetail = "panelbackend/risk_action_plandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_risiko";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Risk Action Plan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Risk Action Plan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Risk Action Plan';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Risk Action Plan';
		}

		$this->load->model("Risk_action_planModel","model");

		$this->load->model("Mt_efektifitasModel","mtefektifitas");
		$mtefektifitas = $this->mtefektifitas;
		$this->data['mtefektifitasarr'] = $mtefektifitas->GetCombo();

		$this->load->model("Mt_status_action_planModel","mtprogress");
		$mtprogress = $this->mtprogress;
		$this->data['pregressarr'] = $mtprogress->GetCombo();
		
		$this->SetAccess(array('panelbackend/risk_scorecard','panelbackend/risk_risiko'));

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			'datepicker'
		);
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama',
				'label'=>'Nama',
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
				'name'=>'menurunkan_dampak_kemungkinan', 
				'label'=>'Menurunkan', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-')+$this->data['menurunkanrr'],
			),
			array(
				'name'=>'id_status_action_plan', 
				'label'=>'Progress', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['pregressarr'],
			),
			array(
				'name'=>'id_status', 
				'label'=>'Status', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtstatusarr'],
			),
			array(
				'name'=>'is_efektif', 
				'label'=>'Efektif ?', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>array(''=>'-pilih-')+listefektifitas(),
			),
		);
	}

	protected function Record($id=null){
		$record = array(
			'nama'=>$this->post['nama'],
			'deskripsi'=>$this->post['deskripsi'],
			'penanggung_jawab'=>$this->post['penanggung_jawab'],
			'id_status_action_plan'=>$this->post['id_status_action_plan'],
			'progress_capaian_kinerja'=>$this->post['progress_capaian_kinerja'],
			'menurunkan_dampak_kemungkinan'=>$this->post['menurunkan_dampak_kemungkinan'],
			'remark'=>$this->post['remark'],
			'biaya'=>Rupiah2Number($this->post['biaya']),
			'cba'=>Rupiah2Number($this->post['cba']),
			'hambatan_kendala'=>$this->post['hambatan_kendala'],
			'penyesuaian_tindakan_mitigasi'=>$this->post['penyesuaian_tindakan_mitigasi'],
			'dead_line'=>$this->post['dead_line'],
		);

		if(!$id)
			$record['id_status'] = 1;

		return $record;
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"dead_line"=>array(
				'field'=>'dead_line',
				'label'=>'Dead Line',
				'rules'=>"required|date",
			),
			"biaya"=>array(
				'field'=>'biaya',
				'label'=>'Biaya',
				'rules'=>"required|numeric",
			),
			"cba"=>array(
				'field'=>'cba',
				'label'=>'Cost Benefit Analysis (CBA)',
				'rules'=>"numeric",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi',
				'label'=>'Deskripsi',
				'rules'=>"max_length[4000]",
			),
			"penanggung_jawab"=>array(
				'field'=>'penanggung_jawab',
				'label'=>'Penanggung Jawab',
				'rules'=>"callback_inlistpegawai|required",
			),
			"id_status_action_plan"=>array(
				'field'=>'id_status_action_plan',
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
	}

    public function inlistpegawai($str)
    {
    	$cek = $this->conn->GetOne("select 1 from mt_pegawai where nid = ".$this->conn->escape($str));

    	if(!$cek){
            $this->form_validation->set_message('penanggung_jawab', 'Pegawai tidak ditemukan');
            return FALSE;
    	}

    	return true;
    }

	public function Index($id_risiko=null, $page=0){

		$this->_beforeDetail($id_risiko,$id);
		$this->_setFilter("id_risiko = ".$this->conn->qstr($id_risiko));
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index"),
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

	public function Add($id_risiko = null){
		$this->Edit($id_risiko);
	}

	public function Edit($id_risiko=null, $id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_risiko,$id);
		$this->data['row'] = $this->model->GetByPk($id);

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
		}

		$this->data['rules'] = $this->Rules();

		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$record['id_risiko'] = $id_risiko;

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

				SetFlash('suc_msg', $return['success']);
				redirect("$this->page_ctrl/detail/$id_risiko/$id");

			} else {
				$this->data['row'] = array_merge($this->data['row'],$record);
				$this->data['row'] = array_merge($this->data['row'],$this->post);

				$this->_afterEditFailed($id);

				$this->data['err_msg'] = "Data gagal disimpan";
			}
		}

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete($id_risiko=null, $id=null){

        $this->model->conn->StartTrans();

		$this->_beforeDetail($id_risiko,$id);

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
			redirect("$this->page_ctrl/index/$id_risiko");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_risiko/$id");
		}

	}

	public function Detail($id_risiko=null, $id=null){

		$this->_beforeDetail($id_risiko,$id);

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	private function actionEfektifitas($status, $id_action_plan, $id_risiko){

		if(!Access("pengajuan_efektif") && !Access("persetujuan_efektif"))
			$this->Error403();

		$this->load->model("Risk_action_plan_efektifitasModel","action_planefektif");

        $this->model->conn->StartTrans();

        $cek = $this->conn->Execute("delete from risk_action_plan_efektifitas where id_action_plan=".$this->conn->escape($id_action_plan));

		if($cek)
			$suc = array('success'=>1);

		$is_efektif = true;
		unset($this->data['mtefektifitasarr']['']);
		foreach ($this->data['mtefektifitasarr'] as $key => $value) {
			if(!$suc['success'])
				break;

			$record = array();
			$record['id_action_plan'] = $id_action_plan;
			$record['id_efektifitas'] = $key;
			$record['is_iya'] = (int)$this->post['efektif'][$key];

			if($is_efektif)
				$is_efektif = (int)$this->post['efektif'][$key];

			$suc = $this->action_planefektif->Insert($record);
		}

		if($suc['success']){
			if($status==3){
				if($is_efektif)
					$r = array("is_efektif"=>3);
				else
					$r = array("is_efektif"=>4);

			}
			else
				$r = array("is_efektif"=>$status);

			$suc = $this->model->Update($r ,"$this->pk = ".$this->conn->qstr($id_action_plan));

			if($suc['success'] && $r['is_efektif']==3){
				$menurunkan = $this->conn->GetOne("select menurunkan_dampak_kemungkinan from risk_action_plan where id_action_plan = ".$this->conn->qstr($id_action_plan));

				if($menurunkan=='D')
					$r = array("action_plan_dampak_penurunan"=>'{{nvl(action_plan_dampak_penurunan,0)+1}}');
				else
					$r = array("action_plan_tingkat_penurunan"=>'{{nvl(action_plan_tingkat_penurunan,0)+1}}');

				$this->load->model("Risk_risikoModel","risiko");

				$suc = $this->risiko->Update($r ,"id_risiko = ".$this->conn->qstr($id_risiko));
			}
		}

        $this->model->conn->CompleteTrans();

        if($suc['success'])
			SetFlash('suc_msg', "Data berhasil disimpan");
		else 
			SetFlash('err_msg',"Data gagal disimpan");
		
		redirect(current_url());

		die();
	}

	protected function _beforeDetail($id=null, $id_action_plan=null){

		if($this->post['act']=='ajukan_efektifitas')
			$this->actionEfektifitas(1, $id_action_plan, $id);

		if($this->post['act']=='tolak_efektifitas')
			$this->actionEfektifitas(2, $id_action_plan, $id);

		if($this->post['act']=='setujui_efektifitas')
			$this->actionEfektifitas(3, $id_action_plan, $id);


		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_risikoModel",'riskrisiko');
		$this->data['rowheader1']  = $this->riskrisiko->GetByPk($id);

		if(!$this->data['rowheader1'])
			$this->NoData();

		$this->_getListTask("risiko", $this->data['rowheader1'], $this->data['editedheader1']);

		$id_scorecard = $this->data['rowheader1']['id_scorecard'];

		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id_scorecard);

		if(!$this->data['rowheader'])
			$this->NoData();

		$this->load->model("Risk_kegiatanModel",'kegiatan');
		$this->data['mtkegiatanarr'] = $this->kegiatan->GetCombo($id_scorecard);
	
		$this->_getListTask("scorecard", $this->data['rowheader'], $this->data['editedheader']);

		$this->data['add_param'] .= $id;
	}

	protected function _beforeEdit(&$record=array(), $id){
		$this->_validAccessTask();
		return true;
	}

	protected function _beforeDelete($id){
		$this->_validAccessTask();
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

		$this->_getListTask("action_plan", $this->data['row'], $this->data['edited']);

		if($this->data['row']['penanggung_jawab']){

			$nid = $this->data['row']['penanggung_jawab'];
			$nama = $this->conn->GetOne("select nama from mt_pegawai where nid = ".$this->conn->escape($nid));

			$this->data['penanggung_jawabarr'] = array($nid=>$nama);
		}

		if($this->data['row']['is_efektif']){
			$id_action_plan = $this->data['row']['id_action_plan'];

			$rowsefektif = $this->conn->GetArray("select * from risk_action_plan_efektifitas where id_action_plan = ".$this->conn->escape($id_action_plan));

			$this->data['row']['efektif'] = array();
			foreach ($rowsefektif as $r) {
				$this->data['row']['efektif'][$r['id_efektifitas']] = (int)$r['is_iya'];
			}
		}
	}

}
