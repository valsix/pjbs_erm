        <div class="container-fluid">
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <!-- Basic Table -->
            <?php
                $modeheader = $mode;
                if(!$editedheader){
                  $modeheader = 'detail';
                }
                $is_readmore_scorecard = false;
                if($page_ctrl!='panelbackend/risk_scorecard')
                  $is_readmore_scorecard = true;
                ?>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

                      <?=FlashMsg()?>
                          <?php if($is_readmore_scorecard){ ?>
                          <div class="col-lg-8">
                            <h1>
                            <?=$mtjeniskajianrisikoarr[$rowheader['id_kajian_risiko']]?>
                             &nbsp;&nbsp;<a href="javascript:void(0);" onclick="$('#body-socrecard').slideToggle(200)"><span class="glyphicon glyphicon-tags" style="font-size: 16px;"></span></a>
                            </h1>
                            <small><?=$rowheader['scope']?></small>

                          </div>
                          <div class="col-lg-4">
                          <?php } ?>
                          <div class="pull-right">
                          <?php echo UI::showButtonModeKajianRisiko($modeheader, $rowheader['id_scorecard'], $editedheader, null, ($is_readmore_scorecard?"btn-plain":null), $this->access_role_custom['panelbackend/risk_scorecard'])?>

                          </div>
                          <?php if($is_readmore_scorecard){ ?>
                          </div>
                          <?php } ?>
                          <div style="clear: both;"></div>

                        </div>
                        <div class="body table-responsive" id="body-socrecard" <?php if($is_readmore_scorecard){ ?> style="display:none" <?php } ?>>

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <div class="col-sm-12">

                      <?php
                      $from = UI::createSelect('owner',$ownerarr,$rowheader['owner'],$editedheader,'form-control select2',"data-ajax--data-type=\"json\" data-ajax--url=\"".site_url('panelbackend/ajax/listjabatan')."\"");
                      echo UI::createFormGroup($from, $rules["owner"], "owner", "Owner", false, 2, $editedheader);
                      ?>

                      <?php
                      $from = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$rowheader['id_kajian_risiko'],$editedheader,'form-control ',"style='width:auto; max-width:100%;'");
                      echo UI::createFormGroup($from, $rules["id_kajian_risiko"], "id_kajian_risiko", "Jenis Kajian Risiko", false, 2, $editedheader);
                      ?>

                      <?php
                      $from = UI::createTextArea('scope',$rowheader['scope'],'','',$editedheader,'form-control',"");
                      echo UI::createFormGroup($from, $rules["scope"], "scope", "Scope", false, 2, $editedheader);
                      ?>

                      <?php
                      $from = UI::showButtonMode("save", null, $editedheader, null, null, $this->access_role_custom['panelbackend/risk_scorecard']);
                      echo UI::createFormGroup($from, null, null, null, false, 2);
                      ?>
                      </div>

                        </div>
                    <?php if($rowheader['id_scorecard']){ ?>
                      <div style="clear: both;"></div>
                        <?=$this->auth->GetTabScorecard($mode, $rowheader['id_scorecard'], $rowheader1['id_risiko']);?>

                          <?php echo $content1;?>
                          <div style="clear: both;"></div>
                    <?php } ?>

                      <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript">

<?php if(Access('add','panelbackend/risk_scorecard')){ ?>
  function goAddKajianRisiko(){
      window.location = "<?=base_url("panelbackend/risk_scorecard/add")?>";
  }
<?php } ?>

<?php if(Access('edit','panelbackend/risk_scorecard')){ ?>
  function goEditKajianRisiko(){
      window.location = "<?=base_url("panelbackend/risk_scorecard/edit/".$rowheader['id_scorecard'])?>";
  }
<?php } ?>

<?php if(Access('delete','panelbackend/risk_scorecard')){ ?>
  function goDeleteKajianRisiko(){
      if(confirm("Apakah Anda yakin akan menghapus ?")){
          window.location = "<?=base_url("panelbackend/risk_scorecard/delete/".$rowheader['id_scorecard'])?>";
      }
  }
<?php } ?>

  function goListKajianRisiko(){
      window.location = "<?=base_url("panelbackend/risk_scorecard")?>";
  }
</script>
<style type="text/css">
  .card .header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: normal;
    color: #111;
    line-height: 30px;
    text-transform: uppercase;
}
</style>
