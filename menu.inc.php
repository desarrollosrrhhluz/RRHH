<?php
//include_once("../Seguridad/verificaSesion.inc.php");

function print_cv(){
	return "javascript:window.open('rep_resumen_curricular.php','_blank','toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes')";

}
/*
    * toolbar = [yes|no|1|0]. Nos dice si la ventana tendr? barra de herramientas (yes,1) o no la tendr? (no,0).
    * location = [yes|no|1|0]. Nos dice si la ventana tendr? campo de localizaci?n o no.
    * directories = [yes|no|1|0]. Nos dice si la nueva ventana tendr? botones de direcci?n o no.
    * status = [yes|no|1|0]. Nos dice si la nueva ventana tendr? barra de estado o no.
    * menubar = [yes|no|1|0]. Nos dice si la nueva ventana tendr? barra de men?s o no.
    * scrollbars = [yes|no|1|0]. Nos dice si la nueva ventana tendr? barras de desplazamiento o no.
    * resizable = [yes|no|1|0]. Nos dice si la nueva ventana podr? ser cambiada de tama?o (con el rat?n) o no.
    * width = px. Nos dice el ancho de la ventana en pixels.
    * height = px. Nos dice el alto de la ventana en pixels.
    * outerWidth = px. Nos dice el ancho *total* de la ventana en pixels. A partir de NS 4.
    * outerHeight = px. Nos dice el alto *total* de la ventana el pixels. A partir de NS 4
    * left = px. Nos dice la distancia en pixels desde el lado izquierdo de la pantalla a la que se debe colocar la ventana.
    * top = px. Nos dice la distancia en pixels desde el lado superior de la pantalla a la que se debe colocar la ventana.

*/
function menu_cv(){
	return  menu_0("C").
'
<div id="menuh">
	<ul>
		<li class="primer">Personal</li>
		<li><a href="frm_datos_personales.php">Datos personales</a></li>
		<li><a href="frm_telefonos.php">Tel?fonos</a></li>
		<li><a href="frm_correoe.php">Correo electr?nico</a></li>
		<li><a href="frm_familiares.php">Familiares</a></li>
	</ul>
</div>
<br/><br/>
<div id="menuh">
	<ul>
		<li class="primer">Ac?demica</li>
		<li><a href="frm_educacion.php">Educaci?n</a></li>
		<li><a href="frm_cursos.php">Cursos / Seminarios / Eventos</a></li>
	</ul>
</div>
<br/><br/>
<div id="menuh">
	<ul>
		<li class="primer">Profesional</li>
		<li><a href="frm_experiencia.php">Experiencia laboral</a></li>
		<li><a href="frm_reconocimiento.php">Reconocimientos / Meritos</a></li>
	</ul>
</div>
<br/><br/>
<div id="menuh">
	<ul>
		<li class="primer">Reportes</li>
		<li><a href="#" onclick="'.print_cv().'">Resumen curricular</a></li>
	</ul>
</div>
<br/><br/>';



}
function menu_solicitudes(){
	return menu_0("S").
'
<div id="menuh">
	<ul>
		<li class="primer">Mis solicitudes</li>
		<li><a href="../beneficio/indexBeneficio.php">Beneficios</a></li>
	</ul>
</div><br/><br/>';

/*
 *
 		<li><a href="frm_financiamiento.php" id="primero">Financiamiento de estudios</a></li>
		<li><a href="frm_concurso.php">Postulaci?n a concurso</a></li>
		<li><a href="frm_pasantia.php">Pasantias</a></li>
		<li><a href="index01.php">Empleo</a></li>
//		<li><a href="" onclick="">Baremos</a></li>
//		<li><a href="solicitudEmpleo.html">Empleo</a></li>
*/

}
function menu_0($opt=""){
    switch($opt){
        case "C":
            $classC =' class="selected"';
            break;
        case "S":
            $classS =' class="selected"';
            break;
        case "N":
            $classN =' class="selected"';
            break;
        default:

    }
	return
    '
<div id="menuh0">
	<ul>
		<li class="primer0">Servicios en l&iacute;nea</li>
		<li><a href="consultaNominaForm.php" >Mi n&oacute;mina</a></li>
		<li><a href="../Seguridad/actualizaUsuarioForm.php" >Actualiza Correo-E</a></li>
		<li><a href="../Seguridad/login.php?accion=bye" >Cerrar sesi&oacute;n</a></li>
	</ul>
</div><br/><br/>';
//		<li><a href="../Seguridad/actualizaUsuarioForm.php" >Actualiza datos</a></li>






















































        
//		<li><a '.$classC.' href="index.php?accion=cv" id="primero">Mi curriculum</a></li>
//		<li><a '.$classS.' href="index.php?accion=solicitud" >Mis solicitudes</a></li>



}
function msj_0(){
    return '<div id="instrucciones"><h2>Servicios en l&iacute;nea</h2><ul>
<li><strong>Mi n&oacute;mina:</strong> puede tener acceso cuando lo desee y sin importar donde se encuentre, a un registro pormenorizado y actualizado de pagos y deudas pendientes por distintos conceptos.</li><br/>
<li><strong>Mi curriculum:</strong> para utilizar algunas de las solicitudes que se ofrecen (empleo,pasant&iacute;as y beneficios) debe antes completar los datos de su curriculum personal, el cual puede actualizar cuando lo requiera. (en desarrollo) </li><br/>
<li><strong>Mis solicitudes:</strong> entre en esta secci&oacute;n para solicitar empleo, pasant&iacute;as o beneficios. (en desarrollo)</li><br/>
</ul>
</div>';
}
function msj_cv(){
	return '<div id="instrucciones"><strong>Mi curriculum:</strong><ul>
<li>Para utilizar algunas de las solicitudes que se ofrecen (empleo,pasant&iacute;as y beneficios) debe antes completar los datos de su curriculum personal, el cual puede actualizar cuando lo requiera.
</li><br/>
</ul></div>';
}
function msj_solicitudes(){
	return '<div id="instrucciones"><strong>Mis solicitudes:</strong> <ul>
<li>Entre en esta secci&oacute;n para solicitar empleo, pasant&iacute;as o beneficios.</li><br/>
</ul></div>';
}
/*
 * <ul>
<li><strong>Personal:</strong> Para utilizar algunas de las solicitudes que se ofrecen (empleo,pasant?as y beneficios) debe antes completar los datos de su curriculum personal, el cual puede actualizar cuando lo requiera.</li><br/>
<li><strong>Acad?mica:</strong> Entre en esta secci�n para solicitar empleo, pasant�as o beneficios.</li><br/>
<li><strong>Mi n?mina:</strong> Puede tener acceso cuando lo desee y sin importar donde se encuentre, a un registro pormenorizado y actualizado de pagos y deudas pendientes por distintos conceptos.</li><br/>

</ul>

 */

?>
