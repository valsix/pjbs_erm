
<div class="col-sm-6">
<?php 
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 4);
?>
</div>

<div class="col-sm-6">

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 4);
?>
</div>
<div class="col-sm-12">

<?php 
echo UI::createTextEditor('strategi_map',$row['strategi_map'],'45','100',$edited,'form-control contents',"");
?>        

<?php 
$from = "<br/>".UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>

<script src="<?php echo base_url()?>assets/js/tinymce/tinymce.min.js"></script>
<script src="<?php echo base_url()?>assets/js/cms.js"></script>