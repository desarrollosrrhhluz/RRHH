<?php
require_once('../RRHH2/tcpdf6/config/lang/eng.php');
require_once('../RRHH2/tcpdf6/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');
session_start();
class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {

	}
	
	public function Footer() {
	
	}
}



	$pdf = new MYPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
	$pdf->SetCreator('Direccion de Recursos Humanos');
	$pdf->SetAuthor('Proceso de Apoyo Informatica y Tecnologia');
	$pdf->SetTitle(' Certificado');
	$pdf->SetSubject('');
	$pdf->SetKeywords('Planilla, PDF, Registro');
	$pdf->SetMargins(0, 0, 0);
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->SetAutoPageBreak(false, 0);
	$pdf->AddPage();
	$id=$_GET['id'];
$dbr="rrhh";
$sql="select   a.id as id_adiestramiento,a.contenido,c.id,a.descripcion as adiestramiento, a.tipo_certificado, a.cant_horas,
a.fe_desde, a.fe_hasta,
d.descripcion as participacion, 
b.nombre, b.cedula, c.id_tipo_participacion
 from admon_personal.tab_adiestramientos as a,
admon_personal.tab_participantes as b, admon_personal.tab_adiestramiento_participante as c,
opciones.tab_opciones as d
where c.id=$id
and a.id=c.id_adiestramiento
and b.id=c.id_participante
and c.id_tipo_participacion=d.id";

	$ds = new DataStore($dbr, $sql);
	$datos = $ds->getValues(0);
	$id_adiestramiento=$datos['id_adiestramiento'];

	$pdf->Image('images/fondoCertificados.jpg', 0, 0,280,0, '', '', '', false, 0, '', false, false, 0);
	$pdf->setXY(0,55);
	$pdf->SetFont('helvetica', 'B', 32);
	$pdf->SetTextColor(0, 58, 106); 
	$pdf->Cell(300,7,"Certificado",0,1,'C');
	$pdf->SetFont('helvetica', '', 20);
	$pdf->SetTextColor(0, 0, 0); 
	$pdf->Cell(300,10,"Que se otorga a:",0,1,'C');
	$pdf->SetFont('helvetica', 'B', 32);
	$pdf->SetTextColor(0, 58, 106); 
	$pdf->Cell(300,10,$datos['nombre'],0,1,'C');
	$pdf->SetFont('helvetica', 'B', 22);
	$pdf->Cell(300,7,"C.I.  ".number_format($datos['cedula'],0,'','.'),0,1,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica', '', 20);
	$pdf->SetTextColor(0, 0, 0); 

	if($datos['id_tipo_participacion']==10){
 	$pdf->Cell(300,13,utf8_encode("Por servir de facilitador en la siguiente actividad de formación:"),0,1,'C');

	}else{
 	$pdf->Cell(300,13,utf8_encode("Por haber ".($datos['tipo_certificado']=='12'?  'asistido a' : 'aprobado')." la siguiente actividad de formación:"),0,1,'C');
	}
	$pdf->SetFont('helvetica', 'B', 20);
	$pdf->SetTextColor(0, 58, 106); 
	$pdf->writeHTML($datos['adiestramiento'],'LRTB', 0, 1, true, 'C', true);
	$pdf->SetFont('helvetica', '', 20);
	$pdf->SetTextColor(0, 0, 0); 
	$pdf->Cell(300,10,utf8_encode("Duración: ".$datos['cant_horas'] ." horas académicas"),0,1,'C');
	$meses=["01"=>"enero","02"=>"febrero","03"=>"marzo","04"=>"abril","05"=>"mayo","06"=>"junio","07"=>"julio","08"=>"agosto","09"=>"septiembre","10"=>"octubre","11"=>"noviembre","12"=>"diciembre"];
	$pdf->Cell(300,10,"Maracaibo, ".$meses[substr($datos['fe_desde'],5,2)]." de  ".substr($datos['fe_desde'],0,4),0,1,'C');
	$pdf->Ln(20);

	$pdf->Image("images/firmaIxora.png",30, 165,40,0, '', '', '', false, 0, '', false, false, 0);
	$pdf->Image("images/eleyne.png",225, 165,40,0, '', '', '', false, 0, '', false, false, 0);


	if($datos['id_tipo_participacion']==10){
	$pdf->SetFont('helvetica', '', 16);
	$pdf->Cell(150,10,utf8_encode("Dra. Ixora Gómez"),0,0,'C');	
	$pdf->Cell(150,10,utf8_encode("MSc. Eleyne González"),0,1,'C');
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell(150,5,"Directora de RRHH",0,0,'C');
	$pdf->Cell(150,5,utf8_encode("Formación del Talento Humano"),0,1,'C');
	}else{
	$sqlFacilitador="select *
from admon_personal.tab_adiestramiento_participante as a, admon_personal.tab_participantes as b
where a.id_participante=b.id
and a.id_tipo_participacion=10
and a.id_adiestramiento=$id_adiestramiento";
    $ds2 = new DataStore($dbr, $sqlFacilitador);
	$row = $ds2->getValues(0);
	 $id_participante=$row['id_participante'];
	
$y = 10;
$h = 25;
//echo "https://".$_SERVER['SERVER_NAME']."/RRHH/aspirantes.php?op=muestraImagen&id=77&tipo=2";
$img = file_get_contents("http://".$_SERVER['SERVER_NAME']."/RRHH/certificados.php?op=muestraImagen&id=$id_participante");
$pdf->Image("@".$img,125, 160,40,0, '', '', '', false, 0, '', false, false, 0);	
  

	$pdf->SetFont('helvetica', '', 16);
	$pdf->Cell(100,10,utf8_encode("Dra. Ixora Gómez"),0,0,'C');
	$pdf->Cell(95,10,(empty($row['nombre'])  ? '' : $row['nombre']),0,0,'C');
	$pdf->Cell(100,10,utf8_encode("MSc. Eleyne González"),0,1,'C');
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell(100,5,"Directora de RRHH",0,0,'C');
	$pdf->Cell(95,5,"Facilitador",0,0,'C');
	$pdf->Cell(100,5,utf8_encode("Formación del Talento Humano"),0,1,'C');
	}
$style = array(
    'position' => 'S',
    'border' => false,
    'padding' => 4,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 7,
    'stretchtext' => 6
);
	
		$pdf->setXY(240,42);
		$pdf->SetFont('helvetica', 'I', 8);
	
		$pdf->write1DBarcode( str_pad($id, 10, "0", STR_PAD_LEFT), 'C128B', '', '', 40, 18, 0.4, $style, 'N');

	 if(strlen($datos['contenido'])>10){
	$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 20);
	$pdf->writeHTMLCell  (230, 0, 20, 20, '<h1>Contenido</h1>'.$datos['contenido'], 0, 0, 0, true); 

	 }

	$pdf->output('certificadoParticipante_'.$id.'.pdf', 'D')->deleteFileAfterSend(true);;

?>
