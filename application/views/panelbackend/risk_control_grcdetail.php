<?php
//$user_id = $_SESSION[SESSION_APP]['user_id'];
?>
<div  class="header">
    <div class="pull-left">
    <h2>ANALISIS RISIKO
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
$from = UI::createTextBox('no',$row['no'],'','',($edited && $is_allow_edit_control),'form-control ',"style='width:80px'");
echo UI::createFormGroup($from, $rules["no"], "no", "No.");
?>

<?php
$from = null;
if($edited){
  ?>
<div class="modal fade" id="dampakpop" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Referensi Control</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered no-margin table-hover table-risiko">
          <tr>
            <th>Control</th>
            <th width="10px"></th>
          </tr>
          <?php
            if($taksonomirr)
            foreach($taksonomirr as $k=>$r){
              echo "<tr>
                <td>$r[text]</td>
                <td><button type='button' class='btn btn-xs btn-primary' onclick='$(\"#id_taksonomi_control\").val($r[id]); $(\"#nama\").val(\"$r[text]\"); $(\"#dampakpop\").modal(\"toggle\");'>Pilih</button></td>
              </tr>";
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function openControl(){
    $("#dampakpop").modal("toggle");
  }
</script>
<?php
if($taksonomirr){
  
  
  $from = "<button type='button' class='btn btn-primary btn-xs' onclick='openControl()'>Referensi Control</button>";
}
}
$from .= UI::createTextArea('nama',$row['nama'],'','',($edited && $is_allow_edit_control),'form-control myautocomplate',
"style='width:100%' 
placeholder='Isian nama aktifitas kontrol'
data-cb='var cb = function(r){
  $(\"#id_taksonomi_control\").val(r.id);
}'
data-arr='".
json_encode(array_values($taksonomirr))
."'");
$from .= UI::createTextHidden("id_taksonomi_control",$row['id_taksonomi_control'], ($edited && $is_allow_edit_control));
echo UI::createFormGroup($from, $rules["nama"], "nama", "Aktivitas Kontrol");
?>

<?php
$is_allow_edit_penanggung_jawab = $is_allow_edit_penanggung_jawab && ($edited && $is_allow_edit_control);
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
$from = UI::createTextBox('biaya',(($edited && $is_allow_edit_control)?$row['biaya']:rupiah($row['biaya'])),'10','10',($edited && $is_allow_edit_control),'form-control rupiah',"style='text-align:right'");
echo UI::createFormGroup($from, $rules["biaya"], "biaya", "Biaya Kontrol");
?>


<?php
$from = UI::createTextArea('remark',$row['remark'],'','',($edited && $is_allow_edit_control),'form-control',"");
echo UI::createFormGroup($from, $rules["remark"], "remark", "Keterangan");
?>

<?php
/*$from = UI::createSelect('id_control_parent',$mtcontrolarr,$row['id_control_parent'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_control_parent"], "id_control_parent", "Sub Dari");*/
?>

<?php
echo UI::createTextHidden("id_interval_before",$row['id_interval'],$edited);
$from = UI::createSelect('id_interval',$mtintervalarr,$row['id_interval'],($edited && $is_allow_edit_control),'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["id_interval"], "id_interval", "Interval");

?>

<?php
$from = UI::createRadio('menurunkan_dampak_kemungkinan',$menurunkanrr,$row['menurunkan_dampak_kemungkinan'],($edited && $is_allow_edit_control));
echo UI::createFormGroup($from, $rules["menurunkan_dampak_kemungkinan"], "menurunkan_dampak_kemungkinan", "K / D ?");
?>

<?php
if($row['id_mitigasi_sumber']){
	$from = '<a href="'.site_url('panelbackend/risk_mitigasi_grc/detail/'.$rowheader1['id_risiko'].'/'.$row['id_mitigasi_sumber']).'"><span class="label label-primary">DARI MITIGASI</span></a>';
	echo UI::createFormGroup($from, null, null, null);
}
?>
</div>
<div class="col-sm-6">
<?php
$row['is_efektif'] = 1;
foreach ($mtefektifitasarr as $key => $value) {
	
	//print_r($rowheader1);
	
	$add = "";
		
	if($value['need_explanation'] or $value['need_lampiran']){
		$add = "onclick='goSubmit(\"set_val\")'";
	}

	$from = "<b>".$value["nama"]."</b><br/>".UI::createRadio("efektif[$value[id_efektifitas]][is_iya]",array('0'=>'Tidak','1'=>'Ya'),(int)$row['efektif'][$value['id_efektifitas']]['is_iya'],$edited,false, 'form-control', $add);

  if(!(int)$row['efektif'][$value['id_efektifitas']]['is_iya'])
    $row['is_efektif'] = 0;

	if ($value['need_explanation'] && (int)$row['efektif'][$value['id_efektifitas']]['is_iya'])
	{
		$from .= "<div style='clear:both'></div><b>Penjelasan</b>";
		if($edited)
			$from .= "<span style='color:red'>*</span>";

		$from .= UI::createTextArea("efektif[$value[id_efektifitas]][keterangan]",$row['efektif'][$value['id_efektifitas']]['keterangan'],'3','',$edited,'form-control',"placeholder='penjelasan...'");

		if($edited){
			$form_error = $_SESSION[SESSION_APP]['efektif'][$value['id_efektifitas']]['keterangan'];

			if($form_error){
				$from .= '<span style="color:#dd4b39; font-size:11px;">'.$form_error."</span>";
			}
		}
	}

	if ($value['need_lampiran'] && (int)$row['efektif'][$value['id_efektifitas']]['is_iya']) {

    $from .= "<div style='clear:both'></div><b>Lampiran</b>";

    $from .= "<table border='1' class='tbefektif' width='100%'>";
    foreach($periodearr as $periode=>$v){
      $periode = $v['periode'];
      $label = $v['label'];
      $from .= "<tr><td>$label &nbsp;</td><td>";
	
		  $from .= UI::createUploadMultiple('file['.$value['id_efektifitas'].']['.$periode.']', $row['file'][$value['id_efektifitas']][$periode], $page_ctrl, $is_allow_edit_lampiran&&$v['is_edited']);
      $from .= "</td></tr>";
    }
    $from .= "</table>";


    if($row['no_lampiran'])
      $from .= '<label class="label label-danger">Silahkan upload lampiran diperiode saat ini</label><br/>';
    
    if($row['id_control'])
      //$from .= "<a href='#tblampiran' data-toggle=\"modal\" rel=\"tooltip\" data-target=\"#tblampiran\" onclick=\"reqlampiran(".$row['id_control'].','.$value['id_efektifitas'].")\">semua lampiran</a>";
													// $risiko['id_risiko_sebelum'] $c['id_control'] $r['id_efektifitas']

	$from .= "<a href='#tblampiran' data-toggle='modal' data-target='#tblampiran' onclick='reqlampiran(".$rowheader1['id_risiko'].",".$row['id_control'].",".$value['id_efektifitas'].")'>Semua Lampiran </a>";
  }

	echo UI::createFormGroup($from, $rules["efektif"], "efektif", null,true);
	//print_r($rules);
}

$from = labelefektifitas($row['is_efektif']);
echo UI::createFormGroup($from."<br/><br/>", $rules["efektif"], "efektif", null,true);

$editedapprove = false;
if($is_allow_edit_control && $row['status_konfirmasi']==0 && $_SESSION[SESSION_APP]['id_jabatan']==$row['penanggung_jawab'] && $rowheader1['id_status_pengajuan']=='6'){
  $editedapprove = true;
}
$from = "<div style='font-size:13px;'>".UI::createKonfirmasi($row['id_control'],$task_control, $row['status_konfirmasi'], $editedapprove, true)."</div>";

echo UI::createFormGroup($from, null, null, null, true);

if(!$editedapprove){
$from="";
if ($mode!='add') 
  {
    if($this->access_role['review'] or $row['review_kepatuhan']){
      $from = UI::createTextArea('review_kepatuhan',$row['review_kepatuhan'],'','',($this->access_role['review'] && $edited),'form-control ',"style='width:100%'");
      echo UI::createFormGroup($from, $rules["review_kepatuhan"], "review_kepatuhan", "Review Kepatuhan", true);
    }

    if($row['rekomendasi_keterangan'] or $this->access_role['rekomendasi']){
      $from = UI::createTextArea('rekomendasi_keterangan',$row['rekomendasi_keterangan'],'','',$edited && $this->access_role['rekomendasi'],'form-control',"");


      $from .= UI::createUploadMultiple('filerekomendasi', $row['filerekomendasi'], $page_ctrl, $edited && $this->access_role['rekomendasi']);

      echo UI::createFormGroup($from, $rules["rekomendasi_keterangan"], "rekomendasi_keterangan", "Dasar Rekomendasi", true);
    }
  }

if($this->access_role['review']){

  if($edited){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review\')"><span class="glyphicon glyphicon-floppy-save"></span> Save Rekomendasi</button>';
  echo UI::createFormGroup($from, null, null, null, true);
  }elseif(!$edited && $row['is_lock']=='1' && $row['review_is_verified']=='3'){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
  echo UI::createFormGroup($from, null, null, null, true);
  }

}elseif($this->access_role['rekomendasi']){

  if($edited && $row['filerekomendasi']){
    $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from, null, null, null, true);
  }elseif(!$edited && $row['is_lock']=='1' && $row['rekomendasi_is_verified']=='3'){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_rekomendasi_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
  echo UI::createFormGroup($from, null, null, null, true);
  }

}elseif(!$edited && $_SESSION[SESSION_APP]['pic']==$rowheader['owner']/* && $row['is_lock']=='2'*/){

  if($row['rekomendasi_is_verified']=='2'){
    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_rekomendasi\")'>Kirim ke $row[rekomendasi_group]  <span class='glyphicon glyphicon-forward'></span></button>";
  }
  if($row['review_is_verified']=='2'){
    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_review\")'>Kirim ke $row[review_group]  <span class='glyphicon glyphicon-forward'></span></button>";
  }
  echo UI::createFormGroup($from, null, null, null, true);

}elseif($edited){

  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from, null, null, null, true);

}
}
?>

<?php if($row['id_control']){ ?>
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


<?php } ?>

</div>
    <div style="clear: both;"></div>
</div>
<div  class="footer">
      <div class="col-sm-6" style="padding: 0px; font-size: 13px; margin-bottom: 10px">
    <?=UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko']);?>
      </div>
	<div class="col-sm-6" style="padding: 0px; font-size: 13px; margin-bottom: 10px">
    <?=UI::createStatusRisiko($rowheader1['status_risiko'])?>
	</div>
    <div style="clear: both;"></div>
</div>

<script type="text/javascript">
function checkMe() {
  if (confirm("Are you sure")) {
      alert("Clicked Ok");
      return true;
  } else {
      alert("Clicked Cancel");
      return false;
  }
}
</script>
<style>
  .tbefektif td{
    border: 1px #ddd solid;
    padding: 3px 5px;
  }
  .tbefektif p{
    margin: 0px;
  }
</style>

<script type="text/javascript">
  function reqlampiran(id_risiko_sebelum, id_control, id_efektifitas){
  $.ajax({
    type:"post",
    url:"<?=site_url("panelbackend/risk_control_grc/detail")?>/"+id_risiko_sebelum+"/"+id_control,
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
</script>