<div class="col-sm-6">

<?php 
$from = UI::createTextNumber('tahun',$row['tahun'],'4','4',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='9999' step='1'");
echo UI::createFormGroup($from, $rules["tahun"], "tahun", "Tahun");
?>

<?php 
$from = UI::createTextBox('revenue',($edited?$row['revenue']:rupiah($row['revenue'])),'10','10',$edited,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["revenue"], "revenue", "Revenue");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>