
                <td>
                    <?php if($rowheader['id_nama_proses']){ ?>

                        <b>Kode Aktivitas</b> 
                        <br/><?=$r['kode_aktifitas']?><br/>
                        <b>Nama Aktivitas</b> 
                        <br/><?=$r['nama_aktifitas']?><br/>

                    <?php }else{ ?>
                        <b>Sasaran Strategis</b> <br/>
                        <?=$r['sasaran_strategis']?><br/>

                        <?php if($rowheader['jenis_sasaran']=='2'){ ?>
                            <b>Sasaran Kegiatan</b> <br/>
                            <?=$r['sasaran_kegiatan']?><br/>
                        <?php } ?>
                        <br/>
                        <b>KPI</b> 
                        <br/>
                        <?=$r['kpi']?>

                    <?php } ?>
            </td>
            <td>
                    <b>Nama Risiko</b>
                    <br/>
                    <a href="<?=site_url('panelbackend/risk_risiko/detail/'.$r['id_scorecard'].'/'.$r['id_risiko'])?>" target='_BLANK'><?=$r['nama']?></a>
                    <br/>
                    <br/>
                    <b>Residual yang ditargetkan</b>
                    <br/>
                    <?php
                    if($rowheader['id_nama_proses']){
                        $from = "<div class='col-xs-4' style='padding-left: 0px !important;'>".UI::createSelect('control_kemungkinan_penurunan',$mtkemungkinanarr,$r['control_kemungkinan_penurunan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
                        $from .= "<div class='col-xs-4' style='padding-left: 0px !important;'>".UI::createSelect('control_dampak_penurunan',$mtdampakrisikoarr,$r['control_dampak_penurunan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
                        $from .= "<div class='col-xs-4' style='padding-left: 0px !important; margin-bottom:-10px'>".UI::tingkatRisiko('control_kemungkinan_penurunan', 'control_dampak_penurunan', $r, false)."</div>";
                    }else{
                        $from = "<div class='col-xs-4' style='padding-left: 0px !important;'>".UI::createSelect('residual_target_kemungkinan',$mtkemungkinanarr,$r['residual_target_kemungkinan'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
                        $from .= "<div class='col-xs-4' style='padding-left: 0px !important;'>".UI::createSelect('residual_target_dampak',$mtdampakrisikoarr,$r['residual_target_dampak'],false,'form-control ',"style='width:auto; max-width:100%;'")."</div>";
                        $from .= "<div class='col-xs-4' style='padding-left: 0px !important; margin-bottom:-10px'>".UI::tingkatRisiko('residual_target_kemungkinan', 'residual_target_dampak', $r, false)."</div>";
                    }

                    echo $from;
                    ?>
                    <br/>
                    <b>Progress mitigasi</b>
                    <a href='javascript:void(0)' onclick="mitigasi(<?=$r['id_risiko']?>)">
                        <?php
                        if($rr['ratamitigasi']==100)
                            echo "<span class='label label-success'>Complate</span>";
                        else
                            echo "<span class='label label-warning'>On Progress</span>";
                        ?>
                    </a>
                </td>
                <td width="50%" style="padding: 0px">
                    <table width="100%" class="tb">
                        <tr>
                            <td width="50%">
                                <b>Progress Capaian Kinerja</b>
                                <br/>
                                <?=UI::createTextArea("progress_capaian_kinerja_$r[id_risiko]",$r['progress_capaian_kinerja'],'','',true,'form-control')?>
                                <br/>
                                <b>Hambatan/Kendala</b>
                                <br/>
                                <?=UI::createTextArea("hambatan_kendala_$r[id_risiko]",$r['hambatan_kendala'],'','',true,'form-control')?>
                            </td>
                            <td width="50%">
                                <b>Penyesuaian Tindakan Mitigasi</b>
                                <br/>
                                <?=UI::createTextArea("penyesuaian_tindakan_mitigasi_$r[id_risiko]",$r['penyesuaian_tindakan_mitigasi'],'','',true,'form-control')?>
                                <br/>
                                <b>Residual Risk Hasil Evaluasi</b>
                                <br/>
                                <div class="row">
                                    <div class="col-xs-5 no-padding">
                                        <b>Kemungkinan</b>
                                        <?=UI::createSelect('residual_kemungkinan_evaluasi_'.$r['id_risiko'],$mtkemungkinanarr,$r['residual_kemungkinan_evaluasi'],true,'form-control ',"style='width:auto; max-width:100%;' onchange='load_detail($r[id_risiko])'")?>
                                    </div>
                                    <div class="col-xs-5">
                                        <b>Dampak</b>
                                        <?=UI::createSelect('residual_dampak_evaluasi_'.$r['id_risiko'],$mtdampakrisikoarr,$r['residual_dampak_evaluasi'],true,'form-control ',"style='width:auto; max-width:100%;' onchange='load_detail($r[id_risiko])'")?>
                                    </div>
                                    <div class="col-xs-2 no-padding">
                                        <b>&nbsp;</b>
                                        <?php
                                        $r['residual_kemungkinan_evaluasi_'.$r['id_risiko']] = $r['residual_kemungkinan_evaluasi'];
                                        $r['residual_dampak_evaluasi_'.$r['id_risiko']] = $r['residual_dampak_evaluasi'];
                                        ?>
                                        <?=UI::tingkatRisiko('residual_kemungkinan_evaluasi_'.$r['id_risiko'], 'residual_dampak_evaluasi_'.$r['id_risiko'], $r, true);?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                if($r['residual_kemungkinan_evaluasi'] && $r['residual_dampak_evaluasi']){
                                ?>
                                <input type="hidden" name="id_risiko[<?=$r['id_risiko']?>]" value="<?=$r['id_risiko']?>">
                                <?php
                                    $tingkat = $riskmatrixtingkat[$r['residual_kemungkinan_evaluasi']][$r['residual_dampak_evaluasi']];

                                    $is_close = (bool)($tingkat<$riskapertite);

                                    if($is_close){
                                        echo "<b style='display:inline'>Status</b><div style='display:inline'>".UI::createSelect('status_risiko_'.$r['id_risiko'],array(''=>'-pilih-','0'=>'Close','2'=>'Berlanjut'),$this->post['status_risiko'],true,'form-control ',"style='width:auto; max-width:100%;' onchange='load_detail($r[id_risiko])'").'</div>';

                                        if($this->post['status_risiko']==='0'){
                                            echo "<div class='close".$r['id_risiko']."' style=''><b style='display:inline'>Tgl. Close </b>".UI::createTextBox('tgl_close_'.$r['id_risiko'],null,'','',true,'form-control datepickerstart', "style='width:100px; display:inline'")."</div>";
                                        }
                                    }

                                    if($this->post['status_risiko']==='2' or !$is_close){
                                        echo "<div class='berlanjut".$r['id_risiko']."'><b style='display:inline'>Tgl. Risiko Berlanjut </b>".UI::createTextBox('tgl_risiko_'.$r['id_risiko'],$this->post['tgl_risiko'],'','',true,'form-control datepicker',"onchange='load_detail($r[id_risiko])' style='width:100px; display:inline'");
                                        echo UI::createSelectMultiple('id_scorecard_berlanjut_'.$r['id_risiko'].'[]',$scorecardchildarr,$r['id_scorecard'],true)."</div>";
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>