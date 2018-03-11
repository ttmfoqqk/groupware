<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login | Dynamic Admin Template</title>
        <!-- Mobile specific metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
        <!-- Force IE9 to render in normal mode -->
        <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
        <meta name="author" content="" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="application-name" content="" />
        <!-- Import google fonts - Heading first/ text second -->
        <link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans:400,700' rel='stylesheet' type='text/css'>
        <!-- Css files -->
        <!-- Icons -->
        <link href="<?echo $this->config->base_url()?>html/css/icons.css" rel="stylesheet" />
        <!-- Bootstrap stylesheets (included template modifications) -->
        <link href="<?echo $this->config->base_url()?>html/css/bootstrap.css" rel="stylesheet" />
        <!-- Plugins stylesheets (all plugin custom css) -->
        <link href="<?echo $this->config->base_url()?>html/css/plugins.css" rel="stylesheet" />
        <!-- Main stylesheets (template main css file) -->
        <link href="<?echo $this->config->base_url()?>html/css/main.css" rel="stylesheet" />
        <!-- Custom stylesheets ( Put your own changes here ) -->
        <link href="<?echo $this->config->base_url()?>html/css/custom.css" rel="stylesheet" />
        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-57-precomposed.png">
        <link rel="icon" href="<?echo $this->config->base_url()?>html/img/ico/favicon.ico" type="image/png">
        <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
        <meta name="msapplication-TileColor" content="#3399cc" />
    </head>
    <body class="login-page">
    aaa
        <!-- Start login container -->
        <div class="container login-container">
            <div class="login-panel panel panel-default plain animated bounceIn">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <img id="logo" src="<?echo $this->config->base_url()?>html/img/logo-dark.png" alt="(주)스파이더웹">
                    </h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal mt0" action="login/authentication" id="login-form" role="form" method="POST">
					<input type="hidden" name="goUrl" value="<?php echo $this->input->get('goUrl');?>">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <div class="input-group input-icon">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="text" name="userid" id="userid" class="form-control" value="<?php echo $this->input->cookie('login_id_save'); ?>" placeholder="아이디" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <div class="input-group input-icon">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" value="" placeholder="비밀번호" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb0">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                <div class="checkbox-custom">
                                    <input type="checkbox" name="remember" id="remember" value="true" <?php echo $this->input->cookie('login_id_save')?'checked':''; ?>>
                                    <label for="remember">아이디 저장</label>
                                </div>
                            </div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 mb25">
                                <button class="btn btn-default pull-right" type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End .panel -->
        </div>
        <!-- End login container -->
        <div class="container">
            <div class="footer">
                <p class="text-center">&copy;2015 Copyright (주)스파이더웹 All right reserved !!!</p>
            </div>
        </div>
        <!-- Javascripts -->
        <!-- Important javascript libs(put in all pages) -->
        <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script>
        window.jQuery || document.write('<script src="assets/<?echo $this->config->base_url()?>html/js/libs/jquery-2.1.1.min.js">\x3C/script>')
        </script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script>
        window.jQuery || document.write('<script src="assets/<?echo $this->config->base_url()?>html/js/libs/jquery-ui-1.10.4.min.js">\x3C/script>')
        </script>
        <!--[if lt IE 9]>
		  <script type="text/javascript" src="<?echo $this->config->base_url()?>html/js/libs/excanvas.min.js"></script>
		  <script type="text/javascript" src="http:/<?echo $this->config->base_url()?>html5shim.googlecode.com/svn/trunk<?echo $this->config->base_url()?>html5.js"></script>
		  <script type="text/javascript" src="<?echo $this->config->base_url()?>html/js/libs/respond.min.js"></script>
		<![endif]-->
        <!-- Bootstrap plugins -->
        <script src="<?echo $this->config->base_url()?>html/js/bootstrap/bootstrap.js"></script>
        <!-- Form plugins -->
        <script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/jquery.validate.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/additional-methods.min.js"></script>
        <!-- Init plugins olny for this page -->
        <script src="<?echo $this->config->base_url()?>html/js/sw/sw_login.js"></script>
    </body>
<html>