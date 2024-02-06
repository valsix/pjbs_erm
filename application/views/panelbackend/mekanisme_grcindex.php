<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    <center>LINGKUP MEKANISME GRC</center>
                    </h2>
                </div>
                <?php  if(($_SESSION[SESSION_APP]['loginas'])){ ?>
                <div class="alert alert-warning">
                    Anda sedang mengakses user lain. <a href="<?=base_url("panelbackend/home/loginasback")?>" class="alert-link">Kembali</a>.
                </div>
                <?php }?>
                <div class="body" style="padding:30px 20%;">
                    <center>
                   <br/>
                    <?php
                    unset($mtjeniskajianrisikoarr['']);
                    foreach($mtjeniskajianrisikoarr as $k=>$v){
                    if(in_array($k, $kajiankuarr)){ ?>
                       <a href="<?=site_url('panelbackend/mekanisme_grc/index/'.$k)?>" class="btn btn-info waves-effect btn-lg btn-block">
                       <h4><?=str_replace("KAJIAN RISIKO","",strtoupper($v))?></h4>
                       </a>
                   <?php } else { ?>
                       <a href="<?=site_url('panelbackend/mekanisme_grc/index/'.$k)?>" class="btn btn-default waves-effect btn-lg btn-block">
                       <h5><?=str_replace("KAJIAN RISIKO","",strtoupper($v))?></h5>
                       </a>
                   <?php } ?>
                   <br/>
                   <br/>
                   <?php } ?>
                    <div class="clearfix"></div>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
