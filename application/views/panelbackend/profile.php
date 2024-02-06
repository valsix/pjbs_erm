<div class="col-sm-12">

<?php
$from = UI::createTextBox('nid',$row['nid'],'','',false,'form-control');
echo UI::createFormGroup($from, $rules["nid"], "nid", "NID", false, 2);
?>

<?php
$from = UI::createTextBox('name',$row['name'],'','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["nama"], "nama", "Nama", false, 2);
?>

<?php
$from = UI::createTextBox('id_jabatan',$jabatanarr[$row['id_jabatan']],'','',false,'form-control');
echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "Jabatan", false, 2);
?>

<?php
$from = UI::createTextBox('username',$row['username'],'','',false,'form-control');
echo UI::createFormGroup($from, $rules["username"], "username", "Username", false, 2);
?>

<?php
$from = UI::createTextBox('email',$row['email'],'','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["email"], "email", "Email", false, 2);
?>

<?php
$from = UI::createCheckBox('is_notification',1,$row['is_notification'],"Notifikasi Email",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_notification"], "is_notification",null, false, 2);
?>

<br/>
Kosongkan password apabila Anda tidak ingin merubahnya
<hr/>
<?php
$from = UI::createTextPassword('oldpassword','','','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["oldpassword"], "oldpassword", "Password Lama", false, 2);
?>

<?php
$from = UI::createTextPassword('password','','','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["password"], "password", "Password Baru", false, 2);
?>

<?php
$from = UI::createTextPassword('confirmpassword','','','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["confirmpassword"], "confirmpassword", "Konfirmasi Password Baru", false, 2);
?>

<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from, null, null, null, false, 2);
?>
</div>