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
                        if($mn or $str_left){
                        ?>
                          <div class="header">
                            <div class="pull-left">
                              <?=$str_left?>
                            </div>
                            <div class="pull-right">
                              <?=$mn?>
                            </div>
                            <div style="clear: both;"></div>
                          </div>
                        <?php } ?>
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
                      <?php echo $content1;?>
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