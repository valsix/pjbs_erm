<?php
//$user_id = $_SESSION[SESSION_APP]['user_id'];
?>
<div  class="header">
    <div class="pull-left">
        <h2>KONTROL RISIKO 
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
    <?php
$editedheader1 = ($editedheader1 && (!$rowheader1['is_lock'] or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat']));
 //showHeader($header, $filter_arr, $list_sort, $list_order, $is_filter=true, $is_sort = true, $is_no = true)?>
    <?=UI::showHeader($header, $filter_arr, $list_sort, $list_order, true, true, false)?>
    </thead>
    <tbody>
    <?php
    $i = $page;
    $unlock = 0;
    foreach($list['rows'] as $rows){
        $i++;
        echo "<tr>";
        foreach($header as $rows1){
            $val = $rows[$rows1['name']];
            if($rows1['name']=='nama'){
                  echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rowheader1[id_risiko]/$rows[$pk]"))."'>".nl2br($val)."</a><br/>".labelkonfirmasi($rows['status_konfirmasi']);
                  echo labelverified($rows);

                  if($rows['no_lampiran'])
                    echo '<label class="label label-danger">Lampiran expired</label>';

                  echo "</td>";
            }elseif($rows1['name']=='isi'){
                echo "<td>".ReadMore($val,$url)."</td>";
            }elseif($rows1['name']=='id_status_pengajuan'){
                echo "<td style='text-align:center;'>".labelstatus($val)."</td>";
            }elseif($rows1['name']=='is_efektif'){
                echo "<td style='text-align:center;'>".labelefektifitas($val)."</td>";
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
		
		if($rows['is_efektif']=='1' && $is_administrator){
			echo "<td style='text-align:right'>
            ".($rows['is_efektif']=='1'?"
			<button type='button' class='btn btn-warning btn-xs' onclick=\"goSubmitValue('close',$rows[id_control])\"><span class='glyphicon glyphicon-log-out'></span> Closed</button>
			":"")."
            </td>";		
		}elseif((accessbystatus($rowheader1['id_status_pengajuan']) && $rows['is_lock']!='1') or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat']){
            echo "<td style='text-align:right'>
            ".UI::showMenuMode('inlistcontrol', $rows[$pk])."
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
</div>

<div class="card">
<div  class="header disp-inline-block">
<h2>CURRENT RISK</h2>&nbsp;
<?=UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $rowheader1, $editedheader1);?>
<?php if(!$editedheader1 && $this->access_role['edit']  && (!$rowheader1['is_lock'] or $this->access_role_custom['panelbackend/risk_risiko']['view_all_direktorat'])){ 
    ?>
    <a href="<?=site_url("panelbackend/risk_control/index/$rowheader1[id_risiko]/0/1")?>" class="btn btn-xs btn-default fright"><span class="glyphicon glyphicon-edit"></span> Edit</a>
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
$from = UI::createSelect('control_kemungkinan_penurunan',$mtkemungkinanarr,$rowheader1['control_kemungkinan_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["control_kemungkinan_penurunan"], "control_kemungkinan_penurunan", 'Kemungkinan<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaKemungkinan"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
?>
</div>
</div>

  <div class="row" style="margin: 0 -15px;">

  <div class="col-sm-4">
<?php
$from = UI::createSelect('control_dampak_penurunan',$mtdampakrisikoarr,$rowheader1['control_dampak_penurunan'],$editedheader1,'form-control ',"style='width:auto; max-width:100%;'");
    echo UI::createFormGroup($from, $rules["control_dampak_penurunan"], "control_dampak_penurunan", 'Dampak<button type="button" class="btn btn-plain waves-effect" data-toggle="modal" data-target="#kriteriaDampak"><span class="glyphicon glyphicon-info-sign"></span></button>', false, 5, $editedheader1);
?>
</div>
</div>

<?php
if($editedheader1){?>
  <div class="row" style="margin: 0 -15px;">
<div class="col-sm-4 col-btn-rating">
<?php
$from = UI::showButtonMode("save", null, $editedheader1,null,null,$access_role_risiko);
echo UI::createFormGroup($from, NULL, NULL, NULL, false, 5, $editedheader1);
?>
</div>
</div>
<?php } ?>
</div>
</div>
<div  class="footer">
      <div class="col-sm-6 footer-info">
    <?php
    if($rowheader['id_nama_proses']){
        echo UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko'], (!$rowheader1['is_finish'] or ($unlock && $rowheader1['is_lock'])));
    }else{
        echo UI::createStatusPengajuan('risiko',$rowheader1['id_status_pengajuan'],$rowheader1['id_risiko'], ($unlock && $rowheader1['is_lock']));
    }
    ?>
      </div>
		  <div class="col-sm-6 footer-info">
		<?=UI::createStatusRisiko($rowheader1['status_risiko'])?>
		  </div>

    <div style="clear: both;"></div>
</div>
</div>