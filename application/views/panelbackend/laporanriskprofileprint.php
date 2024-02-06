
<?php if($is_css!==false){ ?>
<script src="<?php echo base_url()?>assets/template/backend/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/js/bootstrap.js"></script>
<?php } ?>

<?php if($report){ ?>
<div class="row" style="margin: 0px;">
<div class="col-lg-6" style="margin-left: 0px !important; padding-left: 0px !important">
  <table class="tableku1" id="export">
  <thead>
    <tr>
      <th style="width:1px;text-align:center;background-color:#2196F3;color:#fff;" rowspan="2">NO</th>
      <th  style="text-align:center;background-color:#2196F3;color:#fff;" rowspan="2">RISIKO</th>
      <th  style="text-align:center;background-color:#2196F3;color:#fff;" rowspan="2">RISK OWNER</th>
      <th  style="text-align:center;background-color:#2196F3;color:#fff;" colspan="<?=count($rating)?>">LEVEL RISIKO</th>
    </tr>
    <tr>
      <?php if($rating['i']){ ?>
      <th style="width:75px;text-align:center;background-color:#2196F3;color:#fff;">INHEREN RISK</th>
      <?php } if($rating['c']){ ?>
      <th  style="width:75px;text-align:center;background-color:#2196F3;color:#fff;">CURRENT RISK</th>
      <?php } if($rating['r']){ ?>
      <th  style="width:75px;text-align:center;background-color:#2196F3;color:#fff;">TARGETED RESIDUAL RISK</th>
      <?php } ?>
    </tr>
  </thead>
<?php }else{ ?>
<div class="row" style="margin: 0px auto; width:635px">
<div class="col-lg-12" style="margin-top:-7px">
  <table class="tableku1 table table-bordered" id="export" style="margin-top:15px;">
  <thead>
    <tr>
      <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">NO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISIKO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISK OWNER</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" colspan="<?=count($rating)?>">LEVEL RISIKO</th>
    </tr>
    <tr>
      <?php if($rating['i']){ ?>
      <th style="width:75px;text-align:center;background-color:#034485;color:#eee;">INHEREN RISK</th>
      <?php } if($rating['c']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee;">CURRENT RISK</th>
      <?php } if($rating['r']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee ;">TARGETED RESIDUAL RISK</th>
      <?php } ?>
    </tr>
  </thead>
<?php } ?>
  <tbody>
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
    foreach($rs as $r => $val){
      $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
      $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
      $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
      
      echo "<tr>";
      echo "<td style='text-align:center'>".$no++."</td>";
      echo "<td><a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a></td>";
      echo "<td style='text-align:center'>$val[risk_owner]</td>";

      if($rating['i']){
        $bg = $data[$val['inheren_dampak']][$val['inheren_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:#$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_inheren]</td>";
      }

      if($rating['c']){
        $bg = $data[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']]['warna'];
        echo "<td align='center' style='background-color:#$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_control]</td>";
      }

      if($rating['r']){
        $bg = $data[$val['residual_target_dampak']][$val['residual_target_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:#$bg;color:#333 !important;' class='bg-$bg'>$val[level_residual_evaluasi]</td>";
      }

      echo "</tr>";
    }
    if(!($rs)){
        echo "<tr><td colspan='8'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
</table>
<?php if($report){ ?>
  <br/>
</div>
<div class="col-lg-6" style="margin-right: 0px !important; padding-right: 0px !important">
<?php }else{ ?>
</div>
<div class="col-lg-12">
<?php } ?>
<?php include "_matrix.php"; ?>
</div>
</div>
<style>
    .notshow{
      margin-top: 10px;
    }
  @media print{
    .notshow{
      display: none;
    }
    body{
      margin: 0px;
      padding: 10px 5px;
    }
    html{
      margin:0px;
      padding:0px;
    }
  }
#container{
  width: 100%;
    font-size:14px;
    font-family:Arial, Helvetica, sans-serif;
}
<?php if($report){ ?>
td,th{
    padding: 3px;
    font-size: 12px;
    vertical-align:text-center;
}
<?php }else{ ?>
<?php } ?>
.h4, .h5, .h6, h4, h5, h6, hr {
    margin-top: 5px;
    margin-bottom: 5px;
}
.tableku {
    margin-top: 20px;
    width:100%;
    border:1px solid #555;
}
.tableku td{border: 1px solid #555;
    padding: 0px 3px;
  vertical-align: top;
}
.tableku thead th{
  border:1px solid #555;
  border-bottom:2px  solid #555;
  padding:0px 3px;
}
.tableku th{
  border:1px solid #555;
  padding:0px 3px;
}
.tableku thead, .tableku1 thead{
  border:1px solid #555;
  page-break-before: always;
}

.tableku1 {
    margin-top: 10px;
    width:100%;
    border:1px solid #555;
}
.tableku1 td{
    border:1px solid #555 !important;
  padding:3px 5px;
  vertical-align: top;
  font-size: 12px !important;
}
.tableku1 thead th{
  border:1px solid #555;
  border-bottom:2px  solid #555;
  padding:3px 5px;
  text-align: center;
}
.tableku1 th{
  border:1px solid #555;
  padding:0px 3px;
  text-align: center;
  font-size: 12px !important;
}
</style>