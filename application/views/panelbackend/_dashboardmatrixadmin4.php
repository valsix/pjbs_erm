  <h3 style="
    text-align: center;
    margin: 0px;
    margin-bottom: 5px;
    padding: 10px;
    padding-top: 13px;
    background: #ff216ec4;
    color: #fff;
">
    <?=trim(str_replace("KAJIAN RISIKO","",strtoupper($rk['nama'])))?>
    </h3>
<div class="row" style="margin:0px">

<div class="box-dashboard">
  <h4 style="margin: 0px;">PROSES BISNIS 
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" >
      <?=str_pad($total['total_risiko_probis'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
  </h4>
<hr/>
  <div class="row">
    <div class="col-sm-12">
      <div  style="padding-top: 0px; text-align: center; width: 150px; float: left">
        <input type="text" class="knob" value="<?=(float)$total['control_efektif_probis']?>" data-width="70" data-height="70" data-thickness="0.25" data-fgColor="#97dc45" readonly>
        <div style="font-size: 10px; width: 150px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
      </div>
      <button style="font-size: 10px; padding:5px;margin: 20px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-probis"><i class="material-icons" style="font-size: 16px">table_chart</i> <?=count($rows_probis)?> KATEGORI</button>
      <div style="clear: both;"></div>
    </div>
  </div>
</div>

<div class="box-dashboard">
  <h4 style="margin: 0px;">JASA O&M 
    <div style="display: inline-block; position: relative;">
      <span class="label-total label label-info" >
        <?=str_pad($total['total_risiko_om'],2,'0',STR_PAD_LEFT)?>
      </span>
    </div>
    <div style="float:right;margin-top: -3px;"><small>
      <a href="javascript:void(0)" onclick="reqom(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 527); $('#modal-om').modal('toggle');" style="color: #fff; text-decoration: none;"><b><?=$total_om?> Unit OM</b></a></small>
    </div>
  </h4>
<hr/>
  <div class="row">
    <div class="col-sm-5" style="padding-right: 0px;">
    <?php if($total['progress_mitigasi_om']){ ?>
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <input type="text" class="knob" value="<?=$total['progress_mitigasi_om']?>" data-width="50" data-height="50" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
        <div style="font-size: 10px;width: 80px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
      </div>
    <?php } ?>
    <?php if($total['control_efektif_om']){ ?>
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <input type="text" class="knob" value="<?=$total['control_efektif_om']?>" data-width="50" data-height="50" data-thickness="0.25" data-fgColor="#97dc45" readonly>
        <div style="font-size: 10px; width: 80px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
      </div>
    <?php } ?>
    </div>
    <div class="col-sm-7">
          <div id="chartomdetail" style="height: 150px; color: #fff;    margin-right: -20px;
    margin-top: -25px;
    margin-bottom: -25px;
    margin-left: -25px"></div>

<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartomdetail", am4charts.XYChart3D);
chart.data = <?=json_encode($omrows)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "label";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;
categoryAxis.renderer.labels.template.fill = am4core.color("#fff");
categoryAxis.renderer.labels.template.fontSize = 10;
categoryAxis.renderer.labels.template.rotation = 20;
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
                    <td><a href='<?=site_url("panelbackend/risk_scorecard/index/4/526/$r[id_kategori]")?>'><?=$r['nama']?></a></td>
                    <td><?=round($r['efektif'])?></td>
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



</div>
<div class="box-dashboard">
  <h4 style="margin: 0px;">JASA PROYEK
    <div style="display: inline-block; position: relative;">
      <span class="label-total label label-info" style="display: inline-block;">
        <?=str_pad($total['total_risiko_proyek'],2,'0',STR_PAD_LEFT)?>
      </span>
    </div>
    <div style="float:right;margin-top: -3px;"><small>
      <a href="javascript:void(0)" onclick="reqproyek(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 528); $('#modal-proyek').modal('toggle');" style="color: #fff; text-decoration: none;"><b><?=$total_proyek?> Proyek</b></a></small>
    </div>
  </h4>
<hr/>
  <div class="row">
    <div class="col-sm-5" style="padding-right: 0px;">
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <input type="text" class="knob" value="<?=(float)$total['progress_mitigasi_proyek']?>" data-width="50" data-height="50" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
        <div style="font-size: 10px;width: 80px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
      </div>
      <div  style="padding-top: 0px; text-align: center; width: 80px; float: left;">
        <input type="text" class="knob" value="<?=(float)$total['control_efektif_proyek']?>" data-width="50" data-height="50" data-thickness="0.25" data-fgColor="#97dc45" readonly>
        <div style="font-size: 10px; width: 80px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
      </div>
    </div>
    <div class="col-sm-7">
          <div id="chartprojectetail" style="height: 150px; color: #fff;    margin-right: -20px;
    margin-top: -25px;
    margin-bottom: -25px;
    margin-left: -25px"></div>

<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartprojectetail", am4charts.XYChart3D);
chart.data = <?=json_encode($projectrows)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "label";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;
categoryAxis.renderer.labels.template.fill = am4core.color("#fff");
categoryAxis.renderer.labels.template.fontSize = 10;
categoryAxis.renderer.labels.template.rotation = 20;
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


</div>
<div class="row">
  <marquee style='margin: 0px 15px;
    background: #0000009c;
    padding: 5px;'>
      <?php if($rows_project_run)
      foreach($rows_project_run as $r){ echo $r['nama'].',&nbsp;&nbsp;&nbsp; '; } ?>
    </marquee>
  </div>
</div>


<script src="<?php echo base_url()?>assets/template/backend/js/pages/charts/jquery-knob.js"></script>