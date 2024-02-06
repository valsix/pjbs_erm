<?php
class globalfungsi {

	private $auth = array();

	public static function tanggalsekarang()
	{
		$tanggalsekarang= date("d-m-Y");
		// $tanggalsekarang= date('d-m-Y', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
		return $tanggalsekarang;
	}

	public static function statusappr($param=[])
	{
		return array(''=>'Pilih','0'=>'Draft','1'=>'Wappr','2'=>'Return','3'=>'Appr','4'=>'Cancel','5'=>'Close');
	}

	public static function menuctrl($param=[])
	{
		return array(
			''=>'Pilih'
			, 'form_reservasitool'=>'Reservasi Tool'
			, 'form_penambahantool'=>'Penambahan Tool'
			, 'form_pengurangantool'=>'Pengurangan Tool'
			, 'form_pengirimantool'=>'Pengiriman Tool'
			, 'form_penerimaantool'=>'Penerimaan Tool'
			, 'form_pengambilantool'=>'Pengambilan Tool'
			, 'form_pengembaliantool'=>'Pengembalian Tool'
		);
	}

	public static function rolemenuctrl($param=[])
	{
		return array(
			''=>'Pilih'
			, 'menyetujui'=>'Menyetujui'
			, 'mengetahui'=>'Mengetahui'
		);
	}

	public static function statuarr($param=[])
	{
		$arrreturn= [];
		$m= $param["m"];

		if(empty($m))
		{
			$arrreturn= array(''=>'Tidak', '1'=>'Ya');
		}
		else if($m == "aktif")
		{
			$arrreturn= array(''=>'Pilih', '0'=>'Aktif', '1'=>'Non Aktif');
		}
		else if($m == "ak")
		{
			$arrreturn= array(''=>'Pilih', '1'=>'Awal', '2'=>'Akhir');
		}
		else if($m == "jenistransaksi")
		{
			$arrreturn= array(''=>'Pilih','01'=>'Pengiriman','02'=>'Pengambilan');
		}

		return $arrreturn;
	}

	public static function jenistool($param=[])
	{
		$jenis= $param["jenis"];
		// echo $jenis;
		if ($jenis=='general') {
			$jen= '02';
		}
		elseif ($jenis=='spesifik'){
			$jen= '01';
		}
		elseif ($jenis=='spesial'){
			$jen= '03';
		}
		elseif ($jenis=='consumable'){
			$jen= '04';
		}

		// $vlabel= $param["vlabel"];
		// if(empty($vlabel))
		// {

		// }

		return $jen;
	}

	public static function idlokasigdproyek()
	{
		return "2";
	}

	public static function idlokasiarea()
	{
		return "3";
	}

	function numberToIna($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
	{
		$arr_value = explode(".", $value);
		
		if(count($arr_value) > 1)
			$value = $arr_value[0];
		
		if($value < 0)
		{
			$neg = "-";
			$value = str_replace("-", "", $value);
		}
		else
			$neg = false;
			
		$cntValue = strlen($value);
		//$cntValue = strlen($value);
		
		if($cntValue <= $digit)
			$resValue =  $value;
		
		$loopValue = floor($cntValue / $digit);
		
		for($i=1; $i<=$loopValue; $i++)
		{
			$sub = 0 - $i; //ubah jadi negatif
			$tempValue = $endValue;
			$endValue = substr($value, $sub*$digit, $digit);
			$endValue = $endValue;
			
			if($i !== 1)
				$endValue .= ".";
			
			$endValue .= $tempValue;
		}
		
		$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
		
		if($cntValue % $digit == 0)
			$resValue = $beginValue.$endValue;
		else if($cntValue > $digit)
			$resValue = $beginValue.".".$endValue;
		
		//additional
		if($symbol == true && $resValue !== "")
		{
			$resValue = $resValue;
		}
		
		if($minusToBracket && $neg)
		{
			$resValue = "(".$resValue.")";
			$neg = "";
		}
		
		if($minusLess == true)
		{
			$neg = "";
		}

		if(count($arr_value) == 1)
			$resValue = $neg.$resValue;
		else
			$resValue = $neg.$resValue.",".$arr_value[1];
		

		
		//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

		return $resValue;
	}

	public static function dotToNo($varId)
	{
		$newId = str_replace(".", "", $varId);	
		$newId = str_replace(",", ".", $newId);	
		return $newId;
	}

	public static function PINSERTINVLOKTOOLMTRL($idkatalogtool="", $idlokasi="")
	{
		$CI = &get_instance();
		// print_r($CI->conn);exit;
		$sql= "call PINSERTINVLOKTOOLMTRL('".$idkatalogtool."', '".$idlokasi."')";
		// echo $sql;exit;
		return $CI->conn->Execute($sql);
	}

	public static function PINSERTINVLOKTOOL($idkatalogtool="", $idlokasi="")
	{
		$CI = &get_instance();
		// print_r($CI->conn);exit;
		$sql= "call PINSERTINVLOKTOOL('".$idkatalogtool."', '".$idlokasi."')";
		// echo $sql;exit;
		return $CI->conn->Execute($sql);
	}

	public static function PKALKULASIINVENTORI($vid="", $ptabel="")
	{
		$CI = &get_instance();
		// print_r($CI->conn);exit;
		$sql= "call PKALKULASIINVENTORI(".$vid.", '".$ptabel."')";
		// echo $sql;exit;
		return $CI->conn->Execute($sql);
	}

	public static function CEKVALIDASIAPPROVE($vid="", $ptabel="")
	{
		$CI = &get_instance();
		// print_r($CI->conn);exit;
		$sql= "SELECT CEKVALIDASIAPPROVE(".$vid.", '".$ptabel."') INFOVALID FROM DUAL";
		// echo $sql;exit;
		return $CI->conn->GetRow($sql);
	}

	public static function filterdatastatusaktif($infomode, $infodatatable)
	{
		$infomode= "status".$infomode;
		$arrkondisicheck= globalfungsi::in_array_column("", $infomode, $infodatatable);
		// print_r($arrkondisicheck);exit;
		$vreturn= [];
		foreach($arrkondisicheck as $key=>$val)
		{
			array_push($vreturn, $infodatatable[$val]);
		}
		// print_r($vreturn);exit;
		return $vreturn;
	}

	public static function setoption($infodata, $infoval, $infotext, $infonote="Pilih...")
	{
		$inforeturn= [];
		$inforeturn[""]= $infonote;
		foreach($infodata as $key=>$val)
		{
			// print_r($val);exit;
			$inforeturn[$val[$infoval]]= $val[$infotext];
		}
		// print_r($inforeturn);exit;

		return $inforeturn;
	}

	public static function checkduplikasikunci($infodatatable)
	{
		$inforeturn= true;
		$cm= array_column($infodatatable, 'rowkunci');
		if($cm != array_unique($cm)){
			$inforeturn= false;
		}
		return $inforeturn;
	}

	public static function in_array_column($text, $column, $array)
	{
	    if (!empty($array) && is_array($array))
	    {
	        for ($i=0; $i < count($array); $i++)
	        {
	            if ($array[$i][$column]==$text || strcmp($array[$i][$column],$text)==0) 
					$arr[] = $i;
	        }
			return $arr;
	    }
	    // print_r($arr);exit;
	    return "";
	}

	public static function totalarray($text, $column, $array)
	{
		$infoarray= globalfungsi::in_array_column($text, $column, $array);
		// print_r($infoarray);exit;
		if(empty($infoarray))
			return 0;
		else
			return count($infoarray);
	}

	public static function makedirs($dirpath, $mode=0777)
	{
	    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
	}
}