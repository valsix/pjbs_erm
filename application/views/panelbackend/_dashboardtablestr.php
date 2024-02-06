<div class="row">
<?php if($_SESSION[SESSION_APP]['group_id']==1){
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

  include "_matrix1.php";
} 
?>
  <table class="table table-bordered no-margin table-hover" id="export" style="margin-top:15px;">
      <thead>
        <tr>
          <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">NO</th>
          <th style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISIKO</th>
          <th style="text-align:center;background-color:#034485;color:#eee;" colspan="3">PROGRES MITIGASI</th>
        </tr>
        <tr>
          <th style="width: 50px">PROGRESS</th>
          <th style="width: 50px">COMPLETE</th>
          <th style="width: 50px">AVERAGE</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rs = $this->data['rows'];
        $no=1;
        foreach($rs as $r){

          echo "<tr>";
          echo "<td style='text-align:center' >".$no."</td>";
          echo "<td><a href='".site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko]")."'>$r[nama]</a></td>";
          echo "<td align='center'><span class='badge bg-orange'>".((int)$r['progress'])."</span></td>";
          echo "<td align='center'><span class='badge bg-teal'>".((int)$r['complete'])."</span></td>";
          echo "<td align='center'><span class='badge bg-purple'>".round($r['average'],2)." %</span></td>";
          echo "</tr>";

          $no++;
        }
        ?>
      </tbody>
    </table>
    <br/>
    <center style="display: relative">
      <h4 class="modal-title" style="font-size: 16px;color: #333; display: inline">
        KESIMPULAN
      </h4>
      <?php if(Access("edit_kesimpulan", "panelbackend/home") or $this->is_super_admin){ ?>
            <ul class="header-dropdown m-r--5" style="display: inline; padding-inline-start:0px">
              <li class="dropdown" style="display: inline;">
              <a href="javascript:void(0);" style="text-decoration: none" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                  <i class="material-icons" style="font-size: inherit;">more_vert</i>
              </a>
              <ul class="dropdown-menu pull-right" style="min-width: 200px">
                  <li style="width: 200px"><a href="javascript:void(0)" class=" waves-effect waves-block" onclick="reset_kesimpulan(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$id_scorecard?>)"><span class="glyphicon glyphicon-refresh"></span> Reset Kesimpulan</a></li>
                  <?php if($kesimpulan['status']){ ?>
                    <li style="width: 200px"><a href="javascript:void(0)" class=" waves-effect waves-block" onclick="reqkesimpulan(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$id_scorecard?>)"><span class="glyphicon glyphicon-edit"></span> Edit Kesimpulan</a></li>
                  <?php } ?>
                </ul>
              </li>
            </ul>
      <?php } ?>
    </center>

    <center>
    <?php
    if($kesimpulan['status'] == 'bad'){ ?>
      <span class="label label-danger"><?=$statusarr[$kesimpulan['status']]?></span>
      <br/>
      <?php
      if(!$kesimpulan['keterangan'])
      $kesimpulan['keterangan'] = $this->config->item("bad_condition");

      echo $kesimpulan['keterangan'];
    }
    if($kesimpulan['status'] == 'good'){ ?>
      <span class="label label-success">GOOD CONDITION</span>
      <br/>
      <?php
      if(!$kesimpulan['keterangan'])
      $kesimpulan['keterangan'] = $this->config->item("good_condition");

      echo $kesimpulan['keterangan'];
    }
    if($kesimpulan['status'] == 'default'){ ?>
      <?php
      if(!$kesimpulan['keterangan'])
      $kesimpulan['keterangan'] = $this->config->item("default_condition");

      echo $kesimpulan['keterangan'];
    }
    ?>
    <?php if(Access("view_all_direktorat", "panelbackend/risk_risiko") or $this->is_super_admin){ ?>
    <br/>
    <br/>
    <a href="<?=site_url("panelbackend/laporan_makalah/go_print/?id_kajian_risiko=$id_kajian_risiko&tahun=$tahun&bulan=$bulan&tanggal=$tanggal&id_scorecard=$id_scorecard&id_scorecard_sub=$id_scorecard")?>" class="btn btn-sm btn-info" target="_BLANK"><span class="glyphicon glyphicon-floppy-save" ></span> Download Document</a>
    <?php } ?></center>
</div>

<script type="text/javascript">
</script>

<style type="text/css">
  #kesimpulan<?=$id_class?> {
  z-index: 1080 !important;
}
</style>