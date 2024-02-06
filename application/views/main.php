<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?=Title($page_title)?></title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/img/favicon.ico" type="image/x-icon" />

    <!-- Google Fonts -->
    <?php /*<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">*/ ?>
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900|Titillium+Web:200,300,400,600,700" rel="stylesheet">  -->
    <link href="<?php echo base_url()?>assets/template/backend/css/icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Wait Me Css -->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <!--<link href="<?php echo base_url()?>assets/template/backend/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />-->

    <!-- Morris Chart Css-->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Style Css -->
    <link href="<?php echo base_url()?>assets/template/backend/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url()?>assets/template/backend/css/themes/all-themes.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/template/backend/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/jquery.treetable.theme.default.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/jquery.treetable.css" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?php echo base_url()?>assets/resources/css/custom.css" rel="stylesheet">

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/jquery/jquery.min.js"></script>

    <script type="text/javascript">
        function site_url(url){
            return "<?=site_url()?>"+url;
        }
        function current_url(){
            return "<?=current_url()?>";
        }
    </script>

    <script src="<?php echo base_url()?>assets/js/autoNumeric.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo base_url()?>assets/template/backend/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Data table -->
    <script src="<?=base_url()?>assets/js/jquery.treetable.js"></script>
    <script src="<?=base_url()?>assets/js/jquery.dataTables.min.js"></script>
    <script src="<?=base_url()?>assets/js/dataTables.fixedColumns.min.js"></script>
</head>

<body class="theme-indigo <?php if($_SESSION[SESSION_APP]['toggle']){ ?>full<?php } ?>">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <div class="overlay"></div>
    <!-- #END# Page Loader -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User <?=$_SESSION[SESSION_APP]['name']?></div>
                    <div class="email"><?= $_SESSION[SESSION_APP]['nama_group']?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right dropdown-small">
                            <li><a href="<?=base_url("panelbackend/home/profile")?>"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>

                            <?php if($is_administrator){ ?>
                            <li><a href="<?=base_url("panelbackend/loginas")?>"><i class="material-icons">input</i>Login As</a></li>
                            <?php } ?>

                            <li><a href="<?=base_url("panelbackend/login/logout")?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">

              <?=$this->auth->GetMenu();?>
            </div>
            <!-- #Menu -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
                <a style="float: left;margin-left: 35px;" href="<?=site_url('panelbackend/home')?>">
                 <img src="<?=base_url()?>assets/img/logo-pjb-service-shear2.png" alt="PJB Services" style="height: 50px;">
                 </a>
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>

                <a class="navbar-brand" href="<?=site_url('panelbackend/home')?>">
                APLIKASI MANAJEMEN RISIKO
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php 
                    if($is_allow_tgl_efektif){
                    if(Access('view_all_direktorat','panelbackend/risk_risiko') or $this->config->item('open_tgl_efektif')){ ?>
                    <li style=" margin-top: 13px; margin-left: 0px; font-size: 12px">
                        <span style="color: #fff; font-weight: bold;">Tgl. Efektif : </span><input type="text" style="padding: 3px 5px;width: 75px;" name="tgl_efektif" value="<?=$_SESSION[SESSION_APP]['tgl_efektif']?>" class="datepickerefektif" onchange="$('#key').val($(this).val()); goSubmit('set_efektif')">
                    </li>
                    <script type="text/javascript">
                        $(function(){

                            $(".datepickerefektif").bootstrapMaterialDatePicker({
                                format: "DD-MM-YYYY",
                                clearButton: true,
                                weekStart: 1,
                                time: false
                            });
                        })
                    </script>
                    <?php } } 
                    if($is_dashboard){ ?>
                    <li style=" margin-top: 13px; margin-left: 0px; font-size: 12px">
                        <button style="padding: 3px 7px;font-size: inherit;" class="btn btn-primary btn-sm" onclick="taksonomi()" type="button"><i class="material-icons" style="margin: -5px 0px; top: 5px;">pie_chart</i> TAKSONOMI</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </li>
                    <li style=" margin-top: 13px; margin-left: 0px; font-size: 12px">
                        <button style="padding: 3px 7px;font-size: inherit;" class="btn btn-primary btn-sm" onclick="arsip()" type="button"><i class="material-icons" style="margin: -5px 0px; top: 5px;">assignment</i> ANNUAL REPORT</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </li>
                    <li style=" margin-top: 13px; margin-left: 0px; font-size: 12px">
                        <button style="padding: 3px 7px;font-size: inherit;" class="btn btn-primary btn-sm" onclick="maturylevel()" type="button"><i class="material-icons" style="margin: -5px 0px; top: 5px;">bar_chart</i> MATURITY LEVEL</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </li>
                    <?php } if($is_dashboard or $is_allow_tahun_efektif){ ?>

                    <li style=" margin-top: 13px; margin-left: 0px; font-size: 12px">
                        <span style="color: #fff; font-weight: bold;">Tahun : </span><input type="number" style="padding: 3px 5px;width: 75px;" class="tahunefektif" name="tahun_efektif" value="<?=$_SESSION[SESSION_APP]['tahun_efektif']?>" onchange="$('#key').val($(this).val()); goSubmit('set_tahun_efektif')">
                    </li>

                    <?php } ?>
                    <!-- Call Search -->
                    <?php /*<li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>*/ ?>
                    <!-- Tasks -->
                    <?php $d_task = $this->auth->GetTask(); ?>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons" style="font-size: 24px">flag</i>
                            <span class="label-count" id="task_count"><?=$d_task['count']?></span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 500px !important;">
                            <li class="header">TASKS</li>
                            <li class="body">
                                <ul class="menu" id="task_data">
                                <?php foreach($d_task['content'] as $r){  ?>
                                    <li>
                                        <a href="<?=site_url($r['url'])?>">
                                            <div class="icon-circle bg-<?=$r['bg']?>">
                                                <i class="material-icons"><?=$r['icon']?></i>
                                            </div>
                                            <div class="menu-info">
                                                <p class="info"><?=$r['info']?></p>
                                                <p>
                                                    <i class="material-icons">access_time</i> <?=$r['time']?>
                                                    <i class="material-icons">account_circle</i> <?=$r['user']?>
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="<?=site_url("panelbackend/risk_task")?>">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                            <i class="material-icons"  style="font-size: 24px">help</i>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 140px !important;">
                            <li class="header">HELP</li>
                            <li class="body">
                                <a target="_blank" href="<?=site_url('panelbackend/home/wf')?>">
                                    <div class="menu-info">
                                        <i class="material-icons">show_chart</i>
                                        Work Flow
                                    </div>
                                </a>
                                <a target="_blank" href="<?=site_url('panelbackend/home/ug')?>">
                                    <div class="menu-info">
                                        <i class="material-icons">book</i>
                                        User Guide
                                    </div>
                                </a>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
      <form method="post" enctype="multipart/form-data" id="main_form" class="form-horizontal">
        <input type="hidden" name="act" id="act" />
        <input type="hidden" name="go" id="go" />
        <input type="hidden" name="key" id="key" />
        <?php
        echo $content;?>


        </form>
    </section>


    <!-- Select Plugin Js -->
    <!--<script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap-select/js/bootstrap-select.js"></script>-->

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/node-waves/waves.js"></script>


    <!-- Autosize Plugin Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/autosize/autosize.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?php echo base_url()?>assets/template/backend/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url()?>assets/template/backend/js/admin.js"></script>

    <script src="<?php echo base_url()?>assets/template/backend/js/pages/forms/basic-form-elements.js"></script>
    <!-- Demo Js -->
    <script src="<?php echo base_url()?>assets/template/backend/js/demo.js"></script>
    <script src="<?php echo base_url()?>assets/js/custom.js"></script>
   <script src="<?php echo base_url()?>assets/js/select2/select2.full.min.js"></script><script>$(function() {
        $(".select2, select.form-control").select2({
            placeholder: '-pilih-',
            allowClear: true   // Shows an X to allow the user to clear the value.
        });
        });</script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2-materialize.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/materialize.css" />
    <?=$add_plugin?>

    <script type="text/javascript">

    function goToggle(){
      $.ajax({
        url:"<?=base_url("panelbackend/ajax/set_toggle")?>",
        data:{collapse:($("body").hasClass("full")?1:0)}
      });
    }
    </script>
</body>

</html>
<style type="text/css">
.tunjuk {font-size: 14px !important;
    margin-top:10px !important;
    color: #dd425f !important;
    position: relative;
    -webkit-animation: mytunjuk 0.5s;
    animation: mytunjuk 0.5s;
    -webkit-animation-iteration-count: infinite; /* Safari 4.0 - 8.0 */
    animation-iteration-count: infinite;
    animation-direction: alternate;
    -webkit-animation-direction: alternate; /* Safari 4.0 - 8.0 */
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes mytunjuk {
    0% {margin-left:5px; color: #ffcb52 !important;}
    100% {margin-left:0px; color: #dd425f !important;}
}

@keyframes mytunjuk {
    0% {margin-left:5px; color: #ffcb52 !important;}
    100% {margin-left:0px; color: #dd425f !important;}
}
</style>