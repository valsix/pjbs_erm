<div class="col-sm-6">

<?php 
$from = UI::createTextBox('kode',$row['kode'],'5','5',$edited,'form-control ',"style='width:50px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

</div>
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
?>
				

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
?>
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>