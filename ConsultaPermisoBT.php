<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
//include('includes/Funciones.inc.php');
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";


$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	    
			 case 'filtroBusquedaCedula3':
	      filtroBusquedaCedula3();
			 break;
			 

			 }



function filtroBusquedaCedula3(){
global $dbr;
extract($_POST);


/*	
$sql =  "SELECT 
*
FROM 
  public.def_estructura_evaluacion as a
INNER JOIN mst_lider_evaluacion as b ON (a.id_lider_evaluacion= b.id)
where b.cedula=$cedula_trabajador;";*/

	
$sql =  "SELECT 
a.ce_funcionario,
a.id_permiso,
a.fe_inicio,
a.fe_fin,
a.estatus,
b.descripcion as tipopermiso,
c.estatus as estatuspermiso,
d.descripcion as permniso
FROM 
  nomina.tab_permisos as a
  INNER JOIN nomina.tab_definicion_permisos b ON (a.id_permiso= b.id)
    INNER JOIN nomina.tab_estatus_permisos c ON (a.estatus = c.id)
     INNER JOIN opciones.tab_opciones d ON (b.id_tipo = d.id)
where a.ce_trabajador=$cedula_trabajador AND d.id != 26;";

$ds     = new DataStore($dbr, $sql);

if($ds->getNumRows()==0){
	
	echo "<div  align='center'>No tienes permisos realizados </div>";
	
}else{
	
    $_SESSION['ce_trabajadorjefe']=$cedula_trabajador;
	
   
		  
 echo "<h3 align='center'><b>PERMISOS</b></h3>";
 echo "
 <table width='100%' border='1' cellspacing='0' cellpadding='0'   class='table table-bordered' >
			  
				<tr class='gradeB'>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Permiso</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Tipo Permiso</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Fecha Inicio</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Fecha Finalización</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Estatus</th>
			
			  </tr>
		
			   ";

			    $i=0;
			   
		  while ($i < $ds->getNumRows()) {
			$fila = $ds->getValues($i);
			//echo DatosUsuario($fila['cedula'],$fila['id_area_proceso']);

			echo "<tr class='gradeB'>
				<td class='td_resultado'>".$fila['permniso']."</td>
				<td class='td_resultado'>".$fila['tipopermiso']."</td>
				<td class='td_resultado'>".cambia_fecha_de_formato_mysql_a_formato_normal($fila['fe_inicio'])."</td>
				<td class='td_resultado'>".cambia_fecha_de_formato_mysql_a_formato_normal($fila['fe_fin'])."</td>
				<td class='td_resultado'>".$fila['estatuspermiso']."</td>
			    
			  </tr>";



			  $i++;
			  }
			  echo "</table>";

}


}
	
	
	
	
	
	
 function cambia_fecha_de_formato_mysql_a_formato_normal($fecha_formato_mysql){
 date_default_timezone_set('Europe/Madrid');
 $date = date_create($fecha_formato_mysql);
 return date_format($date, 'd/m/Y');
 }	

?>