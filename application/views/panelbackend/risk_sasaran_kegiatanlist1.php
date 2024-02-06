        <div class="container-fluid">
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <div class="pull-left filter-list">
<?php
$from = UI::createSelect('pic',$jabatanarr,$pic,true,'form-control select2',"data-ajax--data-type=\"json\" data-ajax--url=\"".site_url('panelbackend/ajax/listjabatan')."\" onchange='goSubmit(\"setpic\")'");
echo UI::createFormGroup($from, null, "pic", "PIC : ", false, 2);
?>

                          </div>
                          <div class="pull-right">
                            <?php echo UI::showButtonMode($mode, $row[$pk])?>
                          </div>
                          <div style="clear: both;"></div>
                        </div>
                        <div class="body table-responsive">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>

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
                    echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rows[$pk]"))."'>".$val."</a>";
                    if($kajian['2']){
                      echo "<br/>";
                      foreach ($kajian['2'] as $r) {
                          echo ' <a class="btn waves-effect btn-xs btn-success" href="'.base_url("panelbackend/risk_risiko/add/".$r['id_scorecard']."/".$rows['id_sasaran_strategis']."/".$rows[$pk]).'"><span class="glyphicon glyphicon-plus"></span> '.$r['nama'].'</a> ';
                      }
                    }
                    echo "</td>";
              }elseif($rows1['name']=='id_sasaran_strategis'){
                    echo "<td><a href='".($url=base_url("panelbackend/risk_sasaran_strategis/detail/$val"))."'>".$rows1["value"][$val]."</a>";
                    if($kajian['1']){
                      echo "<br/>";
                      foreach ($kajian['1'] as $r) {
                          echo ' <a class="btn waves-effect btn-xs btn-success" href="'.base_url("panelbackend/risk_risiko/add/".$r['id_scorecard']."/".$val).'"><span class="glyphicon glyphicon-plus"></span> '.$r['nama'].'</a> ';
                      }
                    }
                    echo "</td>";
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
          if(!$this->access_role['view_all_direktorat'] && !$jabatanarr[$rows['owner']]){
              echo "<td></td>";
          }else{
            echo "<td style='text-align:left'>
            ".UI::getButton('edit', $rows[$pk], 'class="btn btn-xs btn-warning"')."
          ".UI::getButton('delete', $rows[$pk], 'class="btn btn-xs btn-danger"')."
            </td>";
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



                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>