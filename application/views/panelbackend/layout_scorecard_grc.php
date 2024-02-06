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
        if($page_ctrl!='panelbackend/mekanisme_grc')
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
                    &nbsp;&nbsp;&nbsp; <a href="<?=site_url('panelbackend/risk_risiko_grc/index/'.$rowheader['id_scorecard'])?>" class='btn waves-effect btn-xs btn-default'><span class="glyphicon glyphicon-th-list"></span> List <?php if($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='peluang'){ ?>Peluang<?php }elseif($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='risiko'){ ?> Risiko<?php } else{ ?> Lampiran<?php } ?></a>
                    <?php } ?>
                </div>
                <ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 10px;margin-top: 0px; margin-bottom: 10px;">
                    <li>
                        <a  href="<?=base_url("panelbackend/mekanisme_grc")?>"><i class="material-icons">home</i></a>
                    </li>
                    <li>
                        <?php $id_kajian_risiko = $rowheader['id_kajian_risiko']; ?>
                        <a   href="<?=base_url("panelbackend/mekanisme_grc/index/$id_kajian_risiko")?>"><?=strtoupper($mtjeniskajianrisikoarr[$rowheader['id_kajian_risiko']])?></a>
                    </li>
                    <?php if(($rowheader['broadcrumscorecard'])){ ?>
                        <?php foreach($rowheader['broadcrumscorecard'] as $k=>$v){ ?>
                            <li><a   href="<?=base_url("panelbackend/mekanisme_grc/index/$id_kajian_risiko/$k")?>"><?=$v?></a></li>
                        <?php } ?>
                        <li><?=strtoupper($rowheader['nama'])?></li>
                    <?php } ?>
                </ol>

                <table style="width:100%">
                    <tr>
                        <td>
                            <B>No. GRC </B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <?=$rowheader['nomor_ba_grc']?>
                        </td>
                        <td>
                            <B>Nama Program</B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <?=$rowheader['nama']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <B>Sasaran/KPI </B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <?=$rowheader['sasaran_kpi']?>
                        </td>
                        <td>
                            <B>Klasifikasi Progran </B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <?=$rowheader['klasifikasi_program']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <B>Estimasi Nilai </B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            Rp. <?=numberToIna($rowheader['estimasi_biaya'])?>
                        </td>
                        <td>
                            <B>Sumber Dana </B>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <?=$rowheader['sumber_dana']?>
                        </td>
                    </tr>
                    
                </table>
                <!-- <small>
                    <B>No. GRC :</B> <?=$rowheader['nomor_ba_grc']?>
                </small><br/>
                <small>
                    <B>Nama Program :</B> <?=$rowheader['nama']?>
                </small><br/>
                <small>
                    <B>Sasaran/KPI :</B> <?=$rowheader['sasaran_kpi']?>
                </small><br/>
                <small>
                    <B>Klasifikasi Progran :</B> <?=$rowheader['klasifikasi_program']?>
                </small><br/>
                <small>
                    <B>Estimasi Nilai :</B> <?=$rowheader['estimasi_biaya']?>
                </small><br/>
                <small>
                    <B>Sumber Dana :</B> <?=$rowheader['sumber_dana']?>
                </small><br/> -->
                <!-- <small>
                    <B>SCOPE :</B> <?=$rowheader['scope']?>
                    <?php
                    if((!$rowheader['scope'] or trim($rowheader['scope'])=='-') && $this->access_role['edit'] && Access('edit','panelbackend/mekanisme_grc')){ ?>
                    <a href="<?=site_url('panelbackend/mekanisme_grc/edit/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect btn btn-xs btn-default">Isi Scope</a>
                    <?php } ?>
                </small> -->

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <?php
                            if($this->access_role['edit'] && Access('edit','panelbackend/mekanisme_grc')){ ?>
                                <li><a href="<?=site_url('panelbackend/mekanisme_grc/edit/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect waves-block">Edit Scorecard</a></li>
                            <?php } ?>
                            <?php
                            if(Access('delete','panelbackend/mekanisme_grc')){ ?>
                                <li><a href="<?=site_url('panelbackend/mekanisme_grc/delete/'.$rowheader['id_kajian_risiko'].'/'.$rowheader['id_scorecard'])?>" class=" waves-effect waves-block">Delete Scorecard</a></li>
                            <?php } ?>
                            <?php if($rowheader1['id_risiko']){ ?>
                                <li><a href="<?=site_url('panelbackend/risk_log_risiko/index/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Log History</a></li>
                                <li><a href="<?=site_url('panelbackend/risk_review/index/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Review / Diskusi</a></li>
                            <?php } ?>
                            <?php if($rowheader1['id_risiko']){ ?>
                                <li><a target='_BLANK' href="<?=site_url('panelbackend/risk_risiko_grc/log_history/'.$rowheader1['id_risiko'])?>" class=" waves-effect waves-block">Arsip Risiko</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php 

            if($page_ctrl=='panelbackend/risk_risiko_grc' and $mode=='index'){ ?>
                <div style="width:33%;float:left;text-align: center;border: 0.5em solid #e6e6e6;border-bottom: 0px; border-right: 0px" class="info-box-flat<?=($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='lampiran'?'-active':'')?>">
                  <div class="content">
                      <div class="text">
                      <a href="javascript::void(0)" onclick="goSubmit('set_lampiran')">
                      Lampiran
                      </a>
                      </div>
                  </div>
                </div>
                <div style="width:33%;float:left;text-align: center;border: 0.5em solid #e6e6e6;border-bottom: 0px; border-right: 0px" class="info-box-flat<?=($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='risiko'?'-active':'')?>">
                  <div class="content">
                      <div class="text">
                      <a href="javascript::void(0)" onclick="goSubmit('set_risiko')">
                      RISIKO
                      </a>
                      </div>
                  </div>
                </div>
                <div style="width:34%;float:left;text-align: center;border: 0.5em solid #e6e6e6;border-bottom: 0px;" class="dark-tooltip info-box-flat<?=($_SESSION[SESSION_APP][$rowheader['id_scorecard']]=='peluang'?'-active':'')?>" rel="tooltip" title="Peluang tidak wajib ditambahkan, masukan peluang apabila ada potensi peluang di divisi Anda.">
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
                        <?=$this->auth->GetTabScorecardGrc($mode, $rowheader['id_scorecard'], $rowheader1['id_risiko'], $rowheader1['is_finish'], $rowheader['id_nama_proses'], $rowheader['is_info'], $notab, $is_peluang);?>
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
<?php
    function numberToIna($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
    {
        $arr_value = explode(".", $value);
        
        if(count($arr_value) > 1)
            $value = $arr_value[0];
        
        if($value < 0)
        {
            $neg = "-";
            $value = str_replace("-", "", $value);
        }
        else
            $neg = false;
            
        $cntValue = strlen($value);
        //$cntValue = strlen($value);
        
        if($cntValue <= $digit)
            $resValue =  $value;
        
        $loopValue = floor($cntValue / $digit);
        
        for($i=1; $i<=$loopValue; $i++)
        {
            $sub = 0 - $i; //ubah jadi negatif
            $tempValue = $endValue;
            $endValue = substr($value, $sub*$digit, $digit);
            $endValue = $endValue;
            
            if($i !== 1)
                $endValue .= ".";
            
            $endValue .= $tempValue;
        }
        
        $beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
        
        if($cntValue % $digit == 0)
            $resValue = $beginValue.$endValue;
        else if($cntValue > $digit)
            $resValue = $beginValue.".".$endValue;
        
        //additional
        if($symbol == true && $resValue !== "")
        {
            $resValue = $resValue;
        }
        
        if($minusToBracket && $neg)
        {
            $resValue = "(".$resValue.")";
            $neg = "";
        }
        
        if($minusLess == true)
        {
            $neg = "";
        }

        if(count($arr_value) == 1)
            $resValue = $neg.$resValue;
        else
            $resValue = $neg.$resValue.",".$arr_value[1];
        

        
        //$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

        return $resValue;
    }
?>