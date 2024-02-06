
    <div class="col-sm-4 no-padding no-margin">
      <div class="card" style="margin-bottom: 0px; margin-top:-3px; padding:5px">
        <div class="pull-left">
          <h5>GRAFIK TAKSONOMI</h5>
        </div>
        <div class="pull-right">
        </div>
        <!-- Tab panes -->
        <div class="tab-content clearfix">
          <div id="pietaksonomi" style="height: 200px"></div>
        </div>
      </div>
    </div>

    <div class="col-sm-8 no-padding no-margin">
      <div class="card" style="margin-bottom: 0px; margin-top:-3px; padding:5px; min-height: 238px" id="datatrend">
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