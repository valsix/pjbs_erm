<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextArea('deskripsi_kualitatif',$row['deskripsi_kualitatif'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi_kualitatif"], "deskripsi_kualitatif", "Deskripsi Kualitatif");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('probabilitas',$row['probabilitas'],'50','50',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["probabilitas"], "probabilitas", "Probabilitas");
?>

<?php 
$from = UI::createTextArea('insiden_sebelumnya',$row['insiden_sebelumnya'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["insiden_sebelumnya"], "insiden_sebelumnya", "Insiden Sebelumnya");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>