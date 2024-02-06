<div class="col-sm-12">

<?php 
$from = UI::createSelect('id_induk',$mtkriteriadampakarr,(int)$row['id_induk'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_induk"], "id_induk", "Induk", false, 2);
?>

<?php 
$from = UI::createTextBox('kode',$row['kode'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode", false, 2);
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
?>

<?php
unset($mtdampakrisikoarr['']);
foreach ($mtdampakrisikoarr as $key => $value) {
	//createTextArea($nameid,$value='',$rows='',$cols='',$edit=true,'form-control',$add='')
	$from = UI::createTextArea("keterangan[$key]",$row['keterangan'][$key],'','',$edited,'form-control ',"style='width:100%'");
	echo UI::createFormGroup($from, $rules["keterangan[$key]"], "keterangan[$key]", $value, false, 2);
}
?>
<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>