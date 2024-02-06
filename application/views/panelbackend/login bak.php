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
    <link href="<?php echo base_url()?>assets/template/backend/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url()?>assets/template/backend/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>	


    <div class="container">
        <div class="row" style="margin-top:100px;background:rgba(0, 0, 0, 0.7);padding-top:50px;padding-bottom:30px;">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="fa fa-sign-in"></span> Login User</h3>
                    </div>
                    <div class="panel-body">

					      <form class="form-signin" role="form" id="login" method="post" accept-charset="UTF-8" action="<?php echo base_url("panelbackend/login/auth")?>">
					        <center><img src="<?=base_url("assets/img/akuarip.jpg")?>" width="40%"/></center>
					        <br/>

                            <?php if($_SESSION[SESSION_APP]['error_login']){ ?>
                            <div id="respon-msg" role="alert" class="alert alert-danger"><?=$_SESSION[SESSION_APP]['error_login']; unset($_SESSION[SESSION_APP]['error_login']);?></div>
                            <?php }else{ ?>
                            <div id="respon-msg" style="display:none" role="alert"></div>
                            <?php } ?>
                            <fieldset>
                                <div class="form-group">
        <input type="text" 
        class="form-control" name="username" id="username" 
        placeholder="Username" required autofocus>
                                </div>
                                <div class="form-group">
        <input type="password" 
        class="form-control" name="password" id="password"
        placeholder="Password" required>
        <input type="hidden" name="<?=$token_name?>" value="<?=$token_value?>">
                                </div>
        <button class="btn waves-effect btn-lg btn-primary btn-block" type="button">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
					window.location="<?php echo base_url($_SESSION[SESSION_APP]['curr_page']);?>";
				}
			}
		});
		return false;
	});
$(function(){
});
</script>
</html>
<style type="text/css">
    body{
        background-image: url("<?=base_url("assets/img/background.jpg")?>");
        background-size: 100%;
        background-position: 0px -100px;
    }
    .panel-default>.panel-heading {
color: #FFF;
background-color: #0F5189;
border-color: #ddd;
border-top-left-radius: 5px;
border-top-right-radius: 5px;
}
.panel-default {
}
.panel{
    border-radius: 5px;
}
</style>