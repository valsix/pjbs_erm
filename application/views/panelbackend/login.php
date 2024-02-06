<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=Title("Login")?></title>

    <link rel="shortcut icon" href="<?php echo base_url()?>assets/img/favicon.ico" type="image/x-icon" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/template/admin/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/template/admin/css/Custom.css">
</head>
<body class="hold-transition login-page container">
<div class="login-box">
  
  <!-- /.login-logo -->
  <div class="login-box-body">
    <div class="login-logo text-center">
    <img src="<?php echo base_url()?>assets/resources/images/bg_head_login2.png" class="img-logo">
    </div>



  <form class="form-signin" role="form" id="login" method="post" accept-charset="UTF-8" action="<?php echo base_url("panelbackend/login/auth")?>">
    <?php if($_SESSION[SESSION_APP]['error_login']){ ?>
    <div id="respon-msg" role="alert" class="alert alert-danger"><?=$_SESSION[SESSION_APP]['error_login']; unset($_SESSION[SESSION_APP]['error_login']);?></div>
    <?php }else{ ?>
    <div id="respon-msg" style="display:none" role="alert"></div>
    <?php } ?>

      <div class="form-group has-feedback">
        <input type="text" class="form-control color-white" name="username" id="username" placeholder="Username">
        <span class="glyphicon glyphicon-send form-control-feedback color-white"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control color-white" name="password" id="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback color-white"></span>
  <input type="hidden" name="<?=$token_name?>" value="<?=$token_value?>">
      </div>
      <div class="row customrow">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-sky btn-block btn-md">Login Aplikasi&nbsp;&nbsp;<i class="glyphicon glyphicon-log-in"></i></button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

</body>



    <!-- jQuery -->
    <script src="<?php echo base_url()?>assets/js/jquery.min.js"></script>
<script>
$("#login").submit(function(){
		$.ajax({
			url:$(this).attr("action"),
			type:"post",
			data:$(this).serialize(),
			dataType:"json",
			cache:false,
			success:function(data)
			{
        if(data.error)
        {
            $("#respon-msg").text(data.error).fadeOut('500');
            $("#respon-msg").attr("class","alert alert-danger");
            $("#respon-msg").text(data.error).fadeIn('500');
            
        }
        else
        {
            $("#respon-msg").text(data.success).fadeOut('500');
            $("#respon-msg").attr("class","alert alert-success");
            $("#respon-msg").text(data.success).fadeIn('500');
            if (data.link!=undefined) {
              window.location=data.link;
            }else{
              window.location="<?php echo site_url($_SESSION[SESSION_APP]['curr_page']);?>";
            }
            
        }
			}
		});
		return false;
	});
$(function(){
});
</script>
</html>