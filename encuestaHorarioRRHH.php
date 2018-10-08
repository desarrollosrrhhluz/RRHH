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
    <meta name="description" content="Portal de servicios en linea de la direccion de recursos humanos de la Univerisidad del Zulia">
    <meta name="author"  content="Ing. Tonny Medina">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
   <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
   <link href="bootstrap/dist/css/bootstrap-switch.min.css" rel="stylesheet">
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
body{font-size: 16px; }

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
           <a class="navbar-brand"  href="#">Encuesta Horario Corrido</a>          
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
         <!--   <li class="active"><a href="#" id='minomina'>Mi N&oacute;mina</a></li>-->
            <!--<li><a href="#">Carta de Trabajo</a></li>-->
           
          </ul>
    
          
          <ul class="nav navbar-nav navbar-right">
           <!-- <li ><a href="./">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>-->
           
            <li><a href="../Seguridad/login.php?accion=bye" >Terminar Sesion</a></li>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" id="cuerpo">
       <?php
      $dbs="sidial";
      $dbr="rrhh";
    $sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS in ('A','C') and ESTATUS!='R' and ESTATUS!='S' and CONVERT(CHAR(5), CO_UBICACION) like '38%' and SUBSTRING(TIPOPERSONAL,4,1) not in ('8','9','4') ";
        $ds     = new DataStore($dbs, $sql2);
      if($ds->getNumRows()==0){
    echo '<h2>No puedes participar en la encuesta!!</h2>
        <p>Usted no es un trabajador de la Direccion de Recursos Humanos</p>
       ';
    
    }else{
    $row = $ds->getValues(0);
    $_SESSION['sexo']=$row['SEXO'];
    $_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
    $_SESSION['coubicacion']=$row['CO_UBICACION'];
    $_SESSION['ano_proceso']=2015;
    $_SESSION['estatus']=$row['ESTATUS'];
    $_SESSION['nombres']=trim(utf8_encode($row['NOMBRES']));


      $sql =  "select * from tab_fiesta_rrhh where ce_trabajador=$ci";
      $ds2     = new DataStore($dbr, $sql);
      if($ds2->getNumRows()<=0){//No se ha censado
     echo '<div class="text-left "><h3 class="text-success">A continuación, deberá elegir cuál de los siguientes horarios es el de su preferencia:</h3><br>
      <form id="form" method="post" accept-charset="utf-8">
      <div class="text-left" >
      <input type="radio" name="opcion" value="1" required> De 7:30 am a 2:30 pm <br>
      <input type="radio" name="opcion" value="2" required> De 8:00 am a 3:00 pm <br>
      </div>
      <h3 class="text-success">¿Es imprescindible para usted ausentarse a la hora del mediodía, lo cual le impediría cumplir con la jornada corrida?</h3><br>
      <div class="text-left" >
      <input type="radio" name="opcion2" value="3" required> Si <br>
      <input type="radio" name="opcion2" value="4" required> No<br>
      </div>

      <div class="text-center">
      <button type="button" id="nuevo" class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-inbox"></i> Votar</button>
      </div>
      
    </form>
      </div>';
      }else{// Censado
     echo '<div class="text-center"><h2>Ya participaste en la encuesta!</h2>';
      }

}



        ?>

        
      
      </div>
  
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery-1.9.0.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap-switch.min.js"></script>
    <script src="bootstrap/js/tooltip.js"></script>
    <script src="bootstrap/js/transition.js"></script>
     
 <script>
var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 

nuevo();
	
	
}
function nuevo(){
$("#nuevo").click(function(){
var res = $('input:radio[name=opcion]:checked').val();
var res2 = $('input:radio[name=opcion2]:checked').val();
if(res>0){
  if(res2>0){

   $.post('censoFiestaRRHH_.php', {op:'nuevo', id:res, id2:res2}, function(data) {
     consulta();
   }); 
 }else{
  alert("Debe seleccionar 2 opciones");
}
}else{
  alert("Debe seleccionar 2 opciones");
}

});

}

function consulta(){
$.post('censoFiestaRRHH_.php', {op: 'consultaCenso'}, function(data) {
$("#cuerpo").html(data);
nuevo();
});

}

	 </script>
  </body>
</html>