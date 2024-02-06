
  <table class="table table-bordered" id="export">
    <thead>
      <tr>
        <th style="width:10px">No</th>
        <?php 
          foreach($header as $rows){
           echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
          }
        ?>
      </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach($list['rows'] as $rows){
    	$i++;
      echo "<tr>";
    	echo "<td>$i</td>";
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];

        switch ($rows1['type']) {
            case 'list':
                echo "<td>".$rows1["value"][$val]."</td>";
                break;
            default :
                echo "<td>$val</td>";
                break;
        }
    	}
    	echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>