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
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$row['id_kajian_risiko'],true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Kajian Risiko'
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
$form = UI::createSelect('rating',array(
	"i"=>"Inheren Risk",
	"c"=>"Current Risk",
	"r"=>"Targeted Residual Risk",
	"ic"=>"Inheren Risk vs Current Risk",
	"ir"=>"Inheren Risk vs Targeted Residual Risk",
	"cr"=>"Current Risk vs Targeted Residual Risk",
	"icr"=>"Inheren Risk vs Current Risk vs Targeted Residual Risk",
	),(!$row['rating']?'icr':$row['rating']),true,'form-control select2',"style='width:auto; max-width:100%;'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Rating'
	));
?>

<?php 
$form = UI::createTextNumber('top',($row['top']?$row['top']:10),'4','4',true,'form-control ');
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Top'
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
