
<script src="<?php echo base_url()?>assets/template/backend/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/core.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/charts.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/animated.js"></script>
<script src="<?php echo base_url()?>assets/js/chart/dark.js"></script>
<script src="<?php echo base_url()?>assets/js/html2canvas.js"></script>
<script src="<?php echo base_url()?>assets/js/canvas2image.js"></script>
<div style="margin-right: -10px; margin-left: -10px">
  <?php
  $report = true;
  unset($mtjeniskajianrisikoarr['']);
  $color = array('1'=>'#2091f3','2'=>'#ff216e','3'=>'#ffd300');
  foreach ($rowskajianrisiko as $rk) { $id_class++; ?>
  <div class="col-sm-<?=12/count($rowskajianrisiko)?> no-margin" id="kajian<?=$id_class?>" style="min-height: 570px; color: #fff; padding: 0px 5px !important; background-color: <?=$color[$id_class]?>00">    
  </div>
    <script type="text/javascript">
      $(function(){
        req(<?=$id_class?>, <?=$rk['id_kajian_risiko']?>);
      })
    </script>
  <?php } ?>
</div>
<div style="clear: both;"></div>

 <div class="modal fade" id="modal-taksonomi" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="color:#333" id="taksonomibar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(function(){
    $.ajax({
      type:"post",
      url:"<?=current_url()?>",
      data:{act:'get_taksonomi'},
      success:function(ret){
        $('#taksonomibar').html(ret);
      }
    });
  })
</script>

 <div class="modal fade" id="maturylevel" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>MATURITY LEVEL</center></h2>
            </div>
            <div class="modal-body" style="color:#333">
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
                  <th style="text-align:center;background-color:#034485;color:#eee;font-size: 16px;">TAHUN</th>
                  <?php foreach ($matury_level as $r) { ?>
                  <th style="text-align:center;background-color:#034485;color:#eee;font-size: 16px;"  width="122px"><?=$r['tahun']?></th>
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
                  $level = round($r['realisasi']/$r['target']*100,2); 
                  if(!$r['realisasi']){ ?>
                  <td></td>
                  <?php }elseif($level>=102.5){
                  ?>
                  <td style="background: #127dd0; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }elseif($level>=100){ ?>
                  <td style="background: #58b051; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }elseif($level>=97.5){ ?>
                  <td style="background: #f9973a; color: #000;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }else{ ?>
                  <td style="background: #f14236; color: white;text-align: center;font-size: 16px;"><?=$level?>%</td>
                  <?php }
                } ?>
                </tr>
              </tbody>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="arsip" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>ANNUAL REPORT</center></h2>
            </div>
            <div class="modal-body" id="elarsip">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="modal-issue" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>ISSUE & PROGRAM STRATEGIS</center></h2>
            </div>
            <div class="modal-body" id="dataissue">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="modal-om" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>DAFTAR UNIT JASA O&M</center></h2>
            </div>
            <div class="modal-body" id="dataom">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="modal-proyek" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>DAFTAR PROYEK</center></h2>
            </div>
            <div class="modal-body" id="dataproyek">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
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
<div class="row">
  <div class="col-sm-4" style="padding: 0px 10px !important;">
    <div id="grafik_batang" style="width: 32vw;"></div>
  </div>
  <div class="col-sm-4" style="padding: 0px 10px !important;">
    <div id="grafik_pie" style="width: 32vw;"></div>
  </div>
  <div class="col-sm-4" style="padding: 0px 10px !important;">
    <div id="grafik_line" style="width: 32vw;"></div>
  </div>
</div>
<br>
<table style="width: 100%; background-color:white;">
  <tr>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Nama Program</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Pemrakarsa</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Status Dokumen</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Status GRC</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Jumlah Risiko</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Jumlah Mitigasi</td>
    <td style="text-align:center;background-color: black;color: white;padding:10px 0px">Rata-rata Progress Mitigrasi</td>
  </tr>

  <tr>
    <td style="text-align:center;padding:10px 0px">Nama Program</td>
    <td style="text-align:center;padding:10px 0px">Pemrakarsa</td>
    <td style="text-align:center;padding:10px 0px">Status Dokumen</td>
    <td style="text-align:center;padding:10px 0px">Status GRC</td>
    <td style="text-align:center;padding:10px 0px">Jumlah Risiko</td>
    <td style="text-align:center;padding:10px 0px">Jumlah Mitigasi</td>
    <td style="text-align:center;padding:10px 0px">Rata-rata Progress Mitigrasi</td>
  </tr>
</table>

<?php if((Access("edit_kesimpulan", "panelbackend/home") or $this->is_super_admin)){ ?>
 <div class="modal fade" id="kesimpulan">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content-kesimpulan">
        </div>
    </div>
</div>
<?php } ?>

<style>
.dark-tooltip + .tooltip > .tooltip-inner {
    background-color: #000c !important; 
    border: 1px solid #000c;
}#chartdiv1,#chartdiv2,#chartdiv3,#charttrend1,#charttrend2,#charttrend3 ,#charttrenddetail1,#charttrenddetail2,#charttrenddetail3 {
  width: 100%;
  height: 200px;
}
</style>
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

function req(id_class, id_kajian_risiko, tahun){
  if(tahun==undefined)
    tahun = $(".tahunefektif").val();

    $.ajax({
      type:"post",
      url:"<?=current_url()?>",
      beforeSend:function(){
        $('#kajian'+id_class).append(''
          +' <div style="position:absolute; min-height: 570px; width:100%; top:0px; left:0px; background-color: #ffffff00; vertical-align: middle;text-align: center; padding-top: 280px">'
          +'<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
          +'</div>');
      },
      data:{
        act:'get_kajian_risiko', 
        id_kajian_risiko:id_kajian_risiko, 
        id_class:id_class, 
        tahun:tahun,
        top:$("#top"+id_class).val(),
        id_scorecard:$("#id_scorecard"+id_class).val()
      },
      success:function(ret){
        $('#kajian'+id_class).html(ret);
      }
    });
}

function reset_kesimpulan(id_class, id_kajian_risiko, id_scorecard){
  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'reset_kesimpulan', 
      id_kajian_risiko:id_kajian_risiko, 
      id_class:id_class, 
      tahun:$(".tahunefektif").val(),
      top:$("#top"+id_class).val(),
      id_scorecard:id_scorecard
    },
    success:function(ret){
      reqtb(id_class, id_kajian_risiko, undefined, id_scorecard);
      // $("#kesimpulan"+id_class).modal('toggle');
    }
  });
}
function save_kesimpulan(id_class, id_kajian_risiko, id_scorecard){
  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'save_kesimpulan', 
      id_kajian_risiko:id_kajian_risiko, 
      id_class:id_class, 
      tahun:$(".tahunefektif").val(),
      keterangan:$("#kesimpulan #keterangan").val(),
      status:$("#kesimpulan #status").val(),
      top:$("#top"+id_class).val(),
      id_scorecard:id_scorecard
    },
    success:function(ret){
      reqtb(id_class, id_kajian_risiko, undefined, id_scorecard);
      $("#kesimpulan").modal('toggle');
    }
  });
}

function reqkesimpulan(id_class, id_kajian_risiko, id_scorecard, tahun){
  if(tahun==undefined)
    tahun = $(".tahunefektif").val();
  if(id_scorecard==undefined)
    id_scorecard = $("#id_scorecard"+id_class).val();

  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_kesimpulan', 
      id_kajian_risiko:id_kajian_risiko, 
      id_class:id_class, 
      tahun:tahun,
      id_scorecard:id_scorecard
    },
    success:function(ret){
      $('#content-kesimpulan').html(ret);
      $('#kesimpulan').modal("toggle");
    }
  });
}
function reqtb(id_class, id_kajian_risiko, tahun, id_scorecard){
  if(tahun==undefined)
    tahun = $(".tahunefektif").val();
  if(id_scorecard==undefined)
    id_scorecard = $("#id_scorecard"+id_class).val();

  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_table', 
      id_kajian_risiko:id_kajian_risiko, 
      id_class:id_class, 
      tahun:tahun,
      top:$("#top"+id_class).val(),
      id_scorecard:id_scorecard
    },
    success:function(ret){
      $('#datarisiko'+id_kajian_risiko).html(ret);
    }
  });
}
function reqom(id_class, id_kajian_risiko, tahun, id_scorecard, id_status_unit){
  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_om', 
      id_class:id_class,
      id_status_unit:id_status_unit,
      id_kajian_risiko:id_kajian_risiko,
      id_scorecard:id_scorecard,
      tahun:tahun,
    },
    success:function(ret){
      $('#dataom').html(ret);
    }
  });
}
function reqproyek(id_class, id_kajian_risiko, tahun, id_scorecard, id_status_proyek){
  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_proyek', 
      id_class:id_class,
      id_status_proyek:id_status_proyek,
      id_kajian_risiko:id_kajian_risiko,
      id_scorecard:id_scorecard,
      tahun:tahun,
    },
    success:function(ret){
      $('#dataproyek').html(ret);
    }
  });
}
function reqissue(tahun, jenis){
  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_issue', 
      jenis:jenis, 
      tahun:tahun,
    },
    success:function(ret){
      $('#dataissue').html(ret);
    }
  });
}

function maturylevel(){

  $('#maturylevel').modal('show');
}

function arsip(){

  $.ajax({
    type:"post",
    url:"<?=current_url()?>",
    data:{
      act:'get_arsip'
    },
    success:function(ret){
      $('#elarsip').html(ret);
    }
  });

  $('#arsip').modal('show');
}

function taksonomi(){
  $('#modal-taksonomi').modal('show');
}

var chart = AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "addClassNames": true,
  "theme": "light",
  "autoMargins": false,
  "marginLeft": 30,
  "marginRight": 8,
  "marginTop": 10,
  "marginBottom": 26,
    "depth3D": 10,
  "angle": 50,
 /* "balloon": {
    "adjustBorderColor": false,
    "horizontalPadding": 10,
    "verticalPadding": 8,
    "color": "#ffffff"
  },*/

  "dataProvider": <?=json_encode($matury_level)?>,
  "valueAxes": [ {
    "axisAlpha": 0,
    "position": "left",
  } ],
  "startDuration": 1,
  "graphs": [ {
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "alphaField": "alpha",
    "fillColors":['#1174c3','#2196f3'],
    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    "fillAlphas": 1,
        "lineAlpha": 0.1,
    "type": "column",
    "title": "Target",
    "valueField": "target",
    "dashLengthField": "dashLengthColumn"
  }, {
    "fontFamily":"'Lato', Arial, Tahoma, sans-serif",
    "alphaField": "alpha",
    "fillColors":['#d06e12','#f9973a'],
    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    "fillAlphas": 1,
        "lineAlpha": 0.1,
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
    "tickLength": 0,
     "gridThickness": 0
  },
  "export": {
    "enabled": false
  }
} );

</script>


<script src="<?php echo base_url()?>lib/highcharts/highcharts.js"></script>
<script src="highcharts/modules/exporting.js"></script>
<script src="highcharts/modules/export-data.js"></script>

<script type="text/javascript">

        Highcharts.chart('grafik_batang', {
            chart: {
                type: 'column',
                backgroundColor:'black',
            },
            title: {
                text: 'JUMLAH GRC',
                style: {
                    fontFamily: 'monospace',
                    color: "white"
                }
  
            },
            xAxis: {
                categories: [
                    '<span style="color:white">2016</span>',
                    '<span style="color:white">2017</span>',
                    '<span style="color:white">2018</span>',
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<span style="color:white">Jumlah</span>'
                },
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px;">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} Mhs Aktif</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 2,
                }
            },
            series: [{
                data: [515,540,610],
                style: {
                    fontFamily: 'monospace',
                    color: "white"
                }
            }]
        });

        Highcharts.chart('grafik_pie', {
          chart: {
            type: 'pie',
            backgroundColor:'black'
          },
          title: {
            text: 'JUMLAH GRC BERDASARKAN LEVEL',
            style: {
                    fontFamily: 'monospace',
                    color: "white"
                }
          },
          series: [{
            data: [{
              name: 'Chicken',
              y: 40
            }, {
              name: 'Vegetable',
              y: 30
            }, {
              name: 'Fish',
              y: 20
            }, {
              name: 'Steak',
              y: 5
            }, {
              name: 'Other',
              y: 5
            }]
          }]
        });

        var chart = new Highcharts.Chart({
          chart: {
            renderTo: 'grafik_line',
            backgroundColor:'black',
            marginBottom: 80
          },
          title: {
            text: 'TREND JUMLAH GRC',
            style: {
                    fontFamily: 'monospace',
                    color: "white"
                }
          },
          xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
              rotation: 90
            }
          },

          series: [{
            data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]        
          }]
        });
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
  .panah{
    background-image: -webkit-gradient(linear, 0% 50%, 100% 100%, from(#ffffff), to(#e6e6e6)) !important;
    font-size: 100px;
 /*   margin-left: -50px;
    margin-top: 320px;*/
    position: absolute;
    z-index: 10;
    text-shadow: 0px 5px 5px rgba(0,0,0,.2);
    color:transparent;
    -webkit-background-clip: text;
    background-clip: text;
  }
  .panah1{
    -webkit-animation: myrighta 4s;  /* Safari 4.0 - 8.0 */
    animation: myrighta 4s;
    top: 150px;
    right: 62%;
  }
  .panah2{
    -webkit-animation: myrightb 4s;  /* Safari 4.0 - 8.0 */
    animation: myrightb 4s;
    top: 150px;
    right: 300px;
    right: 28.7%;
  }
@media (max-width: 991px) {
    .panah{
      display: none;
    }
}


@-webkit-keyframes myrighta {
    0% {opacity:0;
      right: 67%;}
    75% {opacity:0;
      right: 67%;}
    85% {right: 62%;opacity:1;}
}

@keyframes myrighta {
    0% {opacity:0;
      right: 67%;}
    75% {opacity:0;
      right: 67%;}
    85% {right: 62%;opacity:1;}
}
@-webkit-keyframes myrightb {
    0% {right: 33.7%;opacity:0;}
    90% {right: 33.7%;opacity:0;}
    100% {right:28.7%;opacity:1;}
}

@keyframes myrightb {
    0% {right: 33.7%;opacity:0;}
    90% {right: 33.7%;opacity:0;}
    100% {right:28.7%;opacity:1;}
}

.btn-navigsi, .btn-navigsi:focus{
  color: #ffffff4d;
  border: 1px solid #ffffff4d;
      padding: 10px 10px 1px 10px;
}

.btn-navigsi:hover{
  color: #fff;
  border: 1px solid #fff;
}

  .modal .table td, .modal .table th{
    padding: 3px !important;
    font-size: 13px !important;
  }
hr{border-top: 1px solid #ffffff12;
      margin-top: 5px !important;
    margin: 10px -10px;
}

.box-dashboard{
  padding: 10px; 
  background: #0000009c;
    margin-bottom: 7px;
}
text > tspan{
  color: 'white;';
}
</style>