<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php
$from = UI::createCheckBox('need_lampiran',1,$row['need_lampiran'],"Lampiran",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["need_lampiran"], "need_lampiran");
?>

<?php
$from = UI::createCheckBox('need_explanation',1,$row['need_explanation'],"Penjelasan",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["need_explanation"], "need_explanation");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>