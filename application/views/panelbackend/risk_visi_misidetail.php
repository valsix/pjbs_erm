        <div class="container-fluid">
          <div class="block-header" style="margin:0px; margin-bottom: 15px; ">
<?php if(!$edited){ ?>
<div style="color: #fff;
    background: #1f8fffad;
    position: relative;
    padding: 5px;
    width: 90%;
    float: right;
    border-radius: 200px 200px 0px 0px;
    min-height: 110px;">
<center>
<h3>Visi :</h3>
<h5 style="max-width: 500px">"<?=$row['visi']?>"</h5>
</center>
</div>

<div style="clear: both;"></div>

<?php
if(file_exists(APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php")){
  include APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php";
}else{
  include APPPATH."/views/panelbackend/_strategimap.php";
}
?>
  <!-- modal untuk nama risiko berdasarkan sasaran strategi -->
  <div class="modal fade" id="risikostrategis" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="risikostrategislabel">Daftar Risiko</h4>
            </div>
            <div class="modal-body">
              <div id="datarisikostrategis">

              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
          </div>
      </div>
  </div>
<script type="text/javascript">
  $(function(){
    function callRisiko(id_sasaran_strategis, id_kajian_risiko) {
      if(id_kajian_risiko==undefined)
        id_kajian_risiko = 0;
      
  $.ajax({
    dataType: 'html',
    url:"<?=base_url("panelbackend/ajax/risikosasaran")?>/"+id_sasaran_strategis+'/'+id_kajian_risiko,
    success:function(response) {
      $('#datarisikostrategis').html(response);
    }
  })
}

$(function(){
  $('*[data-target="#risikostrategis"]').click(function(){
    var id = $(this).attr('id');
    callRisiko(id);
  });
});
});
</script>
<?php } ?>


          </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php 

                        if(strtotime(date('d-m-Y'))<=strtotime(($row['tgl_akhir_efektif']?$row['tgl_akhir_efektif']:date('d-m-Y')))){
                          $mn = UI::showButtonMode($mode, $row[$pk]);

                        }
                        if($mn or $str_left){
                        ?>
                          <div class="header">
                            <div class="pull-left">
                              <?=$str_left?>
                            </div>
                            <div class="pull-right">
                              <?=$mn?>
                            </div>
                            <div style="clear: both;"></div>
                          </div>
                        <?php } ?>
                        <div class="body table-responsive" style="<?=($mode=='index')?'padding:0px':''?>">

                        <?php  if($_SESSION[SESSION_APP]['loginas']){ ?>
                        <div class="alert alert-warning" style="
              position: fixed;
              top: 50px;
              text-align: center;
              padding: 3px;
              z-index: 100;
              width: 100%;
              margin: 0px -15px;
              background: #ff960094 !important;">
                            Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                        </div>
                        <?php }?>

                      <?=FlashMsg()?>

<div class="col-sm-12">

<?php
if($edited){
  $from = UI::createTextArea('visi',$row['visi'],'','',$edited,'form-control',"style='height:110px'");
  echo UI::createFormGroup($from, $rules["visi"], "visi", "Visi", false, 2);
}
?>

<?php
$from = UI::createTextArea('misi',$row['misi'],'','',$edited,'form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["misi"], "misi", "Misi", false, 2);
?>
<hr/>

<?php
$from = UI::createTextArea('konteks_internal',$row['konteks_internal'],'','',$edited,'form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["konteks_internal"], "konteks_internal", "Konteks Internal", false, 2);
?>

<?php
$from = UI::createTextArea('konteks_eksternal',$row['konteks_eksternal'],'','',$edited,'form-control',"style='height:110px'");
echo UI::createFormGroup($from, $rules["konteks_eksternal"], "konteks_eksternal", "Konteks Eksternal", false, 2);
?>
<hr/>

<?php
$from = UI::createTextArea('strength',$row['strength'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["strength"], "strength", "Strength", false, 2);
?>

<?php
$from = UI::createTextArea('weakness',$row['weakness'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["weakness"], "weakness", "Weakness", false, 2);
?>

<?php
$from = UI::createTextArea('opportunity',$row['opportunity'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["opportunity"], "opportunity", "Opportunity", false, 2);
?>

<?php
$from = UI::createTextArea('threat',$row['threat'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["threat"], "threat", "Threat", false, 2);
?>
<?php 

if($view_all_direktorat && $edited){
$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',($view_all_direktorat && $edited),'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2, ($view_all_direktorat && $edited));
?>
                

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',($view_all_direktorat && $edited),'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2, ($view_all_direktorat && $edited));
}
?>

<?php /*
$from = UI::createSelect('unit',$mtunitarr,$row['unit'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["unit"], "unit", "Unit", false, 2);
*/?>

<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>

                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-bottom: 6px !important;
    max-width: none !important;
}
</style>