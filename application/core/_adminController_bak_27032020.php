<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class _adminController extends _Controller{
	public $access_role = array();
	public $access_role_custom = array();
	public $page_escape = array('panelbackend/login','panelbackend/publ1c','panelbackend/ajax','panelbackend/filemanager');
	public $is_administrator = false;
	public $is_coordinator = false;
	public $is_owner = false;
	public $is_review = false;
	public $is_bod = false;
	public $list_order = '';
	public $private = true;
	public $limit = 10;
	public $limit_arr = array('10','30','50','100');
	public function __construct()
	{

		parent::__construct();
		
		if($_GET['debug']=='1'){
			$this->conn->debug = 1;
		}

		$this->SetConfig();

		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";


		$this->load->model('AuthModel', 'auth');
		$this->load->library("UI");

		$this->sso = $this->config->item('sso');

		$this->helper("a");
		$this->helper("s");

		$this->SetAccessRole();

		$this->init();

		$this->InitAdmin();
	}

	protected function SetConfig(){
		$sql = "select * from public_sys_setting";
		$rows = $this->conn->GetArray($sql);

		$configarr = array();
		foreach($rows as $r){
			if(strstr($r['nama'],'.')!==false){
				list($nama, $nama1) = explode(".",$r['nama']);
				$configarr[$nama][$nama1] = trim($r['isi']);
			}else{
				$configarr[$r['nama']] = trim($r['isi']);
			}
		}

		foreach ($configarr as $key => $value) {
			$this->config->set_item($key, $value);
		}


		$this->data['collapse'] = $configarr['collapse'];
	}

	protected function init(){
		$this->data['show_button'] = true;
		$this->data['sekarang'] = $this->conn->GetOne("select sysdate from dual");

		if($_SESSION[SESSION_APP]['group_id']==1){
			$this->is_administrator = true;
			$this->data['is_administrator'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==2){
			$this->is_coordinator = true;
			$this->data['is_coordinator'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==3){
			$this->is_owner = true;
			$this->data['is_owner'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==24){
			$this->is_reviewer = true;
			$this->data['is_reviewer'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==25){
			$this->is_bod = true;
			$this->data['is_bod'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==26){
			$this->is_audit = true;
			$this->data['is_audit'] = true;
		}
		if($_SESSION[SESSION_APP]['group_id']==27){
			$this->is_kepatuhan = true;
			$this->data['is_kepatuhan'] = true;
		}
		if($_SESSION[SESSION_APP]['pic'])
			$this->data['owner'] = $_SESSION[SESSION_APP]['pic'];
		else
			$this->data['owner'] = '0';

		$this->data['is_allow_tgl_efektif'] = true;
	}


	private function InitAdmin(){

		$this->data['listjk'] = array(''=>'-pilih-','1'=>'Laki-laki','2'=>'Perempuan');

		$this->load->model("Mt_sdm_jabatanModel",'mjabatan');

		$this->data['menurunkanrr'] = array('K'=>'K','D'=>'D');


		#dampak
		$this->load->model("Mt_risk_dampakModel","mtdampakrisiko");

		$rowsdampak = $this->mtdampakrisiko->GArray("*","order by id_dampak asc");
		$this->data['mtriskdampak'] = $rowsdampak;

		$this->data['mtdampakrisikoarr'] = array(''=>'-pilih-');
		foreach($rowsdampak as $r){
			$this->data['mtdampakrisikoarr'][$r['id_dampak']] = $r['kode'].'-'.$r['nama'];
		}


		#kemungkinan
		$this->load->model("Mt_risk_kemungkinanModel","mtkemungkinan");

		$rowskemungkinan = $this->mtkemungkinan->GArray("*","order by id_kemungkinan asc");
		$this->data['mtriskkemungkinan'] = array_reverse($rowskemungkinan);

		$this->data['mtkemungkinanarr'] = array(''=>'-pilih-');
		foreach($rowskemungkinan as $r){
			$this->data['mtkemungkinanarr'][$r['id_kemungkinan']] = $r['kode'].'-'.$r['nama'];
		}

		
		#tingkat
		$this->load->model("Mt_risk_tingkatModel",'mttingkatdampak');

		$rowstingkat = $this->mttingkatdampak->GArray("*","order by id_tingkat desc");

		$this->data['mttingkatdampakarr'] = array(''=>'-pilih-');
		foreach($rowstingkat as $r){
			$this->data['mttingkatdampakarr'][$r['id_tingkat']] = $r['nama'];
		}


		#matrix
		$this->load->model("Mt_risk_matrixModel","mtriskmatrix");
		$rowsmatrix = $this->mtriskmatrix->GArray("*");

		$tempd = array();
		foreach($rowsdampak as $r){
			$tempd[$r['id_dampak']] = $r;
		}
		
		$tempk = array();
		foreach($rowskemungkinan as $r){
			$tempk[$r['id_kemungkinan']] = $r;
		}
		
		$tempt = array();
		foreach($rowstingkat as $r){
			$tempt[$r['id_tingkat']] = $r;
		}

		$mtriskmatrixarr = array();
		foreach($rowsmatrix as $r){
			$r['nama'] = $tempt[$r['id_tingkat']]['nama'];
			$r['warna'] = $tempt[$r['id_tingkat']]['warna'];
			$r['kode_kemungkinan'] = $tempk[$r['id_kemungkinan']]['kode'];
			$r['kode_dampak'] = $tempd[$r['id_dampak']]['kode'];
			$mtriskmatrixarr[] = $r;
		}
		
		$this->data['mtriskmatrix'] = $this->data['mtriskmatrixarr'] = $mtriskmatrixarr;
		$this->data['riskmatrixtingkat'] = $this->mtriskmatrix->getTingkat($mtriskmatrixarr);
		$this->data['mttingkatdampakarr1'] = $this->data['mtriskmatrixarr'];

		$this->data['riskapertite'] = $this->mtriskmatrix->minRiskApertite();

		#kajian risiko
		$this->load->model("Mt_risk_kajian_risikoModel","mtkajianrisiko");

		$rowskajianrisiko = $this->mtkajianrisiko->GArray("*","order by id_kajian_risiko asc");
		$this->data['rowskajianrisiko'] = $rowskajianrisiko;

		$this->data['mtjeniskajianrisikoarr'] = array(''=>'-pilih-');
		foreach($rowskajianrisiko as $r){
			$this->data['mtjeniskajianrisikoarr'][$r['id_kajian_risiko']] = $r['nama'];
		}

		#status pengajuan
		$this->load->model("Mt_status_pengajuanModel","mtstatus");
		$this->data['mtstatusarr'] = $this->mtstatus->GetCombo();



		#tanggal efektif
		if(!$_SESSION[SESSION_APP]['tgl_efektif'])
			$_SESSION[SESSION_APP]['tgl_efektif'] = date('d-m-Y');

		$this->data['tgl_efektif'] = $_SESSION[SESSION_APP]['tgl_efektif'];
		
		if($this->post['act']=='set_efektif'){
			$_SESSION[SESSION_APP]['tgl_efektif'] = $this->post['key'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['tgl_efektif'] = $this->post['key'];
			redirect(current_url());
		}

		#tahun efektif
		if(!$_SESSION[SESSION_APP]['tahun_efektif'])
			$_SESSION[SESSION_APP]['tahun_efektif'] = date('Y');

		$this->data['tahun_efektif'] = $_SESSION[SESSION_APP]['tahun_efektif'];
		
		if($this->post['act']=='set_tahun_efektif'){
			$_SESSION[SESSION_APP]['tahun_efektif'] = $this->post['key'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['tahun_efektif'] = $this->post['key'];
			redirect(current_url());
		}

		#action
		if(strstr($this->post['act'],'task') !== false){
			$page = str_replace("task", "", $this->post['act']);
			if($page=='mitigasi'){
				$this->_actionKonfirmasi();
			}elseif($page=='control'){
				$this->_actionKonfirmasiControl();
			}else{
				$this->_actionTaskRisiko();
			}
		}

		if($this->post['act']=='kirim_review_risk'){
			$id=$this->uri->segment(4);
			$this->data['row'] = $this->conn->GetRow("select * from risk_risiko where id_risiko = ".$this->conn->escape($id));
			$this->_kirimReviewRisk($this->data['row']['id_scorecard'],$id);
		}

		if($this->post['act']=='kirim_rekomendasi_risk'){
			$id=$this->uri->segment(4);
			$this->data['row'] = $this->conn->GetRow("select * from risk_risiko where id_risiko = ".$this->conn->escape($id));
			$this->_kirimRekomRisk($this->data['row']['id_scorecard'],$id);
		}

		if($this->post['act']=='unlock'){
			if(!$this->access_role['view_all_direktorat'])
				$this->Error403();

			$cek = $this->unlock();

			if($cek)
				SetFlash('suc_msg', "Data telah di unlock");
			else
				SetFlash('err_msg', "Data gagal di unlock");

			redirect(current_url());
		}


		$this->plugin_arr[] = 'datepicker';
	}

	private function _kirimReviewRisk($id_scorecard=null,$id=null){
		$this->load->model("Risk_risikoModel","risiko");
		$return = $this->risiko->Update(array('is_lock'=>'1','review_is_verified'=>'3'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risk_risiko',
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

	private function _kirimRekomRisk($id_scorecard=null,$id=null){
		$this->load->model("Risk_risikoModel","risiko");
		$return = $this->risiko->Update(array('is_lock'=>'1','rekomendasi_is_verified'=>'3'),"id_risiko=".$this->conn->escape($id));

		$record = array(
			'page'=>'risk_risiko',
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

	protected function SetAccess($pagearr=array()){
		if(!is_array($pagearr))
			$pagearr = array($pagearr);

		foreach ($pagearr as $v) {
			$this->access_role_custom[$v] = $this->auth->GetAccessRole($v);
		}
	}

	protected function View($view='')
	{
		if(!empty($this->layout)){
			$this->data['content1']=$this->PartialView($view,true);
			parent::View($this->layout);
		}else{
			parent::View($view);
		}
	}
	// set access for url and action
	protected function SetAccessRole($action=""){
		// ceck referer from host or not
		if(
		static::$referer == true and
		str_replace('/','',str_replace('panelbackend','',str_replace('index.php','',$_SERVER['HTTP_REFERER'])))
		<>
		str_replace('/','',str_replace('panelbackend','',str_replace('index.php','',base_url())))
		)
		{

			$this->Error404();
			exit();
		}

		if(in_array($this->page_ctrl, $this->page_escape))
			return true;

		// set private area
		if($this->private)
		{
			// ceck login
			if(!$_SESSION[SESSION_APP]['login']){
				$_SESSION[SESSION_APP]['curr_page'] = uri_string();
				redirect('panelbackend/login','client');
			}

			/*if($this->sso['auth_page'] && $_SESSION[SESSION_APP]['group_id']!=1){

				$username = $_SESSION[SESSION_APP]['username'];
				$credential = $_SESSION[SESSION_APP]['credential'];
            	$respon = $this->auth->autoAuthenticate($username,$credential);

            	if(!($respon->RESPONSE == "1" or $respon->RESPONSE == "PAGE")){

					unset($_SESSION[SESSION_APP]);

					$_SESSION[SESSION_APP]['curr_page'] = uri_string();
           			$_SESSION[SESSION_APP]['error_login'] = trim($respon->RESPONSE_MESSAGE,'.')." lewat <a href='http://portal.pjbservices.com'>portal.pjbservices.com</a>";

					redirect('panelbackend/login','client');
            	}
			}*/
		}


		if($this->page_ctrl=='panelbackend/home' && !empty($_SESSION[SESSION_APP]['user_id']))
			return true;

		if($_SESSION[SESSION_APP]['user_id']!=1){

			$this->is_super_admin = false;

			if($this->page_ctrl=='panelbackend/page' or $this->page_ctrl=='panelbackend/pageone'){
				$this->access_role = $this->auth->GetAccessRole('panelbackend/page');
			}else{
				$this->access_role = $this->auth->GetAccessRole($this->page_ctrl);
			}

			$this->access_role_custom[$this->page_ctrl] = $this->access_role;

			if(!$this->access_role[$this->mode]){
				$str = '';

				if(ENVIRONMENT=='development')
					$str = "akses : ".print_r($this->access_role,true);

				$this->Error403($str);
				exit();
			}
		}else{
			$this->is_super_admin = true;
		}
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

		if($this->post['act'] && $this->post['act']<>'save'){
			
			if($this->data['add_param']){
				$add_param = '/'.$this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(),"/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGrid(
			$param
		);

		return $respon;
	}

	protected function _getListPrint(){
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param=array(
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		$respon = $this->model->SelectGridPrint($param);

		return $respon;
	}

	protected function _resetList(){
		if($this->post['act']=='list_reset'){
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_limit']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']);
			unset($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);
		}
	}

	protected function _limit(){
		if($this->post['act']=='list_limit' && $this->post['list_limit']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_limit']=$this->post['list_limit'];
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_limit']){
			$this->limit = $_SESSION[SESSION_APP][$this->page_ctrl]['list_limit'];
		}

		return $this->limit;
	}

	protected function _order(){

		if($this->post['act']=='list_sort' && $this->post['list_sort']){

			$_SESSION[SESSION_APP][$this->page_ctrl]['list_order']=$this->post['list_order'];
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']=$this->post['list_sort'];
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_sort']){
			$order .= $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_order'] && $order){
			$order .= ' '. $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];
		}

		$this->data['list_sort'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_sort'];
		$this->data['list_order'] = $_SESSION[SESSION_APP][$this->page_ctrl]['list_order'];

		replaceSingleQuote($this->list_order);

		if($this->list_order && $order)
			$this->list_order .= ", ".$order;
		elseif($order)
			$this->list_order = $order;

		if(!$this->list_order){
			if($this->model->order_default)
				return $this->model->order_default;
			else
				return $this->model->pk." asc ";
		}

		if($this->list_order)
			return $this->list_order;

		return null;
	}

	protected function _setFilter($filter=''){
		if($filter){
			$this->filter .= ' and '. $filter;
		}
	}

	protected function _getFilter(){
		$this->xss_clean = true;

		$this->FilterRequest();

		$filter_arr = array();

		if($this->post['act']=='list_filter' && $this->post['list_filter']){
			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = $this->post['list_filter'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'],$this->post['list_filter']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter']){

			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] as $r){
				$key = $r['key'];
				$filter_arr1 = array();

				foreach($r['values'] as $k=>$v){
					$k=str_replace("_____", ".", $k);

					replaceSingleQuote($v);
					replaceSingleQuote($k);
					if(!($v==='' or $v===null or $v===false))
						$filter_arr1[] = 'a.'.$key ." = '$v'";
				}

				$filter_str = implode(' or ',$filter_arr1);

				if($filter_str){
					$filter_arr[]="($filter_str)";
				}
			}
		}

		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array();
		}

		if($this->post['act']=='list_search' && $this->post['list_search_filter']){
			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = $this->post['list_search_filter'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'],$this->post['list_search_filter']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter'] as $k=>$v){
				$k=str_replace("_____", ".", $k);

				if(!($v==='' or $v===null or $v===false)){
					replaceSingleQuote($v);
					replaceSingleQuote($k);

					$filter_arr[]="$k='$v'";
				}
			}
		}




		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
			$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array();
		}

		if($this->post['act']=='list_search' && $this->post['list_search']){

			if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = $this->post['list_search'];
			}else{
				$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'],$this->post['list_search']);

			}
		}

		if($_SESSION[SESSION_APP][$this->page_ctrl]['list_search']){
			foreach ($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] as $k=>$v){
				$k=str_replace("_____", ".", $k);

				replaceSingleQuote($v);
				replaceSingleQuote($k);

				if(trim($v)!=='' && in_array($k, $this->arrNoquote)){
					$filter_arr[]="$k=$v";
				}else if($v!==''){
					$v = strtolower($v);
					$filter_arr[]="lower($k) like '%$v%'";
				}
			}
		}

		$this->data['filter_arr'] = array_merge($_SESSION[SESSION_APP][$this->page_ctrl]['list_search'],$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']);

		if(($filter_arr)){
			$this->filter .= ' and '.implode(' and ', $filter_arr);
		}

		return $this->filter;
	}

	protected function _setLogRecord(&$array,$is_update=true){
		$datenow = '{{'.$this->conn->sysTimeStamp.'}}';
		$user_id = $_SESSION[SESSION_APP]['user_id'];
		if(!$is_update){
			$array['created_date']=$datenow;
			$array['created_by']=$user_id;
		}
		$array['modified_date']=$datenow;
		$array['modified_by']=$user_id;
	}

	public function Index($page=0){
		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getList($page);

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

	public function go_print(){

		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getListPrint();

		$this->View($this->viewprint);
	}

	public function PrintDetail($id=null){

		$this->data['row'] = $this->model->GetByPk($id);

		$this->_getDetailPrint($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->View($this->viewprintdetail);
	}

	public function Add(){
		$this->Edit();
	}

	protected function _isValid($record=array(), $show_error=true){
		if(!is_array($this->data['rules']))
			return;

		$rules = array_values($this->data['rules']);

		$this->form_validation->set_rules($rules);

		if (count($rules) && $this->form_validation->run() == FALSE)
		{
			if($show_error){
				$this->data['err_msg'] = validation_errors();
			}

			$this->data['row'] = array_merge($this->data['row'],$record);

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}
	}

	protected function Halt($msg){
		if($msg){

			if($this->data['err_msg'])
				$this->data['err_msg'] .= "<br/>".$msg;
			else
				$this->data['err_msg'] = $msg;

			$this->_afterDetail($this->data['row'][$this->pk]);

			$this->View($this->viewdetail);
			exit();
		}

	}

	protected function _getDetailPrint($id){

	}

	public function Edit($id=null){

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$this->_beforeDetail($id);

		$this->data['idpk'] = $id;

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
			if (trim($this->data['row'][$this->pk])==trim($id) && trim($id)) {

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
				redirect("$this->page_ctrl/detail/$id");

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

	public function Detail( $id=null){

		$this->_beforeDetail($id);

		$this->data['row'] = $this->model->GetByPk($id);

		if (!$this->data['row'])
			$this->NoData();

		$this->_afterDetail($id);

		$this->View($this->viewdetail);
	}

	public function Delete( $id=null){

        $this->model->conn->StartTrans();

        $this->_beforeDetail($id);

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
			redirect("$this->page_ctrl");
		}
		else {
			SetFlash('err_msg',"Data gagal didelete");
			redirect("$this->page_ctrl/detail/$id");
		}

	}

	protected function _beforeEdit(&$record=array(), $id){
	}

	protected function _afterEditSucceed($id=null){

	}

	protected function _afterEditFailed($id=null){

	}

	protected function _beforeDetail($id){

	}

	protected function _afterDetail($id){

	}

	protected function _beforeDelete($id){
		return true;
	}

	protected function _afterDelete($id){
		return true;
	}

	protected function _beforeUpdate($record, $id=null){
		return true;
	}

	protected function _afterUpdate($id){
		return true;
	}

	protected function _beforeInsert($id){
		return true;
	}

	protected function _afterInsert($id){
		return true;
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama',
				'label'=>'Kategori',
				'width'=>"auto"
			),
		);
	}

	protected function Record($id){
		return array(
			'nama'=>$this->post['nama']
		);
	}

	protected function Rules(){
		return array(
		   'nama'=>array(
				 'field'   => 'nama',
				 'label'   => 'Kategori',
				 'rules'   => 'required'
			  ),
		);
	}

	public function NoData($str='Data tidak ditemukan.'){
		$this->data['error_str']=$str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error404");
		exit();
	}

	public function Error404($str=''){
		$this->data['error_str']=$str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error404");
		exit();
	}

	public function Error403($str=''){
		$this->data['error_str']=$str;
		$this->layout = "panelbackend/layout1";
		$this->view("panelbackend/error403");
		exit();
	}

	protected function ReExecuteTask($id_risiko=null, $is_send=false){
		if($is_send){
			$rows = $this->conn->GetRows("select * from risk_task where id_risiko=".$this->conn->escape($id_risiko)." and is_pending = '1' order by created_date");

			foreach ($rows as $record) {
				unset($record['is_pending']);
				unset($record['id_task']);

				$this->InsertTask($record);
			}
		}

		$this->conn->Execute("delete from risk_task where id_risiko=".$this->conn->escape($id_risiko)." and is_pending = '1'");
	}

	protected function InsertTask($record){
		$this->load->model("Risk_taskModel",'modeltask');
		
		$created_by = $record['created_by'];

  		$this->_setLogRecord($record,null);

  		if($created_by)
  			$record['created_by'] = $created_by;

		$record['group_id']=$_SESSION[SESSION_APP]['group_id'];

		$return = $this->modeltask->Insert($record);

		if($return['success'] && !$record['is_pending']){
			$this->SendEmailNotif($record['deskripsi'], $record['id_risiko'], $record['id_status_pengajuan'], $record['untuk'],$return['data']['id_task']);
		}

		return $return;
	}

	protected function SendEmailNotif($deskripsi="", $id_risiko=null, $id_status_pengajuan=null, $untuk=null, $id_task=null){

		$recipientsarr = array();

		$rows = $this->auth->PenerimaByStatus($id_risiko,$id_status_pengajuan, $untuk);

		foreach($rows as $r){
			$recipientsarr[] = $r['email'];
		}

		$row = $this->conn->GetRow("select r.*, s.owner from risk_risiko r 
			join risk_scorecard s on r.id_scorecard = s.id_scorecard 
			where id_risiko=".$this->conn->escape($id_risiko));

		$id_scorecard = $row['id_scorecard'];
		$nama = $row['nama'];
		$owner = $row['owner'];

		$body = "Salam
		<br/>
		<br/>
		<b>Risiko : </b> $nama<br/>".
		($id_status_pengajuan?"<b>Status Pengajuan : </b>".strtolower($this->data['mtstatusarr'][$id_status_pengajuan]):"").
		"<br/><br/>
		<i>".$deskripsi."</i>
		<br/>".
		"<a href='".site_url("panelbackend/home/task/$id_task")."'>Selengkapnya</a>";

		$this->curl("panelbackend/publ1c/send_email", array(
			'subject'=>"[".($id_status_pengajuan?strtoupper($this->data['mtstatusarr'][$id_status_pengajuan]):$rdeskripsi)."] ".$nama,
			'body'=>$body,
			'recipients'=>implode(",",$recipientsarr),
		));
	}

	protected function _actionTaskRisiko(){
		$page_ctrl = "panelbackend/risk_risiko";
			

		$keterangan = $this->post['keterangan']['risiko'];
		$id_risiko = $this->post['id']['risiko'];
		$id_status_pengajuant = $id_status_pengajuan = $this->post['id_status_pengajuan']['risiko'];

		if(
		!($this->Access('pengajuan',$page_ctrl) && $id_status_pengajuan=='2')
		and
		!($this->Access('persetujuan',$page_ctrl) && ($id_status_pengajuan=='5' or $id_status_pengajuan=='4'))
		and
		!($this->Access('penerusan',$page_ctrl) && ($id_status_pengajuan=='4' or $id_status_pengajuan=='3'))
		){
			SetFlash('err_msg', "Anda tidak mempunyai akses");
			redirect(current_url());
			die();
		}

		if (!$keterangan or !$id_risiko or !$id_status_pengajuan)
		{
			SetFlash('err_msg', "Data tidak valid");
			redirect(current_url());
			die();
		}
		else{
			$id_scorecard = $this->conn->GetOne("select id_scorecard from risk_risiko where id_risiko = ".$this->conn->escape($id_risiko));


            $this->conn->StartTrans();

            #start urusan interdependent
			#jika diteruskan dicek dulu interdependent atau tidak
			if($id_status_pengajuan==3){

				$check_interdependent = $this->checkApproveInterdependent($this->post['id']['risiko']);

				if($check_interdependent===0){
					$id_status_pengajuant = 6;
					$is_pending = 1;
					$keterangant = "Menunggu konfirmasi interdependent";
				}
				elseif($check_interdependent===2){
					$id_status_pengajuant = 4;
					$is_pending = 1;
					$keterangant = "Interdependent menolak";
				}
			
				if($check_interdependent===0 or $check_interdependent===2){
					$record = array(
						'page'=>'risiko',
						'deskripsi'=>$keterangant,
						'id_status_pengajuan'=>$id_status_pengajuant,
						'id_risiko'=>$id_risiko,
						'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
					);

					$return = $this->InsertTask($record);
				}

	            if($id_status_pengajuant==6){

	            	$owner = $this->conn->GetOne("select owner 
	            		from risk_risiko r 
	            		join risk_scorecard s on r.id_scorecard = s.id_scorecard
	            		where id_risiko = ".$this->conn->escape($id_risiko));
	            	
					#memberi notif kepada interdependent
					$sql = "select id_mitigasi, penanggung_jawab 
					from risk_mitigasi 
					where id_risiko = ".$this->conn->escape($id_risiko)."
					and penanggung_jawab <> ".$this->conn->escape($owner)."
					and id_mitigasi not in (
					select id_mitigasi 
					from risk_task 
					where id_risiko = ".$this->conn->escape($id_risiko)." and page='mitigasi' and id_status_pengajuan = '5'
					) and status_konfirmasi='0'";

					$rows = $this->conn->GetRows($sql);

					foreach($rows as $r1){
						$return = $this->TaskInterdependentMitigasi($id_risiko, $r1['id_mitigasi'], "Anda Ditunjuk Sebagai Penanggung Jawab Control", $r1['penanggung_jawab'], $id_status_pengajuant);
					}

					$sql = "select id_control, penanggung_jawab 
					from risk_control 
					where id_risiko = ".$this->conn->escape($id_risiko)."
					and penanggung_jawab <> ".$this->conn->escape($owner)."
					and id_control not in (
					select id_control 
					from risk_task 
					where id_risiko = ".$this->conn->escape($id_risiko)." and page='control' and id_status_pengajuan = '5'
					) and status_konfirmasi='0'";

					$rows = $this->conn->GetRows($sql);

					foreach($rows as $r1){
						$return = $this->TaskInterdependentControl($id_risiko, $r1['id_control'], "Anda Ditunjuk Sebagai Penanggung Jawab Control", $r1['penanggung_jawab'], $id_status_pengajuant);
					}
	            }
			}

			#end urusan interdependent

			//$this->ReExecuteTask($id_risiko);

			$cek = $this->conn->GetOne("select 1 
				from risk_risiko 
				where id_risiko = ".$this->conn->escape($id_risiko)."
				and is_lock <> '1'");
			if($cek){
				$record = array(
					'page'=>'risiko',
					'deskripsi'=>$keterangan,
					'id_status_pengajuan'=>$id_status_pengajuan,
					'id_risiko'=>$id_risiko,
					'is_pending'=>$is_pending,
					'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
				);
			}else{
				$cek = $this->conn->GetOne("select 1 
					from risk_control
					where id_risiko = ".$this->conn->escape($id_risiko)."
					and is_lock <> '1'");
				if($cek){
					$record = array(
						'page'=>'risiko',
						'deskripsi'=>$keterangan,
						'id_status_pengajuan'=>$id_status_pengajuan,
						'id_risiko'=>$id_risiko,
						'is_pending'=>$is_pending,
						'url'=>"panelbackend/risk_control/index/$id_risiko"
					);
				}else{
					$cek = $this->conn->GetOne("select 1 
					from risk_mitigasi
					where id_risiko = ".$this->conn->escape($id_risiko)."
					and is_lock <> '1'");

					if($cek){
						$record = array(
							'page'=>'risiko',
							'deskripsi'=>$keterangan,
							'id_status_pengajuan'=>$id_status_pengajuan,
							'id_risiko'=>$id_risiko,
							'is_pending'=>$is_pending,
							'url'=>"panelbackend/risk_mitigasi/index/$id_risiko"
						);
					}elseif(strstr(current_url(),'risk_evaluasi')!==false){
						$record = array(
							'page'=>'risiko',
							'deskripsi'=>$keterangan,
							'id_status_pengajuan'=>$id_status_pengajuan,
							'id_risiko'=>$id_risiko,
							'is_pending'=>$is_pending,
							'url'=>"panelbackend/risk_evaluasi/detail/$id_scorecard/$id_risiko"
						);
					}else{
						$record = array(
							'page'=>'risiko',
							'deskripsi'=>$keterangan,
							'id_status_pengajuan'=>$id_status_pengajuan,
							'id_risiko'=>$id_risiko,
							'is_pending'=>$is_pending,
							'url'=>"panelbackend/risk_risiko/detail/$id_scorecard/$id_risiko"
						);
					}
				}
			}
			$return = $this->InsertTask($record);


			if ($return['success']) {

				$r = array('id_status_pengajuan'=>$id_status_pengajuant);

            	$this->_setLogRecord($r,$this->post['id']['risiko']);

				$this->load->model('Risk_risikoModel','modelpage');

				$this->modelpage->Update($r, $this->modelpage->pk." = ".$this->conn->qstr($id_risiko));

				if($id_status_pengajuan==5){
					$this->conn->Execute("update risk_risiko set is_lock = 1 where id_risiko = ".$this->conn->escape($id_risiko));
					$this->conn->Execute("update risk_control set is_lock = 1 where id_risiko = ".$this->conn->escape($id_risiko));
					$this->conn->Execute("update risk_mitigasi set is_lock = 1 where id_risiko = ".$this->conn->escape($id_risiko));
				}elseif($id_status_pengajuan==4 && $this->access_role['view_all_direktorat']){
					$this->conn->Execute("update risk_mitigasi set is_lock = 0 where id_risiko = ".$this->conn->escape($id_risiko));
				}

            	$this->model->conn->CompleteTrans();

				SetFlash('suc_msg', "Task berhasil dikirim");
				redirect(current_url());
				die();
			}
			else{

            	$this->model->conn->CompleteTrans();
            	
				SetFlash('err_msg', "Task gagal dikirim");
				redirect(current_url());
				die();
			}
		}
	}

	protected function TaskInterdependentMitigasi($id_risiko=null, $id_mitigasi=null, $msg=null, $penanggung_jawab=null, $id_status_pengajuan=6){

		$record = array(
			'page'=>'mitigasi',
			'deskripsi'=>$msg,
			'id_mitigasi'=>$id_mitigasi,
			'id_risiko'=>$id_risiko,
			'id_status_pengajuan'=>$id_status_pengajuan,
			'untuk'=>$penanggung_jawab,
			'url'=>"panelbackend/risk_mitigasi/edit/$id_risiko/{$id_mitigasi}"
		);

		$return = $this->InsertTask($record);

		if($return['success']){

			$jabatanarr = $this->auth->GetChildJabatan($penanggung_jawab, false);

			foreach($jabatanarr as $key=>$id_jabatan){
				$record = array(
					'page'=>'mitigasi',
					'id_risiko'=>$id_risiko,
					'id_mitigasi'=>$id_mitigasi,
					'deskripsi'=>"Pimpinan ".$msg,
					'id_status_pengajuan'=>$id_status_pengajuan,
					'untuk'=>$id_jabatan,
					'url'=>"panelbackend/risk_mitigasi/edit/$id_risiko/{$id_mitigasi}"
				);

				$return = $this->InsertTask($record);
			}
		}

		return $return;
	}

	protected function TaskInterdependentControl($id_risiko=null, $id_control=null, $msg=null, $penanggung_jawab=null, $id_status_pengajuan=6){

		$record = array(
			'page'=>'control',
			'deskripsi'=>$msg,
			'id_control'=>$id_control,
			'id_risiko'=>$id_risiko,
			'id_status_pengajuan'=>$id_status_pengajuan,
			'untuk'=>$penanggung_jawab,
			'url'=>"panelbackend/risk_control/edit/$id_risiko/{$id_control}"
		);

		$return = $this->InsertTask($record);

		if($return['success']){

			$jabatanarr = $this->auth->GetChildJabatan($penanggung_jawab, false);

			foreach($jabatanarr as $key=>$id_jabatan){
				$record = array(
					'page'=>'control',
					'id_risiko'=>$id_risiko,
					'id_control'=>$id_control,
					'deskripsi'=>"Pimpinan ".$msg,
					'id_status_pengajuan'=>$id_status_pengajuan,
					'untuk'=>$id_jabatan,
					'url'=>"panelbackend/risk_control/edit/$id_risiko/{$id_control}"
				);

				$return = $this->InsertTask($record);
			}
		}

		return $return;
	}

	protected function _actionKonfirmasi($is_plain){

		$this->load->library('form_validation');

		$id_risiko = $this->post['id']['risiko'];
		$id_mitigasi = $this->post['id']['mitigasi'];
		$id_status_pengajuan = $this->post['id_status_pengajuan']['mitigasi'];
		$keterangan = $this->post['keterangan']['mitigasi'];


		if (!$keterangan or !$id_mitigasi or !$id_status_pengajuan)
		{
			SetFlash('err_msg', "Data tidak valid");
			redirect(current_url());
			die();
		}
		else{

            $this->conn->StartTrans();

            if(!$id_risiko)
				$id_risiko = $this->conn->GetOne("select id_risiko from risk_mitigasi where id_mitigasi = ".$this->conn->escape($this->post['id']['mitigasi']));

			$record = array(
				'page'=>'mitigasi',
				'deskripsi'=>$keterangan,
				'id_status_pengajuan'=>$id_status_pengajuan,
				'id_risiko'=>$id_risiko,
				'id_mitigasi'=>$id_mitigasi,
				'url'=>"panelbackend/risk_mitigasi/edit/$id_risiko/$id_mitigasi"
			);

			$return = $this->InsertTask($record);

			if($return['success']){

				$status_konfirmasi = 1;
				if($id_status_pengajuan==4){
					$status_konfirmasi = 2;
				}
				
				$r = array('status_konfirmasi'=>$status_konfirmasi);

				if($status_konfirmasi==1)
					$r['is_lock'] = 1;

            	$this->_setLogRecord($r,$id_mitigasi);

				$this->load->model('Risk_mitigasiModel','modelpage');

				$return = $this->modelpage->Update($r, $this->modelpage->pk." = ".$this->conn->qstr($id_mitigasi));
			}

			if($return['success']){

				$check_interdependent = $this->checkApproveInterdependent($id_risiko);

				if($check_interdependent){

					if($check_interdependent==2)
						$id_status_pengajuan = 4;
					else
						$id_status_pengajuan = 3;

					$r = array('id_status_pengajuan'=>$id_status_pengajuan);

	            	$this->_setLogRecord($r,$id_risiko);

					$this->load->model('Risk_risikoModel','moderisk');

					$this->moderisk->Update($r, $this->moderisk->pk." = ".$this->conn->qstr($id_risiko));

					if($check_interdependent===1)
						$this->ReExecuteTask($id_risiko,true);
				}
			}

        	$this->conn->CompleteTrans();

			if($is_plain){
				return;
			}

			if ($return['success']) {

				SetFlash('suc_msg', "Task berhasil dikirim");
				redirect(current_url());
				die();
			}
			else{
            	
				SetFlash('err_msg', "Task gagal dikirim");
				redirect(current_url());
				die();
			}
		}

	}

	protected function _actionKonfirmasiControl($is_plain){

		$this->load->library('form_validation');

		$id_risiko = $this->post['id']['risiko'];
		$id_control = $this->post['id']['control'];
		$id_status_pengajuan = $this->post['id_status_pengajuan']['control'];
		$keterangan = $this->post['keterangan']['control'];


		if (!$keterangan or !$id_control or !$id_status_pengajuan)
		{
			SetFlash('err_msg', "Data tidak valid");
			redirect(current_url());
			die();
		}
		else{

            $this->conn->StartTrans();

            if(!$id_risiko)
				$id_risiko = $this->conn->GetOne("select id_risiko from risk_control where id_control = ".$this->conn->escape($this->post['id']['control']));

			$record = array(
				'page'=>'control',
				'deskripsi'=>$keterangan,
				'id_status_pengajuan'=>$id_status_pengajuan,
				'id_risiko'=>$id_risiko,
				'id_control'=>$id_control,
				'url'=>"panelbackend/risk_control/edit/$id_risiko/$id_control"
			);

			$return = $this->InsertTask($record);

			if($return['success']){

				$status_konfirmasi = 1;
				if($id_status_pengajuan==4){
					$status_konfirmasi = 2;
				}
				
				$r = array('status_konfirmasi'=>$status_konfirmasi);

				if($status_konfirmasi==1)
					$r['is_lock'] = 1;

            	$this->_setLogRecord($r,$id_control);

				$this->load->model('Risk_controlModel','modelpage');

				$return = $this->modelpage->Update($r, $this->modelpage->pk." = ".$this->conn->qstr($id_control));
			}

			if($return['success']){

				$check_interdependent = $this->checkApproveInterdependent($id_risiko);

				if($check_interdependent){

					if($check_interdependent==2)
						$id_status_pengajuan = 4;
					else
						$id_status_pengajuan = 3;

					$r = array('id_status_pengajuan'=>$id_status_pengajuan);

	            	$this->_setLogRecord($r,$id_risiko);

					$this->load->model('Risk_risikoModel','moderisk');

					$this->moderisk->Update($r, $this->moderisk->pk." = ".$this->conn->qstr($id_risiko));

					if($check_interdependent===1)
						$this->ReExecuteTask($id_risiko,true);
				}
			}

        	$this->conn->CompleteTrans();

			if($is_plain){
				return;
			}

			if ($return['success']) {

				SetFlash('suc_msg', "Task berhasil dikirim");
				redirect(current_url());
				die();
			}
			else{
            	
				SetFlash('err_msg', "Task gagal dikirim");
				redirect(current_url());
				die();
			}
		}

	}

	protected function checkApproveInterdependent($id_risiko=null){
		$sql = "select status_konfirmasi from risk_mitigasi where id_risiko = ".$this->conn->escape($id_risiko);
		$rows = $this->conn->GetRows($sql);
		$setuju = 0;
		$tolak = 0;
		foreach ($rows as $r) {
			if(!$r['status_konfirmasi'])
				return 0;

			if($r['status_konfirmasi']=='1')
				$setuju++;

			if($r['status_konfirmasi']=='2')
				$tolak++;
		}

		$sql = "select status_konfirmasi from risk_control where id_risiko = ".$this->conn->escape($id_risiko);
		$rows = $this->conn->GetRows($sql);
		foreach ($rows as $r) {
			if(!$r['status_konfirmasi'])
				return 0;

			if($r['status_konfirmasi']=='1')
				$setuju++;

			if($r['status_konfirmasi']=='2')
				$tolak++;
		}

		if($tolak)
			return 2;

		return 1;
	}

	protected function _getListTask($page, $row, &$edited=null){
		$page = $this->conn->escape_string($page);

		$page_allow = array("scorecard","risiko","control","kegiatan","mitigasi");

		$page_ctrl = "panelbackend/risk_".$page;

		if(!in_array($page, $page_allow)){
			$this->Error403();
		}

		$id = $row['id_'.$page];

		if($page=='mitigasi'){
			$this->data['task_'.$page] = $this->conn->GetArray("select t.deskripsi, t.id_status_pengajuan, u.name as nama_user
				from risk_task t
				join public_sys_user u on t.created_by = u.user_id
				where page = ".$this->conn->escape($page)."
				and id_".$page."=".$this->conn->escape($id)."
				and t.is_pending != '1'
				order by id_task desc");
		}else{
			$this->data['task_'.$page] = $this->conn->GetArray("select t.deskripsi, t.id_status_pengajuan, u.name as nama_user, g.name as nama_group
				from risk_task t
				join public_sys_user u on t.created_by = u.user_id
				join public_sys_group g on t.group_id = g.group_id
				where page = ".$this->conn->escape($page)."
				and id_".$page."=".$this->conn->escape($id)."
				and t.id_status_pengajuan is not null
				and t.is_pending != '1'
				order by id_task desc");
		}

		if($edited!==null)
			$this->_accessTask($page_ctrl, $row, $edited);

	}

	protected function isAllowEditPenanggungJawab(){
		if(!$this->data['rowinterdependent'])
			$this->data['rowinterdependent'] = $this->data['row'];

		if($this->data['mode']!='edit' && $this->mode != 'add')
			return false;

		if($this->access_role['edit'] && !isset($this->data['rowinterdependent']))
			return true;

		if($this->Access("view_all_direktorat", "panelbackend/risk_risiko"))
			return true;

		if($this->data['rowinterdependent']['is_lock']=='1')
			return false;

		$is_interdependent = $this->isAccessInterdependent();

		/*if(!$is_interdependent && $this->data['rowinterdependent']['status_konfirmasi']=='1')
			return false;*/

		if($this->access_role['edit'] && !$is_interdependent)
			return true;

		return false;
	}

	protected function isAccessInterdependent($is_pic=true){
		if($is_pic)
			return (bool)($this->isInterdependent() && $this->data['rowinterdependent']['penanggung_jawab']==$_SESSION[SESSION_APP]['pic']);
		else
			return (bool)($this->isInterdependent() && $this->data['rowinterdependent']['penanggung_jawab']==$_SESSION[SESSION_APP]['id_jabatan']);
	}

	protected function _accessTask($page_ctrl, $row, &$edited){

		$is_edit = accessbystatus($row['id_status_pengajuan'], $page_ctrl);

		if($is_edit && $this->Access("view_all_direktorat","panelbackend/risk_risiko")){
			return;
		}


		if((!$is_edit or ($row['status_risiko']!=='1' && $row['id_risiko'])) and !($this->isAccessInterdependent() && $row['id_status_pengajuan']=='6')){
			$this->access_role_custom[$page_ctrl]['edit'] = false;
			$this->access_role_custom[$page_ctrl]['delete'] = false;

			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			
			if($this->page_ctrl!=$page_ctrl){
				$this->access_role['add'] = false;
			}

			$edited = false;
		}
	}

	protected function isInterdependent($ispost=false){
		if(!$this->data['rowinterdependent'] or $ispost)
			$this->data['rowinterdependent'] = $this->data['row'];

		if(!$this->data['rowinterdependent']['penanggung_jawab'] or !$this->data['rowheader']['owner'])
			return false;
		
		return (bool)($this->data['rowinterdependent']['penanggung_jawab']<>$this->data['rowheader']['owner']);
	}

	protected function _validAccessTask($page_ctrl, $row, &$edited){
		$this->_accessTask($page_ctrl, $row, $edited);

		if(!$this->access_role[$this->mode]){
			$str = '';

			if(ENVIRONMENT=='development')
				$str = "akses : ".print_r($this->access_role,true);

			$this->Error403($str);
			exit();
		}
	}

	protected function backtodraft($id_risiko=null){
		if($this->post['act']!='save')
			return false;
		
		$this->load->model("Risk_risikoModel","mrisk");
		$row = $this->mrisk->GetByPk($id_risiko);
		if(($row)){
			if(($row['id_status_pengajuan']=='5' or $row['id_status_pengajuan']=='4') && ($this->Access("pengajuan",'panelbackend/risk_risiko') or $this->isInterdependent()) && !$this->Access("view_all_direktorat",'panelbackend/risk_risiko')){
				$record = array("id_status_pengajuan"=>1);
				$this->mrisk->Update($record, "id_risiko = ".$this->conn->qstr($id_risiko));
			}
		}
	}

	protected function LogColumns($column=null){
		$return = array(
			"nomor"=>array(
				'label'=>'Nomor',
			),
			"nama"=>array(
				'label'=>'Nama',
			),
			"deskripsi"=>array(
				'label'=>'Deskripsi',
			),
			"inheren_dampak"=>array(
				'label'=>'Tingkat Dampak Inheren Risk',
				'arr'=>$this->data['mtdampakrisikoarr'],
			),
			"inheren_kemungkinan"=>array(
				'label'=>'Tingkat Kemungkinan Inheren Risk',
				'arr'=>$this->data['mtkemungkinanarr'],
			),
			"control_dampak_penurunan"=>array(
				'label'=>'Tingkat Dampak Current Risk',
				'arr'=>$this->data['mtdampakrisikoarr'],
			),
			"control_kemungkinan_penurunan"=>array(
				'label'=>'Tingkat Kemungkinan Current Risk',
				'arr'=>$this->data['mtkemungkinanarr'],
			),
			"penyebab"=>array(
				'label'=>'Penyebab'
			),
			"dampak"=>array(
				'label'=>'Dampak'
			),
			"id_sasaran_strategis"=>array(
				'label'=>'Sasaran Strategis',
				'arr'=>$this->data['sasaranarr'],
			),
			"id_sasaran_kegiatan"=>array(
				'label'=>'Sasaran Kegiatan',
				'arr'=>$this->data['mtkegiatanarr'],
			),
			"residual_target_dampak"=>array(
				'label'=>'Tingkat Dampak Risidual Risk',
				'arr'=>$this->data['mtdampakrisikoarr'],
			),
			"residual_target_kemungkinan"=>array(
				'label'=>'Tingkat Kemugnkinan Risidual Risk',
				'arr'=>$this->data['mtkemungkinanarr'],
			),
			"id_kriteria_dampak"=>array(
				'label'=>'Kategori',
				'arr'=>$this->data['kriteriaarr'],
			),
			"residual_dampak_evaluasi"=>array(
				'label'=>'Tingkat Dampak Evaluasi',
				'arr'=>$this->data['mtdampakrisikoarr'],
			),
			"residual_kemungkinan_evaluasi"=>array(
				'label'=>'Tingkat Kemungkinan Evaluasi',
				'arr'=>$this->data['mtkemungkinanarr'],
			),
			"progress_capaian_kinerja"=>array(
				'label'=>'Progress Capaian Kinerja',
			),
			"penyesuaian_tindakan_mitigasi"=>array(
				'label'=>'Penyesuaian Tindakan Mitigasi',
			),
			"hambatan_kendala"=>array(
				'label'=>'Hambatan Kendala',
			),
			"dead_line"=>array(
				'label'=>'Dead Line',
			),
			"rating"=>array(
				'label'=>'Rating',
			),
			"biaya"=>array(
				'label'=>'Biaya',
			),
			"revenue"=>array(
				'label'=>'Dasar Perhitungan Dampak Finansial',
			),
			"penanggung_jawab"=>array(
				'label'=>'Penanggung Jawab',
				'arr'=>$this->data['penanggung_jawabarr']
			),
			"id_status_progress"=>array(
				'label'=>'Progress',
				'arr'=>$this->data['pregressarr'],
			),
			"menurunkan_dampak_kemungkinan"=>array(
				'label'=>'K/D',
				'arr'=>$this->data['menurunkanrr'],
			),
			"remark"=>array(
				'label'=>'Remark',
			),
			"is_efektif"=>array(
				'label'=>'Efektifitas',
				'arr'=>array('2'=>'Tidak Efektif','1'=>'Efektif')
			),
			"id_interval"=>array(
				'label'=>'Interval',
				'arr'=>$this->data['mtintervalarr'],
			),
		);

		if(!$return[$column]){
			$return[$column] = array('label'=>$column);
		}

		return $return[$column];
	}

	protected function riskchangelog($new=array(), $old=array(), $str='', $page_ctrl=''){

		if(!$page_ctrl)
			$page_ctrl = $this->page_ctrl;
				
		if(($this->ctrl == 'risk_control' or $this->ctrl=='risk_mitigasi') && $this->method=='index')
			$page_ctrl = "panelbackend/risk_risiko";

		$skiparr = array("modified_date","modified_by","created_by","created_date");

		if(is_array($old) && is_array($new) && ($old)){
			$str .= "";;
			foreach ($new as $key => $newvalue) {
				if(in_array($key,$skiparr) or $newvalue===null)
					continue;

				$oldvalue = $old[$key];

				$key1 = str_replace("id_", "", $key);
				$ref = $this->data['mt'.$key1.'arr'];

				if(is_array($ref)){
					if($ref[$newvalue])
						$newvalue = $ref[$newvalue];

					if($ref[$oldvalue])
						$oldvalue = $ref[$oldvalue];
				}

				$col = $this->LogColumns($key);
				$key_alias = $col['label'];

				if(!$key_alias)
					continue;

				if($col['arr'][$newvalue])
					$newvalue = $col['arr'][$newvalue];

				if($col['arr'][$oldvalue])
					$oldvalue = $col['arr'][$oldvalue];

				if($oldvalue<>$newvalue)
					$str .= "\n".$key_alias." <b>$oldvalue</b> menjadi <b>$newvalue</b>, ";
			}

			if($str){
				$nama = $old['nama'];
				$str = "Mengubah data di ".var2alias($page_ctrl) ." $nama : ".$str;
			}
		}
		elseif(is_array($new)){
			$str .= "Menambah data ".var2alias($page_ctrl);
			foreach ($new as $key => $value) {
				if(in_array($key,$skiparr))
					continue;

				if($value){
					$col = $this->LogColumns($key);
					$key_alias = $col['label'];

					if(!$key_alias)
						continue;

					if($col['arr'][$value])
						$value = $col['arr'][$value];

					$str .= "\n". $key_alias ." <b>$value</b>, ";
				}
			}
		}

		$str = trim($str,',');
		$this->risklog($str, $page_ctrl, $new, $old);
	}

	protected function risklog($act="",$page_ctrl='', $data=array(), $data_old = array()){
		if(!$page_ctrl)
			$page_ctrl = $this->page_ctrl;

		if(!$act)
			return;

		$record = array();

		if(!$data)
			$data = $this->data['row'];

		if(!$data['id_risiko'])
			$data['id_risiko'] = $this->data['row']['id_risiko'];

		if(!$data['id_risiko'])
			$data['id_risiko'] = $data_old['id_risiko'];

		if(in_array($page_ctrl, array(
			"panelbackend/risk_scorecard",
			"panelbackend/risk_risiko",
			"panelbackend/risk_sasaran_kegiatan",
			)) && !$data['id_risiko']){
			$record['id_scorecard'] = $data['id_scorecard'];
		}
		else{
			$record['id_risiko'] = $data['id_risiko'];
		}

		$record['deskripsi'] = $act;
		$record['activity_time'] = "{{sysdate}}";
		$record['created_by'] = $_SESSION[SESSION_APP]['user_id'];
		$record['group_id'] = $_SESSION[SESSION_APP]['group_id'];

		$this->conn->goInsert("risk_log",$record);
	}

	function curl($q, $params=array()) {	
		$url = site_url($q);
		$param_str = http_build_query($params);

		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch,CURLOPT_TIMEOUT, 2);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $param_str);
		curl_setopt($ch,CURLOPT_VERBOSE, true);
		curl_setopt($ch,CURLOPT_COOKIEJAR, '-'); 
		curl_setopt($ch,CURLOPT_COOKIEFILE, 'cookie.txt'); 
		curl_setopt($ch,CURLOPT_COOKIESESSION, true);

		$result = curl_exec($ch);

		if($result)
			file_put_contents('logs/curl', $result."\n", FILE_APPEND);

		$info = curl_getinfo($ch);
		$err = curl_errno($ch);
		$msg = curl_error($ch);
		
		if(FALSE){
			echo $url;
			echo '<pre>PARAM :'."\n";
			print_r($params);
			echo ' ===>'.$result."\n";/*
			echo 'INFO : '."\n";
			print_r($info);
			echo 'ERR : '."\n";
			print_r($err);
			echo 'MSG : '."\n";
			print_r($msg);
			echo '</pre>';*/
		}
		
		curl_close($ch);
		
		return $result;	
	}


	function Access($mode, $page=null){
		if($page){
			if($this->access_role_custom[$page])
				$access_role = $this->access_role_custom[$page];
			else{
				$this->access_role_custom[$page] = $this->auth->GetAccessRole($page);
				$access_role = $this->access_role_custom[$page];
			}
		}
		else{
			$access_role = $this->access_role;
		}

		if($access_role[$mode])
			return true;
		else
			return false;
	}

	function isLock(){
		if($this->data['rowheader1']['status_risiko']!='1' && $this->data['rowheader1']['status_risiko']){
			$this->data['editedheader1'] = false;
			$this->data['edited'] = false;
			$this->access_role['edit'] = false;
			$this->access_role['delete'] = false;
			$this->access_role['add'] = false;
			$this->data['row']['is_lock']=0;
			$this->data['is_allow_edit_penanggung_jawab']=false;
			return;
		}

		if($this->Access("view_all_direktorat","panelbackend/risk_risiko"))
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

	function unlock(){
		$id = $this->post['key'];
		$record = array("is_lock"=>2);
		return $this->model->Update($record, "$this->pk = ".$this->conn->qstr($id));
	}

	protected function UpdateConfig($var, $val){
		$this->conn->goUpdate("public_sys_setting",array('isi'=>$val),"nama = ".$this->conn->escape($var));
	}

	function upload_file($id=null){
		$jenis_file = key($_FILES);

		$ret = $this->_uploadFiles($jenis_file, $id);		

		echo json_encode($ret);
	}

	function delete_file($id=null){
		$ret = $this->_deleteFiles($this->post['id']);
		
		echo json_encode($ret);
	}

	function open_file($id=null, $nameid=null){
		$this->_openFiles($id, $nameid);
	}

	protected function _updateFiles($record=array(), $id=null){
		return $this->modelfile->Update($record, $this->modelfile->pk."=".$this->conn->escape($id));
	}

	private function _deleteFiles($id){
		
		$row = $this->modelfile->GetByPk($id);

		if(!$row)
			$this->Error404();

		$file_name = $row['file_name'];

		$return = $this->modelfile->Delete($this->modelfile->pk." = ".$this->conn->escape($id));

		if ($return) {
			$full_path = $this->data['configfile']['upload_path'].$file_name;
			unlink($full_path);

			return array("success"=>true);
		}else{
			return array("error"=>"File ".$row['client_name']." gagal dihapus");
		}
	}

	private function _openFiles($id=null, $nameid=null){
		$row = $this->modelfile->GetByPk($id);
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
			$record['jenis_file'] = $record['jenis'] = str_replace("upload","",$jenis_file);
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

	protected function _isValidImport($record){
		$this->data['rules'] = $this->Rules();

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
		return $this->Header();
	}

	public function import_list(){

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

			for ($row = 2; $row <= $highestRow; $row++){ 

		    	$col = 'A';
		    	$record = array(
					'id_dpt'=>$this->id_dpt,
					'id_suplier'=>$this->id_suplier
				);

		    	foreach($header as $r1){
		    		if($r1['type']=='list')
		           		$record[$r1['name']] = (string)$sheet->getCell($col.$row)->getValue();
		           	elseif($r1['type']=='listinverst'){
		           		$rk = strtolower(trim((string)$sheet->getCell($col.$row)->getValue()));
		           		$arr =array();
		           		foreach ($r1['value'] as $key => $value) {
		           			$arr[strtolower(trim($value))] = $key;
		           		}
		           		$record[$r1['name_ori']] = (string)$arr[$rk];
		           	}
		           	else
		           		$record[$r1['name']] = $sheet->getCell($col.$row)->getValue();
		           	
	           		$col++;
		    	}

		    	$this->data['row'] = $record;

		    	$error = $this->_isValidImport($record);
		    	if($error){
		    		$return['error'] = $error;
		    	}else{
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

	public function export_list(){
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

	    foreach($header as $r){
	    	if(!$col)
	    		$col = 'A';
	    	else
	        	$col++;    

	        $excelactive->setCellValue($col.$row,$r['name']);
	    }

		$excelactive->getStyle('A1:'.$col.$row)->getFont()->setBold(true);
        $excelactive
		    ->getStyle('A1:'.$col.$row)
		    ->getFill()
		    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		    ->getStartColor()
		    ->setARGB('6666ff');
		    
	    #data
		$respon = $this->model->SelectGrid(
			array(
			'limit' => -1,
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
			)
		);
		$rows = $respon['rows'];

		$row = 2;
        foreach($rows as $r){
	    	$col = 'A';
	    	foreach($header as $r1){
	    		if($r1['type']=='listinverst'){
	    			$r[$r1['name']] = $r1['value'][$r[$r1['name_ori']]];
	    		}
           		$excelactive->setCellValue($col.$row,$r[$r1['name']]);
           		$col++;
	    	}
            $row++;
        }


	    $objWriter = Factory::createWriter($excel,'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->ctrl.date('Ymd').'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit();
	}

	function curlwait($q, $params=array(), $debug=false) {	
		$url = $q;
		$param_str = http_build_query($params);

		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 2000);
		curl_setopt($ch,CURLOPT_TIMEOUT, 2000);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $param_str);
		curl_setopt($ch,CURLOPT_VERBOSE, true);
		curl_setopt($ch,CURLOPT_COOKIEJAR, '-'); 
		curl_setopt($ch,CURLOPT_COOKIEFILE, 'cookie.txt'); 
		curl_setopt($ch,CURLOPT_COOKIESESSION, true);

		$result = curl_exec($ch);
/*
		if($result)
			file_put_contents('logs/curl', $result."\n", FILE_APPEND);*/

		$info = curl_getinfo($ch);
		$err = curl_errno($ch);
		$msg = curl_error($ch);
		
		if($debug){
			echo $url;
			echo '<pre>PARAM :'."\n";
			print_r($params);
			echo ' ===>'.$result."\n";/*
			echo 'INFO : '."\n";
			print_r($info);
			echo 'ERR : '."\n";
			print_r($err);
			echo 'MSG : '."\n";
			print_r($msg);
			echo '</pre>';*/
			die();
		}
		
		curl_close($ch);
		
		return $result;	
	}

	function curlnowait($q, $params=array(), $debug=false) {	
		$url = $q;
		$param_str = http_build_query($params);

		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch,CURLOPT_TIMEOUT, 1);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $param_str);
		curl_setopt($ch,CURLOPT_VERBOSE, true);
		curl_setopt($ch,CURLOPT_COOKIEJAR, '-'); 
		// curl_setopt($ch,CURLOPT_COOKIEFILE, 'cookie.txt'); 
		curl_setopt($ch,CURLOPT_COOKIESESSION, true);

		$result = curl_exec($ch);
/*
		if($result)
			file_put_contents('logs/curl', $result."\n", FILE_APPEND);*/

		$info = curl_getinfo($ch);
		$err = curl_errno($ch);
		$msg = curl_error($ch);
		
		if($debug){
			echo $url;
			echo '<pre>PARAM :'."\n";
			print_r($params);
			echo ' ===>'.$result."\n";
			echo 'INFO : '."\n";
			print_r($info);
			echo 'ERR : '."\n";
			print_r($err);
			echo 'MSG : '."\n";
			print_r($msg);
			echo '</pre>';
			die();
		}
		
		curl_close($ch);
		
		return $result;	
	}

	protected function reqpromis($action=null, $pos=array(), $debug=false){
		$pos['data_auth'] = $this->config->item("auth_promis");
		return json_decode($this->curlwait($this->config->item("url_promis")."$action", $pos, $debug),true);
	}

	protected function reqpromisnowait($action=null, $pos=array(), $debug=false){
		$pos['data_auth'] = $this->config->item("auth_promis");
		return json_decode($this->curlnowait($this->config->item("url_promis")."$action", $pos, $debug),true);
	}
}
