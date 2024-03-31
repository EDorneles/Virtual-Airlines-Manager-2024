<?php
/**
* @Project: Virtual Airlines Manager (VAM)
* @Author: Alejandro Garcia
* @New_template: Jonatha Silva
* @Web http://virtualairlinesmanager.net
* Copyright (c) 2013 - 2016 Alejandro Garcia
* VAM is licensed under the following license:
*   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
*   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>PPT Virtual | 2.0</title>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<meta name="keywords"
      content="vam, virtual airlines manager , va , ivao, vatsim , airlines manager, prepar3d, aerosoft, pmdg,virtual pilot, piloto virtual, open source,xplane, flight simulator, flight simulation, flight, flying, fsx, fs9, flight simulator x, flight simulator 2004, simulators, simulator, simulation, flight enthusiasts, fsacars, fskeeper"/>
<meta name="description"
      content="PPT Virtual | V2.0"/>
<meta name="author" content="Alejandro Garcia and Jonatha Silva">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel=”author” href=”https://plus.google.com/u/0/108665243705425046932/“ title="Virtual Airlines Manager on Google+" />
<link rel="icon" href="vam_favicon.png" type="image/png" sizes="16x16">
<link rel="shortcut icon" href="images/black-eagle-and-red-sun.ico" >
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap-datetimepicker.min.css"/>
<script src="js/bootstrapValidator.min.js" type="text/javascript"></script>
<script src="Charts/Chart.js"></script>
<script type="text/javascript" src="js/moment-with-locales.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/jquery.confirm.min.js" type="text/javascript"></script>
<!-- Custom styles for this template -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="css/social-vam.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/morris.css" rel="stylesheet">
<!-- data tables plugins -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/numeric-comma.js"></script>
<script src="js/raphael.min.js" type="text/javascript"></script>
<script src="js/morris.min.js" type="text/javascript"></script>
<!-- VAM javascript -->
<script src="js/vam.js" type="text/javascript"></script>
<script type="text/javascript" src="simbrief.apiv1.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php if ($user_logged==0) {
?>
  <header class="main-header">
    <!-- Logo -->
    <a href="./index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b></b>PPT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Private</b> Virtual</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
<!--
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Notifications: style can be found in dropdown.less 
          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div> -->
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image"><!--images/tail.jpg-->
          <img src="images/red-user-icon.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>You're not logged</p>
          <a href="login/login.html" role="button"><span class="glyphicon glyphicon-log-in"></span> Log in</a>
        </div>
      </div>
      <!-- search form -->
      <form id="frmSearch" method="get" class="sidebar-form" action="default.html">
        <div class="input-group">
          <input id="txtSearch" type="text" name="serach_bar" class="form-control" placeholder="Check metar...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-cloud"></i>
                </button>
              </span>
        </div>
      </form>
      
      <script type="text/javascript">
    document.getElementById('frmSearch').onsubmit = function() {
        window.location = './index.php?page=airport_info&airport=' + document.getElementById('txtSearch').value;
        return false;
    }
</script>
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVEGAÇÃO PRINCIPAL
</li>
        <li>
          <a href="./index.php">
            <i class="fa fa-th"></i> <span>Home</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-info-circle fa-fw"></i>
            <span>Sobre </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./index.php?page=staff"><i class="fa fa-user-o fa-fw"></i> Staff</a></li>
            <li><a href="./index.php?page=rules"><i class="fa fa-file-text-o fa-fw"></i> Regras</a></li>
            <li><a href="http://www.privatevirtual.com.br/vam/index.php?page=school" target="_blank"><i class="fa fa-graduation-cap fa-fw"></i> Academy</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-sliders fa-fw"></i>
            <span>OPERAÇÕES</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./index.php?page=fleet_public"><i class="fa fa-plane fa-fw"></i> Frota</a></li>
            <li><a href="./index.php?page=route_public"><i class="fa fa-globe fa-fw"></i> Rotas</a></li>
            <li><a href="./index.php?page=hubs"><i class="fa fa-crosshairs fa-fw"></i> Hubs</a></li>
            <li><a href="./index.php?page=tours"><i class="fa fa-code-fork fa-fw"></i> Tours</a></li>
            <li><a href="./index.php?page=ranks"><i class="fa fa-bookmark fa-fw"></i> Ranks</a></li>
            <li><a href="./index.php?page=awards"><i class="fa fa-star fa-fw"></i> Awards</a></li>
            <li><a href="./index.php?page=va_global_financial_report"><i class="fa fa-dollar fa-fw"></i> Financial report</a></li>
          </ul>
        </li>
<li>
          <a href="./index.php?page=pilots_public">
            <i class="fa fa-users fa-fw"></i> <span> NOSSA TRIPULAÇÃO</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"><?php echo $num_pilots; ?></small>
            </span>
          </a>
        </li>
        <li>
          <a href="./index.php?page=stats">
            <i class="fa fa-area-chart fa-fw"></i> <span> Estatísticas</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li class="header">LABELS</li>
        <li class="bg-green">					
          <a href="./index.php?page=school" style="background-color: rgb(0, 166, 90);">
            <i class="fa fa-user-plus" style="color: rgb(255, 255, 255);"></i> <span style="color: rgb(255, 255, 255);"> INSCRIÇÕES</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-blue">ABERTAS</small>
            </span>
          </a>
        </li>
        <li><a href="https://api.whatsapp.com/send?phone=351932271334" target="_blank"><i class="fa fa-whatsapp"></i> <span> Whatsapp Contact</span>
					</a></li>
      
<li class="header">POWERED BY</li>
<li><a href="https://adminlte.io/" target="_blank"><i class="fa fa-circle-o text-red"></i> <span> AdminLTE</span></a></li>
<li><a href="http://virtualairlinesmanager.net" target="_blank"><i class="fa fa-globe text-blue"></i> <span> VAM</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

<?php
}
else{      
        include('get_pilot_data.php');
        include('get_va_data.php');
				?>  <header class="main-header">
    <!-- Logo -->
    <a href="./index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b></b>PPT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Private</b> Virtual</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
<!--
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Notifications: style can be found in dropdown.less 

          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div> -->
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if (strlen ($pilot_image)<=10)
							{
								$pilot_image="./uploads/pilot_default.png";
							}
							echo '<img src='.$pilot_image.' style="height:45px;width:45px;" class="img-circle">'; ?>
        </div>
        <div class="pull-left info">
          <p> <?php echo $_SESSION["name"] . ' | ' . $_SESSION["user"]; ?>&nbsp;<a href="./index_vam_op.php?page=my_profile"><span class="glyphicon glyphicon-edit"></span></a> </p>
          <a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> <?php echo 'Log out '; ?></span>
          </a>
        </div>
      </div>
      <!-- search form -->
      <form id="frmSearch" method="get" class="sidebar-form" action="default.html">
        <div class="input-group">
          <input id="txtSearch" type="text" name="serach_bar" class="form-control" placeholder="Check metar...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-cloud"></i>
                </button>
              </span>
        </div>
      </form>
      
      <script type="text/javascript">
    document.getElementById('frmSearch').onsubmit = function() {
        window.location = './index.php?page=airport_info&airport=' + document.getElementById('txtSearch').value;
        return false;
    }
</script>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVEGAÇÃO PRINCIPAL</li>
        <li>
          <a href="./index.php">
            <i class="fa fa-th"></i> <span>Home</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
<li>
          <a href="./index_vam.php">
            <i class="ion ion-person fa-fw"></i><span>Pilot area</span>
            <span class="treeview">
            </span>
          </a>
        </li>
        <li class="bg-green">
          <a href="./index_vam_op.php?page=route_selection_stage1" style="background-color: rgb(0, 166, 90);">
            <i class="fa fa-plane fa-fw" style="color: rgb(255, 255, 255);"></i><span style="color: rgb(255, 255, 255);">RESERVA DE VÔOS</span>
            <span class="treeview">
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-info-circle fa-fw"></i>
            <span>About </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./index.php?page=staff"><i class="fa fa-user-o fa-fw"></i> Staff</a></li>
            <li><a href="./index.php?page=rules"><i class="fa fa-file-text-o fa-fw"></i> Rules</a></li>
            <li><a href="http://www.privatevirtual.com.br/vam/index.php?page=school" target="_blank"><i class="fa fa-graduation-cap fa-fw"></i> Academy</a></li>
          </ul>
        </li>
          <li class="treeview">
          <a href="#">
            <i class="fa fa-gears fa-fw"></i>
            <span>MENU DO PILOTO </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./index_vam_op.php?page=mails"><i class="fa fa-envelope fa-fw"></i> E-mail Interno</a></li>
            <li><a href="./index_vam_op.php?page=route_selection_stage1"><i class="fa fa-plane fa-fw"></i> Reservar Rota</a></li>
            <li><a href="#" data-toggle="modal" data-target="#JumpModal"><i class="fa fa-map-marker fa-fw"></i> Alterar Localização</a></li>
            <li><a href="./index_vam_op.php?page=my_bank"><i class="fa fa-money fa-fw"></i> Minhas Finanças</a></li>
            <li><a href="./index_vam_op.php?page=pirep_manual_create"><i class="fa fa-hand-pointer-o fa-fw"></i> Enviar voo Manual</a></li>
            <li><a href="./index_vam_op.php?page=my_profile"><i class="fa fa-edit fa-fw"></i> Editar Perfil</a></li>
            <li><a href="./index_vam_op.php?page=pilot_profile_stats&pilotid=<?php echo $_SESSION["id"];?>"><i class="fa fa-area-chart fa-fw"></i> Meus Status</a></li>
            <li><a href="./index_vam_op.php?page=vaparameters_info"><i class="fa fa-exclamation fa-fw"></i> Parâmetros da PPT</a></li>
            <li><a href="./index_vam_op.php?page=download"><i class="fa fa-download fa-fw"></i> Downloads</a></li>
            <li><a href="./index_vam_op.php?page=tours_pilot"><i class="fa fa-external-link-square fa-fw"></i> Reportar Tour</a></li>
          </ul>
        </li>
<li class="treeview">
          <a href="#">
            <i class="fa fa-sliders fa-fw"></i>
            <span>OPERAÇÕES</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="./index.php?page=fleet_public"><i class="fa fa-plane fa-fw"></i> Frota</a></li>
            <li><a href="./index.php?page=route_public"><i class="fa fa-globe fa-fw"></i> Rotas</a></li>
            <li><a href="./index.php?page=hubs"><i class="fa fa-crosshairs fa-fw"></i> Hubs</a></li>
            <li><a href="./index.php?page=tours"><i class="fa fa-code-fork fa-fw"></i> Tours</a></li>
            <li><a href="./index.php?page=ranks"><i class="fa fa-bookmark fa-fw"></i> Ranks</a></li>
            <li><a href="./index.php?page=awards"><i class="fa fa-star fa-fw"></i> Awards</a></li>
            <li><a href="./index.php?page=va_global_financial_report"><i class="fa fa-dollar fa-fw"></i> Financial report</a></li>
          </ul>
        </li>
<li>
          <a href="./index.php?page=pilots_public">
            <i class="fa fa-users fa-fw"></i> <span> LISTA DE PILOTOS</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"><?php echo $num_pilots; ?></small>
            </span>
          </a>
        </li>
        <li>
          <a href="./index.php?page=stats">
            <i class="fa fa-area-chart fa-fw"></i> <span> ESTATÍSTICAS</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
<?php if ($_SESSION["access_administration_panel"] == 1) { ?>
				<li><a href="../vamcore/Gvausers" target="_blank">
            <i class="glyphicon glyphicon-briefcase fa-fw"></i> <span> PAINEL ADM</span>
            <span class="pull-right-container">
            </span>
          </a>	
				<li>
				<a href="../vam/index.php?page=pilot_register">
            <i class="ion ion-person-add fa-fw"></i><span> ADD NOVO PILOTO</span>
            <span class="pull-right-container">
						</span></a>
<?php } ?>
        <li class="header">LABELS</li>
        <li><a href="https://api.whatsapp.com/send?phone=351932271334" target="_blank"><i class="fa fa-whatsapp"></i> <span> Whatsapp Contact</span>
					</a></li>
<li class="header">POWERED BY</li>
<li><a href="https://adminlte.io/" target="_blank"><i class="fa fa-circle-o text-red"></i> <span> AdminLTE</span></a></li>
<li><a href="http://virtualairlinesmanager.net" target="_blank"><i class="fa fa-globe text-blue"></i> <span> VAM</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<?php
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <h1>
    <a href="https://sites.google.com/view/pptvirtual" target="_blank">
      Private Plane Táxi Aéreo
      <small>A new look to your va. V2.0</small>
    </a>
    <div class="box-tools pull-right">
      <!-- Conteúdo relacionado às suas ferramentas aqui -->
    </div>
  </h1>
    </section>
    
    <div class="modal fade" id="examiner_johnny" tabindex="-1" role="dialog" aria-labelledby="myregLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
      <h4 class="modal-title"><center><b>Jonatha Silva</b></center></h4><center>VAM developer</center><br>
			<center><img src="images/developer1.jpg" WIDTH="200" HEIGHT="200" BORDER=0 ALT="20" class="img-circle"></center>
			<h2><center><span class="label label-warning">Johnny</span></center></h2>
      </div>
      <div class="modal-body">
			<center><p>Johnny is an official <strong>VAM Developer</strong> and <strong>Edsilo</strong> cofounder</p><p></p></center>
        <div class="modal-footer"><br>
		  <center><strong><i class="fa fa-envelope"></i> jonatha@edsilo.com</strong></center>
          <a href="https://edsilo.com" target="_blank"><i class="fa fa-globe"> </i></a>
          <a href="https://fb.com/silvajohnny777" target="_blank"><i class="fa fa-facebook-square"> </i></a>
          <a href="https://instagram.com/silvajohnny777" target="_blank"><i class="fa fa-instagram"> </i></a>
          <a href="https://twitter.com/silvajohnny777" target="_blank"><i class="fa fa-twitter"> </i></a>
          <a href="https://github.com/silvajohnny777" target="_blank"><i class="fa fa-github"> </i></a>
          <a href="https://www.linkedin.com/in/silvajohnny777" target="_blank"><i class="fa fa-linkedin-square"> </i></a>
          <a href="https://virtuallh.com" target="_blank"><i class="fa fa-plane"> </i></a>
        </div>
		  <p></p>
		  </div>
      </div>
			</div>
			</div>
    <div class="modal fade" id="JumpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo CHANGE_LOCATION; ?></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="change-location-form" action="./index_vam_op.php?page=jump_insert"
				      role="form" method="post">
					<div class="form-group">
						<label class="control-label col-sm-2" for="destiny"><?php echo CHANGE_LOCATION_ICAO; ?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="destiny" id="destiny"
							       placeholder="Enter Callsign">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit"
							        class="btn btn-primary"><?php echo CHANGE_LOCATION_SUBMIT_BTN; ?></button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.nav-tabs-custom -->
<div class="row">
<div class="col-md-12">
<div id="carousel">
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
<!-- Indicators -->

<!-- Wrapper for slides -->
<!-- Controls -->
<!-- Carousel -->
</div>
</div><br>
</div></section>
<br><div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<center><h4 class="modal-title" id="myModalLabel">Login to Virtual LH</h4></center>
</div>
<div class="modal-header">
<center><img src="images/pilot_login.png" WIDTH="128" HEIGHT="128" BORDER=0 ALT="20"></center>
</div>
<div class="modal-body">
<form class="form-horizontal" id="login-form" action="./login.php" role="form"
      method="post">
<div class="form-group">
<label class="control-label col-sm-2" for="user">Callsign:</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="user" id="user"
       placeholder="Enter Callsign">
</div>
</div>
<div class="form-group">
<label class="control-label col-sm-2" for="password">Password:</label>
<div class="col-sm-10">
<input type="password" class="form-control" name="password" id="password"
       placeholder="Enter password">
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<div class="checkbox">
<label><input type="checkbox"> Remember me</label>
</div>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<div class="checkbox">
<a href="./index.php?page=password_recover">Recover Password</a>
</div>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-primary">Login</button>
</div>
</div>
</form>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<section class="col-lg-12 connectedSortable">
  <br>

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
