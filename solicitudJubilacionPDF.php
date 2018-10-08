<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once('app_class/DataStore.class.php');
include_once('includes/Funciones.inc.php');
session_start();

class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
		$this->SetFont('helvetica', '', 9);
       /* $this->Cell(180,5,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$this->SetFont('helvetica', 'B', 9);
        $this->Cell(60,5,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $this->Image('images/logo_bn_luz_peq.jpg',45,15,10);
		$this->SetFont('helvetica', '', 9);
		$this->Ln(12);
        $this->Cell(180,5,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');*/
		   $this->Image('images/encabezado_aniversario.jpg',15,5,100);
        $this->Ln(40);
		$this->SetFont('helvetica', 'B',10);
	//	$this->SetFont('helvetica', 'I', 10);
		$this->Cell(180,5,utf8_encode("FORMATO UNICO DE SOLICITUD DE JUBILACION"),0,1,'C');
		$this->SetFont('helvetica', 'I', 8);
		$this->Cell(180,5,utf8_encode("(Personal Administrativo y Obrero)"),0,1,'C');
	}
	
	public function Footer() {
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
		$this->id = str_pad($_SESSION['solicitud'], 8, "0", STR_PAD_LEFT);
		$this->SetY(-50);
		$this->Cell(40,5,utf8_encode("Fecha de Impresion: ".date("d/m/Y").""),0,1,'L');
			//$this->SetXY(170,-55);
			
		$this->write1DBarcode( $this->id, 'C128B', '', '', 30, 15, 0.4, $style, 'R');
		
		$this->Image('images/pie_aniversario.jpg',10,260,190);
		/*$this->Cell(60,40,'FOTOCOPIE DENTRO DEL RECUADRO LA CEDULA DE IDENTIDAD',1,0,1,'C');
		$this->SetY(-55);
		$this->SetX(80);
		$this->SetFont('helvetica', 'I', 5);
		$this->Cell(15,20,'',1,0,1,'C');
		$this->Ln();
		
		$this->Cell(15,2,'Huella Dactilar ',0,1,'C');
		$this->Ln(1);
		$this->SetX(80);
		$this->Cell(15,2,'Pulgar Derecho ',0,1,'C');*/
		//$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TonnyMedina');
$pdf->SetTitle('Solicitud de Jubilacion');
$pdf->SetSubject('reporte en linea');
$pdf->SetKeywords('jubilacion, formato, rrhh');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(20, 75, 20);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
		$pdf->cedula = $_SESSION['cedula'];
		$db     = "rrhh";
		$dbs     = "sidial";
		$fecha="";
       $datospersonales =  "select *, EDAD = datediff(day,FE_NACIMIENTO, getdate())/365 ,(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER 
	  where CE_TRABAJADOR=$pdf->cedula and  (TIPOPERSONAL  LIKE '2%' or  TIPOPERSONAL  LIKE '3%')";
	   $ds2     = new DataStore($dbs, $datospersonales);
    	if($ds2->getNumRows()<=0){
           
        } else {
		
	    $fila = $ds2->getValues(0);
		/**********************************************************************/	
	    $sql =  "select * from tra_solicitud_jubilacion as a , mst_dato_personal_sso as b where a.ce_trabajador=$pdf->cedula and a.ce_trabajador=b.ce_trabajador
";
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<=0){
     
        } else {
			$i=$ds->getNumRows();
			$row = $ds->getValues(0);
			$fecha=$row['fe_solicitud'];
		$html='<div style="text-align:justify;" >Yo, <i><u>'.trim($fila['NOMBRES']).'</u> </i>, titular de la cédula de identidad: <i><u>'.trim($fila['CE_TRABAJADOR']).'</u> </i>, cumpliendo como están los requisitos exigidos por la Convención Colectiva vigente, solicito el beneficio de jubilación a partir del: '.cambiaf_a_normal($row['fecha_efectiva']).'.
		</div>';
		$arraytipo=array(1=>"DOCENTE",2=>"ADMINISTRATIVO",3=>"OBRERO");
		$html2='<div style="text-align:justify;" ><br><table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%" bgcolor="#bbbbbb"><strong>FECHA INGRESO LUZ</strong></td>
    <td width="40%" bgcolor="#bbbbbb"><strong>FACULTAD / DEPENDENCIA</strong></td>
    <td width="40%" bgcolor="#bbbbbb"><strong>CARGO</strong></td>
  </tr>
  <tr>
    <td width="20%">'.formato_fecha($fila['FE_INGRESO']).'</td>
    <td width="40%">'.$fila['DE_UBICACION'].'</td>
    <td width="40%">'.$fila['DESCRIPCION'].'('.$fila['GDO_SALARIAL'].')</td>
  </tr>
  <tr bgcolor="#bbbbbb">
    <td width="20%"><strong>FECHA NACIMIENTO</strong></td>
    <td width="40%"><strong>EDAD</strong></td>
    <td width="40%"><strong>TIPO PERSONAL</strong></td>
  </tr>
  <tr>
    <td width="20%">'.formato_fecha($fila['FE_NACIMIENTO']).'</td>
    <td width="40%">'.$fila['EDAD'].'</td>
    <td width="40%">'.$arraytipo[substr($fila['TIPOPERSONAL'],0,1)].'</td>
  </tr>
</table><br></div>';
$arrayVal=array("s"=>"SI","n"=>"NO");
/***************************************************************/
   		$id=$row['id_solicitud'];
		$sqldetalle =  "select * from mst_detalle_jubilacion where id_solicitud=$id";
        $dsd     = new DataStore($db, $sqldetalle);
    	if($dsd->getNumRows()<=0){
     
        } else {
			$j=$dsd->getNumRows();
			$k=0;
			$comienzo='<br><table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%" bgcolor="#bbbbbb"><strong>ORGANISMO</strong></td>
	
    <td width="15%" bgcolor="#bbbbbb"><strong>DESDE</strong></td>
    <td width="15%" bgcolor="#bbbbbb"><strong>HASTA</strong></td>
  </tr>';
  $cadena='';
			while($k < $j){
				$value = $dsd->getValues($k);
				$contenido=' <tr>
    <td width="70%">'.utf8_decode($value['institucion']).'</td>
    
	<td width="15%">'.cambiaf_a_normal($value['fe_desde']).'</td>
    <td width="15%">'.cambiaf_a_normal($value['fe_hasta']).'</td>
  </tr>';
			$cadena=$cadena .''.$contenido;	
				$k++;
				}
				$cadena=$cadena.'</table>';
			
		}
/***************************************************************/
$html3='<div style="text-align:justify;" >Asimismo declaro que:<br>
a) <u>'.$arrayVal[$row['concurso']].'</u> me encuentro actualmente postulado (a) para concurso de credenciales en LUZ.<br>
b) <u>'.$arrayVal[$row['admon_publica']].'</u> presté servicios en la Administración Pública.<br>
'.($row['admon_publica']=='s'? $comienzo.''.$cadena :'' ).'
</div><br><table width="100%" border="1">
  <tr bgcolor="#bbbbbb">
    <td colspan="3" width="100%"><div align="center"><strong>DIRECCIÓN DE HABITACIÓN:</strong></div></td>
  </tr>
  <tr> 
    <td colspan="3">'.$row['direccion'].'</td>
  </tr>
  <tr bgcolor="#bbbbbb">
    <td  width="34%"><strong>TELÉFONO DE HABITACIÓN:</strong></td>
    <td width="33%"><strong>TELÉFONO DE CELULAR:</strong></td>
    <td width="33%"><strong>CORREO ELECTRÓNICO:</strong></td>
  </tr>
  <tr>
    <td width="34%">'.$row['telefono_local'].'</td>
    <td width="33%">'.$row['telefono_celular'].'</td>
    <td width="33%">'.$row['email'].'</td>
  </tr>
</table>';
$html4='<br><strong>Nota:</strong><br/>* Por tratarse de un beneficio requerido en línea, con acceso exclusivo del trabajador cuya clave es confidencial, no requiere firma del interesado por lo que la solicitud se procesará automáticamente por esta dirección.
<br/>* En caso de requerir le sea considerada la labor prestada en la Administración Pública para efectos de jubilación, es indispensable consignar en original y actualizado el Antecedente de Servicio (FP-023) que indique además si devengó o no pago por concepto de Prestaciones Sociales, de ser afirmativo, el citado documento debe señalar el monto percibido por dicho concepto.<br/>
* En caso de contar con años de servicios bajo el programa Beca-Empleo (DIDSE) anexar: original de la constancia emitida por la Direcci&oacute;n de Desarrollo y Servicio Estudiantil<br/>* Anexar copia legible de la cédula de identidad.<br/>';
$html5='<div align="center">___________________________________<br/>'.trim($fila['NOMBRES']).'<br/>C.I:'.trim($fila['CE_TRABAJADOR']).'</div>';
			
		}
	/**********************************************************************/
	
		}
		

$pdf->SetFont('helvetica', '',9);
$pdf->AddPage();



$pdf->Ln();$pdf->Ln();
$pdf->Ln();
$pdf->writeHTMLCell  (175, 0, 20, 55, utf8_encode($html), 0, 0, 0, true); 
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTMLCell  (175, 0, 20, 65, utf8_encode($html2), 0, 0, 0, true); 
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTMLCell  (175, 0, 20, 100, utf8_encode($html3), 0, 0, 0,1 ,true);
$pdf->SetFont('helvetica', '', 9); 
$pdf->writeHTMLCell  (175, 0, 20, 170, utf8_encode($html4), 0, 0, 0,1 ,true); 
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTMLCell  (175, 0, 20, 245, utf8_encode($html5), 0, 0, 0,1 ,true); 
$pdf->writeHTMLCell  (175, 0, 20, 270, 'Fecha de Solicitud:'.cambiaf_a_normal($fecha) , 0, 0, 0,1 ,true); 


$pdf->Ln(20);


//$pdf->Cell(180,5,'Atentamente.',0,1,'C');
/*$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
//$pdf->Image("images/firma_ ixoragomez.jpg",80,200,50);
$pdf->Cell(180,3,'Mgr. Gómez Ixora',0,1,'C');
$pdf->Cell(180,5,'Directora de Recursos Humanos ',0,1,'C');
$pdf->Ln();
$pdf->Ln();*/
//$pdf->Cell(80,5,'RMM/YG',0,1,'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->lastPage();
$pdf->Output("".$pdf->cedula.".pdf",'I');


?>