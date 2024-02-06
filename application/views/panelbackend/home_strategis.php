<div style="width:33.3%" <?php if($method!='operasional' && $method!='strategis' && $method!='index' && $method=='proyek'){?>class="info-box-flat-active"<?php }else{?>class="info-box-flat"<?php } ?>>
  <div class="content">
      <div class="text"><a href="<?=site_url('panelbackend/home/proyek')?>">PROYEK</a></div>
  </div>
</div>
<div style="width:33.3%" <?php if($method!='proyek' && $method!='strategis' && $method!='index' && $method=='operasional'){?>class="info-box-flat-active"<?php }else{?>class="info-box-flat"<?php } ?>>
    <div class="content">
        <div class="text"><a href="<?=site_url('panelbackend/home/operasional')?>">OPERASIONAL</a></div>
    </div>
</div>
<div style="width:33.3%" <?php if($method!='proyek' && $method!='operasional' && $method=='strategis' || $method=='index'){?>class="info-box-flat-active"<?php }else{?>class="info-box-flat"<?php } ?>>
  <div class="content">
      <div class="text"><a href="<?=site_url('panelbackend/home/strategis')?>">STRATEGIS</a></div>
  </div>
</div>
<div style="clear: both;"></div>
<br/>
<div class="row clearfix">
    <!-- Task Info -->
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">

    <div class="row">
    <div style="color: #fff;
background: #3f51b5;
position: relative;
padding: 5px;
min-height: 110px;">
    <div style="position: absolute;
left: 0;
width: 0;
height: 0;
border-style: solid;
border-width: 109px 510px 0 0;
border-color: white transparent transparent transparent;
top: 0;"></div>
    <center>
    <h3>Visi :</h3>
    <h5>"Menjadi Perusahaan Pengelola<br/>Aset Pambangkit Listrik dan Pendukungnya dengan Standar Internasional"</h5>
    </center>
    <div style="position: absolute;
width: 0;
height: 0;
border-style: solid;
border-width: 0 510px 109px 0;
border-color: transparent white transparent transparent;
right: 0;
top: 0;"></div>
    </div>
    <div style="width: 50%;float: left;">
    <div class="bg-red" style="padding: 0px; margin: 10px 10px 10px 0px;text-align: center;"><h3 style="padding: 5px;"><i>Sustainable Growth</i></h3></div>
    </div>
    <div style="width: 50%;float: right;">
    <div class="bg-red" style="padding: 0px; margin: 10px 0px 10px 10px;text-align: center;"><h3 style="padding: 5px;"><i>Operational Excellence</i></h3></div>
    </div>
    <div style="clear: both;"></div>
      <table style="border:1px solid #333;margin-bottom: 20px;margin-top: 10px" width="100%">
          <tr>
              <td rowspan="2" style="text-align:center;width: 8%;">FINANCIAL</td>
              <td colspan="2">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(22)">PSF1 : Meningkatkan profitability dan mengoptimasi manajemen cash flow</a>
              </div>
              </td>
          </tr>
          <tr>
              <td style="width: 42%;">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(41)">PSF2 : Meningkatkan penjualan</a>
              </div>
              </td>
              <td>
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(42)">PSF3 : Mengoptimalkan biaya</a>
              </div>
              </td>
          </tr>
      </table>
      <table style="border:1px solid #333;margin-bottom: 20px;margin-top: 20px" width="100%">
          <tr>
              <td style="text-align:center;width: 8%;">STAKE<br/>HOLDER</td>
              <td colspan="2">
              <div style="text-align: center;font-size:20px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(43)">PSS1 : "Berikan <span style="color:red; font-weight: bold">TOTAL</span> <span style="color:blue; font-weight: bold">SOLUTION</span> yang nilai-nya melebihi harap pelanggan"</a>
              </div>
              </td>
          </tr>
      </table>
      <table style="border:  1px solid #333;margin-bottom: 20px;margin-top: 20px" width="100%">
          <tr>
              <td rowspan="4" style="text-align:center;width: 8%;">INTERNAL<BR/>PROCCESS</td>
              <td style="width: 42%;" colspan="2">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' onclick="callRisiko(44)" data-target="#risikostrategis">PSI1 : Kreatif menciptakan peluang dan menjamin kontrak berprinsip win win</a>
              </div>
              </td>
              <td>
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' onclick="callRisiko(47)" data-target="#risikostrategis">PSI4 : Menciptakan kinerja operasi unggul sesuai menajemen aset kelas dunia</a>
              </div>
              </td>
          </tr>
          <tr>
              <td style="width: 21%;">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(45)">PSI2 : Memperkuat hubungan dan brand</a>
              </div>
              </td>
              <td style="width: 21%;">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(46)">PSI2 : Membangun strategic partnership</a>
              </div>
              </td>
              <td>
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333; height: 66px;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(48)">PSI5 : Memastikan ketaatan pada SHE</a>
              </div>
              </td>
          </tr>
          <tr>
              <td colspan="3">
              <div style="text-align: center;font-size: 20px;padding: 10px;margin: 10px;border: 2px solid #333; ">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(49)">PSI6 : Memastikan keberlangsungan bisnis dengan Enterprise Risk Management</a>
              </div>
              </td>
          </tr>
          <tr>
              <td colspan="3">
              <div style="text-align: center;font-size: 20px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(50)">PSI7 : Membangun <span style="color:blue; font-weight: bold;">SIAP</span> <span style="color:red; font-weight: bold;">IMS</span> dan meningkatkan kinerja berbasis Baldrige</a>
              </div>
              </td>
          </tr>
      </table>
      <table style="border:1px solid #333;margin-bottom: 20px;margin-top: 10px" width="100%">
          <tr>
              <td rowspan="2" style="text-align:center;width: 8%;">LEARNING</td>
              <td colspan="3">
                  <div class="bg-red" style="padding: 0px; margin: 0px 10px;text-align: center;"><h3 style="padding: 5px;"><i>Organizational Readiness</i></h3></div>
              </td>
          </tr>
          <tr>
              <td style="width: 35%;">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(51)">PSL1 : Memastikan kapabilitas, kapasistas dan kesiapan sistem SDM</a>
              </div>
              </td>
              <td style="width: 30%;">
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(52)">PSL2 : Mendorong implementasi budaya</a>
              </div>
              </td>
              <td>
              <div style="text-align: center;font-size: 16px;padding: 10px;margin: 10px;border: 2px solid #333;">
              <a data-toggle='modal' data-target="#risikostrategis" onclick="callRisiko(53)">PSL3 : Implementasi IT untuk efisiensi</a>
              </div>
              </td>
          </tr>
      </table>
      </div>
          </div>
      </div>
      <!-- modal untuk nama risiko berdasarkan sasaran strategi -->
      <div class="modal fade" id="risikostrategis" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="risikostrategislabel">Daftar Risiko Berdasarkan Kajian Risiko Strategis</h4>
                </div>
                <div class="modal-body">
                  <div id="datarisikostrategis">

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
              </div>
          </div>
      </div>
      <div class="row clearfix">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="card">
                <div class="header">
                  <h2>Tabel Matriks Risiko</h2>
                </div>
                <div class="body">
                  <table class="table table-bordered table-matrix" style="width:auto">
                    <thead>
                      <tr>
                        <th style="width:1px;text-align:center;background-color:blue;color:white;border:1px solid black;" rowspan="2">No</th>
                        <th style="width:200px;text-align:center;background-color:blue;color:white;border:1px solid black;" rowspan="2">Deskripsi Risiko</th>
                        <th style="width:200px;text-align:center;background-color:blue;color:white;border:1px solid black;" colspan="3">Keterangan Tingkat Risiko</th>
                      </tr>
                      <tr>
                        <th style="width:120px;text-align:center;background-color:blue;color:white;border:1px solid black;">INHEREN RISK</th>
                        <th style="width:120px;text-align:center;background-color:blue;color:white;border:1px solid black;">CURRENT RISK</th>
                        <th style="width:120px;text-align:center;background-color:blue;color:white;border:1px solid black;">RESIDUAL RISK YANG DI TARGETKAN</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $rs = $this->data['rows'];
                      $no=1;
                      $top_inheren = array();
                      $top_paska_kontrol = array();
                      $top_paska_mitigasi = array();
                      foreach($rs as $r => $val){
                        $top_inheren[$val['inheren_dampak']][$val['inheren_kemungkinan']][] = $no;
                        $top_paska_kontrol[$val['control_dampak_penurunan']][$val['control_kemungkinan_penurunan']][] = $no;
                        $top_residual_target[$val['residual_target_dampak']][$val['residual_target_kemungkinan']][] = $no;
                        echo "<tr >";
                        echo "<td style='text-align:center;border:1px solid black;'>".$no++."</td>";
                        echo "<td style='border:1px solid black;'><a href='".site_url("panelbackend/risk_risiko/detail/$val[id_scorecard]/$val[id_risiko]")."' target='_BLANK'>$val[nama]</a></td>";
                        echo "<td style='border:1px solid black;'>$val[level_risiko_inheren]</td>";
                        echo "<td style='border:1px solid black;'>$val[level_risiko_control]</td>";
                        echo "<td style='border:1px solid black;'>$val[level_residual_evaluasi]</td>";
                        echo "</tr>";
                      }
                      if(!($rs)){
                          echo "<tr><td colspan='8' style='border:1px solid black;'>Data kosong</td></tr>";
                      }
                      ?>
                      </tbody>
                  </table>
                </div>

                  <div class="header">
                      <h2>MATRIKS RISIKO</h2>
                  </div>
                  <div class="body">
                      <div>
                        <div>
                        <?php
                          $inheren = array();
                          $total_inheren = 0;
                          $control = array();
                          $total_control = 0;
                          $residual = array();
                          $total_residual = 0;
                          $rs = $this->data['rows'];
                          $rs_dampak = $this->data['mtriskdampak'];
                          $rs_kemungkinan = $this->data['mtriskkemungkinan'];
                          $rs_matrix = $this->data['mtriskmatrix'];
                        ?>
                            <table class="table table-bordered table-matrix" style="width:auto;border:2px solid black;">
                                <?php
                                $data = array(array());
                                foreach($rs_matrix as $k => $v){
                                  $data[$v['id_dampak']][$v['id_kemungkinan']] = $v;
                                }

                                echo "<tr style=\"border:2px solid black;\">";
                                  echo "<tr style=\"border:2px solid black;\">";
                                  echo "<td style=\"border:2px solid black;\" rowspan='5'>T<br/>I<br/>N<br/>G<br/>K<br/>A<br/>T<hr/>
                                        K<br/>E<br/>M<br/>U<br/>N<br/>G<br/>K<br/>I<br/>N<br/>A<br/>N</td>";
                                    foreach($rs_kemungkinan as $r_k => $val_k){
                                      echo "<td style=\"border:2px solid black;\">$val_k[nama]</td>";
                                      echo "<td style=\"border:2px solid black;\">$val_k[kode]</td>";
                                      foreach($rs_dampak as $r_d => $val_d){
                                          $bg = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['warna'];


                                          $tingkat_risiko = $data[$val_d['id_dampak']][$val_k['id_kemungkinan']]['nama'];

                                          $div = "";
                                          if ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']]) {
                                            foreach ($top_inheren[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                                              $div .= "<div style='width:23px;height:23px;float:right;padding:2px;margin:2px;background-color:black;border:1px solid black;color:white;'>$n</div>";
                                              $inheren[$tingkat_risiko]++;
                                              $total_inheren++;
                                            }
                                          }
                                          if ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']]) {
                                            foreach ($top_paska_kontrol[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                                              $div .= "<div style='width:23px;height:23px;float:right;padding:2px;margin:2px;background-color:silver;border:1px solid black;color:black;'>$n</div>";
                                              $control[$tingkat_risiko]++;
                                              $total_control++;
                                            }
                                          }
                                          if ($top_residual_target[$val_d['id_dampak']][$val_k['id_kemungkinan']]) {
                                            foreach ($top_residual_target[$val_d['id_dampak']][$val_k['id_kemungkinan']] as $n) {
                                              $div .= "<div style='width:23px;height:23px;float:right;padding:2px;margin:2px;background-color:white;border:1px solid black;color:black;'>$n</div>";
                                              $residual[$tingkat_risiko]++;
                                              $total_residual++;
                                            }
                                          }
                                          echo "<td style='border:2px solid black;' class='bg-$bg' height='100px' width='100px' align='center' valign='middle'><div style='text-align:center;margin:5px;padding:5px;'>$tingkat_risiko</div><div>$div</div></td>";
                                        }
                                  echo "</tr>";
                                  }
                                echo "</tr>";
                                echo "<tr style=\"border:2px solid black;\">";
                                echo "<td colspan='3' style=\"border:2px solid black;\"></td>";
                                foreach($rs_dampak as $r_d => $val_d){
                                  echo "<td style='text-align:center;border:2px solid black;'>$val_d[kode]</td>";
                                }
                                echo "</tr>";
                                echo "<tr style=\"border:2px solid black;\">";
                                echo "<td colspan='3' style=\"border:2px solid black;\"></td>";
                                foreach($rs_dampak as $r_d => $val_d){
                                  echo "<td style='text-align:center;border:2px solid black;'>$val_d[nama]</td>";
                                }
                                echo "</tr>";
                                echo "<tr style=\"border:2px solid black;\">";
                                  echo "<td colspan='8' style='text-align:center'>TINGKAT DAMPAK</td>";
                                echo "</tr>";
                                ?>
                            </table>
                            KETERANGAN MATRIKS RISIKO
                            <table class="table table-bordered table-matrix" style="width:auto;;border:1px solid black">
                              <thead>
                                <tr>
                                  <th style="width:200px;text-align:center;border:1px solid black" rowspan="2">Warna Risiko</th>
                                  <th style="width:200px;text-align:center;border:1px solid black" rowspan="2">Keterangan Risiko</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td style="background-color:black;border:1px solid black;"></td>
                                  <td style="border:1px solid black;">INHEREN RISK</td>
                                </tr>
                                <tr>
                                  <td style="background-color:silver;border:1px solid black;"></td>
                                  <td style="border:1px solid black;">CURRENT RISK</td>
                                </tr>
                                <tr>
                                  <td style="background-color:white;border:1px solid black;"></td>
                                  <td style="border:1px solid black;">RESIDUAL RISK YANG DI TARGETKAN</td>
                                </tr>
                              <tbody>
                            </table>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- #END# matrik risiko dan tabel risiko -->

<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div style="border:2px solid black;" class="body">
                <div class="m-b--35 font-bold">INHEREN RISK</div>
                <div class="body">
                    <div id="donut_chart1" class="dashboard-donut-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div style="border:2px solid black;" class="body">
                <div class="m-b--35 font-bold">CURRENT RISK</div>
            <div class="body">
                <div id="donut_chart2" class="dashboard-donut-chart"></div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div style="border:2px solid black;" class="body">
                <div class="font-bold m-b--35">RESIDUAL RISK YANG DI TARGETKAN</div>
            <div class="body">
                <div id="donut_chart3" class="dashboard-donut-chart"></div>
            </div>
            </div>
        </div>
    </div>
</div>


<!-- Morris Plugin Js -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/morrisjs/morris.js"></script>

<!-- ChartJs -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/chartjs/Chart.bundle.js"></script>

<!-- Flot Charts Plugin Js -->
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.resize.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.pie.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.categories.js"></script>
<script src="<?php echo base_url()?>assets/template/backend/plugins/flot-charts/jquery.flot.time.js"></script>

<style>
.table-matrix > tbody > tr > td{
vertical-align: middle;
font-weight: bold;
text-align: center;
}
</style>
<script>

function callRisiko(id_sasaran_strategis) {
  $.ajax({
    dataType: 'html',
    url:"<?=base_url("panelbackend/ajax/risikosasaran")?>/3/"+id_sasaran_strategis,
    success:function(response) {
      $('#datarisikostrategis').html(response);
    }
  })
}
<?php

$temp_warna = array();
foreach($rs_matrix as $r) {
  $temp_warna[$r['nama']] = $r['warna'];
}
?>
Morris.Donut({
    element: 'donut_chart1',
    <?php if ($total_inheren === null || $total_inheren === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
        <?php foreach($inheren as $label => $count) {?>{
          label: "<?php echo $label;?>",
          value: <?php echo round(($count/$total_inheren)*100,1);?> },
      <?php } ?>
    ],
    colors: [
        <?php foreach($inheren as $label => $count) {
            echo "'".$temp_warna[$label]."',";
          } ?>
        ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});

Morris.Donut({
    element: 'donut_chart2',
    <?php if ($total_control === null || $total_control === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
      <?php foreach($control as $label => $count) {?>{
        label: "<?php echo $label;?>",
        value: <?php echo round(($count/$total_control)*100,1);?> },
      <?php } ?>
    ],
    colors: [
      <?php foreach($control as $label => $count) {
          echo "'".$temp_warna[$label]."',";
        } ?>
      ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});

Morris.Donut({
    element: 'donut_chart3',
    <?php if ($total_residual === null || $total_residual === 0 ) {?>
    data: [{
        label: "Tidak Ada Risiko",
        value: 0
    }],
    colors: ['#000'],
    <?php } else {?>
    data: [
      <?php foreach($residual as $label => $count) {?>{
        label: "<?php echo $label;?>",
        value: <?php echo round(($count/$total_residual)*100,1);?> },
    <?php } ?>
    ],
    colors: [
      <?php foreach($residual as $label => $count) {
          echo "'".$temp_warna[$label]."',";
        } ?>
      ],
    <?php } ?>
    formatter: function (y) {
        return y + '%'
    }
});


</script>
