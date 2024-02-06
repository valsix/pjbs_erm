
        <div class="pull-left">
          <h5 style="padding-left: 10px">TINGKAT RISIKO SETIAP TAHUN</h5>
        </div>
        <div class="pull-right">
        </div>
        <!-- Tab panes -->
        <div class="tab-content clearfix">
          <div id="charttrend"  style="height: 300px;"></div>
        </div>

<script type="text/javascript">
  
am4core.useTheme(am4themes_animated);
var chart = am4core.create("charttrend", am4charts.XYChart);
chart.data = <?=json_encode($tingkattahunan)?>;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "data";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
categoryAxis.renderer.grid.template.location = 0.5;
categoryAxis.startLocation = 0.3;
categoryAxis.endLocation = 0.7;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
/*valueAxis.min = 0.05;
valueAxis.max = 0.8;*/
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "value";
series.dataFields.categoryX = "data";
series.tooltipText = "{value}";
series.strokeWidth = 2;
series.minBulletDistance = 15;
// Drop-shaped tooltips
series.tooltip.background.cornerRadius = 10;
series.tooltip.background.strokeOpacity = 0;
series.tooltip.pointerOrientation = "vertical";
series.tooltip.label.minWidth = 40;
series.tooltip.label.minHeight = 40;
series.tooltip.label.textAlign = "middle";
series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
var bullet = series.bullets.push(new am4charts.CircleBullet());
bullet.circle.strokeWidth = 2;
bullet.circle.radius = 4;
bullet.circle.fill = am4core.color("#fff");
bullet.events.on("hit", function(ev){
  var item = ev.target.dataItem.dataContext;
  reqtrendtaksonomi(item.data);
});

var bullethover = bullet.states.create("hover");
bullethover.properties.scale = 1.3;

// Make a panning cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "panXY";
chart.cursor.xAxis = categoryAxis;
chart.cursor.snapToSeries = series;
</script>