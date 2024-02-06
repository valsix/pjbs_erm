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

                        <?
                        $tabelPK     = $this->data["tabelPK"];
                        $tabelFilter = $this->data["tabelFilter"];
                        $tabelHeader = $this->data["tabelHeader"];
                        $tabelData   = $this->data["tabelData"];

                        ?>

                        <table class="table table-striped table-hover dataTable">
                            
                        <thead>
                         <?=UI::showHeader($tabelHeader, $tabelFilter, $list_sort, $list_order)?>
                        </thead>

                            <tbody>
                            <?php
                            $i = $page;
                            foreach($tabelData as $rows)
                            {
                                $i++;
                                echo "<tr>";
                                echo "<td>$i</td>";
                                foreach($tabelHeader as $rows1){
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
                                ?>
                                <div class="dropdown" style="display:inline">
                                    <a href="javascript:void(0)" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#1f91f3;display:inline-block;">
                                    <span class="glyphicon glyphicon-option-vertical"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2" style="min-width: 10px; margin-top:-20px">
                                        <li><a href="javascript:void(0)"  class="waves-effect " onclick="goEdit('<?=$rows[$tabelPK]?>')" ><span class="glyphicon glyphicon-edit"></span> Edit </a> </li>        
                                        <li><a href="javascript:void(0)"  class="waves-effect " onclick="goDelete('<?=$rows[$tabelPK]?>')" ><span class="glyphicon glyphicon-remove"></span> Delete</a> </li>
                                
                                    </ul>
                                </div>
                                <?
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

<script>
function goEdit(id){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko/ubah/"+id;
}
function goDelete(id){
    if(confirm("Apakah Anda yakin akan menghapus ?")){
        window.location = "<?=base_url()?>v2/sk_matriks_resiko/hapus/"+id;
    }
}
function goAdd(){
    window.location = "<?=base_url()?>v2/sk_matriks_resiko/tambah";
}

</script>