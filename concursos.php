<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0);
session_start();    
//include_once('app_class/DataStore.class.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');
 require './includes/class.phpmailer.php';

$dbs="sidial";
$dbr="rrhh";
$db="rrhh";
$dbw="web";
$cedula=$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	        
			 case 'consultaInicialDatos':
	         consultaInicialDatos();
			 break;
			 case 'buscaConcursos':
	         buscaConcursos();
			 break;	
			 case 'crea_circular':
			 $id=$_REQUEST['id'];
	         crea_circular($id);
			 break;	
			 case 'guarda_postulacion':
	          guarda_postulacion();
			 break;	
			 case 'muestra_cita':
	          muestra_cita();
			 break;	
			 
			 case 'act_correo':
	          act_correo();
			 break;	
			 		 
			 }

//****************************************

//*******************************************
function consultaInicialDatos(){
global $db, $dbs, $ced ;
$cedula=$_SESSION['cedula'];

$sqlverfica="select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$cedula and ESTATUS='A'";
$dsx  = new DataStore($dbs, $sqlverfica);
if($dsx->getNumRows()<=0){

	 
		  }else{
		$row = $dsx->getValues(0);
		$_SESSION['nombre_usuario']=$row['NOMBRES'];
		$_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
		$_SESSION['co_ubicacion']=$row['CO_UBICACION'];	
		$_SESSION['de_ubicacion']=$row['DE_UBICACION'];	
		$_SESSION['gdo_salarial']=$row['GDO_SALARIAL'];	
	
		  }
		  
		  
}

//****************************************************
function buscaConcursos(){
global $db, $dbs, $dbr;

$cedula=$_SESSION['cedula'];
$tipopersonal=$_SESSION['tipopersonal'];
$co_ubicacion=$_SESSION['co_ubicacion'];
$gdo_salarial=$_SESSION['gdo_salarial'];
$co_corto=substr($co_ubicacion,0,2);

// if($cedula==17834399){
// $co_corto= 27;

// }

//
$sql = "SELECT *,da_descripcion as de_cargo, (mst_cargos.escala||''|| mst_cargos.nivel) as gdo_salarial FROM mst_concursos,mst_cargos,mst_cargos_tareas where co_dependencia=$co_corto and ubicacion_destino is NULL and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo  and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo and mst_concursos.estatus=3  and CURRENT_DATE>=fe_apertura and CURRENT_DATE<=fe_cierre
union 
SELECT *,da_descripcion as de_cargo, (mst_cargos.escala||''|| mst_cargos.nivel) as gdo_salarial FROM mst_concursos,mst_cargos,mst_cargos_tareas where tipo_concurso='E' and ubicacion_destino is NULL and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo and mst_concursos.estatus=3 and CURRENT_DATE>=fe_apertura and CURRENT_DATE<=fe_cierre 
union
SELECT *,da_descripcion as de_cargo, (mst_cargos.escala||''|| mst_cargos.nivel) as gdo_salarial FROM mst_concursos,mst_cargos,mst_cargos_tareas where ubicacion_destino=$co_corto and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo and mst_concursos.estatus=3 and CURRENT_DATE>=fe_apertura and CURRENT_DATE<=fe_cierre order by gdo_salarial
";
       

//  $sql =  "SELECT *,da_descripcion as de_cargo, (mst_cargos.escala||''|| mst_cargos.nivel) as gdo_salarial FROM mst_concursos,mst_cargos,mst_cargos_tareas where  mst_concursos.co_cargo=mst_cargos_tareas.co_cargo  and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo and mst_concursos.estatus=3  and fe_apertura >='20/07/2015'
// union 
// SELECT *,da_descripcion as de_cargo, (mst_cargos.escala||''|| mst_cargos.nivel) as gdo_salarial FROM mst_concursos,mst_cargos,mst_cargos_tareas where tipo_concurso='E' and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo and mst_concursos.estatus=3 and fe_apertura >='20/07/2015'";


        $ds     = new DataStore($dbr, $sql);
    	if($ds->getNumRows()<=0){
		  echo 'No hay concursos disponibles para '.$_SESSION['de_ubicacion'].'
		  <br> * No existen datos asociados<br>';

	
		  }else{
		  $inicio='';
		$i=0;
		$cadena="";
		//echo $ds->getNumRows();
	
		 while($i<$ds->getNumRows()){
		 $fila=$ds->getValues($i);
		 $fe_apertura=$fila['fe_apertura'];
		 $fe_cierre=$fila['fe_cierre'];

		$id_concurso= $fila['id_concurso'];
		if($fila['tipo_concurso']=="I"){$tipo='Interno'; $tipo_texto="";}else{ $tipo='Externo'; $tipo_texto="&nbsp;&nbsp<span class='label label-info externo' data-toggle='tooltip' data-placement='right' title='Concurso Externo' ><span class='glyphicon glyphicon-plane'></span></span>&nbsp;&nbsp;";}
		 
		$sql2 =  "SELECT  * FROM  public.tra_participacion_concurso where ce_participante=$cedula and  id_concurso=$id_concurso and fe_postulacion>='$fe_apertura' and fe_postulacion<='$fe_cierre'";
        $ds2     = new DataStore($dbr, $sql2);
    	if($ds2->getNumRows()<=0){
		
		if((int)$fila['gdo_salarial']>(int)$gdo_salarial){
		$opcion= '<input name="opcion" type="radio"  class="opcion " required="required" value="'.$fila['id_concurso'].'" id="'.$fila['id_concurso'].'" /> '.$fila['de_cargo'].' Escala '.$fila['escala'].' Nivel '.$fila['nivel'].' '.$tipo_texto.'<br>';
		}else{
		$opcion= '<input name="opcion" disabled="disabled" type="radio" required="required" class="opcion " value="'.$fila['id_concurso'].'" id="'.$fila['id_concurso'].'" /> '.$fila['de_cargo'].' Escala '.$fila['escala'].' Nivel '.$fila['nivel'].' <span class="label label-warning igual" data-toggle="tooltip" data-placement="right" title="Escala Nivel igual o inferior al actual"><span class="glyphicon glyphicon-warning-sign"></span></span>'.$tipo_texto.'<br>';
		}
		}else{
		
		$opcion= '<input name="opcion" disabled="disabled" type="radio" required="required"  class="opcion " value="'.$fila['id_concurso'].'" id="'.$fila['id_concurso'].'" /> '.$fila['de_cargo'].' Escala '.$fila['escala'].' Nivel '.$fila['nivel'].' <b>(Ya estas postulado)</b><br>';
		
		}
		
		 $cadena=$cadena.''.$opcion;
		 $i++;
		 }
		 
		  echo '<form action="concursos.php" name="form_postular" id="form_postular" method="post"><div align="left">'.$cadena.'</div><div align="center">
		 <br> <input name="op" type="hidden" value="guarda_postulacion"/><input name="btn_postular"  class="btn btn-success" type="submit" value="Postularme"/></div></form>
		 <br><br>
		 <div align="center" id="texto_cita" style="color:#F00"></div>
		 <br>'; 
		  
		  }

}

//***************************************************
function crea_circular($id){
global $db, $dbs, $dbr;
$cedula=$_SESSION['cedula'];
$tipopersonal=$_SESSION['tipopersonal'];
$co_ubicacion=$_SESSION['co_ubicacion'];
if(substr($tipopersonal,0,1)==2){$de_tipo="administrativo";}
if(substr($tipopersonal,0,1)==3){$de_tipo="obrero";}

$array = explode(",",$id);
$arraybien = array_values(array_diff($array, array('')));
$valor = implode(',', $arraybien); 
$nombres=$_SESSION['nombre_usuario'];
 $sql2 =  "SELECT mst_cargos_tareas.co_cargo, mst_cargos.da_descripcion, mst_cargos.escala, mst_cargos.nivel,def_situacion_concurso.descripcion as des_situacion,mst_concursos.situacion, mst_concursos.dejado, mst_concursos.ce_dejado, mst_concursos.tipo_concurso, mst_concursos.ubicacion_larga 
FROM public.mst_cargos_tareas, mst_concursos, mst_cargos, def_situacion_concurso 
where mst_concursos.id_concurso=$valor 
and mst_concursos.situacion=def_situacion_concurso.id
and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo
and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo";
        $ds2     = new DataStore($dbr, $sql2);
    	if($ds2->getNumRows()<=0){
		
		}else{
		$fila=$ds2->getValues(0);
		$cargo=$fila['co_cargo'];
		$de_cargo=$fila['da_descripcion'];
		$es_ni=$fila['escala'].'/'.$fila['nivel'];
		if($fila['tipo_concurso']=="I"){$tipo='Interno'; $tipo_texto="";}else{ $tipo='Externo'; $tipo_texto="<b>(Concurso Externo)</b>";}
		
		if($fila['situacion']=="CR" or $fila['tipo_concurso']=="E" ){

		$codigo=$fila['ubicacion_larga'];
		 $sqlU =  "select * from V_UBICACION where CO_UBICACION=$codigo";
        $ds3     = new DataStore($dbs, $sqlU);
    	if($ds3->getNumRows()<=0){
		
		}else{
		$result=$ds3->getValues(0);
		$resultado='<span class="text-primary"><b>'.$result['DESCRIPCION_CORTA'].'</b></span>';
		}

		}

		if($fila['situacion']=="CR"){

		$comienzo='Estimado(a), '.utf8_encode($nombres).' usted esta interesado(a) en la apertura del Concurso '.$tipo.' <b> '.$fila['da_descripcion'].', ESCALA '.$fila['escala'].', NIVEL '.$fila['nivel'].',</b> de credenciales para optar al cargo creado seg&uacute;n '.$fila['dejado'].' para ser ocupado en '.$resultado.'. Los requisitos exigidos para el cargo de acuerdo con el Manual Descriptivo de Cargos CNU-OPSU son:';
		}else{

		if($fila['tipo_concurso']=="I"){
		$comienzo='Estimado(a), '.utf8_encode($nombres).' usted esta interesado(a) en la apertura del Concurso '.$tipo.' <b> '.$fila['da_descripcion'].', ESCALA '.$fila['escala'].', NIVEL '.$fila['nivel'].',</b> de credenciales para optar al cargo vacante en esa dependencia por '.utf8_encode(str_replace("VACANTE POR","",$fila['des_situacion'])).' de '.$fila['dejado'].', C.I: '.$fila['ce_dejado'].'. Los requisitos exigidos para el cargo de acuerdo con el Manual Descriptivo de Cargos CNU-OPSU son:';

		}else{

		$comienzo='Estimado(a), '.utf8_encode($nombres).' usted esta interesado(a) en la apertura del Concurso '.$tipo.' <b> '.$fila['da_descripcion'].', ESCALA '.$fila['escala'].', NIVEL '.$fila['nivel'].',</b> de credenciales para optar al cargo vacante en esa dependencia por '.utf8_encode(str_replace("VACANTE POR","",$fila['des_situacion'])).' de '.$fila['dejado'].', C.I: '.$fila['ce_dejado'].' para ser ocupado en '.$resultado.'. Los requisitos exigidos para el cargo de acuerdo con el Manual Descriptivo de Cargos CNU-OPSU son:';

		}	
		}
			
		}

$sql3 =  "SELECT  * FROM  public.mst_cargos_perfiles where codigo like '$cargo';";
        $ds3     = new DataStore($dbr, $sql3);
    	if($ds3->getNumRows()<=0){
		
		}else{
		$row=$ds3->getValues(0);
		$parte2='<b>EDUCACION Y EXPERIENCIA:</b><br><b>A- </b>'.$row['perfil_a1'].'. '.$row['perfil_a2'].'<br><b>B- </b>'.$row['perfil_b1'].'. '.$row['perfil_b2'].'';
		
		}

$sql4 =  "SELECT * FROM  public.mst_cargos_competencias where codigo_cargo=$cargo ;";
        $ds4     = new DataStore($dbr, $sql4);
    	if($ds4->getNumRows()<=0){
		}else{
		$tipo1='';
		$tipo2='';
		$tipo3='';
		$j=0;
		while($j<$ds4->getNumRows()){
		$reg=$ds4->getValues($j);
		
		if($reg['tipo']==1){
		$tipo1=$tipo1.''.$reg['comp_esp'].'<br>';
		}
		if($reg['tipo']==2){
		$tipo2=$tipo2.''.$reg['comp_esp'].'<br>';
		}
		if($reg['tipo']==3){
		$tipo3=$tipo3.''.$reg['comp_esp'].'<br>';
		}
		$j++;
		}
		}
    

echo '<legend>Condiciones de participaci&oacute;n</legend><div align="justify">'.$comienzo.'<br><br>'.$parte2.'<br><br><b>CONOCIMIENTOS, HABILIDADES Y DESTREZAS REQUERIDAS</b><br><b>CONOCIMIENTOS EN:</b><br>'.$tipo1.'<br><b>HABILIDADES PARA:</b><br>'.$tipo2.'<br><b>DESTREZAS EN:</b><br>'.$tipo3.'<br><br><p  style="text-decoration:underline"><b>DECLARACI&Oacute;N Y CONDICIONES</b></p><br>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <col width="20%" />
  <col width="80%" />
  <tbody>
    <tr>
      <th width="20%"> <p><b>Declaraci&oacute;n de veracidad</b></p></th>
      <td width="80%"><p align="justify">De acuerdo a lo pautado en el art&iacute;lo 23 de la Ley Org&aacute;nica de Simplificaci&oacute;n de Tr&aacute;mites Administrativos, donde se establece como norma la presunci&oacute;n de buena fe del Ciudadano, la Direcci&oacute;n de Recursos Humanos de la Universidad del Zulia, tomar&aacute; como cierta la declaraci&oacute;n de las personas interesadas en postularse a diferentes cargos en esta instituci&oacute;n. Por lo cual, Yo, '.$_SESSION['nombre_usuario'].', portador(a) del documento de identidad: '.$cedula.', declaro: Que los datos suministrados en esta postulaci&oacute;n electr&oacute;nica, son veraces, por lo que autorizo a la Direcci&oacute;n de Recursos Humanos de la Universidad del Zulia, para que proceda a verificarlos ante las personas u  organismos emisores.</p><br></td>
    </tr>
    <tr>
      <th width="20%"> <p><b>Condiciones de tr&aacute;mite</b></p></th>
      <td width="80%"><hr><br><p align="justify">Por medio de la presente se hace constar que el (la) ciudadano(a): '.utf8_encode($_SESSION['nombre_usuario']).', portador(a) del documento de identidad: '.$cedula.', ha tramitado electr&oacute;nicamente en el portal de esta Direcci&oacute;n de Recursos Humanos, su postulaci&oacute;n al cargo de: '.$de_cargo.', ('.$es_ni.'), la cual ser&aacute; objeto de an&aacute;lisis por parte de la Comisi&oacute;n de Revisi&oacute;n y Evaluaci&oacute;n de Credenciales de LUZ (CRECALUZ), espec&iacute;ficamente en el cargo sometido a concurso, basado en el perfil definido en el  Manual Descriptivo de Cargos CNU-OPSU vigente.
Queda entendido que el interesado es el &uacute;nico responsable de la actualizaci&oacute;n de credenciales en el  expediente personal que reposa en el CEDIA.</p></td>
    </tr>
  </tbody>
</table></div><br><input name="btn_confirmacion" id="btn_confirmacion" class="btn btn-success" type="button" value="Acepto" /> <input id="btn_cancelar" class="btn btn-default" name="btn_cancelar" type="reset" value="Cancelar" />';

}
//**********************************************************
function  guarda_postulacion()
{
global $db, $dbs, $dbr;
$cedula=$_SESSION['cedula'];
$tipopersonal=$_SESSION['tipopersonal'];
$co_ubicacion=$_SESSION['co_ubicacion'];
$fecharegistro=date("d/m/Y");
extract($_POST);

$insertDatos="INSERT INTO  public.tra_participacion_concurso
( ce_participante, id_concurso, estatus, resultado, puntaje, correo,  fe_postulacion, anno) 
VALUES (  $cedula, $opcion, 'I',  NULL,  NULL,  NULL,  '$fecharegistro', 2017)";
 
 $ds	       = new DataStore($dbr);
$rs        = $ds->executesql($insertDatos); 
//$var = $ds->getNumRows(); 
if($rs>0){
echo '{ "message": "Su Postulacion ha sido almacenada exitosamente " }'; 

// $dia=01;
// $mes=02;
// $anno2=2016;
// $cantidad=5;
// $proceso=5;
// crea_cita($cedula,$cantidad,$dia,$mes,$anno2, $proceso, $opcion);

}else{
echo '{ "message": "Ha ocurrido un error! verifique y vuelva a intentarlo  " }'; 
}


}
//****************************
function envia_confirmacion($opcion){
global $db, $dbs, $dbr;
$destino=$_SESSION['email'];
$valor=$opcion;
$cedula=$_SESSION['cedula'];
$nombres=$_SESSION['nombre_usuario'];
$sql2 =  "SELECT mst_cargos_tareas.co_cargo, mst_cargos.da_descripcion, mst_cargos.escala, mst_cargos.nivel,def_situacion_concurso.descripcion as des_situacion,mst_concursos.situacion, mst_concursos.dejado, mst_concursos.ce_dejado, mst_concursos.tipo_concurso, mst_concursos.ubicacion_larga 
FROM public.mst_cargos_tareas, mst_concursos, mst_cargos, def_situacion_concurso 
where mst_concursos.id_concurso=$valor 
and mst_concursos.situacion=def_situacion_concurso.id
and mst_cargos.co_cargo=mst_cargos_tareas.co_cargo
and mst_concursos.co_cargo=mst_cargos_tareas.co_cargo";
        $ds2     = new DataStore($dbr, $sql2);
    	if($ds2->getNumRows()<=0){
		
		}else{
		$fila=$ds2->getValues(0);
		$cargo=$fila['co_cargo'];
		$de_cargo=$fila['de_cargo'];
		if($fila['tipo_concurso']=="I"){$tipo='Interno';}else{ $tipo='Externo';}
		$comienzo='Estimado, '.$nombres.' usted se ha postulado para el Concurso '.$tipo.' <b> '.$fila['da_descripcion'].', ESCALA '.$fila['escala'].', NIVEL '.$fila['nivel'].',</b> de credenciales para optar al cargo vacante en esa dependencia por '.utf8_encode(str_replace("VACANTE POR","",$fila['des_situacion'])).' de '.$fila['dejado'].', C.I: '.$fila['ce_dejado'].'. Los requisitos exigidos para el cargo de acuerdo con el Manual Descriptivo de Cargos CNU-OPSU son:';
		
		}

$sql3 =  "SELECT  * FROM  public.mst_cargos_perfiles where codigo like '$cargo';";
        $ds3     = new DataStore($dbr, $sql3);
    	if($ds3->getNumRows()<=0){
		
		}else{
		$row=$ds3->getValues(0);
		$parte2='<b>EDUCACION Y EXPERIENCIA:</b><br><b>A-</b>'.$row['perfil_a1'].'. '.$row['perfil_a2'].'<br><b>B-</b>'.$row['perfil_b1'].'. '.$row['perfil_b2'].'';
		
		}

$sql4 =  "SELECT * FROM  public.mst_cargos_competencias where codigo_cargo=$cargo ;";
        $ds4     = new DataStore($dbr, $sql4);
    	if($ds4->getNumRows()<=0){
		}else{
		$tipo1='';
		$tipo2='';
		$tipo3='';
		$j=0;
		while($j<$ds4->getNumRows()){
		$reg=$ds4->getValues($j);
		
		if($reg['tipo']==1){
		$tipo1=$tipo1.''.$reg['comp_esp'].'<br>';
		}
		if($reg['tipo']==2){
		$tipo2=$tipo2.''.$reg['comp_esp'].'<br>';
		}
		if($reg['tipo']==3){
		$tipo3=$tipo3.''.$reg['comp_esp'].'<br>';
		}
		$j++;
		}
		}
    $nota="";
	 $sqlverfica="select * from public.tab_ordenamiento_proceso where ce_trabajador=$cedula and proceso=4  and date_part('year',fe_cita)= 2015";
$dsx  = new DataStore($db, $sqlverfica);
if($dsx->getNumRows()<=0){
	
}else{
$rows=$dsx->getValues(0);
$fecha=$rows['fe_cita'];
$turno=$rows['turno'];

$nota.='<div align="justify">Usted deber&aacute; consignar en el CEDIA todas las credenciales el dia '.$fecha.', en el turno  '.($turno==1? 'de la Ma&ntilde;ana de 8:30 am a 11:30 am':' de la Tarde de 2:30 am a 4:30 am').' , a la misma debe asistir de manera obligatoria (No habr&aacute; prorroga).<br>
Deber&aacute; consignar todas las credenciales relacionadas con el cargo a concurso.</div>';
}


		$mensaje= '<div align="justify">'.$comienzo.'<br><br>'.$parte2.'<br><br><b>CONOCIMIENTOS, HABILIDADES Y DESTREZAS REQUERIDAS</b><br><br><b>CONOCIMIENTOS EN:</b><br>'.$tipo1.'<br><b>HABILIDADES PARA:</b><br><br>'.$tipo2.'<br><br><b>DESTREZAS EN:</b><br>'.$tipo3.'<br><br><p><b>Nota:</b>'.$nota.' </p><br></div>';
	$notalegal='<b>***Aviso Legal:***</b><br>
Este mensaje es privado y confidencial, y está dirigido exclusivamente a su(s) destinatario(s). Si usted ha recibido este mensaje por error, debe abstenerse de distribuirlo, copiarlo o usarlo en cualquier sentido. Asimismo, le agradecemos comunicarlo al remitente y borrar el mensaje y cualquier documento adjunto.
Cualquier opinión contenida en este mensaje pertenece únicamente al autor remitente y no representa necesariamente la opinión de la Dirección de Recursos Humanos, a menos que ello se señale en forma expresa.
Eventualmente, los correos electrónicos pueden ser interceptados o alterados, llegar con demora o incompletos. Al respecto, la Dirección de Recursos Humanos no se hace responsable por los errores, defectos u omisiones que pudieran afectar al mensaje original, con motivo de su envío por correo electrónico.
<br>';
		

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
  $mail->AddAddress($destino); //
  $mail->Subject = "Su postulacion ha sido Procesada";
  $mail->Body = utf8_decode($mensaje).'<br><br>'.$notalegal;
  $mail->AltBody = "envio de correo automatico";
   $mail->ConfirmReadingTo="concursos@rrhh.luz.edu.ve";
  $exito = $mail->Send();
  $intentos=1; 
  while ((!$exito) && ($intentos < 2)) {
	sleep(2);
     	//echo $mail->ErrorInfo;
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }



}

////////////////////////////////////////////////////////////////////////////////
function crea_cita($cedula,$cantidad,$dia,$mes,$anno2,$proceso,$opcion){
global $db, $dbs, $ced ;
// $nolaborales=array('24/06/2015','05/07/2015');
// $cedula=$_SESSION['cedula'];
// $j=0;
// $sqlverfica="select * from tab_ordenamiento_proceso where ce_trabajador=$cedula and proceso=$proceso  and fe_cita>='01/02/2016'";
// $dsx  = new DataStore($db, $sqlverfica);

// if($dsx->getNumRows()<=0){
	
// while($j!=1){
// $dial=date("l", mktime(0, 0, 0, $mes, $dia, $anno2));	
// 	if($dial=="Saturday" or $dial=="Sunday" or $dial=="Wednesday"){
		
// 		}else{
// 		$ds	       = new DataStore($db);

// 			 $dian=date("d/m/Y", mktime(0, 0, 0,$mes,$dia,$anno2));
// 		    if (!in_array( $dian, $nolaborales)) {
//             // echo $dial."".$dian."<br>";
//               $sqlverfica="select count(*) as cantidad from tab_ordenamiento_proceso where fe_cita='$dian'";
// 			  $dsx  = new DataStore($db, $sqlverfica);
// 			  if($dsx->getNumRows()<=0){
// 					              $inserReg="INSERT INTO tab_ordenamiento_proceso( proceso, ce_trabajador, fe_cita, turno, anno) VALUES ($proceso,$cedula, '$dian', 1,2016)";
								
// 								  $rs        = $ds->executesql($inserReg);
// 								  $j=1;
// 			//envia_confirmacion($opcion);
			
			  
// 			  }else{
// 			  $rows=$dsx->getValues(0);
// 			  if( $rows['cantidad']<5) {
// 				  $inserReg="INSERT INTO tab_ordenamiento_proceso( proceso, ce_trabajador, fe_cita, turno, anno) 
// 							VALUES ($proceso,$cedula, '$dian', 1, 2016)";
// 						  $rs        = $ds->executesql($inserReg);
// 						  $j=1;
// 			 // envia_confirmacion($opcion);
			  
// 				  }
			  
			  
// 			  }
 
  
  
//             }
			
// 			}//cierra if
// 	$dia++;
// 	//if($i==60){exit;}	
	
// }// cierra while

// }else{///cierra if consulta
// //envia_confirmacion($opcion);

// }
	}

//*****************************************/
function muestra_cita(){
global $db, $dbs, $ced ;
$cedula=$_SESSION['cedula'];	
	 
	 $sqlverfica="select * from public.tra_participacion_concurso where ce_participante=$cedula and anno=2017";
$dsx  = new DataStore($db, $sqlverfica);
if($dsx->getNumRows()<=0){
	//echo "no encuentra datos";
}else{


echo $nota='<div align="justify">Una vez finalizado el lapso de apertura de concursos le notificaremos via correo electr&oacute;nico y SMS la fecha en la que
 deber&aacute; asistir al CEDIA y consignar todas las credenciales.</div>';
}
	}
//********************************
function  act_correo(){
global $db, $dbs, $ced, $dbw ;
$cedula=$_SESSION['cedula'];
extract($_POST);
$_SESSION['email']=$email;

$inserReg="UPDATE   public.mst_registro_usuario  
SET 
  da_e_mail = '$email'
WHERE 
   co_cedula = $cedula";

		$ds	       = new DataStore($dbw);
		$rs        = $ds->executesql($inserReg);
	
	}

?>