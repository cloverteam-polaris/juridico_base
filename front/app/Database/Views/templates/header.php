<!DOCTYPE html>
<html lang="en">

<head>
    <title>Universe Collector</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <!-- Favicon icon -->
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/animation/css/animate.min.css">
    <!-- prism css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/prism/css/prism.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">

    <script src="<?php echo base_url(); ?>assets/js/vendor-all.min.js"></script>

</head>

<body><!-- [ navigation menu ] start -->
    <?php 
    $uri = service('uri'); 
    $classNav = "";
    if($uri->getSegment(2) == "cliente"){
        $classNav = "navbar-collapsed";
    }
    ?>
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar menupos-fixed <?php echo $classNav; ?>">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="index.html" class="b-brand">
                    <img src="<?php echo base_url(); ?>assets/images/logo_horizontal_dark_300.png" alt="Clover Logo" style="width: 180px;" class="logo images">
                    <img src="<?php echo base_url(); ?>assets/images/Trebol_80.png" alt="Clover Logo" style="width: 35px;" class="logo-thumb images">
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Cobranzas</label>
                    </li>
                    
                    <li class="nav-item"><a href="<?php echo base_url("panel") ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a></li>
                    <li class="nav-item"><a href="<?php echo base_url("buscar") ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-search"></i></span><span class="pcoded-mtext">Buscar</span></a></li>

                     <li data-username="Vertical Horizontal Box Layout RTL fixed static collapse menu color icon dark" class="nav-item pcoded-hasmenu  active pcoded-trigger">
                        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Usuarios</span></a>
                        <ul class="pcoded-submenu">
                            
                            <li class=""><a href="<?php echo base_url("usuarios/listaUsuarios") ?>"  class="" target="_blank">Lista Usuarios</a></li>
                            <li class=""><a  href="<?php echo base_url("usuarios/crearUsuario") ?>" class="" target="_blank">Crear Usuario</a></li>
                           
                        </ul>
                    </li>

<li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-sliders"></i></span><span class="pcoded-mtext">Administracion</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="<?php echo base_url("administracion/listaTprocesos") ?>" class="">Lista de tipos de procesos</a></li>
                            <li class=""><a  href="<?php echo base_url("administracion/listaMacroetapas") ?>"class="">Lista de macroetapa</a></li>
                            <li class=""><a  href="<?php echo base_url("administracion/listaMicroetapas") ?>" class="">Lista de microetapas</a></li>
                        </ul>
                    </li>

                   
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light">
        <div class="collapse navbar-collapse">
            <div class="m-header">
				<a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
				<a href="index.html" class="b-brand">

					<img src="<?php echo base_url(); ?>assets/images/logo_horizontal.jpg" alt="" class="logo images">
					<img src="<?php echo base_url(); ?>assets/images/trebol_80.png" alt="" style="width: 35px;" class="logo-thumb images">
				</a>
			</div>
			<a class="mobile-menu" id="mobile-header" href="#!">
				<i class="feather icon-more-horizontal"></i>
			</a>
            <ul class="navbar-nav me-auto">
                
            </ul>
            <ul class="navbar-nav ms-auto">
                <li>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                        <div class="dropdown-menu dropdown-menu-end notification">
                            <div class="noti-head">
                                <h6 class="d-inline-block m-b-0">Notificaciones</h6>
                            </div>
                            <ul class="noti-body">
                                <li class="n-title">
                                    <p class="m-b-0">NUEVAS</p>
                                </li>
                                <li class="notification">
                                    <div class="d-flex">
                                        <div class="media-body">
                                            <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                                            <p>New ticket Added</p>
                                        </div>
                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="icon feather icon-settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-notification">
                            <ul class="pro-body">
                                <li><a href="#!" class="dropdown-item"><i class="feather icon-user"></i> Perfil</a></li>
                                <li><a href="auth-signin.html" class="dropdown-item"><i class="feather icon-lock"></i> Pausas</a></li>
                                <li><a href="<?php echo PROJECT_URL."cerrarsesion"; ?>" class="dropdown-item"><i class="fas fa-user-alt-slash"></i> Cerrar sesion</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->