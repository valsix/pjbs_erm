<div  class="header">
    <h2>REVIEW / DISKUSI</h2>
</div>
<div class="body table-responsive">

<?php
foreach ($list['rows'] as $r) {

	if($r['created_by']==$_SESSION[SESSION_APP]['user_id'])
		$this->access_role['delete'] = 1;

	$btn = UI::getButton('delete', $r['id_review'], null, 'btn-xs');
	echo "<b>".ucwords(strtolower($r['nama_user']))." (".ucwords(strtolower($r['nama_group'])).")</b> <i>".$r['review']."</i><span class='pull-right'>$btn</span><br/><small style='font-size:10px'>Waktu Review : ".Eng2Ind($r['created_date'])."</small><hr/>";
}

$from = UI::createTextArea('review',null,'','',true,'form-control'," placeholder='ketik disini untuk menambah pesan'");
echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "Deskripsi", true);
?>
<br/>
<?php 
$from = UI::showButtonMode("save", null, true);
echo UI::createFormGroup($from, null, null, null, true);
?>
</div>