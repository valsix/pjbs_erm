<?php
$modeheader1 = $mode;
if(!$editedheader1){
    $modeheader1 = 'detail';
}
$is_readmore_risiko= false;
if(!accessbystatus($rowheader1['id_status_pengajuan'], $page_ctrl) && $page_ctrl!='panelbackend/risk_risiko')
  $is_readmore_risiko = true;
?>
<div  class="header">
  <div class="pull-left">
      <h2>IDENTIFIKASI <?=strtoupper($label_risk)?> 

    </h2>

  </div>
  <div class="pull-right">
    <?php echo UI::showButtonMode('edit_detail',$row[$pk], $editedheader1)?>
  </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" id="body-risiko">
<div class="col-sm-6">

<?php /*
$from = UI::createSelect('id_aktifitas',$mtaktifitasarr,$row['id_aktifitas'],$edited,'form-control ',"style='width:100%;' data-tags='true'");
echo UI::createFormGroup($from, $rules["id_aktifitas"], "id_aktifitas", "Aktivitas");*/
?>

<?php
if ($rowheader1['tgl_risiko']) {
  $tgl_risiko = $rowheader1['tgl_risiko'];
}elseif(!$id){
  $tgl_risiko = date("d-m-Y");
}

if($this->access_role['view_all_direktorat'] && $editedheader1){
  $from = UI::createTextBox('tgl_risiko',$tgl_risiko,'','',$editedheader1,'form-control datepicker', "onchange='goSubmit(\"set_value\")'");
}else{
  $from = "<span class='read_detail'>".Eng2Ind($tgl_risiko)."</span>";
}

echo UI::createFormGroup($from, $rules["tgl_risiko"], "tgl_risiko", "Tgl.", false, 4, $editedheader1);
?>

<?php
if ($rowheader1['nomor']) {
  $no_risiko = $rowheader1['nomor'];
}

if($this->access_role['view_all_direktorat']){
  $from = UI::createTextBox('nomor',$no_risiko,'','',$editedheader1,'form-control ');
}else{
  $from = "<span class='read_detail'>".$no_risiko."</span>";
}

echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Kode", false, 4, $editedheader1);
?>

<?php
$from = UI::createTextArea('nama',$rowheader1['nama'],'','',$editedheader1,'form-control ');
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Risiko", false, 4, $editedheader1);
?>

<?php
$from = UI::createTextArea('deskripsi',$rowheader1['deskripsi'],'','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 4, $editedheader1);
?>

</div>
<div class="col-sm-6">

<?php
if($hambatan_kendala = $rowheader1['risiko_old']['hambatan_kendala'])
  $info_penyebab = UI::createInfo("info_penyebab","Hambatan & Kendala Sebelumnya", $hambatan_kendala);

$from = UI::createTextArea('penyebab',$rowheader1['penyebab'],'5','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["penyebab"], "penyebab", "Penyebab".$info_penyebab, false, 4, $editedheader1);
?>

<?php
$from = UI::createTextArea('dampak',$rowheader1['dampak'],'5','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Dampak", false, 4, $editedheader1);
?>

<?php /*
$form = UI::createSelectMultipleAutocomplate('id_jabatan[]',$mtsdmjabatanarr,$row['id_jabatan'],$editedheader1,'form-control select2',"style='width:100%' data-ajax--url=\"".site_url('panelbackend/ajax/listjabatandirektorat')."\"");
echo UI::createFormGroup($form, $rules["id_jabatan[]"], "id_jabatan[]", "Terkait Bidang Lain", false, 4);*/
?>
</div>
</div>
</div>

<div class="card">
<div  class="header disp-inline-block">
<h2>INHEREN RISK</h2>&nbsp;
<?=UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $rowheader1, $editedheader1); ?>
</div>

<div class="body table-responsive">

<?php
  include "_kriteria.php";
?>

<div class="form-horizontal">
  <div class="row" style="margin: 0 -15px;">
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('inheren_kemungkinan',$mtkemungkinanarr,$rowheader1['inheren_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroupPlain($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan', false, $editedheader1, "col-sm-5");
    ?>
  </div>
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('id_kriteria_kemungkinan',$kriteriakemungkinanarr,$rowheader1['id_kriteria_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
    if($rowheader1['id_kriteria_kemungkinan']==3){
      if($edited)
        $from .= "<span style='color:red'>*</span><br/>";

      foreach($files as $k=>$r){
        $from .= UI::InputFile(
        array(
          "nameid"=>"file",
          "edit"=>count($files)>1&&$edited,
          "nama_file"=>$r['client_name'],
          "url_preview"=>site_url("panelbackend/risk_control/preview_file/".$r['id_risiko_files']),
          "url_delete"=>site_url("panelbackend/risk_control/delete_file/".$row['id_risiko'].'/'.$r['id_risiko_files']),
          )
        );
      }

      if($edited){
        $from .= UI::InputFile(
        array(
          "nameid"=>"file",
          "edit"=>$edited,
          "extarr"=>explode("|",$configfile['allowed_types']."<br/> Ukuran Maksimal ".(round($configfile['max_size']/1000))." mb"),
          )
        );
      }
    }
    echo UI::createFormGroupPlain($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", 'Kriteria&nbsp;<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaKemungkinan"><span class="glyphicon glyphicon-info-sign"></span></button>', false, $editedheader1, "col-sm-5");
    ?>
  </div>
  </div>

  <div class="row" style="margin: 0 -15px;">
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('inheren_dampak',$mtdampakrisikoarr,$rowheader1['inheren_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroupPlain($from, $rules["inheren_dampak"], "inheren_dampak", 'Dampak', false, $editedheader1, "col-sm-5");
    ?>
  </div>
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('id_kriteria_dampak',$kriteriaarr,$rowheader1['id_kriteria_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroupPlain($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria&nbsp;<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaDampak"><span class="glyphicon glyphicon-info-sign"></span></button>', false, $editedheader1, "col-sm-5");
    ?>
    </div>
    </div>
</div>

<?php
if($editedheader1){?>
<div class="col-sm-12 col-btn-rating">
<?php
if(($scorecardarr)){
  $from = UI::createSelect('id_scorecard',$scorecardarr,$rowheader1['id_scorecard'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
  echo UI::createFormGroup($from, $rules["id_scorecard"], "id_scorecard", "Disposisi", true, 4, $editedheader1);
}
$from = UI::showButtonMode("save", null, $editedheader1);
echo UI::createFormGroup($from, NULL, NULL, NULL, TRUE, 4, $editedheader1);
?>
</div>
<?php } ?>
</div>

<div  class="footer">
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko']);?>
      </div>
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusRisiko($rowheader1['status_risiko'])?>
      </div>
    <div style="clear: both;"></div>
</div>

<script type="text/javascript">

<?php if($this->access_role['add']){ ?>
  function goAddRisiko(){
      window.location = "<?=base_url("panelbackend/risk_risiko/add/".$rowheader['id_scorecard'])?>";
  }
<?php } ?>

<?php if($this->access_role['edit']){ ?>
  function goEditRisiko(){
      window.location = "<?=base_url("panelbackend/risk_risiko/edit/".$rowheader['id_scorecard']."/".$rowheader1['id_risiko'])?>";
  }
<?php } ?>

<?php if($this->access_role['delete']){ ?>
  function goDeleteRisiko(){
      if(confirm("Apakah Anda yakin akan menghapus ?")){
          window.location = "<?=base_url("panelbackend/risk_risiko/delete/".$rowheader['id_scorecard']."/".$rowheader1['id_risiko'])?>";
      }
  }
<?php } ?>

  function goListRisiko(){
      window.location = "<?=base_url("panelbackend/risk_risiko/index/".$rowheader['id_scorecard'])?>";
  }
</script>
