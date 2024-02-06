<?php
class Ajaxtable extends _Controller{
	function __construct(){
		$this->xss_clean = true;
		parent::__construct();
		$this->load->library('globalfungsi');
		$this->load->library('UI');
	}
	
	function ajaxdetil()
	{
		$f= $_GET['f'];
		// echo $f;exit;

		$vreturn="";
		// check kalau ada function
		if(method_exists($this, $f))
		{
			$vreturn= $this->$f($_GET);
		}

		echo $vreturn;
	}

	function arrdecode($param)
	{
		$param= json_decode($param, true);
		$param= (array)$param;
		return $param;
	}

	function mt_risk_kajian_risikodetail($param=[])
	{
		$edited= true;
		$infomode= $param["m"];
		$i= $param["i"];

		// $id_jenispenambahantool= $param["id_jenispenambahantool"];

		// // print_r($param);exit;

		// $statementkatalog= "";
		// if ($id_jenispenambahantool=='1' or $id_jenispenambahantool=='3') 
		// {
		// 	$statementkatalog= " and statusowner_katalogtool = '1' ";
		// }
		// else if ($id_jenispenambahantool=='2') 
		// {
		// 	$statementkatalog= " and statusowner_katalogtool in ('2','3') ";
		// }
		// // echo $statementkatalog;exit;

		// $this->load->model("Mt_katalogtoolModel","mtkatalogtool");
		// $mtkatalogtoolgeneralarr = $this->mtkatalogtool->GetCombo("nama ||' - '|| spesifikasi", "is_delete = '0' and id_kategori = '3' and status = '3' ".$statementkatalog);
		// $mtkatalogtoolspesifikarr = $this->mtkatalogtool->GetCombo("nama ||' - '|| spesifikasi", "is_delete = '0' and id_kategori = '2' and status = '3' ".$statementkatalog);
		// $mtkatalogtoolspesialarr = $this->mtkatalogtool->GetCombo("nama ||' - '|| spesifikasi", "is_delete = '0' and id_kategori = '1' and status = '3' ".$statementkatalog);

		// $this->load->model("Mt_grouptoolModel","mtgrouptool");
		// $mtgrouptoolgeneralarr = $this->mtgrouptool->GetCombo("nama_grouptool", "is_delete = '0' and id_kategori = '3'");
		// $mtgrouptoolspesifikarr = $this->mtgrouptool->GetCombo("nama_grouptool", "is_delete = '0' and id_kategori = '2'");
		// $mtgrouptoolspesialarr = $this->mtgrouptool->GetCombo("nama_grouptool", "is_delete = '0' and id_kategori = '1'");

		// $this->load->model("Mt_lokasiModel","mtlokasi");
		// $mtlokasiarr = $this->mtlokasi->selectbyparamlokasiparent("list");
		// $mtlokasiarr[""]= '-pilih-';

		// $abarr= array(''=>'Pilih','0'=>'A','1'=>'B');

		$vreturn="";
		// if($infomode == "general")
		// {
			// $mtkatalogtoolgeneralarr= $this->arrdecode($param["mtkatalogtoolgeneralarr"]);
			// $mtlokasiarr= $this->arrdecode($param["mtlokasiarr"]);

			$vreturn='<tr id="tr-'.$infomode.'-'.$i.'" style="display: <?=$infodisplay?>;">';
			$formdetil= "";

			// $form= UI::createSelect('reqdatatable['.$i.'][id_katalogtool]',$mtkatalogtoolgeneralarr,$row['reqdatatable'][$i]['id_katalogtool'],$edited,$class='form-control id_katalogtool'.$i.'',"style='width:100%;'");
			$form= UI::createTextBox('reqdatatable['.$i.'][no_dinamis]','','','',$edited,$class='form-control no_dinamis'.$i.'',"style='width:100%; display:inline;'", '');
			$vreturn.= "<td>".$form."</td>";

			$infodisplay= "";
			if($infostatusheader != "")
				$infodisplay= "none";

			$formdetil= '<input type="hidden" name="reqdatatable['.$i.'][rowdetilid]" id="rowdetilid'.$i.'" value="'.$rowdetilid.'" />
				<input type="hidden" name="reqdatatable['.$i.'][rowkunci]" id="rowkunci'.$i.'" value="'.$rowkunci.'" />';
			if($edited)
			{
				$formdetil.= ' <span style="cursor:pointer; display: '.$infodisplay.';" id="iconhapus'.$i.'" class="glyphicon glyphicon-remove-circle"></span><input type="hidden" name="reqdatatable['.$i.'][status]" id="status'.$i.'" value="'.$infostatus.'" />';
			}

			$form= UI::createCheckBox('reqdatatable['.$i.'][status_aktif]',1,'',null,$edited,$class='form-control sa_check status_aktif'.$i.'',"style='width:80%;'").$formdetil;
			$vreturn.= "<td>".$form."</td>";

			$vreturn.="</tr>";
		// }

		// echo $vreturn;exit;
		return $vreturn;
	}
}
