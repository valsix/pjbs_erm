<div class="col-sm-6">

<?php 
$from = UI::createTextBox('keterangan',$row['keterangan'],'400','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>