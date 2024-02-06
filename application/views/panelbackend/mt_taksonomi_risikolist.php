
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
            if($rows1['name']=='nama'){
                if($add_param)
                    echo "<td><a href='".($url=base_url($page_ctrl."/detail/$add_param/$rows[$pk]"))."'>$val</a></td>"; 
                else  
                    echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rows[$pk]"))."'>$val</a></td>";   
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
    	echo "<td style='text-align:right; width:4px'>";
       /* <a href='".site_url("panelbackend/mt_taksonomi_penyebab/index/".$rows['id_taksonomi_risiko'])."'class='btn btn-xs btn-primary'>Penyebab</a> 
        <a href='".site_url("panelbackend/mt_taksonomi_dampak/index/".$rows['id_taksonomi_risiko'])."'class='btn btn-xs btn-primary'>Dampak</a> 
        <a href='".site_url("panelbackend/mt_taksonomi_control/index/".$rows['id_taksonomi_risiko'])."'class='btn btn-xs btn-primary'>Control</a> 
        <a href='".site_url("panelbackend/mt_taksonomi_mitigasi/index/".$rows['id_taksonomi_risiko'])."'class='btn btn-xs btn-primary'>Mitigasi</a> 
        ";*/

        echo UI::showMenuMode('inlist', $rows[$pk]);
    	echo "</td>";
    	echo "</tr>";
    }
    if(!count($list['rows'])){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>