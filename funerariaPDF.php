<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');;
//include_once('./includes/Funciones.inc.php');
session_start();
class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
		$this->SetFont('helvetica', '', 12);
     /*   $this->Cell(180,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$this->SetFont('helvetica', 'B', 12);
        $this->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $this->Image('images/logo_bn_luz_peq.jpg',45,22,20);
		$this->SetFont('helvetica', '', 12);
		$this->Ln(28);
        $this->Cell(180,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');*/
        $this->Image('images/encabezado_aniversario.jpg',15,5,90);
        // $this->Image('images/logo_funeraria.jpg',150,15,45);
        $this->Ln();
        //

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
  
    $this->SetY(-23);
    $this->SetFont('helvetica', 'I', 7);
  //$this->codigo = $_GET['pass'];
    $this->id = $_GET['id'];
    //$this->anno = $_GET['anno'];
    //$this->Cell(179, 10, $this->codigo, 0, 0, 'R');
    $this->write1DBarcode($this->codigo = $_SESSION['cedula'], 'C128B', '', '', 30, 15, 0.4, $style, 'N');
    $this->SetFont('helvetica', 'I', 8);


	$this->SetY(-20);
		$this->SetFont('helvetica', '', 7);	
		$estilo=array('width' => 0.15, 'cap' => 'square', 'join' => 'miter', 'dash' => 0);
		$this->Line(15, 273,190, 273, $estilo);


	//	$this->Cell(180,3,utf8_encode('Sector Santa Maria Calle 70, entre av 25 y 26 al lado de la Iglesia San Alfonso'),0,1,'C');
		//$this->Cell(180,3,utf8_encode('Telefonos: (0261) 7511358  -  7514705'),0,1,'C');
	//	$this->Cell(180,3,utf8_encode('Maracaibo - Edo Zulia'),0,1,'C');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-TONNY MEDINA');
$pdf->SetTitle('Servicios Funerarios LUZ - '. date("Y"));
$pdf->SetSubject('Ayuda');
$pdf->SetKeywords('Servicios Funerarios LUZ '. date("Y"));

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

    $html='<div align="center"><h2>Solicitud de Servicios Funerarios Colectivos</h2></div>';



    $parent=["D"=>"Hijo(a)","A"=>"Padre/Madre", "C"=>"Conyuge", "DM"=>"Hijo(a)", "H"=>"Hemano(a)", "DH"=>"Sobrino(a)", "S"=>"Suegro(a)", "N"=>"Nieto(a)"];

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
      $html.='<br/><table width="100%" border="1" cellspacing="0" cellpadding="0">
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
    </table><br><div align="center"><h2>GRUPO FAMILIAR</h2></div>';
    $tipop=substr($dat['TIPOPERSONAL'],0,1);
    $sexo=$dat['SEXO'];

  }




 $cadena='<table width="100%"  cellspacing="3" cellpadding="3" > 
 <tr> 
 <th width="5%"  bgcolor="#bbbbbb" aling="center"><b> # </b></th> 
 <th width="45%" bgcolor="#bbbbbb" aling="center"><b>Nombres y Apellidos</b></th> 
 <th width="15%" bgcolor="#bbbbbb" aling="center"><b>Cedula</b></th> 
 <th width="20%" bgcolor="#bbbbbb" aling="center"><b>Parentesco</b></th> 
 <th width="15%" bgcolor="#bbbbbb" aling="center"><b>Edad</b></th> 
 </tr>';


 $cant=1;
 $sqlP="SELECT id_familiar,nombres,ce_familiar,sexo,fe_nacimiento ,parentesco,
EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad
 FROM admon_personal.mst_familiares_beneficios where   nuevo!=1 
 and estatus=1 
 and (ce_trabajador=$cedula or ce_otro_padre=$cedula)
 and id_familiar in  (select id_familiar  from admon_personal.tab_detalle_funeraria as a, admon_personal.tra_planilla_funeraria as b 
where a.id_planilla=b.id
and b.ce_trabajador=$cedula
and a.tab_origen='mst_familiares_beneficios'
and a.id_familiar=admon_personal.mst_familiares_beneficios.id_familiar
and b.anno=2017)
 order by id_familiar";

    $ds     = new DataStore($db, $sqlP);
     
      if($ds->getNumRows()<=0){
    
      }else{
       
     $i=0;
        $j=$ds->getNumRows();
        while($i<$j){
          $row=$ds->getValues($i);

          $cadena.='<tr  > 
          <td width="5%" align="center">'.($cant++).'</td> 
          <td width="45%"  >'.$row['nombres'].'</td> 
          <td width="15%" align="center">'.$row['ce_familiar'].'</td> 
          <td width="20%" align="center" >'.$parent[$row['parentesco']].'</td> 
          <td width="15%" align="center" >'.$row['edad'].'</td> 
          </tr>';

          $i++;

        }


      }

 $sqlA="SELECT id_familiar,nombres,ce_familiar,sexo,fe_nacimiento ,parentesco,
EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad
 FROM admon_personal.mst_familiares_adicionales where    
  estatus=1 
 and ce_trabajador=$cedula
 and id_familiar in  (select id_familiar  from admon_personal.tab_detalle_funeraria as a, admon_personal.tra_planilla_funeraria as b 
where a.id_planilla=b.id
and b.ce_trabajador=$cedula
and a.tab_origen='mst_familiares_adicionales'
and a.id_familiar=admon_personal.mst_familiares_adicionales.id_familiar
and b.anno=2017)
 order by id_familiar";

    $ds3     = new DataStore($db, $sqlA);
     
      if($ds3->getNumRows()<=0){
    
      }else{
        $m=0;

        $n=$ds3->getNumRows();
        while($m<$n){
          $fila=$ds3->getValues($m);

          $cadena.='<tr > 
          <td width="5%" align="center">'.($cant++).'</td> 
          <td width="45%" >'.$fila['nombres'].'</td> 
          <td width="15%" align="center">'.$fila['ce_familiar'].'</td> 
          <td width="20%" align="center" >'.$parent[$fila['parentesco']].'</td> 
          <td width="15%" align="center" >'.$fila['edad'].'</td> 
          </tr>';

          $m++;

        }


      }




$cadena.='</table>';



$legal='<div align="justify">El TITULAR BENEFICIARIO, puede gozar de la protección exequial contratada por La Universidad del Zulia, 
entendiéndose como parte integrante del grupo de beneficiarios: el titular, los padres, cónyuge o persona con la que mantiene unión estable de hecho, hijos, 
quienes tienen derecho a recibir los servicios mortuorios con una cobertura del cien por ciento (100%) del
servicio contratado: desde EL TITULAR BENEFICIARIO y diez (10) 
personas del GRUPO FAMILIAR, con atención las 24 horas desde el día 01/04/2017 al 31/12/2017 .<br>
<br>

* Los datos suministrados no podr&aacute;n ser modificados hasta el vencimiento del contrato (31/12/2017), salvo ocurra el nacimiento de un hijo realizando la participaci&oacute;n ante la Direcci&oacute;n de Recursos Humanos.<br> 
* Conserve este Comprobante como garantia de haber realizado la solicitud<br><b>Fecha de Impresi&oacute;n:</b> '.date("d/m/Y").'</div>';

				   
$pdf->SetFont('helvetica', '', 9);	


$pdf->writeHTMLCell  (180, 0, 15, 50, utf8_encode($html)." ".$cadena, 0, 0, 0, true); 



$pdf->writeHTMLCell  (180, 0, 15, 200, utf8_encode($legal).'<br><br><div align="center"></div>', 0, 0, 0, true); 



     

$pdf->setPrintFooter(true);
$pdf->Output("Planilla_Servicios_Mortuorios".$cedula."_".date("Y-m-d").".pdf",'I');
?>
