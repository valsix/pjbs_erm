<div  class="header">
    <div class="pull-left">
    <h2>Informasi Data Pendukung
    </h2>

    </div>
    <div class="pull-right">
    <?php echo UI::showButtonMode($mode,$row[$pk])?>
    <?php 
    if($this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat'] && $row['is_lock']=='1'){
    ?>
    <button type="button" class="btn waves-effect btn-warning" onclick="goSubmitValue('unlock',<?=$row[$pk]?>)" ><span class="glyphicon glyphicon-lock"></span> Unlock</button>
    <?php
    }
    ?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">

<?php 
$from = UI::createTextEditor('keterangan',$row['keterangan'],'','',$edited,'form-control contents',"");
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "", true);
?>
				

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>
<script src="<?=site_url()?>assets/js/tinymce/tinymce.min.js"></script>
<script src="<?=site_url()?>assets/js/cms.js"></script>