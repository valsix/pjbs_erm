<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Mt_risk_kajian_risiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/mt_risk_kajian_risikolist";
		$this->viewdetail = "panelbackend/mt_risk_kajian_risikodetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Jenis Kajian Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Jenis Kajian Risiko';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Jenis Kajian Risiko';
			$this->data['edited'] = false;
		}else{
			$this->data['page_title'] = 'Daftar Jenis Kajian Risiko';
		}

		$this->load->model("Mt_risk_kajian_risikoModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
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
				'name'=>'keterangan',
				'label'=>'Keterangan',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kode',
				'label'=>'Kode',
				'width'=>"70px",
				'type'=>"varchar2",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'kode'=>$this->post['kode'],
			'keterangan'=>$this->post['keterangan'],
			'jenis_sasaran'=>$this->post['jenis_sasaran'],
			'reqdatatable'=>$this->post['reqdatatable'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Nama Jenis Kajian Risiko',
				'rules'=>"required|max_length[200]",
			),
			"kode"=>array(
				'field'=>'kode',
				'label'=>'Kode Jenis Kajian Risiko',
				'rules'=>"required|max_length[20]",
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
			$rowsdetil= $this->model->selectbyparamkajianrisikodetil($id, "array");
			$infojumlahdata= count($rowsdetil);
			$this->data['row']['reqtable']= $infojumlahdata;
			if($infojumlahdata > 0)
			{
				foreach($rowsdetil as $key=>$v)
				{
					$this->data['row']['reqdatatable'][$key]['rowdetilid']= $v['id_kajian_risikodetil'];
					$this->data['row']['reqdatatable'][$key]['nourut']= $v['nourut'];
					$this->data['row']['reqdatatable'][$key]['no_dinamis']= $v['no_dinamis'];
					$this->data['row']['reqdatatable'][$key]['status_aktif']= $v['status_aktif'];

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
			$recorddetil['id_kajian_risiko']= $id;
			$recorddetil['nourut']= $v['nourut'];
			$recorddetil['no_dinamis']= $v['no_dinamis'];
			$recorddetil['status_aktif']= $v['status_aktif'];

			if(empty($rowdetilid))
			{
				if($status == "hapus"){}
				else
				$ret = $this->conn->goInsert('mt_risk_kajian_risikodetil', $recorddetil);
			}
			else
			{
				if($status == "hapus")
				{
					$ret = $this->conn->Execute("delete from mt_risk_kajian_risikodetil where id_kajian_risikodetil = ".$this->conn->escape($rowdetilid));
				}
				else
				{
					$this->conn->goUpdate("mt_risk_kajian_risikodetil", $recorddetil, "id_kajian_risikodetil = ".$this->conn->escape($rowdetilid));
				}
			}
		}
	}
}
