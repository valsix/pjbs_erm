<?php
$report = true;
unset($mtjeniskajianrisikoarr['']);

function cekResiko($dampak){
  switch ($dampak) {
    case '2':
      return 'btn-lg btn-success';
      break;

    case '3':
      return 'btn-lg btn-info';
      break;

    case '4':
      return 'btn-lg btn-warning';
      break;

    case '5':
      return 'btn-lg btn-danger';
      break;
    default:
      return 'btn-lg btn-success';
      break;
  }
}

$id_box = 0;
foreach ($mtjeniskajianrisikoarr as $key => $value) { $id_class++;
?>
<div style="width:<?=(float)(100/count($mtjeniskajianrisikoarr))?>%;float:left; height: 60px; line-height: 40px;" <?php if($id_kajian_risiko==$key) { echo "class='info-box-flat-active"; } else { echo "class='info-box-flat"; } echo " box-id-".$id_class."'"; ?>>
  <div class="content">
      <div class="text">
        <a style="display: inline;" href="<?=site_url('panelbackend/home/index/'.$key)?>"><?=trim(str_replace(array("KAJIAN","RISIKO"), "", strtoupper($value)))?></a> 
        <div style="display: inline-block;">
          <?php if(($total['total_risiko_kajian'][$key])!=NULL): ?>
          <span class="label label-info" style="display: block;">
            <?=str_pad($total['total_risiko_kajian'][$key], 2, "0", STR_PAD_LEFT);?>
          </span>
          <?php endif; ?>
        </div>
      </div>
  </div>
</div>
<?php } ?>
<div style="clear: both;"></div>

<br/>

<style>
.dark-tooltip + .tooltip > .tooltip-inner {
    background-color: #000c !important; 
    border: 1px solid #000c;
}
</style>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header <?=$id_menu; ?> bg-active<?=$id_kris_active;?>" style="text-align: center">
                <h2 class="dark-tooltip"  data-toggle="tooltip" title="<?=$this->config->item("keterangan_inheren_risk")?>">INHEREN RISK
                    <!-- <small>Risiko yang melekat karena karateristik dan sifat lainya</small> -->
                </h2>
            </div>
              <div class="body">
                  <div id="donut_chart1" class="dashboard-donut-chart"></div>
              </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header <?=$id_menu; ?> bg-active<?=$id_kris_active;?>" style="text-align: center">
                <h2 class="dark-tooltip"  data-toggle="tooltip" title="<?=$this->config->item("keterangan_current_risk")?>">CURRENT RISK
                    <!-- <small>Risiko yang tertinggi setelah dilakukan penanganan/control dengan program telah ada sebelumnya</small> -->
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart2" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header <?=$id_menu; ?> bg-active<?=$id_kris_active;?>" style="text-align: center">
                <h2 class="dark-tooltip"  data-toggle="tooltip" title="<?=$this->config->item("keterangan_residual_risk")?>">TARGETED RESIDUAL RISK
                    <!-- <small>Risiko yang tertinggi setelah dilakukan tindakan mitigasi risiko</small> -->
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart3" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="header text-white bg-active<?=$id_kris_active;?>">
            <!-- <a class="button6" data-toggle="collapse" data-target="#filter">FILTER</a>
            <h1 style="margin: 0px; text-align: center; margin-top: -40px;">TOP RISIKO</h1> -->
            <center><H1 style="margin: 0px">TOP RISIKO</H1></center>
            </div>
            <div class="body">
              <?php
              $is_css = false;
              include"laporanriskprofileprint1.php";
              ?>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="header text-white bg-active<?=$id_kris_active;?>">
            <center><H1 style="margin: 0px">KESIMPULAN</H1></center>
            <?php if(Access("edit_kesimpulan", "panelbackend/home") or $this->is_super_admin){ ?>
            <ul class="header-dropdown m-r--5">
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                    <i class="material-icons">more_vert</i>
                </a>
                  <ul class="dropdown-menu pull-right">
                      <li><a href="javascript:void(0)" class=" waves-effect waves-block" onclick="goSubmit('reset_kesimpulan')"><span class="glyphicon glyphicon-refresh"></span> Reset Kesimpulan</a></li>
                      <?php if($kesimpulan['status']){ ?>
                        <li><a href="javascript:void(0)" class=" waves-effect waves-block" data-toggle="modal" data-target="#kesimpulan"><span class="glyphicon glyphicon-edit"></span> Edit Kesimpulan</a></li>
                      <?php } ?>
                  </ul>
              </li>
            </ul>
            <?php } ?>
            </div>
            <div class="body">
              <?php
              if($kesimpulan['status'] == 'bad'){ ?>
                <span class="label label-danger">BAD CONDITION</span>
                <br/>
                <?php
                if(!$kesimpulan['keterangan'])
                  $kesimpulan['keterangan'] = $this->config->item("bad_condition");

                echo $kesimpulan['keterangan'];
              }
              if($kesimpulan['status'] == 'good'){ ?>
                <span class="label label-success">GOOD CONDITION</span>
                <br/>
                <?php
                if(!$kesimpulan['keterangan'])
                  $kesimpulan['keterangan'] = $this->config->item("good_condition");

                echo $kesimpulan['keterangan'];
              }
              if($kesimpulan['status'] == 'default'){ ?>
                <span class="label label-default">DEFAULT</span>
                <br/>
                <?php
                if(!$kesimpulan['keterangan'])
                  $kesimpulan['keterangan'] = $this->config->item("default_condition");

                echo $kesimpulan['keterangan'];
              }
              ?>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
          <div class="header text-white bg-active<?=$id_kris_active;?>">
          <!-- <a class="button6" data-toggle="collapse" data-target="#filter">FILTER</a>
          <h1 style="margin: 0px; text-align: center; margin-top: -40px;">TOP RISIKO</h1> -->
          <center><h1 style="margin: 0px">Maturity Level ERM</h1></center>
          </div>
          <div class="body">

            <table class="table table-bordered" id="export" width="100%">
              <thead>
              <tr>
                <td style="border: none;">
                  <table style="width:auto;margin-top:10px">
                    <tbody>
                      <tr>
                        <td style="padding: 5px;width:50px;background-color:#2196f3;border:0px solid black;"></td>
                        <td style="padding: 5px;font-size: 12px">&nbsp;Target</td>
                      </tr>
                      <tr>
                        <td style="padding: 5px;width:50px;background-color:#f9973a;border:0px solid black;"></td>
                        <td style="padding: 5px;font-size: 12px">&nbsp;Realisasi</td>
                      </tr>
                          </tbody>
                  </table>
                </td>
                <td colspan="<?=count($matury_level)+1?>" style="border: none;">
                <div style="    margin-left: -50px; margin-right: -30px;">
                <div id="chartdiv"></div>
              </div>
                </td>
              </tr>
                <tr>
                  <th style="text-align:center;background-color:#034485;color:#eee;font-size: 16px;" width="300px">TAHUN</th>
                  <?php foreach ($matury_level as $r) { ?>
                  <th style="text-align:center;background-color:#034485;color:#eee;font-size: 16px;"><?=$r['tahun']?></th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="font-size: 16px;">Target</td>
                  <?php foreach ($matury_level as $r) { ?>
                  <td style="text-align: center;font-size: 16px;"><?=$r['target']?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <td style="font-size: 16px;">Realisasi</td>
                  <?php foreach ($matury_level as $r) { ?>
                  <td style="text-align: center;font-size: 16px;"><?=$r['realisasi']?></td>
                  <?php } ?>
                </tr>
                <tr>
                  <td style="font-size: 16px;">Maturity Level ERM</td>
                  <?php foreach ($matury_level as $r) { 
                  $level = ($r['realisasi']>$r['target']?100:round($r['realisasi']/$r['target']*100,2)); 
                  if(!$r['realisasi']){ ?>
                  <td></td>
                  <?php }elseif($level>=102.5){
                  ?>
                  <td style="background: #f9973a; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }elseif($level>=100){ ?>
                  <td style="background: #58b051; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }elseif($level>=97.5){ ?>
                  <td style="background: #ffe94d; color: #000;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }else{ ?>
                  <td style="background: #f14236; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }
                } ?>
                </tr>
              </tbody>
            </table>
            <div style="clear: both;"></div>
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

<?php if((Access("edit_kesimpulan", "panelbackend/home") or $this->is_super_admin) && $kesimpulan['status']){ ?>
 <div class="modal fade" id="kesimpulan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kesimpulan</h4>
            </div>
            <div class="modal-body">

<?php
$from = UI::createSelect('status',array('default'=>'Default','good'=>'Good Condition', 'bad'=>'Bad Condition'),$kesimpulan['status'],true,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["status"], "status", "Status", true);
$from = UI::createTextArea('keterangan',$kesimpulan['keterangan'],'','',true,'form-control ');
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", true);
?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn waves-effect btn-success" onclick="goSubmit('save_kesimpulan')"><span class="glyphicon glyphicon-floppy-save"></span> SAVE</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="<?php echo base_url()?>assets/js/chart/amcharts.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/pie.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/serial.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/export.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/export.css" type="text/css" media="all" />
<script src="<?php echo base_url()?>assets/js/chart/light.js"></script>
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

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

// function getColor(tingkat) {
//     switch(tingkat) {
//       case 'Rendah':
//           return '#4CAF50'
//           break;
//       case 'Tinggi':
//           return '#FFe821'
//           break;
//       default:
//           return '#CF000F'
//   }
// };

<?php

$temp_warna = array();
foreach($rs_matrix as $r) {
  $temp_warna[$r['nama']] = $r['warna'];
}

echo "function getColor(tingkat) {
    switch(tingkat) {";
foreach ($temp_warna as $key => $value) {
  echo "case '$key':return '$value'; break; ";
}
echo "} };";

$total_inheren = $total['total_inheren'];
$inheren = $total['inheren'];
$total_control = $total['total_control'];
$control = $total['control'];
$total_residual = $total['total_residual'];
$residual = $total['residual'];
?>


var chart = AmCharts.makeChart( "donut_chart1", 
  {
    "type": "pie",
    "theme": "light",
    "labelRadius": -50,
    <?php if ($total_inheren === null || $total_inheren === 0 ) {?>
    dataProvider: [{
        asset: "Tidak Ada Risiko",
        value: 0
    }],
    <?php } else {?>
    dataProvider: [
        <?php foreach($inheren as $label => $count) {?>{
          asset: "<?php echo $label;?>",
          value: <?php echo round(($count/$total_inheren)*100,1);?> ,
          color: getColor('<?php echo $label;?>')
          },
      <?php } ?>
    ],
    <?php } ?>

    "valueField": "value",
    "titleField": "asset",
    "colorField": "color",
    "outlineAlpha": 0.4,
    "depth3D": 15,
    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
    "angle": 30,
    "export": {
      "enabled": false
    } ,

    // atrribut to make wide
    autoMargins: false,
    marginTop: 0,
    marginBottom: 0,
    marginLeft: 20,
    marginRight: 20,
    pullOutRadius: 0
  }
);

var chart = AmCharts.makeChart( "donut_chart2", 
  {
    "type": "pie",
    "theme": "light",
    "labelRadius": -50,
    <?php if ($total_control === null || $total_control === 0 ) {?>
    dataProvider: [{
        asset: "Tidak Ada Risiko",
        value: 0
    }],
    <?php } else {?>
    dataProvider: [
        <?php foreach($control as $label => $count) {?>{
          asset: "<?php echo $label;?>",
          value: <?php echo round(($count/$total_control)*100,1);?>  , 
          color: getColor('<?php echo $label;?>')
          },
      <?php } ?>

    ],
    <?php } ?>

    "valueField": "value",
    "titleField": "asset",
    "colorField": "color",
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "outlineAlpha": 0.4,
    "depth3D": 15,
    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
    "angle": 30,
    "export": {
      "enabled": false
    } , 

    // atrribut to make wide
    autoMargins: true,
    marginTop: 0,
    marginBottom: 0,
    marginLeft: 20,
    marginRight: 20,
    pullOutRadius: 0
  }
);

var chart = AmCharts.makeChart( "donut_chart3", 
  {
    "type": "pie",
    "theme": "light",
    "labelRadius": -50,
    <?php if ($total_residual === null || $total_residual === 0 ) {?>
    dataProvider: [{
        asset: "Tidak Ada Risiko",
        value: 0
    }],
    <?php } else {?>
    dataProvider: [
        <?php foreach($residual as $label => $count) {?>{
          asset: "<?php echo $label;?>",
          value: <?php echo round(($count/$total_residual)*100,1);?>  ,
          color: getColor('<?php echo $label;?>')
        },

      <?php } ?>
    ],
    <?php } ?>

    "valueField": "value",
    "titleField": "asset",
    "colorField": "color",
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "outlineAlpha": 0.4,
    "depth3D": 15,
    "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
    "angle": 30,
    "export": {
      "enabled": false
    } , 

    // atrribut to make wide
    autoMargins: false,
    marginTop: 0,
    marginBottom: 0,
    marginLeft: 20,
    marginRight: 20,
    pullOutRadius: 0
  }
);


var chart = AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "addClassNames": true,
  "theme": "light",
  "autoMargins": false,
  "marginLeft": 30,
  "marginRight": 8,
  "marginTop": 10,
  "marginBottom": 26,
    "depth3D": 20,
  "angle": 30,
 /* "balloon": {
    "adjustBorderColor": false,
    "horizontalPadding": 10,
    "verticalPadding": 8,
    "color": "#ffffff"
  },*/

  "dataProvider": <?=json_encode($matury_level)?>,
  "valueAxes": [ {
    "axisAlpha": 0,
    "position": "left"
  } ],
  "startDuration": 1,
  "graphs": [ {
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "alphaField": "alpha",
    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    "fillAlphas": 1,
    "type": "column",
    "title": "Target",
    "valueField": "target",
    "dashLengthField": "dashLengthColumn"
  }, {
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "alphaField": "alpha",
    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    "fillAlphas": 1,
    "type": "column",
    "title": "Realisasi",
    "valueField": "realisasi",
    "dashLengthField": "dashLengthColumn"
  } ],
  "categoryField": "tahun",
  "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "tickLength": 0
  },
  "export": {
    "enabled": false
  }
} );

</script>
<style type="text/css">
#chartdiv {
  width: 100%;
  height: 300px;
  margin-bottom: -20px;
}                       
  .info-jumlah{    background: #f9f9f9;
    color: #aaa;
    border-top: 1px solid #e9e9e9;
    text-align: center;
    padding: 5px;
    font-weight: bold;
  }
  .info-jumlah-active{    background: #2296f3;
    color: #fff;
    border-top: 1px solid #2496f3;
    text-align: center;
    padding: 5px;
    font-weight: bold;
  }

  #chartdiv .amcharts-category-axis{
    display: none;
  }
</style>
