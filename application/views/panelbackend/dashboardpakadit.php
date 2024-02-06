<div style="width:100%; padding:7px 10px;" class="dark-tooltip info-box-flat-active <?php echo "box-id-".$id_class; ?>" rel="tooltip" title="<?=$rk['nama']?>">
  <div style="width: 50%;height: 25px;float:left;padding-top: 3px;overflow: hidden;text-overflow: unset;">
    <?=trim(str_replace(array("KAJIAN","RISIKO"), "", strtoupper($rk['nama'])))?>
  </div>
  <div style="text-align: right;float:right; width: 50%">
    <select id="id_scorecard<?=$id_class?>" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" style="width: 100%; border: none; background: #00000030; font-size: 12px; display: block;     padding: 4px;">
      <?php $scorecardarr[''] = 'semua kajian'; foreach($scorecardarr as $k=>$v){ ?>
        <option value="<?=$k?>" <?=($id_scorecard==$k)?"selected":""?> style="color: #555; background: #fff"><?=$v?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
        <div class="card" style="margin-bottom: 0px;">
          <table border="0" width="100%">
            <tr>
              <td style="width:115px; text-align: center;vertical-align: top; padding: 7px 0px 5px 9px">
                  <h5 style=" margin: 6px 0px 6px;">TOTAL</h5>
                  <div class="row">
                    <div style="font-size: 36px; padding: 0px; margin-top: -10px; color: #025f9e;">
                      <?=$total['total_risiko']?>
                    </div>

                    <?php if($total['progress_mitigasi']){ ?>
                    <div  style="padding-top: 15px; text-align: center;">
                      <div style="font-size: 9px; padding-bottom:5px; width: 70px; font-weight: bold; margin: auto;">PROGRESS MITIGASI</div>
                      <input type="text" class="knob" value="<?=$total['progress_mitigasi']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="rgb(1, 139, 156)" readonly>
                    </div>
                    <?php } ?>
                    <?php if($total['control_efektif']){ ?>
                      <div  style="padding-top: 15px; text-align: center;">
                        <div style="font-size: 9px; padding-bottom:5px; width: 70px; font-weight: bold; margin: auto;">CONTROL EFEKTIF</div>
                        <input type="text" class="knob" value="<?=$total['control_efektif']?>" data-width="60" data-height="60" data-thickness="0.25" data-fgColor="#E91E63" readonly>
                      </div>
                    <?php } ?>
                  </div>
              </td>
              <td style="width:auto;text-align: center;vertical-align: top; padding: 7px 7px 17px 7px">
                <div class="pull-left">
                  <h5 style=" margin: 6px 0px 0px;">
                    TOP <input type="text" style="border: 0px;width: 25px;" onchange="req(<?=$id_class?>,<?=$id_kajian_risiko?>)" name="top" id="top<?=$id_class?>" value="<?=$top?>"/> MATRIKS
                  </h5>
                </div>
                <div class="pull-right">
                 <a href="#" data-toggle="modal" rel="tooltip" title="Table Risiko" data-target="#tablematrix<?=$id_class?>" onclick="reqtb(<?=$id_class?>, <?=$id_kajian_risiko?>)"><i class="material-icons">view_column</i></a>
                </div>
                  <?php
                  $is_css = false;
                  ?>
                    <div class="tab-content clearfix">
                      <?php
                      $rs_matrix = $this->data['mtriskmatrix'];
                      $data = array(array());
                      foreach($rs_matrix as $k => $v){
                        $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
                      }

                      $rs = $this->data['rows'];
                      $no=1;
                      $top_inheren = array();
                      $top_paska_kontrol = array();
                      $top_paska_mitigasi = array();
                      if($rs)
                      foreach($rs as $r => $val){
                        if($id_risiko_onlyone && $val['id_risiko']!=$id_risiko_onlyone){
                          $no++;
                          continue;
                        }

                        $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
                        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
                        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;

                        $no++;
                      }

                      include "_matrix1.php"; ?>
                      </div>
                  </td>
                </tr>
              </table>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="tablematrix<?=$id_class?>">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
                <center>
                  <h4 class="modal-title">TOP <?=$top?> <?=strtoupper($rk['nama'])?> <?php if($scorecard_name) { ?> <br/><small><?=trim(implode(" / ",$scorecard_name)," / ")?></small><?php } ?> </h4>
                </center>
          </div>
          <div class="modal-body">
            <div id="datarisiko<?=$id_kajian_risiko?>">

            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
    </div>
</div>

<?php if((Access("edit_kesimpulan", "panelbackend/home") or $this->is_super_admin) && $kesimpulan['status']){ ?>
 <div class="modal fade" id="kesimpulan<?=$id_class?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kesimpulan</h4>
            </div>
            <div class="modal-body">

<?php
$from = UI::createSelect('status',$statusarr,$kesimpulan['status'],true,'form-control ',"style='width:auto; max-width:100%;'");
echo UI::createFormGroup($from, $rules["status"], "status", "Status", true);
$from = UI::createTextArea('keterangan',$kesimpulan['keterangan'],'','',true,'form-control ');
echo UI::createFormGroup($from, $rules["keterangan"], "keterangan", "Keterangan", true);
?>
            </div>
            <div class="modal-footer">
                <button type="button" data-target="#kesimpulan<?=$id_class?>" class="btn btn-link waves-effect" data-toggle="modal">CLOSE</button>
                <button type="button" class="btn waves-effect btn-success" onclick="save_kesimpulan(<?=$id_class?>, <?=$id_kajian_risiko?>)"><span class="glyphicon glyphicon-floppy-save"></span> SAVE</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="<?php echo base_url()?>assets/template/backend/js/pages/charts/jquery-knob.js"></script>
<script type="text/javascript">
</script>