
        <div class="pull-left">
          <h5 style="padding-left: 10px">TREND TAKSONOMI TAHUN <?=$tahun?> <?=$nama_taksonomi?></h5>
        </div>
        <div class="pull-right">
          <?php if($id_taksonomi){ ?>
            <a href="javascript:void(0)" onclick="reqtrendtaksonomi(<?=$tahun?>)"><i class="material-icons">arrow_back</i></a>
          <?php }else{ ?>
            <a href="javascript:void(0)" onclick="reqtingkattahunan()"><i class="material-icons">arrow_back</i></a>
          <?php } ?>
        </div>
        <!-- Tab panes -->
        <div class="tab-content clearfix">
          <div id="charttrenddetail" style="height: 300px"></div>
        </div>
<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("charttrenddetail", am4charts.XYChart3D);
chart.data = <?=json_encode($trentaksonomi)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "label";
categoryAxis.renderer.grid.template.location = 0;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
/*valueAxis.min = 0.05;
valueAxis.max = 0.8;*/
var series = chart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "value";
series.dataFields.categoryX = "label";
series.tooltip.label.textAlign = "middle";
series.columns.template.tooltipText = "{categoryX}\n[bold]{valueY}[/]";
<?php if(!$id_taksonomi){ ?>
  series.columns.template.events.on("hit", function(ev){
    var item = ev.target.dataItem.dataContext;
    reqtrendtaksonomi(<?=$tahun?>, item.id_taksonomi_objective);
  });
<?php } ?>
</script>