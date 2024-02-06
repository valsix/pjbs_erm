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

    <link href="<?php echo base_url()?>assets/template/backend/css/icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/template/backend/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Custom Css -->
    <link href="<?php echo base_url()?>assets/template/backend/css/style.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/template/backend/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url()?>assets/template/backend/plugins/bootstrap/js/bootstrap.js"></script>
</head>

<body style="margin-top:0px; background-color: #fff"> 
<?php echo $content;?> 
</body>

</html>