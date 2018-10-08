<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);

$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

 
session_start();
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$si     = "siaca";
$ce     = "cesta";
$ci = $_SESSION['cedula'];
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Tonny Medina">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
   <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!--   <link href="bootstrap/css/bootstrap.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
    #publicidad {
    width:100% ;
    height: 70px;
    background-image: url('images/rrhh_tile.gif');
    background-repeat: repeat-x;
    text-align: right;
    color: #114d7f;
    font-size: 12pt;
  
}

span .error{
	font-size:12px;
	font-style:italic;
	font-weight:normal;
	color: #b94a48;
    display: inline-block;
    *display: inline;
    padding-left: 5px;
    vertical-align: middle;
    *zoom: 1;}

#logotipo   {
    float:left;
    background-image: url('images/rrhh_head.jpg');
    height: 70px;
    width:152px ;
}

    </style>
  </head>

  <body>
<div id="cabecera" class="hidden-xs" ><div id="logotipo"></div><div id="publicidad"  ><span class="hidden-xs hidden-sm">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </span></div></div>

    <div class="container">

      <!-- Static navbar -->
      <div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <a class="navbar-brand"  href="#">Men&uacute;</a>          
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
         <!--   <li class="active"><a href="#" id='minomina'>Mi N&oacute;mina</a></li>-->
            <!--<li><a href="#">Carta de Trabajo</a></li>-->
           
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mis reportes <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <li><a href="#" id="ReporteDeducciones">Reporte Deducciones<span class="badge">Nuevo</span></a></li> 
              
                           
              
              </ul>
            </li>
          </ul>
                   
          <ul class="nav navbar-nav navbar-right">
           <!-- <li ><a href="./">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>-->
           
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/user_orange.png" width="24" height="24" /><?php echo ucwords(strtolower($_SESSION['usuario'])) ; ?><b class="caret"></b></a>
              <ul class="dropdown-menu">
             <!--  <li><a href="#" id='miregistro'>Datos Personales</a></li> -->
                <li><a href="#" id="cambiocontrasena">Cambio de Contraseña</a></li>
                <li><a href="../Seguridad/login.php?accion=bye" >Terminar Sesion</a></li>
              </ul>
            </li>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" id="cuerpo">
        <h2>Bienvenid@</h2>
        <p>Bienvenido al Sistema de Servicios en Línea de la Dirección de Recursos Humanos de la Universidad del Zulia. A partir de este momento usted podrá hacer uso de diversos servicios que ofrecemos de forma digital para agilizar la gestión de sus solicitudes, garantizando un servicio eficiente incluso desde nuestra plataforma web.</p>
       
      
      </div>
  
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery-1.9.0.js"></script>

    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
     <script src="bootstrap/js/tooltip.js"></script>
     <script src="bootstrap/js/transition.js"></script>
     
 <script>
	var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$("#detallesPago").click(function(){
	$( "#cuerpo" ).load( "consultaNominaRRHHBoot.php" );
	});
$("#miregistro").click(function(){
	$( "#cuerpo" ).load( "DatosRegistroFormBT.php" );
	});

$("#constanciaTrabajo").click(function(){
	$( "#cuerpo" ).load( "constanciaTrabajoFormBT.php" );
	});
	
$("#censoUniformes").click(function(){
	$( "#cuerpo" ).load( "censoUniformesAdmForm.php" );
	});
	
$("#cambiocontrasena").click(function(){
	$( "#cuerpo" ).load( "DatosCambioContrasenaFormBT.php" );
	});


$("#ReporteDeducciones").click(function(){
	$( "#cuerpo" ).load( "consultaDeduccionesForm.php" );
	});	
	

	
	
	
}
	 </script>
  </body>
</html>