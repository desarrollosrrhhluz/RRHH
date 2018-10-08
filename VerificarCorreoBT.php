<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 


include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="system";
$id= $_REQUEST['id'];

 $sql = "SELECT da_e_mail_2
  FROM 
  mst_registro_usuario 
  where 
  md5(id_user::varchar)='$id'";

    $ds = new DataStore($db, $sql);
    
     
	$fila = $ds->getValues(0);
	$correoCambio=$fila['da_e_mail_2'];	



if ($ds->getNumRows() > 0) {

	if($correoCambio=="")
	{

	echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">

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
    background-image: url("images/rrhh_tile.gif");
    background-repeat: repeat-x;
    text-align: right;
    color: #114d7f;
    font-size: 12pt;
  
}
#logotipo   {
    float:left;
    background-image: url("images/rrhh_head.jpg");
    height: 70px;
    width:152px ;
}

    </style>
  </head>';


	echo ' <body>
<div id="cabecera" ><div id="logotipo"></div><div id="publicidad">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </div></div>

    <div class="container">

      <!-- Static navbar -->
     

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <p><img src="images/exclamation.png">&nbsp;&nbsp;CORREO YA VERIFICADO</p>
        <p></p>
        <p>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-primary" href="https://www.servicios.luz.edu.ve/RRHH/">Iniciar Sesi&oacute;n</a>
        </p>
    
</div>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/assets/js/jquery.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>';
	
		
		
		
		
		
		
		}else{
			
   
		
  $sql2 = "UPDATE mst_registro_usuario 
  SET da_e_mail = '$correoCambio', 
  correo_estatus_actualiza = 1,
  da_e_mail_2 = '' 
  where md5(id_user::varchar)= '$id'";
     
   $ds2 = new DataStore($db, $sql2);
   
   
   echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">

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
    background-image: url("images/rrhh_tile.gif");
    background-repeat: repeat-x;
    text-align: right;
    color: #114d7f;
    font-size: 12pt;
  
}
#logotipo   {
    float:left;
    background-image: url("images/rrhh_head.jpg");
    height: 70px;
    width:152px ;
}

    </style>
  </head>';

	if ($ds2->getAffectedRows() > 0) {
	echo ' <body>
<div id="cabecera" ><div id="logotipo"></div><div id="publicidad">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </div></div>

    <div class="container">

      <!-- Static navbar -->
     

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <p><img src="images/accept.png">&nbsp;&nbsp;SU CORREO HA SIDO VERIFICADO</p>
        <p></p>
        <p>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-primary" href="https://www.servicios.luz.edu.ve/RRHH/">Iniciar Sesi&oacute;n</a>
        </p>
    
</div>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/assets/js/jquery.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>';
	}else{
	echo ' <body>
<div id="cabecera" ><div id="logotipo"></div><div id="publicidad">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </div></div>

    <div class="container">

      <!-- Static navbar -->
    

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <p><img src="images/cancel.png">&nbsp;&nbsp;PROBLEMAS AL REALIZAR LA OPERACIÓN</p>
        <p></p>
        <p>
          <a class="btn btn-lg btn-danger" href="../../components/#navbar">Iniciar Sesi&oacute;n</a>
        </p>
    
</div>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/assets/js/jquery.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>';
	}
	
	
			
		}

	}else{
		
	 echo ' <body>
<div id="cabecera" ><div id="logotipo"></div><div id="publicidad">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </div></div>

    <div class="container">

      <!-- Static navbar -->
     

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <p><img src="images/cancel.png">&nbsp;&nbsp;PROBLEMAS AL REALIZAR LA OPERACIÓN</p>
        <p></p>
        <p>
          <a class="btn btn-lg btn-danger" href="../../components/#navbar">Iniciar Sesi&oacute;n</a>
        </p>
    
</div>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/assets/js/jquery.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>';	
   
	}


?>