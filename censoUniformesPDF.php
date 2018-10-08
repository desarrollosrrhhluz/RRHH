<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');;
include_once('includes/Funciones.inc.php');
session_start();
class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
    
    $this->SetFont('helvetica', '', 9);
    $this->Image('./images/encabezado_aniversario.jpg',15,10,100);
        $this->Ln(35);
    $this->SetFont('helvetica', 'B',10);
  
  }

  public function Footer() {
    
    $this->SetY(-28);
    $this->SetFont('helvetica', '', 8);
    
    //$this->Cell(170,3,utf8_encode('MM/RN/TM'),0,1,'L');
    $this->Ln();
    $this->Image('./images/pie_constacia.jpg',15,265,190);

  
  }
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-TONNY MEDINA');
$pdf->SetTitle('censoUniformes '.date("Y"));
$pdf->SetSubject('Ayuda');
$pdf->SetKeywords('censoUniformes '.date("Y"));

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15,55, 15);
//$pdf->SetMargins(15, 70, 15);
//$pdf->SetMargins(13, 65, 13);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetFooterMargin(10);
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 



$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

 
		 $db= "rrhh";
		$db2="sidial";

$cedula=$_SESSION['cedula'];

  $datospersonales =  "select *, 
(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION ,
(select DESCRIPCION from TAB_TIPO_PERSONAL where TIPOPERSONAL=V_DATPER.TIPOPERSONAL) as DE_TIPOPERSONAL 
from V_DATPER where CE_TRABAJADOR= $cedula ";
	   $ds2     = new DataStore($db2, $datospersonales);
    	
		if($ds2->getNumRows()<=0){
           
        } else {
		$fil=$ds2->getNumRows();	
	   $dat = $ds2->getValues(0);
	   if($fil>1){$descr="DOBLE UBICACIÓN";};
	   $arraytipo=array(1=>"Docente",2=>"Administrativo",3=>"Obrero");
	   $html='<div align="center"><h2>CENSO DE UNIFORMES DEL PERSONAL '.date("Y").'</h2></div><br/><table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%" bgcolor="#bbbbbb"><strong>C&eacute;dula</strong></td>
    <td width="50%" bgcolor="#bbbbbb"><strong>Nombres</strong></td>
    <td width="35%" bgcolor="#bbbbbb"><strong>Tipo Personal</strong></td>
  </tr>
  <tr>
    <td width="15%">'.$cedula.'</td>
    <td width="50%">'.$dat['NOMBRES'].'</td>
    <td width="35%">'.($fil>1? $descr : $dat['DE_TIPOPERSONAL']  ).' '.($_SESSION['estatus']=='F' ?" <strong>(FALLECIDO)</strong>":"").'</td>
  </tr>
  <tr bgcolor="#bbbbbb">
    <td width="15%"><strong>C&oacute;digo Ubicaci&oacute;n</strong></td>
    <td width="50%"><strong>Ubicaci&oacute;n</strong></td>
    <td width="35%"><strong>P&aacute;gina</strong></td>
  </tr>
  <tr>
    <td width="15%">'.$dat['CO_UBICACION'].'</td>
    <td width="50%">'.$dat['DE_UBICACION'].'</td>
    <td width="35%">1</td>
  </tr>
</table>';
 $tipop=substr($dat['TIPOPERSONAL'],0,1);
 $sexo=$dat['SEXO'];

						}
			
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTMLCell  (180, 0, 14, 60, utf8_encode($html), 0, 0, 0, true); 
$pdf->Ln(30);
 $db= "rrhh";
$sql =  "select * from mst_tallas_uniformes where ce_trabajador=$cedula and fecha>='01/03/2014' ";
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<=0){
		
		  }else{
		  $rows=$ds->getValues(0);






			if( $tipop!='3' ){
			$html2='<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" bgcolor="#bbbbbb"><strong>Item</strong></td>
    <td width="50%" bgcolor="#bbbbbb"><strong>Talla</strong></td>
  </tr>
  <tr>
    <td width="50%">Camisa</td>
    <td width="50%">'.$rows['talla1'].'</td>
  </tr><tr>
    <td width="50%">Pantalon</td>
    <td width="50%">'.$rows['talla2'].'</td>
	</tr><tr>
	<td width="50%">Chaqueta</td>
    <td width="50%">'.$rows['talla3'].'</td>
  </tr>
  '.( $tipop==1 && !is_null($rows['talla4']) ? '<tr> <td width="50%">Bata</td><td width="50%">'.$rows['talla4'].'</td></tr>':'' ).'
  '.( $tipop==1 && $_SESSION['sexo']=='M' ? '<tr> <td width="50%">Corbata</td><td width="50%">UNICA</td></tr>':'' ).'
 </table>';	
				}
		/*	if($sexo=='M' and $tipop=='2' ){
				$html2='<table width="100%" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="50%" bgcolor="#bbbbbb"><strong>Item</strong></td>
    <td width="50%" bgcolor="#bbbbbb"><strong>Talla</strong></td>
  </tr>
  <tr>
    <td width="50%">Camisa</td>
    <td width="50%">'.$rows['talla1'].'</td>
  </tr><tr>
    <td width="50%">Pantalon</td>
    <td width="50%">'.$rows['talla2'].'</td>
  </tr></table>';	
				}*/	
		  	if( $tipop=='3' ){
	$html2='<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" bgcolor="#bbbbbb"><strong>Item</strong></td>
    <td width="50%" bgcolor="#bbbbbb"><strong>Talla</strong></td>
  </tr>
  <tr>
    <td width="50%">Chemise</td>
    <td width="50%">'.$rows['talla1'].'</td>
  </tr><tr>
    <td width="50%">Pantalon</td>
    <td width="50%">'.$rows['talla2'].'</td>
	</tr><tr>
	<td width="50%">Zapatos</td>
    <td width="50%">'.$rows['talla3'].'</td>
  </tr></table>';				
				}


   $censo=' - Tallas actualizadas al '.$rows['fecha'].'';     
				   }
				   
		
$pdf->writeHTMLCell  (100, 0, 50, 120, utf8_encode($html2).'<br/><div align="center">* Conserve este Comprobante como garantia de haber realizado el censo<br>'.$censo.'</div>', 0, 0, 0, true); 

$pdf->SetFont('helvetica', '', 11);
$pdf->setPrintFooter(true);
$pdf->Output("Uniformes_".$cedula."_".date("Y-m-d").".pdf",'I');
?>
