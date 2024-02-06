<div  class="header">
  <div class="pull-left">
      <h2><?=strtoupper($page_title)?></h2>
  </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" id="body-risiko">

<div class="col-sm-12">

<?php
$from = UI::createSelect('id_sasaran_strategis',$sasaranarr,$row['id_sasaran_strategis'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_sasaran_strategis"], "id_sasaran_strategis", "Sasaran Strategis", false, 2, $edited);
?>


<?php 
$from = UI::createSelectMultiple('id_kpi[]',$riskkpiarr,$row['id_kpi'],$edited,'form-control select2',"style='width:auto; width:100%;' data-tags='true'");
echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 2);
?>

<?php
/*$from = UI::createTextBox('kpi',$row['kpi'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["kpi"], "kpi", "KPI", false, 2);*/
?>

<?php
$from = UI::createTextBox('deskripsi_kpi',$row['deskripsi_kpi'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["deskripsi_kpi"], "deskripsi_kpi", "Deskripsi KPI", false, 2);
?>

<?php
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["name"], "nama", "Nama Sasaran Kegiatan", false, 2);
?>

<?php
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi Sasaran Kegiatan",false,2);
?>

<?php
$from = UI::showButtonMode("save_back", null, $edited);
echo UI::createFormGroup($from, null,null,null,null,2);
?>
</div>
    <div style="clear: both;"></div>
</div>