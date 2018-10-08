<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="rrhh";
$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$tipop=$_SESSION['tipopersonal'];
$anovariacion=$_SESSION['ano_proceso'];
$mesvariacion=$_SESSION['mes_proceso'];
$op= $_REQUEST['op'];
$ce_trabajador=$_SESSION["cedula"];
$nombre_trabajador=$_SESSION["nombres"];
$co_ubicacion=$_SESSION['coubicacion'];

//***************************************** 
     switch($op) { 
//***********************************	 
	     case 'GuardarCalculoARI':
	         GuardarCalculoARI();
			 break;	

       case 'tieneARI';
       tieneARI();
       break;

			 }

//****************************************//
function GuardarCalculoARI(){
global $db, $cedula, $anno,$ce_trabajador,$nombre_trabajador,$co_ubicacion,$anovariacion,$mesvariacion,$tipop;
extract($_POST);


 $sql = "SELECT 
  id,
  ce_trabajador,
  nombre,
  co_ubicacion,
  fecha_proceso,
  mes_variacion,
  remun_estim_ut,
  impuest_ano_grava,
  rebajas,
  impuesto_retener,
  monto_menusual_retener,
  estatus,
  ano_variacion,
  tipo_personal
FROM 
  mst_calculo_ari where ce_trabajador=$cedula";

$ds = new DataStore($db, $sql);
$fila = $ds->getValues(0);


if ($ds->getNumRows() > 0) 
{



if($fila['estatus'] == 0)
{


	$nombreaux= utf8_encode($nombre_trabajador);


 $sql = "UPDATE 
  public.mst_calculo_ari  
  SET 
  ce_trabajador = $cedula,
  nombre = '$nombreaux',
  co_ubicacion = $co_ubicacion,
  fecha_proceso = CURRENT_DATE,
  mes_variacion = '$mesvariacion',
  remun_estim_ut = $totalRemuneraciontribuUT,
  impuest_ano_grava = $totalimpuestogravable,
  rebajas = $totalrebajas,
  impuesto_retener = $ImpuestoEstimadoaRetener,
  monto_menusual_retener = $totalmensualretenerinput,
  estatus = 0,
  ano_variacion = '$anovariacion',
  tipo_personal = $tipop,
  carga_familiar =$cargafamiliarimput,
  impuestos_retenidos = $impuestoretenidosimput,
  desgravamen = $desgravamen,
  valor_ut = $valorUT,
  nombre_empresa = '$empresa',
  monto_empresa = '$montos'
WHERE 
  id = ".$fila['id']."
;";


$ds = new DataStore($db, $sql);

	if ($ds->getAffectedRows() > 0) 
	{
		
echo "1";

	}else{
		
echo "0";

	}


}else{// si ya esta verificado
	


//verifico si los meses y año de variacion sean diferentes para permitirle hacer una nueva
if($fila['ano_variacion'] != $anovariacion || $fila['mes_variacion'] != $mesvariacion )
{	
	
	$nombreaux= utf8_encode($nombre_trabajador);
	
 $sql = "UPDATE 
  public.mst_calculo_ari  
  SET 
  ce_trabajador = $cedula,
 nombre = '$nombreaux',
  co_ubicacion = $co_ubicacion,
  fecha_proceso = CURRENT_DATE,
  mes_variacion = '$mesvariacion',
  remun_estim_ut = $totalRemuneraciontribuUT,
  impuest_ano_grava = $totalimpuestogravable,
  rebajas = $totalrebajas,
  impuesto_retener = $ImpuestoEstimadoaRetener,
  monto_menusual_retener = $totalmensualretenerinput,
  estatus = 0,
  ano_variacion = '$anovariacion',
  tipo_personal = $tipop,
  carga_familiar =$cargafamiliarimput,
  impuestos_retenidos = $impuestoretenidosimput,
  desgravamen = $desgravamen,
  nombre_empresa = '$empresa',
   valor_ut = $valorUT,
  monto_empresa = '$montos'
WHERE 
  id = ".$fila['id']."
";


$ds = new DataStore($db, $sql);

	if ($ds->getAffectedRows() > 0) 
	{
		
echo "1";

	}else{
		
echo "0";

	}




}else{// si son iguales los meses y año de variacion y el estatus es en verificado muestro un mensaje que no puede hacer otra
	
echo "2";	
		
}




}


	}else{
		
$nombreaux= utf8_encode($nombre_trabajador);

$sql = "
INSERT INTO public.mst_calculo_ari
(
  ce_trabajador,
  nombre,
  co_ubicacion,
  fecha_proceso,
  mes_variacion,
  remun_estim_ut,
  impuest_ano_grava,
  rebajas,
  impuesto_retener,
  monto_menusual_retener,
  estatus,
  ano_variacion,
  tipo_personal,
  carga_familiar,
  impuestos_retenidos,
  desgravamen,
  nombre_empresa,
  monto_empresa,
  valor_ut
) 
VALUES (

  $cedula,
  '$nombreaux',
  $co_ubicacion,
  CURRENT_DATE,
  '$mesvariacion',
  $totalRemuneraciontribuUT,
  $totalimpuestogravable,
  $totalrebajas,
  $ImpuestoEstimadoaRetener,
  $totalmensualretenerinput,
  0,
  '$anovariacion',
  $tipop,
  $cargafamiliarimput,
  $impuestoretenidosimput,
  $desgravamen,
  '$empresa',
  '$montos',
  $valorUT
)";


$ds = new DataStore($db, $sql);

	if ($ds->getAffectedRows() > 0) 
	{
		
echo "1";

	}else{
		
echo "0";

	}
	
	
	

}	
	
	
	
	
	
	
	

}


function tieneARI(){
global $db, $cedula, $anno,$ce_trabajador,$nombre_trabajador,$co_ubicacion,$anovariacion,$mesvariacion,$tipop;

$sql5 = "SELECT 
  *
 FROM 
 public.mst_calculo_ari where
 ce_trabajador=$cedula;";

 $ds5 = new DataStore($db, $sql5);

 $fila5 = $ds5->getValues(0);

//$fila5['fecha_proceso']

 if ($ds5->getNumRows() > 0) 
 {
   
   
echo '<div class="alert alert-info" role="alert"><p style=" font-size:12px;">A realizado el cálculo de Estimación De Ingresos Anuales exitosamente para imprimir la constancia de nuevo presione la imagen a continuación <a href="https://www.servicios.luz.edu.ve/RRHH/CalculoARIPDF.php?totalRemuneraciontribuUT='.$fila5['remun_estim_ut'].' &totalimpuestogravable='.$fila5['impuest_ano_grava'].'&totalrebajas='.$fila5['rebajas'].'&ImpuestoEstimadoaRetener='.$fila5['impuesto_retener'].'&totalmensualretenerinput='.$fila5['monto_menusual_retener'].'&cargafamiliarimput='.$fila5['carga_familiar'].'&impuestoretenidosimput='.$fila5['impuestos_retenidos'].'&desgravamen='.$fila5['desgravamen'].'&empresa='.$fila5['nombre_empresa'].'&montos='.$fila5['monto_empresa'].'&valorUT='.$fila5['valor_ut'].'&ano='.$fila5['ano_variacion'].'&mes='.$fila5['mes_variacion'].'&fecha='.$fila5['fecha_proceso'].'     "  ><img src="images/page_white_acrobat.png" title="Imprimir Constancia" alt="Imprimir Constancia" /></a></p></div>'; 
  
}else{
  echo "";
}





}

?>