<?php

   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0);

	session_start();
	include_once("../FrameWork/Include/Defines.inc.php");     
	include_once('app_class/DataStore.class.php');

	require 'includes/class.phpmailer.php';

	$db="rrhh";
	$ce_trabajador=$_SESSION["cedula"];
	$correo = $_SESSION['email'];
	$nombre_trabajador=$_SESSION["nombretrabajador"];
	$op= $_REQUEST['op'];

	//***************************************** 
    switch($op) { 	 
    	case 'update_bus':
    	InsertDecisionBus();
    	break;
	}

	///////////////////////////////////////////////////////////
	function InsertDecisionBus(){

		global $db, $ce_trabajador, $correo, $nombre_trabajador;
		extract($_POST);

		/*se verifica si ya registro la decision */
		$sql = "SELECT * FROM public.tab_censo_bus WHERE ce_trabajador=$ce_trabajador;";
	    $ds  = new DataStore($db, $sql);

		$i = $ds->getNumRows();

		/* Se actualiza la decision */
		if($i > 0){

			$fechaRegistro = date('Y-m-d H:i:s');
			$sql = "UPDATE public.tab_censo_bus SET decision = '$decisionUsr', updated_at='$fechaRegistro' WHERE ce_trabajador=$ce_trabajador;";
			$ds = new DataStore($db, $sql);
		    
			if ($ds->getNumRows() == 0) 
			{
		 		echo "0";
		 	}else{

		    	if($decisionUsr == 'S'){
		    		$decisionMsj = "Si";
		    	}else{
		    		$decisionMsj = "No";
		    	}

		    	/** Se envia el correo con la copia de la decision al trabajador */
				$mensaje= '
					<div align="center"><h4>Censo de La Bolsa Universitaria (BUS)</h4></div> 
						<div align="justify">	
						<br/>
						    Sírvase la presente para notificar que en el Censo de la Bolsa Universitaria Solidaria (BUS), el (la) trabajador(a) '.$nombre_trabajador.', C.I: '.$ce_trabajador.', ha tomado la decisión de que: <br> <b>"'.$decisionMsj.'"</b> Acepto que, LUZ me descuente del Bono de Alimentación la cantidad de Bs. 300.000,00 Bsf para el pago de la BUS.
						</div>';	
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
		    	/** Fin del envio del correo */

		    	echo "1";

			}

		/* Se registra la decision */
		}else{

			$fechaRegistro = date('Y-m-d H:i:s');
			$sql = "INSERT INTO public.tab_censo_bus(ce_trabajador, decision, created_at, updated_at) VALUES ($ce_trabajador, '$decisionUsr', '$fechaRegistro', '$fechaRegistro');";
			$ds = new DataStore($db, $sql);
		    
			if($ds->getNumRows() == 0){
		 		echo "0";
		 	}else{

		    	/** Se envia el correo con la copia de la decision al trabajador */
				$mensaje= '
					<div align="center"><h4>Censo de La Bolsa Universitaria (BUS)</h4></div> 
						<div align="justify">	
						<br/>
						    Sírvase la presente para notificar que en el Censo de la Bolsa Universitaria Solidaria (BUS), el (la) trabajador(a) '.$nombre_trabajador.', C.I: '.$ce_trabajador.', ha tomado la decisión de que: <br> <b>"'.$decisionMsj.'"</b> Acepto que, LUZ me descuente del Bono de Alimentación la cantidad de Bs. 300.000,00 Bsf para el pago de la BUS.
						</div>';	
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
		    	/** Fin del envio del correo */

		    	echo "1";
			}
		}
	}
?>