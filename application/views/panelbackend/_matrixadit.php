<div id="divmatrix" style="background-color: #fff; text-align: left; width:auto; max-width:505px; margin: auto;">

<?php
  if(!$top_inheren)
    $top_inheren = array();

  foreach ($top_inheren as $key => $value) {
    foreach ($value as $k => $v) {
      $top_inheren[$key][$k] = array_unique($v);
    }
  }
  if(!$top_paska_kontrol)
    $top_paska_kontrol = array();

  foreach ($top_paska_kontrol as $key => $value) {
    foreach ($value as $k => $v) {
      $top_paska_kontrol[$key][$k] = array_unique($v);
    }
  }
  if(!$top_residual_target)
    $top_residual_target = array();

  foreach ($top_residual_target as $key => $value) {
    foreach ($value as $k => $v) {
      $top_residual_target[$key][$k] = array_unique($v);
    }
  }

  $rs_matrix = $this->data['mtriskmatrix'];
  $data = array(array());
  foreach($rs_matrix as $k => $v){
    $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
  }

  $rs_dampak = $this->data['mtriskdampak'];
  $rs_kemungkinan = $this->data['mtriskkemungkinan'];
?>
    <table class="tbmatrix">
        <?php
        $tooltip1 = array();
        echo "<tr>";
          echo "<tr>";
          echo "<td rowspan='5' style='font-weight:bold;position:relative;width:16px'><div style='position:absolute;right: 13px;top: 170px;width: 0px;-ms-transform: rotate(-90deg);-webkit-transform: rotate(-90deg);transform: rotate(-90deg);height: 0px;'>TINGKAT&nbsp;KEMUNGKINAN</div></td>";
            foreach($rs_kemungkinan as $r_k => $val_k){
              echo "<td align='center' style='width: 16px;text-align:center;vertical-align:middle;font-weight:bold'>$val_k[nama]</td>";
              echo "<td style='width: 16px;text-align:center;font-weight:bold;vertical-align:middle'>$val_k[kode]</td>";
              foreach($rs_dampak as $r_d => $val_d){
                  $bg = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['warna'];
                  $css = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['css'];
                  $tingkat_risiko = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['nama'];
                  $nokotak = 0;
                  $maxdot = 8;
                  $div = "";
                  $div1 = "";
                  if ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $rating['i']) {
                    foreach ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                      $nokotak++;
                      if($id_risiko_onlyone)
                        $cee = 'dot zoom-1';
                      else
                        $cee = 'dot';

                      $d = "<div style='font-size: 7px;width:11.5px;height:11.5px;float:left;padding:0px;margin:0.5px;background-color:black;border:1px solid black;color:#fff;' class='$cee'>$n</div>";
                      if($nokotak>$maxdot)
                        $div1 .= $d;
                      else
                        $div .= $d;
                    }
                  }
                  if ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $rating['c']) {
                    foreach ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                      $nokotak++;
                      if($id_risiko_onlyone)
                        $cee = 'dot zoom-2';
                      else
                        $cee = 'dot zoom-loop';

                      $d = "<div style='font-size: 7px;width:11.5px;height:11.5px;float:left;padding:0px;margin:0.5px;background-color:#666;border:1px solid black;color:#black;'  class='$cee'>$n</div>";
                      if($nokotak>$maxdot)
                        $div1 .= $d;
                      else
                        $div .= $d;
                    }
                  }
                  if ($top_residual_target[$val_d['id_dampak']][$val_k['id_kemungkinan']] && $rating['r']) {
                    foreach ($top_residual_target[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                      $nokotak++;
                      if($id_risiko_onlyone)
                        $cee = 'dot zoom-3';
                      else
                        $cee = 'dot';

                      $d = "<div style='font-size: 7px;width:11.5px;height:11.5px;float:left;padding:0px;margin:0.5px;background-color:#fff;border:1px solid black;color:black;' class='$cee'>$n</div>";
                      if($nokotak>$maxdot)
                        $div1 .= $d;
                      else
                        $div .= $d;
                    }
                  }
                  echo "<td class='bg-$bg' style='border:1px solid #888;background-color:$bg; padding:1px;$css' height='39px' width='39px' align='center' valign='middle'><div style='position:relative;height:39px;width:39px; vertical-align:middle; text-align:center;padding:17px 0px;'>";
                  echo $tingkat_risiko;

                  echo "<div style='position:absolute;top:0px;right:0px;'>".$div;
                  if($div1){
                    $div1 .= "<div style='clear:both'></div>";
                    echo '<div style="font-size: 7px;width:11.5px;height:11.5px;float:left;padding:0px;margin:0.5px;background-color:blue;border:1px solid blue;color:#fff;cursor: pointer;" rel="tooltip" title="'.$div1.'" data-html="true" data-placement="right" class="light-tooltip">+</div>';
                  
                    $tooltip1[$val_k['kode'].$val_d['kode']]['bg'] = $bg;
                    $tooltip1[$val_k['kode'].$val_d['kode']]['div'] = $div1;
                  }
                  echo "</div></div></td>";
                }
          echo "</tr>";
          }
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='3' rowspan='3' style='text-align: center;'>";
        ?>
<a href="" id="downloadmatrix" download="matrix.png" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-floppy-save"></span></a>
        <?php
        echo "</td>";
        foreach($rs_dampak as $r_d => $val_d){
          echo "<td style='font-weight:bold;text-align:center'>$val_d[kode]</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach($rs_dampak as $r_d => $val_d){
          echo "<td style='font-weight:bold;text-align:center;vertical-align:middle'>$val_d[nama]</td>";
        }
        echo "</tr>";
        echo "<tr>";
          echo "<td colspan='5' style='font-weight:bold;text-align:center'>TINGKAT DAMPAK</td>";
        echo "</tr>";
        ?>
    </table>
</div>
<style type="text/css">
  .tooltip-inner {
    white-space:pre-wrap;
}
.light-tooltip + .tooltip > .tooltip-inner {background-color: #fff !important; border: 1px solid #fff;}
.dark-tooltip + .tooltip > .tooltip-inner {background-color: #000c !important; border: 1px solid #000c;}
</style>
<script src="<?php echo base_url()?>assets/js/html2canvas.js"></script>
<script src="<?php echo base_url()?>assets/js/canvas2image.js"></script>
<script type="text/javascript">
  $(function(){
      /*html2canvas($("#divmatrix"), {
        onrendered: function(canvas) {
          var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  

        $("#downloadmatrix").attr("href",image);
        },
        width:600,height:600
      });*/
  })
</script>
<script type="text/javascript">
    $('[rel="tooltip"]').tooltip();  
</script>

<style type="text/css">
  .zoom-loop{    
    -webkit-animation: myzoom 0.3s;  /* Safari 4.0 - 8.0 */
    animation: myzoom 0.3s;
    -webkit-animation-iteration-count: infinite; /* Safari 4.0 - 8.0 */
    animation-iteration-count: infinite;
    animation-direction: alternate;
    -webkit-animation-direction: alternate; /* Safari 4.0 - 8.0 */
}
  .zoom-1{    
    -webkit-animation: myzooma 3s;  /* Safari 4.0 - 8.0 */
    -webkit-animation-iteration-count: infinite; /* Safari 4.0 - 8.0 */
    animation: myzooma 3s;
    animation-iteration-count: infinite;
}
  .zoom-2{    
    -webkit-animation: myzoomb 3s;  /* Safari 4.0 - 8.0 */
    -webkit-animation-iteration-count: infinite; /* Safari 4.0 - 8.0 */
    animation: myzoomb 3s;
    animation-iteration-count: infinite;
}
  .zoom-3{    
    -webkit-animation: myzoomz 3s;  /* Safari 4.0 - 8.0 */
    -webkit-animation-iteration-count: infinite; /* Safari 4.0 - 8.0 */
    animation: myzoomz 3s;
    animation-iteration-count: infinite;
}

@-webkit-keyframes myzoom {
    0% {background-color: #666;}
    100% {background-color: #ccc;}
}

@keyframes myzoom {
    0% {background-color: #666;}
    100% {background-color: #ccc;}
}

@-webkit-keyframes myzooma {
    0% {background-color: #666;color:#fff;}
    11% {background-color: #ccc;color:#fff;}
    22% {background-color: #666;color:#fff;}
    33% {transform: scale(1);}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}

@keyframes myzooma {
    0% {background-color: #666;color:#fff;}
    11% {background-color: #ccc;color:#fff;}
    22% {background-color: #666;color:#fff;}
    33% {transform: scale(1);}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}

@-webkit-keyframes myzoomb {
    0% {transform: scale(1);}
    11% {background-color: #666;color:#fff;}
    22% {background-color: #ccc;color:#fff;}
    33% {background-color: #666;color:#fff;}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}

@keyframes myzoomb {
    0% {transform: scale(1);}
    11% {background-color: #666;color:#fff;}
    22% {background-color: #ccc;color:#fff;}
    33% {background-color: #666;color:#fff;}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}

@-webkit-keyframes myzoomz {
    0% {transform: scale(1);}
    11% {transform: scale(1);}
    22% {transform: scale(1);}
    33% {background-color: #666;color: #fff;}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}

@keyframes myzoomz {
    0% {transform: scale(1);}
    11% {transform: scale(1);}
    22% {transform: scale(1);}
    33% {background-color: #666;color: #fff;}
    44% {transform: scale(1);}
    55% {transform: scale(1);}
    66% {transform: scale(1);}
    100% {transform: scale(1);}
}
.tbmatrix, .tbmatrix tr, .tbmatrix tr td, .tbmatrix tr th{
  border: 1px solid #888;
  font-size: 7px;
  vertical-align: middle;
  padding: 2px;
  font-family: 'Lato', Arial, Tahoma, sans-serif !important;
}

.tbmatrix tr td div.zoom-loop{
  color:#000 !important;
}
</style>