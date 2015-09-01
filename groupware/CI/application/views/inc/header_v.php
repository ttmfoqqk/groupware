<!doctype html>
<!--[if lt IE 8]><html class="no-js lt-ie8"> <![endif]-->
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <title>Dashboard | Dynamic Admin Template</title>
        <!-- Mobile specific metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
        <!-- Force IE9 to render in normal mode -->
        
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
		<link rel="stylesheet" href="<?echo $this->config->base_url()?>daumeditor/css/editor.css" type="text/css" charset="utf-8"/>
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?echo $this->config->base_url()?>html/img/ico/apple-touch-icon-57-precomposed.png">
        <link rel="icon" href="<?echo $this->config->base_url()?>html/img/ico/favicon.ico" type="image/png">
        <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
        <meta name="msapplication-TileColor" content="#3399cc" />


		<!-- Important javascript libs(put in all pages) -->
        <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script>
        window.jQuery || document.write('<script src="<?echo $this->config->base_url()?>html/js/libs/jquery-2.1.1.min.js">\x3C/script>')
        </script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script>
        window.jQuery || document.write('<script src="<?echo $this->config->base_url()?>html/js/libs/jquery-ui-1.10.4.min.js">\x3C/script>')
        </script>
        <!--[if lt IE 9]>
		  <script type="text/javascript" src="<?echo $this->config->base_url()?>html/js/libs/excanvas.min.js"></script>
		  <script type="text/javascript" src="http:/<?echo $this->config->base_url()?>html5shim.googlecode.com/svn/trunk<?echo $this->config->base_url()?>html5.js"></script>
		  <script type="text/javascript" src="<?echo $this->config->base_url()?>html/js/libs/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
        <!--[if lt IE 9]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
        <!-- .page-navbar -->
        <div id="header" class="page-navbar">
            <!-- .navbar-brand -->
            <a href="<?echo base_url()?>" class="navbar-brand hidden-xs hidden-sm">
                <img src="<?echo $this->config->base_url()?>html/img/logo.png" class="logo hidden-xs" alt="Dynamic logo">
                <img src="<?echo $this->config->base_url()?>html/img/logosm.png" class="logo-sm hidden-lg hidden-md" alt="Dynamic logo">
            </a>
            <!-- / navbar-brand -->
            <!-- .no-collapse -->
            <div id="navbar-no-collapse" class="navbar-no-collapse">
                <!-- top left nav -->
                <ul class="nav navbar-nav">
                    <li class="toggle-sidebar">
                        <a href="#">
                            <i class="fa fa-reorder"></i>
                            <span class="sr-only">Collapse sidebar</span>
                        </a>
                    </li>
                </ul>
                <!-- / top left nav -->
                <!-- top right nav -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="sr-only">Notifications</span>
                            <span class="badge badge-danger">6</span>
                        </a>
                        <ul class="dropdown-menu right dropdown-notification" role="menu">
                            <li><a href="#" class="dropdown-menu-header">Notifications</a></li>
                            <li><a href="<?echo site_url('board/lists/notice');?>"><i class="l-basic-elaboration-message-dots"></i> 2 공지사항</a></li>
                            <li><a href="<?echo site_url('approved_send/lists/all');?>"><i class="l-basic-elaboration-mail-upload"></i> 보낸 결재</a></li>
                            <li><a href="<?echo site_url('approved_receive/lists/all');?>"><i class="l-basic-elaboration-mail-download"></i> 받은 확인</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?echo site_url('login/logout');?>">
                            <i class="fa fa-power-off"></i>
                            <span class="sr-only">Logout</span>
                        </a>
                    </li>
                </ul>
                <!-- / top right nav -->
            </div>
            <!-- / collapse -->
        </div>
        <!-- / page-navbar -->
        <!-- #wrapper -->
        <div id="wrapper">