<div class="col-sm-6">

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