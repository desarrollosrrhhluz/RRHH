<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   session_start();    
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
 $db= "rrhh";


//echo"conectado con exito"; 
$ci =$_SESSION['cedula'];
$t= $_SESSION['co_tp'];
$op= $_REQUEST['op'];
 $j='8'; $p='9';
     switch($op) { 
			 case 'creaConstacia':
			 creaConstacia();
			 break;
			 
      }
//**********************************************
function creaConstacia(){
global $p, $j, $t, $ci,$db;
extract($_POST);
$fecha= date ("d-m-y");
if(!isset($tipo)){
	$tip=trim($_SESSION['co_tp']);
	$cou=trim($_SESSION['co_ub']);
	$tra=1;
	$sql = "insert into tra_constancia_trabajo (ce_trabajador, fe_solicitud, tipo_tramite, tipo_constancia, tipopersonal,  ubicacion) values ($ci, '$fecha', $tra, 'S', '$tip','$cou')";
	
	 $ds     = new DataStore($db, $sql);

	$last="SELECT currval('tra_constancia_trabajo_id_seq')"; 
	$ds1     = new DataStore($db, $last);
	 $rows=$ds1->getValues(0);
	$_SESSION['cod']=$rows['currval'];
   	
	$res[0]['codigo']=$_SESSION['cod'];
	$res[0]['tipoc']='S';
	$res[0]['tipop']=$tip;
	$res[0]['cou']=$cou;
	$res[0]['cedula']=$ci;
	$res[0]['destino']=$btndestino;
	$res[0]['destinatario']=$txtcdestinatario;
	echo json_encode($res);
	}else{
		if(count($tipo)==$cantidad){
			
	$tra=1;
	$sql = "insert into tra_constancia_trabajo (ce_trabajador, fe_solicitud, tipo_tramite,tipo_constancia, tipopersonal,  ubicacion) values ($ci, '$fecha', $tra, 'C', '','')";
	 $ds     = new DataStore($db, $sql);
	$last="SELECT currval('tra_constancia_trabajo_id_seq')"; 
	$ds1     = new DataStore($db, $last);
	 $rows=$ds1->getValues(0);
	$_SESSION['cod']=$rows['currval'];
   
   $res[0]['codigo']=$_SESSION['cod'];
	$res[0]['tipoc']='C';
	$res[0]['tipop']='';
	$res[0]['cou']='';
	$res[0]['cedula']=$ci;
   $res[0]['destino']=$btndestino;
	$res[0]['destinatario']=$txtcdestinatario;
	echo json_encode($res);	
			}else{
	$tra=1;
	$partes = explode("-", $tipo[0]);
	$cou=trim($partes[0]);
	$tip=trim($partes[1]);
	$sql = "insert into tra_constancia_trabajo (ce_trabajador, fe_solicitud, tipo_tramite,tipo_constancia, tipopersonal,  ubicacion) values ($ci, '$fecha', $tra, 'E', '$tip','$cou')";
	 $ds     = new DataStore($db, $sql);
	$last="SELECT currval('tra_constancia_trabajo_id_seq')"; 
	$ds1     = new DataStore($db, $last);
	 $rows=$ds1->getValues(0);
	$_SESSION['cod']=$rows['currval'];	
   
	$res[0]['codigo']=$_SESSION['cod'];
	$res[0]['tipoc']='E';
	$res[0]['tipop']=$tip;
	$res[0]['cou']=$cou;
	$res[0]['cedula']=$ci;	
	$res[0]['destino']=$btndestino;
	$res[0]['destinatario']=$txtcdestinatario;	
			echo json_encode($res);
				}
		}

}

//************************************************

//************************************************
?>
