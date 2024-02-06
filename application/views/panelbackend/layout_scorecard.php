<div class="container-fluid">
    <?php /*<div class="block-header">
        <h2>
<?=$page_title?>
<?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
    </div>*/ ?>
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

                <h1>
                <?=$rowheader['nama']?>
                </h1>
                <?=status_proyek($rowheader['id_status_proyek'])?>

                <div class="pull-right" style="top: 17px;position: absolute;right: 70px;">

                <?php if(!$notab){ ?>
                &nbsp;&nbsp;&nbsp; <a href="<?=site_url('panelbackend/risk_risiko/index/'.$rowheader['id_scorecard'])?>" class='btn waves-effect btn-xs btn-default'><span class="glyphicon glyphicon-th-list"></span> List <?php if($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='peluang'){ ?>Peluang<?php }else{ ?> Risiko<?php } ?></a>
                <?php } ?>
                </div>
                        <ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 10px;margin-top: 0px;
    margin-bottom: 10px;">
                    <li>
                        <a  href="<?=base_url("panelbackend/risk_scorecard")?>"><i class="material-icons">home</i></a>
                    </li>
                    <li>
                        <?php $id_kajian_risiko = $rowheader['id_kajian_risiko']; ?>
                        <a   href="<?=base_url("panelbackend/risk_scorecard/index/$id_kajian_risiko")?>"><?=strtoupper($mtjeniskajianrisikoarr[$rowheader['id_kajian_risiko']])?></a>
                    </li>
                    <?php if(($rowheader['broadcrumscorecard'])){ ?>
                        <?php foreach($rowheader['broadcrumscorecard'] as $k=>$v){ ?>
                            <li><a   href="<?=base_url("panelbackend/risk_scorecard/index/$id_kajian_risiko/$k")?>"><?=$v?></a></li>
                        <?php } ?>
                        <li><?=strtoupper($rowheader['nama'])?></li>
                <?php } ?>
                        </ol>
                <small>
                <B>OWNER :</B> <?=$ownerarr[$rowheader['owner']]?>
                </small><br/>
                <small>
                <B>SCOPE :</B> <?=$rowheader['scope']?>
                <?php
                if((!$rowheader['scope'] or trim($rowheader['scope'])=='-') && $this->access_role['edit'] && Access('edit','panelbackend/risk_scorecard')){ ?>
                <a href="<?=site_url('panelbackend/risk_scorecard/edit/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect btn btn-xs btn-default">Isi Scope</a>
                <?php } ?>
                </small>

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <?php
                if($this->access_role['edit'] && Access('edit','panelbackend/risk_scorecard')){ ?>
                            <li><a href="<?=site_url('panelbackend/risk_scorecard/edit/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect waves-block">Edit Scorecard</a></li>
                <?php } ?>
                            <?php
                if(Access('delete','panelbackend/risk_scorecard')){ ?>
                            <li><a href="<?=site_url('panelbackend/risk_scorecard/delete/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect waves-block">Delete Scorecard</a></li>
                <?php } ?>
                            <?php if($rowheader1['id_risiko']){ ?>
                            <li><a href="<?=site_url('panelbackend/risk_log_risiko/index/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Log History</a></li>
                            <li><a href="<?=site_url('panelbackend/risk_review/index/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Review / Diskusi</a></li>
                            <?php } ?>
                            <?php if($rowheader1['id_risiko']){ ?>
                            <li><a target='_BLANK' href="<?=site_url('panelbackend/risk_risiko/log_history/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Arsip Risiko</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php 

            if($page_ctrl=='panelbackend/risk_risiko' and $mode=='index'){ ?>
                <div style="width:49%;float:left;text-align: center;border: 0.5em solid #e6e6e6;border-bottom: 0px; border-right: 0px" class="info-box-flat<?=($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='risiko'?'-active':'')?>">
                  <div class="content">
                      <div class="text">
                      <a href="javascript::void(0)" onclick="goSubmit('set_risiko')">
                      RISIKO
                      </a>
                      </div>
                  </div>
                </div>
                <div style="width:51%;float:left;text-align: center;border: 0.5em solid #e6e6e6;border-bottom: 0px;" class="dark-tooltip info-box-flat<?=($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='peluang'?'-active':'')?>" rel="tooltip" title="Peluang tidak wajib ditambahkan, masukan peluang apabila ada potensi peluang di divisi Anda.">
                  <div class="content">
                      <div class="text">
                      <a href="javascript::void(0)" onclick="goSubmit('set_peluang')">
                      PELUANG
                      </a>
                      </div>
                  </div>
                </div>
                <div style="clear: both;"></div>
            <?php }else{ ?>
                <div class="body" style="padding: 0px;">
                    <div id="wizard_horizontal" role="application" class="wizard clearfix">
                        <div class="steps clearfix">
                        <?=FlashMsg()?>
                <?php //if(!$notab){ ?>
                        <?=$this->auth->GetTabScorecard($mode, $rowheader['id_scorecard'], $rowheader1['id_risiko'], $rowheader1['is_finish'], $rowheader['id_nama_proses'], $rowheader['is_info'], $notab, $is_peluang);?>
                <?php //} ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?=$content1;?>
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
