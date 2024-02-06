  <h3 style="
    text-align: center;
    margin: 0px;
    margin-bottom: 5px;
    padding: 10px;
    padding-top: 13px;
    background: #2091f3c4;
    color: #fff;
">
    <?=trim(str_replace("KAJIAN RISIKO","",strtoupper($rk['nama'])))?>
    </h3>
<div class="row" style="margin:0px">
<div class="box-dashboard">
  <h4 style="margin: 0px;">RJPP 
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" >
      <?=str_pad($total['total_risiko_rjpp'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
  </h4>
<hr/>
  <div class="row">
    <div class="col-sm-6">
      <div  style="padding-top: 0px; text-align: center;">
        <input type="text" class="knob" value="<?=(float)$total['progress_mitigasi_rjpp']?>" data-width="90" data-height="90" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
        <div style=" font-size: 12px;width: 100px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
      </div>
    </div>
    <div class="col-sm-6">
      <div  style="padding-top: 0px; text-align: center;">
        <input type="text" class="knob" value="<?=(float)$total['control_efektif_rjpp']?>" data-width="90" data-height="90" data-thickness="0.25" data-fgColor="#97dc45" readonly>
        <div style=" font-size: 12px; width: 100px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
      </div>
    </div>
  </div>
<hr/>
  <div style="text-align: center;">
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-issue" onclick="reqissue(<?=$tahun?>, 'rjpp')"><i class="material-icons" style="font-size: 16px">table_chart</i> ISSUE & PROGRAM STRATEGIS</button>
      <button style="font-size: 12px; color: #fff !important; padding:5px; font-weight: bold;" type="button" class="btn bg-pink waves-effect" onclick="reqtb(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 207); $('#tablematrix<?=$id_class?>').modal('toggle'); $('#tablematrix<?=$id_class?> .modal-title').html('TOP <?=$top?> <?=strtoupper($rk['nama'])?> <br/><small>RJPP</small>');"><i class="material-icons" style="font-size: 16px">apps</i> TOP <?=$top?> RISIKO & MATRIKS</button>
  </div>
</div>

<div class="box-dashboard">
  <h4 style="margin: 0px;">RKAP 
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" >
      <?=str_pad($total['total_risiko_rkap'],2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
  </h4>
  <hr/>
  <div class="row" >
    <div class="col-sm-6">
      <div  style="padding-top: 0px; text-align: center;">
        <input type="text" class="knob" value="<?=(float)$total['progress_mitigasi_rkap']?>" data-width="90" data-height="90" data-thickness="0.25" data-fgColor="#f9f43a" readonly>
        <div style=" font-size: 12px;width: 100px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
      </div>
    </div>
    <div class="col-sm-6">
      <div  style="padding-top: 0px; text-align: center;">
        <input type="text" class="knob" value="<?=(float)$total['control_efektif_rkap']?>" data-width="90" data-height="90" data-thickness="0.25" data-fgColor="#97dc45" readonly>
        <div style=" font-size: 12px; width: 100px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
      </div>
    </div>
  </div>
  <hr/>
  <div style="text-align: center;">
      <button style="font-size: 12px; padding:5px; font-weight: bold;" type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-issue" onclick="reqissue(<?=$tahun?>, 'rkap')"><i class="material-icons" style="font-size: 16px">table_chart</i> ISSUE & PROGRAM STRATEGIS</button>
      <button style="font-size: 12px; padding:5px; font-weight: bold; color: #fff" type="button" class="btn bg-pink waves-effect" onclick="reqtb(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun?>, 283); $('#tablematrix<?=$id_class?>').modal('toggle'); $('#tablematrix<?=$id_class?> .modal-title').html('TOP <?=$top?> <?=strtoupper($rk['nama'])?> <br/><small>RKAP</small>');"><i class="material-icons" style="font-size: 16px">apps</i> TOP <?=$top?> RISIKO & MATRIKS</button>
  </div>
</div>
<div class="row">
  <marquee style='margin: 0px 15px;
    background: #0000009c;
    padding: 5px;'><?=$this->config->item("berita_strategis");?></marquee>
</div>
</div>


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
</div>

<script src="<?php echo base_url()?>assets/template/backend/js/pages/charts/jquery-knob.js"></script>