<?php
$report = true;
unset($mtjeniskajianrisikoarr['']);
foreach ($mtjeniskajianrisikoarr as $key => $value) {
?>
<div style="width:<?=(float)(100/count($mtjeniskajianrisikoarr))?>%;float:left;" <?php if($id_kajian_risiko==$key){?>class="info-box-flat-active"<?php }else{?>class="info-box-flat"<?php } ?>>
  <div class="content">
      <div class="text"><a href="<?=site_url('panelbackend/home/index/'.$key)?>"><?=trim(str_replace(array("KAJIAN","RISIKO"), "", strtoupper($value)))?></a></div>
  </div>
</div>
<?php } ?>
<div style="clear: both;"></div>
<br/>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
    <div class="card">
        <div class="body">
            <div class="table-responsive">

<div class="row">
<div style="color: #fff;
background: #034485;
position: relative;
padding: 5px;
min-height: 110px;">
<div style="position: absolute;
left: 0;
width: 0;
height: 0;
border-style: solid;
border-width: 109px 510px 0 0;
border-color: white transparent transparent transparent;
top: 0;"></div>
<center>
<h3>Visi :</h3>
<h5 style="max-width: 500px">"<?=$visi?>"</h5>
</center>
<div style="position: absolute;
width: 0;
height: 0;
border-style: solid;
border-width: 0 510px 109px 0;
border-color: transparent white transparent transparent;
right: 0;
top: 0;"></div>
</div>

<div style="clear: both;"></div>
<br/>
<?php
if(file_exists(APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php")){
  include APPPATH."/views/panelbackend/_strategimap".$id_strategi_map.".php";
}else{
  include APPPATH."/views/panelbackend/_strategimap.php";
}
?>
  </div>
      </div>
  </div>
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
</div>
</div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="header">
            <center><H1 style="margin: 0px">MATRIKS RISIKO</H1></center>
            </div>
            <div class="body">
              <div style="max-width: 550px;margin: 0px auto;font-size: 16px;">
              <?php

              if ($id_kajian_risiko == 4 || $id_kajian_risiko == 5) {
                $form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
                echo UI::FormGroup(array(
                  'form'=>$form,
                  'sm_label'=>2,
                  'label'=>'Scorecard'
                  ));
              }?>
              </div>
              <?php
              $is_css = false;
              include"laporanriskprofileprint.php";
              ?>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="text-align: center">
                <h2 style="font-size: 14px">INHEREN RISK</h2>
            </div>
              <div class="body">
                  <div id="donut_chart1" class="dashboard-donut-chart"></div>
              </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="text-align: center">
                <h2 style="font-size: 14px">CURRENT RISK</h2>
            </div>
            <div class="body">
                <div id="donut_chart2" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="text-align: center">
                <h2 style="font-size: 14px">RESIDUAL RISK</h2>
            </div>
            <div class="body">
                <div id="donut_chart3" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
</div>

<?php 
if(($pengumumanarr)){ ?>
 <div class="modal fade" id="pengumuman" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="pengumumanLabel">Pengumuman</h4>
            </div>
            <div class="modal-body" style="color:#333">
            <?php foreach($pengumumanarr as $rmsg){ 
              echo nl2br($rmsg['msg']); ?>
            <br/>
            <a style="font-size: 11px" href="<?=site_url('panelbackend/home/msg/'.$rmsg['id_msg'])?>">Jangan Tampilkan Lagi</a>
            <br/>
            <br/>
            <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(function(){
    $('#pengumuman').modal('show');
  })
</script>
<?php } ?>

<!-- Morris Plugin Js -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/morrisjs/morris.js"></script>

<!-- ChartJs -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/chartjs/Chart.bundle.js"></script>

<!-- Flot Charts Plugin Js -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.resize.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.pie.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.categories.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.time.js"></script>

<script>

function callRisiko(id_sasaran_strategis) {
  $.ajax({
    dataType: 'html',
    url:"<?=base_url("panelbackend/ajax/risikosasaran/$id_kajian_risiko")?>/"+id_sasaran_strategis,
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

<?php

$temp_warna = array();
foreach($rs_matrix as $r) {
  $temp_warna[$r['nama']] = $r['warna'];
}

$total_inheren = $total['total_inheren'];
$inheren = $total['inheren'];
$total_control = $total['total_control'];
$control = $total['control'];
$total_residual = $total['total_residual'];
$residual = $total['residual'];
?>
Morris.Donut({
    element: 'donut_chart1',
    <?php if ($total_inheren === null || $total_inheren === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
        <?php foreach($inheren as $label => $count) {?>{
          label: "<?php echo $label;?>",
          value: <?php echo round(($count/$total_inheren)*100,1);?> },
      <?php } ?>
    ],
    colors: [
        <?php foreach($inheren as $label => $count) {
            echo "'".$temp_warna[$label]."',";
          } ?>
        ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});

Morris.Donut({
    element: 'donut_chart2',
    <?php if ($total_control === null || $total_control === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
      <?php foreach($control as $label => $count) {?>{
        label: "<?php echo $label;?>",
        value: <?php echo round(($count/$total_control)*100,1);?> },
      <?php } ?>
    ],
    colors: [
      <?php foreach($control as $label => $count) {
          echo "'".$temp_warna[$label]."',";
        } ?>
      ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});

Morris.Donut({
    element: 'donut_chart3',
    <?php if ($total_residual === null || $total_residual === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
      <?php foreach($residual as $label => $count) {?>{
        label: "<?php echo $label;?>",
        value: <?php echo round(($count/$total_residual)*100,1);?> },
    <?php } ?>
    ],
    colors: [
      <?php foreach($residual as $label => $count) {
          echo "'".$temp_warna[$label]."',";
        } ?>
      ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});


</script>
