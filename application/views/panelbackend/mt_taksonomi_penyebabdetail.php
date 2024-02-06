<div class="col-sm-6">

<?php 
/*$from = UI::createTextNumber('no',$row['no'],'10','10',$edited,'form-control ',"style='text-align:left; width:190px' min='0' max='10000000000' step='1'");
echo UI::createFormGroup($from, $rules["no"], "no", "No.");*/
?>
<?php
$from = UI::createSelect('jenis',$jenisarr,$rowheader['jenis'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis");
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