<div class="col-sm-6">
<?php 
$from = UI::createSelect('id_taksonomi_area',$areaarr,$row['id_taksonomi_area'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_taksonomi_area"], "id_taksonomi_area", "Area");
?>

<?php 
$from = UI::createTextBox('kode',$row['kode'],'10','10',$edited,'form-control ',"style='width:100px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::createTextArea('nama',$row['nama'],'2','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
        
<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>