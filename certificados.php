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
		

				Nombre              : aspirantes.php
				Autor               : Ing. Tonny Medina
				Fecha Creación      : 15/04/2015
				Objetivo / Resumen  : funciones de la aplicacion de Postulacion Oferente de Servicios
				Ultima Modificación : 15/04/2015
------------------------------------------------------------------------------------------------ */
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');
include("includes/resize-class.php");

$db="rrhh";
$cedula=$_SESSION['cedula'];
$nombreTrabajador=$_SESSION['nombres'];
$anno=$_SESSION['ano_proceso']=2015;
$op= $_REQUEST['op'];

//********************************************** 
     switch($op) { 
	 //**************DATOS PERSONALES***********	 
	         
			 case 'muestraImagen':
			 $id=$_REQUEST['id']; // id aspirante
	         muestraImagen($id, $tipo);
			 break;
			
			 
			  
			 }

	//****************************************


function  muestraImagen($id){
global $db, $cedula, $anno;

 $sql =  "SELECT  * FROM admon_personal.tab_firma_participante where id_participante=$id";
       
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<=0){
		$im = imagecreatetruecolor(100, 30);
		imagefilledrectangle($im, 0, 0, 100, 100, 0xffffff);
		imagestring($im, 1, 5, 20, 'Firma No Disponible', 0x000000);
		header('Content-type: image/png');
		imagegif($im);
		imagedestroy($im);	
		  }else{
		   $row=$ds->getValues(0);
		   $file=pg_unescape_bytea($row['archivo']);
		   header('Content-Type: '.$row['mime'].'');
		   echo $file;	
		   }


}

?>
