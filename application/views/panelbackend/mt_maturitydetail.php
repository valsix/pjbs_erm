<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('tahun',$row['tahun'],'','',$edited,'form-control ',"style='text-align:right; width:100%' step='1'");
echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
?>


<?php 
$from = UI::createTextNumber('target',$row['target'],'','',$edited,'form-control ',"style='text-align:right; width:100%' step='any'");
echo UI::createFormGroup($from, $rules["target"], "target", "Target");
?>

<?php 
$from = UI::createTextNumber('realisasi',$row['realisasi'],'','',$edited,'form-control ',"style='text-align:right; width:100%' step='any'");
echo UI::createFormGroup($from, $rules["realisasi"], "realisasi", "Realisasi");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>