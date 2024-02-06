<div class="col-sm-6">


<?php
if($this->mode == "edit")
{
?>
<div class="form-group ">
	<label for="id_kemungkinan" class="col-sm-4 control-label">
		No. SK
	</label>
	<div class="col-sm-8"><span class='read_detail'><?=$row['sk_nomor']?></span>
	</div>
</div>
<?
}
else
{
	$from = UI::createSelect('sk_id',$skmatriks,$row['sk_id'],$edited,'form-control sk_id',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
	echo UI::createFormGroup($from, $rules["sk_id"], "sk_id", "No. SK");	
}
?>





<?php 
$from = UI::createSelect('id_kemungkinan',$combokemungkinan,$row['id_kemungkinan'],!$row['id_kemungkinan']&&$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kemungkinan"], "id_kemungkinan", "Kemungkinan");
?>

<?php 
$from = UI::createSelect('id_dampak',$combodampak,$row['id_dampak'],!$row['id_dampak']&&$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_dampak"], "id_dampak", "Dampak");
?>

<?php 
$from = UI::createSelect('id_tingkat',$combotingkat,$row['id_tingkat'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_tingkat"], "id_tingkat", "Tingkat Dampak");
?>

<?php 
$from = UI::createTextBox('css',$row['css'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["css"], "css", "Css");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>