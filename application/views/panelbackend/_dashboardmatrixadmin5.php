<style type="text/css">
  table.table {
/*    width:500px;*/
/*    border:1px #a9c6c9 solid;*/
  }
  table.table thead {
    display:table;
    width:100%;
    background-color: salmon;
  }
  table.table tbody {
    display:block;
    height:460px;
    overflow:auto;
    float:left;
    width:100%;
  }
  /*table.table tbody tr {
    display:table;
    width:100%;
  }
  table.table th, td {
    width:33%;
    padding:8px;
  }*/
</style>

  <h3 style="
    text-align: center;
    margin: 0px;
    margin-bottom: 5px;
    padding: 10px;
    padding-top: 13px;
    background: #ffd300c4;
    color: #fff;
">
    <?=trim(str_replace("KAJIAN RISIKO","",strtoupper($rk['nama'])))?>
    <div style="display: inline-block; position: relative;">
    <span class="label-total label label-info" style="top:-27px">
      <?=str_pad(count($scorecardarr)-1,2,'0',STR_PAD_LEFT)?>
    </span>
  </div>
</h3>

<div class="row" style="margin:0px">
<div class="box-dashboard">
  <h4 style="margin: 0px;"><center>DAFTAR PEKERJAAN
    <?php if(!$tahun_proyek)
      $tahun_proyek = $tahun;
      ?>
    <input type="numeric" style="background: #00000000; width: 50px; border:none" name='tahun_proyek' id='tahun_proyek' value='<?=$tahun_proyek?>' onchange='req(<?=$id_class?>, <?=$id_kajian_risiko?>, $(this).val())'/></center>
  </h4>
<hr/>
  <div class="row" style="margin-left: -10px; margin-right: -10px">
    <table class="table" style="margin-top: -11px;
    margin-bottom: -10px;">
    <?php $no=1; if($daftar_pekerjaan)
    foreach($daftar_pekerjaan as $r){ ?>
      <tr>
        <td style="padding: 5px; font-size: 12px">
          <a href='javascript::void(0)' style='color:yellow; font-size: 12px' data-toggle="modal" data-target="#tablematrix<?=$id_class?>" onclick="reqtb(<?=$id_class?>, <?=$id_kajian_risiko?>, <?=$tahun_proyek?>, <?=$r['id_scorecard']?>); $('#tablematrix<?=$id_class?> .modal-title').html('TOP <?=$top?> <?=strtoupper($rk['nama'])?><br/><small><?=$r['nama']?></small>')">
            <?=$r['nama']?>
          </a>
        </td>
        <td width="50px" align="center" style="padding: 5px"><label class='label' style='background:<?=$r['warna']?>'><?=$r['status']?></label></td>
      </tr>
    <?php } ?>
  </table>
</div>
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