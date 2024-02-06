<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=Title($page_title)?></title>
  <link rel="shortcut icon" href="<?php echo base_url()?>assets/img/favicon.ico" type="image/x-icon" />
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url("assets")?>/css/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url()?>assets/template/backend/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="<?=base_url("assets")?>/css/home1.css" rel="stylesheet">
    
    <!-- <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400italic,700italic,400,700" rel="stylesheet" type="text/css">  -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- jQuery -->
    <script src="<?=base_url("assets")?>/js/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/js/datepicker/js/moment.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url("assets")?>/js/bootstrap.min.js"></script>
</head>

<body style="margin-top:0px"> 
<form method="post" class="search" enctype="multipart/form-data" id="main_form" >
<input type="hidden" name="act" id="act">
<?php echo $content;?> 
</form>
</body>

</html>