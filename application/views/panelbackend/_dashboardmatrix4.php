  <h3 style="text-align: center; color: #fff; padding-bottom: 15px; padding-top: 30px">
    <?=strtoupper($rk['nama'])?>
    </h3>
<div class="row" style="margin:0px 3%">
<div class="col-sm-4">
  <h4 style="margin: 10px;text-align: center;">
    TOP <input type="text" style="margin:-1px 0px; border: none; background: #00000000; width: 25px;" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" name="top" id="top<?=$id_class?>" value="<?=$top?>"/> MATRIKS
    <?php if($id_scorecard){ ?>
    <div style="display: inline-block; position: relative; margin:-10px 0px;">
      <span class="label label-info">
        <?=$scorecardarr[$id_scorecard]?>
      </span>
    </div>
    <?php }else{ ?>
     DARI <?=$total['total_risiko']?> RISIKO
    <?php } ?>
  </h4>
    <hr style='border-top: 1px solid #ffffff00;    margin-bottom: 0px;'/>
<select id="id_scorecard<?=$id_class?>" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" style="width: 370px;margin: auto; border: none; background: #00000030; display: block;     padding: 4px;">
        <?php $scorecardarr[''] = 'semua '.strtolower($rk['nama']); foreach($scorecardarr as $k=>$v){ ?>
        <option value="<?=$k?>" <?=($id_scorecard==$k)?"selected":""?> style="color: #555; background: #fff"><?=$v?></option>
        <?php } ?>
        </select>
  <?php
  $rs_matrix = $this->data['mtriskmatrix'];
  $data = array(array());
  foreach($rs_matrix as $k => $v){
    $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
  }

  $rs = $this->data['rows'];
  $no=1;
  $top_inheren = array();
  $top_paska_kontrol = array();
  $top_paska_mitigasi = array();
  if($rs)
  foreach($rs as $r => $val){
    if($id_risiko_onlyone && $val['id_risiko']!=$id_risiko_onlyone){
      $no++;
      continue;
    }

    $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
    $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
    $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

    $no++;
  }

  include "_matrix2.php"; ?>

</div>
<div class="col-sm-8">
  <div class="row">
    <div class="col-sm-3">
  <h4 style="margin: 10px 0px;">PROSES BISNIS 
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -23px; padding: .5em; font-size: 90%;">
      <?=str_pad($total['total_risiko_probis'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
  </h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
  <div class="row" style="margin-top: 0px">
    <div class="col-sm-12">
    <?php //if($total['control_efektif_probis']){ ?>
      <div  style="padding-top: 0px; text-align: center; width: 100px; float: left">
        <div style="padding-bottom:5px; font-size: 12px; width: 80px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
        <input type="text" class="knob" value="<?=(float)$total['control_efektif_probis']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#eb4664" readonly>
      </div>
    <?php //} ?>
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-probis"><i class="material-icons" style="font-size: 16px">table_chart</i> <?=count($rows_probis)?> KATEGORI</button>
      <div style="clear: both;"></div>
  </div>
</div>
</div>
<div class="col-sm-9">

  <h4 style="margin: 10px 0px;">JASA & OM
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -23px; padding: .5em; font-size: 90%;">
      <?=str_pad($total['total_risiko_om'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div></h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
  <div class="row" style="margin-top: 0px">
    <div class="col-sm-4">
    <?php if($total['progress_mitigasi_om']){ ?>
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <div style="padding-bottom:5px; font-size: 12px;width: 80px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
        <input type="text" class="knob" value="<?=$total['progress_mitigasi_om']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
      </div>
    <?php } ?>
    <?php if($total['control_efektif_om']){ ?>
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <div style="padding-bottom:5px; font-size: 12px; width: 80px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
        <input type="text" class="knob" value="<?=$total['control_efektif_om']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#eb4664" readonly>
      </div>
    <?php } ?>
    </div>
    <div class="col-sm-7 no-padding no-margin">
          <div id="chartomdetail" style="height: 150px; color: #fff;     margin-top: -20px;"></div>

<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartomdetail", am4charts.XYChart3D);
chart.data = <?=json_encode($omrows)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "label";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.labels.template.fill = am4core.color("#fff");
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.labels.template.fill = am4core.color("#fff");
/*valueAxis.min = 0.05;
valueAxis.max = 0.8;*/
var series = chart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "value";
series.dataFields.categoryX = "label";
series.tooltip.label.textAlign = "middle";
series.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
series.columns.template.events.on("hit", function(ev){
  var item = ev.target.dataItem.dataContext;
  reqom(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 527, item.id_status_unit);
  $("#modal-om").modal("toggle");
});
</script>

    </div>
  </div>

</div>
</div>

 <div class="modal fade" id="modal-probis" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Efektifitas</th>
                </thead>
                <?php if($rows_probis)
                foreach($rows_probis as $r){ ?>
                  <tr>
                    <td><?=$r['nama']?></td>
                    <td></td>
                  </tr>
                <?php } ?>
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


  <BR/>
  
  <h4 style="margin: 10px 0px;">JASA PROYEK
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -23px; padding: .5em; font-size: 90%;">
      <?=str_pad($total['total_risiko_proyek'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div></h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
  <div class="row" style="margin-top: 0px">
    <?php if($total['progress_mitigasi_proyek']){ ?>
    <div class="col-sm-2">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px;width: 80px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
        <input type="text" class="knob" value="<?=$total['progress_mitigasi_proyek']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
      </div>
    </div>
    <?php } ?>
    <?php if($total['control_efektif_proyek']){ ?>
    <div class="col-sm-2">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px; width: 80px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
        <input type="text" class="knob" value="<?=$total['control_efektif_proyek']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#eb4664" readonly>
      </div>
    </div>
    <?php } ?>
    <div class="col-sm-8">
          <div id="chartprojectetail" style="height: 150px; color: #fff;     margin-top: -20px;"></div>

<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartprojectetail", am4charts.XYChart3D);
chart.data = <?=json_encode($projectrows)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "label";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.labels.template.fill = am4core.color("#fff");
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.labels.template.fill = am4core.color("#fff");
/*valueAxis.min = 0.05;
valueAxis.max = 0.8;*/
var series = chart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "value";
series.dataFields.categoryX = "label";
series.tooltip.label.textAlign = "middle";
series.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";

series.columns.template.events.on("hit", function(ev){
  var item = ev.target.dataItem.dataContext;
  reqproyek(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 528, item.id_status_proyek);
  $("#modal-proyek").modal("toggle");
});
</script>
    </div>
  </div>

  <div class="row">
    <marquee style='    margin-top: 30px;
    margin-left: 20px;
    margin-right: 20px;
    background: #00000042;
    padding: 15px 5px;'>
      <?php if($rows_project_run)
      foreach($rows_project_run as $r){ echo $r['nama'].',&nbsp;&nbsp;&nbsp; '; } ?>
    </marquee>
  </div>
</div>

    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 0 }, 500);' class="btn-navigsi" style="position: absolute; top:280px; right: 30px"><i class="material-icons">keyboard_arrow_up</i></a>
    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 1600 }, 500);' class="btn-navigsi" style="position: absolute; top:320px; right: 30px"><i class="material-icons">keyboard_arrow_down</i></a>
</div>

<!-- 
<div class="modal fade" id="tablematrix<?=$id_class?>">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
                <center>
                  <h4 class="modal-title" style="color: #333">TOP <?=$top?> <?=strtoupper($rk['nama'])?> <?php if($scorecard_name) { ?> <br/><small><?=trim(implode(" / ",$scorecard_name)," / ")?></small><?php } ?> </h4>
                </center>
          </div>
          <div class="modal-body">
            <div id="datarisiko<?=$id_kajian_risiko?>">

            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
    </div>
</div> -->

<script src="<?php echo base_url()?>assets/template/backend/js/pages/charts/jquery-knob.js"></script>