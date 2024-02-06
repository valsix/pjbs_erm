<div class="col-sm-12">

<?php 
$from = UI::createSelect('id_parent_direktorat',$mtdirektoratarr,$row['id_parent_direktorat'],($row['id_parent_direktorat']?false:$edited),'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_parent_direktorat"], "id_parent_direktorat", "Induk Direktorat", false, 2);
?>

<?php 
$from = UI::createTextBox('id_direktorat',$row['id_direktorat'],'20','10',($row['id_direktorat']?false:$edited),'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["id_direktorat"], "id_direktorat", "Kode", false, 2);
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
?>
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>