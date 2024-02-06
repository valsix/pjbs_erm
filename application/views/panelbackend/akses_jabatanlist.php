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
                            <div class="row">
                                <div class="col-sm-4" style="padding-top: 6px">
                                    <b>User Group : </b>
                                </div>
                                <div class="col-sm-6">
                            <?php
                            echo UI::createSelect('group_id',$grouparr,$row['group_id'], true, 'form-control', 'onchange="goSubmit(\'set_value\')"') ;
                            ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if($this->access_role['save']){ ?>
                                        <button type="button" class="btn-save btn btn-sm btn-success" onclick="goSave()"><span class="glyphicon glyphicon-floppy-save"></span> Save</button>
                                        <script>
                                        function goSave(){
                                            $(".btn-save").attr("disabled","disabled");
                                            $("#act").val('save');
                                            $("#main_form").submit();
                                        }
                                        </script>
                                    <?php } ?>
                                </div>
                            </div>
                          </div>
                          <div class="pull-right">
                            <?php 

                            if(strtotime(date('d-m-Y'))<=strtotime(($row['tgl_akhir_efektif']?$row['tgl_akhir_efektif']:date('d-m-Y')))){
                              echo UI::showButtonMode($mode, $row[$pk]);

                            }
                            ?>
                          </div>
                          <div style="clear: both;"></div>
                        </div>
                        <div class="body table-responsive">

                      <?php  if($_SESSION[SESSION_APP]['loginas']){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>


  <table class="table table-striped table-hover dataTable">
    <thead>
    <?=UI::showHeaderCheck($header, $filter_arr, $list_sort, $list_order)?>
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
    	$i++;
    	echo "<tr>";
    	echo "<td>";
        echo UI::createCheckBox("id_jabatan[$rows[$pk]]",1,$row['id_jabatan'][$rows[$pk]],null,true,'checkone','onclick="checkone(this)"');
        echo "</td>";
    	foreach($header as $rows1){
    		$val = $rows[$rows1['name']];
            if($rows1['name']=='isi'){
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
    	echo "<td style='text-align:right'></td>";
    	echo "</tr>";
    }
    if(!count($list['rows'])){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>

<script type="text/javascript">
    function checkAll(e){
        if($(e).is(":checked")){
            $(".checkone").prop("checked", true);
        }else{
            $(".checkone").prop("checked", false);
        }
    }
</script>
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