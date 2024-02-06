<div class="row" style="margin-bottom: -10px">
    <div class="col-sm-5 no-padding no-margin" style="border-right: 1px solid #cccccc;">
        <div class="pull-left">
          <h5 style="padding-left: 10px">GRAFIK TAKSONOMI</h5>
        </div>
        <div class="pull-right">
        </div>
        <!-- Tab panes -->
        <div class="clearfix">
          <div id="pietaksonomi" style="height: 300px"></div>
        </div>
    </div>

    <div class="col-sm-7 no-padding no-margin">
      <div style="min-height: 338px" id="datatrend">
      </div>
    </div>
 </div>
<script>

am4core.options.commercialLicense = true;
// Create chart instance
var chart = am4core.create("pietaksonomi", am4charts.PieChart3D);
chart.data = <?=json_encode($totalrisiko)?>;
var pieSeries = chart.series.push(new am4charts.PieSeries3D());
pieSeries.dataFields.value = "value";
pieSeries.dataFields.category = "category";
pieSeries.ticks.template.disabled = true;
pieSeries.labels.template.disabled = true;
chart.legend = new am4charts.Legend();
chart.legend.position = "right";
chart.legend.labels.template.maxWidth = 100;
chart.legend.labels.template.truncate = true;
chart.legend.itemContainers.template.tooltipText = "{category}";

$(function(){
  reqtingkattahunan();
});

function reqtingkattahunan(){
    $.ajax({
      type:"post",
      url:"<?=current_url()?>",
      data:{
        act:'get_tingkattahunan'
      },
      success:function(ret){
        $('#datatrend').html(ret);
      }
    });
}

function reqtrendtaksonomi(tahun, id_taksonomi){
    $.ajax({
      type:"post",
      url:"<?=current_url()?>",
      data:{
        act:'get_trendtaksonomi',
        tahun:tahun,
        id_taksonomi:id_taksonomi
      },
      success:function(ret){
        $('#datatrend').html(ret);
      }
    });
}
</script>