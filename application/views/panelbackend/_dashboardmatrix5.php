  <h3 style="text-align: center; color: #fff; padding-bottom: 15px; padding-top: 30px; position: relative;">
    <?=strtoupper($rk['nama'])?>
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="display: block;top: -30px; padding: .5em; font-size: 90%;">
      <?=str_pad(count($scorecardarr)-1,2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
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
  <h4 style="margin: 10px 0px;">DAFTAR PEKERJAAN
    <?php if(!$tahun_proyek)
      $tahun_proyek = $tahun;
      ?>
    <input type="numeric" style="background: #00000030; width: 50px; border:none" name='tahun_proyek' id='tahun_proyek' value='<?=$tahun_proyek?>' onchange='req(<?=$id_class?>, <?=$id_kajian_risiko?>, $(this).val())'/>
  </h4>
  <hr style='border-top: 1px solid #ffffff12;'/>
    <table class="table" style="margin-top: -15px;">
    <?php $no=1; if($daftar_pekerjaan)
    foreach($daftar_pekerjaan as $r){ ?>
      <tr>
        <td style="padding: 10px 0px;">
          <?=$no++?>
        </td>
        <td>
          <a href='javascript::void(0)' style='color:yellow' data-toggle="modal" data-target="#tablematrix<?=$id_class?>" onclick="reqtb(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun_proyek?>, <?=$r['id_scorecard']?>); $('#tablematrix<?=$id_class?> .modal-title').html('TOP <?=$top?> <?=strtoupper($rk['nama'])?><br/><small><?=$r['nama']?></small>')">
            <?=$r['nama']?>
          </a>
        </td>
        <td width="100px" align="center"><label class='label' style='background:<?=$r['warna']?>'><?=$r['status']?></label></td>
      </tr>
    <?php } ?>
  </table>
  
</div>

    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 0 }, 500);' class="btn-navigsi" style="position: absolute; top:280px; right: 30px"><i class="material-icons">arrow_upward</i></a>
    <a href="javascript::void(0)" onclick='$("html, body").animate({ scrollTop: 800 }, 500);' class="btn-navigsi" style="position: absolute; top:320px; right: 30px"><i class="material-icons">keyboard_arrow_up</i></a>
</div>


<!-- <div class="modal fade" id="tablematrix<?=$id_class?>">
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