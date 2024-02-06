<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="pull-left">
                    <h2>
                    <?=strtoupper($mtjeniskajianrisikoarr[$id_kajian_risiko])?>
                    </h2>
                        <ol class="breadcrumb no-padding" style="padding-left: 0px;font-size: 10px;margin-top: 10px;
    margin-bottom: 0px;">
                        <li>
                            <a  href="<?=base_url("panelbackend/mekanisme_grc")?>"><i class="material-icons">home</i></a>
                        </li>
                        <li>
                            <a   href="<?=base_url("panelbackend/mekanisme_grc/index/$id_kajian_risiko")?>"><?=strtoupper($mtjeniskajianrisikoarr[$id_kajian_risiko])?></a>
                        </li>

                        <?php foreach($broadcrum as $k=>$v){ ?>
                            <li>
                                <?php if($k){ ?>
                                    <a  href="<?=base_url("panelbackend/mekanisme_grc/index/$id_kajian_risiko/$k")?>"><?=$v?></a>
                                <?php }else{ ?>
                                    <?=$v?>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        </ol>
                    </div>
                    <div class="pull-right">
                        <?php /*if($id_parent_scorecard){ ?>
                        <a class="btn btn-success waves-effect" href="<?=site_url('panelbackend/mekanisme_grc/index/'.$id_kajian_risiko)?>"><span class="glyphicon glyphicon-backward"></span> BACK</a>
                        <?php }else{ ?>
                        <a class="btn btn-success waves-effect" href="<?=site_url('panelbackend/mekanisme_grc')?>"><span class="glyphicon glyphicon-backward"></span> BACK</a>
                        <?php }*/ ?>
                        <?php 
                        if($this->access_role['view_all_direktorat']){

                            if($id_parent_scorecard==528){
                                echo '<button type="button" class="btn waves-effect btn-sm btn-success" onclick="goSubmit(\'sync\')"><span class="glyphicon glyphicon-refresh"></span> Update From Promis</button>';
                            }

                            echo UI::showButtonMode('add|edit|delete', $id_parent_scorecard, ($edited && $this->access_role['view_all_direktorat']));
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="body" style="<?=($mode=='index')?'padding:0px':''?>">
                    <?=FlashMsg()?>

<?php
$is_proyek = ($id_parent_scorecard==528);
$is_om = ($id_parent_scorecard==527);
unset($mtstatusunitarr['']);
?>

<table class="tree-table table table-hover dataTable table-bordered">
    <?php if($is_proyek){ ?>
        <thead>
            <tr>
                <th>Nama Proyek</th>
                <th>Jumlah Risiko</th>
                <th>Status Proyek</th>
                <th>On Cost (%)</th>
                <th>On Time (%)</th>
                <th>On Spec (%)</th>
                <th>On Safety (%)</th>
            </tr>
        </thead>
    <?php }elseif($is_om){ ?>
        <thead>
            <tr>
                <th>Nama Unit</th>
                <th width="150px">Status Unit</th>
            </tr>
        </thead>
    <?php } ?>
<tbody>
<?php $no=1; foreach($rows as $row){ 

    if(!$row['nama'])
        $row['nama'] = '-';
    
    if(!$row['id_parent'] && $row['id']<>'A')
        $row['id_parent']='A';

    ?>
    <?php if(!$row['id_scorecard']){ ?>
    <tr data-tt-id='<?=$row['id']?>' data-tt-parent-id='<?=$row['id_parent']?>'>
        <td>
            <b><?=$row['nama']?></b>
        </td>
    </tr>
    <?php }elseif($row['navigasi']=='1' or $row['navigasi']=='2'){ ?>
    <tr data-tt-id='<?=$row['id']?>' data-tt-parent-id='<?=$row['id_parent']?>'>
        <td>
            <b><a href="<?=base_url("panelbackend/mekanisme_grc/index/$id_kajian_risiko/$row[id_scorecard]")?>"><?=$row['nama']?></a></b>
        </td>
    </tr>
    <?php }elseif($row['owner']==$owner){ ?>
    <tr data-tt-id='<?=$row['id']?>' data-tt-parent-id='<?=$row['id_parent']?>' class='bg-light-blue'>
        <td>
            <a style="color:#fff" href="<?=base_url("panelbackend/risk_risiko_grc/index/$row[id_scorecard]")?>"><?php if($is_proyek){ ?><?=$no++?>. <?php } ?> <?=$row['nomor_ba_grc'].' '.$row['nama']?></a>
        </td>
        <?php if($is_proyek){ ?>

        <td align="center">
            <?=$row['jumlah_risiko']?>
        </td>

        <td align="center">
            <?=status_proyek($row['id_status_proyek'])?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_cost'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_time'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_spec'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_safety'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <?php }elseif($is_om){ ?>

        <td align="center">
            <?=$mtstatusunitarr[$row['id_status_unit']]?>
        </td>

        <?php } ?>
    </tr>
    <?php }elseif(!$row['owner']){ ?>
    <tr data-tt-id='<?=$row['id']?>' data-tt-parent-id='<?=$row['id_parent']?>'>
        <td>
            <a href="<?=base_url("panelbackend/mekanisme_grc/edit/$id_kajian_risiko/$row[id_scorecard]")?>"><?=$row['nomor_ba_grc'].' '.$row['nama']?></a>
        </td>
    </tr>
    <?php }else{ ?>
    <tr data-tt-id='<?=$row['id']?>' data-tt-parent-id='<?=$row['id_parent']?>'>
        <td>
            <a href="<?=base_url("panelbackend/risk_risiko_grc/index/$row[id_scorecard]")?>"><?php if($is_proyek){ ?><?=$no++?>. <?php } ?><?=$row['nomor_ba_grc'].' '.$row['nama']?></a>
        </td>
        <?php if($is_proyek){ ?>

        <td align="center">
            <?=$row['jumlah_risiko']?>
        </td>

        <td align="center">
            <?=status_proyek($row['id_status_proyek'])?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_cost'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_time'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_spec'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <td align="center">
            <?php 
            $level = $row['on_safety'];
            if($level!==''){
                $color = '';
                if($level>=102.5){
                    $color = '#127dd0';
                }elseif($level>=100){
                    $color = '#58b051';
                }elseif($level>=97.5){
                    $color = '#f9973a';
                }else{
                    $color = '#f14236';
                }

                echo "<span class='label' style='background:$color'>".$level."</span>";
            }
            ?>
        </td>

        <?php }elseif($is_om){ ?>

        <td align="center">
            <?=$mtstatusunitarr[$row['id_status_unit']]?>
        </td>

        <?php } ?>
    </tr>
    <?php } ?>
<?php } ?>
</tbody>
</table>

                </div>
            </div>
        </div>
    </div>
</div>