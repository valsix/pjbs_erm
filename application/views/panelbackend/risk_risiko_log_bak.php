<?php
foreach ($rows as $i=>$row) {
    $scorecard = $row['scorecard'];
    $risiko = $row['risiko'];
    $control = $row['control'];
    $mitigasi = $row['mitigasi'];
    
    ?>
    <hr/>
    <br/>
    <?php if($i==0){ ?>
    <h5><b><?=$scorecard['nkr']?></b></h5>
    <small><b>Owner : </b><?=$scorecard['nj']?></small><br/>
    <small><b>Scope : </b><?=$scorecard['scope']?></small><br/>
    <?php } ?>
    <br/>
    <h4>Tgl. Risiko : <?=Eng2Ind($risiko['tgl_risiko'])?> Tgl. Close : <?=Eng2Ind($risiko['tgl_close'])?> </h4>
    <br/>
    <table class="table">
      <tr>
        <td><b>Sasaran Strategis</b></td>
        <td><?=$risiko['nss']?></td>
        <td colspan="2"><b>RISIKO INHEREN</b></td>
      </tr>
      <tr>
        <td><b>KPI</b></td>
        <td><?=$risiko['kss']?></td>
        <td><b>Kemungkinan</b></td>
        <td><?=$mtkemungkinanarr[$risiko['inheren_kemungkinan']]?></td>
      </tr>
      <tr>
        <td><b>Sasaran Kegiatan</b></td>
        <td><?=$risiko['nsk']?></td>
        <td><b>Dampak</b></td>
        <td><?=$mtdampakrisikoarr[$risiko['inheren_dampak']]?></td>
      </tr>
      <tr>
        <td><b>KPI</b></td>
        <td><?=$risiko['ksk']?></td>
        <td><b>Kategori</b></td>
        <td><?=$risiko['nk']?></td>
      </tr>
      <tr>
        <td><b>Nomor Risiko</b></td>
        <td><?=$risiko['nomor']?></td>
        <td><b>Tingkat</b></td>
        <td><?=UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $risiko, false)?></td>
      </tr>
      <tr>
        <td><b>Nama Risiko</b></td>
        <td><?=$risiko['nama']?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Deskripsi</b></td>
        <td><?=$risiko['deskripsi']?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Penyebab</b></td>
        <td><?=$risiko['penyebab']?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Dampak</b></td>
        <td><?=$risiko['dampak']?></td>
        <td></td>
        <td></td>
      </tr>
    </table>

    <div><b>ANALISIS RISIKO </b></div>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Control</th>
                <th>Keterangan</th>
                <th>Menurunkan ?</th>
                <th>Interval</th>
                <th>Efektif ?</th>
            </tr>
        </thead>
        <?php $no = 1;
        foreach($control as $c) { ?>
        <tbody>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $c['nama'] ?></td>
              <td><?= $c['deskripsi'] ?></td>
              <td><?= $c['menurunkan_dampak_kemungkinan'] ?></td>
              <td><?= $c['interval'] ?></td>
              <?php if ($c['is_efektif'] == 2) { ?>
                <td><?php echo "Tidak Efektif"; ?></td>
              <?php } else if ($c['is_efektif'] == 1) { ?>
                  <td><?php echo "Efektif"; ?></td>
              <?php } ?>
            </tr>
        </tbody>
        <?php } ?>
    </table>

    <table class="table">
      <tr>
        <td colspan="6"><b>CURRENT RISK</b></td>
      </tr>
      <tr>
        <td><b>Kemungkinan</b></td>
        <td><?=$mtkemungkinanarr[$risiko['control_kemungkinan_penurunan']]?></td>
        <td><b>Dampak</b></td>
        <td><?=$mtdampakrisikoarr[$risiko['control_dampak_penurunan']]?></td>
        <td><b>Tingkat</b></td>
        <td><?=UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $risiko, false)?></td>
      </tr>
    </table>

    <div><b>PENGANGANAN RISIKO </b></div>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mitigasi</th>
                <th>Keterangan</th>
                <th>Deadline</th>
                <th>Menurunkan ?</th>
                <th>Biaya Mitigasi</th>
                <th>CBA</th>
                <th>Efektif ?</th>
                <th>Penanggung Jawab</th>
                <th>Progress Mitigasi</th>
            </tr>
        </thead>
        <?php
        $no = 1;
        foreach($mitigasi as $m) { ?>
        <tbody>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $m['nama'] ?></td>
              <td><?= $m['deskripsi'] ?></td>
              <td><?= $m['dead_line'] ?></td>
              <td><?= $m['menurunkan_dampak_kemungkinan'] ?></td>
              <td><?= $m['biaya'] ?></td>
              <td><?= $m['cba'] ?></td>
              <?php if ($m['is_efektif'] == 0) { ?>
                <td><?php echo "Tidak Efektif"; ?></td>
              <?php } else {?>
                <td><?php echo "Efektif"; ?></td>
              <?php } ?>
              <td><?= $m['jabatan'] ?></td>
              <td><?= $m['status_progress'] ?></td>
            </tr>
        </tbody>
        <?php } ?>
    </table>

    <table class="table">
      <tr>
        <td colspan="6"><b>RESIDUAL YANG DITARGETKAN</b></td>
      </tr>
      <tr>
        <td><b>Kemungkinan</b></td>
        <td><?=$mtkemungkinanarr[$risiko['residual_target_kemungkinan']]?></td>
        <td><b>Dampak</b></td>
        <td><?=$mtdampakrisikoarr[$risiko['residual_target_dampak']]?></td>
        <td><b>Tingkat</b></td>
        <td><?=UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $risiko, false)?></td>
      </tr>
    </table>

    <div><b>PEMANTAUAN DAN PENINJAUAN</b></div>

    <table class="table">
      <tr>
        <td><b>Sasaran Strategis</b></td>
        <td><?=$risiko['nss']?></td>
        <td colspan="2"><b>RISIDUAL RISK HASIL EVALUASI</b></td>
      </tr>
      <tr>
        <td><b>KPI</b></td>
        <td><?=$risiko['kss']?></td>
        <td><b>Kemungkinan</b></td>
        <td><?=$mtkemungkinanarr[$risiko['residual_kemungkinan_evaluasi']]?></td>
      </tr>
      <tr>
        <td><b>Sasaran Kegiatan</b></td>
        <td><?=$risiko['nsk']?></td>
        <td><b>Dampak</b></td>
        <td><?=$mtdampakrisikoarr[$risiko['residual_dampak_evaluasi']]?></td>
      </tr>
      <tr>
        <td><b>KPI</b></td>
        <td><?=$risiko['ksk']?></td>
        <td><b>Kategori</b></td>
        <td><?=$risiko['nk']?></td>
      </tr>
      <tr>
        <td><b>Hambatan/Kendala</b></td>
        <td><?=$risiko['hambatan_kendala']?></td>
        <td><b>Tingkat</b></td>
        <td><?=UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $risiko, false)?></td>
      </tr>
      <tr>
        <td><b>Penyesuaian Tindakan Mitigasi</b></td>
        <td><?=$risiko['penyesuaian_tindakan_mitigasi']?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Progress Capaian Kinerja</b></td>
        <td><?=$risiko['progress_capaian_kinerja']?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Progress Capaian Sasaran</b></td>
        <td><?=$risiko['progress_capaian_sasaran']?></td>
        <td></td>
        <td></td>
      </tr>
    </table>
<?php
}
