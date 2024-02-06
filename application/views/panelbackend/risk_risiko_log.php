<?php
foreach ($rows as $i=>$row) {
    $scorecard = $row['scorecard'];
    $risiko = $row['risiko'];
    $control = $row['control'];
    $mitigasi = $row['mitigasi'];
    if(!$risiko['id_risiko_sebelum'])
      $risiko['id_risiko_sebelum'] = $id_risiko;
    ?>
    <hr/>
    <br/>
    <?php if($i==0){ ?>
    <h5><b><?=$scorecard['nkr']?></b></h5>
    <h5><b><?=$scorecard['nama']?></b></h5>
    <small><b>Owner : </b><?=$scorecard['nj']?></small><br/>
    <small><b>Scope : </b><?=$scorecard['scope']?></small><br/>
    <?php } ?>
    <br/>
    <br/>
    <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">IDENTIFIKASI RISIKOOOO</h4>
    <table border="0" width="100%">
      <tr>
        <td valign="top" width="50%" style="margin:0px;padding: 0px;">
          <table class="table">
            <tr>
              <td><b>Sasaran Strategis</b></td>
              <td><?=$risiko['nss']?></td>
            </tr>
            <?php if($risiko['nsk']){ ?>
            <tr>
              <td><b>Sasaran Kegiatan</b></td>
              <td><?=$risiko['nsk']?></td>
            </tr>
            <tr>
              <td><b>KPI</b></td>
              <td><?=$risiko['ksk']?></td>
            </tr>
            <?php }else{ ?>
            <tr>
              <td><b>KPI</b></td>
              <td><?=$risiko['kss']?></td>
            </tr>
            <?php } ?>
          </table>
        </td>
        <td valign="top" width="50%" style="margin:0px;padding: 0px;">
          <table class="table">
            <tr>
              <td><b>Tgl. Risiko</b></td>
              <td><?=Eng2Ind($risiko['tgl_risiko'])?></td>
            </tr>
            <tr>
              <td><b>Nomor Risiko</b></td>
              <td><?=$risiko['nomor']?></td>
            </tr>
            <tr>
              <td><b>Nama Risiko</b></td>
              <td><?=$risiko['nama']?></td>
            </tr>
            <tr>
              <td><b>Deskripsi</b></td>
              <td><?=$risiko['deskripsi']?></td>
            </tr>
            <tr>
              <td><b>Penyebab</b></td>
              <td><?=$risiko['penyebab']?></td>
            </tr>
            <tr>
              <td><b>Dampak</b></td>
              <td><?=$risiko['dampak']?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    

    <table class="table">
      <tr>
        <td colspan="4"><b><u>INHEREN RISK</u></b></td>
      </tr>
      <tr>
        <th>Kemungkinan</th>
        <th>Dampak</th>
        <th>Kriteria</th>
        <th>Tingkat</th>
      </tr>
      <tr>
        <td><?=$mtkemungkinanarr[$risiko['inheren_kemungkinan']]?></td>
        <td><?=$mtdampakrisikoarr[$risiko['inheren_dampak']]?></td>
        <td><?=$risiko['nk']?></td>
        <td><?=UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $risiko, false, false)?></td>
      </tr>
    </table>
    <br/>
    <br/>
    <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">ANALISIS RISIKO / KONTROL</h4>
    <table class="table">
            <tr>
                <th>Nama Aktivitas Kontrol</th>
                <th>K/D</th>
                <th>Interval</th>
                <th width="100px">Efektif ?</th>
            </tr>
        <?php 
        foreach($control as $c) {?>
        <tbody>
            <tr>
              <td><?= $c['nama'] ?></td>
              <td><?= $c['menurunkan_dampak_kemungkinan'] ?></td>
              <td><?= $c['interval'] ?></td>
              <td align="center">
              <?php if ($c['is_efektif'] == 2) { ?>
                <?php echo "Tidak Efektif"; ?>
              <?php } else if ($c['is_efektif'] == 1) { ?>
                  <?php echo "Efektif"; ?>
              <?php } foreach($efektifitasarr as $r){ ?>
              
                <a href='#tblampiran' data-toggle='modal' data-target='#tblampiran' onclick="reqlampiran(<?=$risiko['id_risiko_sebelum']?>,<?=$c['id_control']?>, <?=$r['id_efektifitas']?>)"><i class="material-icons" style="font-size: 14px;">link</i></a>
              <?php } ?>
            </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>

    <table class="table">
      <tr>
        <td colspan="3"><b><u>CURRENT RISK</u></b></td>
      </tr>
      <tr>
        <th>Kemungkinan</th>
        <th>Dampak</th>
        <th>Tingkat</th>
      </tr>
      <tr>
        <td><?=$mtkemungkinanarr[$risiko['control_kemungkinan_penurunan']]?></td>
        <td><?=$mtdampakrisikoarr[$risiko['control_dampak_penurunan']]?></td>
        <td><?=UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $risiko, false,false)?></td>
      </tr>
    </table>

    <br/>
    <br/>
    <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">PENANGANAN RISIKO / MITIGASI</h4>
    <table class="table">
            <tr>
                <th>Aktivitas Mitigasi</th>
                <th>Deadline</th>
                <th>Penanggung Jawab</th>
                <th>K/D</th>
                <th>Biaya Mitigasi</th>
                <th>CBA</th>
                <th>Progress</th>
                <th></th>
            </tr>
        <tbody>
        <?php
        foreach($mitigasi as $m) { ?>
            <tr>
              <td><?= $m['nama'] ?></td>
              <td><?= $m['dead_line'] ?></td>
              <td><?= $m['jabatan'] ?></td>
              <td><?= $m['menurunkan_dampak_kemungkinan'] ?></td>
              <td><?= rupiah($m['biaya']) ?></td>
              <td><?= $m['cba'] ?> %</td>
              <td>
                <?= $m['status_progress'] ?>

              </td>
              <td>
                <a href='#tblampiran' data-toggle='modal' data-target='#tblampiran' onclick="reqlampiranmitigasi(<?=$risiko['id_risiko_sebelum']?>,<?=$m['id_mitigasi']?>)"><i class="material-icons" style="font-size: 14px;">link</i></a>
              </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <table class="table">
      <tr>
        <td colspan="3"><b><u>CURRENT RISK</u></b></td>
      </tr>
      <tr>
        <th>Kemungkinan</th>
        <th>Dampak</th>
        <th>Tingkat</th>
      </tr>
      <tr>
        <td><?=$mtkemungkinanarr[$risiko['residual_target_kemungkinan']]?></td>
        <td><?=$mtdampakrisikoarr[$risiko['residual_target_dampak']]?></td>
        <td><?=UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $risiko, false, false)?></td>
      </tr>
    </table>

    <br/>
    <br/>
    <h4 style="background-color: #034485 !important; color: #fff; padding:5px; text-align:center;">PEMANTAUAN DAN PENINJAUAN</h4>

    <table class="table">
      <tr>
        <td><b>Progress Capaian Kinerja</b></td>
        <td><?=$risiko['progress_capaian_kinerja']?></td>
      </tr>
      <tr>
        <td  width="30%"><b>Hambatan/Kendala</b></td>
        <td><?=$risiko['hambatan_kendala']?></td>
      </tr>
      <tr>
        <td><b>Penyesuaian Tindakan Mitigasi</b></td>
        <td><?=$risiko['penyesuaian_tindakan_mitigasi']?></td>
      </tr>
    </table>

    <table class="table">
      <tr>
        <td colspan="3"><b><u>RISIDUAL RISK HASIL EVALUASI</u></b></td>
      </tr>
      <tr>
        <th>Kemungkinan</th>
        <th>Dampak</th>
        <th>Tingkat</th>
      </tr>
      <tr>
        <td><?=$mtkemungkinanarr[$risiko['residual_kemungkinan_evaluasi']]?></td>
        <td><?=$mtdampakrisikoarr[$risiko['residual_dampak_evaluasi']]?></td>
        <td><?=UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $risiko, false, false)?></td>
      </tr>
    </table>
    <br/>
<?php
$label_close = "";
switch ($risiko['status_risiko']) {
  case '0':
    $label_close = "<span class='label label-default'>CLOSED</span><br/><br/>RISIKO SUDAH SELESAI";
    break;
  case '2':
    $label_close = "<span class='label label-warning'>BERLANJUT</span><br/><br/>STATUS RISIKO MASIH PERLU DI LAKUKAN KONTROL DAN MITIGASI";
    break;

  default:

    if($risiko['id_risiko_sebelum']){
    $label_close = "<span class='label label-success'>BERLANJUT</span><br/><br/>STATUS RISIKO MASIH PERLU DI LAKUKAN KONTROL DAN MITIGASI";
    }else{
      $label_close = "<span class='label label-success'>OPEN</span>";
    }
    break;
}
?>
    <center><h4><?=$label_close?></h4></center>    
    <center>TGL. PROSES : <?=strtoupper(Eng2Ind($risiko['tgl_close']))?></center>
    <br/>
    <br/>
<style type="text/css">
  .table{
    margin-bottom: -1px;
  }
</style>
<?php
}
?>


<div class="modal fade" id="tblampiran">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
                <center>
                  <h4 class="modal-title">LAMPIRAN</h4>
                </center>
          </div>
          <div class="modal-body">
            <div id="datalampiran">

            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  function reqlampiran(id_risiko_sebelum, id_control, id_efektifitas){
  $.ajax({
    type:"post",
    url:"<?=site_url("panelbackend/risk_control/detail")?>/"+id_risiko_sebelum+"/"+id_control,
    data:{
      act:'get_lampiran', 
      id_efektifitas:id_efektifitas, 
      id_control:id_control
    },
    success:function(ret){
      $('#datalampiran').html(ret);
    }
  });
  }
  function reqlampiranmitigasi(id_risiko_sebelum, id_mitigasi){
  $.ajax({
    type:"post",
    url:"<?=site_url("panelbackend/risk_mitigasi/detail")?>/"+id_risiko_sebelum+"/"+id_mitigasi,
    data:{
      act:'get_lampiran', 
      id_mitigasi:id_mitigasi
    },
    success:function(ret){
      $('#datalampiran').html(ret);
    }
  });
  }
</script>