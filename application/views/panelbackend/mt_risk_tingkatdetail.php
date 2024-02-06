<div class="col-sm-6">

<?php
$from = UI::createSelect('sk_id',$skmatriks,$row['sk_id'],$edited,'form-control sk_id',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["sk_id"], "sk_id", "No. SK");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextBox('warna',$row['warna'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["warna"], "warna", "Warna");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('penanganan',$row['penanganan'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["penanganan"], "penanganan", "Penanganan");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>