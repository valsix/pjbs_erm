<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_issue',$mtissuearr,$row['id_issue'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_issue"], "id_issue", "Issue");
?>

<?php 
$from = UI::createTextArea('nama',$row['nama'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createSelect('jenis',array('rjpp'=>'RJPP','rkap'=>'RKAP'),$row['jenis'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["jenis"], "jenis", "Jenis");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>