<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHBC';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

session_start();
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$_SESSION['fe_inicio_bc']="2018-09-19";
$ci = $_SESSION['cedula'];
 
/////////////////////////////////////////////////////////
//$os = array(19706762,13930556,6746770,12801967,12445260,19549551,12688246,16298379,7818037,7977040,18744700,16298379);
$os = array(0000000);
if (in_array($ci, $os)) {
  echo '<fieldset><legend>
    <h2><img src="images/exclamation.png" /> Usted NO esta Autorizado a Acceder a est&aacute; Aplicaci&oacute;n</h2></legend>
 
    <strong> Conforme al  Sello R-00018 87 del 06/07/2011</strong>
    <br />
    <div align="justify">El Trababajdor que incurra en presentar documentaci&oacute;n adulterada, forjada o falsificada, previa comprobaci&oacute;n por autoridad competente, ser&oacute;n   sancionadas  con suspensi&oacute;n del  beneficio por el lapso de dos (2) a&ntilde;os consecutivos.</div>
</fieldset>
';
		 exit;
}
////////////////////////////////////////////////////////
/*$today = date("Y-m-d H:i:s");
$punto=  "2013-09-20 17:25:04";

if($punto< $today){
//echo "Ya la hora Paso";
 header ('Location: ProcesoTerminado.html');
exit;
}*/

///////////////////////////////////////////////////////	
		$db     = "sidial";
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS in ('A','C','F') and ESTATUS!='R' and ESTATUS!='S' ";
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()==0){
		
		// header ('Location: errorRRHH.html');
		 //exit;
		}else{
      
		$row = $ds->getValues(0);
		$_SESSION['sexo']=$row['SEXO'];
		$_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
		$_SESSION['coubicacion']=$row['CO_UBICACION'];
		$_SESSION['ano_proceso']=2018;
		$_SESSION['estatus']=$row['ESTATUS'];
		$_SESSION['nombres']=trim(utf8_encode($row['NOMBRES']));
        }
?>
<script language="javascript"  src="./js/BeneficiosContractualesBT.js"></script>

  <h2><img src="images/document_layout.png" width="32" height="32">Solicitud de Beneficios Contractuales (2018 - 2019)</h2><hr>

<div id="familiaresRegistrados">
  <fieldset>
    <legend><h2><img src="images/group.png" width="32" height="32" />Hijos Registrados</h2></legend>
    <div id="familiares_existentes" ></div>
  </fieldset>

   <div style="font-size:12px; text-align:justify">* Los simbolos <span class="label label-default" title="Beca Inactiva">B</span> , <span class="label label-default" title="Ayuda Textos y Utiles Inactiva">T</span> , <span class="label label-default" title="C.E.I Inactivo">G</span>,  <span class="label label-default" title="Educ. para los hijos con discapacidad">D</span> y <span class="label label-default" title="Juguetes Inactiva">J</span> singifican Beca, Ayuda para Textos y &Uacute;tiles Escolares, Centro de Educaci&oacute;n Incial, Educacion para los hijos con discapacidad y Juguetes respectivamente. Las variantes de estos simbolos en colores Verde y Gris representan los estatus activo e inactivo del beneficio </div>
</div>


<div id="div_form_fam" >

  <fieldset><legend><h2><img src="images/user_edit.png" width="32" height="32" />Datos del Familiar</h2></legend>

    <form action="" method="post" name="form_fam" id="form_fam">

      <div class="row">
        <div class="col-lg-3 col-md-3 col-md-3"><label>C.I. Otro Progenitor:</label></div>
        <div class="col-lg-1 col-md-1 col-md-1">
          <select name="nac1" id="nac1" size="0" class="form-control"><option value="V" selected>V</option><option value="E">E</option></select>
        </div>
        <div class="col-lg-2 col-md-2">
          <input name="ce_otro_p"  placeholder="9999999" pattern="[0-9]{5,8}" id="ce_otro_p" type="text" class="form-control" required="required"  title="Debe escribir n&uacute;mero de c&eacute;dula del otro progenitor (OBLIGATORIO). Debe ser distinto de <?php echo $_SESSION['cedula'] ?> "/>
        </div>
        <div class="col-lg-3 col-md-3 col-md-3"><label>Nombre Otro Prog.:</label></div>
        <!--pattern="[\W ? '?\D]+"-->
        <div class="col-lg-3 col-md-3">
          <input name="nomb_otro_p" type="text" class="form-control" pattern="[\W\s\D]+"  placeholder="Ejemplo: Garcia Bracho Pedro Jose" required="required" id="nomb_otro_p" size="40" title="Escriba nombres y apellidos separados por coma(,)"/>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-md-3 col-md-3"><label>Fec. de Nac. Hijo(a):</label></div>
        <div class="col-lg-2 col-md-2 col-md-2">
          <input name="fe_nac_fam" id="fe_nac_fam" type="text" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" class="form-control" placeholder="Dia/Mes/año" title="Escriba fecha de nacimiento en formato día/mes/año"/>
        </div>
        <div class="col-lg-2 col-md-2 col-md-2"><label>C.I. Hijo(a):</label></div>
        <div class="col-lg-2 col-md-2 col-md-3">
          <input name="ce_familiar" id="ce_familiar" required="required" type="text" class="form-control" pattern="[0-9]+" title="Escriba la cédula del familiar, si no posee escriba 0"/>
        </div>
        <div class="col-lg-1 col-md-1 col-md-1"><label>Sexo:</label></div>
        <div class="col-lg-2 col-md-2 col-md-2">
          <select name="sexo_fam" id="sexo_fam" class="form-control" required title="Seleccione el sexo de familiar" >
            <option value="">-Seleccione-</option>
            <option value="F">F</option>
            <option value="M">M</option>
          </select>
        </div>
      </div> 

      <div class="row">
        <div class="col-lg-3 col-md-3"><label class="control-label">Primer Nombre:</label></div>
        <div class="col-lg-3 col-md-3">
          <input name="nombre1" type="text" class="form-control" id="nombre1" pattern="[\W\s\D]+"  required="required" placeholder="Carlos"   title="Escriba el primer nombre"/>
        </div>
        <div class="col-lg-3 col-md-3 "><label class="control-label">Segundo Nombre:</label></div>
        <div class="col-lg-3 col-md-3 ">
          <input name="nombre2" type="text" class="form-control" id="nombre2" pattern="[\W\s\D]+"  placeholder="Luis"  title="Escriba el segundo nombre"/>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-md-3"><label>Primer Apellido:</label></div>
        <div class="col-lg-3 col-md-3">
          <input name="apellido1" type="text" class="form-control" id="apellido1" pattern="[\W\s\D]+" required="required" placeholder="Prieto"    title="Escriba el primer apellido"/>
        </div>
        <div class="col-lg-3 col-md-3"><label>Segundo Apellido:</label></div>
        <div class="col-lg-3 col-md-3">
          <input name="apellido2" type="text" class="form-control" id="apellido2" pattern="[\W\s\D]+"  placeholder="Garcia"  title="Escriba el segundo apellido"/>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-md-3"><label>Condicion personal:</label></div>
        <div class="col-lg-3 col-md-3">
          <select name="condicion_fam" id="condicion_fam" class="form-control" required="required" title="Seleccione la condición personal de su familiar">
            <option value="">-Seleccione-</option>
            <option value="1">Sin Discapacidad</option>
            <option value="2">Con Discapacidad</option>
          </select>
        </div>
        <span id="tipo_incapacidad">
          <div class="col-lg-3 col-md-3"><label>Numero CONAPDIS:</label></div>
          <div class="col-lg-2 col-md-2">
            <input name="conapdis" type="text" placeholder="0000" pattern="[0-9]+" class="form-control" id="conapdis"  title="Codigo CONAPDIS "/>
          </div>
        </span>
      </div>

      <span><strong>Informacion de estudios</strong></span><br/>
      <div class="row">
        <div class="col-lg-3 col-md-3"><label>&iquest;Estudia?</label></div>
        <div class="col-lg-2 col-md-2">
          <select name="estudia" id="estudia" class="form-control" required="required" title="Indique si su familiar estudia actualmente ">
            <option value="">- -</option>
            <option value="1">S&iacute;</option>
            <option value="2">No</option>
          </select>
        </div>
      </div>

      <div id="campos_estudio">
        <div class="row">
          <div class="col-lg-3 col-md-3"><label>Nivel de estudio:</label></div>
          <div class="col-lg-3 col-md-3">
            <select name="nivel_estudio" id="nivel_estudio" class="form-control" title="Seleccione el nivel de estudio">
              <option value="">-Seleccione-</option>
              <option value="5">Educacion inicial y/o preescolar</option>
              <option value="1">Basica </option>
              <option value="2">Secundaria (7mo a 2do C.D.)</option>
              <option value="3">Universitaria Pregrado</option>
            </select>
          </div>
          <div class="col-lg-3 col-md-3" style="font-size:10px">* Solo para estudiantes de LUZ  R.I.F  G-20008806-0</div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-3"><label>Tipo Sitio de Estudio:</label></div>
          <div class="col-lg-3 col-md-3">
            <select name="tipo_sitio" id="tipo_sitio" class="form-control" title="Seleccione el tipo sitio de estudio">
              <option value="">-Seleccione-</option>
              <option value="1">Publico </option>
              <option value="2">Privado</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-3"><label>R.I.F Sitio de Estudio:</label></div>
          <div class="col-lg-2 col-md-2"> 
          <!--pattern="[JGEPjgep][-][0-9]{8}[-][0-9]{1}"-->
            <input type="text" name="rif" id="rif" style="text-transform: uppercase;" placeholder="RIF o CODIGO DEL PLANTEL"  title="Ingrese un RIF con el formato J-0000000-0 , G-0000000-0" required="required" class="form-control" />
          </div>
          <div class="col-lg-3 col-md-5">
            <input type="text" name="sitio_estudio" id="sitio_estudio" required="required"  placeholder="Nombre del Sitio de Estudio"  class="form-control" />
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-3"><label>Regimen educativo:</label></div>
          <div class="col-lg-2 col-md-2">
            <select name="regimen" size="1" id="regimen" class="form-control" title="Seleccione regimen de estudio">
              <option value="">-seleccione-</option>
              <option value="1">Anual</option>
              <option value="2">Semestral</option>
              <option value="3">Trimestral</option>
            </select>
          </div>
          <div class="col-lg-3 col-md-3"><label>A&ntilde;o, Sem. o trimestre:</label></div>
          <div class="col-lg-2 col-md-2">
            <select name="grado_estudio" id="grado_estudio" class="form-control" title="Seleccione Grado, semestre, trimestre o año"></select>
          </div>
        </div>
      </div>

      <div align="center">
        <input type="hidden" name="op" id="op_familiar" value="guarda_familiar"/>
        <input type="hidden" name="id_fo" id="id_fo"/>
        <input type="hidden" name="registra" id="registra" value="<?php echo $_SESSION['cedula']; ?>"/>
        <input type="hidden" name="sexor" id="sexor" value="<?php echo $_SESSION['sexo']; ?>"/>
        <input name="btn_guardar_familiar" id="btn_guardar_familiar" type="submit" value="Guardar" class="btn btn-success"/>
        <input name="btn_cancelar_familiar" id="btn_cancelar_familiar" type="reset" value="Cancelar" class="btn btn-default"/>
      </div>

    </form>

  </fieldset>

  <div id="destino"></div>

</div>

