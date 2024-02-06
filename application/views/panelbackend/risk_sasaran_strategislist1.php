<div  class="header">
    <div class="pull-left">
      <h2>SASARAN STRATEGIS</h2>
    </div>
    <div class="pull-right">
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive">
  <table class="table table-striped table-hover dataTable">
    <thead>
    <?=UI::showHeader($header, $filter_arr, $list_sort, $list_order)?>
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
    	echo "<tr>";
    	echo "<td>$i</td>";
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];
            echo "<td>$val</td>";
    	}
    	echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header))."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>
</div>