<?php

function scorecardtree($rowscorecards, $row=null, $id_kajianrisiko=null, $owner=null, $label=false, $is_msg=false){
	ob_start();
	if(($rowscorecards)){ 
	?>

	<table class="tree-table<?=$id_kajianrisiko?> table table-hover dataTable" style="margin-top: 0px">
	<tbody>
	<tr data-tt-id='AS<?=$id_kajianrisiko?>'>
		<td>
			<?php echo UI::createCheckBox("id_rootscorecard[$id_kajianrisiko]",0,0,($label?$label:"Pilih Semua"),true,"echeck$id_kajianrisiko", "data-id='AS$id_kajianrisiko'");?>
		</td>
	</tr>
	<?php 
	$have_a = false;
	foreach($rowscorecards as $rowscorecard){ 

		if($rowscorecard['id']=='A')
			$have_a = true;

		$id = $rowscorecard['id'];
		$id_scorecard = $rowscorecard['id_scorecard'];
		$id_parent = $rowscorecard['id_parent'];

	    if(!$rowscorecard['nama'])
	        $rowscorecard['nama'] = '-';

	    if($is_msg){
	    	if($rowscorecard['open_evaluasi']){
	    		$rowscorecard['nama'] = $rowscorecard['nama'].' <span style="color:green">(open)</span> ';
	    	}else{
	    		$rowscorecard['nama'] = $rowscorecard['nama'].' (close) ';
	    	}
	    }
	    
		if(!$id_parent && $rowscorecard['id']<>'A' && $have_a)
			$id_parent='A';

		if(!$id_parent)
			$id_parent = 'AS'.$id_kajianrisiko;

		?>
		<?php if($rowscorecard['owner']==$owner && $owner!==null){ ?>
		<tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>' class='bg-light-blue'>
			<td>
				<?php echo UI::createCheckBox("id_scorecard[$id]",$id_scorecard,$row['id_scorecard'][$id],$rowscorecard['nama'],true,"echeck$id_kajianrisiko id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			</td>
		</tr>
		<?php }else{ ?>
	    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
			<td>
				<?php echo UI::createCheckBox("id_scorecard[$id]",$id_scorecard,$row['id_scorecard'][$id],$rowscorecard['nama'],true,"echeck$id_kajianrisiko id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			</td>
	    </tr>
	    <?php } ?>
	<?php } ?>
	</tbody>
	</table>

	<script type="text/javascript">
		$(".echeck<?=$id_kajianrisiko?>").change(function(){
			var id = $(this).attr("data-id");		
			var child = $(".idparent"+id);

			if($(this).is(":checked")){
				child.prop("checked", true);
			}else{
				child.prop("checked", false);
			}

			child.change();

		});

<?php if($id_kajianrisiko){ ?>
$(function(){
  $(".tree-table<?=$id_kajianrisiko?>").treetable({ expandable: true });
  $(".tree-table<?=$id_kajianrisiko?>").treetable('expandAll');
});
<?php } ?>
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
}
?>