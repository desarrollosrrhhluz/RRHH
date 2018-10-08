<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
set_time_limit(1000);
session_start();

//print_r($_SESSION);
//error_reporting(1);



include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
include_once('includes/Funciones.inc.php');
include_once('includes/NumerosLetras.inc.php');


class PDF extends FPDF {
  public $cedula;
  public $tipo;
  public $tipop;
  public $cou;
  public $codigo;
  public $destinatario;
  public $destino;
  public $id;

  function Header() {
   $this->SetFont('Arial','',12);
   $this->Cell(20,8,'',0,0,'L');
   $this->Image('images/encabezado_aniversario.jpg',15,5,100);
   $this->SetFont('Arial','',12);
   $this->Ln();$this->Ln();$this->Ln();
   $this->Ln();
   $this->Ln();
   $this->destino = $_GET['destino'];
   $this->destinatario = $_GET['destinatario'];
   if($this->destino==1) {
    $dest = "A QUIEN PUEDA INTERESAR";
    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','B',12);
    $this->Cell(180,7,$dest,0,1,'C');
  }else{ 
		//$dest = '';
    $this->Ln();
    $this->Ln();
    $this->Cell(180,7,utf8_decode('Atención:'),0,1,'L');
    $this->Cell(180,7,''.utf8_decode(strtoupper($this->destinatario)),0,1,'L');
  }
  $this->Ln();
}

function Detail(){
  $db     = "sidial";
  $pdf->tipo   = $_GET['tipo'];
  $pdf->tipop   = $_GET['tipop'];
  $pdf->cou  = $_GET['cou'];
  if($pdf->tipo=='C'){
    $sql =  "SELECT  *, convert( char(10), FE_INGRESO,103 ) as FE_INGRESO, convert( char(10), FE_JUBILACION,103 ) as FE_JUBILACION,     
    (select  VW_SUMA_ASIGNACIONES_PARALELA.SUMA_ASIGNACIONES  from VW_SUMA_ASIGNACIONES_PARALELA where VW_SUMA_ASIGNACIONES_PARALELA.CE_TRABAJADOR=$this->cedula  and VW_SUMA_ASIGNACIONES_PARALELA.ANO=VW_SUMA_ASIGNACIONES.ANO  and VW_SUMA_ASIGNACIONES_PARALELA.MES=VW_SUMA_ASIGNACIONES.MES and VW_SUMA_ASIGNACIONES_PARALELA.PRINCIPAL=VW_SUMA_ASIGNACIONES.PRINCIPAL) as PARALELA
    FROM VW_SUMA_ASIGNACIONES  
    WHERE VW_SUMA_ASIGNACIONES.CE_TRABAJADOR =$this->cedula and VW_SUMA_ASIGNACIONES.PRINCIPAL='S'";
  }else{
   $sql =  "SELECT  *,  convert( char(10), FE_INGRESO,103 ) as FE_INGRESO, convert( char(10), FE_JUBILACION,103 ) as FE_JUBILACION,      
   (select  VW_SUMA_ASIGNACIONES_PARALELA.SUMA_ASIGNACIONES  from VW_SUMA_ASIGNACIONES_PARALELA where VW_SUMA_ASIGNACIONES_PARALELA.CE_TRABAJADOR=$this->cedula  and VW_SUMA_ASIGNACIONES_PARALELA.ANO=VW_SUMA_ASIGNACIONES.ANO  and VW_SUMA_ASIGNACIONES_PARALELA.MES=VW_SUMA_ASIGNACIONES.MES  and VW_SUMA_ASIGNACIONES_PARALELA.CO_TIPOPERSONAL='$this->tipop' and VW_SUMA_ASIGNACIONES_PARALELA.CO_UBICACION=$this->cou ) as PARALELA
   FROM VW_SUMA_ASIGNACIONES  
   WHERE VW_SUMA_ASIGNACIONES.CE_TRABAJADOR =$this->cedula 
   and VW_SUMA_ASIGNACIONES.CO_TIPOPERSONAL='$this->tipop' and VW_SUMA_ASIGNACIONES.CO_UBICACION=$this->cou 
   ";
 }


 $ds     = new DataStore($db, $sql);
 $i=$ds->getNumRows();
 if($i<=0){
  $this->SetFont('Arial','B',14);
  $this->Cell(180,10,'No hay datos para la consulta',0,0,'C');
}else{
  $fila = $ds->getValues(0);


  $fil['SUMA_TOTAL']=$fila['SUMA_ASIGNACIONES']+$fila['PARALELA']+0.01;

  $this->SetFont('Arial','',11);
  $this->MultiCell(180,7,'     La suscrita, Directora de Recursos Humanos de la Universidad del Zulia, por medio de la presente hace constar que el (la) ciudadano(a): ');

 //  $this->MultiCell(180,7,'     El suscrito, Director(E) de Recursos Humanos de la Universidad del Zulia, por medio de la presente hace constar que el (la) ciudadano(a): ');


  $this->SetFont('Arial','B',11);
  $this->Cell(180,8,($fila['NOMBRES']). "   C.I. ".number_format($fila['CE_TRABAJADOR'], 0, ',', '.'),0,1,'C');
  $this->SetFont('Arial','',11);

  $personal=substr($fila['CO_TIPOPERSONAL'],0,1);
  $condicion=	substr($fila['CO_TIPOPERSONAL'],3,1);	
  $V=new EnLetras();
      if($personal<2){// Docentes
			if( $condicion=='8' or $condicion=='9'){//personal jubilado

       
       $cadena='prestó sus servicios en esta casa de estudios desde el '.($fila['FE_INGRESO']).' hasta el '.($fila['FE_JUBILACION']).', ubicado actualmente como '.$fila['DE_TIPOPERSONAL'].' en la categoría de '.$fila['DE_CAT_DED'].' adscrito a '.$fila['DE_UBICACION'].', con una pensión mensual de BsF. '.number_format($fil['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'],2), "Bolívares")).'.) y un estimado anual de BsF. '.number_format($fil['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'] * 18,2), "Bolívares")).')';

				}else{ // personal activo
       
      $cadena='presta sus servicios en esta casa de estudios desde el '.($fila['FE_INGRESO']).', ubicado actualmente como '.$fila['DE_TIPOPERSONAL'].' en la categoría de '.$fila['DE_CAT_DED'].' adscrito a '.$fila['DE_UBICACION'].', con un sueldo mensual de BsF. '.number_format($fil['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'],2), "Bolívares")).'.) y un estimado anual de BsF. '.number_format($fil['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'] * 18,2), "Bolívares")).')';

       }
				}else{// administrativos y Obreros
			if( $condicion=='8' or $condicion=='9'){//personal jubilado
       
       $cadena='prestó sus servicios en esta casa de estudios desde el '.($fila['FE_INGRESO']).' hasta el '.($fila['FE_JUBILACION']).', y actualmente pertenece al personal '.$fila['DE_TIPOPERSONAL'].' desempeñando el cargo de '.$fila['DE_CARGO'].' en la (el) '.$fila['DE_UBICACION'].', con una pensión mensual de BsF. '.number_format($fil['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'],2), "Bolívares")).'.) y un estimado anual de BsF. '.number_format($fil['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'] * 18,2), "Bolívares")).')';

				}else{ // personal  activo
         
      $cadena='presta sus servicios en esta casa de estudios desde el '.($fila['FE_INGRESO']).', y actualmente pertenece al personal '.$fila['DE_TIPOPERSONAL'].' desempeñando el cargo de '.$fila['DE_CARGO'].' en la (el) '.$fila['DE_UBICACION'].', con un sueldo mensual de BsF. '.number_format($fil['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'],2), "Bolívares")).'.) y un estimado anual de BsF. '.number_format($fil['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper($V->ValorEnLetras(round($fil['SUMA_TOTAL'] * 18,2), "Bolívares")).')';

       }		
     }	



     $this->MultiCell(180,7,utf8_decode($cadena));



     $this->Ln();
     $fecha = date("d-m-y");
     $fe_letras = fecha_texto(date("Y-m-d"));
     $this->Cell(180,7,utf8_decode('Constancia que se expide a petición de la parte interesada el a '.$fe_letras),0,1,'L');
     $this->Ln();
     $this->Ln();
    $this->Image("images/firmaIxora.png", 95, null,30,0,"png");
    // $this->Image("images/firma_corena.png", 95, null,30,0,"png");
     
     $this->Cell(180,3,utf8_decode('Dra. Ixora Gómez'),0,1,'C');
   }

 }
    //Pie de p�gina

 function Footer() {
  $this->SetY(-60);
  $codigoBarras = new CodigoBarras();
  $codigoBarras->setCodigoBarras("CT",$this->id,date("d/m/Y"));
  $this->SetFont('Arial','B',10);
  $this->Cell(90,4,'ID digital: '.$codigoBarras->getCodigo(),0,0,'L');
  $this->Cell(90,4,utf8_decode('Fecha de impresión: ').$codigoBarras->getFecha(),0,0,'R');
  $this->Ln();
  $this->Image($codigoBarras->getHTMLImage(), 90, null,40,0,"png");
  $this->SetFont('Arial','I',7);
  $this->Cell(180,3,'Para confirmar la validez de este documento, ingrese en nuestro sitio web https://www.servicios.luz.edu.ve/Validador/  y siga las instrucciones',0,1,'C');
  $this->Image('images/pie_constacia.jpg',20,250,190);
     //   $this->Cell(180,3,'o comuniquese al n�mero telefonico: (0261) 412-4510   R.I.F. G200088060',0,1,'C');
		//$this->Ln();
        //$this->Cell(180,3,'La informaci�n mostrada en este reporte es personal.',0,1,'C');
      // $this->Cell(180,3,'www.rrhh.luz.edu.ve',0,1,'R');
}
}

//Creaci�n del objeto de la clase heredada
$pdf=new PDF('P','mm','letter');
if(empty($_GET["cedula"])){
  $pdf->cedula = $_SESSION["cedula"];
}else{
  $pdf->cedula = $_GET["cedula"];
}

$pdf->id     = $_SESSION["cod"];
$pdf->tipo   = $_GET['tipo'];
$pdf->tipop   = $_GET['tipop'];
$pdf->cou  = $_GET['cou'];
$pdf->destino = $_GET['destino'];
$pdf->destinatario = $_GET['destinatario'];

$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("RRHH");
$pdf->SetSubject("Constancia de Trabajo");
$pdf->SetLeftMargin(20);
//$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

