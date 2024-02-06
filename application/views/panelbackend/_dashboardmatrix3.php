  <h3 style="text-align: center; color: #fff; padding-bottom: 15px; padding-top: 30px">
    <?=strtoupper($rk['nama'])?>
    </h3>
<div class="row" style="margin:0px 5%">
<div class="col-sm-5">
  <h4 style="margin: 10px;text-align: center;">
    TOP <input type="text" style="margin:-1px 0px; border: none; background: #00000000; width: 25px;" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" name="top" id="top<?=$id_class?>" value="<?=$top?>"/> MATRIKS
    <?php if($id_scorecard){ ?>
    <div style="display: inline-block; position: relative; margin:-10px 0px;">
      <span class="label label-info">
        <?=$scorecardarr[$id_scorecard]?>
      </span>
    </div>
    <?php }else{ ?>
     DARI <?=$total['total_risiko']?> RISIKO
    <?php } ?>
  </h4>
    <hr style='border-top: 1px solid #ffffff00;    margin-bottom: 0px;'/>
<select id="id_scorecard<?=$id_class?>" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" style="width: 370px;margin: auto; border: none; background: #00000030; display: block;     padding: 4px;">
        <?php $scorecardarr[''] = 'semua '.strtolower($rk['nama']); foreach($scorecardarr as $k=>$v){ ?>
        <option value="<?=$k?>" <?=($id_scorecard==$k)?"selected":""?> style="color: #555; background: #fff"><?=$v?></option>
        <?php } ?>
        </select>
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

  include "_matrix2.php"; ?>

</div>
<div class="col-sm-7">
  <h4 style="margin: 10px 0px;">RJPP 
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -23px; padding: .5em; font-size: 90%;">
      <?=str_pad($total['total_risiko_rjpp'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
  </h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
  <div class="row" style="margin-top: 18px">
    <?php if($total['progress_mitigasi_rjpp']){ ?>
    <div class="col-sm-3">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px;width: 100px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
        <input type="text" class="knob" value="<?=$total['progress_mitigasi_rjpp']?>" data-width="80" data-height="80" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
      </div>
    </div>
    <?php } ?>
    <?php if($total['control_efektif_rjpp']){ ?>
    <div class="col-sm-3">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px; width: 100px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
        <input type="text" class="knob" value="<?=$total['control_efektif_rjpp']?>" data-width="80" data-height="80" data-thickness="0.25" data-fgColor="#eb4664" readonly>
      </div>
    </div>
    <?php } ?>
    <div class="col-sm-6">
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-issue" onclick="reqissue(<?=$tahun?>, 'rjpp')"><i class="material-icons" style="font-size: 16px">table_chart</i> ISSUE & PROGRAM STRATEGIS</button>
      <!-- <br/>
      <br/>
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn bg-pink waves-effect" onclick="$('#id_scorecard<?=$id_class?>').val(207); $('#id_scorecard<?=$id_class?>').change()"><i class="material-icons" style="font-size: 16px">apps</i> TOP <?=$top?> MATRIKS</button> -->
    </div>
  </div>


  <BR/>
  <BR/>
  <h4 style="margin: 10px 0px;">RKAP
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -23px; padding: .5em; font-size: 90%;">
      <?=str_pad($total['total_risiko_rkap'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div></h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
  <div class="row" style="margin-top: 18px">
    <?php if($total['progress_mitigasi_rkap']){ ?>
    <div class="col-sm-3">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px;width: 100px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
        <input type="text" class="knob" value="<?=$total['progress_mitigasi_rkap']?>" data-width="80" data-height="80" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
      </div>
    </div>
    <?php } ?>
    <?php if($total['control_efektif_rkap']){ ?>
    <div class="col-sm-3">
      <div  style="padding-top: 0px; text-align: center;">
        <div style="padding-bottom:5px; font-size: 12px; width: 100px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
        <input type="text" class="knob" value="<?=$total['control_efektif_rkap']?>" data-width="80" data-height="80" data-thickness="0.25" data-fgColor="#eb4664" readonly>
      </div>
    </div>
    <?php } ?>
    <div class="col-sm-3">
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-issue" onclick="reqissue(<?=$tahun?>, 'rkap')"><i class="material-icons" style="font-size: 16px">table_chart</i> ISSUE & PROGRAM STRATEGIS</button>
      <!-- <br/>
      <br/>
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn bg-pink waves-effect" onclick="$('#id_scorecard<?=$id_class?>').val(283); $('#id_scorecard<?=$id_class?>').change()"><i class="material-icons" style="font-size: 16px">apps</i> TOP <?=$top?> MATRIKS</button> -->
    </div>
    <div class="col-sm-3">
      
    </div>
  </div>
  <div class="row">
    <marquee style='    margin-top: 39px;
    margin-left: 20px;
    margin-right: 20px;
    background: #00000042;
    padding: 15px 5px;'><?=$this->config->item("berita_strategis");?></marquee>
  </div>
</div>

    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 800 }, 500);' class="btn-navigsi" style="position: absolute; top:280px; right: 30px"><i class="material-icons">keyboard_arrow_down</i></a>
    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 1600 }, 500);' class="btn-navigsi" style="position: absolute; top:320px; right: 30px"><i class="material-icons">arrow_downward</i></a>
</div>

<!-- 
<div class="modal fade" id="tablematrix<?=$id_class?>">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
                <center>
                  <h4 class="modal-title" style="color: #333">TOP <?=$top?> <?=strtoupper($rk['nama'])?> <?php if($scorecard_name) { ?> <br/><small><?=trim(implode(" / ",$scorecard_name)," / ")?></small><?php } ?> </h4>
                </center>
          </div>
          <div class="modal-body">
            <div id="datarisiko<?=$id_kajian_risiko?>">

            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
    </div>
</div> -->

<script src="<?php echo base_url()?>assets/template/backend/js/pages/charts/jquery-knob.js"></script>