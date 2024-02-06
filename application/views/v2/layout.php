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
                        <?php 

                        if(strtotime(date('d-m-Y'))<=strtotime(($row['tgl_akhir_efektif']?$row['tgl_akhir_efektif']:date('d-m-Y')))){
                          $mn = UI::showButtonMode($mode, $row[$pk]);

                        }

                        ?>
                          <div class="header">
                            <div class="pull-left">
                              <?=$str_left?>
                            </div>
                            <div class="pull-right">
                              <button type="button"  class="btn waves-effect  btn-primary" onclick="goAdd()" ><span class="glyphicon glyphicon-plus"></span> Add New</button> 

                            </div>
                            <div style="clear: both;"></div>
                          </div>
                        <div class="body table-responsive" style="<?=($mode=='index')?'padding:0px':''?>">

                        <?php  if($_SESSION[SESSION_APP]['loginas']){ ?>
                        <div class="alert alert-warning" style="
              position: fixed;
              top: 50px;
              text-align: center;
              padding: 3px;
              z-index: 100;
              width: 100%;
              margin: 0px -15px;
              background: #ff960094 !important;">
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
                                        echo "<td><a href='".($url=base_url($page_ctrl."/detail/$rows[$pk]"))."'>$val</a></td>";   
                                    }elseif($rows1['name']=='isi'){
                                        echo "<td>".ReadMore($val,$url)."</td>";
                                    }else{
                                        switch ($rows1['type']) {
                                            case 'list':
                                                echo "<td>".$rows1["value"][$val]."</td>";
                                                break;
                                            case 'number':
                                                echo "<td style='text-align:right'>".(float)$val."</td>";
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


                          
                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-bottom: 6px !important;
    max-width: none !important;
}
</style>