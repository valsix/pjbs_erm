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
      <th style="width:1px;text-align:center;background-color:#034485;color:#eee;">NO</th>
      <th style="text-align:center;background-color:#034485;color:#eee;">RISIKO</th>
      <th style="width:350px;text-align:center;background-color:#034485;color:#eee;">MITIGASI</th>
      <th style="width:90px;text-align:center;background-color:#034485;color:#eee;">STATUS MITIGASI</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $rs = $this->data['rows'];
    $no=1;
    foreach($rs as $r){          
      $mitigasi = $rowsmitigasi[$r['id_risiko']];
      // dpr($mitigasi,1);
      $rowspan = count($mitigasi);

      $css_only_one = "";
      $status_proyek = "";

      if($r['id_status_proyek']==1){
      $status_proyek = '<span class="label label-warning">YANG AKAN DATANG</span>';
      } if($r['id_status_proyek']==2){
      $status_proyek = '<span class="label label-success">BERJALAN</span>';
      } if($r['id_status_proyek']==3){ 
      $status_proyek = '<span class="label label-danger">GAGAL</span>';
      } if($r['id_status_proyek']==4){
      $status_proyek = '<span class="label label-default">HOLD</span>';
      }

      if($r['id_risiko']==$id_risiko_onlyone)
        $css_only_one = "css_only_one";

      if(!$rowspan or $rowspan=='1'){
        $r1 = $mitigasi[0];

        $progress = "<span class='label label-warning'>On Progress</span>";
        if($r1['id_status_progress']==4)
          $progress = "<span class='label label-success'>Complate</span>";

        echo "<tr class='$css_only_one'>";
        echo "<td style='text-align:center' >".$no."</td>";
        echo "<td ><a href='".site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko]")."'>$r[nama]</a></td>";

        echo "<td><span class='textmore textmore$r[id_risiko]'>".nl2br($r1['nama'])."</span>";

        if(strlen($r1['nama'])>45 && $rowspan){

          echo '<a href="javascript:void(0)" class="btn-show btnshow'.$r['id_risiko'].'" onclick="$(\'.btnshow'.$r['id_risiko'].'\').hide(); $(\'.btnhide'.$r['id_risiko'].'\').show(); $(\'.textmore'.$r['id_risiko'].' .morehide\').show();" style="font-size: 10px;"><i class="material-icons">keyboard_arrow_down</i></a>';

          echo '<a href="javascript:void(0)" class="btn-hide btnhide'.$r['id_risiko'].'" style="font-size: 10px; display:none" onclick="$(\'.btnshow'.$r['id_risiko'].'\').show(); $(\'.btnhide'.$r['id_risiko'].'\').hide(); $(\'.textmore'.$r['id_risiko'].' .morehide\').hide();"><i class="material-icons">keyboard_arrow_up</i></a>';
        }

        echo "</td>";
        echo "<td style='text-align:center'>$progress</td>";
        echo "</tr>";
      }else{
        foreach($mitigasi as $i=>$r1){
          $progress = "<span class='label label-warning'>On Progress</span>";
          if($r1['id_status_progress']==4)
            $progress = "<span class='label label-success'>Complate</span>";

          if($i==0){
            echo "<tr class='$css_only_one'>";
            echo "<td style='text-align:center' class='risikotop$r[id_risiko]' rowspan='1'>".$no."</td>";
            echo "<td class='risikotop$r[id_risiko]' rowspan='1'><a href='".site_url("panelbackend/risk_risiko/detail/$r[id_scorecard]/$r[id_risiko]")."'>$r[nama]</a></td>";
            echo "<td><span class='textmore textmore$r[id_risiko]'>".nl2br($r1['nama'])."</span> ";
            echo '<a href="javascript:void(0)" class="btn-show btnshow'.$r['id_risiko'].'" onclick="$(\'.risiko'.$r['id_risiko'].'\').show(); $(\'.btnshow'.$r['id_risiko'].'\').hide(); $(\'.risikotop'.$r['id_risiko'].'\').attr(\'rowspan\','.$rowspan.'); $(\'.textmore'.$r['id_risiko'].' .morehide\').show();" style="font-size: 10px;"><i class="material-icons">keyboard_arrow_down</i></a>';
            echo "</td>";
            echo "<td style='text-align:center'>$progress</td>";
            echo "</tr>";
          }else{
            echo "<tr style='display:none' class='risiko$r[id_risiko] $css_only_one'>";
            echo "<td>$r1[nama] ";
            if($rowspan-1==$i){
              echo '<a href="javascript:void(0)" class="btn-hide" style="font-size: 10px;" onclick="$(\'.risiko'.$r['id_risiko'].'\').hide(); $(\'.btnshow'.$r['id_risiko'].'\').show(); $(\'.risikotop'.$r['id_risiko'].'\').attr(\'rowspan\',1); $(\'.textmore'.$r['id_risiko'].' .morehide\').hide();"><i class="material-icons">keyboard_arrow_up</i></a>';
            }
            echo "</td>";
            echo "<td style='text-align:center'>$progress</td>";
            echo "</tr>";
          }
        }
      }

      $no++;
    }
    if(!($rs)){
        echo "<tr><td colspan='7'>Data kosong</td></tr>";
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
  $(function(){
    $('.textmore').each(function(){
      var textmore = $(this).html();
      var loop = textmore.split("");
      var str1 = '';
      var str2 = '';
      for(var i = 0; i < loop.length; i++) {
        if(i<=44)
          str1 += loop[i]+'';
        else
          str2 += loop[i]+'';
      }

      $(this).html(str1+'<span class="morehide" style="display:none">'+str2+'</span>');
    });
  })
</script>

<style type="text/css">
  #kesimpulan<?=$id_class?> {
  z-index: 1080 !important;
}
  .modal .table td, .modal .table th{
    padding: 3px !important;
    font-size: 13px !important;
  }
.btn-show{
  float: right;
  height: 0px;
  margin-top:-1px;
}
.btn-hide{    
  float: right;
  height: 15px;
}
</style>