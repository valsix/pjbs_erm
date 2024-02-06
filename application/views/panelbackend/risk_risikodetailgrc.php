<?php
$modeheader1 = $mode;
if(!$editedheader1){
    $modeheader1 = 'detail';
}
$is_readmore_risiko= false;
if(!accessbystatus($rowheader1['id_status_pengajuan'], $page_ctrl) && $page_ctrl!='panelbackend/risk_risiko')
  $is_readmore_risiko = true;

$user_id = $_SESSION[SESSION_APP]['user_id'];
?>
<div  class="header">
  <div class="pull-left">
      <h2>IDENTIFIKASI <?=strtoupper($label_risk)?> 
    </h2>
        <?=labelverified($row);?>

  </div>
  <div class="pull-right">
    <?php 
    echo UI::showButtonMode('edit_detail',$row[$pk], $editedheader1);
    ?>
  </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" id="body-risiko">
<div class="col-sm-6">

<?php
if(!$rowheader['id_nama_proses']){
$from = UI::createSelect('id_sasaran_strategis',$sasaranarr,$rowheader1['id_sasaran_strategis'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_strategis\")'");
echo UI::createFormGroup($from, $rules["id_sasaran_strategis"], "id_sasaran_strategis", "Sasaran Strategis", false, 4, $editedheader1);
}

if($rowheader['id_nama_proses']){
  $from = UI::createTextBox('kode_aktifitas',$rowheader1['kode_aktifitas'],'','',$editedheader1,'form-control ', "onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["kode_aktifitas"], "kode_aktifitas", "Kode Aktivitas", false, 4, $editedheader1);
$from = UI::createTextArea('nama_aktifitas',$rowheader1['nama_aktifitas'],'','',$editedheader1,'form-control ');
echo UI::createFormGroup($from, $rules["nama_aktifitas"], "nama_aktifitas", "Nama Aktivitas", false, 4, $editedheader1);
}elseif($rowheader['jenis_sasaran']=='2'){ 

$from = UI::createSelect('id_sasaran_kegiatan',$mtkegiatanarr,$rowheader1['id_sasaran_kegiatan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"post_kegiatan\")'");
echo UI::createFormGroup($from, $rules["id_sasaran_kegiatan"], "id_sasaran_kegiatan", "Sasaran Kegiatan", false, 4, $editedheader1);

$from = "";
foreach ($rowheader1['kpi_kegiatan'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],$editedheader1);
  $from .= "<br/>";
}

echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 4, $editedheader1);

}else{

$from = "";
foreach ($rowheader1['kpi_strategis'] as $rk) {
  $idkpi = $rk['id_kpi'];
  $from .= UI::createCheckBox("id_kpi[$idkpi]",$idkpi,$rowheader1['id_kpi'][$idkpi],$rk['nama'],$editedheader1);
  $from .= "<br/>";
}

echo UI::createFormGroup($from, $rules["id_kpi[]"], "id_kpi[]", "KPI", false, 4, $editedheader1);

} ?>
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

if(!$is_peluang){ 
$from = null;
if($edited){
  $from .= "<button type='button' class='btn btn-primary btn-xs' onclick='openTaksonomi()'>Referensi Risiko</button>";
}
$from .= UI::createSelect('id_taksonomi_objective',$objectivearr,$rowheader1['id_taksonomi_objective'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["id_taksonomi_objective"], "id_taksonomi_objective", "Taksonomi", false, 4, $editedheader1);

$from = UI::createSelect('id_taksonomi_area',$areaarr,$rowheader1['id_taksonomi_area'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["id_taksonomi_area"], "id_taksonomi_area", "Area", false, 4, $editedheader1);
?>
<?php
$from = UI::createTextArea('nama',$rowheader1['nama'],'','',$editedheader1,'form-control myautocomplate',
($rowheader1['id_taksonomi_risiko']?"readonly":"")." style='width:100%' 
placeholder='Isian nama risiko'
data-cb='var cb = function(r){
  $(\"#id_taksonomi_risiko\").val(r.id);
  if(r.id)
    goSubmit(\"set_value\");
}'
data-arr='".
json_encode(array_values($taksonomirr))
."'");
$from .= UI::createTextHidden("id_taksonomi_risiko",$rowheader1['id_taksonomi_risiko'], $editedheader1);
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Risiko", false, 4, $editedheader1);

}
?>

</div>
<div class="col-sm-6">

<?php
if($is_peluang){

$from = UI::createTextArea('nama',$rowheader1['nama'],'','',$editedheader1,'form-control',
"style='width:100%' 
placeholder='Isian nama peluang'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Peluang", false, 4, $editedheader1);

$from = UI::createTextArea('deskripsi',$rowheader1['deskripsi'],'','',$editedheader1,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", false, 4, $editedheader1);
}
if(!$is_peluang){ 
if($hambatan_kendala = $rowheader1['risiko_old']['hambatan_kendala'])
  $info_penyebab = UI::createInfo("info_penyebab","Hambatan & Kendala Sebelumnya", $hambatan_kendala);


$from = null;
if($edited){
  
  ?>
<div class="modal fade" id="penyebabpop" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Referensi Penyebab</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered no-margin table-hover table-risiko">
          <tr>
            <th width="10px">Jenis</th>
            <th>Penyebab</th>
            <th width="10px"></th>
          </tr>
          <?php
          $ada_penyebab = false;
          if($jenisarr){
          foreach($jenisarr as $jenis=>$v){
            if($penyebabarr[$jenis])
            foreach($penyebabarr[$jenis] as $k=>$r){
            $ada_penyebab = true;
              echo "<tr>
                <td>$v</td>
                <td>$r[text]</td>
                <td>".UI::createCheckBox("penyebab[$jenis][$k][id_taksonomi_penyebab]",$k,null,null,true)."</td>
              </tr>";
            }
          }
        } if(!$ada_penyebab){
          ?>
          <tr><td colspan="3"><i>Data Kosong</i></td></tr>
        <?php } ?>
        </table>
        <br/>
        <div style="text-align: right">
          <?php if($ada_penyebab){ ?>
            <button type='button' class='btn btn-primary btn-sm' onclick="goSubmit('set_value')">Pilih</button>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function openPenyebab(){
    $("#penyebabpop").modal("toggle");
  }
</script>
  <?php 
  if($ada_penyebab){
    $from .= "<button type='button' class='btn btn-primary btn-xs' onclick='openPenyebab()'>Referensi Penyebab</button>";
  }
}
foreach($jenisarr as $k=>$v){

  if(!$editedheader1 && !$rowheader1['penyebab'][$k])
    continue;


  if($k)
    $from .= "<b>$v</b><br/>";

  $fn = function($r=null, $editedheader1, $k, $addparam=array()){
    $jenis = $addparam['jenis'];
    $penyebabarr = $addparam['penyebabarr'];

    if(!$r['nama'])
      $r['nama'] = $penyebabarr[$r['id_taksonomi_penyebab']]['text'];

    $str = UI::createTextArea("penyebab[$jenis][$k][nama]",@$r['nama'],'3','3',$editedheader1,'form-control',($r['id_taksonomi_penyebab']?"readonly":"").
      " style='width:100%' 
      placeholder='Isian penyebab risiko'");

    return $str;
  };

  $from .= UI::AddForm('penyebab_'.$k, $rowheader1['penyebab'][$k], $fn, $editedheader1, false, array('jenis'=>$k, 'penyebabarr'=>$penyebabarr[$k]));
  $from .= "<div style='clear:both'></div><hr/>";
}

echo UI::createFormGroup($from, $rules["penyebab"], "penyebab", "Penyebab".$info_penyebab, false, 4, $editedheader1);

$from = null;
if($edited){
  
  ?>
<div class="modal fade" id="dampakpop" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Referensi Dampak</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered no-margin table-hover table-risiko">
          <tr>
            <th>Dampak</th>
            <th width="10px"></th>
          </tr>
          <?php
          $ada_dampak = count($dampakarr);
            if($dampakarr)
            foreach($dampakarr as $k=>$r){
              echo "<tr>
                <td>$r[text]</td>
                <td>".UI::createCheckBox("dampak[$k][id_taksonomi_dampak]",$k,null,null,true)."</td>
              </tr>";
            }
          ?>
        </table>
        <br/>
        <div style="text-align: right"><button type='button' class='btn btn-primary btn-sm' onclick="goSubmit('set_value')">Pilih</button></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function openDampak(){
    $("#dampakpop").modal("toggle");
  }
</script>
  <?php 
  if($ada_dampak){
    $from = "<button type='button' class='btn btn-primary btn-xs' onclick='openDampak()'>Referensi Dampak</button>";
  }
}
$fn = function($r=null, $editedheader1, $k, $addparam=array()){
    $dampakarr = $addparam['dampakarr'];
    if(!$r['nama'])
      $r['nama'] = $dampakarr[$r['id_taksonomi_dampak']]['text'];

    $str = UI::createTextArea("dampak[$k][nama]",@$r['nama'],'3','3',$editedheader1,'form-control',
      "style='width:100%' 
      placeholder='Isian dampak risiko'");

    return $str;
};

$from .= UI::AddForm('dampak', $rowheader1['dampak'], $fn, $editedheader1, false, array('dampakarr'=>$dampakarr));
echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Dampak", false, 4, $editedheader1);
}else{

$from = UI::createTextArea('dampak',$rowheader1['dampak'],'','',$editedheader1,'form-control',
"style='width:100%' 
placeholder='Isian keuntungan'");
echo UI::createFormGroup($from, $rules["dampak"], "dampak", "Keuntungan", false, 4, $editedheader1);

}
?>

<?php
if(!$is_peluang){
  if ($mode!='add') 
  {
    if($this->access_role['review'] or $rowheader1['review_kepatuhan']){
      $from = UI::createTextArea('review_kepatuhan',$rowheader1['review_kepatuhan'],'','',($this->access_role['review'] && $edited),'form-control ',"style='width:100%'");
      echo UI::createFormGroup($from, $rules["review_kepatuhan"], "review_kepatuhan", "Review Kepatuhan");
    }

    if($rowheader1['rekomendasi_keterangan'] or $this->access_role['rekomendasi']){
      $from = UI::createTextArea('rekomendasi_keterangan',$rowheader1['rekomendasi_keterangan'],'','',$edited && $this->access_role['rekomendasi'],'form-control',"");

      $from .= UI::createUploadMultiple('filerekomendasi', $row['filerekomendasi'], $page_ctrl, $edited);

      echo UI::createFormGroup($from, $rules["rekomendasi_keterangan"], "rekomendasi_keterangan", "Dasar Rekomendasi", false, 4, $edited);
    }
  }
    
}
?>
<?php if($is_peluang){ 

  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from, NULL, NULL, NULL,false, 4, $edited);
} ?>
</div>
<div style="clear: both;"></div>
</div>
  <?php if(!$is_peluang){ ?>
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
    echo UI::createFormGroup($from, $rules["inheren_kemungkinan"], "inheren_kemungkinan", 'Kemungkinan<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaKemungkinan"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
    ?>
  </div>
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('id_kriteria_kemungkinan',$kriteriakemungkinanarr,$rowheader1['id_kriteria_kemungkinan'],$editedheader1,'form-control id_kriteria_kemungkinan',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
    echo UI::createFormGroup($from, $rules["id_kriteria_kemungkinan"], "id_kriteria_kemungkinan", "Kriteria", false, 3, $editedheader1);
    ?>
  </div>
  <div class="col-sm-4">
    <?php
    $from = "";
    if($rowheader1['id_kriteria_kemungkinan']==3){
      if($edited)
        $from .= "<span style='color:red; display:inline'>*</span>";

        $from .= UI::createUploadMultiple('file', $row['file'], $page_ctrl, $edited);
    }
    echo $from;
    ?>
  </div>
  </div>

  <div class="row" style="margin: 0 -15px;">
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('inheren_dampak',$mtdampakrisikoarr,$rowheader1['inheren_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["inheren_dampak"], "inheren_dampak", 'Dampak<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaDampak"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
    ?>
  </div>
  <div class="col-sm-4">
    <?php
    $from = UI::createSelect('id_kriteria_dampak',$kriteriaarr,$rowheader1['id_kriteria_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["id_kriteria_dampak"], "id_kriteria_dampak", 'Kriteria', false, 3, $editedheader1);
    ?>
    </div>
    </div>
  <div class="row" style="margin: 0 -15px;">
<div class="col-sm-4">
<?php
if(isset($scorecardarr) && count($scorecardarr)>1 && $editedheader1){
  $from = UI::createSelect('id_scorecard',$scorecardarr,$rowheader1['id_scorecard'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
  echo UI::createFormGroup($from, $rules["id_scorecard"], "id_scorecard", "Disposisi", false, 5, $editedheader1);
}
?>

<?php

if($this->access_role['review']){

  if($edited){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review\')"><span class="glyphicon glyphicon-floppy-save"></span> Save Rekomendasi</button>';
    echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5);
  }elseif(!$edited && $row['is_lock']=='1' && $row['review_is_verified']=='3'){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_review_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
    echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5);
  }

}elseif($this->access_role['rekomendasi']){

  if($edited && $row['filerekomendasi']){
    $from = UI::showButtonMode("save", null, $edited);
    echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5, $edited);
  }elseif(!$edited && $row['is_lock']=='1' && $row['rekomendasi_is_verified']=='3'){
    $from = '<button type="button" class="btn-save btn  btn-success" onclick="goSubmitConfirm(\'save_rekomendasi_verified\')"><span class="glyphicon glyphicon-ok"></span> Verified</button>';
    echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5);
  }

}elseif(!$edited && $_SESSION[SESSION_APP]['pic']==$rowheader['owner'] && $row['is_lock']=='2'){

  if($row['rekomendasi_is_verified']=='2'){
    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_rekomendasi\")'>Kirim ke $row[rekomendasi_group]  <span class='glyphicon glyphicon-forward'></span></button>";
  }
  if($row['review_is_verified']=='2'){
    $from = "<button type='button' class='btn btn-success' onclick='goSubmitConfirm(\"kirim_review\")'>Kirim ke $row[review_group]  <span class='glyphicon glyphicon-forward'></span></button>";
  }
  echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5);

}elseif($edited){

  $from = UI::showButtonMode("save", null, $edited);
  echo UI::createFormGroup($from, NULL, NULL, NULL,false, 5, $edited);

}
?>
</div>
</div>
</div>
</div>
<?php } ?>

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


<?php if($edited){ ?>
<div class="modal fade" id="taksonomipop" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Taksonomi Risiko</h4>
      </div>
      <div class="modal-body" id="datataksonomi">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="risikopop" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Tambah Risiko Baru</h4>
      </div>
      <div class="modal-body">
        <?=UI::createTextArea('nama_baru',null,'','',$editedheader1,'form-control', "style='width:100%'")?>
        <div style='text-align:center'><br/><button type='button' class='btn btn-sm btn-success' onclick='savenew()'>Simpan Risiko Baru</button></div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
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

  <?php if($edited){ ?>
  function callTaksonomi() {
    $.ajax({
      dataType: 'html',
      type:'POST',
      data:{
        id_taksonomi_objective:$('#id_taksonomi_objective_filter').val(),
        id_taksonomi_area:$('#id_taksonomi_area_filter').val(),
        nama:$('#nama_filter').val()
      },
      url:'<?=base_url("panelbackend/ajax/listtaksonomi")?>',
      success:function(response) {
        $('#datataksonomi').html(response);
      }
    })
  }

  function pilihTaksonomi(id_taksonomi_risiko){
    $("#id_taksonomi_objective").val($("#id_taksonomi_objective_filter").val());
    $("#id_taksonomi_area").html($("#id_taksonomi_area_filter").html());
    $("#id_taksonomi_area").val($("#id_taksonomi_area_filter").val());
    $("#id_taksonomi_risiko").val(id_taksonomi_risiko);
    goSubmit("set_value");
  }

  function openTaksonomi(){
    callTaksonomi();
    $("#taksonomipop").modal('toggle');
  }

  function opennew(){
    $("#taksonomipop").modal('toggle');
    $("#risikopop").modal('toggle');
  }

  function savenew(){
    $("#risikopop").modal('toggle');
    $("#nama").val($("#nama_baru").val());
    $("#id_taksonomi_risiko").val('');
    $("#id_taksonomi_objective").val($("#id_taksonomi_objective_filter").val());
    $("#id_taksonomi_area").html($("#id_taksonomi_area_filter").html());
    $("#id_taksonomi_area").val($("#id_taksonomi_area_filter").val());
    goSubmit("set_value");
  }
  <?php } ?>
</script>
<style>
  .table-risiko td, .table-risiko th{
  padding: 3px !important;
  font-size: 13px !important;
  }
</style>