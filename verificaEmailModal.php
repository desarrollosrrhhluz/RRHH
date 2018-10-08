<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0);
session_start();    
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include('includes/Funciones.inc.php');
 require 'includes/class.phpmailer.php';

$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$cedula=$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	        case 'verificaOK':
	        $cedula=$_REQUEST['cedula'];
	        verificaOK($cedula);
			break;
			case 'enviarEmail':
			$cedula=$_REQUEST['cedula'];
			$email=$_REQUEST['email'];
	        enviarEmail($cedula, $email);
			break;	
			case 'verificar':
			$cedula=$_REQUEST['cedula'];
			$codigo=$_REQUEST['codigo'];
	        verificar($cedula, $codigo);
			break;
			case 'omitirEmail':
			$cedula=$_REQUEST['cedula'];
	        omitirEmail($cedula);
			break;
			 		 
			 }


//********************************************
function verificaOK($cedula){
global $cedula, $dbr, $db, $dbs;	
	 $sql = "SELECT * FROM public.tmp_validacion_email where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				
				$res=["estatus"=>0 ];			 
				echo json_encode($res) ;	
				  }else{

				  $row = $ds2->getValues(0);
				  $omitir= $row['codigo'] == 0 ? 1 : 0 ;

				  $res=["omitir"=>$omitir,"estatus"=>$row['estatus'] , "email"=>$row['email'] ];
				  echo json_encode($res);	

				   }     
	}

function enviarEmail($cedula, $email){
global  $dbr, $db, $dbs;
$codigo=generarCodigo(4);

 $sql = "SELECT * FROM  public.tmp_validacion_email where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				//insert 
				$sqlCodigo="INSERT INTO 
  public.tmp_validacion_email (ce_trabajador, codigo, email, estatus, created_at )
				VALUES ( $cedula, '$codigo', '$email', FALSE, now() );";
				  }else{
				//update
				$sqlCodigo="UPDATE 
  public.tmp_validacion_email
SET 
  codigo = '$codigo',
  email = '$email',
  estatus = FALSE,
  updated_at=now()
WHERE 
  ce_trabajador = $cedula;";
				   } 
/***************************************/

$ds3  = new DataStore($dbr);
$rs        = $ds3->executesql($sqlCodigo);


$mensaje= 'Su Codigo de validación de correo electrónico es el siguiente: <b><h3>'.$codigo.'</h3></b>';

$notalegal='<div aligm="justify">
<b>Nota Confidencial:</b> "La información contenida en esta comunicación es para el exclusivo uso y conocimiento de la persona natural o jurídica a la que ha sido remitida, y para aquellos otros autorizados para recibirla, pues puede contener información de carácter legal, confidencial y privilegiada. Si usted no es uno de los destinatarios, es nuestro deber notificarle que su revelación, copia o distribución a otras personas está totalmente prohibida y pudiera constituir violación expresa del ordenamiento jurídico vigente. Si ha recibido esta comunicación por error, agradecemos que lo notifique por esta misma vía a esta Dirección y la borre inmediatamente de su sistema. RRHH no se halla obligada, ni por la adecuada y completa transmisión de la información contenida en esta comunicación, ni es responsable por demoras o atrasos en su entrega."
</div>';


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


$mail->AddAddress(urldecode($email)); 

$mail->Subject = "Respuesta a su validación de correo ".date('d-m-y h:i:s A');
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




/**************************************/
		
			echo json_encode(1);
			

}
//***************************************//
function omitirEmail($cedula){
global  $dbr, $db, $dbs;
$codigo=generarCodigo(6);
$sqlCodigo="UPDATE 
  public.tmp_validacion_email
SET 
  codigo = '$codigo',
  updated_at= now()
WHERE 
  ce_trabajador = $cedula;";
$ds3  = new DataStore($dbr);
$rs        = $ds3->executesql($sqlCodigo);
echo json_encode(1);
} 

//***************************************//
function verificar($cedula, $codigo){
global $cedula, $dbr, $db, $dbs;
$sql = "SELECT * FROM public.tmp_validacion_email where ce_trabajador= $cedula and codigo='$codigo'";
$ds2    = new DataStore($dbr, $sql);
if($ds2->getNumRows()<=0){
				//insert 
	echo json_encode(0);
}else{
				//update
	$sqlCodigo="UPDATE 
	public.tmp_validacion_email 
	SET 
	estatus = TRUE,
	updated_at= now()
	WHERE 
	ce_trabajador = $cedula";

	$ds3  = new DataStore($dbr);
	$rs        = $ds3->executesql($sqlCodigo);
	echo json_encode(1);
} 


}

function generarCodigo($longitud) {
 $key = '';
 $pattern = '1234567890';
 $max = strlen($pattern)-1;
 for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
 return $key;
}

?>