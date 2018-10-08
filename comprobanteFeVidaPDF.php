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
		$this->SetFont('helvetica', '', 12);
        $this->Cell(180,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$this->SetFont('helvetica', 'B', 12);
        $this->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $this->Image('images/logo_bn_luz_peq.jpg',45,22,20);
		$this->SetFont('helvetica', '', 12);
		$this->Ln(28);
        $this->Cell(180,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
        $this->Ln();
	}
	
	public function Footer() {
	$this->SetY(-20);
		$this->SetFont('helvetica', '', 7);
		
		
		$estilo=array('width' => 0.15, 'cap' => 'square', 'join' => 'miter', 'dash' => 0);
		$this->Line(15, 273,190, 273, $estilo);
	/*	$this->Cell(170,3,utf8_encode('Av. 16 (Guajira) Esquina Calle 66 - Edificio Antigua Sede Rectorado Planta Alta. Telfs (0261) 4124504'),0,1,'C');
		$this->Cell(170,3,utf8_encode('Maracaibo Edo Zulia. Página WEB http://www.rrhh.luz.edu.ve E-mail: direccion@rrhh.luz.edu.ve'),0,1,'C');
		$this->Cell(170,3,utf8_encode('AL CONTESTAR SE AGRADECE HACER REFERENCIA A ESTA COMUNICACIÓN'),0,1,'C');*/
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-TONNY MEDINA');
$pdf->SetTitle('comprobante fe de vida ');
$pdf->SetSubject('comprobante fe de vida');
$pdf->SetKeywords('comprobante fe de vida');

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

 
	
$db="sidial";

$ci=$_SESSION['cedula'];

$datospersonales =  "select *, 
(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION ,
(select DESCRIPCION from TAB_TIPO_PERSONAL where TIPOPERSONAL=V_DATPER.TIPOPERSONAL) as DE_TIPOPERSONAL 
from V_DATPER where CE_TRABAJADOR= $ci ";
     $ds2     = new DataStore($db, $datospersonales);
      
    if($ds2->getNumRows()<=0){
           
        } else {
    $fil=$ds2->getNumRows();  
     $dat = $ds2->getValues(0);
     if($fil>1){$descr="DOBLE UBICACIÓN";};
     $arraytipo=array(1=>"Docente",2=>"Administrativo",3=>"Obrero");
     $html='<div align="center"><h2>Comprobante de Fe de Vida</h2></div><br/><table width="100%" border="1" cellspacing="0" cellpadding="0">
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


            }


$tipo=['FP'=>'Fecha de Presencia', 'VD'=>'Fecha Visita Domiciliaria', 'VH'=>'Fecha Visita Hospitalaria', 'FV'=>'Fecha Entrega Fe de Vida', 'CV'=>'Fecha Constancia de Viudez', 'CS'=>'Fecha Constancia Solteria', 'CE'=>'Fecha Constancia de Estudio'];

$sql2 =  "select *,convert(char(10), FECHA, 103) as FE_VIDA from VW_FE_DE_VIDA where CEDULA=$ci";
        $ds     = new DataStore($db, $sql2);
        $i=$ds->getNumRows();
        $j=0;
      if($i==0){    

    }else{
  
        $html.= "<br><h4>Sus registros de fe de vida son los siguientes </h4><br>";
      while ( $j < $i) {
        $row = $ds->getValues($j);

        $html.= "- <b>".$row['FE_VIDA']." ".$tipo[$row['CODIGO']]."</b><br>";



        $j++;
      }
    }

$pdf->SetFont('helvetica', '', 10);			
$pdf->writeHTMLCell  (180, 0, 20, 60, utf8_encode($html).'<br/><div align="center">* Conserve este Comprobante como garantia de haber presentado su fe de vida, <u><b>NO</b></u> debe ser consigando en la Direccion de Recursos Humanos<br></div>', 0, 0, 0, true); 


$pdf->setPrintFooter(true);
$pdf->Output("FedeVida_".$cedula."_".date("Y-m-d").".pdf",'I');
?>
