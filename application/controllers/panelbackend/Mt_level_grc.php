<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_level_grc extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_level_grclist";
		$this->viewdetail = "panelbackend/mt_level_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Level GRC';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Level GRC';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Level GRC';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Level GRC';
		}

		$this->load->model("Mt_level_grcModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->data['status'] = array("1"=>"Aktif", "0"=>"Non Aktif");

		$this->load->model("Mt_folder_dokgrcModel","mtdokgrc");
		$this->data['mtdokgrcarr'] = $this->mtdokgrc->selectbyparamfolder("list");
	}

	protected function Header(){
		return array(
			array(
				'name'=>'kode', 
				'label'=>'Kode', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'nama', 
				'label'=>'Nama', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'deskripsi', 
				'label'=>'Deskripsi', 
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'status',
				'label'=>'Status',
				'width'=>"auto",
				'type'=>"list",
				'value'=>$this->data['status'],
			),
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
			'status'=>$this->post['status'],
			'reqdatatable'=>$this->post['reqdatatable'],
		);
	}

	protected function Rules(){
		return array(
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'Kode', 
				'rules'=>"required|max_length[10]",
			),
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
		);
	}

	protected function _afterDetail($id=null) 
	{
		$statement= $statementjoin= "";
		if($this->data['row']['reqdatatable'])
		{
			// $rowsdetil= $this->data['row']['reqdatatable'];
			// foreach($rowsdetil as $key=>$v)
			// {
			// 	// untuk no katalog arr sesuai dengan grouptool terpilih
			// 	$idkatalogtool= $v['id_katalogtool'];
			// 	if ($idkatalogtool) 
			// 	{
			// 		$infosql= "select id_grouptool from mt_katalogtool where id_katalogtool = ".$this->conn->escape($idkatalogtool);
			//     	$datanya= $this->conn->GetRow($infosql);
			//     	$this->data['row']['reqdatatable'][$key]['id_grouptool']= $datanya['id_grouptool'];

			//     	$this->data['mtkatalogtoolgeneralarr']['reqdatatable'][$key] = $this->mtkatalogtool->selectbyparamkatalogtoolowner(null, "and a.is_delete = '0' and a.id_grouptool = ".$datanya['id_grouptool'], "list", $idlokasikatalog);
			// 	}
			// }
		}
		else
		{
			if ($id) 
			{
				$statementjoin= " and b.id_level_grc = ".$id;
			}

			$rowsdetil= $this->model->selectbyparamfolderlevelgrc($statement, $statementjoin, "array");
			$infojumlahdata= count($rowsdetil);
			$this->data['row']['reqtable']= $infojumlahdata;
			if($infojumlahdata > 0)
			{
				foreach($rowsdetil as $key=>$v)
				{
					$status= $v['status'];
					if ($v['status']=='') 
					{
						$status= 0;
					}
					$this->data['row']['reqdatatable'][$key]['rowdetilid']= $v['id_level_grcdetil'];
					$this->data['row']['reqdatatable'][$key]['id_dok_pendukung_grc']= $v['id_dok_pendukung_grc'];
					$this->data['row']['reqdatatable'][$key]['status_aktif']= $status;

					// // untuk no katalog arr sesuai dengan grouptool terpilih
					// $idkatalogtool= $v['id_katalogtool'];
					// if ($idkatalogtool) 
					// {
					// 	$infosql= "select id_grouptool from mt_katalogtool where id_katalogtool = ".$this->conn->escape($idkatalogtool);
				    // 	$datanya= $this->conn->GetRow($infosql);
				    // 	$this->data['row']['reqdatatable'][$key]['id_grouptool']= $datanya['id_grouptool'];

				    // 	$this->data['mtkatalogtoolgeneralarr']['reqdatatable'][$key] = $this->mtkatalogtool->selectbyparamkatalogtoolowner(null, "and a.is_delete = '0' and a.id_grouptool = ".$datanya['id_grouptool'], "list", $idlokasikatalog);
					// }
				}
			}
		}
		
		// print_r($this->data['row']);exit;
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
		// $ret = $this->conn->Execute("delete from mt_level_grcdetil where id_level_grc = ".$this->conn->escape($id));

		$infodatatable= $record['reqdatatable'];
		if(!empty($infodatatable)){
			$this->setinsertdetil($id, $infodatatable);
		}

		return $ret;
	}

	function setinsertdetil($id, $infodatatable)
	{
		foreach ($infodatatable as $key => $v) {
			$rowdetilid= $v['rowdetilid'];
			$status= $v['status'];

			$recorddetil = array();
			$recorddetil['id_level_grc']= $id;
			$recorddetil['id_dok_pendukung_grc']= $v['id_dok_pendukung_grc'];
			$recorddetil['status']= $v['status_aktif'];

			if(empty($rowdetilid))
			{
				if($status == "hapus"){}
				else
				$ret = $this->conn->goInsert('mt_level_grcdetil', $recorddetil);
			}
			else
			{
				if($status == "hapus")
				{
					$ret = $this->conn->Execute("delete from mt_level_grcdetil where id_level_grcdetil = ".$this->conn->escape($rowdetilid));
				}
				else
				{
					$this->conn->goUpdate("mt_level_grcdetil", $recorddetil, "id_level_grcdetil = ".$this->conn->escape($rowdetilid));
				}
			}
		}
	}

}