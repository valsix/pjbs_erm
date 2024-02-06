<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createSelect('id_nama_proses',$mtpbnamaprosesarr,$row['id_nama_proses'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_nama_proses"], "id_nama_proses", "Nama Proses");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('kode',$row['kode'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>