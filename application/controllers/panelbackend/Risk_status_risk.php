<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Risk_status_risk extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/risk_status_risklist";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

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
			$this->data['notab'] = true;
			$this->data['page_title'] = 'Daftar Status Risiko';
		}

		$this->load->model("Risk_risikoModel","model");

		$this->load->model("Mt_risk_kriteria_dampakModel",'kriteria');
		$this->data['kriteriaarr'] = $this->kriteria->GetCombo();

		$this->SetAccess('panelbackend/risk_scorecard');

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);
	}

	protected function Header(){
		if($this->data['tipe']==1){
			$ret = array(
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
				array(
					'name'=>'nama_mitigasi',
					'label'=>'Aktivitas Mitigasi',
					'width'=>"auto",
					'type'=>"varchar2",
				),
				array(
					'name'=>'inheren',
					'label'=>'Inheren Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'control',
					'label'=>'Current Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'risidual',
					'label'=>'Targeted Risidual Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'status_konfirmasi',
					'label'=>'Status Konfirmasi',
					'width'=>"auto",
					'type'=>"list",
					'value'=>array(''=>'','0'=>'Dalam Konfirmasi','1'=>'Disetujui','2'=>'Ditolak'),
				),
			);	

			if(!$this->Access("view_all_direktorat","panelbackend/risk_risiko"))
				unset($ret[3]);
			else{
				$ret[] = array(
					'name'=>'id_status_pengajuan',
					'label'=>'Status Risiko',
					'width'=>"100px",
					'type'=>"list",
					'value'=>$this->data['mtstatusarr'],
				);
			}
			
			return $ret;
		}elseif($this->data['tipe']==2){
			$ret = array(
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
				array(
					'name'=>'nama_control',
					'label'=>'Aktivitas Kontrol',
					'width'=>"auto",
					'type'=>"varchar2",
				),
				array(
					'name'=>'inheren',
					'label'=>'Inheren Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'control',
					'label'=>'Current Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'risidual',
					'label'=>'Targeted Risidual Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'status_konfirmasi',
					'label'=>'Status Konfirmasi',
					'width'=>"auto",
					'type'=>"list",
					'value'=>array(''=>'','0'=>'Dalam Konfirmasi','1'=>'Disetujui','2'=>'Ditolak'),
				),
			);	

			if(!$this->Access("view_all_direktorat","panelbackend/risk_risiko"))
				unset($ret[3]);
			else{
				$ret[] = array(
					'name'=>'id_status_pengajuan',
					'label'=>'Status Risiko',
					'width'=>"100px",
					'type'=>"list",
					'value'=>$this->data['mtstatusarr'],
				);
			}
			
			return $ret;
		}else{
			$ret = array(
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
				array(
					'name'=>'inheren',
					'label'=>'Inheren Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'control',
					'label'=>'Current Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'risidual',
					'label'=>'Targeted Risidual Risk',
					'width'=>"auto",
					'type'=>"list",
					'value'=>$this->data['mttingkatdampakarr'],
				),
				array(
					'name'=>'id_status_pengajuan',
					'label'=>'Status Risiko',
					'width'=>"100px",
					'type'=>"list",
					'value'=>$this->data['mtstatusarr'],
				),
			);

			if(!$this->Access("view_all_direktorat","panelbackend/risk_risiko"))
				unset($ret[2]);

			return $ret;
		}
	}

	public function Index($tipe = 0, $page=0){

		$this->data['tipe'] = $tipe;
		
		$this->data['list']=$this->_getList($page);
		$this->data['header']=$this->Header();
		$this->data['page']=$page;
		$param_paging = array(
			'base_url'=>base_url("{$this->page_ctrl}/index/$tipe"),
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


		$this->data['add_param'] .= $tipe;

		$this->View($this->viewlist);
	}

	protected function _getList($page=0){
		$this->_resetList();

		$this->arrNoquote = $this->model->arrNoquote;

		$param=array(
			'page' => $page,
			'tipe' => $this->data['tipe'],
			'limit' => $this->_limit(),
			'order' => $this->_order(),
			'filter' => $this->_getFilter()
		);

		if($this->post['act']){
			if($this->data['add_param']){
				$add_param = '/'.$this->data['add_param'];
			}
			redirect(str_replace(strstr(current_url(),"/index$add_param/$page"), "/index{$add_param}", current_url()));
		}

		$respon = $this->model->SelectGridStatus(
			$param
		);

		return $respon;
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
					if(!($v==='' or $v===null or $v===false)){
						$filter_arr1[] = 'a.'.$key ." = '$v'";
					}
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
}
