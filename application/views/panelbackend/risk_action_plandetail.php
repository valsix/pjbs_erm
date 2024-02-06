<div  class="header">
    <div class="pull-left">
    <h2>MITIGASI</h2>
    </div>
    <div class="pull-right">
    <?php echo UI::showButtonMode($mode,$row[$pk])?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">
<div class="col-sm-6">

<?php
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Mitigasi");
?>

<?php
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php
$form = UI::createSelect('penanggung_jawab',$penanggung_jawabarr,$row['penanggung_jawab'],$edited,'form-control select2',"data-ajax--data-type=\"json\" data-ajax--url=\"".site_url('panelbackend/ajax/listpegawai')."\"");
echo UI::createFormGroup($form, $rules["penanggung_jawab"], "penanggung_jawab", "Penanggung Jawab");
?>

<?php
$from = UI::createTextBox('dead_line',$row['dead_line'],'10','10',$edited,'form-control datepicker',"");
echo UI::createFormGroup($from, $rules["dead_line"], "dead_line", "Dead Line");
?>

<?php
$from = UI::createTextBox('biaya',($edited?$row['biaya']:rupiah($row['biaya'])),'10','10',$edited,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya");
?>

<?php
$from = UI::createTextBox('cba',($edited?$row['cba']:rupiah($row['cba'])),'10','10',$edited,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["cba"], "cba", "Cost Benefit Analysis (CBA)");
?>

<?php
$from = UI::createTextBox('progress_capaian_kinerja',$row['progress_capaian_kinerja'],'200','100',$edited,'form-control ',"style='width:100%'");
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
<div class="col-sm-6">

<?php
$from = UI::createSelect('id_status_action_plan',$pregressarr,$row['id_status_action_plan'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_status_action_plan"], "id_status_action_plan", "Progress");
?>

<?php
$from = UI::createRadio('menurunkan_dampak_kemungkinan',$menurunkanrr,$row['menurunkan_dampak_kemungkinan'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["menurunkan_dampak_kemungkinan"], "menurunkan_dampak_kemungkinan", "Menurunkan ?");
?>

<?php
$from = UI::createStatusPengajuan('action_plan',$row['id_status'],$row['id_action_plan']);
echo UI::createFormGroup($from, $rules["id_status"], "id_status", "Status ");
?>

<?php
if($row['id_action_plan'] && $row['id_status']=='4'){
$pengajuan_efektif = Access("pengajuan_efektif");
$persetujuan_efektif = Access("persetujuan_efektif");
$edited_efektif = false;

if($pengajuan_efektif && ($row['is_efektif']==0 or $row['is_efektif']==2))
	$edited_efektif = true;

if($persetujuan_efektif && ($row['is_efektif']==0 or $row['is_efektif']==1 or $row['is_efektif']==2))
	$edited_efektif = true;

unset($mtefektifitasarr['']);

foreach ($mtefektifitasarr as $key => $value) {
	$from = UI::createCheckBox("efektif[$key]",1,$row['efektif'][$key],$value,$edited_efektif);
	echo UI::createFormGroup($from, $rules["efektif"], "efektif", null,true);
}

if($row['is_efektif']){
	$from = labelefektifitas($row['is_efektif']);
	echo UI::createFormGroup($from, $rules["efektif"], "efektif", null,true);
}

$form = "<br/>";

if($pengajuan_efektif && $edited_efektif && ($row['is_efektif']==0 or $row['is_efektif']==2)){
	$form .= " <a class='btn btn-warning' onclick='goSubmit(\"ajukan_efektifitas\")'>AJUKAN</a> ";
}

if($persetujuan_efektif && $edited_efektif && $row['is_efektif']=='1'){
	$form .= " <a class='btn btn-success' onclick='goSubmit(\"setujui_efektifitas\")'>SETUJUI</a> ";
	$form .= " <a class='btn btn-danger' onclick='goSubmit(\"tolak_efektifitas\")'>TOLAK</a> ";
}

echo UI::createFormGroup($form, null, null, null, true);

}
?>


<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>
