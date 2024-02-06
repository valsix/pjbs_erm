<div class="col-sm-6">

<?php 
/*$from = UI::createTextBox('kode',$row['kode'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");*/
?>

<?php 
$from = UI::createTextArea('nama',$row['nama'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>