<div class="col-sm-6">

<?php 
$from = UI::createTextBox('table_code',$row['table_code'],'50','50',($edited && !$idpk),$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["table_code"], "table_code", "Table Code");
?>

<?php 
$from = UI::createTextBox('table_desc',$row['table_desc'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["table_desc"], "table_desc", "Table Desc");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>