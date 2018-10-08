<?php
include_once("../FrameWork/Include/Funciones.inc.php");

function clausulaVeracidad($n="",$l="",$ci=0){
    $cedula = number_format($ci,0,',','.');
    return "En concordancia con el artículo 23 de la Ley Orgánica de Simplificación de Trámites Administrativos, donde se establece como norma con la presunción de buena fe, la Dirección de Recursos Humanos de la Universidad del Zulia tomará como cierta la declaración de las personas interesadas en prestar servicios en esta institución. Por lo cual, Yo, $n, natural de $l, portador del documento de identidad $cedula, declaro que los datos suministrados en esta solicitud electrónica de empleo, son veraces, por lo que autorizo a la Dirección de Recursos Humanos de la Universidad del Zulia para que proceda a verificarlos ante las personas y organismos emisores.";
}
function tramitacionElectronica($n="",$ci=0,$id="",$f=""){
//    $fi = date("d/m/Y", $f);
//    $fv = substr(dateadd($f,0,0,1,0,0,0),0,10); // suma 1 año a la fecha
    list($d,$m,$a) = explode('/', $f);  //fecha es dd/mm/yyyy
    $fi = $f;
    $fv = "$d/$m/".strval($a+1);
    $cedula = number_format($ci,0,',','.');
    return "Por medio de la presente se hace constar que el(la) ciudadano(a): $n portador del documento de identidad $cedula, ha tramitado electrónicamente en el portal de esta Dirección de Recursos Humanos una solicitud de empleo número $id la cual será objeto de análisis con la finalidad de ser incluida en el Banco de Potenciales administrados por ella, específicamente para ofertar sus servicios como personal administrativo u obrero, de acuerdo con el estudio de: nivel de instrucción, credenciales y experiencia laboral. Queda entendido que la tramitación electrónica de solicitud de empleo no indica la existencia de ningún compromiso laboral entre las partes, y que su vigencia en el Banco de Potenciales será de un (1) año a partir de la fecha de su envío (desde $fi hasta $fv), teniendo el interesado derecho a actualización curricular y/o renovación, al finalizar dicho periodo.";
}

?>
