<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Sk_matriks_resiko extends _adminController{

	public function __construct(){
		parent::__construct();
	}

	protected function init()
	{

		parent::init();

		$this->data['tabelPK']     = "sk_matriks_resiko_id";

		$aksi = $this->uri->segment(3);
		if($aksi == "tambah")
		{

			$this->viewdetail = "v2/sk_matriks_resiko_detil";
			$this->template = "panelbackend/main";
			$this->data['page_title'] = 'Tambah Sk Matriks Resiko';
			$this->data['edited'] = true;
		}
		elseif($aksi == "ubah")
		{

			$this->viewdetail = "v2/sk_matriks_resiko_detil";
			$this->template = "panelbackend/main";
			$this->data['page_title'] = 'Edit Sk Matriks Resiko';
			$this->data['edited'] = true;
		}
		elseif($aksi == "detil")
		{

			$this->viewdetail = "v2/sk_matriks_resiko_detil";
			$this->template = "panelbackend/main";
			$this->data['page_title'] = 'Detail Sk Matriks Resiko';
			$this->data['edited'] = false;
		}
		else
		{
			$this->layout = "v2/sk_matriks_resiko";
			$this->viewlist = "v2/kosongan";
			$this->viewdetail = "v2/sk_matriks_resiko_detil";
			$this->template = "panelbackend/main";

			$this->data['page_title'] = 'Daftar Sk Matriks Resiko';

			$this->load->model("v2/SkMatriksResiko","model");

			$this->pk = $this->model->pk;
			$this->data['tabelFilter'] = ["nomor" => "", 
										  "judul" => "", 
										  "tanggal_awal" => "", 
										  "tanggal_akhir" => ""];
			$this->data['tabelHeader'] = [["name"  => "nomor", 
										   "label" => "Nomor", 
										   "width" => "auto", 
										   "type"  => ""
										   ],
										  ["name"  => "judul", 
										   "label" => "Judul", 
										   "width" => "auto", 
										   "type"  => ""
										   ],
										  ["name"  => "tanggal_awal", 
										   "label" => "Tanggal Awal", 
										   "width" => "auto", 
										   "type"  => ""
										   ],
										  ["name"  => "tanggal_akhir", 
										   "label" => "Tanggal Akhir", 
										   "width" => "auto", 
										   "type"  => ""
										   ]
									     ];
			$this->data['tabelData'] = $this->db->query(" SELECT * FROM SK_MATRIKS_RESIKO ORDER BY TANGGAL_AWAL DESC ")->result_array();
			$this->plugin_arr = array(
				''
			);

		}
	}

	function tambah()
	{
		$this->ubah();
	}

	function ubah()
	{

		$id = $this->uri->segment(4);

		if(!empty($id))
			$this->data['tabelData'] = $this->db->query(" SELECT * FROM SK_MATRIKS_RESIKO WHERE SK_MATRIKS_RESIKO_ID = '$id' ")->row_array();

		if($this->post['act']=='reset'){
			redirect(current_url());
		}

		$act = $this->post['act'];

		if($act == "save")
		{

			$tableName = $this->router->fetch_class();
			$NOMOR = $this->post['nomor'];
			$JUDUL = $this->post['judul'];
			$TANGGAL_AWAL = $this->post['tanggal_awal'];
			$TANGGAL_AKHIR = $this->post['tanggal_akhir'];
			$CREATED_BY = "";

			$sql = " SELECT COUNT(1) ADA FROM SK_MATRIKS_RESIKO 
					 WHERE 
					 		(
					 		TANGGAL_AWAL BETWEEN TO_DATE('$TANGGAL_AWAL', 'DD-MM-YYYY') AND TO_DATE('$TANGGAL_AKHIR', 'DD-MM-YYYY') OR 
					 		TANGGAL_AKHIR BETWEEN TO_DATE('$TANGGAL_AWAL', 'DD-MM-YYYY') AND TO_DATE('$TANGGAL_AKHIR', 'DD-MM-YYYY') OR 
					 		TO_DATE('$TANGGAL_AWAL', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR OR 
					 		TO_DATE('$TANGGAL_AKHIR', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR
					 		)
					 		 AND NOT SK_MATRIKS_RESIKO_ID = '$id'
					  ";
			$adaData = $this->db->query($sql)->first_row()->ada;
			if($adaData > 0)
			{
				SetFlash('err_msg',  "Masa berlaku SK bersinggungan dengan SK lainnya.");
				$this->View($this->viewdetail);
				return;
			}


			if(empty($id))
			{

				$sql = "SELECT SEQ_".strtoupper($tableName).".NEXTVAL HASIL FROM DUAL ";
				$PRIMARY_ID  = $this->db->query($sql)->first_row()->hasil;

				$sql = " INSERT INTO SK_MATRIKS_RESIKO (
						   SK_MATRIKS_RESIKO_ID, NOMOR, JUDUL, 
						   TANGGAL_AWAL, TANGGAL_AKHIR, 
						   CREATED_BY)
						 VALUES (
						   '$PRIMARY_ID', '$NOMOR', '$JUDUL', 
						   TO_DATE('$TANGGAL_AWAL', 'DD-MM-YYYY'), TO_DATE('$TANGGAL_AKHIR', 'DD-MM-YYYY'), 
						   '$CREATED_BY')
						 ";
				$result = $this->db->query($sql);

			}
			else
			{	
				$PRIMARY_ID  = $id;				

				$sql = " UPDATE SK_MATRIKS_RESIKO 
							SET NOMOR 			= '$NOMOR', 
								JUDUL 			= '$JUDUL',
								TANGGAL_AWAL 	= TO_DATE('$TANGGAL_AWAL', 'DD-MM-YYYY'),
								TANGGAL_AKHIR 	= TO_DATE('$TANGGAL_AKHIR', 'DD-MM-YYYY'),
								UPDATED_BY   	= '$CREATED_BY',
								UPDATED_DATE 	= SYSDATE 
						 WHERE SK_MATRIKS_RESIKO_ID = '$PRIMARY_ID' ";

				$result = $this->db->query($sql);

			}



			if($result)
			{
				SetFlash('suc_msg',  $JUDUL." berhasil disimpan.");
				redirect("v2/sk_matriks_resiko/detil/".$PRIMARY_ID);
				exit;
			}

		}


		$this->View($this->viewdetail);

	}

	function hapus()
	{
		$id = $this->uri->segment(4);

		$sql = " DELETE FROM SK_MATRIKS_RESIKO WHERE SK_MATRIKS_RESIKO_ID = '$id' ";
		$result = $this->db->query($sql);

		if($result)
		{
			redirect("v2/sk_matriks_resiko");
			exit;
		}
	}

	
	function detil()
	{
		$id = $this->uri->segment(4);
		$this->data['tabelData'] = $this->db->query(" SELECT * FROM SK_MATRIKS_RESIKO WHERE SK_MATRIKS_RESIKO_ID = '$id' ")->row_array();
		$this->View($this->viewdetail);
		
	}

	protected function Header(){
		return array(
			array(
				'name'=>'nama',
				'label'=>'Sk Matriks Resiko',
				'width'=>"auto",
				'type'=>"varchar2",
			),
			array(
				'name'=>'kode',
				'label'=>'Kode',
				'width'=>"70px",
				'type'=>"varchar2",
			),
			array(
				'name'=>'rating',
				'label'=>'Rating',
				'width'=>"auto",
				'type'=>"number",
			),
		);
	}

	protected function Record($id=null){
		return array(
			'nama'=>$this->post['nama'],
			'kode'=>$this->post['kode'],
			'keterangan'=>$this->post['keterangan'],
			'rating'=>$this->post['rating'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama',
				'label'=>'Sk Matriks Resiko',
				'rules'=>"required|max_length[300]",
			),
			"kode"=>array(
				'field'=>'kode',
				'label'=>'Kode',
				'rules'=>"required|max_length[300]",
			),
			"rating"=>array(
				'field'=>'rating',
				'label'=>'Rating',
				'rules'=>"required|numeric",
			),
			"keterangan"=>array(
				'field'=>'keterangan',
				'label'=>'Keterangan',
				'rules'=>"max_length[4000]",
			),
		);
	}

}
