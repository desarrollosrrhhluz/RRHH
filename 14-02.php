<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once('../FrameWork/Class/DataStore.class.php');
include_once('includes/Funciones.inc.php');
session_start();

class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
		
	}
	
	public function Footer() {
		$this->SetY(-20);
		$this->SetFont('helvetica', '', 7);
		$this->Cell(170,3,utf8_encode('Av. 16 (Guajira) Esq. Calle 66 - Edificio Rectorado Planta Alta - Telfs (0261) 7598302 - 8329, Fax (0261) 7598349'),0,1,'C');
		$this->Cell(170,3,utf8_encode('Maracaibo Edo Zulia. Página WEB http://www.rrhh.luz.edu.ve E-mail: direccion@rrhh.luz.edu.ve'),0,1,'C');
		$this->Cell(170,3,utf8_encode('AL CONTESTAR SE AGRADECE HACER REFERENCIA A ESTA COMUNICACIÓN'),0,1,'C');
	
	
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TonnyMedina');
$pdf->SetTitle('forma 14-02');
$pdf->SetSubject('14-02');
$pdf->SetKeywords('14-02');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(30, 10, 30);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
		
	
		

$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();
$pdf->Bookmark(utf8_encode(trim('Forma 14-02')), 0, 0);
$pdf->Image('images/1402.png',25,8,160);
$pdf->Ln(44);
$pdf->Cell(32,7,'',0,0,'L');
$pdf->Cell(148,7,'X',0,1,'L');
$pdf->Cell(34,7,' X',0,0,'L');
$pdf->Ln(13);
$pdf->Cell(103,7,'UNIVERSIDAD DEL ZULIA',0,0,'L');
//14147261
 if(empty($_GET['cedula'])){ $pdf->cedula = $_SESSION['cedula'];}else{$pdf->cedula = $_GET['cedula'];}
 $db="rrhh";
 $db2="sidial";
 $sql =  "SELECT * FROM  public.mst_dato_personal_sso where ce_trabajador=$pdf->cedula";
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<0){
           
        } else {
		$dat = $ds->getValues(0);	
		
	if(substr($dat['tipopersonal'],0,1)==3){
		$pdf->Cell(77,7,' Z   1   4   1   4   7   2   6   1',0,1,'L');
		}else{
			$pdf->Cell(77,7,' Z   1   9   8   4   7   2   6   2',0,1,'L');
			}	
$pdf->Ln(6);



		
		if($dat['nacionalidad']=='V'){$pdf->Cell(5,7,'X',0,0,'L'); $pdf->Cell(5,7,'',0,0,'L');}
		if($dat['nacionalidad']=='E'){$pdf->Cell(5,7,'',0,0,'L'); $pdf->Cell(5,7,'X',0,0,'L');}
		$pdf->Cell(74,7,$dat['ce_trabajador'],0,0,'L');
		if($dat['nacionalidad']=='V'){$pdf->Cell(40,7,''.$dat['ce_trabajador'],0,1,'L');}
		if($dat['nacionalidad']=='E'){$pdf->Cell(40,7,''.$dat['ce_trabajador'],0,1,'L');}
		}
	$pdf->Ln(8);
	$pdf->Cell(90,9,strtoupper($dat['apellido1'].' '.$dat['apellido2'].', '.$dat['nombre1'].' '.$dat['nombre2']),0,0,'L');	
	$pdf->Cell(10,9,'  '.substr($dat['fe_nacimiento'],0,2),0,0,'L');
	$pdf->Cell(10,9,'   '.substr($dat['fe_nacimiento'],3,2),0,0,'L');
	$pdf->Cell(10,9,'  '.substr($dat['fe_nacimiento'],6,4),0,0,'L');
	if(substr($dat['tipopersonal'],3,1)==9){$pdf->Cell(25,4,'X',0,0,'R');}
	if(substr($dat['tipopersonal'],3,1)==8){$pdf->Cell(25,14,'X',0,0,'R');}
	$pdf->Ln(18);
	if($dat['sexo']=='M'){$pdf->Cell(5,9,' X',0,0,'L'); $pdf->Cell(5,9,'',0,0,'L');}
	if($dat['sexo']=='F'){$pdf->Cell(5,9,'',0,0,'L'); $pdf->Cell(5,9,' X',0,0,'L');}
	if($dat['zurdo']>0){$pdf->Cell(5,9,'  X',0,0,'L');$pdf->Cell(5,9,'',0,0,'L');}else{$pdf->Cell(5,9,'',0,0,'L'); $pdf->Cell(5,9,'  X',0,0,'L');}
	$pdf->Cell(12,9,' ',0,0,'L');
	$pdf->Cell(10,9,'  '.substr($dat['fe_ingreso'],0,2),0,0,'L');
	$pdf->Cell(10,9,'   '.substr($dat['fe_ingreso'],3,2),0,0,'L');
	$pdf->Cell(13,9,'  '.substr($dat['fe_ingreso'],6,4),0,0,'L');
	$pdf->Cell(25,9,number_format(410.87 ,2, ',', '.'),0,0,'C');
	$pdf->Cell(45,9,'ASEGURADO',0,0,'C');
	
	if(substr($dat['tipopersonal'],0,1)!=3){
	$pdf->Cell(10,9,'0300',0,1,'C');
	}else{
		$pdf->Cell(10,9,'8259',0,1,'C');	
			}
	
	$pdf->Ln(7);
	$pdf->SetFont('helvetica', '', 9);

	$pdf->Cell(150,9,$dat['direccion'],0,0,'L');
	$pdf->setPrintFooter(false);

$pdf->lastPage();
$pdf->SetMargins(20, 10, 20);


$pdf->AddPage();
$pdf->Bookmark(utf8_encode('Constacia de Trabajo'), 0, 0);
 $pdf->SetFont('helvetica', '', 12);
		$pdf->Ln();
        $pdf->Cell(100,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $pdf->Image('images/logo_bn_luz_peq.jpg',45,35,20);
		$pdf->SetFont('helvetica', '', 12);
		$pdf->Ln(28);
        $pdf->Cell(100,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
		$pdf->Ln(10);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->Cell(170,5,'A QUIEN PUEDA INTERESAR',0,0,'C');
		$pdf->SetFont('helvetica', '', 11);
		$dbs     = "sidial";
        $sqls =  " SELECT dbo.VW_SUMA_ASIGNACIONES.CE_TRABAJADOR,   
         dbo.VW_SUMA_ASIGNACIONES.NOMBRES,   
         dbo.VW_SUMA_ASIGNACIONES.FE_JUBILACION,   
         dbo.VW_SUMA_ASIGNACIONES.FE_INGRESO,   
         dbo.VW_SUMA_ASIGNACIONES.DE_CARGO,   
         dbo.VW_SUMA_ASIGNACIONES.SUMA_ASIGNACIONES,   
         dbo.VW_SUMA_ASIGNACIONES.DE_UBICACION,   
         dbo.VW_SUMA_ASIGNACIONES.DE_TIPOPERSONAL,   
         dbo.VW_SUMA_ASIGNACIONES.MES,   
         dbo.VW_SUMA_ASIGNACIONES.ANO,   
         dbo.VW_SUMA_ASIGNACIONES.PRINCIPAL,   
         dbo.VW_SUMA_ASIGNACIONES.RIF,   
         dbo.VW_SUMA_ASIGNACIONES.CO_UBICACION,   
         dbo.VW_SUMA_ASIGNACIONES.F_NOMINA,   
         dbo.VW_SUMA_ASIGNACIONES.DEDICACION,   
         dbo.VW_SUMA_ASIGNACIONES.CATEGRADO,   
         dbo.VW_SUMA_ASIGNACIONES.CO_TIPOPERSONAL,   
         dbo.VW_SUMA_ASIGNACIONES.DE_CAT_DED,   
         (select VW_SUMA_ASIGNACIONES_PARALELA.SUMA_ASIGNACIONES  from VW_SUMA_ASIGNACIONES_PARALELA where VW_SUMA_ASIGNACIONES_PARALELA.CE_TRABAJADOR=dbo.VW_SUMA_ASIGNACIONES.CE_TRABAJADOR  and VW_SUMA_ASIGNACIONES_PARALELA.ANO=dbo.VW_SUMA_ASIGNACIONES.ANO  and VW_SUMA_ASIGNACIONES_PARALELA.MES=dbo.VW_SUMA_ASIGNACIONES.MES) as PARALELA 
    FROM dbo.VW_SUMA_ASIGNACIONES  
   WHERE ( dbo.VW_SUMA_ASIGNACIONES.CE_TRABAJADOR = $pdf->cedula ) ";
        $dss     = new DataStore($dbs, $sqls);
    	if($dss->getNumRows()<=0){
           
        } else {
            $fila = $dss->getValues(0);
			$fila['SUMA_TOTAL']=$fila['SUMA_ASIGNACIONES']+$fila['PARALELA'];
			 $pdf->Ln(15);
			 $pdf->MultiCell(170,8,utf8_encode('     La suscrita, Directora de Recursos Humanos de la Universidad del Zulia, por medio de la presente hace constar que el (la) ciudadano(a):                                      '), 0, 'J', 0, 1, '', '', true);
			 $pdf->SetFont('helvetica', 'B', 11);
			 $pdf->Cell(170,5,utf8_encode($fila['NOMBRES']). "   C.I. ".number_format($fila['CE_TRABAJADOR'], 0, ',', '.'),0,1,'C');
			 $pdf->SetFont('helvetica', '', 11);
			if(substr($fila['CO_TIPOPERSONAL'],0,1)==1){
			if(substr($fila['CO_TIPOPERSONAL'],3,1)==8 or substr($fila['CO_TIPOPERSONAL'],3,1)==9){
			$pdf->MultiCell(170,8,utf8_encode('prestó sus servicios en esta casa de estudios desde el '.formato_fecha($fila['FE_INGRESO']).' hasta el '.formato_fecha($fila['FE_JUBILACION']).', ubicado actualmente como '.$fila['DE_TIPOPERSONAL'].' en la categoría de '.$fila['DE_CAT_DED'].' adscrito a '.$fila['DE_UBICACION'].', con una asignación mensual de BsF. '.number_format($fila['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL'],2))).' BsF.) y un estimado anual de BsF. '.number_format($fila['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL']*18,2))).' BsF.)                                                                       '), 0, 'J', 0, 1, '', '', true);
			
			}else{
			$pdf->MultiCell(170,8,utf8_encode('presta sus servicios en esta casa de estudios desde el '.formato_fecha($fila['FE_INGRESO']).', ubicado actualmente como '.$fila['DE_TIPOPERSONAL'].' en la categoría de '.$fila['DE_CAT_DED'].' adscrito a '.$fila['DE_UBICACION'].', con una asignación mensual de BsF. '.number_format($fila['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL'],2))).' BsF.) y un estimado anual de BsF. '.number_format($fila['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL']*18,2))).' BsF.)                                                                       '), 0, 'J', 0, 1, '', '', true);
			
			
			
			}
			}
			
		if(substr($fila['CO_TIPOPERSONAL'],0,1)==2 or substr($fila['CO_TIPOPERSONAL'],0,1)==3 ){
			if(substr($fila['CO_TIPOPERSONAL'],3,1)==8 or substr($fila['CO_TIPOPERSONAL'],3,1)==9){
			$pdf->MultiCell(170,8,utf8_encode('prestó sus servicios en esta casa de estudios desde el '.formato_fecha($fila['FE_INGRESO']).' hasta el '.formato_fecha($fila['FE_JUBILACION']).', y actualmente pertenece al personal '.$fila['DE_TIPOPERSONAL'].' desempeñando el cargo de '.$fila['DE_CARGO'].' en la (el) '.$fila['DE_UBICACION'].', con una asignación mensual de BsF. '.number_format($fila['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL'],2))).' BsF.) y un estimado anual de BsF. '.number_format($fila['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL']*18,2))).' BsF.)                                                                       '), 0, 'J', 0, 1, '', '', true);
			
			}else{
			$pdf->MultiCell(170,8,utf8_encode('presta sus servicios en esta casa de estudios desde el '.formato_fecha($fila['FE_INGRESO']).', y actualmente pertenece al personal '.$fila['DE_TIPOPERSONAL'].' desempeñando el cargo de '.$fila['DE_CARGO'].' en la (el) '.$fila['DE_UBICACION'].', con una asignación mensual de BsF. '.number_format($fila['SUMA_TOTAL'],2, ',', '.').'  ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL'],2))).' BsF.) y un estimado anual de BsF. '.number_format($fila['SUMA_TOTAL'] * 18, 2, ',', '.').' ('.strtoupper(convertir_a_letras(redondear($fila['SUMA_TOTAL']*18,2))).' BsF.)                                                                       '), 0, 'J', 0, 1, '', '', true);
			
			}
			}
	$pdf->Ln(10);
	
	 $fe_letras = fecha_texto(date("Y-m-d"));
	 $pdf->Cell(170,5,utf8_encode('Constancia que se expide a petición de la parte interesada el día '.$fe_letras),0,0,'C');
	 $pdf->Ln(15);
	 $pdf->Image('images/firma_ ixoragomez.jpg',85,175,50);
	 $pdf->Ln(25);
	 $pdf->Cell(170,5,utf8_encode('Mgr. Gómez Ixora'),0,0,'C');
	$pdf->setPrintFooter(true);

    	
		}
$pdf->AddPage();
$pdf->Bookmark(utf8_encode('Requisitos'), 0, 0);
        $pdf->SetFont('helvetica', '', 12);
		$pdf->Ln(6);

        $pdf->Cell(100,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $pdf->Image('images/logo_bn_luz_peq.jpg',45,30,20);
		$pdf->SetFont('helvetica', '', 12);
		$pdf->Ln(28);
        $pdf->Cell(100,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
		$pdf->Ln(7);
	    $pdf->Cell(170,5,'Maracaibo, '.fecha_oficio().'',0,1,'R');
		$datospersonales =  "SELECT * FROM V_DATPER WHERE CE_TRABAJADOR = $pdf->cedula";
	    $ds2     = new DataStore($db2, $datospersonales);
    	if($ds2->getNumRows()<=0){
           
        } else {
	   $dat = $ds2->getValues(0);
	  $html='<br><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr bgcolor="#BBBBBB">
   <td width="12%"><strong>Cedula</strong></td>
    <td width="50%"><strong>Nombres</strong></td>
    <td width="15%"><strong>Tipo Personal </strong></td>
    <td width="11%"><strong>Ubicacion</strong></td>
    <td width="12%"><strong>Pagina</strong></td>
  </tr>
  <tr>
    <td width="12%">'.$pdf->cedula.'</td>
    <td width="50%">'.$dat['NOMBRES'].'</td>
    <td width="15%">'.$dat['TIPOPERSONAL'].'</td>
    <td width="11%">'.number_format($dat['CO_UBICACION'], 0, ',', '').'</td>
	<td width="12%">'.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages().'</td>
  </tr>
</table><br>';
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTMLCell  (0, 0, 20, 80, utf8_encode($html), 0, 0, 0, true); 
$pdf->Ln(30);
$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Una forma 14-02 (original y dos copias). ',0,0,'L');$pdf->Ln(7);

$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Tres copias de la cedula de identidad ampliada y centrada',0,0,'L');$pdf->Ln(7);

/*$fecha=formato_fecha_aaaa($dat['FE_NACIMIENTO']);
if($dat['SEXO']=='F' and edad_mes($fecha)>=55 ){
$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Acta de nacimiento o datos filiatorios original y dos copias',0,0,'L');$pdf->Ln(7);
}
if($dat['SEXO']=='M' and edad_mes($fecha)>=60 ){
$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Acta de nacimiento o datos filiatorios original y dos copias',0,0,'L');$pdf->Ln(7);
}
*/$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Dos carpetas manila oficio ',0,0,'L');$pdf->Ln(7);
$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Constancia de trabajo actualizada',0,0,'L');$pdf->Ln(7);
$pdf->Cell(5,5,'',1,0,'L'); $pdf->Cell(5,5,'Cuenta individual',0,0,'L');$pdf->Ln(7);
$pdf->Ln(7);
$pdf->Ln(20);

$pdf->MultiCell(170,8,utf8_encode('La presente constituye un certificado provisional de recepción de documentación, una vez firmada, sellada y validada por el IVSS la podrá  retirar en un lapso de 30 días hábiles en la oficina de Atención al Adulto Mayor, antigua sede rectoral(plata baja).                                                       '), 0, 'J', 0, 1, '', '', true);
$pdf->Ln(30);
                $pdf->MultiCell(60,5,'_____________________' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(50,5,'' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(64,5,'_____________________' , 0, 'C', 0, 0, '', '', true);
				$pdf->Ln();	
				$pdf->MultiCell(60,5,'Firma del trabajador' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(50,5,'' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(64,5,'Firma Recibido' , 0, 'C', 0, 0, '', '', true);
				$pdf->Ln();	
				$pdf->MultiCell(60,5,'' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(50,5,'' , 0, 'C', 0, 0, '', '', true);
				$pdf->MultiCell(64,30,'Fecha Recibido: _____/_____/______' , 0, 'C', 0, 0, '', '', true);
				$pdf->setPrintFooter(false);

}
//*****************************************************************************	
$pdf->lastPage();

		
$pdf->lastPage();
//*******************************************************************************
$pdf->Output("".$pdf->cedula.".pdf",'I');

?>