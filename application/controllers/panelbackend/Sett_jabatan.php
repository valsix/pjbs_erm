<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH."core/_adminController.php";
class Sett_jabatan extends _adminController{

	public function __construct(){
		parent::__construct();
	}
	
	protected function init(){
		parent::init();
		$this->viewlist = "panelbackend/sett_jabatanlist";
		// $this->viewdetail = "panelbackend/mt_status_progress_grcdetail";
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout_sett_jabatan";

		if ($this->mode == 'add') {
			$this->data['page_title'] = 'Tambah Setting Jabatan';
			$this->data['edited'] = true;
		}
		elseif ($this->mode == 'edit') {
			$this->data['page_title'] = 'Edit Setting Jabatan';
			$this->data['edited'] = true;	
		}
		elseif ($this->mode == 'detail'){
			$this->data['page_title'] = 'Detail Setting Jabatan';
			$this->data['edited'] = false;	
		}else{
			$this->data['page_title'] = 'Daftar Setting Jabatan';
		}

		$this->load->model("Mt_sdm_jabatanModel","model");

		$this->pk = $this->model->pk;
		$this->data['pk'] = $this->pk;
		$this->plugin_arr = array(
			''
		);

		$this->plugin_arr = array(
			'datepicker','myautocomplete','upload'
		);

		$this->data['configfile'] = $this->config->item('file_upload_config');
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
		);
	}

	protected function Record($id=null){
		return array(
			'kode'=>$this->post['kode'],
			'nama'=>$this->post['nama'],
		);
	}

	protected function Rules(){
		return array(
			"nama"=>array(
				'field'=>'nama', 
				'label'=>'Nama', 
				'rules'=>"required|max_length[100]",
			),
			"kode"=>array(
				'field'=>'kode', 
				'label'=>'%', 
				'rules'=>"required|max_length[50]",
			),
		);
	}

	public function HeaderExport(){
		$this->data['penanggungjawabarr'] = $this->conn->GetList("select id_jabatan as key, nama as val from mt_sdm_jabatan a where exists (select 1 from risk_scorecard r where a.id_jabatan = r.owner)  order by key");

		$this->data['mtkegiatanarr'] = $this->data['sasaranarr'] = $this->conn->GetList("select id_sasaran_kegiatan as key, nama as val from risk_sasaran_kegiatan a order by key");

		$this->data['kpiarr'] = $this->conn->GetList("select id_kpi as key, nama as val from risk_kpi a  order by key");

		$this->data['kemungkinanarr'] = $this->conn->GetList("select id_kemungkinan as key, kode as val from mt_risk_kemungkinan order by kode");

		$this->data['dampakarr'] = $this->conn->GetList("select id_dampak as key, kode as val from mt_risk_dampak order by kode");

		return array();

		// return array(
		// 	array(
		// 		'name'=>'kemungkinan',
		// 		'label'=>'Kemungkinan',
		// 		'width'=>"50px",
		// 		'type'=>"listinverst",
		// 		'value'=>$this->data['kemungkinanarr'],
		// 	),
		// 	array(
		// 		'name'=>'dampak',
		// 		'label'=>'Dampak',
		// 		'width'=>"50px",
		// 		'type'=>"listinverst",
		// 		'value'=>$this->data['dampakarr'],
		// 	),
		// 	array(
		// 		'name'=>'penanggung_jawab',
		// 		'label'=>'Penanggung jawab mitigasi',
		// 		'width'=>"50px",
		// 		'type'=>"listinverst",
		// 		'value'=>$this->data['penanggungjawabarr'],
		// 	),
		// 	array(
		// 		'name'=>'id_sasaran_kegiatan',
		// 		'label'=>'Sasaran Kegiatan',
		// 		'width'=>"50px",
		// 		'type'=>"listinverst",
		// 		'value'=>$this->data['sasaranarr'],
		// 	),
		// 	array(
		// 		'name'=>'id_kpi',
		// 		'label'=>'KPI',
		// 		'width'=>"50px",
		// 		'type'=>"listinverst",
		// 		'value'=>$this->data['kpiarr'],
		// 	),
		// );
	}

	public function import_list($id_scorecard=null){

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

			// $kemungkinanarr = array();
			// foreach($this->data['kemungkinanarr'] as $k=>$v){
			// 	$kemungkinanarr[strtolower(trim($v))] = $k;
			// }

			// $dampakarr = array();
			// foreach($this->data['dampakarr'] as $k=>$v){
			// 	$dampakarr[strtolower(trim($v))] = $k;
			// }

			// $penanggungjawabarr = array();
			// foreach($this->data['penanggungjawabarr'] as $k=>$v){
			// 	$penanggungjawabarr[strtolower(trim($v))] = $k;
			// }

			// $sasaranarr = array();
			// foreach($this->data['sasaranarr'] as $k=>$v){
			// 	$sasaranarr[strtolower(trim($v))] = $k;
			// }

			// $kpiarr = array();
			// foreach($this->data['kpiarr'] as $k=>$v){
			// 	$kpiarr[strtolower(trim($v))] = $k;
			// }

			// $this->conn->debug = 1;

			for ($row = 2; $row <= $highestRow; $row++)
			{ 
				//cari data position_id baru
				$datbaru = $this->conn->GetRow("select *
					from mt_sdm_jabatan 
					where position_id = '".$sheet->getCell('C'.$row)->getValue()."'");

				//cari data position_id lama
				$datlama = $this->conn->GetRow("select *
					from mt_sdm_jabatan 
					where position_id = '".$sheet->getCell('A'.$row)->getValue()."'");

				if ($datbaru && $datlama && $sheet->getCell('C'.$row)->getValue()!='') 
				{
					$id_jabatanlama= $datlama['id_jabatan'];
					$id_jabatanbaru= $datbaru['id_jabatan'];

					$arrdata['position_id_lama']= $sheet->getCell('A'.$row)->getValue();
					$arrdata['nama_lama']= $sheet->getCell('B'.$row)->getValue();

					//update data position_id_lama dengan where position_id baru
					$ret = $this->conn->goUpdate('mt_sdm_jabatan',$arrdata,"position_id = ".$this->conn->escape($sheet->getCell('C'.$row)->getValue()));

					if ($ret) 
					{
						//risk_control cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datriskctrl= $this->conn->GetRow("select *
						from risk_control 
						where penanggung_jawab = '".$id_jabatanlama."'");

						if ($datriskctrl) 
						{
							//risk_control update set penanggung_jawab_lama = id_jabatan dari data lama dengan where penanggung_jawab = id_jabatan dari data lama
							$arrdatriskctrl1['penanggung_jawab_lama']= $id_jabatanlama;
							$retriskctrl = $this->conn->goUpdate("risk_control",$arrdatriskctrl1,"penanggung_jawab = ".$this->conn->escape($id_jabatanlama));

							if ($retriskctrl) 
							{
								$arrdatriskctrl2['penanggung_jawab']= $id_jabatanbaru;
								$retriskctrl = $this->conn->goUpdate("risk_control",$arrdatriskctrl2,"penanggung_jawab_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}


						//risk_mitigasi cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datriskmit= $this->conn->GetRow("select *
						from risk_mitigasi 
						where penanggung_jawab = '".$id_jabatanlama."'");

						if ($datriskmit) 
						{
							//risk_mitigasi update set penanggung_jawab_lama = id_jabatan dari data lama dengan where penanggung_jawab = id_jabatan dari data lama
							$arrdatriskmit1['penanggung_jawab_lama']= $id_jabatanlama;
							$retriskmit = $this->conn->goUpdate("risk_mitigasi",$arrdatriskmit1,"penanggung_jawab = ".$this->conn->escape($id_jabatanlama));

							if ($retriskmit) 
							{
								$arrdatriskmit2['penanggung_jawab']= $id_jabatanbaru;
								$retriskmit = $this->conn->goUpdate("risk_mitigasi",$arrdatriskmit2,"penanggung_jawab_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}


						//risk_sasaran_kegiatan cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datrisksake= $this->conn->GetRow("select *
						from risk_sasaran_kegiatan 
						where owner = '".$id_jabatanlama."'");

						if ($datrisksake) 
						{
							//risk_sasaran_kegiatan update set owner_lama = id_jabatan dari data lama dengan where owner = id_jabatan dari data lama
							$arrdatrisksake1['owner_lama']= $id_jabatanlama;
							$retrisksake = $this->conn->goUpdate("risk_sasaran_kegiatan",$arrdatrisksake1,"owner = ".$this->conn->escape($id_jabatanlama));

							if ($retrisksake) 
							{
								$arrdatrisksake2['owner']= $id_jabatanbaru;
								$retrisksake = $this->conn->goUpdate("risk_sasaran_kegiatan",$arrdatrisksake2,"owner_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}


						//risk_sasaran_strategis_pic cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datrisksastpic= $this->conn->GetRow("select *
						from risk_sasaran_strategis_pic 
						where id_jabatan = '".$id_jabatanlama."'");

						if ($datrisksastpic) 
						{
							//risk_sasaran_strategis_pic update set id_jabatan_lama = id_jabatan dari data lama dengan where id_jabatan = id_jabatan dari data lama
							$arrdatrisksastpic1['id_jabatan_lama']= $id_jabatanlama;
							$retrisksastpic = $this->conn->goUpdate("risk_sasaran_strategis_pic",$arrdatrisksastpic1,"id_jabatan = ".$this->conn->escape($id_jabatanlama));

							if ($retrisksastpic) 
							{
								$arrdatrisksastpic2['id_jabatan']= $id_jabatanbaru;
								$retrisksastpic = $this->conn->goUpdate("risk_sasaran_strategis_pic",$arrdatrisksastpic2,"id_jabatan_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}


						//risk_scorecard cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datrisksco= $this->conn->GetRow("select *
						from risk_scorecard 
						where owner = '".$id_jabatanlama."'");

						if ($datrisksco) 
						{
							//risk_scorecard update set owner_lama = id_jabatan dari data lama dengan where owner = id_jabatan dari data lama
							$arrdatrisksco1['owner_lama']= $id_jabatanlama;
							$retrisksco = $this->conn->goUpdate("risk_scorecard",$arrdatrisksco1,"owner = ".$this->conn->escape($id_jabatanlama));

							if ($retrisksco) 
							{
								$arrdatrisksco2['owner']= $id_jabatanbaru;
								$retrisksco = $this->conn->goUpdate("risk_scorecard",$arrdatrisksco2,"owner_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}


						//risk_scorecard_view cari data dengan where penanggung_jawab = id_jabatan dari data lama
						$datriskscoview= $this->conn->GetRow("select *
						from risk_scorecard_view 
						where id_jabatan = '".$id_jabatanlama."'");

						if ($datriskscoview) 
						{
							//risk_scorecard_view update set id_jabatan_lama = id_jabatan dari data lama dengan where id_jabatan = id_jabatan dari data lama
							$arrdatriskscoview1['id_jabatan_lama']= $id_jabatanlama;
							$retriskscoview = $this->conn->goUpdate("risk_scorecard_view",$arrdatriskscoview1,"id_jabatan = ".$this->conn->escape($id_jabatanlama));

							if ($retriskscoview) 
							{
								$arrdatriskscoview2['id_jabatan']= $id_jabatanbaru;
								$retriskscoview = $this->conn->goUpdate("risk_scorecard_view",$arrdatriskscoview2,"id_jabatan_lama = ".$this->conn->escape($id_jabatanlama));
							}
						}
					}
				}

				if(!$ret)
				{
	    			$return['success'] = false;
	    			$return['error'] = "Gagal insert";
	    		}
	    		else
	    		{
	    			$return['success'] = "Data berhasil di setting.";
	    		}
				// print_r($rrr);exit;
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

	public function export_list($id_scorecard=null){
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
		$excelactive->setCellValue('A'.$row,"KODE_JABATAN_LAMA");
		$excelactive->setCellValue('B'.$row,"JABATAN_LAMA");
		$excelactive->setCellValue('C'.$row,"KODE_JABATAN_BARU");
		$excelactive->setCellValue('D'.$row,"JABATAN_BARU");

		// $col = 'Q';
		// $excelactive->getStyle('A1:'.$col.$row)->getFont()->setBold(true);
        // $excelactive
		//     ->getStyle('A1:'.$col.$row)
		//     ->getFill()
		//     ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		//     ->getStartColor()
		//     ->setARGB('eff0f1');

	    //  $style = array(
        // 	'alignment' => array(
	    //         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    //         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    //     ),
	    //     'borders' => array(
	    //         'allborders' => array(
	    //             'style' => PHPExcel_Style_Border::BORDER_THIN,
	    //             'color' => array('rgb' => '333333')
	    //         )
	    //     )
	    // );

	    // $excelactive->getStyle('A1:'.$col.$row)->applyFromArray($style);
		
		// $this->_setFilter("id_scorecard = ".$this->conn->escape($id_scorecard));

	    // #data
		// $respon = $this->model->SelectGrid(
		// 	array(
		// 	'limit' => -1,
		// 	'order' => "no, nomor",
		// 	'filter' => $this->_getFilter()
		// 	)
		// );
		// $rows = $respon['rows'];

		// $kemungkinanarr = $this->data['kemungkinanarr'];
		// $dampakarr = $this->data['dampakarr'];

		// $no1=0;

		// $row = 3;
        // foreach($rows as $r){
        // 	$no1++;

        // 	// if(!$r['no'])
    	// 	$r['no'] = (int)substr($r['nomor'],-2);

        // 	if(!$r['no'])
        // 		$r['no'] = $no1;

        // 	$$no1 = $r['no'];

        // 	$nama_kpi = $this->conn->GetListStr("select nama as val from risk_risiko_kpi a join risk_kpi b on a.id_kpi = b.id_kpi where a.id_risiko = ".$this->conn->escape($r['id_risiko']));

        // 	$nama_kegiatan = $this->conn->GetListStr("select nama as val from risk_sasaran_kegiatan where id_sasaran_kegiatan = ".$this->conn->escape($r['id_sasaran_kegiatan']));

        // 	$rs = $this->conn->GetArray("select * from risk_risiko_penyebab where id_risiko = ".$this->conn->escape($r['id_risiko']));
        // 	$penyebab = null;
        // 	$no=1;
        // 	foreach($rs as $r1){

    	// 		$nostr = ($no++).'. ';

        // 		$penyebab .= $nostr.$r1['nama']."\n";
        // 	}

        // 	$rs = $this->conn->GetArray("select * from risk_risiko_dampak where id_risiko = ".$this->conn->escape($r['id_risiko']));
        // 	$dampak = null;
        // 	$no=1;
        // 	foreach($rs as $r1){
        		
    	// 		$nostr = ($no++).'. ';

        // 		$dampak .= $nostr.$r1['nama']."\n";
        // 	}

        // 	$rs = $this->conn->GetArray("select * from risk_control where id_risiko = ".$this->conn->escape($r['id_risiko'])." order by no");
        // 	$control = null;
        // 	$no=1;
        // 	foreach($rs as $r1){

        // 		if($r1['no'])
        // 			$no=$r1['no'];
        		
    	// 		$nostr = ($no++).'. ';

        // 		$control .= $nostr.$r1['nama']."\n";
        // 	}

        // 	$rs = $this->conn->GetArray("select m.*, j.nama as penanggung_jawab
        // 		from risk_mitigasi m 
        // 		join mt_sdm_jabatan j on m.penanggung_jawab = j.id_jabatan
        // 		where id_risiko = ".$this->conn->escape($r['id_risiko'])." order by no");

        // 	$mitigasi = null;
        // 	$biaya = null;
        // 	$penanggung_jawab_mitigasi = null;
        // 	$dead_line_mitigasi = null;
        // 	$no=1;
        // 	foreach($rs as $r1){

        // 		if($r1['no'])
        // 			$no=$r1['no'];
        		
    	// 		$nostr = ($no++).'. ';

        // 		$mitigasi .= $nostr.$r1['nama']."\n";
        // 		$biaya .= $nostr.$r1['biaya']."\n";
        // 		$penanggung_jawab_mitigasi .= $nostr.$r1['penanggung_jawab']."\n";
        // 		$dead_line_mitigasi .= $nostr.$r1['dead_line']."\n";
        // 	}

        // 	if(!$penyebab)
        // 		$penyebab = $r['penyebab'];

        // 	if(!$dampak)
        // 		$dampak = $r['dampak'];

	    // 	$excelactive->setCellValue('A'.$row,$nama_kegiatan);
	    // 	$excelactive->setCellValue('B'.$row,$nama_kpi);
	    // 	$excelactive->setCellValue('C'.$row,$r['no']);
	    // 	$excelactive->setCellValue('D'.$row,$r['nama']);
	    // 	$excelactive->setCellValue('E'.$row,$penyebab);
	    // 	$excelactive->setCellValue('F'.$row,$dampak);
	    // 	$excelactive->setCellValue('G'.$row,$kemungkinanarr[$r['inheren_kemungkinan']]);
	    // 	$excelactive->setCellValue('H'.$row,$dampakarr[$r['inheren_dampak']]);
	    // 	$excelactive->setCellValue('I'.$row,$control);
	    // 	$excelactive->setCellValue('J'.$row,$kemungkinanarr[$r['control_kemungkinan_penurunan']]);
	    // 	$excelactive->setCellValue('K'.$row,$dampakarr[$r['control_dampak_penurunan']]);
	    // 	$excelactive->setCellValue('L'.$row,$mitigasi);
	    // 	$excelactive->setCellValue('M'.$row,$biaya);
	    // 	$excelactive->setCellValue('N'.$row,$penanggung_jawab_mitigasi);
	    // 	$excelactive->setCellValue('O'.$row,$dead_line_mitigasi);
	    // 	$excelactive->setCellValue('P'.$row,$kemungkinanarr[$r['residual_target_kemungkinan']]);
	    // 	$excelactive->setCellValue('Q'.$row,$dampakarr[$r['residual_target_dampak']]);
        //     $row++;
        // }


	    $objWriter = Factory::createWriter($excel,'Excel2007');
	    ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->ctrl.date('Ymd').'.xls"');
		header('Cache-Control: max-age=0');
		ob_end_clean();
		$objWriter->save('php://output');
		exit();
	}

}