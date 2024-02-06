<div class="col-sm-6">

<?php 
$from = UI::createTextBox('page',$row['page'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["page"], "page", "Page");
?>

<?php 
$from = UI::createTextBox('dari',$row['dari'],'10','10',$edited,'form-control ',"style='width:100px'");
echo UI::createFormGroup($from, $rules["dari"], "dari", "Dari");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createSelect('untuk',$mtpegawaiarr,$row['untuk'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["untuk"], "untuk", "untuk");
?>

<?php 
$from = UI::createTextBox('deskripsi',$row['deskripsi'],'20','20',$edited,'form-control ',"style='width:200px'");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>