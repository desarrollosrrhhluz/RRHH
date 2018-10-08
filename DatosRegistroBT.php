<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');
 require 'includes/class.phpmailer.php';
$db="system";
$db1="system_q";
$ce_trabajador=$_SESSION["cedula"];

$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
			case 'RegistroFormulario':
	        RegistroFormulario();
			break;
			case 'DatosRegistroFormulario':
	        DatosRegistroFormulario();
			break;
			case 'CambiaContrasena':
	        CambiaContrasena();
			break;
            case 'VerificarCorreoFormulario':
	        VerificarCorreoFormulario();
			break;

			 }


///////////////////////////////////////////////////////////
function RegistroFormulario(){
global $db,$db1,$ce_trabajador;
extract($_POST);

$sql = "UPDATE 
  mst_registro_usuario  
SET 
  da_primer_nombre = '$primernombre',
  da_segundo_nombre = '$segundonombre',
  da_primer_apellido = '$primerapellido',
  da_segundo_apellido = '$segundoapellido',
  de_pregunta = '$preguntasecreta',
  de_respuesta = '$respuesta',
  de_direccion = '$direccion',
  da_telefonos = $telefono,
  da_e_mail_2 = '$cambiocorreo'
  where co_cedula=".$ce_trabajador."";
    
   $ds = new DataStore($db);
   $x= $ds->executesql($sql);



	if ($x > 0) {
        
		 
		 $id = md5 ($idusuario); 
		 
		 if($cambiocorreo != ''){
			 echo "2";
			 
		$link="https://www.servicios.luz.edu.ve/RRHH/VerificarCorreoBT.php?id=$id" ;

					$mensaje= '<div align="center"><h4>Validación de correo</h4></div> 
					<div align="justify">	
					<br/>
Por favor presione el link siguiente para culminar la verificación de su correo.  Si no funciona copie y péguelo en la barra de un explorador como mozilla o chrome.:<br/><br/>

'.$link.'

</div>	
';
					
	$notalegal='<div><p style="font-size:10px"><b>Si este correo no era para usted por favor bórrelo.</b><br></div>';
		

	$mail  = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = '465';                                                                   // Puerto a utilizar
	$mail->Username   = 'soporte.tecnico@rrhh.luz.edu.ve';
	$mail->Password   = 'sitrrhh20101';
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->CharSet    = "UTF-8";
	$mail->From       = "soporte.tecnico@rrhh.luz.edu.ve";
	$mail->FromName   = 'Soporte Web RRHH';
	$mail->IsHTML(true); 
  $mail->ContentType = 'text/html';

  	
 $mail->AddBCC($cambiocorreo); 


  $mail->Subject = "Validación de correo ".date('d-m-y h:i:s A');
  $mail->Body = $mensaje.'<br><br>'.$notalegal;
  $mail->AltBody = "";
  $mail->ConfirmReadingTo="soporte.tecnico@rrhh.luz.edu.ve";
  $exito = $mail->Send();
  $intentos=1; 
  $aux=0; 
  
  while ((!$exito)) {
	sleep(5);
	
	  if($intentos == 3){
		  $aux=1;
		  break;
   }
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }
   
   
}else{
	
	 echo "1";
	
	
}
		 
		 
		 
		 
		 
		 
		 
        } else {
         echo "0";
        }

}
	
	
///////////////////////////////////////////////////////////
function DatosRegistroFormulario(){
global $db,$db1,$ce_trabajador;
extract($_POST);


$sql = "SELECT 
  id_user,
  da_primer_nombre,
  da_segundo_nombre,
  da_primer_apellido,
  da_segundo_apellido,
  da_e_mail,
  da_password,
  da_tipo_usuario,
  de_pregunta,
  de_respuesta,
  da_sexo,
  de_direccion,
  fe_nacimiento,
  roles,
  da_telefonos,
  da_e_mail_2,
  correo_estatus_actualiza,
  co_rif
FROM 
  public.mst_registro_usuario where co_cedula=".$ce_trabajador."";
    
    $ds = new DataStore($db1, $sql);
    
    $fila = $ds->getValues(0);
    
    $jsondata = array();
    
    $jsondata['da_primer_nombre'] =utf8_encode(utf8_decode($fila['da_primer_nombre']));
    $jsondata['da_segundo_nombre'] = utf8_encode(utf8_decode($fila['da_segundo_nombre']));
    $jsondata['da_primer_apellido'] = utf8_encode(utf8_decode($fila['da_primer_apellido']));
	$jsondata['da_segundo_apellido'] = utf8_encode(utf8_decode($fila['da_segundo_apellido']));
	$jsondata['da_e_mail'] = utf8_encode(utf8_decode($fila['da_e_mail']));
	$jsondata['de_pregunta'] = utf8_encode(utf8_decode($fila['de_pregunta']));
	$jsondata['de_respuesta'] = utf8_encode(utf8_decode($fila['de_respuesta']));
	$jsondata['de_direccion'] = utf8_encode(utf8_decode($fila['de_direccion']));
	$jsondata['da_telefonos'] = utf8_encode(utf8_decode($fila['da_telefonos']));
	$jsondata['id_user'] = $fila['id_user'];
	$jsondata['correo_estatus_actualiza'] = $fila['correo_estatus_actualiza'];
  
    
    echo json_encode($jsondata);	
}

///////////////////////////////////////////////////////////
function CambiaContrasena(){
global $db,$db1,$ce_trabajador;
extract($_POST);

$constrasenaaux=md5($contrasenaactual);
$nuevaconstrasenaaux=md5($nuevacontrasena);

$sql = "SELECT 
  id_user
  FROM 
  mst_registro_usuario
  where da_password='$constrasenaaux' and id_user=$idusuariocambiocontra";
    
    $ds = new DataStore($db1, $sql);
    
    $fila = $ds->getValues(0);

	if ($ds->getNumRows() > 0) {
			
 $sql2 = "UPDATE mst_registro_usuario 
  SET da_password = '$nuevaconstrasenaaux'
  where
 id_user=$idusuariocambiocontra";
    
   $ds2 = new DataStore($db, $sql2);

	if ($ds2->getAffectedRows() > 0) {
		
		echo "1";
		
		}else{
			
		echo "2";
		
		}

		}else{
    
   echo "0";
  
	}
}
	
	
	
///////////////////////////////////////////////////////////
function VerificarCorreoFormulario(){
global $db,$db1,$ce_trabajador;
extract($_POST);

$sql = "UPDATE 
  mst_registro_usuario  
SET 
  correo_estatus_actualiza = 0,
  correo_fecha_actualiza = 'NOW()',
  da_e_mail_2 = '$inputverificarcorreo'
  where co_cedula=".$ce_trabajador."";
    
   $ds = new DataStore($db, $sql);

	if ($ds->getAffectedRows() > 0) {
        
		 
		 $id = md5 ($idusuarioverificacorreo); 
		 
	
			 echo "1";
			 
		$link="https://www.servicios.luz.edu.ve/RRHH/VerificarCorreoBT.php?id=$id";

			$mensaje= '<div align="center"><h4>Validación de correo</h4></div> 
					<div align="justify">	
					<br/>
Por favor presione el link siguiente para culminar la verificación de su correo.  Si no funciona copie y péguelo en la barra de un explorador de internet como mozilla o chrome.:<br/><br/>

'.$link.'

</div>	
';
					
	$notalegal='<div><p style="font-size:10px"><b>Si este correo no era para usted por favor ignórelo.</b><br></div>';
		

	$mail  = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = '465';                                                                   // Puerto a utilizar
	$mail->Username   = 'soporte.tecnico@rrhh.luz.edu.ve';
	$mail->Password   = 'sitrrhh20101';
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->CharSet    = "UTF-8";
	$mail->From       = "soporte.tecnico@rrhh.luz.edu.ve";
	$mail->FromName   = 'Soporte Web RRHH';
	$mail->IsHTML(true); 
  $mail->ContentType = 'text/html';

  	
 $mail->AddBCC($inputverificarcorreo); 


  $mail->Subject = "Validación de correo ".date('d-m-y h:i:s A');
  $mail->Body = $mensaje.'<br><br>'.$notalegal;
  $mail->AltBody = "";
  $mail->ConfirmReadingTo="soporte.tecnico@rrhh.luz.edu.ve";
  $exito = $mail->Send();
  $intentos=1; 
  $aux=0; 
  
  while ((!$exito)) {
	sleep(5);
	
	  if($intentos == 3){
		  $aux=1;
		  break;
   }
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }
   
   
		 
		 
		 
		 
		 
		 
		 
        } else {
         echo "0";
        }

}
	
	
	

?>