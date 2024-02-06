        <div class="container-fluid">
        <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <div class="pull-left">
                          </div>
                          <div class="pull-right">
                            <?php if($this->access_role['add']){ ?>
                                <button type="button" class="btn btn-danger" onclick="sync()"><span class="glyphicon glyphicon-refresh" ></span> Sync Dengan Ellipse</button>
                            <?php } ?>
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

  <table class="table table-striped table-hover dataTable tree-table">
    <thead>
        <tr>
            <th style="max-width:auto">Nama Organisasi</th>
            <th>Urutan</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
        ?>

    	<tr data-tt-id='<?=$rows['id']?>' data-tt-parent-id='<?=$rows['id_parent']?>'>

        <?php
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];
            if($rows1['name']=='nama'){
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
        ?>
        <td><?=$rows['urutan']?></td>
        <td>
            <a href="javascript:void(0)" onclick="$('#key').val(<?=$rows[$pk]?>); goSubmit('sort_up');">
                <span class="glyphicon glyphicon-chevron-up"></span>
            </a>
            <a href="javascript:void(0)" onclick="$('#key').val(<?=$rows[$pk]?>); goSubmit('sort_down');">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
        </td>
        <?php
        echo "<td style='text-align:left'>";
        if(strtotime(date('d-m-Y'))<=strtotime(($rows['tgl_akhir_efektif']?$rows['tgl_akhir_efektif']:date('d-m-Y')))){

            echo UI::getButton('edit', $rows[$pk], 'class="btn btn-xs btn-warning"');
            echo UI::getButton('delete', $rows[$pk], 'class="btn btn-xs btn-danger"');

        }
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
                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-top: -15px !important;
    margin-bottom: 6px !important;
    max-width: none !important;
}
</style>
<script type="text/javascript">
    <?php if($this->access_role['add']){ ?>
        function sync(){
            if(confirm('Pastikan bahwa benar-benar ada perubahan struktur perusahaan.\nKarena semua data struktur organisasi dan jabatan akan di expiredkan, kemudian Anda harus memapingkan scorecard dan risiko kembali.')){
                goSubmit('sync');
            }
        }
    <?php } ?>
</script>