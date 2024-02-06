<?php $user_id = $_SESSION[SESSION_APP]['user_id']; ?>
<div  class="header">
  <div class="pull-left">
      <h2>SASARAN</h2>
  </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" id="body-risiko">
<div class="col-sm-12">

<?php

$from = UI::createSelect('id_sasaran_strategis',$sasaranarr,$rowheader1['id_sasaran_strategis'],$edited,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_strategis\")'");
echo UI::createFormGroup($from, $rules["id_sasaran_strategis"], "id_sasaran_strategis", "Sasaran Strategis", false, 2, $edited);

if($rowheader['jenis_sasaran']=='2'){ 

$from = UI::createSelect('id_sasaran_kegiatan',$mtkegiatanarr,$rowheader1['id_sasaran_kegiatan'],$edited,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_kegiatan\")'");

if($edited && $rowheader1['id_sasaran_strategis']){
  ;
  $from .= UI::getButton('add', $rowheader1['id_sasaran_kegiatan'], 'class="btn btn-xs btn-success"');
  if($mtkegiatanarr[$rowheader1['id_sasaran_kegiatan']]){
  $from .= UI::getButton('edit', $rowheader1['id_sasaran_kegiatan'], 'class="btn btn-xs btn-warning"');
  $from .= UI::getButton('delete', $rowheader1['id_sasaran_kegiatan'], 'class="btn btn-xs btn-danger"');
}
}

echo UI::createFormGroup($from, $rules["id_sasaran_kegiatan"], "id_sasaran_kegiatan", "Sasaran Kegiatan", false, 2, $edited);

$from = "";
foreach ($rowheader1['kpi_kegiatan'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],$edited,null,"onclick='goSubmit(\"set_value\")'");
  $from .= "<br/>";
}
/*if($rowheader['id_status_proyek']){
  $from .= "<table>";
  $from .= "<tr><td><b>On Time</b></td><td>".UI::createTextBox('deskripsi_kpi',$row['deskripsi_kpi'],'200','100',$edited,'form-control ',"style='width:200px'")."</td></tr>";
  $from .= "<tr><td><b>On Spec</b></td><td>".UI::createTextBox('deskripsi_kpi',$row['deskripsi_kpi'],'200','100',$edited,'form-control ',"style='width:200px'")."</td></tr>";
  $from .= "<tr><td><b>On Cost</b></td><td>".UI::createTextBox('deskripsi_kpi',$row['deskripsi_kpi'],'200','100',$edited,'form-control ',"style='width:200px'")."</td></tr>";
  $from .= "<tr><td><b>On Safety</b></td><td>".UI::createTextBox('deskripsi_kpi',$row['deskripsi_kpi'],'200','100',$edited,'form-control ',"style='width:200px'")."</td></tr>";
  $from .= "</table>";
}*/

echo UI::createFormGroup($from, null, "kpi_kegiatan", "KPI", false, 2, $edited);

if($rowheader1['id_sasaran_kegiatan'] && $rowheader1['id_sasaran_kegiatan'] && ($rowheader1['id_kpi']) && $edited){
  $from = '<a class="btn waves-effect btn-lg btn-success" href="'.base_url("panelbackend/risk_risiko/add/".$rowheader['id_scorecard']."/".$rowheader1['id_sasaran_strategis']."/".$rowheader1['id_sasaran_kegiatan'])."/".implode("-",$rowheader1['id_kpi']).'">NEXT NEW '.strtoupper($label_risk).' <span class="glyphicon glyphicon-forward"></span></a> ';

  echo UI::createFormGroup($from, null, null, null, false, 2, $edited);
}


}else{

$from = "";
foreach ($rowheader1['kpi_strategis'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],$edited,null,"onclick='goSubmit(\"set_value\")'");
  $from .= "<br/>";
}

echo UI::createFormGroup($from, null, "kpi_kegiatan", "KPI", false, 2, $edited);

if($rowheader1['id_sasaran_strategis'] && ($rowheader1['id_kpi']) && $edited){
  $from = '<a class="btn waves-effect btn-lg btn-success" href="'.base_url("panelbackend/risk_risiko/add/".$rowheader['id_scorecard']."/".$rowheader1['id_sasaran_strategis'])."/0/".implode("-",$rowheader1['id_kpi']).'">NEXT NEW '.strtoupper($label_risk).' <span class="glyphicon glyphicon-forward"></span></a> ';

  echo UI::createFormGroup($from, null, null, null, false, 2, $edited);
}

} ?>
</div>
<div style="clear: both;"></div>
</div>

<?php if($rowheader1){ ?>
<div  class="footer">
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko']);?>
      </div>
	  
	  
	  <?//modif dewangga 07-09-2023 agar user review tidak bisa download lampiran
	if($user_id=2547){}else{?>
		  <div class="col-sm-6 footer-info">
		<?=UI::createStatusRisiko($rowheader1['status_risiko'])?>
		  </div>
	<?}?>
	  

    <div style="clear: both;"></div>
</div>
<?php } ?>