<?php $user_id = $_SESSION[SESSION_APP]['user_id']; ?>
<div  class="header">
    <div class="pull-left">
        <h2>MITIGASI RISIKO2 
    </h2>      
    </div>
    <div class="pull-right">
    <?php echo UI::showButtonMode($mode, null, false, '','btn-lg')?>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="body table-responsive" style="padding:0px">
  <table class="table table-bordered table-hover dataTable">
    <thead>
    <?=UI::showHeader($header, $filter_arr, $list_sort, $list_order, true, true, false)?>
    </thead>
    <tbody>
    <?php
$editedheader1 = ($editedheader1 && (!$rowheader1['is_lock'] or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat']));
    $i = $page;
    $unlock = 0;
    foreach($list['rows'] as $rows){
        $i++;
        echo "<tr>";
        foreach($header as $rows1){
            $val = $rows[$rows1['name']];
            if($rows1['name']=='nama_aktifitas'){
                  echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rowheader1[id_risiko]/$rows[$pk]"))."'>".nl2br($val)."</a><br/>".labelkonfirmasi($rows['status_konfirmasi']);

                  echo labelverified($rows);
                  
                  echo "</td>";
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }elseif($rows1['name']=='cba'){
                echo "<td style='text-align:center'>".($val?((float)$val)."%":'-')."</td>";
            }elseif($rows1['name']=='id_status_pengajuan'){
                echo "<td style='text-align:center'>".labelstatus($val)."</td>";
            }elseif($rows1['name']=='is_efektif'){
                echo "<td>".labelefektifitas($val)."</td>";
            }else{
                switch ($rows1['type']) {
                    case 'list':
                        echo "<td style='text-align:center'>".$rows1["value"][$val]."</td>";
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
        if($rows['is_lock']!='1')
            $unlock = 1;
        
        //if($pregressarr1[$rows['id_status_progress']]=='100' && $this->access_role['add'] && $this->access_role['edit'] /*&& $rows['id_mitigasi_sebelum']*/){
		if($pregressarr1[$rows['id_status_progress']]=='100' && $this->access_role['add'] && $this->access_role['edit'] /*&& $rows['id_mitigasi_sebelum']*/){
            if($is_administrator){
				echo "<td style='text-align:right'>
				".($pregressarr1[$rows['id_status_progress']]=='100'?"<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue('jadikan_control',$rows[id_mitigasi])\"><span class='glyphicon glyphicon-share'></span> Move to Control</button>
				<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue('close',$rows[id_mitigasi])\"><span class='glyphicon glyphicon-log-out'></span> Closed</button>
				":"")."
				</td>";
			}
			else{
				echo "<td style='text-align:right'>
				".($pregressarr1[$rows['id_status_progress']]=='100'?"<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue('jadikan_control',$rows[id_mitigasi])\"><span class='glyphicon glyphicon-share'></span> Move to Control</button>
				":"")."
				</td>";
			}
        }elseif((accessbystatus($rowheader1['id_status_pengajuan']) && $rows['is_lock']!='1') or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat']){
            
            echo "<td style='text-align:right'>
            ".UI::showMenuMode('inlist', $rows[$pk])."
            </td>";
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
<?php if(!$is_peluang){ ?>
</div>

<div class="card">
<div  class="header disp-inline-block">
<h2>TARGETED RESIDUAL RISK</h2>&nbsp;
<?=UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $rowheader1, $editedheader1);?>
<?php if(!$editedheader1 && $this->access_role['edit']  && (!$rowheader1['is_lock'] or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat'])){ 
        ?>
    <a href="<?=site_url("panelbackend/risk_mitigasi/index/$rowheader1[id_risiko]/0/1")?>" class="btn btn-xs btn-default fright"><span class="glyphicon glyphicon-edit"></span> Edit</a>
    <?php } ?>
</div>
<div class="body table-responsive">

<?php
  include "_kriteria.php";
?>

<div class="form-horizontal">
  <div class="row" style="margin: 0 -15px;">
  <div class="col-sm-4">
<?php
$from = UI::createSelect('residual_target_kemungkinan',$mtkemungkinanarr,$rowheader1['residual_target_kemungkinan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["residual_target_kemungkinan"], "residual_target_kemungkinan", 'Kemungkinan<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaKemungkinan"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
?>
</div>
</div>

  <div class="row" style="margin: 0 -15px;">

  <div class="col-sm-4">
<?php
$from = UI::createSelect('residual_target_dampak',$mtdampakrisikoarr,$rowheader1['residual_target_dampak'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["residual_target_dampak"], "residual_target_dampak", 'Dampak<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaDampak"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
?>
</div>
</div>


<?php
if($editedheader1){?>

  <div class="row" style="margin: 0 -15px;">
<div class="col-sm-4  col-btn-rating">
<?php

$from = UI::showButtonMode("save", null, $editedheader1,null,null,$access_role_risiko);
echo UI::createFormGroup($from, NULL, NULL, NULL, false, 5, $editedheader1);
?>
    </div>
</div>
<?php } ?>

</div>
</div>
<?php } ?>

<div  class="footer">
      <div class="col-sm-6 footer-info">
    <?=UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko'],((!$rowheader1['is_finish'] or $is_peluang or !$rowheader1['is_lock']) or ($unlock && $rowheader1['is_lock'])));?>
      </div>
	  
	  <?//modif dewangga 07-09-2023 agar user review tidak bisa download lampiran
	if($user_id=2547){}else{?>
		  <div class="col-sm-6 footer-info">
		<?=UI::createStatusRisiko($rowheader1['status_risiko'])?>
		  </div>
	<?}?>
	  
    <div style="clear: both;"></div>
</div>
<?php if($is_peluang){ ?>

</div>
<?php } ?>