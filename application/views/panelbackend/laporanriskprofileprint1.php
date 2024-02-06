<div class="row">
  <div class="col-lg-12">
    <div style="max-width: 300px; margin:auto">
      <?php 
      $form = UI::createTextNumber('tahun',$tahun,'200','100',true,'form-control ',"style='width:100px' onchange='goSubmit(\"set_value\")'");
      echo UI::FormGroup(array(
        'form'=>$form,
        'onlyone'=>3,
        'label'=>'Tahun'
        ));
      ?>
      <?php
      $form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
      echo UI::FormGroup(array(
        'form'=>$form,
        'onlyone'=>3,
        'label'=>'Risk Profile'
        ));
      ?>
      <?php 
      // unset($scorecardsubarr['']);
      if(is_array($scorecardsubarr) && count($scorecardsubarr)>1){
        $form = UI::createSelect('id_scorecard_sub',$scorecardsubarr,$id_scorecard_sub,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
        echo UI::FormGroup(array(
          'form'=>$form,
          'onlyone'=>3,
          'label'=>'Scorecard'
          ));
      }

      if($id_scorecard){
      ?>
      <a type="button" class="btn btn-default btn-lg" href="<?=current_url()?>"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
    <?php } ?>  
    <br/>
</div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6  no-padding">
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
        if($id_risiko_onlyone && $val['id_risiko']!=$id_risiko_onlyone){
          $no++;
          continue;
        }

        $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

        $no++;
      }

    include "_matrix.php"; ?>
  </div>
  <div  class="col-lg-6">
                    
    <table class="tableku1 table table-bordered no-margin table-hover" id="export" style="margin-top:15px;">
      <thead>
        <tr>
          <th style="width:1px;text-align:center;background-color:#034485;color:#eee;">NO</th>
          <th style="width:155px;text-align:center;background-color:#034485;color:#eee;">KODE</th>
          <th style="width:360px;text-align:center;background-color:#034485;color:#eee;">RISIKO</th>
          <th style="width:360px;text-align:center;background-color:#034485;color:#eee;">MITIGASI</th>
          <th style="width:90px;text-align:center;background-color:#034485;color:#eee;">STATUS MITIGASI</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rs = $this->data['rows'];
        $no=1;
        foreach($rs as $r => $val){
          $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
          $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
          $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
          
          $mitigasi = $rowsmitigasi[$val['id_risiko']];
          $rowspan = is_count($mitigasi);

          $css_only_one = "";

          if($val['id_risiko']==$id_risiko_onlyone)
            $css_only_one = "css_only_one";

          if(!$rowspan or $rowspan=='1'){
            $r1 = $mitigasi[0];

            $progress = "<span class='label label-warning'>On Progress</span>";
            if($r1['id_status_progress']==4)
              $progress = "<span class='label label-success'>Complate</span>";

            echo "<tr class='$css_only_one'>";
            echo "<td style='text-align:center' >".$no."</td>";
            echo "<td ><a href='javascript:void(0)' ";
            if($val['id_risiko']==$id_risiko_onlyone){
              echo "onclick='$(\"#key\").val($val[id_risiko]); goSubmit(\"set_value\")'";
            }else{
              echo "onclick='$(\"#key\").val($val[id_risiko]); goSubmit(\"only_one\")'";
            }
            echo "><span class='textmore textmore$val[id_risiko]'>$val[nomor]</span></a></td>";
            echo "<td ><a href='".site_url("panelbackend/risk_high_info/detail/$val[id_risiko]")."' target='_BLANK' data-toggle='tooltip' class='dark-tooltip' title='Informasi data pendukung'>";
            echo "<span class='textmore textmore$val[id_risiko]'>".$val['nama']."</span>";
            echo "</a></td>";

            echo "<td><span class='textmore textmore$val[id_risiko]'>";
            if($r1['no'])
              echo "$r1[no]. ";

            echo "$r1[nama]</span>";

            if((explode(" ", $r1['nama']))>5 or count(explode(" ", $val['nama']))>5 or count(explode(" ", $val['nomor']))>5){

              echo '<a href="javascript:void(0)" class="btn-show btnshow'.$val['id_risiko'].'" onclick="$(\'.btnshow'.$val['id_risiko'].'\').hide(); $(\'.btnhide'.$val['id_risiko'].'\').show(); $(\'.textmore'.$val['id_risiko'].' .morehide\').show();" style="font-size: 10px;"><i class="material-icons">keyboard_arrow_down</i></a>';

              echo '<a href="javascript:void(0)" class="btn-hide btnhide'.$val['id_risiko'].'" style="font-size: 10px; display:none" onclick="$(\'.btnshow'.$val['id_risiko'].'\').show(); $(\'.btnhide'.$val['id_risiko'].'\').hide(); $(\'.textmore'.$val['id_risiko'].' .morehide\').hide();"><i class="material-icons">keyboard_arrow_up</i></a>';
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
                echo "<td style='text-align:center' class='risikotop$val[id_risiko]' rowspan='1'>".$no."</td>";
                echo "<td class='risikotop$val[id_risiko]' rowspan='1'><a href='javascript:void(0)' ";

                if($val['id_risiko']==$id_risiko_onlyone){
                  echo "onclick='$(\"#key\").val($val[id_risiko]); goSubmit(\"set_value\")'";
                }else{
                  echo "onclick='$(\"#key\").val($val[id_risiko]); goSubmit(\"only_one\")'";
                }
            
                echo "><span class='textmore textmore$val[id_risiko]'>$val[nomor]</span></a></td>";
                echo "<td class='risikotop$val[id_risiko]' rowspan='1'><a href='".site_url("panelbackend/risk_high_info/detail/$val[id_risiko]")."' target='_BLANK' data-toggle='tooltip' class='dark-tooltip' title='Informasi & data pendukung'>";
                
                echo "<span class='textmore textmore$val[id_risiko]'>".$val['nama']."</span>";

                echo "</a></td>";
                echo "<td><span class='textmore textmore$val[id_risiko]'>";
                
                if($r1['no'])
                  echo "$r1[no]. ";

                echo "$r1[nama]</span> ";
                echo '<a href="javascript:void(0)" class="btn-show btnshow'.$val['id_risiko'].'" onclick="$(\'.risiko'.$val['id_risiko'].'\').show(); $(\'.btnshow'.$val['id_risiko'].'\').hide(); $(\'.risikotop'.$val['id_risiko'].'\').attr(\'rowspan\','.$rowspan.'); $(\'.textmore'.$val['id_risiko'].' .morehide\').show();" style="font-size: 10px;"><i class="material-icons">keyboard_arrow_down</i></a>';
                echo "</td>";
                echo "<td style='text-align:center'>$progress</td>";
                echo "</tr>";
              }else{
                echo "<tr style='display:none' class='risiko$val[id_risiko] $css_only_one'>";
                echo "<td> ";

                if($r1['no'])
                  echo "$r1[no]. ";

                echo "$r1[nama]";

                if($rowspan-1==$i){
                  echo '<a href="javascript:void(0)" class="btn-hide" style="font-size: 10px;" onclick="$(\'.risiko'.$val['id_risiko'].'\').hide(); $(\'.btnshow'.$val['id_risiko'].'\').show(); $(\'.risikotop'.$val['id_risiko'].'\').attr(\'rowspan\',1); $(\'.textmore'.$val['id_risiko'].' .morehide\').hide();"><i class="material-icons">keyboard_arrow_up</i></a>';
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
            echo "<tr><td colspan='8'>Data kosong</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<!-- <div class="row"> -->
  <!-- <div class="col-lg-12"> -->
  <!-- </div> -->
<!-- </div> -->
<script type="text/javascript">
  $(function(){
    $('.textmore').each(function(){
      var textmore = $(this).html();
      var loop = textmore.split(" ");
      var str1 = '';
      var str2 = '';
      for(var i = 0; i < loop.length; i++) {
        if(i<=5)
          str1 += loop[i]+' ';
        else
          str2 += loop[i]+' ';
      }

      $(this).html(str1+'<span class="morehide" style="display:none">'+str2+'</span>');
    });
  })
</script>

<style>
#container{
  width: 100%;
    font-size:14px;
    font-family:Arial, Helvetica, sans-serif;
}
.h4, .h5, .h6, h4, h5, h6, hr {
    margin-top: 5px;
    margin-bottom: 5px;
}
.tableku1 thead{
  page-break-before: always;
}
.css_only_one td{
  font-weight: bold !important;
}
.tableku1 {
    margin-top: 10px;
    width:100%;
}
.tableku1 td{
  padding:10px 5px !important;
  vertical-align: top;
  font-size: 12px !important;
}
.tableku1 thead th{
  padding:10px 5px !important;
  text-align: center;
}
.tableku1 th{
  padding:0px 3px !important;
  text-align: center;
  font-size: 12px !important;
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
