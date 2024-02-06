<?php if(Access("add","panelbackend/risk_scorecard")){ ?>
<div  class="header">
  <div class="pull-left">
    <?=UI::createUpload('fileproses', $row['fileproses'], $page_ctrl, true, "Select files...", null, true);?>
  </div>
  <div class="pull-right">
  </div>
    <div style="clear: both;"></div>
</div>
<?php } ?>
<div class="body table-responsive" id="body-risiko">
	<iframe src="<?=site_url($page_ctrl."/open_file/".((int)$row['fileproses']['id'])."/fileproses")?>" width='100%' height='500px' style="border:none"></iframe>
</div>