<?php

define('lang_Select','Select');
define('lang_Erase','Erase');
define('lang_Open','Open');
define('lang_Confirm_del','Are you sure you want to delete this file?');
define('lang_All','All');
define('lang_Files','Files');
define('lang_Images','Images');
define('lang_Archives','Archives');
define('lang_Error_Upload','The uploaded file exceeds the max size allowed.');
define('lang_Error_extension','File extension is not allowed.');
define('lang_Upload_file','Upload');
define('lang_Filter','Filter');
define('lang_Videos','Videos');
define('lang_Music','Music');
define('lang_New_Folder','New Folder');
define('lang_Folder_Created','Folder correctly created');
define('lang_Existing_Folder','Existing folder');
define('lang_Confirm_Folder_del','Are you sure to delete the folder and all the elements in it?');
define('lang_Return_Files_List','Return to files list');
define('lang_Preview','Preview');
define('lang_Download','Download');
define('lang_Insert_Folder_Name','Insert folder name:');
define('lang_Root','root');
define('lang_Send_File','Send File');

function mime($ext){
	$mimes = array(	'hqx'	=>	'application/mac-binhex40',
				'cpt'	=>	'application/mac-compactpro',
				'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
				'bin'	=>	'application/macbinary',
				'dms'	=>	'application/octet-stream',
				'lha'	=>	'application/octet-stream',
				'lzh'	=>	'application/octet-stream',
				'exe'	=>	array('application/octet-stream', 'application/x-msdownload'),
				'class'	=>	'application/octet-stream',
				'psd'	=>	'application/x-photoshop',
				'so'	=>	'application/octet-stream',
				'sea'	=>	'application/octet-stream',
				'dll'	=>	'application/octet-stream',
				'oda'	=>	'application/oda',
				'pdf'	=>	array('application/pdf', 'application/x-download'),
				'ai'	=>	'application/postscript',
				'eps'	=>	'application/postscript',
				'ps'	=>	'application/postscript',
				'smi'	=>	'application/smil',
				'smil'	=>	'application/smil',
				'mif'	=>	'application/vnd.mif',
				'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
				'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
				'wbxml'	=>	'application/wbxml',
				'wmlc'	=>	'application/wmlc',
				'dcr'	=>	'application/x-director',
				'dir'	=>	'application/x-director',
				'dxr'	=>	'application/x-director',
				'dvi'	=>	'application/x-dvi',
				'gtar'	=>	'application/x-gtar',
				'gz'	=>	'application/x-gzip',
				'php'	=>	'application/x-httpd-php',
				'php4'	=>	'application/x-httpd-php',
				'php3'	=>	'application/x-httpd-php',
				'phtml'	=>	'application/x-httpd-php',
				'phps'	=>	'application/x-httpd-php-source',
				'js'	=>	'application/x-javascript',
				'swf'	=>	'application/x-shockwave-flash',
				'sit'	=>	'application/x-stuffit',
				'tar'	=>	'application/x-tar',
				'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
				'xhtml'	=>	'application/xhtml+xml',
				'xht'	=>	'application/xhtml+xml',
				'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
				'rar'	=>  array('application/x-rar-compressed', 'application/octet-stream'),
				'mid'	=>	'audio/midi',
				'midi'	=>	'audio/midi',
				'mpga'	=>	'audio/mpeg',
				'mp2'	=>	'audio/mpeg',
				'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
				'aif'	=>	'audio/x-aiff',
				'aiff'	=>	'audio/x-aiff',
				'aifc'	=>	'audio/x-aiff',
				'ram'	=>	'audio/x-pn-realaudio',
				'rm'	=>	'audio/x-pn-realaudio',
				'rpm'	=>	'audio/x-pn-realaudio-plugin',
				'ra'	=>	'audio/x-realaudio',
				'rv'	=>	'video/vnd.rn-realvideo',
				'wav'	=>	array('audio/x-wav', 'audio/wave', 'audio/wav'),
				'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
				'gif'	=>	'image/gif',
				'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
				'png'	=>	array('image/png',  'image/x-png'),
				'tiff'	=>	'image/tiff',
				'tif'	=>	'image/tiff',
				'css'	=>	'text/css',
				'html'	=>	'text/html',
				'htm'	=>	'text/html',
				'shtml'	=>	'text/html',
				'txt'	=>	'text/plain',
				'text'	=>	'text/plain',
				'log'	=>	array('text/plain', 'text/x-log'),
				'rtx'	=>	'text/richtext',
				'rtf'	=>	'text/rtf',
				'xml'	=>	'text/xml',
				'xsl'	=>	'text/xml',
				'mpeg'	=>	'video/mpeg',
				'mpg'	=>	'video/mpeg',
				'mpe'	=>	'video/mpeg',
				'qt'	=>	'video/quicktime',
				'mov'	=>	'video/quicktime',
				'avi'	=>	'video/x-msvideo',
				'movie'	=>	'video/x-sgi-movie',
				'doc'	=>	'application/msword',
				'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'word'	=>	array('application/msword', 'application/octet-stream'),
				'xl'	=>	'application/excel',
				'eml'	=>	'message/rfc822',
				'json' 	=> array('application/json', 'text/json'),
				'pptx'	=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
			);
	return $mimes[$ext];
}

function array_ramdom($list) {
  if (!is_array($list)) return $list;

  $keys = array_keys($list);
  shuffle($keys);
  $random = array();
  foreach ($keys as $key) {
    $random[$key] = $list[$key];
  }
  return $random;
}

function replaceSingleQuote(&$val){
	if(is_array($val)){
		foreach($val as $k=>$v){
			$val[$k]=replaceSingleQuote($v);
		}
	}else{
		$val = str_replace("'", "''", $val);
	}
}

function ReadMore($text='',$urlreadmore='#',$readmore=true){
	if(is_object($text))
		return $text;

	$str='';
	$str.=strstr($text, '<br /><!-- pagebreak --><br />', true);
	if(!$str){
		$str.=strstr($text, '<!-- pagebreak -->', true);
	}
	if(!$str){
		$readmore = false;
		$str.= $text;
	}
	if($readmore){
		$str.='<a title="Read more" href="'.$urlreadmore.'" class="more">Read more →</a>';
	}
	$str.="<div style='clear:both'></div>";


	return $str;
}

function DifTime($time1, $time2){
	$time1 = strtotime($time1);
	$time2 = strtotime($time2);
	$time3 = $time1-$time2;
	$jam = floor($time3/3600);
	$time3 = $time3%3600;
	$menit = floor($time3/60);
	$time3 = $time3%60;
	$detik = $time3;
	return $jam.' jam, '. $menit.' menit, '. $detik.' detik';
}



function ReadMorePlain($text='',$count_word=10){
	//$text = str_replace(array('<h2>','</h2>','<p>','</p>','<strong>','</strong>', '<ol>', '</ol>', '<li>', '</li>', '<!-- pagebreak -->'),'',$text);
	//$text = str_replace(array('  ', '&nbsp;',"\r\t","\r\n"), ' ', $text);
	$text = strip_tags($text);
	$text_arr = explode(' ', $text);

	$i=0;
	$str = '';
	foreach ($text_arr as $key => $value) {
		$i++;
		$str.=$value.' ';
		if($count_word==$i)
			break;
	}

	return $str;
}

#2012-01-01
function Eng2Ind($datetime,$is_time=true){
	$ci = get_instance();
	$exp = explode(" ", $datetime);
	$date = $datetime;
	$time = '';
	if(($exp)>1){
		$time = substr($exp[1], 0, 8);
		$date = $exp[0];
	}

	if(!$is_time)
		$time = '';

	$exp1 = explode("-", $date);
	$list_bulan = ListBulan();
	$date_format = $ci->config->item("date_format");
	if($date_format=="YYYY-MM-DD")
		return $exp1[2].' '.substr($list_bulan[$exp1[1]],0,3).' '.$exp1[0].' '.$time;
	else
		return $exp1[0].' '.substr($list_bulan[$exp1[1]],0,3).' '.$exp1[2].' '.$time;
}

function RevertDate($date){
	list($d,$m,$y) = explode("-",$date);
	return $y."-".$m."-".$d;
}

function ListBulan(){
	return array(
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'Nopember',
			'12'=>'Desember',
		);
}

function GetBulan($bulan=null){
	return ListBulan()[str_pad($bulan,2, "0", STR_PAD_LEFT)];
}

function ListHari(){
	return array('Minggu','Senin','Selasa','Rabu','Kamis',"Jum'at",'Sabtu');
}

function Hari($i){
	$hari_arr = ListHari();
	return $hari_arr[$i];
}

function DateHari($date){
	$time = strtotime($date);
	return Hari(date('w',$time));
}

function DateDiff($besar, $kecil){
	$a = strtotime($besar);
	$b = strtotime($kecil);
	$c = $a - $b;
	if($c>=86400){
		$hari = floor($c/86400).' hari ';
		$c = $c%86400;
	}
	if($c>=3600){
		$jam = floor($c/3600).' jam ';
		$c = $c%3600;
	}
	if($c>=60){
		$menit = floor($c/60).' menit ';
		$c = $c%60;
	}
	return $hari.$jam.$menit;

}

function DNDcheck($mobileno)
{
	$mobileno = substr($mobileno, -10, 10);
	$url = "http://www.nccptrai.gov.in/nccpregistry/saveSearchSub.misc";
	$postString = "phoneno=" . $mobileno;
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HEADER, 0);
	//curl_setopt($request , CURLOPT_PROXY , '10.3.100.211:8080' );
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_POST, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $postString);
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
	$response = curl_exec($request);
	curl_close ($request);

	return (is_int(strpos(strtolower(strip_tags($response)), "number is not")) ? false : true);
}

function filter_data($data)
{
	if($data==NULL) return "<i>Unknown</i>";
	else return $data;
}

function nice_date($str, $option=NULL)
{
	// convert the date to unix timestamp
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	$now = time();
	$blocks = array(
	array('name'=>lang('kalkun_year'), 'amount' => 60*60*24*365),
	array('name'=>lang('kalkun_month'), 'amount' => 60*60*24*31),
	array('name'=>lang('kalkun_week'), 'amount' => 60*60*24*7),
	array('name'=>lang('kalkun_day'), 'amount' => 60*60*24),
	array('name'=>lang('kalkun_hour'), 'amount' => 60*60),
	array('name'=>lang('kalkun_minute'), 'amount' => 60),
	array('name'=>lang('kalkun_second'), 'amount' => 1)
	);

	if($timestamp > $now) $string_type = ' remaining';
	else $string_type = ' '.lang('kalkun_ago');

	$diff = abs($now-$timestamp);

	if($option=='smsd_check')
	{
		return $diff;
	}
	else
	{
		if($diff < 60)
		{
			return "Less than a minute ago";
		}
		else
		{
			$levels = 1;
			$current_level = 1;
			$result = array();
			foreach($blocks as $block)
			{
				if ($current_level > $levels) { break; }
				if ($diff/$block['amount'] >= 1)
				{
					$amount = floor($diff/$block['amount']);
					$plural = '';
					//if ($amount>1) {$plural='s';} else {$plural='';}
					$result[] = $amount.' '.$block['name'].$plural;
					$diff -= $amount*$block['amount'];
					$current_level+=1;
				}
			}
			$res = implode(' ',$result).''.$string_type;
			return $res;
		}
	}
}

function get_modem_status($status, $tolerant)
{
	// convert the date to unix timestamp
	list($date, $time) = explode(' ', $status);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute+$tolerant, $second, $month, $day, $year);
	$now = time();

	//$diff = abs($now-$timestamp);
	if($timestamp>$now)
	{
		return "connect";
	}
	else
	{
		return "disconnect";
	}
}

function message_preview($str, $n)
{
	if (strlen($str) <= $n) return showtags($str);
	else return showtags(substr($str, 0, $n-3)).'&#8230;';
}

function showtags($msg)
{
	$msg = preg_replace("/</","&lt;",$msg);
	$msg = preg_replace("/>/","&gt;",$msg);
	return $msg;
}

function showmsg($msg)
{
	return nl2br(showtags($msg));
}

function compare_date_asc($a, $b)
{
	$date1 = strtotime($a['globaldate']);
	$date2 = strtotime($b['globaldate']);

	if($date1 == $date2) return 0;
	return ($date1 < $date2) ? -1 : 1;
}

function compare_date_desc($a, $b)
{
	$date1 = strtotime($a['globaldate']);
	$date2 = strtotime($b['globaldate']);

	if($date1 == $date2) return 0;
	return ($date1 > $date2) ? -1 : 1;
}

function check_delivery_report($report)
{
	if($report=='SendingError' or $report=='Error' or $report=='DeliveryFailed'): $status = lang('tni_msg_stat_fail');
	elseif($report=='SendingOKNoReport'): $status = lang('tni_msg_stat_oknr');
	elseif($report=='SendingOK'): $status = lang('tni_msg_stat_okwr');
	elseif($report=='DeliveryOK'): $status = lang('tni_msg_stat_deliv');
	elseif($report=='DeliveryPending'): $status = lang('tni_msg_stat_pend');
	elseif($report=='DeliveryUnknown'): $status = lang('tni_msg_stat_unknown');
	endif;

	return $status;
}

function simple_date($datetime)
{
	list($date, $time) = explode(' ', $datetime);
	list($year, $month, $day) = explode('-', $date);
	return $day.'/'.$month.'/'.$year.' '.$time;
}

function get_hour()
{
	for($i=0;$i<24;$i++)
	{
		$hour = $i;
		if($hour<10) $hour = "0".$hour;
		echo "<option value=\"".$hour."\">".$hour."</option>";
	}
}

function get_minute()
{
	for($i=0;$i<60;$i=$i+5)
	{
		$min = $i;
		if($min<10) $min = "0".$min;
		echo "<option value=\"".$min."\">".$min."</option>";
	}
}

function is_ajax()
{
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function rupiah($number){
	if(!$number)return 0;
	return number_format ($number , 2 , "," , "." );
}

function rupiahAngka($angka) {
	if(!$angka)return 0;
	$rupiah = number_format($angka , 2 , "," , "." );
	return "Rp ".$rupiah;
}

function terbilang($satuan){
	$huruf = array ("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh","sebelas");
	if($satuan < 0){
		$add = " minus ";
		$satuan  = abs($satuan);
	}

	if ($satuan < 12)
		return $add." ".$huruf[$satuan];
	elseif ($satuan < 20)
		return terbilang($satuan - 10)." belas";
	elseif ($satuan < 100)
		return $add.terbilang($satuan / 10)." puluh".terbilang($satuan % 10);
	elseif ($satuan < 200)
		return "seratus".terbilang($satuan - 100);
	elseif ($satuan < 1000)
		return $add.terbilang($satuan / 100)." ratus".terbilang($satuan % 100);
	elseif ($satuan < 2000)
		return $add."seribu".terbilang($satuan - 1000);
	elseif ($satuan < 1000000)
		return $add.terbilang($satuan / 1000)." ribu".terbilang($satuan % 1000);
	elseif ($satuan < 1000000000)
		return $add.terbilang($satuan / 1000000)." juta".terbilang($satuan % 1000000);
	elseif ($satuan >= 1000000000)
		return "Angka yang Anda masukkan terlalu besar";
}

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
function memory_used(){
	return convert(memory_get_usage(true)); // 123 kb
}
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}


function delete_folder($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!delete_folder($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return rmdir($dir);
}

function create_img_gd($imgfile, $imgthumb, $newwidth, $newheight="") {

	$ci = get_instance();

    $ci->load->library("Imagelib",array('fileName'=>$imgfile));
    // *** Resize to best fit then crop
    $ci->imagelib -> resizeImage($newwidth, $newheight, 'crop');

    // *** Save resized image as a PNG
    $ci->imagelib -> saveImage($imgthumb);
}

function makeSize($size) {
   $units = array('B','KB','MB','GB','TB');
   $u = 0;
   while ( (round($size / 1024) > 0) && ($u < 4) ) {
     $size = $size / 1024;
     $u++;
   }
   return (number_format($size, 1, ',', '') . " " . $units[$u]);
}

function create_folder($path=false){
	$oldumask = umask(0);
	if ($path && !file_exists($path))
		mkdir($path, 0775); // or even 01777 so you get the sticky bit set

	umask($oldumask);
}

function Title($title="",$add = true){
	$ci = get_instance();
	if($title && $add)
		return $ci->config->item("title")." | ".$title;
	else if($title && !$add)
		return $title;
	else
		return $ci->config->item("title");
}


function FlashMsg(){
	$ci = get_instance();
	if(Get($ci->ctrl.'suc_msg')){
		$ci->data['suc_msg']=GetFlash($ci->ctrl.'suc_msg');
	}
	if(Get($ci->ctrl.'inf_msg')){
		$ci->data['inf_msg']=GetFlash($ci->ctrl.'inf_msg');
	}
	if(Get($ci->ctrl.'wrn_msg')){
		$ci->data['wrn_msg']=GetFlash($ci->ctrl.'wrn_msg');
	}
	if(Get($ci->ctrl.'err_msg')){
		$ci->data['err_msg']=GetFlash($ci->ctrl.'err_msg');
	}

	if($ci->data['suc_msg']){
		echo '
		<div class="alert-dismissible alert alert-success" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		'.$ci->data['suc_msg'].'
		</div>';
	}
	if($ci->data['inf_msg']){
		echo '
		<div class="alert-dismissible alert alert-info" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		'.$ci->data['inf_msg'].'
		</div>';
	}
	if($ci->data['wrn_msg']){
		echo '
		<div class="alert-dismissible alert alert-warning" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		'.$ci->data['wrn_msg'].'
		</div>';
	}
	if($ci->data['err_msg']){
		echo '
		<div class="alert-dismissible alert alert-danger" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		'.$ci->data['err_msg'].'
		</div>';
	}

	if($ci->data['err_msg'] or $ci->data['wrn_msg'] or $ci->data['inf_msg'] or $ci->data['suc_msg'])
		echo '<script>$(function(){sessionStorage.scrollTop=0;});</script>';
}

function SetFlash($key, $msg){
	$ci = get_instance();
	Set($ci->ctrl.$key, $msg);
}

function SetPage($keys, $val){
	$ci = get_instance();

	if(is_string($keys)){
		$_SESSION[SESSION_APP][$ci->page_ctrl][$keys] = $val;
		return;
	}

	if(is_array($keys)){
		foreach ($keys as $key => $value) {
			# code...
			$_SESSION[SESSION_APP][$ci->page_ctrl][$key] = $value;
		}
	}

}

function GetPage($key){
	$ci = get_instance();
	return $_SESSION[SESSION_APP][$ci->page_ctrl][$key];
}

function Set($keys, $val){
	if(is_string($keys)){
		$_SESSION[SESSION_APP][$keys] = $val;
		return;
	}

	if(is_array($keys)){
		foreach ($keys as $key => $value) {
			# code...
			$_SESSION[SESSION_APP][$key] = $value;
		}
	}
}

function Get($key){
	return $_SESSION[SESSION_APP][$key];
}

function GetFlash($key){
	$return = $_SESSION[SESSION_APP][$key];
	unset($_SESSION[SESSION_APP][$key]);
	return $return;
}

function CreateList($data){
	$ret = array();
	foreach ($data as $r) {
		$ret[$r['key']] = $r['val'];
	}
	return $ret;
}

function var2alias($str){
	$data = array(
		'panelbackend/risk_scorecard'=>'Kajian Risiko',
		'panelbackend/risk_risiko'=>'Risiko',
		'panelbackend/risk_sasaran_kegiatan'=>'Kegiatan',
		'panelbackend/risk_control'=>"Kontrol",
		'panelbackend/risk_mitigasi'=>"Mitigasi"
	);

	if($data[$str])
		return $data[$str];
	else
		return $str;
}

function Access($mode, $page=null){
	$ci = &get_instance();
	return $ci->Access($mode, $page);
}

function status_proyek($id_status_proyek = null){
	$ci = &get_instance();
	if(!$ci->data['status_proyekarr']){
		$rows = $ci->conn->GetArray("select * from mt_status_proyek");
		$ci->data['status_proyekarr'] = array();
		foreach($rows as $r){
			$ci->data['status_proyekarr'][$r['id_status_proyek']] = '<span class="label" style="background-color:'.$r['warna'].'">'.$r['nama'].'</span>';
		}
	}

	return $ci->data['status_proyekarr'][$id_status_proyek];
}

function accessbystatus($id_status_pengajuan=null, $page_ctrl=null){
	if(!$page_ctrl){
		$page_ctrl = "panelbackend/risk_risiko";
	}

	if(Access("view_all_direktorat",$page_ctrl))
		return true;

	#posisi koordinator
	if($id_status_pengajuan=='1' or $id_status_pengajuan=='4' or $id_status_pengajuan=='5')
		return true;

	#posisi owner
	if(($id_status_pengajuan=='2') && (Access("persetujuan",$page_ctrl) or Access("penerusan",$page_ctrl)))
		return true;

	#posisi reviewer
	if($id_status_pengajuan=='3' && Access("persetujuan",$page_ctrl))
		return true;
	
	return false;
}

function labelstatus($id_status_pengajuan, $row=array()){
	/*$arr = array(
		'1'=>'<span class="label label-default">DRAFT</span>',
		'2'=>'<span class="label label-warning">DIAJUKAN KE OWNER</span>',
		'3'=>'<span class="label label-default">DIKEMBALIKAN KE KOORDINATOR</span>',
		'4'=>'<span class="label label-success">DITERUSAN KE REVIEWER</span>',
		'5'=>'<span class="label label-warning">DIKEMBALIKAN KE OWNER</span>',
		'6'=>'<span class="label label-primary">DISETUJUI</span>',
	);*/
	$arr = array(
		'1'=>'<span class="label label-default">DRAFT</span>',
		'2'=>'<span class="label label-warning">DIAJUKAN KE OWNER</span>',
		'3'=>'<span class="label label-success">DISETUJUI DAN DITERUSAN KE REVIEWER</span>',
		'4'=>'<span class="label label-danger">DIKEMBALIKAN</span>',
		'5'=>'<span class="label label-primary">DISETUJUI</span>',
		'6'=>'<span class="label bg-yellow">MENUNGGU KONFIRMASI INTERDEPENDENT</span>',
	);

	if($row['is_lock'])
		$arr['1'] = '<span class="label label-default">EDITED</span>';

	return $arr[$id_status_pengajuan];
}

function labelstatusrisiko($status_risiko){
	$arr = array(
		'0'=>'<span class="label label-default">CLOSED</span>',
		'1'=>'<span class="label label-success">OPEN</span>',
		'2'=>'<span class="label label-warning">BERLANJUT</span>',
	);
	return $arr[$status_risiko];
}

function listefektifitas(){
	$arr = array(
		'1'=>'Efektif',
		'2'=>'Tidak Efektif',
	);

	return $arr;
}

function labelefektifitas($status=null){
	$arr = array(
		'1'=>'<span class="label label-primary">EFEKTIF</span>',
		'2'=>'<span class="label label-danger">TIDAK EFEKTIF</span>',
	);
	if($arr[$status])
		return $arr[$status];
	else
		return '';
}

function labelkonfirmasi($status=null){
	$arr = array(
		'0'=>'<span class="label label-warning">DALAM KONFIRMASI</span>',
		'1'=>'<span class="label label-success">DISETUJUI</span>',
		'2'=>'<span class="label label-danger">DITOLAK</span>',
	);
	if($arr[$status])
		return $arr[$status];
	else
		return '';
}

function labeltingkatrisiko($id_tingkat=null){

	$ci = get_instance();
	$mtriskmatrixarr = $ci->data['mttingkatdampakarr1'];

	$matrixarr = array();
	foreach ($mtriskmatrixarr as $r) {
			$matrixarr[$r['id_kemungkinan'].$r['id_dampak']] = "<td align='center'><label class='label' style='background-color:$r[warna]; color:#000;'>{$r['kode_kemungkinan']}{$r['kode_dampak']}</label></td>";
	}

	if($matrixarr[$id_tingkat]=='-pilih-' or !$matrixarr[$id_tingkat])
		return '<td></td>';

	return $matrixarr[$id_tingkat];
}

function dpr($arr, $die=false){
	echo "<pre>";
	var_dump($arr);
	echo "</pre>";
	if($die)
		die();
}

function Rupiah2Number($str){
	return str_replace(",",".",str_replace(".", "", $str));
}


function HitungCBA($nilai_cr,$nilai_rr,$revenue,$implement_cost, $is_debug = false) {
	#cba = (revenue * rating current risk) - (revenue * rating residual risk) / biaya

	$baseline_cost = 0;
	$baseline_cost = $revenue * $nilai_cr;
	$residual_cost = 0;
	$residual_cost = $revenue * $nilai_rr;
	$benefit_cost = 0;
	$benefit_cost = $baseline_cost - $residual_cost;
	$cba = 0;

	if($is_debug){
		echo "baseline_cost $baseline_cost <br/>";
		echo "residual_cost $residual_cost <br/>";
		echo "benefit_cost $benefit_cost <br/>";
		echo "baseline_cost $baseline_cost <br/>";
		echo "implement_cost $implement_cost <br/>";
	}

	if(!(float)$implement_cost)
		return 0;
	else
		$cba = (float)$benefit_cost / (float)$implement_cost;

	return round($cba,1);
}

function RefererPageCtrl(){
	$exp = explode("/",str_replace(site_url(), "", $_SERVER['HTTP_REFERER']));
	return $page_ctrl = $exp[0]."/".$exp[1];
}


function waktu_lalu($timestamp, $now, $limit_jam=true)
{
	$nowtime = time();

	if($now)
		$nowtime = strtotime($now);

    $selisih = $nowtime - strtotime($timestamp) ;

    $detik = $selisih ;
    $menit = round($selisih / 60 );
    $jam = round($selisih / 3600 );
    $hari = round($selisih / 86400 );
    $minggu = round($selisih / 604800 );
    $bulan = round($selisih / 2419200 );
    $tahun = round($selisih / 29030400 );

    if($limit_jam){
    	if ($detik <= 60) {
	        $waktu = $detik.' detik yang lalu';
	    } else if ($menit <= 60) {
	        $waktu = $menit.' menit yang lalu';
	    } else if ($jam <= 24) {
	    	list($tgl, $waktu) = explode(" ", $timestamp);
	    	
	    	if(!$waktu)
	    		$waktu = $jam.' jam yang lalu';

	    }else{
	    	list($tgl, $waktu) = explode(" ", $timestamp);
	    	$waktu = DateHari($tgl).", ".Eng2Ind(RevertDate($tgl))." ".$waktu;
	    }
    }else{
    	if ($detik <= 60) {
	        $waktu = $detik.' detik yang lalu';
	    } else if ($menit <= 60) {
	        $waktu = $menit.' menit yang lalu';
	    } else if ($jam <= 24) {
	        $waktu = $jam.' jam yang lalu';
	    } else if ($hari <= 7) {
	        $waktu = $hari.' hari yang lalu';
	    } else if ($minggu <= 4) {
	        $waktu = $minggu.' minggu yang lalu';
	    } else if ($bulan <= 12) {
	        $waktu = $bulan.' bulan yang lalu';
	    } else {
	        $waktu = $tahun.' tahun yang lalu';
	    }
	}

    return $waktu;
}

function get_key($data, $col){
	if(!is_array($data))
		return array();

	$dataarr = array();
	foreach ($data as $r) {
		$dataarr[] = $r[$col];
	}

	return $dataarr;
}

function get_key_str($data, $col, $delimiter=","){
	$r = get_key($data, $col);

	return implode($delimiter, $r);
}

function ext($file_name){
	$exp = explode(".", $file_name);

	return $exp[count($exp)-1];
}

function is_count($d=array()){
	if(!$d)
		return 0;

	return count($d);
}

function labelverified($row){
	$ret = null;
	if($row['rekomendasi_is_verified']=='1')
		$ret .= "<label class='label label-warning'>Review dan rekomendasi $row[rekomendasi_group]</label><label class='label label-success'><span class='glyphicon glyphicon-ok'></span> Verified</label>&nbsp;&nbsp;";
	elseif($row['rekomendasi_is_verified']=='2')
		$ret .= "<label class='label label-warning'>Review dan rekomendasi $row[rekomendasi_group]</label>&nbsp;&nbsp;";

	if($row['review_is_verified']=='1')
		$ret .= "<label class='label label-warning'>Review dan rekomendasi $row[review_group]</label><label class='label label-success'><span class='glyphicon glyphicon-ok'></span> Verified</label>&nbsp;&nbsp;";
	elseif($row['review_is_verified']=='2')
		$ret .= "<label class='label label-warning'>Review dan rekomendasi $row[review_group]</label>&nbsp;&nbsp;";

	if($ret)
		$ret = "<br/>".$ret;
	
	return $ret;
}

function script_name(){
	return str_replace("index.php","",$_SERVER['SCRIPT_NAME']);
}

function host() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'] . '/';
    return $protocol . $domainName;
}

function file_manager_ext($param=""){
	$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff');
	$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv','html','psd','sql','log','fla','xml','ade','adp','ppt','pptx');
	$ext_misc = array('zip', 'rar','gzip');
	$ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg','wma');
	$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid');

	if(isset(${$param}))
		return ${$param};

	return array_merge($ext_img, $ext_file, $ext_misc, $ext_video,$ext_music);
}

function file_manager_image($param=""){
	$arr = array(
		'image_max_width'=>0,
		'image_max_height'=>0,
		'image_resizing'=>false,
		'image_width'=>600,
		'image_height'=>0,
		);
	return $arr[$param];
}

function file_manager_permit($param=""){
	$arr = array(
		'delete_file'=>true,
		'create_folder'=>true,
		'delete_folder'=>true,
		'upload_files'=>true,
		);
	return $arr[$param];
}

function nl2brword($text=null){
	if($text)
		return str_replace("\n", "<w:br/>", $text);
	else
		return null;
}