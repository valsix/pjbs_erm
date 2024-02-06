<table class="table table-striped table-hover dataTable">
    <thead>
    <?=UI::showHeader($header, $filter_arr, $list_sort, $list_order)?>
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
        $warnaa=$rows['warna'];
        $fontcolor= '#000000';
        if ($warnaa=='#C00000') {
            $fontcolor= '#FFFFFF';
        }
    	echo "<tr style='background-color: $warnaa; color: $fontcolor;'>";
    	echo "<td>$i</td>";
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];
            if($rows1['name']=='nama'){
                echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rows[id_kemungkinan]/$rows[id_dampak]"))."'>$val</a></td>";   
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }else{
                switch ($rows1['type']) {
                    case 'list':
                        echo "<td>".$rows1["value"][$val]."</td>";
                        break;
                    case 'number':
                        echo "<td style='text-align:right'>$val</td>";
                    break;
                    case 'date':
                        echo "<td>".Eng2Ind($val,false)."</td>";
                        break;
                    case 'datetime':
                        echo "<td>".Eng2Ind($val)."</td>";
                        break;
                    default :
                        echo "<td>$val</td>";
                        break;
                }
            }
    	}
        echo "<td style='text-align:right'>";
        echo UI::showMenuMode('inlist', "$rows[id_kemungkinan]/$rows[id_dampak]");
        echo "</td>";
    	echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>