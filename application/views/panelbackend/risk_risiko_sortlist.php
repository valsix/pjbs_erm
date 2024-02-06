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

<div class="col-sm-3 no-padding">
<?php
$form = UI::createSelect('id_kajian_risiko',$mtjeniskajianrisikoarr,$id_kajian_risiko,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>'Kajian Risiko'
    ));
?>
</div>
<?php if(($scorecardarr)){ ?>
<div class="col-sm-3 no-padding">
<?php
$form = UI::createSelect('id_scorecard',$scorecardarr,$id_scorecard,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>'Risk Profile'
    ));
?>
</div>
<?php } ?>
<?php if(($scorecardchildarr)){ ?>
<div class="col-sm-5 no-padding">
<?php
$form = UI::createSelect('id_scorecard_child',$scorecardchildarr,$id_scorecard_child,true,'form-control select2',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>4,
    'onlyone'=>true,
    'label'=>$scorecardarr[$id_scorecard]
    ));
?>
</div>
<?php } ?>
<div class="col-sm-1 no-padding">

<?php 
$form = UI::createTextNumber('top',$top,'4','4',true,'form-control ',"onchange='goSubmit(\"set_value\")'");
echo UI::FormGroup(array(
    'form'=>$form,
    'sm_label'=>3,
    'onlyone'=>true,
    'label'=>'Top'
    ));
?>
</div>
                          <div style="clear: both;"></div>
                        </div>

                        <div class="body table-responsive" style="padding: 0px">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>
<table class="table table-bordered table-hover dataTable">
  <thead>
    <tr>
      <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">
        <button type='button' class="btn btn-primary btn-sm" onclick="goSubmit('set_merge')">Merge</button>
        <button type='button' class="btn btn-warning btn-sm" onclick="goSubmit('set_unmerge')">Split</button>
      </th>
      <th style="width:1px;text-align:center;background-color:#034485;color:#eee;" rowspan="2">NO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISIKO</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" rowspan="2">RISK OWNER</th>
      <th  style="text-align:center;background-color:#034485;color:#eee;" colspan="<?=count($rating)?>">LEVEL RISIKO</th>
      <th style="width:50px;text-align:center;background-color:#034485;color:#eee;" rowspan="2"></th>
    </tr>
    <tr>
      <?php if($rating['i']){ ?>
      <th style="width:75px;text-align:center;background-color:#034485;color:#eee; cursor: pointer" onclick="setOrder('i')" <?php if($order=='i'){echo "class='sorting_desc'";}?> >INHEREN RISK</th>
      <?php } if($rating['c']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee; cursor: pointer" onclick="setOrder('c')" <?php if($order=='c'){echo "class='sorting_desc'";}?> >CURRENT RISK</th>
      <?php } if($rating['r']){ ?>
      <th  style="width:75px;text-align:center;background-color:#034485;color:#eee ; cursor: pointer" onclick="setOrder('r')" <?php if($order=='r'){echo "class='sorting_desc'";}?> >TARGETED RESIDUAL RISK</th>
      <?php } ?>
    </tr>
    </thead>
  <tbody>
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
    foreach($rs as $r => $val){
      $idmerge = $val['merge'];
      if(!$idmerge)
        $idmerge = $val['id_risiko'];
      
      echo "<tr>";
      echo "<td style='text-align:center'>".UI::createCheckBox('merge['.$idmerge.']',1,$this->post['merge'][$idmerge],null,true,'iCheck-helper')."</td>";
      echo "<td style='text-align:center'>".$no++."</td>";
      echo "<td>";

      if($val['merge']){
        echo "<a href='javascript:void(0)' onclick='detailMerge(\"".$val['merge']."\")'>$val[nama]</a> ";
        echo "<a href='javascript:void(0)' onclick='editMerge(\"".$val['nama']."\",\"".$val['merge']."\")'><span class='glyphicon glyphicon-edit'></span></a>";
      }else{
        echo "<a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a>";
      }

      echo "</td>";
      echo "<td style='text-align:center'>$val[risk_owner]</td>";

      if($rating['i']){
        $bg = $data[$val['inheren_dampak']][$val['inheren_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_inheren]</td>";
      }

      if($rating['c']){
        $bg = $data[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_risiko_control]</td>";
      }

      if($rating['r']){
        $bg = $data[$val['residual_target_dampak']][$val['residual_target_kemungkinan']]['warna'];
        echo "<td align='center' style='background-color:$bg;color:#333 !important;' class='bg-$bg'>$val[level_residual_evaluasi]</td>";
      }

      echo "<td align='center'>

            <a href=\"javascript:void(0)\" onclick=\"$('#key').val($val[id_risiko]); goSubmit('sort_up');\">
                <span class=\"glyphicon glyphicon-chevron-up\"></span>
            </a>
            <a href=\"javascript:void(0)\" onclick=\"$('#key').val($val[id_risiko]); goSubmit('sort_down');\">
                <span class=\"glyphicon glyphicon-chevron-down\"></span>
            </a>

      </td>";

      echo "</tr>";
    }
    if(!($rs)){
        echo "<tr><td colspan='8'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>

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
<input type="hidden" name="order" id="order" value="<?=$order?>">
<script type="text/javascript">
  function setOrder(order){
    $("#order").val(order);
    goSubmit('set_order');
  }

  function detailMerge(merge){
    $(function(){
      $.ajax({
        type:"post",
        url:"<?=site_url("panelbackend/ajax/detail_merge")?>",
        data:{merge:merge},
        success:function(ret){
          $("#detailmergebody").html(ret);
          $("#detailmerge").modal("toggle");
        }
      });
    })
  }

  function editMerge(nama, merge){
    $("#nama_merge").val(nama);
    $("#key").val(merge);
    $("#editmerge").modal("toggle");
  }
</script>


 <div class="modal fade" id="editmerge" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><center>EDIT NAMA RISIKO</center></h2>
            </div>
            <div class="modal-body">
              <?=UI::createTextArea('nama_merge',null,'','',true,'form-control', "style='width:100%'")?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" onclick="goSubmit('save_nama')">SAVE</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="detailmerge" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
              <div class="row"  id="detailmergebody"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
  .header .form-group{
    font-size: 16px;
    margin: 0px;
  }
  .header .form-group input{
    font-size: 16px;
  }
</style>