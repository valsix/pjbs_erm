<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('prosentase',$row['prosentase'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["prosentase"], "prosentase", "%");
?>
<?php 
$from = UI::createTextBox('nama',$row['nama'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Keterangan");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>