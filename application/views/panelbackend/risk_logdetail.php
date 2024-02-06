<div class="col-sm-6">

<?php 
$from = UI::createSelect('id_scorecard',$riskscorecardarr,$row['id_scorecard'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_scorecard"], "id_scorecard", "Scorecard");
?>

<?php 
$from = UI::createSelect('id_risiko',$riskrisikoarr,$row['id_risiko'],$edited,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["id_risiko"], "id_risiko", "Risiko");
?>

</div>
<div class="col-sm-6">
				

<?php 
$from = UI::createTextArea('deskripsi',$row['deskripsi'],'','',$edited,'form-control',"");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi");
?>

<?php 
$from = UI::showButtonMode("save", null, $edited);
echo UI::createFormGroup($from);
?>
</div>