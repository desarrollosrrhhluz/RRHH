<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHARI';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

session_start();

$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$_SESSION['fe_inicio_bc']="2014-10-01";
$ci = $_SESSION['cedula'];

$db     = "sidial";

$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where 
  CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER
 where CE_TRABAJADOR=$ci and ESTATUS='A' and ESTATUS != 'F' and ESTATUS!= 'R' and PRINCIPAL = 'S' ";

$ds     = new DataStore($db, $sql2);

if($ds->getNumRows()==0){



}else{
 
 $row = $ds->getValues(0);
 
 $_SESSION['sexo']=$row['SEXO'];
 $_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
 $_SESSION['coubicacion']=$row['CO_UBICACION'];
 $_SESSION['nombres']=$row['NOMBRES'];
 
 $cuartaposicion=substr($row['TIPOPERSONAL'],4,1);
 
 
 

 $sql =  "SELECT
 id,
 mes_variacion,
 ano_variacion,
 unidad_tributaria
 FROM 
 tra_configuracion_ari
 WHERE 
 id = 1";
 
 $ds2  = new DataStore($dbr, $sql);
 $row2 = $ds2->getValues(0);	

 $UnidadT=$row2['unidad_tributaria'];
 $_SESSION['ano_proceso']=$row2['ano_variacion'];
 $_SESSION['mes_proceso']=$row2['mes_variacion'];


 }

 $sql5 = "SELECT 
  *
 FROM 
 public.mst_calculo_ari where
 ce_trabajador=$ci;";

 $ds5 = new DataStore($dbr, $sql5);

 $fila5 = $ds5->getValues(0);

//$fila5['fecha_proceso']

 if ($ds5->getNumRows() > 0) 
 {
   
   
  $MensajeAux='<div class="alert alert-info" role="alert"><p style=" font-size:12px;">Usted realizo un cálculo de Estimación De Ingresos Anuales en la fecha '.$fila5['fecha_proceso'].' con un Monto Mensual A Retener (Porcentual) de '.$fila5['monto_menusual_retener'].' Para imprimir la constancia de este cálculo presione la imagen a continuación <a href="https://www.servicios.luz.edu.ve/RRHH/CalculoARIPDF.php?totalRemuneraciontribuUT='.$fila5['remun_estim_ut'].' &totalimpuestogravable='.$fila5['impuest_ano_grava'].'&totalrebajas='.$fila5['rebajas'].'&ImpuestoEstimadoaRetener='.$fila5['impuesto_retener'].'&totalmensualretenerinput='.$fila5['monto_menusual_retener'].'&cargafamiliarimput='.$fila5['carga_familiar'].'&impuestoretenidosimput='.$fila5['impuestos_retenidos'].'&desgravamen='.$fila5['desgravamen'].'&empresa='.$fila5['nombre_empresa'].'&montos='.$fila5['monto_empresa'].'&valorUT='.$fila5['valor_ut'].'&ano='.$fila5['ano_variacion'].'&mes='.$fila5['mes_variacion'].'&fecha='.$fila5['fecha_proceso'].'     "  ><img src="images/page_white_acrobat.png" title="Imprimir Constancia" alt="Imprimir Constancia" /></a></p></div>'; 
  
}else{
  $MensajeAux="";
}




if($cuartaposicion != '8' || $cuartaposicion != '9' )
{


?>
<script language="javascript"  src="./js/ARIBT.js"></script>
<div id="divInfo" >
 <?php echo $MensajeAux; ?>
</div>

<h2><img src="images/document_layout.png" width="32" height="32">Estimación De Ingresos Anuales (ARI)</h2><hr>
<div style="font-size:12px;">Solo aplica para personas naturales que estimen percibir remuneraciones iguales o superiores a mil unidades tributarias anuales.<BR />
  Si usted  se encuentra en condición de pensionado o jubilado no debe  realizar esta estimación.</div>


<div class="table-responsive" id="Div1" >
  <div align="right"><h2><a onclick="myModalARIMSJ0();" title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a></h2></div>
  <form name="form_REMUNERACIONESTIMADAENUT" id="form_REMUNERACIONESTIMADAENUT" >

    <table class="table" name="tablaMontosEmpresa" id="tablaMontosEmpresa" style="font-size:12px;" width="100%"> 
      <tr>
        <th>Nombre De La Empresa Donde Trabaja</th>
        <th>Monto Anual</th>
        <th><a href="#" onclick="AgregarCampos();" title="Agregar monto de otra empresa donde trabajo" ><img src="images/Gnome-List-Add-32.png" width="32" height="32"></a></th>
        <th><a href="#" onclick="EliminarCampos();" title="Borrar monto de otra empresa donde trabajo" ><img src="images/Gnome-Dialog-Error-32.png" width="32" height="32"></a></th>
      </tr>
      
      
      <tr>
        <th><input id="empresa_1" name="empresa_1" type="text" class="form-control"  value="LUZ" readonly="readonly" required="required" placeholder="" pattern=""  title=""/></th>
        <th><input id="monto_1" name="monto_1" type="text" class="form-control" required="required" placeholder="" pattern="[0-9]+([\.][0-9]+)?"  title="Solo números y puntos permitidos Ejemplo: 150000.78 " /></th>
        <th></th>
        
      </tr>
      
    </table>
    
    
    <table class="table" name="tablaTotalRemuneracion" id="tablaTotalRemuneracion" style="font-size:12px;"> 
      <tr>
        <th>Remuneración Estimada</th>
        <th>Valor Unidad Tributaria</th>
        <th>Remuneración Estimada En UT</th>
      </tr>
      
      
      <tr>
        <th><input id="TotalRemuAnual" name="TotalRemuAnual" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title=""/></th>
        <th><input id="valorUT" name="valorUT" type="text" class="form-control" required="required"  value="<?php echo $UnidadT ?>"  readonly="readonly" placeholder="" pattern=""  title=""/></th>
        <th><input id="totalRemuneraciontribuUT" name="totalRemuneraciontribuUT" type="text" class="form-control" required="required" placeholder="" pattern=""  title="" readonly="readonly"/></th>
        
      </tr>
      
    </table>
    
    
    
    <div align="center">
      <input type="submit"  value="Calcular" class="btn btn-primary"  />

      <div align="right">

        <button type="button" class="btn btn-default"  onclick="SIGUIENTE1();">Siguiente</button>
      </div>
    </div>

  </form>

</div>


<div style="font-size:12px; display:none" id="Div2" ><h3><div align="right"><a onclick="myModalARIMSJ1();" title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a></div>Desgravamenes</h3>


<input type='radio' name='desgravamenesgroup' id='desgravamenesgroup1' value='1' required checked="checked">Único&nbsp;&nbsp;<input type='radio' name='desgravamenesgroup' id='desgravamenesgroup2' value='2' required>Variable<br/><br/>


<div style="font-size:12px;"><b>Si elige desgravamen unico seleccione directamente "calcular" y luego "siguiente"</b></div>
<br />
<div id="divDESGRAVAMENDefecto" style="font-size:12px;">


 <table class="table" > 
  <tr>
    <th>Remuneraciones UT</th>
    <th>Desgravamen Unico </th>
    <th>Total</th>
  </tr>
  <tr>
   <th><input id="RemuneracionesUTCampodesgra" name="RemuneracionesUTCampodesgra" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title=""/>
   </th>
   <th><input id="DESGRAVAMENUNICOCampo" name="DESGRAVAMENUNICOCampo" type="text" class="form-control"  value="774.00" readonly="readonly" required="required" placeholder="" pattern=""  title=""/>
   </th>
   <th><input id="TotalDESGRAVAMENUNICOCampo" name="TotalDESGRAVAMENUNICOCampo" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
   </th>
 </tr>
 
</table>


<table class="table" > 
  <tr>
    <th>Total De Impuesto Del Año Gravable</th>
  </tr>
  <tr>
   <th><input id="totalimpuestogravable" name="totalimpuestogravable" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
   </th>
 </tr>
 
</table>

<hr />

<div align="center">
 <input name="" id="" type="button" onclick="CalcularDESGRAVAMENUTBton();" value="Calcular" class="btn btn-primary"  />
</div>


<div align="left" style="position:absolute"><input type="button"  value="Atrás" class="btn btn-default"   onclick="ATRASDIV1();"/></div>

<div align="right">

  <button type="button" class="btn btn-default"  onclick="SIGUIENTE2();">Siguiente</button>
</div>


</div>




<div id="divDESGRAVAMENVarios" style="display:none;font-size:12px;" >

 <laber >1.-Institutos Docentes Por La Educacion Del Contribuyente Y Descendientes No Mayores De 25 Años</laber>
 <input id="DESGRAVAMENUNICOvariante1" name="DESGRAVAMENUNICOvariante1" type="text" class="form-control"  value="0" required="required" placeholder="" pattern=""  title=""/>


 <laber >2.-Primas De Seguros De Hospitalizacion, Cirugia Y Maternidad</laber>
 <input id="DESGRAVAMENUNICOvariante2" name="DESGRAVAMENUNICOvariante2" type="text" class="form-control"  value="0"  required="required" placeholder="" pattern=""  title=""/>
 
 <laber >3.-Servicios Medicos Odontologicos Y De Hospitalizacion  (incluye Carga Familiar)</laber>
 <input id="DESGRAVAMENUNICOvariante3" name="DESGRAVAMENUNICOvariante3" type="text" class="form-control"  value="0"  required="required" placeholder="" pattern=""  title=""/>
 
 <laber >4.-Intereses Para La Adquisicion De La Vivienda Principal O De Lo Pagado Por Alquiler De La Vivienda Que Le Sirve De Asiento Permanente Del Hogar</laber>
 <input id="DESGRAVAMENUNICOvariante4" name="DESGRAVAMENUNICOvariante4" type="text" class="form-control"  value="0"  required="required" placeholder="" pattern=""  title=""/>


 
 <table class="table" > 
  <tr>
    <th>Remuneraciones UT</th>
    <th>Desgravamen</th>
    <th>Total</th>
  </tr>
  
  
  <tr>
   <th><input id="RemuneracionesUTCampodesgra2" name="RemuneracionesUTCampodesgra2" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title=""/>
   </th>
   <th><input id="DESGRAVAMENUNICOCampo2" name="DESGRAVAMENUNICOCampo2" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title=""/>
   </th>
   <th><input id="TotalDESGRAVAMENUNICOCampo2" name="TotalDESGRAVAMENUNICOCampo2" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title=""/>
   </th>
 </tr>
 
</table>



<table class="table" > 
  <tr>
    <th>Total De Impuesto Del Año Gravable</th>
  </tr>

  <tr>
   <th><input id="totalimpuestogravable2" name="totalimpuestogravable2" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
   </th>
 </tr>
 
</table>





<div align="center">
  <input  type="button" onclick="CalcularDESGRAVAMENUTBton2();" value="Calcular" class="btn btn-primary" />
</div>


<div align="left" style="position:absolute">
  <input type="button"  value="Atrás" class="btn btn-default"   onclick="ATRASDIV1();"/>
</div>

<div align="right">

  <button type="button" class="btn btn-default"  onclick="SIGUIENTE3();">Siguiente</button>
</div>


</div>
</div>
</div>


<div style="font-size:12px; display:none" id="Div3">
  <hr />
  <div style="font-size:12px;"><h3><div align="right"><a onclick="myModalARIMSJ2();" title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a></div>Rebajas Al Impuesto</h3>
    <br />
    <div class="row">
      <div class="col-sm-4"><label class="control-label">1.-Rebaja Persona Natural (art.  61 De La Ley)</label></div>
      <div class="col-sm-3"><input id="rebajaPersonaNatural" name="rebajaPersonaNatural" type="text" class="form-control"  value="1" required="required" placeholder="" pattern="" readonly="readonly"  title=""/></div>
      <div class="col-sm-2 "><label class="control-label">Total UT:</label></div>
      <div class="col-sm-3 "><input name="TotalUt1" id="TotalUt1" type="text" class="form-control" value="10" readonly="readonly"  required="required"  placeholder=""  title=""/></div>
    </div>


    <div class="row">
      <div class="col-sm-4"><label class="control-label">2.-Carga Familiar Cantidad</label></div>
      <div class="col-sm-3"><input id="cargafamiliarimput" name="cargafamiliarimput" type="text" class="form-control" value="0"  required="required" placeholder="" pattern=""  title=""/></div>
      <div class="col-sm-2 "><label class="control-label">Total UT:</label></div>
      <div class="col-sm-3 "><input name="TotalUt2" id="TotalUt2" type="text" class="form-control"  required="required" readonly="readonly"   placeholder=""  title=""/></div>
    </div>


    <div class="row">
      <div class="col-sm-4"><label class="control-label">3.-Impuestos Retenidos Demas En Años Anteriores</label></div>
      <div class="col-sm-3"><input id="impuestoretenidosimput" name="impuestoretenidosimput" type="text" class="form-control"  value="0"  required="required" placeholder="" pattern=""  title=""/></div>
      <div class="col-sm-2 "><label class="control-label">Total UT:</label></div>
      <div class="col-sm-3 "><input name="TotalUt3" id="TotalUt3" type="text" class="form-control"  required="required" readonly="readonly" placeholder=""  title=""/></div>
    </div>


    <br />
    
    <table class="table" > 
      <tr>
        <th>Total Rebajas</th>
      </tr>
      
      
      <tr>
       <th><input id="totalrebajas" name="totalrebajas" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
       </th>
     </tr>
     
   </table>
   
   
   <table class="table" > 
    <tr>
      <th>Impuesto (estimado) A Retener En El Año Gravable</th>
    </tr>
    
    
    <tr>
     <th><input id="ImpuestoEstimadoaRetener" name="ImpuestoEstimadoaRetener" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
     </th>
   </tr>
 </table>
 
 
 <table class="table" > 
  <tr>
    <th>Monto Mensual A Retener (porcentual)</th>
  </tr>
  <tr>
   <th><input id="totalmensualretenerinput" name="totalmensualretenerinput" type="text" class="form-control"  value="" readonly="readonly" required="required" placeholder="" pattern=""  title="" />
   </th>
 </tr>
 
</table>

<div align="left" style="position:absolute"><input type="button"  value="Atrás" class="btn btn-default"   onclick="ATRASDIV2();" /></div>

<div align="center"><input name="" id="" type="button" onclick="Calculartotalrebajas();" value="Calcular" class="btn btn-primary"  /><br /><br />


  <div id="cargadiv" align="center"></div>

  <input name="" id="" type="button" onclick="CalculoFinalPDF();" value="GUARDAR" class="btn btn-success"  />

</div>
</div>
<br/>
</div>


<div class="modal fade" id="myModalARIMSJ0">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><div ><a title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a>&nbsp;<b>AYUDA</b></div></h3>
      </div>
      
      
      <div class="modal-body">
       <h3 style="font-size:13px;"><b>&nbsp;<img src="images/Gnome-List-Add-32.png" width="32" height="32">&nbsp;Agregar Monto De Otra Empresa Donde Trabajo</b></h3>
       <p class="small" style="font-size:12px;">

       </p>
       <hr />
       <h3 style="font-size:12px;"><b><img src="images/Gnome-Dialog-Error-32.png" width="32" height="32">&nbsp;Borrar Monto De Otra Empresa Donde Trabajo</b></h3>
       <p class="small" style="font-size:12px;">

       </p>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button> 
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="myModalARIMSJ1">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><div ><a title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a>&nbsp;<b>AYUDA</b></div></h3>
      </div>
      
      
      <div class="modal-body">
       <h3 style="font-size:13px;"><b>Desgravamen Unico</b></h3>
       <p class="small" style="font-size:12px;">
   Artículo 60: 
   <br /><br />
   Las personas residentes en el país, podrán optar por aplicar un desgravamen único equivalente  a setecientas setenta y cuatro unidades tributarias (774 U.T).
       
      </p>
      <hr />
      <h3 style="font-size:12px;"><b>Desgravamen Variable</b></h3>
      <p class="small" style="font-size:12px;">
      Artículo 59:
        <br /><br />
        1.- Lo pagado a los institutos docentes del país, por la educación del contribuyente y de sus descendientes no mayores de (25) años. este límite no se aplicara a los casos de educación especial.
        <br /><br />
        2.- Lo pagado por el contribuyente a empresas domiciliadas en el pais por concepto de primas de seguros de hospitalizacion, cirugia y maternidad.
        <BR /><BR />
        3.- Lo pagado por servicios medicos, odontologicos y de hospitalizacion,  prestados en el pais al contribuyente y a las personas a su cargo.
        <BR /><BR />
        4.- Se refiere a los intereses de prestamos para la adquisicion de la vivienda principal del contribuyente (hasta 1000 ut). o  por concepto de alquiler de vivienda hasta (800 ut)
      </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button> 
    </div>
  </div>
</div>
</div>



<div class="modal fade" id="myModalARIMSJ2">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><div ><a title="Ayuda" > <span class="glyphicon glyphicon-question-sign"></span></a>&nbsp;<b>AYUDA</b></div></h3>
      </div>
      
      
      <div class="modal-body">
       <h3 style="font-size:13px;"><b>REBAJAS</b></h3>
       <p class="small" style="font-size:12px;">
        ARTÍCULO 61:
        <br /><br />
      1.- Las personas naturales residentes en el pais, gozaran de una rebaja de impuesto de diez (10) ut. anuales.
        <br />
        <br />
        2.- Los contribuyentes que tienen personas a su cargo gozaran de las rebajas de impuestos siguientes:
        <br /><br />
        -  Diez (10) unidades tributarias por el conyuge no separado de bienes.
        <br /><br />
        -  Diez (10) unidades tributarias por cada ascendiente o descendiente directo residente  en el pais. no aplica esta rebaja a los descendientes mayores de edad a menos que esten discapacitados para el trabajo, o esten trabajando y sean menores de veinticinco (25) años.
        <br /><br />
        artículo 58:
        <br /><br />
        3.-  Cuando en razón de los anticipos o pagos a cuenta, derivados de la retención en la
fuente, resultare que el contribuyente tomando  en cuenta la liquidación proveniente de la 
declaración de rentas, ha pagado más del impuesto causado en el respectivo ejercicio, tendrá 
derecho a solicitar en sus declaraciones futuras que dicho exceso le sea rebajado en las 
liquidaciones de impuesto correspondientes a los subsiguientes ejercicios, hasta la concurrencia 
del monto de tal exceso, todo sin perjuicio del derecho a reintegro.
       
      </p>
    
     
     
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button> 
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="myModalMSJGUARDAR">
  <div class="modal-dialog"> 
    <div class="modal-content">
     
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><b>Advertencia</b></h3>
      </div>
      
      
      <div class="modal-body">
        
        <p class="small">Desea guardar este calculo de estimación de ingresos anuales ?</p>
      </div>
      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button> 
        <button type="button" class="btn btn-primary" id="btn_comfirma_evaluacion" onClick="botonEventoVentanaARIGUARDAR();">Continuar</button>
      </div>
      
      
    </div>
  </div>
</div>



<div align="center" id="Div4" style="display:none">

  <br />
  <br />
  <div class="alert alert-success" role="alert">Operación realizada con éxito</div>


</div>
<?php }else{ ?>

<div style="font-size:12px;" ><b><br />
USTED SE ENCUENTRA EN CONDICIÓN DE PENSIONADO O JUBILADO NO DEBE  REALIZAR ESTA ESTIMACIÓN.</b></div>

<?php }?>
