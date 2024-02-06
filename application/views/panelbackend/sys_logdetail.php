
<div class="box-body">
<div style="text-align:right">
<?php echo UI::showButtonMode($mode, $row[$pk])?>
</div><br/>
<?=FlashMsg()?>
<div class="col-sm-6">

<?php 
$from = UI::createTextBox('page',$row['page'],'100','100',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["page"], "page", "Page");
?>

<?php 
$from = UI::createTextArea('activity',$row['activity'],'','',$edited,'form-control col-sm-10',"");
echo UI::createFormGroup($from, $rules["activity"], "activity", "Activity");
?>

<?php 
$from = UI::createTextBox('ip',$row['ip'],'30','30',$edited,'form-control ',"style='width:100%'");
echo UI::createFormGroup($from, $rules["ip"], "ip", "IP");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextNumber('activity_time',$row['activity_time'],'11','11',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='100000000000' step='1'");
echo UI::createFormGroup($from, $rules["activity_time"], "activity_time", "Activity Time");
?>

<?php 
$from = UI::createTextNumber('user_id',$row['user_id'],'','',$edited,'form-control ',"style='text-align:right; width:100%' min='0' max='1' step='1'");
echo UI::createFormGroup($from, $rules["user_id"], "user_id", "User ID");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>
</div>