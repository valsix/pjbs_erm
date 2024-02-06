<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Akses_jabatan extends _adminController{

	public $limit = -1;
	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/akses_jabatanlist";
		$this->viewdetail = "panelbackend/akses_jabatandetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout2";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jabatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jabatan';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jabatan';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Jabatan';
		}

		$this->data['width'] = "2400px";

		$this->load->model("Mt_sdm_jabatanModel","model");
		$this->load->model("Mt_sdm_unitModel","mtsdmunit");
		$this->data['mtsdmunitarr'] = $this->mtsdmunit->GetCombo();

		/*
		$this->load->model("Mt_sdm_jabatanModel","mtsdmjabatan");
		$this->data['mtsdmjabatanarr'] = $this->mtsdmjabatan->GetCombo();
*/
		
		$this->load->model("Public_sys_groupModel","group");
		$this->data['grouparr'] = $this->group->GetCombo();
		/*
		$this->load->model("Mt_sdm_jenjangModel","mtsdmjenjang");
		$this->data['mtsdmjenjangarr'] = $this->mtsdmjenjang->GetCombo();

		
		$this->load->model("Mt_sdm_kategoriModel","mtsdmkategori");
		$this->data['mtsdmkategoriarr'] = $this->mtsdmkategori->GetCombo();

		
		$this->load->model("Mt_sdm_dit_bidModel","mtsdmditbid");
		$this->data['mtsdmditbidarr'] = $this->mtsdmditbid->GetCombo();

		
		$this->load->model("Mt_sdm_subbidModel","mtsdmsubbid");
		$this->data['mtsdmsubbidarr'] = $this->mtsdmsubbid->GetCombo();

		
		$this->load->model("Mt_sdm_tipe_unitModel","mtsdmtipeunit");
		$this->data['mtsdmtipeunitarr'] = $this->mtsdmtipeunit->GetCombo();*/

		
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
				'name'=>'id_unit', 
				'label'=>'Unit', 
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['mtsdmunitarr'],
			),
			array(
				'name'=>'direktorat_ket', 
				'label'=>'Direktorat', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'subdit_ket', 
				'label'=>'Subdit', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
		);
	}

	public function Index($page=0){

		if(!$_SESSION[SESSION_APP][$this->page_ctrl]['list_filter'] && !$_SESSION[SESSION_APP][$this->page_ctrl]['list_search'] && !$_SESSION[SESSION_APP][$this->page_ctrl]['list_search_filter']){
			$this->_setFilter("1=2");
		}

		if($this->post['group_id'])
			$_SESSION[SESSION_APP][$this->page_ctrl]['group_id'] = $this->post['group_id'];

		$this->data['row']['group_id'] = $_SESSION[SESSION_APP][$this->page_ctrl]['group_id'];

		$this->data['header']=$this->Header();

		$this->data['list']=$this->_getList($page);

		$ret = true;

		if($this->access_role['save'] && $this->post['act']=='save'){

			if(!$this->post['group_id'])
				$ret = false;

			foreach($this->data['list']['rows'] as $r){
				if(!$ret)
					break;

				$id_jabatan = $r['id_jabatan'];
				$group_id = $this->post['group_id'];

				$record = array();
				$record['group_id'] = $group_id;
				$record['name'] = $r['nama'];
				$record['id_jabatan'] = $id_jabatan;
				$record['is_manual'] = 0;
				$record['is_active'] = 1;

				$user_id = $this->conn->GetOne("select 1 from public_sys_user where id_jabatan = ".$this->conn->escape($id_jabatan)." and is_manual = 0 and group_id = ".$this->conn->escape($group_id));
				if($user_id){
					if(!$this->post['id_jabatan'][$id_jabatan]){
						$ret = $this->conn->Execute("update public_sys_user set is_active = 0 where user_id = ".$this->conn->escape($user_id));
					}else{
						$ret = $this->conn->goUpdate("public_sys_user",$record, "user_id = ".$this->conn->escape($user_id));
					}
				}elseif($this->post['id_jabatan'][$id_jabatan]){
					$ret = $this->conn->goInsert("public_sys_user",$record);
				}
			}

			if($ret)
				SetFlash('suc_msg', "Sukses");
			else
				SetFlash('err_msg', "Gagal");

			redirect(current_url());

		}


		$this->data['row']['id_jabatan'] = $this->post['id_jabatan'];

		if(empty($this->data['row']['id_jabatan']) or $this->post['act']=='set_value'){
			$this->data['row']['id_jabatan'] = array();
			$rows = $this->conn->GetArray("select id_jabatan from public_sys_user where is_manual = 0 and group_id = ".$this->conn->escape($this->data['row']['group_id']));
			foreach($rows as $r){
				$this->data['row']['id_jabatan'][$r['id_jabatan']] = 1;
			}
		}

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

}