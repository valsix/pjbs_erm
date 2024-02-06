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
                        <div class="body table-responsive">

                      <?php  if($_SESSION[SESSION_APP]['loginas']){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>

<div style="clear: both;"></div>
<div class="row" style="margin:-15px">
  <div id="wizard_horizontal" role="application" class="wizard clearfix">
    <div class="steps clearfix">
      <!-- Nav tabs -->
      <ul role='tablist'>
        <?php if(!($mode=='add' && $this->page_ctrl=='panelbackend/mt_taksonomi_risiko'))
        foreach(
            array(
                array("ctrl"=>"panelbackend/mt_taksonomi_risiko","uri"=>"/detail/".$rowheader1['id_taksonomi_area']."/".$rowheader2['id_taksonomi_risiko'],"label"=>"Risiko"),
                array("ctrl"=>"panelbackend/mt_taksonomi_penyebab","uri"=>"/index/".$rowheader2['id_taksonomi_risiko'],"label"=>"Penyebab"),
                array("ctrl"=>"panelbackend/mt_taksonomi_dampak","uri"=>"/index/".$rowheader2['id_taksonomi_risiko'],"label"=>"Dampak"),
                array("ctrl"=>"panelbackend/mt_taksonomi_control","uri"=>"/index/".$rowheader2['id_taksonomi_risiko'],"label"=>"Control"),
                array("ctrl"=>"panelbackend/mt_taksonomi_mitigasi","uri"=>"/index/".$rowheader2['id_taksonomi_risiko'],"label"=>"Mitigasi"),
            ) as $r){ 

            $active = "";
            if($r['ctrl']==$this->page_ctrl)
              $active = "current";

          ?>
          <li role="tab" class="done <?=$active?>" aria-disabled="true" aria-selected="false">
            <a href="<?=site_url($r['ctrl'].$r['uri'])?>"><?=$r['label']?></a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
      <!-- Tab panes -->
  <?php 
  if($mode<>'index')
    echo "<br/>";
  ?>
  <?=$content1;?>
</div>


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