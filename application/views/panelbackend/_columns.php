<?php
ob_start();
if(($header)){ 
?>

<table class="tree-table1 table table-hover dataTable" style="margin-top: 0px">
<tbody>
<tr data-tt-id='AC'>
	<td>
		<?php echo UI::createCheckBox("idrootheader[0]",0,0,"Pilih Semua",true,"echeck1", "data-id='AC'");?>
	</td>
</tr>
<?php 

foreach($header as $id=>$rowheader){ 
	if(is_array($rowheader)){ 
		$id_parent = "AC";
		$label = key($rowheader);
		$header1 = $rowheader[$label];
		?>

	    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
			<td>
				<?php echo UI::createCheckBox("header[$id]",$id,$row['header'][$id],$label,true,"echeck1 id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			</td>
	    </tr>

		<?php
		$id_parent = $id;
		foreach($header1 as $id1=>$rowheader1){ 
			$label = $rowheader1;
	?>

	    <tr data-tt-id='<?=$id1?>' data-tt-parent-id='<?=$id_parent?>'>
			<td>
				<?php echo UI::createCheckBox("header[$id1]",$id1,$row['header'][$id],$label,true,"echeck1 id$id1 idparent$id_parent", "data-id='$id1' data-idparent='$id_parent'");?>
			</td>
	    </tr>

	<?php }}else{
		$label = $rowheader;
		$id_parent = "AC";
	?>
	    <tr data-tt-id='<?=$id?>' data-tt-parent-id='<?=$id_parent?>'>
			<td>
				<?php echo UI::createCheckBox("header[$id]",$id,$row['header'][$id],$label,true,"echeck1 id$id idparent$id_parent", "data-id='$id' data-idparent='$id_parent'");?>
			</td>
	    </tr>
<?php }} ?>
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