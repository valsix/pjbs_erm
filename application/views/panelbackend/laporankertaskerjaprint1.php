<div style="height:500px; width:auto; overflow-x:scroll ; overflow-y: scroll; padding-bottom:10px;">
<table class="tableku1" id="export">
  <thead>
    <tr>
      <th style="width:120px;text-align:center" rowspan="2">No</th>
      <th style="width:120px;text-align:center" rowspan="2">Sasaran Strategis</th>
      <th style="width:120px;text-align:center" rowspan="2">Sasaran / Fokus Unit</th>
      <th style="width:120px;text-align:center" colspan="3">Identifikasi Risiko</th>
      <th style="width:120px;text-align:center" rowspan="2">Risk Owner</th>
      <th style="width:120px;text-align:center" colspan="4">Risiko Inheren</th>
      <th style="width:120px;text-align:center" colspan="1">Kontrol Yang Telah Ada</th>
      <th style="width:120px;text-align:center" colspan="3">Risiko Paska Kontrol Existing</th>
      <th style="width:120px;text-align:center" colspan="4">Pencegahan ( Mitigasi )</th>
      <th style="width:120px;text-align:center" colspan="3">Risiko Residual ( Paska Mitigasi )</th>
      <th style="width:120px;text-align:center" rowspan="2">Mekanisme Pemantauan</th>
    </tr>
    <tr>
			<th style="width:120px;text-align:center">Risiko</th>
			<th style="width:120px;text-align:center">Penyebab</th>
			<th style="width:120px;text-align:center">Dampak</th>
      <th style="width:120px;text-align:center">Tingkat Kemungkinan</th>
      <th style="width:120px;text-align:center">Skala Dampak</th>
      <th style="width:120px;text-align:center">Kategori</th>
      <th style="width:120px;text-align:center">Level Risiko</th>
      <th style="width:200px;text-align:center">Pencegahan / Pemulihan</th>
      <th style="width:120px;text-align:center">Tingkat Kemungkinan</th>
      <th style="width:120px;text-align:center">Skala Dampak</th>
      <th style="width:120px;text-align:center">Level Risiko</th>
      <th style="width:120px;text-align:center">Program Mitigasi</th>
      <th style="width:200px;text-align:center">Biaya Mitigasi</th>
      <th style="width:200px;text-align:center">Penanggung Jawab Mitigasi</th>
      <th style="width:200px;text-align:center">Waktu Pelaksanaan</th>
      <th style="width:120px;text-align:center">Tingkat Kemungkinan</th>
      <th style="width:120px;text-align:center">Skala Dampak</th>
      <th style="width:120px;text-align:center">Level Risiko</th>
		</tr>
  </thead>
  <tbody>
  <?php
    $rs = $this->data['rows'];
    $no=1;
  //  dpr($rs);
    foreach($rs as $r => $val){
      echo "<tr>";
      echo "<td style='text-align:center'>".$no++."</td>";
      echo "<td>$val[sasaran_strategis]</td>";
      echo "<td>$val[sasaran_kegiatan]</td>";
      echo "<td>$val[risiko]</td>";
      echo "<td>$val[penyebab]</td>";
      echo "<td>$val[dampak]</td>";
      echo "<td>$val[risk_owner]</td>";
      echo "<td>$val[inheren_kemungkinan]</td>";
      echo "<td>$val[inheren_dampak]</td>";
      echo "<td>$val[kategori]</td>";
      echo "<td>$val[level_risiko_inheren]</td>";
      echo "<td>$val[nama_kontrol]</td>";
      echo "<td>$val[kemungkinan_paskakontrol]</td>";
      echo "<td>$val[dampak_paskakontrol]</td>";
      echo "<td>$val[level_risiko_paskakontrol]</td>";
      echo "<td>$val[nama_mitigasi]</td>";
      echo "<td>$val[biaya_mitigasi]</td>";
      echo "<td>$val[penanggungjawab_mitigasi]</td>";
      echo "<td>$val[waktu_pelaksanaan]</td>";
      echo "<td>$val[kemungkinan_paskamitigasi]</td>";
      echo "<td>$val[dampak_paskamitigasi]</td>";
      echo "<td>$val[level_risiko_paskamitigasi]</td>";
      echo "<td>$val[nama_action_plan]</td>";

      echo "</tr>";
    }
    if(!($rs)){
        echo "<tr><td colspan='8'>Data kosong</td></tr>";
    }
  ?>

  </tbody>
</table>
</div>
