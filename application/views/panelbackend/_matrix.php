<?php if($ajax){ ?>
<link href="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/template/backend/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/js/bootstrap.js"></script>
    
<div style="text-align: left; position: inherit; width:500px; margin: 0;">
<?php }else{ ?>
<div style="text-align: center; margin-top: 10px;position: inherit; width:500px; margin: auto;">
<?php } ?>
<div id="divmatrix" style="background-color: #fff; text-align: left; width:500px;">

<?php
  
  $SK_ID = ($this->data['mtriskkemungkinan'][0]["sk_id"]);
  $x = $this->db->query(" select min(id_dampak) x from MT_RISK_DAMPAK WHERE SK_ID = '$SK_ID' ")->first_row()->x;
  $y = $this->db->query(" select min(id_kemungkinan) y from MT_RISK_KEMUNGKINAN WHERE SK_ID = '$SK_ID' ")->first_row()->y;

  $iDampak = $x - 1;
  $iKemungkinan = $x - 1;


  if(!$top_inheren)
    $top_inheren = array();

  foreach ($top_inheren as $key => $value) {

    echo $key;
    foreach ($value as $k => $v) {


      $key = $key + $iDampak;
      $k = $k + $iKemungkinan;   



      $top_inheren[$key][$k] = array_unique($v);
    }
  }


  if(!$top_paska_kontrol)
    $top_paska_kontrol = array();

  foreach ($top_paska_kontrol as $key => $value) {
    foreach ($value as $k => $v) {

      $key = $key + $iDampak;
      $k = $k + $iKemungkinan;   



      $top_paska_kontrol[$key][$k] = array_unique($v);
    }
  }
  if(!$top_residual_target)
    $top_residual_target = array();

  foreach ($top_residual_target as $key => $value) {
    foreach ($value as $k => $v) {

      $key = $key + $iDampak;
      $k = $k + $iKemungkinan;   


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
          echo "<td rowspan='5' style='font-weight:bold;position:relative;width:25px'><div style='position:absolute;right: 19px;top: 260px;width: 0px;-ms-transform: rotate(-90deg);-webkit-transform: rotate(-90deg);transform: rotate(-90deg);height: 0px;'>TINGKAT&nbsp;KEMUNGKINAN</div></td>";
            foreach($rs_kemungkinan as $r_k => $val_k){
              echo "<td align='center' style='width: 25px;text-align:center;vertical-align:middle;font-weight:bold'>$val_k[nama]</td>";
              echo "<td style='width: 25px;text-align:center;font-weight:bold;vertical-align:middle'>$val_k[kode]</td>";
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

                      $d = "<div style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:black;border:1px solid black;color:#fff;' class='$cee'>$n</div>";
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

                      $d = "<div style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:#666;border:1px solid black;color:#black;'  class='$cee'>$n</div>";
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

                      $d = "<div style='font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:#fff;border:1px solid black;color:black;' class='$cee'>$n</div>";
                      if($nokotak>$maxdot)
                        $div1 .= $d;
                      else
                        $div .= $d;
                    }
                  }

                  echo "<td class='bg-$bg' style='border:1px solid #555;background-color:$bg; padding:1px;$css' height='75px' width='75px' align='center' valign='middle'><div style='position:relative;height:75px;width:75px; vertical-align:middle; text-align:center;padding:30px 0px;'>$tingkat_risiko
                  <div style='position:absolute;top:5px;right:5px;'>".$div;
                  if($div1){
                    $div1 .= "<div style='clear:both'></div>";
                    echo '<div style="font-size: 10px;width:17px;height:17px;float:left;padding:1px;margin:2px;background-color:blue;border:1px solid blue;color:#fff;cursor: pointer;" rel="tooltip" title="'.$div1.'" data-html="true" data-placement="right" class="light-tooltip">+</div>';
                  
                    $tooltip1[$val_k['kode'].$val_d['kode']]['bg'] = $bg;
                    $tooltip1[$val_k['kode'].$val_d['kode']]['div'] = $div1;
                  }
                  echo "</div></div></td>";
                }
          echo "</tr>";
          }
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='3' rowspan='3'></td>";
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

    <table style="width:auto;margin-top:10px">
      <tbody>

      <?php if($rating['i']){ ?>
        <tr>
          <td style="width:100px;background-color:black;border:2px solid black;"></td>
          <td style="font-size: 12px">&nbsp;INHEREN RISK</td>
        </tr>

      <?php } if($rating['c']){ ?>
        <tr>
          <td style="width:100px;background-color:#777;border:2px solid black;"></td>
          <td style="font-size: 12px">&nbsp;CURRENT RISK</td>
        </tr>
      <?php } if($rating['r']){ ?>
        <tr>
          <td style="width:100px;background-color:#fff;border:2px solid black;"></td>
          <td style="font-size: 12px">&nbsp;TARGETED RESIDUAL RISK</td>
        </tr>
      <?php } ?>
      </tbody>
    </table>

<?php if($no_tooltip){ ?>

<table style="margin-top: 10px; clear: both;" class="moretooltip">
<?php foreach($tooltip1 as $kd=>$r){ ?>
<tr>
  <td width="20px" style="background-color:<?=$r['bg']?>; vertical-align: middle;text-align: center;"><?=$kd?></td>
  <td width="100%"><?=$r['div']?></td>
</tr>
<?php } ?>
</table>
<style type="text/css">
  .moretooltip td{
    text-align: center !important;
    padding: 5px !important;
  }
</style>
<?php } ?>
</div>
<?php if(!$ajax){ ?>
<a href="" id="downloadmatrix" <?php /*onclick="saveimage()"*/ ?> download="matrix.png" class="btn btn-sm btn-success" style="float: right; margin-top: -40px;"><span class="glyphicon glyphicon-floppy-save"></span> Save Matriks</a>
<br/>
<br/>
<?php } ?>
</div>
<style type="text/css">
  .tooltip-inner {
    white-space:pre-wrap;
}
.light-tooltip + .tooltip > .tooltip-inner {background-color: #fff !important; border: 1px solid #000;}
.dark-tooltip + .tooltip > .tooltip-inner {background-color: #000c !important; border: 1px solid #000c;}
</style>
<?php if(!$ajax){ ?>
<script src="<?php echo base_url()?>assets/js/html2canvas.js"></script>
<script src="<?php echo base_url()?>assets/js/canvas2image.js"></script>
<script type="text/javascript">
  $(function(){
      html2canvas($("#divmatrix"), {
        onrendered: function(canvas) {
          // Canvas2Image.saveAsPNG(canvas);
          var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.

        $("#downloadmatrix").attr("href",image);
/*          var a = $("#linkmatrix");
          a.attr("href",image);
          a.attr("target","_blank");
          a.attr("download","MATRIX.png");
          var a = document.createElement('a');
           a.href = image;
           a.target = "_blank";
           a.download = 'MATRIX.png';
           a.click();*/
        },
        width:600,height:600
      });
  })
/*    function saveimage(){
    }*/
</script>
<?php } ?>
<script type="text/javascript">
    //data-html="true"
    // $('[data-toggle="tooltip"]').tooltip({
    //     container: 'body'
    // });
    $('[rel=tooltip]').tooltip({
        container: 'body'
    })

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
  border: 1px solid #9e9e9e;
  font-size: 11px;
  vertical-align: middle;
  padding: 5px;
  font-family: 'Lato', Arial, Tahoma, sans-serif !important;
}

.tbmatrix tr td div.zoom-loop{
  color:#000 !important;
}
</style>