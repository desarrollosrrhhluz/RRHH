<?php
include_once("../FrameWork/Include/Funciones.inc.php");

function clausulaVeracidad($n="",$l="",$ci=0){
    $cedula = number_format($ci,0,',','.');
    return "En concordancia con el art�culo 23 de la Ley Org�nica de Simplificaci�n de Tr�mites Administrativos, donde se establece como norma con la presunci�n de buena fe, la Direcci�n de Recursos Humanos de la Universidad del Zulia tomar� como cierta la declaraci�n de las personas interesadas en prestar servicios en esta instituci�n. Por lo cual, Yo, $n, natural de $l, portador del documento de identidad $cedula, declaro que los datos suministrados en esta solicitud electr�nica de empleo, son veraces, por lo que autorizo a la Direcci�n de Recursos Humanos de la Universidad del Zulia para que proceda a verificarlos ante las personas y organismos emisores.";
}
function tramitacionElectronica($n="",$ci=0,$id="",$f=""){
//    $fi = date("d/m/Y", $f);
//    $fv = substr(dateadd($f,0,0,1,0,0,0),0,10); // suma 1 a�o a la fecha
    list($d,$m,$a) = explode('/', $f);  //fecha es dd/mm/yyyy
    $fi = $f;
    $fv = "$d/$m/".strval($a+1);
    $cedula = number_format($ci,0,',','.');
    return "Por medio de la presente se hace constar que el(la) ciudadano(a): $n portador del documento de identidad $cedula, ha tramitado electr�nicamente en el portal de esta Direcci�n de Recursos Humanos una solicitud de empleo n�mero $id la cual ser� objeto de an�lisis con la finalidad de ser incluida en el Banco de Potenciales administrados por ella, espec�ficamente para ofertar sus servicios como personal administrativo u obrero, de acuerdo con el estudio de: nivel de instrucci�n, credenciales y experiencia laboral. Queda entendido que la tramitaci�n electr�nica de solicitud de empleo no indica la existencia de ning�n compromiso laboral entre las partes, y que su vigencia en el Banco de Potenciales ser� de un (1) a�o a partir de la fecha de su env�o (desde $fi hasta $fv), teniendo el interesado derecho a actualizaci�n curricular y/o renovaci�n, al finalizar dicho periodo.";
}

?>
