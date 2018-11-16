<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.png" type="image/x-icon" />
    <title>Thai PBS : Line Watch</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url()?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url()?>assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url()?>assets/dist/css/sb-admin-2.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url()?>assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">
.logo,.heading{text-align:center;}
.logo img{width:200px;}
.heading h4{color:#787c89;}
.heading h4 img{margin-right:10px;}
</style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
				
                <div class="login-panel panel panel-default">
					<div class="logo">
						
							<img src="<?php echo base_url()?>assets/images/logo_new_m.png" />
					</div>
                   <div class="heading">
						<h4><img src="<?php echo base_url()?>assets/images/logo-Line.png" width="40" />Line Watch</h4>
				   </div>
                    <div class="panel-body">
					
                        <form role="form" method="post" id="login-form" action="<?php echo site_url('authen/login')?>">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="modal-loder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static" 
   data-keyboard="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <h1 class="modal-title"><i class="fa fa-clock-o"></i> กำลังดำเนินการ...</h1>
                </div>
                <div class="modal-body" style="min-height:150px">
					<div class="loader"></div>
                </div>
               
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

	<div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static" 
   data-keyboard="false" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h1 class="modal-title"><i class="fa fa-exclamation-circle"></i> ผิดพลาด</h1>
                </div>
                <div class="modal-body">
					<div id="errText"></div>
                </div>
				<div class="modal-footer">
					 <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                </div>
               
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	
    <!-- jQuery -->
    <script src="<?php echo base_url()?>assets/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url()?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url()?>assets/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url()?>assets/dist/js/sb-admin-2.js"></script>
	<script>
	$(function(){
		$('#login-form').submit(function(e){
			e.preventDefault();
			$.ajax({
				url : $(this).attr('action'),
				type : 'POST',
				data : $(this).serialize(),
				dataType: 'json',
				beforeSend : function(){
					$('#modal-loder').modal('show');
				},
				success : function(callback){
					$('#modal-loder').modal('hide');
					if(callback == true){
						window.location = '<?php echo site_url('messages')?>';
					}
					else{
						$('#errText').text('อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง !');
						$('#modal-error').modal('show');
					}
				}
			})
		})
	})
	</script>
</body>
</html>