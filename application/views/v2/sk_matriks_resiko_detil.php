<div class="header">
<div class="pull-left">
</div>
<div class="pull-right">

<?


$tabelPK     = $this->data["tabelPK"];
$tabelData   = $this->data["tabelData"];
$row = $tabelData;

$primaryId   = $row[$tabelPK];


if($edited)
{
?>
    <button type="button"  class="btn waves-effect  btn-success" onclick="goList()" ><span class="glyphicon glyphicon-arrow-left"></span> Back</button>  
    <script>
    function goList(){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko";
    }
    </script>                            
<?
}
else
{
?>
    <button type="button"  class="btn waves-effect  btn-success" onclick="goList()" ><span class="glyphicon glyphicon-arrow-left"></span> Back</button>  
    <script>
    function goList(){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko";
    }
    </script> 
    <button type="button"  class="btn waves-effect  btn-primary" onclick="goAdd()" ><span class="glyphicon glyphicon-plus"></span> Add New</button> 
    <script>
    function goAdd(){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko/tambah";
    }
    </script> 
    <button type="button"  class="btn waves-effect  btn-warning" onclick="goEdit('<?=$primaryId?>')" ><span class="glyphicon glyphicon-edit"></span> Edit</button> 
    <script>
    function goEdit(id){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko/ubah/"+id;
    }
    </script>
    <button type="button"  class="btn waves-effect  btn-danger" onclick="goDelete('<?=$primaryId?>')" ><span class="glyphicon glyphicon-remove"></span> Delete</button> 
    <script>
    function goDelete(id){
    if(confirm("Apakah Anda yakin akan menghapus ?")){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko/hapus/"+id;
    }
    }
    </script>      
<?
}
?>

</div>
<div style="clear: both;"></div>
</div>

<div class="col-sm-6">

<?php

$from = UI::createTextBox('nomor',$row['nomor'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nomor"], "nomor", "No. SK");
?>

<?php
$from = UI::createTextBox('judul',$row['judul'],'300','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["judul"], "judul", "Judul");
?>

<?php 
$from = UI::createTextBox('tanggal_awal',$row['tanggal_awal'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tanggal_awal"], "tanggal_awal", "Tgl. Awal");
?>


<?php 
$from = UI::createTextBox('tanggal_akhir',$row['tanggal_akhir'],'10','10',$edited,'form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["tanggal_akhir"], "tanggal_akhir", "Tgl. Akhir");
?>


</div>
<div class="col-sm-6">

<?
if($edited)
{
?>
<div class="form-group ">
    <div class="col-sm-12">
        <button type="submit" class="btn-save btn  btn-success" onclick="goSave()" ><span class="glyphicon glyphicon-floppy-save"></span> Save</button>
            <script>
            function goSave(){
                $(".btn-save").attr("disabled","disabled");
                $("#act").val('save');
                $("#main_form").submit();
            }
            </script><button type="submit" class="btn waves-effect  btn-default" onclick="goBatal('')" ><span class="glyphicon glyphicon-repeat"></span> Cancel</button> 
            <script>
            function goBatal(){
                $("#act").val('reset');
                $("#main_form").submit();
            }
            </script>
        <span style="color:#dd4b39; font-size:11px; display: none" id="info_">
        
        </span>
    </div>
</div>
<?
}
?>

</div>
