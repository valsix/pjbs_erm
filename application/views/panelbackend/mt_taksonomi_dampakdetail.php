<div class="col-sm-6">

<?php 
/*$from = UI::createTextBox('no',$row['no'],'10','10',$edited,'form-control rupiah ',"style='text-align:right; width:190px' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["no"], "no", "NO");*/
?>

<?php 
$from = UI::createTextArea('nama',$row['nama'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
			

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>