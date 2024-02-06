<div class="container-fluid">

        <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

<div class="col-sm-3 no-padding">
<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$id_kajian_risiko,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>'KAJIAN RISIKO'
    ));
?>
</div>
<?php if(($scorecardarr)){ ?>
<div class="col-sm-3 no-padding">
<?php
$form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>'RISK PROFILE'
    ));
?>
</div>
<?php } ?>
<?php if(($scorecardchildarr)){ ?>
<div class="col-sm-6 no-padding">
<?php
$form = UI::createSelect('id_scorecard_child',$scorecardchildarr,$id_scorecard_child,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>$scorecardarr[$id_scorecard]
    ));
?>
</div>
<?php } ?>

                  <div style="clear: both;"></div>
                </div>

                <div class="body table-responsive" style="padding: 0px">

              <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
              <div class="alert alert-warning">
                  Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
              </div>
              <?php }?>

              <?=FlashMsg()?>

<?php if($id_scorecard_child){ ?>
  <table class="table table-hover table-bordered">
    <tbody>
        <?php foreach($rows as $r){ ?>
            <tr id="risiko<?=$r['id_risiko']?>">
                <script type="text/javascript">
                    $(function(){
                        load_detail(<?=$r['id_risiko']?>);
                    })
                </script>
            </tr>
        <?php } ?>
    </tbody>
  </table>
<?php } ?>

              <div style="clear: both;"></div>
              <div style="text-align: right; padding: 10px">
                <?php if(count($rows)){ ?>
                  <a data-toggle='modal' data-target='#closemodal' class='btn btn-success'><span class="glyphicon glyphicon-floppy-save"></span> SAVE ALL</a>
                <?php } ?>
                <br/>
              </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="closemodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Keterangan<span style="color:red">*</span></h4>
            </div>
            <div class="modal-body">
                <?=UI::createTextArea('status_keterangan',null,'','',true,'form-control status_keterangan'," placeholder='ketik disini untuk menambah keterangan'")?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-link waves-effect" onclick="goSubmitRequired('save_all','.status_keterangan')">SEND</button>
            </div>
        </div>
    </div>
</div>


 <div class="modal fade" id="modal-mitigasi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>MITIGASI</center></h2>
            </div>
            <div class="modal-body" id="datamitigasi">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script>
    function mitigasi(id_risiko){
        $.ajax({
        type:"post",
        url:"<?=current_url()?>",
        data:{
          act:'get_mitigasi', 
          id_risiko:id_risiko
        },
        success:function(ret){
          $("#datamitigasi").html(ret);
          $("#modal-mitigasi").modal("toggle");
        }
        });
    }

    function load_detail(id_risiko){
        $.ajax({
        type:"post",
        url:"<?=current_url()?>",
        data:{
          act:'get_detail', 
          id_risiko:id_risiko,
          status_risiko:$("#status_risiko_"+id_risiko).val(),
          tgl_risiko:$("#tgl_risiko_"+id_risiko).val(),
          progress_capaian_kinerja:$("#progress_capaian_kinerja_"+id_risiko).val(),
          hambatan_kendala:$("#hambatan_kendala_"+id_risiko).val(),
          penyesuaian_tindakan_mitigasi:$("#penyesuaian_tindakan_mitigasi_"+id_risiko).val(),
          residual_kemungkinan_evaluasi:$("#residual_kemungkinan_evaluasi_"+id_risiko).val(),
          residual_dampak_evaluasi:$("#residual_dampak_evaluasi_"+id_risiko).val(),
        },
        success:function(ret){
          $("#risiko"+id_risiko).html(ret);
            reload();
        }
        });
    }

    function reload(){
        $("select").select2();
        $(".datepicker").bootstrapMaterialDatePicker({
                    format: "DD-MM-YYYY",
                    clearButton: true,
                    weekStart: 1,
                    time: false
                });
        $(".datepickerstart").bootstrapMaterialDatePicker({
            format: "DD-MM-YYYY",
            clearButton: true,
            weekStart: 1,
            time: false,
            minDate : new Date()
        });
    }
</script>

<style type="text/css">
  .header .form-group{
    font-size: 16px;
    margin: 0px;
  }
  .header .form-group input{
    font-size: 16px;
  }
  .table-bordered > tbody > tr > td{
    padding: 5px;
    border: 1px solid rgb(204, 204, 204);
  }
  .tb > tbody > tr > td{
    vertical-align: top;
  }

  .form-line{
    display:inline;
  }
</style>