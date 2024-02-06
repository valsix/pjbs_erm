<div  class="header">
    <div class="pull-left">
      <h2 ><?=strtoupper($page_title)?></h2>

    <?php
    if(isset($ischild) && count($ischild)>1)
        echo "<br/>".UI::createCheckBox('risiko_sendiri',1,$risiko_sendiri,'Risiko "'.$rowheader['nama'].'"',true, '', "onclick='goSubmit(\"filter_sendiri\")'");
?>
    <?php 
    if($rowheader['id_proyek'] && $_SESSION[SESSION_APP][$rowheader['id_scorecard']]<>'peluang'){
    ?>
    <table>
        <tr>
            <td><?=UI::createExportImport()?></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
                <?php if($rowheader['id_status_proyek']=='1'){ ?>
                    <b>Laporan Akhir Project :</b> <?=UI::createUpload('fileakhirproject', $row['fileakhirproject'], $page_ctrl, $is_open_risiko, "Select files...", null, true, $rowheader);?>
                <?php } ?>
            </td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
                <?php if($row['fileakhirproject'] && Access("view_all_direktorat") && $is_open_risiko){ ?>
                    <button type="button" class="btn btn-warning" onclick='goSubmit("close_all")'>Close All Risiko</button>
                <?php } ?>
            </td>
        </tr>
    </table>
        


    

    <?php } ?>
    </div>
    <div class="pull-right" style="text-align: right;">
    <?php echo UI::showButtonMode($mode, null, false, '','btn-lg')?>
    </div>
    <div style="clear: both;"></div>
</div>

<div class="body table-responsive" style="padding:0px">

  <table class="table table-bordered table-hover dataTable">
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
                  echo "<td><a href='".($url=base_url("panelbackend/risk_risiko/detail/{$rows['id_scorecard']}/$rows[$pk]"))."'>".nl2br($val)."</a>";

                  echo labelverified($rows);

                  echo "</td>";
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }elseif($rows1['name']=='id_status_pengajuan'){
                echo "<td style='text-align:center'>".labelstatus($val, $rows)."</td>";
            }elseif($rows1['name']=='status_risiko'){
                echo "<td style='text-align:center'>".labelstatusrisiko($val)."</td>";
            }elseif($rows1['name']=='inheren' or $rows1['name']=='control' or $rows1['name']=='risidual'){
                echo labeltingkatrisiko($val);
            }elseif($rows1['name']=='nomor'){
                echo "<td style='text-align:center'>$val</td>";
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
                        echo "<td>".nl2br($val)."</td>";
                        break;
                }
            }
    	}
        if(((accessbystatus($rows['id_status_pengajuan']) && !$rows['is_lock'] && $rows['id_scorecard']==$this->data['rowheader']['id_scorecard']) or $this->access_role['view_all_direktorat']) & $rows['status_risiko']=='1'){
            echo "<td style='text-align:right'>";
            echo UI::showMenuMode('inlist', $rows[$pk]);
            echo "</td>";
        }else{
            echo "<td></td>";
        }
    	echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>
</div>
