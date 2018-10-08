<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');

require 'includes/class.phpmailer.php';

$db="rrhh";
$ce_trabajador=$_SESSION["cedula"];

$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$tipo_personal=$_SESSION['tipopersonal'];

$anovariacion=$_SESSION['ano_proceso'];
$mesvariacion=$_SESSION['mes_proceso'];
$op= $_REQUEST['op'];
$ce_trabajador=$_SESSION["cedula"];
$nombre_trabajador=$_SESSION["nombretrabajador"];
$co_ubicacion=$_SESSION['coubicacion'];
$correo = $_SESSION['email'];

$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
			case 'InsertReclacoCCU':
	        InsertReclacoCCU();
			break;
			case 'VerificaYaRealizoReclamo':
	        VerificaYaRealizoReclamo();
			break;
           case 'DatosRegistroFormulario':
	        DatosRegistroFormulario();
			break;
			 }


///////////////////////////////////////////////////////////
function InsertReclacoCCU(){
global $db,$ce_trabajador,$tipo_personal,$correo,$tipo_personal,$co_ubicacion,$nombre_trabajador;
extract($_POST);

$sql = "INSERT INTO public.mst_reclamos_ccu(cedula,reclamo,fecha,correo,tipo_personal,ubicacion,asunto) 
VALUES ($ce_trabajador,'$TextareaCampoReclamo',CURRENT_DATE,'$correo',$tipo_personal,$co_ubicacion,'$asuntoReclamoRRHH')";

$ds = new DataStore($db, $sql);
    
	if ($ds->getAffectedRows() == 0) 
	{

 	echo "0";

 	}else{
		
    echo "1";



			$mensaje= '<div align="center"><h4>Módulo de atención al usuario</h4></div> 
					<div align="justify">	
					<br/>
Recibimos su solicitud con éxito. Luego de estudiar su caso, nos comunicaremos con usted

</div>	
';
					
	$notalegal='<div><p style="font-size:10px"><b>Si este correo no era para usted por favor ignórelo.</b><br></div>';
		

	$mail  = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = '465';                                                                
	$mail->Username   = 'soporte.tecnico@rrhh.luz.edu.ve';
	$mail->Password   = 'sitrrhh20101';
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->CharSet    = "UTF-8";
	$mail->From       = "soporte.tecnico@rrhh.luz.edu.ve";
	$mail->FromName   = 'Soporte Web RRHH';
	$mail->IsHTML(true); 
  $mail->ContentType = 'text/html';

  	
 $mail->AddBCC($correo); 

 

  $mail->Subject = "Módulo de atención al usuario ".date('d-m-y h:i:s A');
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
   



///////////////////////////////////////////////////////////////////////////////////////////////////////////

$tipoPersonal=substr($tipo_personal,0,1);


if($tipoPersonal=="1"){
$tipoPersonalTexto= "DOCENTE";
}


if($tipoPersonal=="2"){
$tipoPersonalTexto= "ADMINISTRATIVO";
}

if($tipoPersonal=="3"){
$tipoPersonalTexto= "OBRERO";
}

   $mensaje= '<div align="center"><h4>Módulo de atención al usuario '.$tipoPersonalTexto.'</h4></div> 
					<div align="justify">	
					<br/>
Datos del Trabajador:<br/><br/>

Nombre: '.$nombre_trabajador.'<br/>
Cedula: '.$ce_trabajador.'<br/>
Correo: '.$correo.'<br/>
Tipo Personal: '.$tipo_personal.'<br/>
Ubicacion: '.$co_ubicacion.'<br/>
Asunto: '.$asuntoReclamoRRHH.'<br/>
Descripcion Reclamo: '.$TextareaCampoReclamo.'<br/>
Fecha: '.date('d-m-y h:i:s A').'<br/>

</div>	
';
				
	$notalegal='<div><p style="font-size:10px"><b>Si este correo no era para usted por favor ignórelo.</b><br></div>';
		

	$mail  = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = '465';                                                                
	$mail->Username   = 'soporte.tecnico@rrhh.luz.edu.ve';
	$mail->Password   = 'sitrrhh20101';
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->CharSet    = "UTF-8";
	$mail->From       = "soporte.tecnico@rrhh.luz.edu.ve";
	$mail->FromName   = 'Soporte Web RRHH';
	$mail->IsHTML(true); 
  $mail->ContentType = 'text/html';

  	
/* $mail->AddBCC("rarojas@rrhh.luz.edu.ve"); 
$mail->AddBCC("nomina@rrhh.luz.edu.ve"); */

/*$mail->AddBCC("jfernandez@rrhh.luz.edu.ve"); */
$mail->AddBCC("buzon@rrhh.luz.edu.ve"); 

  $mail->Subject = "Módulo de atención al usuario ".$tipoPersonalTexto." ".date('d-m-y h:i:s A');
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


   
   

////////////Correo a los de  nomina


/* $mensaje= '<div align="center"><h4>Inquietudes sobre pagos III CCU</h4></div> 
					<div align="justify">	
					<br/>

					Datos del Trabajador:<br/><br/>


Cedula: '.$ce_trabajador.'<br/>
Correo: '.$correo.'<br/>
Tipo Personal: '.$tipo_personal.'<br/>
Ubicacion: '.$co_ubicacion.'<br/>
Descripcion Reclamo: '.$TextareaCampoReclamo.'<br/>
Fecha: '.date('d-m-y h:i:s A').'<br/>
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

  	
 $mail->AddBCC("goodfriendtec@gmail.com"); 


  $mail->Subject = "Inquietudes III CCU ".$tipo_personal." ".date('d-m-y h:i:s A');
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
	
   }*/






}


	

}
	
	
	
function VerificaYaRealizoReclamo(){

global $db, $ce_trabajador;
extract($_POST);

$sql5 = "SELECT 
  *
 FROM 
 public.mst_reclamos_ccu where
 cedula=$ce_trabajador;";

 $ds5 = new DataStore($db, $sql5);

 

if ($ds5->getAffectedRows() == 0) 
 { 

echo 0; 

}else{
 
  
 echo 1;
 
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
	

?>