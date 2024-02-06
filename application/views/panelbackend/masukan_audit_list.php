

<div class="col-sm-5">
<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$id_kajian_risiko,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'label'=>'Kajian Risiko'
    ));
?>
</div>
<?php if(($scorecardarr)){ ?>
<div class="col-sm-7">
<?php
$form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'label'=>'Risk Profile'
    ));
?>
</div>
<?php } ?>
                          <div style="clear: both;"></div>
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
            if($rows1['name']=='nama_risiko'){
                echo "<td><a href='".base_url("panelbackend/risk_risiko/detail/$rows[id_scorecard]/$rows[id_risiko]")."'>$val</a></td>";   
            }elseif($rows1['name']=='nama_aktifitas'){
                echo "<td><a href='".base_url("panelbackend/risk_mitigasi/detail/$rows[id_risiko]/$rows[id_mitigasi]")."'>$val</a></td>";   
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
        echo UI::showMenuMode('inlist', $rows[$pk]);
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