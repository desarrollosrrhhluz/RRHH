<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');
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
			 case 'MostrarInstruccionces':
	      MostrarInstruccionces();
			 break;
			  case 'IniciarEvaluacionEtica':
		  IniciarEvaluacionEtica();
			 break;
			 case 'EticaUpdate':
		  EticaUpdate();
			 break;
			  case 'IniciarEvaluacionResponsabilidad':
		  IniciarEvaluacionResponsabilidad();
			 break;
			 case 'ResponsabilidadUpdate':
		  ResponsabilidadUpdate();
			 break;
			 case 'IniciarEvaluacionLiderazgo':
		 IniciarEvaluacionLiderazgo();
			 break;
			  case 'LiderazgoUpdate':
		 LiderazgoUpdate();
			 break;
			 case 'IniciarEvaluacionAutonomia':
		 IniciarEvaluacionAutonomia();
			 break;
			  case 'AutonomiaUpdate':
		 AutonomiaUpdate();
			 break;
			 case 'IniciarEvaluacionCalidadymejoracontinua':
		 IniciarEvaluacionCalidadymejoracontinua();
			 break;
			  case 'CalidadymejoracontinuaUpdate':
		 CalidadymejoracontinuaUpdate();
			 break;
			  case 'IniciarEvaluacionGestionLogrodeObjetivos':
		 IniciarEvaluacionGestionLogrodeObjetivos();
			 break;
			  case 'GestionLogrodeObjetivosUpdate':
		 GestionLogrodeObjetivosUpdate();
			 break;
			   case 'IniciarEvaluacioncomunicacionEficaz':
		 IniciarEvaluacioncomunicacionEficaz();
			 break;
			  case 'comunicacionEficazUpdate':
		 comunicacionEficazUpdate();
			 break;
			  case 'IniciarEvaluacionPensamientoEstrategico':
		 IniciarEvaluacionPensamientoEstrategico();
			 break;
			  case 'PensamientoEstrategicoUpdate':
		 PensamientoEstrategicoUpdate();
			 break;
			   case 'IniciarEvaluacionTomadeDecisiones':
		 IniciarEvaluacionTomadeDecisiones();
			 break;
			  case 'TomadeDecisionesUpdate':
		 TomadeDecisionesUpdate();
			 break;
			 case 'Camposfinales':
		 Camposfinales();
			 break;
			 case 'CulminaEvaluacion':
		 CulminaEvaluacion();
			 break;

			 }



function filtroBusquedaCedula3(){
global $dbs,$dbr, $db;
extract($_POST);
	
$sql =  "SELECT 
  a.id_estructura,
  a.id_lider_evaluacion,
  a.cedula,
  a.id_area_proceso,
  b.cedula as cedulalider
FROM 
  public.def_estructura_evaluacion as a
INNER JOIN mst_lider_evaluacion as b ON (a.id_lider_evaluacion= b.id)
where b.cedula=$cedula_trabajador;";

//$cedula_trabajador

$ds     = new DataStore($dbr, $sql);

if($ds->getNumRows()==0){
	
	echo "<div  align='center'>Usted no tiene personal a evaluar</div>";
	
}else{
	
    $_SESSION['ce_trabajadorjefe']=$cedula_trabajador;
	$rows=$ds->getNumRows();
    $i=0;
		  
 echo "<h3 align='center'><b>PERSONAL A EVALUAR</b></h3>";
 echo "
 <table width='100%' border='1' cellspacing='0' cellpadding='0'   class='table table-bordered' >
			  
				<tr class='gradeB'>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Cedula</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Nombres</th>
				<th BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>Cargo</th>
			
				<th BGCOLOR='#006699'></th>
			  </tr>
		
			   ";
			   
		  while($rows>$i){
			$fila = $ds->getValues($i);
			echo DatosUsuario($fila['cedula'],$fila['id_area_proceso']);
			  $i++;
			  }
			  echo "</table>";

}
}
	
	
	
	function DatosUsuario($cedula,$id_area_proceso){
		global $dbs,$dbr;
		
		$aux="";
		    $sql =  "select * from V_DATPER where CE_TRABAJADOR=$cedula";
			 $ds     = new DataStore($dbs, $sql);
			 
			 $fila = $ds->getValues(0);
			 
return "<tr class='gradeB'>
				<td class='td_resultado'>".$fila['CE_TRABAJADOR']."</td>
				<td class='td_resultado'>".utf8_encode($fila['NOMBRES'])."</td>
				<td class='td_resultado'>".utf8_encode($fila['DESCRIPCION'])."</td>
				
				
				<td class='td_resultado'><span><a onClick='MostrarInstrucciones($cedula,$id_area_proceso); return false'><img src='images/client_account_template.png' title='" . utf8_encode("Evaluar") . "' alt='" . utf8_encode("Evaluar") . "' /></span></td>
			  </tr>";
			
			 
			
	}
	
	
	function CargaEvaluacionAux($id_area_proceso){
	global $dbs,$dbr;
  
	$_SESSION['id_area_proceso']=$id_area_proceso;	  
			  
  $sql99 =  "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,c.descripcion,b.id_compete_descripcion,b.titulo
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  where  c.id_area_proceso=".$id_area_proceso."
  GROUP BY a.id_competencia,
  a.descripcion,c.descripcion,b.id_compete_descripcion,b.titulo ORDER by a.id_competencia ASC;";

$ds99 = new DataStore($dbr, $sql99);

 $rows = $ds99->getNumRows();
 $i    = 0;

   while ($i < $rows) {
	   
        $fila99 = $ds99->getValues($i);
		$_SESSION['id_compete_descripcion'][$i]=$fila99['id_compete_descripcion'];
            
            $i++;
        }

 $fila1 = $ds99->getValues(0);
 $fila2 = $ds99->getValues(5);

$_SESSION['id_competencia1']=$fila1['id_competencia'];
$_SESSION['id_competencia2']=$fila2['id_competencia'];

	}
	
	
///////////////////////////////////////////////////////////
function MostrarInstruccionces(){
global $dbr;
extract($_POST);

CargaEvaluacionAux($idprocesoevaluacion);

$_SESSION['ce_trabajadorevaluado']=$cedula;


echo "<div id='GrupoPregrado' ><fieldset><legend>INSTRUCCIONES</legend><div align='justify'>
En el siguiente instrumento, se le presenta una serie de proposiciones relacionadas con el desempeño de cada trabajador en sus funciones. Para evaluar, marque la opción que usted considere que se ajusta a su experiencia, considerando las ponderaciones:
</div>


<div align='center'  style='color:#000000;' ><h3>Ponderación de Ítems del instrumento</h3>
<table class='table table-bordered'  border='1' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >	  
<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'><strong>PONDERACIÓN EN AFIRMATIVO</strong></td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'><strong>ALTERNATIVA DE RESPUESTAS</strong></td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'><strong>PONDERACIÓN EN NEGATIVO</strong></td>
</tr>
<tr>
<td><strong>5</strong></td>
<td><strong>Siempre</strong></td>
<td><strong>1</strong></td>
</tr>
<tr>
<td><strong>4</strong></td>
<td><strong>Casi Siempre</strong></td>
<td><strong>2</strong></td>
</tr>
<tr>
<td><strong>3</strong></td>
<td><strong>Algunas veces</strong></td>
<td><strong>3</strong></td>
</tr>
<tr>
<td><strong>2</strong></td>
<td><strong>Rara vez</strong></td>
<td><strong>4</strong></td>
</tr>
<tr>
<td><strong>1</strong></td>
<td><strong>Nunca</strong></td>
<td><strong>5</strong></td>
</tr>
</table>
<input type='button' value='Iniciar Evaluación'  onClick='IniciarEvaluacion();'  class='btn btn-primary'/>
</div>
</fieldset>
";
}

/////////////////////////////////////////////////////

function DatosUsuarioCabecera(){
		global $dbs,$dbr;
		
		$aux="";
		    $sql =  "select *,'1' as orden from V_DATPER where CE_TRABAJADOR = ".$_SESSION['ce_trabajadorjefe']." UNION  select *,'2' as orden  from V_DATPER where CE_TRABAJADOR =".$_SESSION['ce_trabajadorevaluado']." order by orden desc";
			 $ds     = new DataStore($dbs, $sql);
			 
			 $fila = $ds->getValues(0);
			  $fila1 = $ds->getValues(1);

$_SESSION['NombreEvaluador']=utf8_encode($fila1['NOMBRES']);
$_SESSION['NombreEvaluado']=utf8_encode($fila['NOMBRES']);
	}

////////////////////////////////////////////////

// Etica

function IniciarEvaluacionEtica(){
global $dbr;
extract($_POST);

$AuxEstructuraEvaluacion=GeneraEctructuraEvaluacion();

if($AuxEstructuraEvaluacion == "1" or $AuxEstructuraEvaluacion == "2")
{
DatosUsuarioCabecera();


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia1']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][0]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);

echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>
<form  name='form_1_evalu' id='form_1_evalu'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;' >
<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' colspan='4' style='color:#ffFFFF; font-weight:bold'>PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = "SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=".$fila['id_competencia']."
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
		
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoEtica($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}


echo "</table>";
echo "<div align='center'><input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";



}else{
	
	echo "

<div align='left'>

<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>

<h1>Problemas al iniciar la evaluación,  Por favor  presione el botón IR AL INICIO y vuelva a intentar si el problema persiste diríjase al departamento de informática.</h1>
</div>

";

	
	
}



}

function quienFuecheckieadoEtica($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='etica' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='etica' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}

}

function GeneraEctructuraEvaluacion(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  id_desempeno,
  cedula,
  fecha,
  oportunidadmejorar,
  acciones,
  recomendaglobal,
  estatus
FROM 
  public.tra_desempeno_evaluacion where cedula=".$_SESSION['ce_trabajadorevaluado']."";

 $ds11     = new DataStore($dbr, $sql);
 
 if($ds11->getNumRows()==0){
	 
$sql1 = "SELECT 
  a.id_area_proceso,
  a.descripcion,c.id_compete_descripcion,d.id_comportamiento
FROM 
  tab_area_proceso_evaluacion as a
  INNER JOIN tab_competencia_evaluacion AS b ON (a.id_area_proceso= b.id_area_proceso)
  INNER JOIN def_compete_descrip_evaluacion  AS c ON (b.id_competencia= c.id_competencia)
  INNER JOIN tab_comportamiento_evaluacion AS d ON (c.id_compete_descripcion= d.id_competencia_descri)
  where a.id_area_proceso=".$_SESSION['id_area_proceso']."
  GROUP BY   a.id_area_proceso,
  a.descripcion,c.id_compete_descripcion,d.id_comportamiento
  order by id_comportamiento ASC ;";

 $ds     = new DataStore($dbr, $sql1);


if($ds->getNumRows()==0){
		
		
		 return "0";
		
		
		  }else{


$sql2 = "INSERT INTO tra_desempeno_evaluacion
(
  cedula,
  fecha,
  oportunidadmejorar,
  acciones,
  recomendaglobal,
  estatus,
  observacionevaluador
) 
VALUES (".$_SESSION['ce_trabajadorevaluado'].",current_date,'','','',0,'');";

$ds2     = new DataStore($dbr, $sql2);

 if ($ds2 > 0) {

	$sql3 = "SELECT MAX(id_desempeno) FROM tra_desempeno_evaluacion";
		
		$ds3     = new DataStore($dbr, $sql3);
		
		$fila3 = $ds3->getValues(0);

 $rows=$ds->getNumRows();
		  $i=0;

		  while($rows>$i){
			  
			$fila = $ds->getValues($i);
			
			$sql4 .= "INSERT INTO 
  tra_eval_resultados_evaluacion
(
  id_comportamiento,
  id_ponderacion,
  fecha,
  id_desempeno
) 
VALUES (
  ".$fila['id_comportamiento'].",
  null,
  current_date,
 ".$fila3['max']."
);";
           
			  $i++;
	}
	
	$consulta=	"BEGIN; 
	".$sql4."
	 COMMIT;";
	
	$ds4    = new DataStore($dbr);	
	
	$resultadoSQL= $ds4->executesql($consulta);
	
	 if ($resultadoSQL == 0) {
		 
	return "1";
	
	 }else{
		 
    return "0";
	
}
	 
	 
	
	
 }else{
	 
	return "0";
	
 }
			
  }
  
  
  
  
 }else{
	 
	return "2"; 
 }
 
 
 
 
 
}

function EticaUpdate()
{
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

///// Fin Etica

///// Responsabilidad

function IniciarEvaluacionResponsabilidad(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia1']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][1]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_Respon' id='form_1_Respon'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' >".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' colspan='4' style='color:#ffFFFF; font-weight:bold' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento'].".
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = "  SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=".$fila['id_competencia']."
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
		
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoResponsabilidad($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";

echo "<div align='center' class='row' ><a  onClick='IniciarEvaluacion()' class='btn btn-info' > Volver </a>&nbsp;&nbsp;&nbsp;<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";


}


function quienFuecheckieadoResponsabilidad($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='Responsabilidad' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='Responsabilidad' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		

}




function ResponsabilidadUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

//fin Responsabilidad

//IniciarEvaluacionLiderazgo

function IniciarEvaluacionLiderazgo(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia1']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][2]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_Liderazgo' id='form_1_Liderazgo'>

 <table width='100%' class='table table-bordered'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = "  SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=".$fila['id_competencia']."
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
		
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoLiderazgo($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionResponsabilidad()' class='btn btn-info'>Atras</a>&nbsp;&nbsp;&nbsp;
<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";


}


function quienFuecheckieadoLiderazgo($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='liderazgo' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='liderazgo' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
}

function LiderazgoUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}
//fin Liderazgo

//Autonomia

function IniciarEvaluacionAutonomia(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia1']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][3]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_Autonomia' id='form_1_Autonomia'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px; '  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = "  SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=".$fila['id_competencia']."
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
		
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoAutonomia($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionLiderazgo()' class='btn btn-info' >Atras</a>&nbsp;&nbsp;&nbsp;<input  type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";


}


function quienFuecheckieadoAutonomia($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='Autonomia' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='Autonomia' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		
}




function AutonomiaUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

//fin Autonomia

//Calidadymejoracontinua


function IniciarEvaluacionCalidadymejoracontinua(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia1']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][4]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_Calidadymejoracontinua' id='form_1_Calidadymejoracontinua'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = "  SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=".$fila['id_competencia']."
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
		
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoCalidadymejoracontinua($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionAutonomia()' class='btn btn-info' >Atras</a>&nbsp;&nbsp;&nbsp;<input  type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";

}


function quienFuecheckieadoCalidadymejoracontinua($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='Calidadymejoracontinua' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='Calidadymejoracontinua' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		

}




function CalidadymejoracontinuaUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

// fin Calidadymejoracontinua

//Gestion Logro de objetivos


function IniciarEvaluacionGestionLogrodeObjetivos(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia2']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][5]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_GestionLogrodeObjetivos' id='form_1_GestionLogrodeObjetivos'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;' >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = " SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=1
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
	
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoGestionLogrodeObjetivos($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionCalidadymejoracontinua()' class='btn btn-info' >Atras</a>&nbsp;&nbsp;&nbsp;<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";

}


function quienFuecheckieadoGestionLogrodeObjetivos($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='GestionLogrodeObjetivos' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='GestionLogrodeObjetivos' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		
}




function GestionLogrodeObjetivosUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

//fin GestionLogrodeObjetivos


//comunicacionEficaz


function IniciarEvaluacioncomunicacionEficaz(){
global $dbr;
extract($_POST);

$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia2']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][6]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";
  
  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_comunicacionEficaz' id='form_1_comunicacionEficaz'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px; type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary'  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = " SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=1
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
	
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadocomunicacionEficaz($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionGestionLogrodeObjetivos()' class='btn btn-info'>Atras</a>&nbsp;&nbsp;&nbsp;<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary' /></div>";
echo " </form> ";


}


function quienFuecheckieadocomunicacionEficaz($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='comunicacionEficaz' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='comunicacionEficaz' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		
}




function comunicacionEficazUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

//fin comunicacionEficaz


//comunicacionEficaz


function IniciarEvaluacionPensamientoEstrategico(){
global $dbr;
extract($_POST);


	

$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia2']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][7]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_PensamientoEstrategico' id='form_1_PensamientoEstrategico'>

 <table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >

<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = " SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=1
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
			//  echo $fila8['id_ponderacion'];
	
			 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
	
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoPensamientoEstrategico($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }
			
		
}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacioncomunicacionEficaz()' class='btn btn-info' >Atras</a>&nbsp;&nbsp;&nbsp;<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary'/></div>";
echo " </form> ";


}


function quienFuecheckieadoPensamientoEstrategico($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='PensamientoEstrategico' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";

		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='PensamientoEstrategico' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		
}




function PensamientoEstrategicoUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);


}
		echo "actualice";

}

//fin PensamientoEstrategico



//TomadeDecisiones

function IniciarEvaluacionTomadeDecisiones(){
global $dbr;
extract($_POST);


$sql = "SELECT 
  a.id_competencia,
  a.descripcion as descompetencia,
  b.titulo,
  b.descripcion as descomportamiento,b.id_compete_descripcion,b.titulo,c.id_area_proceso
  FROM 
  tab_competencia_evaluacion as a
  INNER JOIN def_compete_descrip_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
  INNER JOIN tab_area_proceso_evaluacion AS c ON (a.id_area_proceso= c.id_area_proceso)
  where a.id_competencia=".$_SESSION['id_competencia2']." and  c.id_area_proceso=".$_SESSION['id_area_proceso']."
  and b.id_compete_descripcion=".$_SESSION['id_compete_descripcion'][8]."
  GROUP BY   a.id_competencia,
  a.descripcion,
  b.titulo,
  b.descripcion,b.id_compete_descripcion,b.titulo,c.id_area_proceso ORDER by b.id_compete_descripcion ASC
  ";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);


echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left'>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>
<br/>

<form  name='form_1_TomadeDecisiones' id='form_1_TomadeDecisiones'>

<table class='table table-bordered' width='100%'  cellspacing='1' cellpadding='15'  style='font-size:13px;'  >
<tr>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>".$fila['descompetencia']."</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold'>COMPORTAMIENTOS</td>
<td BGCOLOR='#006699' style='color:#ffFFFF; font-weight:bold' colspan='4' >PONDERACION</td>
</tr>

<tr>
<td rowspan='4' width='25%'>
<p>".$fila['titulo'].".</p> 
".$fila['descomportamiento']."
</td>
";


$sql5 =  "SELECT 
  id_comportamiento,
  id_competencia_descri,
  descripcion
FROM 
  tab_comportamiento_evaluacion where id_competencia_descri=".$fila['id_compete_descripcion']." order by id_comportamiento";

$ds5     = new DataStore($dbr, $sql5);


//cargo los comportamientos
if($ds5->getNumRows()==0){
}else{
	
	
	    $rows=$ds5->getNumRows();
		  $i=0;
		  
		  $sql6 = " SELECT 
  a.id_competencia,b.descripcion as descri1,c.descripcion as descripcion,c.valor,c.id_ponderacion
FROM 
 tab_competencia_evaluacion as a
  INNER JOIN tab_ponderacion_evaluacion AS b ON (a.id_competencia= b.id_competencia) 
    INNER JOIN def_ponderacion AS c ON (b.id_ponderacion_evaluacion= c.id_ponderacion_evaluacion) 
  where a.id_competencia=1
  GROUP BY  a.id_competencia,b.descripcion,c.descripcion,c.valor,c.id_ponderacion order by c.valor desc";


$ds6     = new DataStore($dbr, $sql6);


 $sql7 = " SELECT 
  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion
FROM 
 tra_eval_resultados_evaluacion as a
  INNER JOIN tab_comportamiento_evaluacion AS b ON (a.id_comportamiento= b.id_comportamiento) 
INNER JOIN tra_desempeno_evaluacion AS c ON (a.id_desempeno= c.id_desempeno)
   where b.id_competencia_descri=".$fila['id_compete_descripcion']." and c.cedula=".$_SESSION['ce_trabajadorevaluado']."
  GROUP BY  a.id_eval_resultado,
  a.id_comportamiento,
  a.id_ponderacion,
  a.fecha,
  a.id_desempeno,b.id_competencia_descri,b.descripcion order by a.id_comportamiento asc
 ;";


$ds7     = new DataStore($dbr, $sql7);
	$aux = $ds7->getValues(1);
echo "<input name='id_desempeno' type='hidden' id='id_desempeno' value='".$aux['id_desempeno']."' />";

		  while($rows>$i){
 		$fila8 = $ds7->getValues($i);
		$fila5 = $ds5->getValues($i);
              
	
		 $rows2=$ds6->getNumRows();
		  $j=0;
			
			
		echo"<td width='30%'>•".utf8_encode($fila5['descripcion']).".</td>
		<td COLSPAN=2><span>";
	
		 while($rows2>$j){
			 $fila6 = $ds6->getValues($j);

echo quienFuecheckieadoTomadeDecisiones($fila6['id_ponderacion'],$fila8['id_ponderacion'],$fila6['descripcion'],$fila6['id_ponderacion'],$fila5['id_comportamiento']);

 $j++;
}
echo"</span></td></tr>";

		
			  $i++;
			  }

}
echo "</table>";
echo "<div align='center'><a onClick='IniciarEvaluacionPensamientoEstrategico()' class='btn btn-info'>Atras</a>&nbsp;&nbsp;&nbsp;<input type='submit' title='Siguiente' value='Siguiente' alt='Siguiente' class='btn btn-primary'/></div>";
echo " </form> ";
}


function quienFuecheckieadoTomadeDecisiones($valor1,$valor2,$descripcion,$id_ponderacion,$id_comportamiento)
{
	if($valor1==$valor2)
		{
return "".$descripcion."<input  type='radio' class='TomadeDecisiones' checked value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
		if($valor1!=$valor2)
		{
return "".$descripcion."<input  type='radio'   class='TomadeDecisiones' value='".$id_ponderacion."' required='required' name='grupo_".$id_comportamiento."'>";
		}
}

function TomadeDecisionesUpdate(){
global $dbr;
extract($_POST);

$arv1 = $idponderacion; 
$arv2 = $idcomportamiento; 


// La funcion explode convertira la cadena a arreglo 
$tok1 = explode(',',$arv1);
// La funcion explode convertira la cadena a arreglo 
$tok2 = explode(',',$arv2);



foreach ($tok1 as  $index =>  $valor) {
 
$sql4="UPDATE 
  tra_eval_resultados_evaluacion  
SET 
  id_ponderacion = ".$valor.",
  fecha = current_date
WHERE id_comportamiento = ".$tok2[$index]." and id_desempeno=$id_desempeno
;";

$ds4     = new DataStore($dbr, $sql4);
}
		echo "actualice";
}

//fin TomadeDecisiones

// Camposfinales
function Camposfinales(){
global $dbr;
extract($_POST);



$sql = "SELECT 
  id_desempeno,
  cedula,
  fecha,
  oportunidadmejorar,
  acciones,
  recomendaglobal,
  estatus,
  observacionevaluador,
  proximaevaluacion
FROM 
  public.tra_desempeno_evaluacion as a where a.id_desempeno=$id_desempeno ;";

  $ds     = new DataStore($dbr, $sql);

$fila = $ds->getValues(0);

if($fila['estatus']==0)
{
echo "

<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<div align='left' >

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>

<form  method='post' name='form_opcionesfinales' id='form_opcionesfinales'>
<br/>
<strong>OBSERVACIONES DEL EVALUADOR</strong><br/> <textarea name='observacionevaluador' id='observacionevaluador' placeholder='' class='form-control' rows='3' class='form-control' required></textarea>
<br/><strong>OPORTUNIDADES DE MEJORA</strong><br/> <textarea name='oportunidadmejora' id='oportunidadmejora' placeholder='' class='form-control' rows='3' required></textarea>
<br/><strong>ACCIONES</strong><br/> <textarea name='accioneseval' id='accioneseval' placeholder='' class='form-control' rows='3' required></textarea>
<br/>
<strong>RECOMENDACIONES GLOBALES</strong><br/><select name='recomendacionGlobal' class='form-control' id='recomendacionGlobal' required autofocus >
<option value=''>Seleccione</option>
<option value='ADECUAR LAS FUNCIONES A UN CARGO SUPERIOR'>ADECUAR LAS FUNCIONES A UN CARGO SUPERIOR</option>
<option value='MANTENER EN EL CARGO EJECUTADO'>MANTENER EN EL CARGO EJECUTADO</option>
</select>
<br/>
<strong>PRÓXIMA EVALUACIÓN</strong>
<br/>
<select name='proximaevaluacionstatus' id='proximaevaluacionstatus' class='form-control' required autofocus >
<option value=''>Seleccione</option>
<option value='90 DIAS'>90 DÍAS</option>
<option value='180 DIAS'>180 DÍAS</option>
<option value='360 DIAS'>360 DIAS</option>
</select>
<input type='hidden' id='op'  value='CulminaEvaluacion' > 
<div align='center'>
<input type='submit' value='Aceptar' class='btn btn-default'/>
<div/>
<span><a onClick='IniciarEvaluacionTomadeDecisiones()'> <img src='images/Gnome-Go-Previous-32.png' title='Atras' alt='Atras' style='width : 50px; heigth : 50px'/></a></span>
<br/>
</form>
";
}else{
	
	
echo "<div align='left' >


<div align='right'>
<span><a onClick='CulminarEvaluacion()' > <img src='images/group_go.png' title='IR AL INICIO' alt='IR AL INICIO' /></a></span>
</div>

<img  src='images/forward_disabled.jpg'><b>EVALUADOR:</b> ".$_SESSION['NombreEvaluador']."
<br/>
<img  src='images/forward_disabled.jpg'><b>EVALUADO:</b> " .$_SESSION['NombreEvaluado']."

</div>

<form  method='post' name='form_opcionesfinales' id='form_opcionesfinales'>
<br/>
<strong>OBSERVACIONES DEL EVALUADOR</strong><br/> <textarea name='observacionevaluador' id='observacionevaluador' placeholder=''  class='form-control' rows='3' required>".$fila['observacionevaluador']."</textarea>
<br/><strong>OPORTUNIDADES DE MEJORA</strong><br/> <textarea name='oportunidadmejora' id='oportunidadmejora' placeholder='' class='form-control' rows='3' required>".$fila['oportunidadmejorar']."</textarea>
<br/><strong>ACCIONES</strong><br/> <textarea name='accioneseval' id='accioneseval' placeholder='' class='form-control' rows='3' required>".$fila['acciones']."</textarea>
<br/>
<strong>RECOMENDACIÓN GENERAL</strong><br/>
<select name='recomendacionGlobal' id='recomendacionGlobal'  class='form-control' >
<option value='".$fila['recomendaglobal']."'>".$fila['recomendaglobal']."</option>
<option value='ADECUAR LAS FUNCIONES A UN CARGO SUPERIOR'>ADECUAR LAS FUNCIONES A UN CARGO SUPERIOR</option>
<option value='MANTENER EN EL CARGO EJECUTADO'>MANTENER EN EL CARGO EJECUTADO</option>
</select>
<br/>
<strong>PRÓXIMA EVALUACIÓN</strong>
<br/>
<select name='proximaevaluacionstatus' id='proximaevaluacionstatus'  class='form-control'>
<option value='".$fila['proximaevaluacion']."'>".$fila['proximaevaluacion']."</option>
<option value='90 DIAS'>90 DÍAS</option>
<option value='180 DIAS'>180 DÍAS</option>
<option value='360 DIAS'>360 DIAS</option>
</select>
<input type='hidden' id='op'  value='CulminaEvaluacion' > 
<div align='center'>
<a onClick='IniciarEvaluacionTomadeDecisiones()' class='btn btn-info' >Atras</a>
<input type='submit' value='Aceptar' class='btn btn-primary'/>
<div/>
<br/>

<br/>
</form>
";

	
}
/*
<b>TAREAS QUE  REALIZA EL EVALUADO</b>
<br/>
<span><a onClick='TareasEvaluado()' > <img src='images/column_single.png' title='Consultar' alt='Consultar' /></a></span>
<br/>
*/

}

function CulminaEvaluacion(){
global $dbr;
extract($_POST);

$sql4="UPDATE 
 tra_desempeno_evaluacion  
SET 
  fecha = current_date,
  oportunidadmejorar = '$oportunidadmejora',
  acciones = '$accioneseval',
  recomendaglobal = '$recomendacionGlobal',
  estatus = 1,
  proximaevaluacion='$proximaevaluacionstatus',
  observacionevaluador = '$observacionevaluador'
   WHERE 
 cedula = ".$_SESSION['ce_trabajadorevaluado']."
;";

$ds4     = new DataStore($dbr, $sql4);
	
	if($ds4->getNumRows()>0){
		
		
		$sql ="SELECT 
e.id_compete_descripcion,e.titulo, sum(c.valor)
FROM 
  tra_eval_resultados_evaluacion as a
  INNER JOIN tra_desempeno_evaluacion AS b ON (a.id_desempeno= b.id_desempeno) 
  INNER JOIN def_ponderacion AS c ON (a.id_ponderacion= c.id_ponderacion) 
  INNER JOIN tab_comportamiento_evaluacion AS d ON (a.id_comportamiento= d.id_comportamiento)
  INNER JOIN def_compete_descrip_evaluacion AS e ON (d.id_competencia_descri= e.id_compete_descripcion)
  where b.cedula=".$_SESSION['ce_trabajadorevaluado']." and e.id_competencia=".$_SESSION['id_competencia1']."
  GROUP BY e.id_compete_descripcion,e.titulo
  order by e.id_compete_descripcion asc";
	
$ds = new DataStore($dbr, $sql);	

$sql2 ="SELECT 
e.id_compete_descripcion,e.titulo, sum(c.valor)
FROM 
  tra_eval_resultados_evaluacion as a
  INNER JOIN tra_desempeno_evaluacion AS b ON (a.id_desempeno= b.id_desempeno) 
  INNER JOIN def_ponderacion AS c ON (a.id_ponderacion= c.id_ponderacion) 
  INNER JOIN tab_comportamiento_evaluacion AS d ON (a.id_comportamiento= d.id_comportamiento)
  INNER JOIN def_compete_descrip_evaluacion AS e ON (d.id_competencia_descri= e.id_compete_descripcion)
  where b.cedula=".$_SESSION['ce_trabajadorevaluado']." and e.id_competencia=".$_SESSION['id_competencia2']."
  GROUP BY e.id_compete_descripcion,e.titulo
  order by e.id_compete_descripcion asc";
	
$ds2 = new DataStore($dbr, $sql2);	

$i=0;
$rows=$ds->getNumRows();

$arreglo1 = array();
$arreglo2 = array();
$arreglo3 = array();
$arreglo4 = array();

	while($rows>$i){
	
	$fila = $ds->getValues($i);
	$fila2 = $ds2->getValues($i);
	
    $arreglo1[$i]=$fila['titulo'];
	$arreglo2[$i]=($fila['sum']/4);
	$arreglo3[$i]=$fila2['titulo'];
	$arreglo4[$i]=(($fila2['sum']/4) ? ($fila2['sum']/4) : "");
  
$SumaCARDINALES=$SumaCARDINALES+$fila['sum'];
$SumaAREA=$SumaAREA+$fila2['sum'];

  $i++;
}

$SumaAREA=$SumaAREA+20;

$TotalGENERAL= number_format(($SumaCARDINALES+$SumaAREA)/2);

$cedula_tra=$_SESSION['ce_trabajadorevaluado'];

$ssql = "INSERT INTO 
  tra_desempeno_evaluacion_his
(
  cedula,
  fecha,
  oportunidadmejorar,
  acciones,
  recomendaglobal,
  estatus,
  observacionevaluador,
  proximaevaluacion,
  competenciac1,
  competenciatc1,
  competenciac2,
  competenciatc2,
  competenciac3,
  competenciatc3,
  competenciac4,
  competenciatc4,
  competenciaa1,
  competenciaatc1,
  competenciaa2,
  competenciaatc2,
  competenciaa3,
  competenciaatc3,
  competenciaa4,
  competenciaatc4,
  totalcompetenciac,
  totalcompetenciaa,
  totalglobal,
  competenciac5,
  competenciatc5
) 
VALUES (
  $cedula_tra,
  current_date,
  '$oportunidadmejora',
  '$accioneseval',
  '$recomendacionGlobal',
   1,
  '$observacionevaluador',
  '$proximaevaluacionstatus',
  '$arreglo1[0]',
  '$arreglo2[0]',
  '$arreglo1[1]',
  '$arreglo2[1]',
  '$arreglo1[2]',
  '$arreglo2[2]',
  '$arreglo1[3]',
  '$arreglo2[3]',
  '$arreglo3[0]',
  '$arreglo4[0]',
  '$arreglo3[1]',
  '$arreglo4[1]',
  '$arreglo3[2]',
  '$arreglo4[2]',
  '$arreglo3[3]',
  '$arreglo4[3]',
  '$SumaCARDINALES',
  '$SumaAREA',
  '$TotalGENERAL',
  '$arreglo1[4]',
  '$arreglo2[4]'
);";


$ds2 = new DataStore($dbr, $ssql);	
$rows=$ds2->getNumRows();

if($rows>0)
{
	
echo '1';  

}else{
	
echo '0';
 
} 


		}else{
			
		echo "0";
		
		}
}

?>