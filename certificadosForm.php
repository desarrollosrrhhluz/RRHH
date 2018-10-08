<?php
/*  --------------------------------------------------------------------------------------------
                                                                                       
 .d8888b.888888888888888888        8888888b. 8888888b. 888    888888    888 
d88P  Y88b 888      888            888   Y88b888   Y88b888    888888    888 
Y88b.      888      888            888    888888    888888    888888    888 
 "Y888b.   888      888            888   d88P888   d88P88888888888888888888 
    "Y88b. 888      888            8888888P" 8888888P" 888    888888    888 
      "888 888      888   888888   888 T88b  888 T88b  888    888888    888 
Y88b  d88P 888      888            888  T88b 888  T88b 888    888888    888 
 "Y8888P"8888888    888            888   T88b888   T88b888    888888    888 
    

        Nombre              : []
        Autor               : Ing. Tonny Medina
        Fecha Creación      : Fecha
        Objetivo / Resumen  : Descripcion.
        Ultima Modificación : Fecha ?>
------------------------------------------------------------------------------------------------ */
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHCERT';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];
session_start();
$dbr="rrhh";
$ci = $_SESSION['cedula'];
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Tonny Medina">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="icons/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css" />
        

 

    <style>
    body, input, table{
      font-size: 13px
    }

   .icono{
cursor:pointer;


}

.form-control, .btn{
      font-size: 13px 
    }

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
.row{padding-bottom: 5px;}
.file-drop-zone-title{font-size: 14px}

    </style>
  </head>

  <body>

<div id="cabecera"  ><div id="logotipo"></div><div id="publicidad"  ><span class="hidden-xs hidden-sm">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </span></div></div>
<div class="container kv-main">
<!-- <img src="aspirantes.php?op=muestraImagen&id=31&tipo=2" alt=""> -->
<h3>Programa de Formacion Continua</h3>
<div class="text-right"><a href="https://www.servicios.luz.edu.ve/Seguridad/login.php?accion=bye"><i class="fa fa-key text-primary"></i> Terminar Sesión</a></div>
<div class="panel panel-default">
  <div class="panel-heading"><i class="fa fa-graduation-cap  fa-2x text-primary"></i> <b>Mis certificados</b>  </div>
  <div class="panel-body">
  <?php
$sql="select c.id,a.descripcion as adiestramiento, d.descripcion as participacion  from admon_personal.tab_adiestramientos as a,
admon_personal.tab_participantes as b, admon_personal.tab_adiestramiento_participante as c,
opciones.tab_opciones as d
where b.cedula=$ci
and a.id=c.id_adiestramiento
and b.id=c.id_participante
and c.id_tipo_participacion=d.id";
  $ds = new DataStore($dbr, $sql);
     $i=$ds->getNumRows();
    if($i<=0){     
       echo "<p>No tiene cerficiados asociados</p>"; 
        } else { 
    $j=0;
     
     while ( $i> $j) {
      $datos = $ds->getValues($j);
      echo "<li>".$datos['adiestramiento']."(<a href='certificadoAdiestramientoPDF.php?id=".$datos['id']."'>".$datos['participacion']."</a>)</li>";

      $j++;
     }

   }
  
  ?> 
  </div>
</div>


  </body>

  

  
</html>
