<?php //$user_id = $_SESSION[SESSION_APP]['user_id']; ?>
<div  class="header">
    <div class="pull-left">
    <h2>MITIGASI RISIKO
    </h2>

        <?=labelverified($row);?>
    </div>
    <div class="pull-right">
    <?php 
    	echo UI::showButtonMode($mode,$row[$pk]);
    ?>
    <?php 
    if($this->access_role_custom['panelbackend/risk_risiko_grc']['view_all_direktorat'] && $row['is_lock']=='1' && $this->access_role['edit']){
    ?>
    <button type="button" class="btn waves-effect btn-warning" onclick="goSubmitValue('unlock',<?=$row[$pk]?>)" ><span class="glyphicon glyphicon-lock"></span> Unlock</button>
    <?php
    }
    ?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">
<div class="col-sm-6">

<?php
$from = UI::createTextBox('no',$row['no'],'','',$is_allow_edit_mitigasi,'form-control ',"style='width:80px'");
echo UI::createFormGroup($from, $rules["no"], "no", "No.");

if(!$is_peluang){
$from = null;
if($edited){
  
  ?>
<div class="modal fade" id="dampakpop" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Referensi Mitigasi</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered no-margin table-hover table-risiko">
          <tr>
            <th>Mitigasi</th>
            <th width="10px"></th>
          </tr>
          <?php
            if($taksonomirr)
            foreach($taksonomirr as $k=>$r){
              echo "<tr>
                <td>$r[text]</td>
                <td><button type='button' class='btn btn-xs btn-primary' onclick='$(\"#id_taksonomi_mitigasi\").val($r[id]); $(\"#nama\").val(\"$r[text]\"); $(\"#dampakpop\").modal(\"toggle\");'>Pilih</button></td>
              </tr>";
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function openMitigasi(){
    $("#dampakpop").modal("toggle");
  }
</script>
<?php
if(count($taksonomirr)){
  $from = "<button type='button' class='btn btn-primary btn-xs' onclick='openMitigasi()'>Referensi Mitigasi</button>";
}
}
$from .= UI::createTextArea('nama',$row['nama'],'','',$is_allow_edit_mitigasi,'form-control myautocomplate',
"style='width:100%' 
placeholder='Isian nama aktifitas mitigasi'
data-cb='var cb = function(r){
  $(\"#id_taksonomi_mitigasi\").val(r.id);
}'
data-arr='".
json_encode(array_values($taksonomirr))
."'");
$from .= UI::createTextHidden("id_taksonomi_mitigasi",$row['id_taksonomi_mitigasi'], $edited);
echo UI::createFormGroup($from, $rules["nama"], "nama", "Aktivitas Mitigasi");
}else{
$from = UI::createTextArea('nama',$row['nama'],'','',$is_allow_edit_mitigasi,'form-control',
"style='width:100%' 
placeholder='Isian nama tindak lanjut'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Aktivitas Tindak Lanjut");
}
?>

<?php
if($is_allow_edit_penanggung_jawab)
	$info_interdependent = UI::createInfo("info_interdependent","Keterangan", "Centang apabila penanggung jawab mitigasi berhubungan dengan devisi lain.");

$from = $info_interdependent.UI::createCheckBox('interdependent',1,$row['interdependent'],'Interdependent',$is_allow_edit_penanggung_jawab, '', "onclick='goSubmit(\"set_value\")'");

if($row['interdependent']){
	$from .= UI::createSelect('penanggung_jawab',$penanggung_jawabarr,$row['penanggung_jawab'],$is_allow_edit_penanggung_jawab,'form-control select2','form-control select2');
}else{
	$from .= UI::createSelect('penanggung_jawab',$penanggung_jawabarr,$rowheader['owner'],false,'form-control select2','form-control select2');
	$from .= UI::createTextHidden('penanggung_jawab',$rowheader['owner']);
}

echo UI::createFormGroup($from, $rules["penanggung_jawab"], "penanggung_jawab", "Penanggung Jawab");
?>

<?php
if(!$is_peluang){
$from = UI::createRadio('menurunkan_dampak_kemungkinan',$menurunkanrr,$row['menurunkan_dampak_kemungkinan'],$is_allow_edit_mitigasi);
echo UI::createFormGroup($from, $rules["menurunkan_dampak_kemungkinan"], "menurunkan_dampak_kemungkinan", "K / D ?");
}
?>

<?php
$from = UI::createTextBox('dead_line',$row['dead_line'],'10','10',$is_allow_edit_mitigasi,'form-control datepicker',"");
echo UI::createFormGroup($from, $rules["dead_line"], "dead_line", "Dead Line");
?>
</div>
<div class="col-sm-6">
<?php
if(!$is_peluang){ 
if ($rowheader1['rating_kemungkinancr'] && $rowheader1['rating_dampakcr']) {
  $nilai_cr = $rowheader1['rating_kemungkinancr']*$rowheader1['rating_dampakcr'];
  $from = '<span class=" rupiah read_detail">'.(double)$nilai_cr.'</span>';
} else {
  $from = '<span class=" rupiah read_detail">'."<i>diisi otomatis oleh sistem</i>".'</span>';
}
echo UI::createFormGroup($from, $rules["rating"], "rating", "Nilai Rating Current Risk");
?>

<?php
/*if(!$row['revenue'])
  $row['revenue'] = $revenue;*/

$from = UI::createTextBox('revenue',$row['revenue'],'10','10',$is_allow_edit_mitigasi,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["revenue"], "revenue", "Dasar Perhitungan Dampak Finansial");
?>

<?php
if ($rowheader1['rating_tingkatrisikors'] && $rowheader1['rating_dampakrisikors']) {
  $nilai_rr = $rowheader1['rating_tingkatrisikors']*$rowheader1['rating_dampakrisikors'];
  $from = '<span class=" rupiah read_detail">'.(double)$nilai_rr.'</span>';
} else {
  $from = '<span class=" rupiah read_detail">'."<i>diisi otomatis oleh sistem</i>".'</span>';
}

echo UI::createFormGroup($from, $rules["rating"], "numeric", "Nilai Rating Risiko Residual Yang Di Targetkan");
?>

<?php
$from = UI::createTextBox('biaya',($is_allow_edit_mitigasi?$row['biaya']:rupiah($row['biaya'])),'10','10',$is_allow_edit_mitigasi,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya Mitigasi");
}else{
$from = UI::createTextBox('biaya',($is_allow_edit_mitigasi?$row['biaya']:rupiah($row['biaya'])),'10','10',$is_allow_edit_mitigasi,'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya Tindak Lanjut");
}
?>

<?php
if ($row['cba']) {
	$info_cba = UI::createInfo("info_cba","Info CBA (Cost Baseline Analisis)", "<ul style='padding-left: 15px;'><li>CBA digunakan untuk acuan mitigasi yang dilaksanakan layak atau tidak.</li><li>Apabila CBA Ratio > 100% berarti penanganan risiko tersebut memiliki manfaat lebih besar daripada biaya sehingga layak untuk diterapkan.</li></ol>");

  $from = '<span class=" rupiah read_detail">'.(double)$row['cba'].'%'.'</span>';
	echo UI::createFormGroup($from, $rules["cba"], "cba", "Cost Baseline Analisis".$info_cba);
}

?>

<?php
$from = UI::createSelect('id_status_progress',$pregressarr,$row['id_status_progress'],$is_allow_edit_progress,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_status_progress"], "id_status_progress", "Progress");
?>

<?php




		$from = UI::createUploadMultiple('file', $row['file'], $page_ctrl, $is_allow_edit_progress);
echo UI::createFormGroup($from, $rules["file"], "file", "File Lampiran Progress");
?>
<?php

if(!$is_peluang){
  if ($mode!='add') 
  {
    if($this->access_role['review'] or $row['review_kepatuhan']){
      $from = UI::createTextArea('review_kepatuhan',$row['review_kepatuhan'],'','',($this->access_role['review'] && $edited),'form-control ',"style='width:100%'");
      echo UI::createFormGroup($from, $rules["review_kepatuhan"], "review_kepatuhan", "Review Kepatuhan");
    }

    if($row['rekomendasi_keterangan'] or $this->access_role['rekomendasi']){
      $from = UI::createTextArea('rekomendasi_keterangan',$row['rekomendasi_keterangan'],'','',$edited && $this->access_role['rekomendasi'],'form-control',"");

      $from .= UI::createUploadMultiple('filerekomendasi', $row['filerekomendasi'], $page_ctrl, $edited && $this->access_role['rekomendasi']);

      echo UI::createFormGroup($from, $rules["rekomendasi_keterangan"], "rekomendasi_keterangan", "Dasar Penetapan Mitigasi");
    }
  }
}
$editedapprove = false;
if($is_allow_edit_mitigasi && $row['status_konfirmasi']==0 && $_SESSION[SESSION_APP]['id_jabatan']==$row['penanggung_jawab'] && $rowheader1['id_status_pengajuan']=='6'){
	$editedapprove = true;
}


$from = "<div style='font-size:13px;'>".UI::createKonfirmasi($row['id_mitigasi'],$task_mitigasi, $row['status_konfirmasi'], $editedapprove)."</div>";

echo UI::createFormGroup($from);

if(!$editedapprove){
	if($this->access_role['review']){

	  if($edited){
	    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review\')"><span class="glyphicon glyphicon-floppy-save"></span> Save Rekomendasi</button>';
	    echo UI::createFormGroup($from);
	  }elseif(!$edited && $row['is_lock']=='1' && $row['review_is_verified']=='3'){
	    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
	    echo UI::createFormGroup($from);
	  }

	}elseif($this->access_role['rekomendasi']){

	  if($edited && $row['filerekomendasi']){
	    $from = UI::showButtonMode("save", null, $edited);
	    echo UI::createFormGroup($from, $edited);
	  }elseif(!$edited && $row['is_lock']=='1' && $row['rekomendasi_is_verified']=='3'){
	    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_rekomendasi_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
	    echo UI::createFormGroup($from);
	  }

	}elseif(!$edited && $_SESSION[SESSION_APP]['pic']==$rowheader['owner'] && $row['is_lock']=='2'){

	  if($row['rekomendasi_is_verified']=='2'){
	    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_rekomendasi\")'>Kirim ke $row[rekomendasi_group]  <span class='glyphicon glyphicon-forward'></span></button>";
	  }
	  if($row['review_is_verified']=='2'){
	    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_review\")'>Kirim ke $row[review_group]  <span class='glyphicon glyphicon-forward'></span></button>";
	  }
	  echo UI::createFormGroup($from);

	}elseif($edited){

	  $from = UI::showButtonMode("save", null, $edited);
	  echo UI::createFormGroup($from, $edited);

	}
}

?>
</div>
    <div style="clear: both;"></div>
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