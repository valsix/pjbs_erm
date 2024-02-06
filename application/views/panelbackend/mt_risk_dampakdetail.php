<div class="col-sm-6">


<?php
$from = UI::createSelect('sk_id',$skmatriks,$row['sk_id'],$edited,'form-control sk_id',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["sk_id"], "sk_id", "No. SK");
?>

<?php
$from = UI::createTextBox('nama',$row['nama'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Dampak Risiko");
?>

<?php
$from = UI::createTextBox('kode',$row['kode'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php
$from = UI::createTextNumber('rating',$row['rating'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["rating"], "rating", "Rating");
?>

<?php
$from = UI::createTextArea('keterangan',$row['keterangan'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan");
?>

</div>
<div class="col-sm-6">

<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
