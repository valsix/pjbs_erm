<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_sasaran_kegiatan extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_sasaran_kegiatanlist";
		$this->viewdetail = "panelbackend/risk_sasaran_kegiatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_scorecard";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Sasaran Kegiatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Sasaran Kegiatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Sasaran Kegiatan';
			$this->data['edited'] = false;
		}elseif($this->mode == 'index'){
			$this->data['edited'] = true;
			$this->data['page_title'] = 'Daftar Sasaran Kegiatan';
		}

		$this->load->model("Risk_kpiModel","riskkpi");

		$this->data['riskkpiarr'] = $this->riskkpi->GetCombo();
		unset($this->data['riskkpiarr']['']);

		$this->load->model("Risk_sasaran_kegiatanModel","model");

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Record($id=null){
		$this->AddOption();
		$record =  array(
			'nama'=>$this->post['nama'],
			'deskripsi'=>$this->post['deskripsi'],
			'kpi'=>$this->post['kpi'],
			'owner'=>$this->data['rowheader']['owner'],
			'deskripsi_kpi'=>$this->post['deskripsi_kpi'],
			'id_sasaran_strategis'=>$this->post['id_sasaran_strategis'],
		);

		return $record;
	}

	protected function Rules(){
		return array(
			"name"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"id_kpi[]"=>array(
				'field'=>'id_kpi[]',
				'label'=>'KPI',
				'rules'=>"required",
			),
			"deskripsi_kpi"=>array(
				'field'=>'deskripsi_kpi',
				'label'=>'Deskripsi KPI',
				'rules'=>"max_length[500]",
			),
			"name"=>array(
				'field'=>'nama',
				'label'=>'Nama',
				'rules'=>"required|max_length[200]",
			),
			"deskripsi"=>array(
				'field'=>'deskripsi',
				'label'=>'Deskripsi',
				'rules'=>"max_length[4000]",
			),
			"id_sasaran_strategis"=>array(
				'field'=>'id_sasaran_strategis',
				'label'=>'Sasaran Strategis',
				'rules'=>"required|in_list[".implode(",", array_keys($this->data['sasaranarr']))."]",
			),
		);
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

	public function Index($id_scorecard=null, $id_risiko=null, $id_sasaran_strategis=null, $id_sasaran_kegiatan=null){

		$this->data['rowheader1']['id_sasaran_strategis'] = $id_sasaran_strategis;
		$this->data['rowheader1']['id_sasaran_kegiatan'] = $id_sasaran_kegiatan;

		if($this->post['id_sasaran_strategis'])
			$this->data['rowheader1']['id_sasaran_strategis'] = $this->post['id_sasaran_strategis'];

		if($this->post['id_sasaran_kegiatan'])
			$this->data['rowheader1']['id_sasaran_kegiatan'] = $this->post['id_sasaran_kegiatan'];

		if($this->post['id_kpi'])
			$this->data['rowheader1']['id_kpi'] = $this->post['id_kpi'];

		$this->_beforeDetail($id_scorecard, $id_risiko);

		$this->View($this->viewlist);
	}

	public function Add($id_scorecard=null,$id_risiko=null, $id_sasaran_strategis=null){
		$this->data['rowheader1']['id_sasaran_strategis'] = $id_sasaran_strategis;

		$this->Edit($id_scorecard);
	}

	public function Edit($id_scorecard=null,$id_risiko=null,$id_sasaran_strategis=null,$id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id_scorecard,$id_risiko);

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

			$this->_isValid($record,false);

            $this->_beforeEdit($record,$id);

            $this->_setLogRecord($record,$id);

            $this->model->conn->StartTrans();
			if ($this->data['row'][$this->pk]==$id && $id) {

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

				$id_sasaran_strategis = $this->data['row']['id_sasaran_strategis'];

				redirect("$this->page_ctrl/index/$id_scorecard/0/$id_sasaran_strategis/$id");

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

	protected function AddOption(){

		if(($this->post['id_kpi'])){
			foreach($this->post['id_kpi'] as $k=>$value){
				$v = $value;

				$ada = $this->data['riskkpiarr'][$v];
				if(!$ada && $v){
					$record = array();
					$record['nama'] = $v;

					$sql = $this->conn->InsertSQL("risk_kpi", $record);
					$this->conn->Execute($sql);

					$this->post['id_kpi'][$k] = $_POST['id_kpi'][$k] = $id = $this->conn->GetOne("select id_kpi from risk_kpi where nama = '{$record['nama']}'");

					$this->data['riskkpiarr'][$id] = $record['nama'];
				}
			}
		}
	}

	protected function _afterUpdate($id){
		$ret = $this->_delSertKpi($id);

		return $ret;
	}

	protected function _afterInsert($id){
		$ret = $this->_delSertKpi($id);

		return $ret;
	}

	private function _delSertKpi($id){
		$return = $this->conn->Execute("delete from risk_sasaran_kegiatan_kpi where id_sasaran_kegiatan = ".$this->conn->escape($id));

		if(is_array($this->post['id_kpi'])){
			foreach ($this->post['id_kpi'] as $key => $value) {
				if($return){
					if(!$value)
						continue;

					$record = array();
					$record['id_sasaran_kegiatan'] = $id;
					$record['id_kpi'] = $value;

					$sql = $this->conn->InsertSQL("risk_sasaran_kegiatan_kpi", $record);

	        		if($sql){
					    $return = $this->conn->Execute($sql);
					}
				}
			}
		}
		return $return;
	}

	public function Detail($id_scorecard=null, $id_risiko=null, $id_sasaran_strategis=null, $id=null){

		$this->_beforeDetail($id_scorecard, $id_risiko);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	protected function _beforeDelete($id_scorecard=null, $id_risiko=null, $id_sasaran_strategis=null, $id=null){
		$cek = $this->conn->GetOne("select nomor from risk_risiko where id_sasaran_kegiatan = ".$this->conn->escape($id));

		$return = $this->model->Execute("delete from risk_sasaran_kegiatan_kpi where id_sasaran_kegiatan = ".$this->conn->escape($id));

		if($cek){
			SetFlash('err_msg',"Sasaran kegiatan sudah dipakai di risiko nomor ".$cek);
			redirect("$this->page_ctrl/detail/$id_scorecard/$id_risiko/$id_sasaran_strategis/$id");
			die();
		}

		return true;
	}

	public function Delete($id_scorecard=null, $id_risiko=null, $id_sasaran_strategis=null, $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id_scorecard, $id_risiko);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$return = $this->_beforeDelete($id_scorecard, $id_risiko, $id_sasaran_strategis, $id);

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
			redirect("$this->page_ctrl/index/$id_scorecard/$id_risiko/$id_sasaran_strategis/$id");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id_scorecard/$id_risiko/$id_sasaran_strategis/$id");
		}

	}

	protected function _beforeDetail($id=null, $id_risiko=null){
		
		if(!$id)
			redirect('panelbackend/risk_scorecard/daftarscorecard');
		
		#mengambil dari model karena sudah difilter sesuai akses
		$this->load->model("Risk_scorecardModel",'riskscorecard');
		$this->data['rowheader']  = $this->riskscorecard->GetByPk($id);
		if(!$this->data['rowheader'])
			$this->NoData();

		$owner = $this->data['rowheader']['owner'];

		if($id_risiko){
			$this->load->model("Risk_risikoModel",'riskrisiko');

			$rowheader1  = $this->riskrisiko->GetByPk($id_risiko);

			if($this->data['rowheader1']['id_sasaran_strategis'] == $rowheader1['id_sasaran_strategis'] or !$this->data['rowheader1']['id_sasaran_strategis'])
				$this->data['rowheader1'] = $rowheader1;

			if(!$this->data['rowheader1'])
				$this->NoData();

			$this->_getListTask("risiko", $this->data['rowheader1'], $temp=true);
		}

		if($owner){
			$this->data['ownerarr'][$owner] = $this->conn->GetOne("select nama from mt_sdm_jabatan where id_jabatan = ".$this->conn->escape($owner));

			if($this->data['rowheader1']['id_sasaran_strategis'])
				$id_sasaran_strategis = $this->data['rowheader1']['id_sasaran_strategis'];
			elseif($id_risiko)
				$id_sasaran_strategis = $this->conn->GetOne("select id_sasaran_strategis from risk_risiko where id_risiko = ".$this->conn->escape($id_risiko));

			$this->data['mtkegiatanarr'] = $this->model->GetCombo($id_sasaran_strategis);
			
			$this->load->model("Risk_sasaran_strategisModel","msasaran");

			$this->data['sasaranarr'] = $this->msasaran->GetCombo($owner);
			
			$this->data['sasaranarr'][$id_sasaran_strategis] = $this->msasaran->GetNama($id_sasaran_strategis);
		}

		if(!$id_risiko)
			$id_risiko = 0;

		$id_sasaran_strategis = 0;
		if($this->data['rowheader1']['id_sasaran_strategis'])
			$id_sasaran_strategis = $this->data['rowheader1']['id_sasaran_strategis'];

		$id_sasaran_strategis = $this->data['rowheader1']['id_sasaran_strategis'];
		$id_sasaran_kegiatan = $this->data['rowheader1']['id_sasaran_kegiatan'];

		$this->data['rowheader1']['kpi_strategis'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_strategis_kpi s join risk_kpi k on s.id_kpi = k.id_kpi 
			where id_sasaran_strategis = ".$this->conn->escape($id_sasaran_strategis));

		$this->data['rowheader1']['kpi_kegiatan'] = $this->conn->GetArray("select 
			k.* 
			from risk_sasaran_kegiatan_kpi s 
			join risk_kpi k on s.id_kpi = k.id_kpi 
			where id_sasaran_kegiatan = ".$this->conn->escape($id_sasaran_kegiatan));

		$this->data['add_param'] .= $id.'/'.$id_risiko.'/'.$id_sasaran_strategis;

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
	}

	protected function _afterDetail($id=null){
		if(!$this->data['row'])
			$this->data['row'] = $this->data['rowheader1'];



		if(!($this->data['row']['id_kpi'])){
			$id_kpiarr = array();

			$mtsdmkpiarr = $this->conn->GetArray("select id_kpi from risk_sasaran_kegiatan_kpi where id_sasaran_kegiatan = ".$this->conn->escape($id));

			foreach ($mtsdmkpiarr as $key => $value) {
				$id_kpiarr[]=$value['id_kpi'];
			}

			$this->data['row']['id_kpi'] = $id_kpiarr;
		}
	}
}
