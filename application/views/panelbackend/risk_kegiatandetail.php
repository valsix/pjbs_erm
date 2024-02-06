<div  class="header">
    <div class="pull-left">
      <h2>KEGIATAN</h2>
    </div>
    <div class="pull-right">
    <?php echo UI::showButtonMode($mode,$row[$pk])?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">

<div class="col-sm-12">

<?php
$from = UI::createTextBox('nama',$row['nama'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["name"], "nama", "Nama Kegiatan", false, 2);
?>

<?php
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi",false,2);
?>

<?php
$from = UI::createStatusPengajuan('kegiatan',$row['id_status'],$row['id_kegiatan']);
echo UI::createFormGroup($from, $rules["id_status"], "kegiatan", "Status", false, 2);
?>

<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null,null,null,null,2);
?>
</div>
</div>