<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>
				
<?php 
$from = UI::createSelect('id_struktur',$mtsdmstrukturarr,$row['id_struktur'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_struktur"], "id_struktur", "Struktur");
?>

<?php 
$from = UI::createSelect('id_unit',$mtsdmunitarr,$row['id_unit'],$edited,$class='form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit");
?>

<?php 
$from = UI::createTextBox('position_id',$row['position_id'],'20','20',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["position_id"], "position_id", "Kode ELLIPSE");
?>

</div>
<div class="col-sm-6">

<?php 
$from = UI::createCheckBox('is_pimpinan',1,$row['is_pimpinan'], "Pimpinan",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_pimpinan"], "is_pimpinan");


$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif");
?>
                

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>