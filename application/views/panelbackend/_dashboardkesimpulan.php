
            <div class="modal-header">
                <h4 class="modal-title">Kesimpulan</h4>
            </div>
            <div class="modal-body">

<?php
$from = UI::createSelect('status',$statusarr,$kesimpulan['status'],true,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["status"], "status", "Status", true);
$from = UI::createTextArea('keterangan',$kesimpulan['keterangan'],'','',true,'form-control ');
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", true);
?>
            </div>
            <div class="modal-footer">
                <button type="button" data-target="#kesimpulan<?=$id_class?>" class="btn btn-link waves-effect" data-toggle="modal">CLOSE</button>
                <button type="button" class="btn waves-effect btn-success" onclick="save_kesimpulan(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$id_scorecard?>)"><span class="glyphicon glyphicon-floppy-save"></span> SAVE</button>
            </div>