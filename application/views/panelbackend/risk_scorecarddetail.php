<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
    				<div class="pull-left">
                    <h2>
                    <?=$page_title?>
                    </h2>
                    </div>
                    <div class="pull-right">
                            <?php echo UI::showButtonMode($mode, $rowheader['id_scorecard'])?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="body">
                    <?=FlashMsg()?>

<?php
$from = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$rowheader['id_kajian_risiko'],false,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_kajian_risiko"], "id_kajian_risiko", "Lingkup Kajian Risiko", false, 2, $editedheader);
?>

<?php
if($this->access_role['view_all_direktorat'] && $editedheader){
if($rowheader['navigasi']===null)
    $rowheader['navigasi'] = 0;

$from = UI::createSelect('navigasi',array('1'=>'Folder','2'=>'Sub Folder','0'=>'Risiko'), $rowheader['navigasi'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;'' onchange='goSubmit()'");
echo UI::createFormGroup($from, $rules["navigasi"], "navigasi", "Tipe Scorecard", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
?>

<?php
if(!$rowheader['navigasi']){
$from = UI::createSelect('id_unit',$unitarr, $rowheader['id_unit'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["id_unit"], "id_unit", "Unit", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
}
}
?>

<?php
if(!$rowheader['navigasi']){
$from = UI::createSelect('owner',$ownerarr,$rowheader['owner'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control select2'," onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["owner"], "owner", "Owner", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
if($rowheader['id_kajian_risiko']=='4'){
$from = UI::createSelect('id_nama_proses',$mtpbnamaprosesarr, $rowheader['id_nama_proses'],$editedheader,'form-control ',"style='width:auto; max-width:100%;' onchange='goSubmit(\"set_value\")'");
echo UI::createFormGroup($from, $rules["id_nama_proses"], "id_nama_proses", "Nama Proses", false, 2, $editedheader);
}

if($rowheader['id_kajian_risiko']=='5'){
$from = UI::createSelect('id_status_proyek',$mtstatusproyekarr, $rowheader['id_status_proyek'],$editedheader,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_status_proyek"], "id_status_proyek", "Status Proyek", false, 2, $editedheader);
}
}
if($rowheader['id_parent_scorecard']=='527'){
$from = UI::createSelect('id_status_unit',$mtstatusunitarr, $rowheader['id_status_unit'],$editedheader,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_status_unit"], "id_status_unit", "Status Unit", false, 2, $editedheader);
}
?>


<?php
$from = UI::createTextBox('nama',$rowheader['nama'],'','',($this->access_role['view_all_direktorat'] && $editedheader),'form-control',"");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama Scorecard", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
?>

<?php
if(!$rowheader['navigasi']){
$from = UI::createTextArea('scope',$rowheader['scope'],'','',$editedheader,'form-control',"");
echo UI::createFormGroup($from, $rules["scope"], "scope", "Scope", false, 2, $editedheader);

if($rowheader['id_status_proyek']==1){
$from = UI::createTextNumber('on_cost',$rowheader['on_cost'],'','',($this->access_role['view_all_direktorat'] && $editedheader),'form-control',"");
echo UI::createFormGroup($from, $rules["on_cost"], "on_cost", "On Cost", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));

$from = UI::createTextNumber('on_time',$rowheader['on_time'],'','',($this->access_role['view_all_direktorat'] && $editedheader),'form-control',"");
echo UI::createFormGroup($from, $rules["on_time"], "on_time", "On Time", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));

$from = UI::createTextNumber('on_spec',$rowheader['on_spec'],'','',($this->access_role['view_all_direktorat'] && $editedheader),'form-control',"");
echo UI::createFormGroup($from, $rules["on_spec"], "on_spec", "On Spec", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));

$from = UI::createTextNumber('on_safety',$rowheader['on_safety'],'','',($this->access_role['view_all_direktorat'] && $editedheader),'form-control',"");
echo UI::createFormGroup($from, $rules["on_safety"], "on_safety", "On safety", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
}

$from = UI::createCheckBox("is_info",1,$rowheader['is_info'],"Tampilkan tab informasi data pendukung",$editedheader);
echo UI::createFormGroup($from, $rules["is_info"], "is_info", "", false, 2, $editedheader);

}
?>

<?php 
if($this->access_role['view_all_direktorat'] && $editedheader){
$from = UI::createSelect('id_parent_scorecard',$scorecardarr,$rowheader['id_parent_scorecard'],($this->access_role['view_all_direktorat'] && $editedheader),'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_parent_scorecard"], "id_parent_scorecard", "Induk Scorecard", false, 2, $editedheader);

$from = UI::createTextBox('tgl_mulai_efektif',($row['tgl_mulai_efektif']?$row['tgl_mulai_efektif']:date('d-m-Y')),'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_mulai_efektif"], "tgl_mulai_efektif", "Tgl. Mulai Efektif", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
?>
                

<?php 
$from = UI::createTextBox('tgl_akhir_efektif',$row['tgl_akhir_efektif'],'10','10',($this->access_role['view_all_direktorat'] && $editedheader),'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tgl_akhir_efektif"], "tgl_akhir_efektif", "Tgl. Akhir Efektif", false, 2, ($this->access_role['view_all_direktorat'] && $editedheader));
}
?>


<?php
$form = UI::createSelectMultiple('id_jabatan[]',$mtsdmjabatanarr,$row['id_jabatan'],$edited,'form-control select2',"style='width:100%'");
echo UI::createFormGroup($form, $rules["id_jabatan[]"], "id_jabatan[]", "Bisa Dilihat Oleh ?", false, 2,$edited);


$from = UI::InputFile(
array(
    "nameid"=>"template_laporan",
    "edit"=>$edited,
    "extarr"=>explode("|","doc, docx<br/> Ukuran Maksimal ".(round($configfile['max_size']/1000))." mb"),
    "nama_file"=>$row['template_laporan'],
    "url_preview"=>site_url("panelbackend/risk_scorecard/preview_file/".$row[$pk]),
    "url_delete"=>site_url("panelbackend/risk_scorecard/delete_file/".$row[$pk]),
    )
);
$from .= "<div><small>Template digunakan untuk Laporan Dokumen. <br/>Sertakan variabel berikut :<br/>
\${tahun} = untuk mengambil informasi tahun risiko<br/>
\${risk_profile} = untuk mengambil nama profil risiko<br/>
\${tablekertaskerja} = untuk membuat tabel kertas kerja<br/>
\${tablerisiko} = untuk membuat tabel risiko<br/>
\${matrix} = untuk mengambil matrx<br/>
\${kesimpulan} = untuk mengambil kesimpulan yang ada didashboard</small></div>";
echo UI::createFormGroup($from, $rules["template_laporan"], "template_laporan", "Template Laporan", false, 2,$edited);
?>

<?php
$from = UI::showButtonMode("save_detail", $rowheader[$pk], $editedheader, null, null, $this->access_role_custom['panelbackend/risk_scorecard']);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>  

                </div>
            </div>
        </div>
    </div>
</div>