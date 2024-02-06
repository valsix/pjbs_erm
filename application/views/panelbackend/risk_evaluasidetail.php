<div  class="header">
    <div class="pull-left">
    <h2>PEMANTAUAN & PENINJAUAN
    </h2>
    </div>
    <div class="pull-right">
    <?php echo UI::showButtonMode('edit_detail',$row[$pk], $edited)?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">
<div class="col-sm-6">
<?php
if($rowheader['id_nama_proses']){
  $from = UI::createTextBox('kode_aktifitas',$rowheader1['kode_aktifitas'],'','',false,'form-control ', "onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["kode_aktifitas"], "kode_aktifitas", "Kode Aktivitas", false, 4, false);
$from = UI::createTextArea('nama_aktifitas',$rowheader1['nama_aktifitas'],'','',false,'form-control ');
echo UI::createFormGroup($from, $rules["nama_aktifitas"], "nama_aktifitas", "Nama Aktivitas", false, 4, false);
}else{
$from = UI::createSelect('id_sasaran_strategis',$sasaranarr,$rowheader1['id_sasaran_strategis'],false,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_strategis\")'");
echo UI::createFormGroup($from, $rules["id_sasaran_strategis"], "id_sasaran_strategis", "Sasaran Strategis", false, 4, false);

if($rowheader['jenis_sasaran']=='2'){

$from = UI::createSelect('id_sasaran_kegiatan',$mtkegiatanarr,$rowheader1['id_sasaran_kegiatan'],false,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_kegiatan\")'");
echo UI::createFormGroup($from, $rules["id_sasaran_kegiatan"], "id_sasaran_kegiatan", "Sasaran Kegiatan", false, 4, false);

$from = "";
foreach ($rowheader1['kpi_kegiatan'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],false);
  $from .= "<br/>";
}

echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 4, false);

}else{

$from = "";
foreach ($rowheader1['kpi_strategis'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],false);
  $from .= "<br/>";
}

echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 4, false);

}
}
?>


<?php
$from = UI::createTextArea('nama',$row['nama'],'','',false,'form-control ');
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Risiko");
?>

<?php
if($rowheader['id_nama_proses']){
$from = "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::createSelect('control_kemungkinan_penurunan',$mtkemungkinanarr,$row['control_kemungkinan_penurunan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
$from .= "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::createSelect('control_dampak_penurunan',$mtdampakrisikoarr,$row['control_dampak_penurunan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
$from .= "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $row, false)."</div>";

echo UI::createFormGroup($from, $rules["nama"], "nama", "Current Risk");
  }else{
$from = "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::createSelect('residual_target_kemungkinan',$mtkemungkinanarr,$row['residual_target_kemungkinan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
$from .= "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::createSelect('residual_target_dampak',$mtdampakrisikoarr,$row['residual_target_dampak'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
$from .= "<div class='col-sm-4' style='padding-left: 0px !important;'>".UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $row, false)."</div>";

echo UI::createFormGroup($from, $rules["nama"], "nama", "Residual yang ditargetkan");
}
?>

<?php /*
$from = UI::createTextArea('progress_capaian_sasaran',$row['progress_capaian_sasaran'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["progress_capaian_sasaran"], "progress_capaian_sasaran", "Progress Capaian Sasaran");*/
?>
</div>
<div class="col-sm-6">

<?php
$from = UI::createTextArea('progress_capaian_kinerja',$row['progress_capaian_kinerja'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["progress_capaian_kinerja"], "progress_capaian_kinerja", "Progress Capaian Kinerja");
?>

<?php
$from = UI::createTextArea('hambatan_kendala',$row['hambatan_kendala'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["hambatan_kendala"], "hambatan_kendala", "Hambatan/Kendala");
?>

<?php
$from = UI::createTextArea('penyesuaian_tindakan_mitigasi',$row['penyesuaian_tindakan_mitigasi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["penyesuaian_tindakan_mitigasi"], "penyesuaian_tindakan_mitigasi", "Penyesuaian Tindakan Mitigasi");
?>
</div>

    <div style="clear: both;"></div>
</div>
</div>

<div class="card">
<div  class="header disp-inline-block">
<h2>RESIDUAL RISK HASIL EVALUASI</h2>&nbsp;
<?=UI::tingkatRisiko('residual_kemungkinan_evaluasi', 'residual_dampak_evaluasi', $rowheader1, $edited);?>
</div>
<div class="body table-responsive">

<?php
  include "_kriteria.php";
?>
<div class="form-horizontal">
  <div class="row" style="margin: 0 -15px;">
  <div class="col-sm-4">
<?php
$from = UI::createSelect('residual_kemungkinan_evaluasi',$mtkemungkinanarr,$rowheader1['residual_kemungkinan_evaluasi'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["residual_kemungkinan_evaluasi"], "residual_kemungkinan_evaluasi", 'Kemungkinan<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaKemungkinan"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $edited);
?>
</div>
</div>

  <div class="row" style="margin: 0 -15px;">

  <div class="col-sm-4">
<?php
$from = UI::createSelect('residual_dampak_evaluasi',$mtdampakrisikoarr,$rowheader1['residual_dampak_evaluasi'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["residual_dampak_evaluasi"], "residual_dampak_evaluasi", 'Dampak<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaDampak"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $edited);
?>
</div>
</div>

<?php
if($editedheader1){?>

  <div class="row" style="margin: 0 -15px;">
<div class="col-sm-4  col-btn-rating">
<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, NULL, NULL, NULL, false, 5, $edited);
?>
    </div>
</div>
<?php } ?>
</div>
</div>


<div  class="footer">
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko'],true);?>
      </div>
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusRisiko($rowheader1['status_risiko'],($rowheader1['residual_dampak_evaluasi'] && $rowheader1['residual_kemungkinan_evaluasi']))?>
      </div>
    <div style="clear: both;"></div>
</div>
