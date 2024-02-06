<div class="col-sm-6">

<?php 
$from = UI::createTextBox('kode',$row['kode'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");

$from = UI::createTextBox('nama',$row['nama'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");

$from = UI::createSelect('status',$status,$row['status'],$edited,'form-control select2'," ");
echo UI::createFormGroup($from, $rules["status"], "status", "Status");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>