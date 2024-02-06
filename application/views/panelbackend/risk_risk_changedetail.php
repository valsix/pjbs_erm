<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_risiko',$riskrisikoarr,$row['id_risiko'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_risiko"], "id_risiko", "Risiko");
?>

<?php 
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php 
$from = UI::createTextNumber('inheren_dampak',$row['inheren_dampak'],'','',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='1' step='1'");
echo UI::createFormGroup($from, $rules["inheren_dampak"], "inheren_dampak", "Inheren Dampak");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextNumber('inheren_kemungkinan',$row['inheren_kemungkinan'],'','',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='1' step='1'");
echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", "Inheren Tingkat");
?>

<?php 
$from = UI::createTextNumber('current_dampak',$row['current_dampak'],'','',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='1' step='1'");
echo UI::createFormGroup($from, $rules["current_dampak"], "current_dampak", "Current Dampak");
?>

<?php 
$from = UI::createTextNumber('current_tingkat',$row['current_tingkat'],'','',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='1' step='1'");
echo UI::createFormGroup($from, $rules["current_tingkat"], "current_tingkat", "Current Tingkat");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>