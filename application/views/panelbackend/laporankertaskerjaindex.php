<br/>
<div class="col-sm-12">     

<?php 
if(!$row['bulan'])
	$row['bulan'] = date('m');

$form = "<table><tr>
	<td width='200px'>".UI::createSelect('bulan',ListBulan(),$row['bulan'],true,'form-control select2',"style='width:auto; max-width:100%;'")."</td><td width='20px'></td>
	<td width='100px'>".UI::createTextNumber('tahun',($row['tahun']?$row['tahun']:date('Y')),'4','4',true,'form-control ', " onchange='goSubmit(\"set_value\")'")."</td>
	</tr></table>";
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Bulan Tahun'
	));
?>

<?php
$form = UI::createSelect('id_sasaran_strategis',$sasaranarr,$row['id_sasaran_strategis'],true,'form-control select2',"style='width:auto; max-width:100%;'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Sasaran Strategis'
	));
?>

<?php
$form = UI::createSelect('id_status_pengajuan',$mtstatusarr,$row['id_status_pengajuan'],true,'form-control select2',"style='width:auto; max-width:100%;'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Status Pengajuan'
	));
?>

<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$row['id_kajian_risiko'],true,'form-control select2',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Kajian Risiko'
	));
?>

<?php
$form = UI::createSelect('kd_subdit',$mtbidangarr,$row['kd_subdit'],true,'form-control select2',"style='width:auto; max-width:100%;'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Subdit'
	));
?>

<?php 
$form = require_once("_scorecard.php");

echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Scorecard'
	));
?>

<?php 
$form = require_once("_columns.php");

echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Kolom'
	));
?>

<?php 
$form = UI::createSelect('jenis',array('0'=>'Semua','1'=>'Inheren Risk','2'=>'Current Risk', '3'=>'Targeted Residual Risk'),$row['jenis'],true,'form-control select2');
$form1 = UI::createSelect('tingkat',$tingkatarr,$row['tingkat'],true,'form-control select2',"style='width:100px;'");
echo UI::FormGroup(array(
	'form'=>"<table><tr><td style='width:150px'>".$form."</td><td style='width:10px'></td>
	<td style='width:200px; ".(($row['jenis'])?"":"display:none")."' class='tdjenis'>".$form1."</td></tr></table>",
	'sm_label'=>2,
	'label'=>'Tingkat'
	));
?>

<?php
$form = UI::getButton('print', null, true);
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	));
?>

</div>
<script type="text/javascript">
	$("#jenis").change(function(){
		if($(this).val()!='0'){
			$(".tdjenis").show();
		}else{
			$(".tdjenis").hide();
		}
	});
</script>