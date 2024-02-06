        <div class="container-fluid">
        <?php if($page_title){ ?>
            <div class="block-header">
                <h2>
        <?=$page_title?>
        <?=$layout_header?>
        <?php if($sub_page_title){ ?> <small><?=$sub_page_title?></small> <?php }?></h2>
            </div>
            <?php } ?>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <div class="pull-right">
                            <?php echo UI::showButtonMode($mode, $row[$pk])?>
                          </div>
                          <div style="clear: both;"></div>
                        </div>
                        <div class="body table-responsive" style="<?=($mode=='index')?'padding:0px':''?>">

                      <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                      <div class="alert alert-warning">
                          Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                      </div>
                      <?php }?>

                      <?=FlashMsg()?>
                      <div class="pull-right">
                        <?
                        $form = UI::getButton('print', null, true);
                        echo UI::FormGroup(array(
                            'form'=>$form,
                            'sm_label'=>2,
                            ));
                        ?>
                      </div>
  <table class="table table-striped table-hover dataTable">
    <thead>
    <tr>
        <th width="30" colspan="4">List File GRC</th>

        <?php
        $rowsfolder = $this->conn->GetArray("select * from mt_fd_dok_pendukung_grc where status = 1");
        foreach($rowsfolder as $r){
        ?>
        <th rowspan="2" align="center" style="width: 50px;"><?=$r['alias']?></th>
        <?php } ?>

        <!-- <th rowspan="2">Hasil Petikan Radir</th> -->
        <th rowspan="2">Divisi Pemrakarsa</th>
        <!-- <th rowspan="2">PIC RTH</th> -->
        <th rowspan="2">Progress/Status</th>
    </tr>
    <tr>
        <th>Berita Acara</th>
        <th>Start Date</th>
        <th>Nama Program</th>
        <!-- <th>Hasil Keputusan Radir</th> -->
        <th>Level</th>
        
    </tr>
    <!-- <tr>
        <th width="30%">Ketegori/Parameter Risiko</th>
        <?php
        $rowskategori = $this->conn->GetArray("select * from mt_risk_dampak order by sk_tanggal_awal, id_dampak");
        foreach($rowskategori as $r){
        ?>
        <th align="center"><?=$r['kode']." - ".$r['nama']?></th>
        <?php } ?>
    </tr>
    <tr>
      <th>Rating</th>
      <?php
            $rowskategori = $this->conn->GetArray("select * from mt_risk_dampak order by sk_tanggal_awal, id_dampak");
            foreach($rowskategori as $r){
            ?>
            <th align="center"><?=(float)$r['rating']?></th>
            <?php } ?>
    </tr> -->
    </thead>
    <tbody>
    <?php
    $i = $page;
    foreach($list['rows'] as $rows){
        $i++;
        ?>

        <tr>
        <?php 
        echo "<td>$rows[nomor_ba_grc]</td>";
        echo "<td>$rows[tgl_mulai_efektif]</td>";
        echo "<td>$rows[nama]</td>";
        // echo "<td>$rows[hasil_keputusan_radir]</td>";
        echo "<td>$rows[id_level_grc]</td>";
        foreach($rowskategori as $r){

        echo "<td>";
        echo $rows[$r['id_dampak']];
        echo "</td>";
            } ?>
        <?php
    	// echo "<td style='text-align:right'>";
        // echo UI::showMenuMode('inlist', $rows[$pk]);
    	// echo "</td>";
        // echo "</tr>";
    }
    if(!$list['rows']){
        echo "<tr><td colspan='".(count($header)+2)."'>Data kosong</td></tr>";
    }
    ?>
    </tbody>
  </table>
  <?=UI::showPaging($paging,$page, $limit_arr,$limit,$list)?>
                      <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="key" id="key"/>
<style type="text/css">
    table.dataTable {
    clear: both;
    margin-bottom: 6px !important;
    max-width: none !important;
}
</style>