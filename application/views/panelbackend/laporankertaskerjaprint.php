
<script src="<?php echo base_url()?>assets/template/backend/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/js/bootstrap.js"></script>
<table class="tableku1" id="export" border="1" style="border: 0px;">
  <thead style="border: 0px;">
  <tr  style="border: 0px;">
    <td style="border: 0px;"></td>
    <td style="border: 0px;">
    <img src="<?=base_url()?>assets/img/logo.jpg" width="70px">
    </td>
    <td colspan="<?=(@count($this->data['rows'][0])-2)?>" style="border: 0px;">
    <b>
    <b>
    <h4 style="font-weight: bold;">
    <?=$this->config->item("company_name")?>
    </h4>
    </b>
    <small>
    <?=$this->config->item("company_address")?>
    </small>
    </b>
    </td>
  </tr>
  <tr>
    <td style="border: 0px;"  colspan="<?=(@count($this->data['rows'][0]))?>">
      <table border="0" width="100%">
        <tr style="border-top:1px solid #555;border-bottom:1px solid #555;">
          <td width="1%" style="border: 0px;"></td>
          <td width="29%" align="left" style="border: 0px;"><small><b>Telepon : <?=$this->config->item("company_telp")?></b></small></td>
          <td width="30%" align="center" style="border: 0px;"><small><b>Faksimile : <?=$this->config->item("company_fax")?></b></small></td>
          <td width="30%" align="right" style="border: 0px;"><small><b>Email : <?=$this->config->item("company_email")?></b></small></td>
        </tr>
      </table>
      <br/>
    </td>
  </tr>
    <tr>
      <th class="bg-blue" style="color:#fff; background-color: blue;" rowspan="2">No</th>
      <?php foreach ($header1 as $key => $r) { ?>
      <th class="bg-blue" style="color:#fff; background-color: blue;" rowspan="<?=$r['rowspan']?>" colspan="<?=$r['colspan']?>"><?=$r['label']?></th>
      <?php } ?>
    </tr>
    <tr>

      <?php foreach ($header2 as $key => $r) { ?>
      <th class="bg-blue" style="color:#fff; background-color: blue;" rowspan="<?=$r['rowspan']?>" colspan="<?=$r['colspan']?>"><?=$r['label']?></th>
      <?php } ?>
		</tr>
  </thead>
  <tbody>
  <?php
  
    if(in_array('level_risiko_inheren',$paramheader))
      $rating['i']=1;

    if(in_array('level_risiko_paskakontrol',$paramheader))
      $rating['c']=1;

    if(in_array('level_risiko_residual',$paramheader))
      $rating['r']=1;

    $rs = $this->data['rows'];

    $rowsmitigasi = array();
    $rowscontrol = array();

    foreach($rs as $r){
      $rowsmitigasi[$r['id_risiko']][$r['id_mitigasi']] = $r;
      $rowscontrol[$r['id_risiko']][$r['id_control']] = $r;
    }

    $rs = array();
    foreach ($rowscontrol as $id_risiko => $row) {
      if(count($row)>count($rowsmitigasi[$id_risiko])){
        foreach ($row as $id_control=>$r) {
          $t = @each($rowsmitigasi[$id_risiko]);
          $r1 = $t['value'];
          foreach ($norowspan_mitigasi as $k1 => $v2) {
              $r[$v2] = $r1[$v2];
          }
          $r['id_control'] = $id_control;
          $r['id_mitigasi'] = $r1['id_mitigasi'];
          $rs[] = $r;
        }
      }else{
        foreach ($rowsmitigasi[$id_risiko] as $id_mitigasi=>$r) {
          $t = @each($row);
          $r1 = $t['value'];
          foreach ($norowspan_control as $k1 => $v2) {
              $r[$v2] = $r1[$v2];
          }
          $r['id_mitigasi'] = $id_mitigasi;
          $r['id_control'] = $r1['id_control'];
          $rs[] = $r;
        }
      }
    }

    $rowspan = array();
    foreach ($rs as $r) {
        $rowspan[$r['id_risiko']]++;
    }

    $no=1;
    if(!$paramheader)$paramheader = array();
    $id_risiko = 0;

    $top_inheren = array();
    $top_paska_kontrol = array();
    $top_paska_mitigasi = array();

    foreach($rs as $r => $val){

      $rp = $rowspan[$val['id_risiko']];

      echo "<tr>";
      if($id_risiko!=$val['id_risiko']){

        $top_inheren[$val['inheren_dampak1']][$val['inheren_kemungkinan1']][] = $no;
        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
        echo "<td style='text-align:center' rowspan='$rp' valign='top'>".$no++."</td>";
      }

      foreach($paramheader as $k1=>$k){
        $rp1=$rp;

        if(in_array($k, $norowspan))
          $rp1=0;

        if($rp1 && $id_risiko==$val['id_risiko'])
          continue;

        $addrowspan = "";
        
        if($rp1)
          $addrowspan = "rowspan='$rp1'";

        $addrowspan .= " valign='top'";
        $v = $val[$k];
        if($v==null)
          echo "<td $addrowspan></td>";
        elseif($type_header[$k]){
          if(is_array($type_header[$k])){
            $list = $type_header[$k]['list'];
            echo "<td $addrowspan>".$list[$v]."</td>";
          }else{
            $type = $type_header[$k];
            if($type=='date'){
              echo "<td $addrowspan>".Eng2Ind($v)."</td>";
            }elseif($type=='rupiah'){
              echo "<td $addrowspan>".rupiahAngka($v)."</td>";
            }elseif($type=='persen'){
              echo "<td $addrowspan style='text-align:right'>".($v)."%</td>";
            }elseif($type=='rating'){
              echo "<td $addrowspan style='background-color:$warnarr[$v]'>$v</td>";
            }else{
              echo "<td $addrowspan>".nl2br($v)."</td>";
            }
          }
        }else{
          if($k=='status_risiko'){
            if($v=='0'){
              echo "<td $addrowspan style='background-color:#ddd;'>Close</td>";
            }else{
              echo "<td $addrowspan style='background-color:#58b051;'>Open</td>";
            }
          }else
            echo "<td $addrowspan>".nl2br($v)."</td>";
        }
      }

      echo "</tr>";
      $id_risiko = $val['id_risiko'];
    }
    if(!isset($rs)){
        echo "<tr><td colspan='".(count($paramheader)+1)."'>Data kosong</td></tr>";
    }
  ?>

  </tbody>
</table>

<?php 
// include "_matrix.php"; ?>