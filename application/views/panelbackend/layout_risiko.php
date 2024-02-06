<?php ob_start();?>

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

  <?php if($is_readmore_risiko){ ?>
  <div class="col-lg-8">
    <h2>
     RISIKO : <?=$rowheader1['nomor']?> | <?=$rowheader1['nama']?>
     &nbsp;&nbsp;<a href="javascript:void(0);" onclick="$('#body-risiko').slideToggle(200)"><span class="glyphicon glyphicon-tags"  style="font-size: 14px;"></span></a>
    </h2>
    <small>Tingkat risiko saat ini </small> <?=UI::tingkatRisiko('current_risk_kemungkinan', 'current_risk_dampak', $rowheader1, false, false)?>
  </div>
  <div class="col-lg-4">
  <?php }else{ ?>
  <div class="pull-left">
      <h2>IDENTIFIKASI RISIKO</h2>
  </div>
  <?php } ?>
  <div class="pull-right">
    <?php
    if($editedheader1)
      include "_kriteria.php";
    ?>
    <?php echo UI::showButtonModeRisiko($modeheader1, $rowheader1['id_risiko'], $editedheader1, null, ($is_readmore_risiko?"btn-plain":null), $this->access_role_custom['panelbackend/risk_risiko'])?>

  </div>
  <?php if($is_readmore_risiko){ ?>
  </div>
  <?php } ?>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" id="body-risiko" <?php if($is_readmore_risiko){ ?> style="display:none" <?php } ?>>
<div class="col-sm-5">

<?php
$from = UI::createSelect('id_sasaran_strategis',$sasaranarr,$rowheader1['id_sasaran_strategis'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_sasaran_strategis"], "id_sasaran_strategis", "Sasaran Strategis", false, 3, $editedheader1);
?>

<?php
$from = UI::createSelect('id_sasaran_kegiatan',$mtkegiatanarr,$rowheader1['id_sasaran_kegiatan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_sasaran_kegiatan"], "id_sasaran_kegiatan", "Sasaran Kegiatan", false, 3, $editedheader1);
?>

<?php
if ($rowheader1['nomor']) {
  $from = $rowheader1['nomor'];
} else {
  $from = $no_risiko;
}
echo UI::createFormGroup($from, $rules["nomor"], "nomor", "Nomor Risiko", false, 3, $editedheader1);
?>

<?php
$from = UI::createTextBox('nama',$rowheader1['nama'],'200','100',$editedheader1,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Risiko", false, 3, $editedheader1);
?>

<?php
$from = UI::createTextArea('deskripsi',$rowheader1['deskripsi'],'','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 3, $editedheader1);
?>

<?php
$from = UI::createTextArea('karena',$rowheader1['karena'],'','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["karena"], "karena", "Penyebab", false, 3, $editedheader1);
?>

<?php
$from = UI::createTextArea('konsekuensi',$rowheader1['konsekuensi'],'','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["konsekuensi"], "konsekuensi", "Dampak", false, 3, $editedheader1);
?>

<?php
$from = UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko']);
echo UI::createFormGroup($from, $rules["id_status_pengajuan"], "risiko", "Status", false, 3, $editedheader1);
?>


</div>
<div class="col-sm-7">
<table width="100%" class="table borderless form-table">
  <thead>
    <tr>
      <th width="120"></th>
      <th width="149">Kemungkinan</th>
      <th width="173">Dampak</th>
      <th>Tingkat&nbsp;Risiko</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="table-label"><?php if($editedheader1){ ?><span style="color:#dd4b39">*</span><?php } ?> Risiko Inheren</td>
      <td>

        <?=UI::createSelect('inheren_kemungkinan',$mtkemungkinanarr,$rowheader1['inheren_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?>

        <?php
        $name = 'inheren_kemungkinan';
        if($editedheader1){
            $form_error = form_error($name);
            echo '
          <span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
          '.$form_error.'
          </span>';
        }
        ?>

      </td>
      <td>

        <?=UI::createSelect('inheren_dampak',$mtdampakrisikoarr,$rowheader1['inheren_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?>

        <?php
        $name = 'inheren_dampak';
        if($editedheader1){
            $form_error = form_error($name);
            echo '
          <span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
          '.$form_error.'
          </span>';
        }
        ?>

      </td>
      <td><?=UI::tingkatRisiko('inheren_kemungkinan', 'inheren_dampak', $rowheader1, $editedheader1)?></td>
    </tr>
    <tr>
      <td class="table-label"><?php if($editedheader1){ ?><span style="color:#dd4b39">*</span><?php } ?> Risiko Residual Yang Ditargetkan</td>
      <td>

        <?=UI::createSelect('residual_target_kemungkinan',$mtkemungkinanarr,$rowheader1['residual_target_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?>

        <?php
        $name = 'residual_target_kemungkinan';
        if($editedheader1){
            $form_error = form_error($name);
            echo '
          <span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
          '.$form_error.'
          </span>';
        }
        ?>

      </td>
      <td>

        <?=UI::createSelect('residual_target_dampak',$mtdampakrisikoarr,$rowheader1['residual_target_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?>

        <?php
        $name = 'residual_target_dampak';
        if($editedheader1){
            $form_error = form_error($name);
            echo '
          <span style="color:#dd4b39; font-size:11px; '.(($form_error)?'':'display: none').'" id="info_'.$name.'">
          '.$form_error.'
          </span>';
        }
        ?>

      </td>
      <td><?=UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $rowheader1, $editedheader1)?></td>
    </tr>
    <tr>
      <td class="table-label">Risiko Paska Kontrol</td>
      <td><?=UI::createSelect('control_kemungkinan_penurunan',$mtkemungkinanarr,$rowheader1['control_kemungkinan_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::createSelect('control_dampak_penurunan',$mtdampakrisikoarr,$rowheader1['control_dampak_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $rowheader1, $editedheader1)?></td>
    </tr>
    <tr>
      <td class="table-label">Risiko Paska Mitigasi</td>
      <td><?=UI::createSelect('mitigasi_kemungkinan_penurunan',$mtkemungkinanarr,$rowheader1['mitigasi_kemungkinan_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::createSelect('mitigasi_dampak_penurunan',$mtdampakrisikoarr,$rowheader1['mitigasi_dampak_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::tingkatRisiko('mitigasi_kemungkinan_penurunan', 'mitigasi_dampak_penurunan', $rowheader1, $editedheader1)?></td>
    </tr>
    <tr>
      <td class="table-label">Current Risk</td>
      <td><?=UI::createSelect('current_risk_kemungkinan',$mtkemungkinanarr,$rowheader1['current_risk_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::createSelect('current_risk_dampak',$mtdampakrisikoarr,$rowheader1['current_risk_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");?></td>
      <td><?=UI::tingkatRisiko('current_risk_kemungkinan', 'current_risk_dampak', $rowheader1, $editedheader1)?></td>
    </tr>
  </tbody>
</table>
<?php
$from = UI::showButtonMode("save", null, $editedheader1,null,null,$access_role_risiko);
echo UI::createFormGroup($from);
?>
</div>
</div>
<div style="clear: both;"></div>

<?php //if($rowheader1['id_risiko'] && $rowheader1['id_status_pengajuan']==4){ ?>
    <?=$this->auth->GetTabRisiko($mode, $rowheader1['id_risiko']);?>
    <div class="tab-content">
        <div class="tab-pane active">
            <?php echo $content1;?>
  <div style="clear: both;"></div>
        </div>
    </div>
<?php //} ?>

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

<?php
$content1=ob_get_contents();
ob_end_clean();
include "layout_scorecard.php";
?>
