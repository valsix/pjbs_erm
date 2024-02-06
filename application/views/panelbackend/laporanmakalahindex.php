<br/>
<div class="col-sm-12">      

<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$row['id_kajian_risiko'],true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
	'form'=>$form,
	'sm_label'=>2,
	'label'=>'Kajian Risiko'
	));
?>

<?php 
$form = UI::createTextNumber('tahun',$row['tahun'],'200','100',true,'form-control ',"style='width:100%' onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
'form'=>$form,
'sm_label'=>2,
'label'=>'Tahun'
));
?>
<?php
$form = UI::createSelect('id_scorecard',$scorecardarr,$row['id_scorecard'],true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
'form'=>$form,
'sm_label'=>2,
'label'=>'Risk Profile'
));
?>
<?php 
if(($scorecardsubarr)){
$form = UI::createSelect('id_scorecard_sub',$scorecardsubarr,$row['id_scorecard_sub'],true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
  'form'=>$form,
  'sm_label'=>2,
  'label'=>'Scorecard'
  ));
}
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
$form = UI::createTextNumber('top',$row['top'],'200','100',true,'form-control ',"style='width:100%'");
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
