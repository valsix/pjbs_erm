<div class="col-sm-6">

<?php 
$from = UI::createTextBox('nama',$row['nama'],'43','43',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama");
?>

<?php 
$from = UI::createTextBox('unit',$row['unit'],'3','3',$edited,$class='form-control ',"style='width:30px'");
echo UI::createFormGroup($from, $rules["unit"], "unit", "Unit");
?>

<?php 
$from = UI::createTextBox('unitket',$row['unitket'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["unitket"], "unitket", "Unitket");
?>

<?php 
$from = UI::createTextBox('direktorat',$row['direktorat'],'6','6',$edited,$class='form-control ',"style='width:60px'");
echo UI::createFormGroup($from, $rules["direktorat"], "direktorat", "Direktorat");
?>

<?php 
$from = UI::createTextBox('direktoratket',$row['direktoratket'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["direktoratket"], "direktoratket", "Direktoratket");
?>

<?php 
$from = UI::createTextBox('subdit',$row['subdit'],'6','6',$edited,$class='form-control ',"style='width:60px'");
echo UI::createFormGroup($from, $rules["subdit"], "subdit", "Subdit");
?>

<?php 
$from = UI::createTextBox('subditket',$row['subditket'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["subditket"], "subditket", "Subditket");
?>

<?php 
$from = UI::createTextBox('fungsi',$row['fungsi'],'6','6',$edited,$class='form-control ',"style='width:60px'");
echo UI::createFormGroup($from, $rules["fungsi"], "fungsi", "Fungsi");
?>

<?php 
$from = UI::createTextBox('fungsiket',$row['fungsiket'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["fungsiket"], "fungsiket", "Fungsiket");
?>

<?php 
$from = UI::createTextBox('kdstaff',$row['kdstaff'],'6','6',$edited,$class='form-control ',"style='width:60px'");
echo UI::createFormGroup($from, $rules["kdstaff"], "kdstaff", "Kdstaff");
?>

<?php 
$from = UI::createTextBox('staff',$row['staff'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["staff"], "staff", "Staff");
?>

<?php 
$from = UI::createTextBox('kdjabatan',$row['kdjabatan'],'10','10',$edited,$class='form-control ',"style='width:100px'");
echo UI::createFormGroup($from, $rules["kdjabatan"], "kdjabatan", "Kdjabatan");
?>

<?php 
$from = UI::createTextBox('jabatan',$row['jabatan'],'558','100',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jabatan"], "jabatan", "Jabatan");
?>

<?php 
$from = UI::createTextBox('kdjenjang',$row['kdjenjang'],'4','4',$edited,$class='form-control ',"style='width:40px'");
echo UI::createFormGroup($from, $rules["kdjenjang"], "kdjenjang", "Kdjenjang");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextBox('jenjang',$row['jenjang'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jenjang"], "jenjang", "Jenjang");
?>

<?php 
$from = UI::createTextBox('kdstatus',$row['kdstatus'],'3','3',$edited,$class='form-control ',"style='width:30px'");
echo UI::createFormGroup($from, $rules["kdstatus"], "kdstatus", "Kdstatus");
?>

<?php 
$from = UI::createTextBox('status',$row['status'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["status"], "status", "Status");
?>

<?php 
$from = UI::createTextBox('gender',$row['gender'],'1','1',$edited,$class='form-control ',"style='width:10px'");
echo UI::createFormGroup($from, $rules["gender"], "gender", "Gender");
?>

<?php 
$from = UI::createTextArea('email',$row['email'],'','',$edited,$class='form-control',"");
echo UI::createFormGroup($from, $rules["email"], "email", "Email");
?>

<?php 
$from = UI::createTextBox('birth_date',$row['birth_date'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["birth_date"], "birth_date", "Birth Date");
?>

<?php 
$from = UI::createTextBox('hire_date',$row['hire_date'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["hire_date"], "hire_date", "Hire Date");
?>

<?php 
$from = UI::createTextBox('suspend_date',$row['suspend_date'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["suspend_date"], "suspend_date", "Suspend Date");
?>

<?php 
$from = UI::createTextBox('term_date',$row['term_date'],'10','10',$edited,$class='form-control datepicker',"style='width:100px'");
echo UI::createFormGroup($from, $rules["term_date"], "term_date", "Term Date");
?>

<?php 
$from = UI::createTextBox('emp_status',$row['emp_status'],'1','1',$edited,$class='form-control ',"style='width:10px'");
echo UI::createFormGroup($from, $rules["emp_status"], "emp_status", "EMP Status");
?>

<?php 
$from = UI::createTextBox('jabatan2',$row['jabatan2'],'40','40',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["jabatan2"], "jabatan2", "Jabatan2");
?>

<?php 
$from = UI::createTextBox('kdstaff2',$row['kdstaff2'],'3','3',$edited,$class='form-control ',"style='width:30px'");
echo UI::createFormGroup($from, $rules["kdstaff2"], "kdstaff2", "Kdstaff2");
?>

<?php 
$from = UI::createTextBox('staff2',$row['staff2'],'50','50',$edited,$class='form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["staff2"], "staff2", "Staff2");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>