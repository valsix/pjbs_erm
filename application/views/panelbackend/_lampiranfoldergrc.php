<?php
ob_start();
if(($headerlampiran)){ 
	// print_r($headerlampiran);exit();
?>

<table class="tree-table1 table table-hover dataTable" style="margin-top: 0px">
<tbody>
<tr data-tt-id='AC'>
	<td>
		<b id="idrootheader[0]" name="idrootheader[0]" data-id='AC'><?php echo $rowheader['nomor_ba_grc']; ?></b>
	</td>
</tr>
<?php 

foreach($headerlampiran as $id=>$rowheader)
{ 
	if(is_array($rowheader['dataupload']))
	{ 
		$id_parent = "AC";
		// $label = key($rowheader['dataupload']);
		$label = $rowheader['nama'];
		$header1 = $rowheader['dataupload'];
		$id_dok_pendukung_grc = $rowheader['id_dok_pendukung_grc'];
		// print_r($label);
		// print_r($rowheader['dataupload']);exit();
		?>

    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
		<td>
			<!-- createCheckBox($nameid,$valuecontrol='',$value='',$label='label',$edit=true,$class='',$add='') -->
			<?php //echo UI::createCheckBox("header[$id]",$id,$row['header'][$id],$label,true,"echeck1 id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			<!-- <b id="header[$id]" name="header[$id]" data-id='$id' data-idparent='$id_parent'><?php echo $label; ?></b> -->

			<b id="header[$id]" name="header[$id]" class="id$id idparent$id_parent" data-id='$id' data-idparent='$id_parent'>
						<a href="" data-toggle="modal" data-target="#myModal<?php echo $id;?>"> <?php echo $label; ?> </a>
					</b>
					<div class="modal fade" id="myModal<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModal<?php echo $id;?>Label" style="text-align:left;">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModal<?php echo $id;?>Label">Upload Data</h4>
			      </div>
			      <div class="modal-body">
			        <!-- <ul>
			        	<li>Download template dengan cara export data.</li>
			        </ul> -->
			        <?php
			        $from = UI::createUploadMultipleGrc('file', $row['file'], $page_ctrl, true, "Select files grc...", $id_dok_pendukung_grc);
							echo UI::createFormGroup($from, $rules["file"], "file", "File Lampiran");
			        ?>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	
			      </div>
			    </div>
			  </div>
			</div>
		</td>
    </tr>

		<?php
		$id_parent = $id;
		if ($header1) 
		{
			foreach($header1 as $id1=>$rowheader1)
			{ 
				$label = $rowheader1;
				?>

		    <tr data-tt-id='<?=$id1?>' data-tt-parent-id='<?=$id_parent?>'>
				<td>
					<?php //echo UI::createCheckBox("header[$id1]",$id1,$row['header'][$id],$label,true,"echeck1 id$id1 idparent$id_parent", "data-id='$id1' data-idparent='$id_parent'");?>
					
				</td>
		    </tr>

				<?php 
			}
		}
		
	}
	else
	{
		$label = $rowheader['nama'];
		$id_dok_pendukung_grc = $rowheader['id_dok_pendukung_grc'];
		$id_parent = "AC";
		?>
    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
		<td>
			<?php //echo UI::createCheckBox("header[$id]",$id,$row['header'][$id],$label,true,"echeck1 id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			<b id="header[$id]" name="header[$id]" class="id$id idparent$id_parent" data-id='$id' data-idparent='$id_parent'>
				<a href="" data-toggle="modal" data-target="#myModal<?php echo $id;?>"> <?php echo $label; ?> </a>
			</b>
			<!-- <span style="height: 50%;" class="btn btn-success fileinput-button" data-toggle="modal" data-target="#myModal<?php echo $id;?>">
				<i class="glyphicon glyphicon-upload"></i>
			</span> -->

			<div class="modal fade" id="myModal<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModal<?php echo $id;?>Label" style="text-align:left;">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModal<?php echo $id;?>Label">Upload Data</h4>
			      </div>
			      <div class="modal-body">
			        <!-- <ul>
			        	<li>Download template dengan cara export data.</li>
			        </ul> -->
			        <?php
			        $from = UI::createUploadMultipleGrc('file', $row['file'], $page_ctrl, true, "Select files grc...", $id_dok_pendukung_grc);
							echo UI::createFormGroup($from, $rules["file"], "file", "File Lampiran");
			        ?>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	
			      </div>
			    </div>
			  </div>
			</div>
		</td>
    </tr>
		<?php 
	}
} ?>
</tbody>
</table>


<script type="text/javascript">
$(function(){
  $(".tree-table1").treetable({ expandable: true });
  $(".tree-table1").treetable('expandAll');
});

	$(".echeck1").change(function(){
		var id = $(this).attr("data-id");		
		var idparent = $(this).attr("data-idparent");	
		var child = $(".idparent"+id);

		if($(this).is(":checked")){
			child.prop("checked", true);
		}else{
			child.prop("checked", false);
		}

		child.change();

	})
</script>
<style type="text/css">
	.table tbody tr td, .table tbody tr th {
    padding: 0px;
    border-top: none;
    border-bottom: none;
}
table.dataTable {
	    margin-top: 7px !important;
	    margin-left: -20px;
	}
</style>
<?php } 

$ret=ob_get_contents();
ob_end_clean();

return $ret;
?>