<?php

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');

error_reporting(0);
session_start();

	class MYPDF extends TCPDF {

		public $codigo;
		public $id;
		//Page header
		public function Header() {
			$this->SetFont('helvetica', '', 12);
			$this->Cell(180,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,0,'L'); 
			$this->SetFont('helvetica', '', 8);
			$this->Cell(70,5,'Maracaibo, '.fecha_oficio().'',0,1,'R');	
			$this->SetFont('helvetica', 'B', 12);
			$this->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
			$this->Image('images/logo_bn_luz_peq.jpg',45,18,15);
			$this->SetFont('helvetica', '', 12);
			$this->Ln(19);
			$this->Cell(180,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
			$this->Ln();
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
			$this->write1DBarcode($this->codigo = $_GET['id'], 'C128B', '', '', 30, 15, 0.4, $style, 'N');
			$this->SetFont('helvetica', 'I', 8);
				//
			$this->SetXY(45,-20);
			$this->SetFont('helvetica', 'B', 8);
			$this->MultiCell(80,5,' Conforme al  Sello R-00018 87 del 06/07/2011.' , 0, 'L', 0, 0, '', '', true);
			$this->Ln(3);$this->SetFont('helvetica', 'I', 8);
			$this->SetXY(45,-17);
			$this->MultiCell(235,5,utf8_encode('El Trababajdor que incurra en presentar documentaciòn adulterada, forjada o falsificada, previa comprobaciòn por autoridad competente, seràn   sancionadas  con suspensiòn del  beneficio por el lapso de dos (2) años consecutivos.                                                         ')  , 0, 'J', 0, 0, '', '', true);
		}
	}

	$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('TonnyMedina');
	$pdf->SetTitle('Universidad del Zulia');
	$pdf->SetSubject('Beneficios Contractuales');
	$pdf->SetKeywords('beneficios, personal, administrativo,obrero, luz');
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf->SetMargins(15, 45, 15);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	$pdf->setLanguageArray($l); 
	$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->id = $_GET['id'];
	
	$db  = "rrhh";
	$dbr = "rrhh";
	$db2 ="sidial";
	$sql =  "select * from admon_personal.tra_solicitud_beneficios where anno=2019 and id_solicitud in (select id_solicitud from admon_personal.beneficios_contractuales where id_familiar=$pdf->id)";
	$ds = new DataStore($db, $sql);

    if($ds->getNumRows()<=0) {
           
    } else {

    	$pdf->SetFont('helvetica', 'B', 10);
	  	$pdf->MultiCell(270,5,'COMPROBANTE DE SOLICITUD DE BENEFICIOS DEL PERSONAL (2018 - 2019)', 0, 'C', 0, 1, '', '', true);
	  
	  	$pdf->SetFont('helvetica', '', 10);
	   	$fila = $ds->getValues(0);
	   	$cedula =$fila['ce_trabajador'];
	    $id_solicitud =$fila['id_solicitud'];
	   	$datospersonales =  "select *, 
							(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION ,
							(select DESCRIPCION from TAB_TIPO_PERSONAL where TIPOPERSONAL=V_DATPER.TIPOPERSONAL) as DE_TIPOPERSONAL 
							from V_DATPER where CE_TRABAJADOR= $cedula ";
	  	$ds2 = new DataStore($db2, $datospersonales);
    	
		if($ds2->getNumRows()<=0){
           
        } else {

			$fil=$ds2->getNumRows();	
	   		$dat = $ds2->getValues(0);
	   		
	   		if($fil>1){$descr="DOBLE UBICACIÓN";};
	   		
	   		$arraytipo=array(1=>"Docente",2=>"Administrativo",3=>"Obrero");
	   		$html='<table width="100%" border="1" cellspacing="0" cellpadding="0">
					  <tr>
					    <td width="19%" bgcolor="#bbbbbb"><strong>C&eacute;dula</strong></td>
					    <td width="46%" bgcolor="#bbbbbb"><strong>Nombres</strong></td>
					    <td width="35%" bgcolor="#bbbbbb"><strong>Tipo Personal</strong></td>
					  </tr>
					  <tr>
					    <td width="19%">'.$cedula.'</td>
					    <td width="46%">'.$dat['NOMBRES'].'</td>
					    <td width="35%">'.($fil>1? $descr : $dat['DE_TIPOPERSONAL']  ).' '.($_SESSION['estatus']=='F' ?" <strong>(FALLECIDO)</strong>":"").'</td>
					  </tr>
					  <tr bgcolor="#bbbbbb">
					    <td width="19%"><strong>C&oacute;digo Ubicaci&oacute;n</strong></td>
					    <td width="46%"><strong>Ubicaci&oacute;n</strong></td>
					    <td width="35%"><strong>P&aacute;gina</strong></td>
					  </tr>
					  <tr>
					    <td width="19%">'.$dat['CO_UBICACION'].'</td>
					    <td width="46%">'.$dat['DE_UBICACION'].'</td>
					    <td width="35%">1</td>
					  </tr>
					</table>';

			$ub=number_format($dat['CO_UBICACION'],'',',','');
			$sqlxx =  "select cedula from public.mst_analistas_rrhh where dependencia=$ub";
			$dsr = new DataStore($dbr, $sqlxx);
			
			if($dsr->getNumRows()<=0) {
						   
			} else {
				
				$resultado = $dsr->getValues(0);
				$cedulaanalista=$resultado['cedula'];
				$datosanalista =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$cedulaanalista ";
	   			$dsr2 = new DataStore($db2, $datosanalista);
		    	
		    	if($dsr2->getNumRows()<=0){
		           
		        } else {

			   		$dat2 = $dsr2->getValues(0);
			   		$nombreanalista[]=$dat2['NOMBRES'];
			  	}
			}
					

			$pdf->writeHTMLCell  (270, 0, 14, 50, utf8_encode($html), 0, 0, 0, true); 
			$pdf->Ln(30);
			// fin datos personales
			$sql2 =  "SELECT *,fu_obtener_edad(fe_nacimiento,CURRENT_DATE) as edad FROM admon_personal.mst_familiares_beneficios where  parentesco='D'  and estatus=1 and id_familiar in (select id_familiar from admon_personal.beneficios_contractuales where id_solicitud=$id_solicitud)";
			$ds3  = new DataStore($db, $sql2);
			
			if($ds3->getNumRows()<=0) {
				//no tiene hijos
		  	} else {

		  		$tipopersonal=substr($dat['TIPOPERSONAL'], 0,1 );
		   		$k=$ds3->getNumRows();
				$j=0;
				$pdf->SetFont('helvetica', 'B', 10);
				$pdf->SetFillColor(187, 187, 187);
				$pdf->MultiCell(269,4,'BENEFICIOS SOLICITADOS', 1, 'C', 1, 1, '', '', true);
				$pdf->SetFont('helvetica', 'B', 8);	
				$pdf->SetFillColor(200, 193, 180);
				
				$pdf->SetFont('helvetica', 'B', 7);	
				$pdf->MultiCell(18,5,'Cedula', 1, 'J', 1, 0, '', '', true);
				$pdf->MultiCell(75,5,'Nombres', 1, 'J', 1, 0, '', '', true);
				$pdf->MultiCell(22,5,'RIF Instituto', 1, 'J', 1, 0, '', '', true);
				$pdf->MultiCell(15,5,'Edad', 1, 'J', 1, 0, '', '', true);
				$pdf->MultiCell(30,5,'Beca', 1, 'C',1, 0, '', '', true);	
				$pdf->MultiCell(18,5,'Juguetes', 1, 'C',1, 0, '', '', true);	
				$pdf->MultiCell(26,5,'Ayuda Textos', 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(32,5,'Centro Educ. Inicial', 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(33,5,'CEI Hijos Discap', 1, 'C', 1, 0, '', '', true);
				$pdf->Ln();											

				$sitio='';

			while ($j<$k){

				$row = $ds3->getValues($j);					
				$idfamiliar=$row['id_familiar'];
				$sitio=strtoupper($row['rif_sitio_estudio']);
				$tipo_sitio=$row['tipo_sitio_estudio'];
				$array=array("","PUBLICO","PRIVADO");
									
				if($row['nuevo']==0 and $row['modificado']==0 ){$marca="";}
				if($row['estatus']==1 and $row['modificado']==1  ){$marca="(*)";}
				if($row['nuevo']==0 and $row['modificado']==2 ){$marca="(**)";}	

				$buscaresultados = "select '$idfamiliar' as id_familiar,
									(select CASE WHEN beneficio='J'  THEN 'JUGUETES'   ELSE '-' END  from admon_personal.beneficios_contractuales where id_familiar=$idfamiliar and id_solicitud=$id_solicitud and beneficio='J' ) as juguetes,
									(select CASE WHEN beneficio='D'  THEN 'DISCAPACIDAD'   ELSE '-' END  from admon_personal.beneficios_contractuales where id_familiar=$idfamiliar and id_solicitud=$id_solicitud and beneficio='D' ) as discapacidad,
									(select CASE WHEN beneficio='G'  THEN 'C.E.I' ELSE '-' END  from admon_personal.beneficios_contractuales where id_familiar=$idfamiliar and id_solicitud=$id_solicitud and beneficio='G' ) as guarderia,
									(select CASE WHEN nivel=1  THEN 'BÁSICA'   WHEN nivel=2 THEN 'SECUNDARIA' WHEN nivel=3 THEN 'UNIVERSITARIA' WHEN nivel=5 THEN 'C.E.I' ELSE '-' END  from admon_personal.beneficios_contractuales where id_familiar=$idfamiliar and id_solicitud=$id_solicitud and beneficio in ('B','G','D')) as textos,
									(select CASE WHEN nivel=1  THEN 'BÁSICA'   WHEN nivel=2 THEN 'SECUNDARIA' WHEN nivel=3 THEN 'UNIVERSITARIA' ELSE '-' END  from admon_personal.beneficios_contractuales where id_familiar=$idfamiliar and id_solicitud=$id_solicitud and beneficio='B')as beca";

				//$pdf->MultiCell(64,5,$buscaresultados, 1, 'L', 0, 1, '', '', true);	
				
				$ds6  = new DataStore($db, utf8_encode($buscaresultados));
				
				if($ds6->getNumRows()<=0) {
									
				} else {

					$val = $ds6->getValues(0);	

					if($sitio=='G-20008806-0' and $val['beca']=='UNIVERSITARIA' ){
						
						$valor=1;
					
					} else {
					
						$valor=0;
					}
					
					if($sitio=='G-20008806-0' and $val['textos']=='UNIVERSITARIA' ){
						
						$valor2=1;
					
					} else {
						$valor2=0;
					}	
										
					$pdf->SetFont('helvetica', '', 7);
					$pdf->MultiCell(18,5,"".$row['ce_familiar']."", 1, 'R', 0, 0, '', '', true);
					$pdf->MultiCell(75,5,$row['nombres']." ".$marca, 1, 'L', 0, 0, '', '', true);	
					$pdf->MultiCell(22,5,"".$row['rif_sitio_estudio']."", 1, 'L', 0, 0, '', '', true);	
					$pdf->MultiCell(15,5,"".$row['edad']."", 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(30,5,($valor==1  ? 'LUZ': ''.$val['beca'].'' ), 1, 'C',0, 0, '', '', true);	
					$pdf->MultiCell(18,5,$val['juguetes'], 1, 'C',0, 0, '', '', true);	
					$pdf->MultiCell(26,5,($valor2==1  ? 'LUZ': ''.$val['textos'].'' ), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(32,5,($val['guarderia']=='C.E.I' ?  $array[$tipo_sitio] : "" ), 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(33,5,($val['discapacidad']=='DISCAPACIDAD' ?  $array[$tipo_sitio] : "" ), 1, 'C',0, 0, '', '', true);	
					$pdf->Ln();											

				}
				  				
				$j++;
			}

			$pdf->SetFillColor(187, 187, 187);
			$pdf->SetFont('helvetica', 'B', 10);	

			/*$pdf->MultiCell(184,5,'OBSERVACIONES', 1, 'C', 1, 1, '', '', true);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->MultiCell(184,30,'' , 1, 'C', 0, 0, '', '', true);*/
			$pdf->Ln(5);	
			$pdf->SetFont('helvetica', '', 7);
			$pdf->MultiCell(270,5,'Yo, '.trim(utf8_encode($dat['NOMBRES'])).' declaro que la informacion suministrada es fiel y exacta; por lo que autorizo a la Universidad del Zulia a constatar la veracidad de la misma y de la documentacion anexa, asumiendo la responsabilidad de los resultados de dicha investigacion.                                                            ' , 0, 'J', 0, 1, '', '', true);				
			$pdf->MultiCell(90,5,'_____________________' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,'_____________________' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,'_____________________' , 0, 'C', 0, 0, '', '', true);
			$pdf->Ln();	
			$pdf->MultiCell(90,5,'Firma del trabajador' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,''.$nombreanalista[0].'' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,'Firma Procesado' , 0, 'C', 0, 0, '', '', true);
			$pdf->Ln();	
			$pdf->MultiCell(90,5,'' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,'Fecha Recibido: ___/___/___' , 0, 'C', 0, 0, '', '', true);
			$pdf->MultiCell(90,5,'Fecha Procesado: ___/___/___' , 0, 'C', 0, 0, '', '', true);
	
		}
	}
}
	 	
$pdf->lastPage();
$pdf->Output("".$cedula.".pdf",'I');


?>
