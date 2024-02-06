<div class="col-sm-6">

<?php
$from = UI::createTextBox('nid',$row['nid'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["nid"], "nid", "NID");
?>

<?php
$from = UI::createTextBox('name',$row['name'],'200','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["name"], "name", "Name");
?>

<?php
$from = UI::createSelect('id_jabatan',$jabatanarr,$row['id_jabatan'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_jabatan"], "id_jabatan", "Jabatan");
?>

<?php
$from = UI::createTextBox('username',$row['username'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["username"], "username", "Username");
?>

<?php
$from = UI::createTextBox('email',$row['email'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["email"], "email", "Email");
?>

<?php
$from = UI::createSelect('group_id',$publicsysgrouparr,$row['group_id'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["group_id"], "group_id", "Group ID");
?>

<?php
$from = UI::createCheckBox('is_notification',1,$row['is_notification'],"Notifikasi Email",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_notification"], "is_notification");
?>

<?php
$from = UI::createCheckBox('is_active',1,$row['is_active'],"Active",$edited,$class='iCheck-helper ',"");
echo UI::createFormGroup($from, $rules["is_active"], "is_active");
?>

</div>
<div class="col-sm-6">


<?php if($edited){?>
<?php if($row[$this->pk]){ ?>
<?php
$from = "Kosongkan password apabila Anda tidak ingin merubahnya.";
echo UI::createFormGroup($from, null, null, "");
?>
<?php } ?>
<?php
$from = UI::createTextPassword('password','','','',$edited,'form-control ');
echo UI::createFormGroup($from, $rules["password"], "password", "Password");
?>
<?php
$from = UI::createTextPassword('confirmpassword','','','',$edited,'form-control');
echo UI::createFormGroup($from, $rules["confirmpassword"], "confirmpassword", "Confirm Password");
?>
<?php }?>


<?php
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>

<?php if($edited){ ?>
<script type="text/javascript">
	$("#nid").on("select2:select", function (e) {
		$("#username").val($("#nid").val());
		$("#name").val($("#nid option:selected").text());
	});
</script>
<?php } ?>
