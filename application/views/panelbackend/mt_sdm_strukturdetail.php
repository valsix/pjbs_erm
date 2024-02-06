<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_struktur_parent',$mtsdmstrukturarr,$row['id_struktur_parent'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_struktur_parent"], "id_struktur_parent", "Induk Struktur");
?>

<?php 
$from = UI::createTextBox('kode',$row['kode'],'18','18',$edited,$class='form-control ',"style='width:180px'");
echo UI::createFormGroup($from, $rules["kode"], "kode", "Kode");
?>

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php
$form = UI::createSelectMultiple('id_struktur_history[]',$mtsdmstrukturarrhistory,$row['id_struktur_history'],$edited,$class='form-control select2',"style='width:100%'");
echo UI::createFormGroup($form, $rules["id_struktur_history[]"], "id_struktur_history[]", "Sturktur Sebelumnya");
?>

</div>
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
?>
				

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
?>

<?php 
$from = UI::createTextNumber('urutan',$row['urutan'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["urutan"], "urutan", "Urutan");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>