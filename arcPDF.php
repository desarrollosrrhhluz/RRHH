<?php
   header( "Cache-Control: no-store, no-cache, must-revalidate" );
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );

include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');

class PDF extends FPDF {
    public $cedula;
    public $tipo;
    public $codigo;
    public $ano;
    public $destino;
    public $id;

    function Header() {
        $this->SetFont('Arial','',12);
        $this->Cell(20,7,'',0,0,'L');
        $this->Cell(160,7,'UNIVERSIDAD DEL ZULIA',0,0,'L');
        $this->Ln();
        $this->Cell(20,7,'',0,0,'L');
        $this->Cell(120,7,'VICE-RECTORADO ADMINISTRATIVO',0,0,'L');
        $this->SetFont('Arial','',10);
        $this->Cell(40,7,'EJERCICIO FISCAL : AÑO '.$this->ano,0,0,'R');
        $this->SetFont('Arial','',12);
        $this->Image('images/escudo_de_luz_peq.jpg',20,10,20);
        $this->Ln();
        $this->Cell(20,7,'',0,0,'L');
        $this->Cell(60,7,'DIRECCIÓN DE ADMINISTRACIÓN',0,0,'L');
        $this->Ln();
        $this->SetFont('Arial','',10);
        $this->Cell(20,7,'',0,0,'L');
//        $this->Cell(60,7,'RIF. J-07031841-4',0,0,'L');
        $this->Cell(60,7,'RIF. G-20008806-0',0,0,'L');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',14);
        $this->Cell(180,7,'TOTAL REMUNERACIONES ANUALES',0,0,'C');
        $this->Ln();

    }

      function Detail(){
    	if($this->ano < 2008 || $this->ano >(date("Y"))){
            $this->SetFont('Arial','B',22);
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->MultiCell(180,10,'Solo puede solicitar información desde el año 2008 hasta el año '.($this->ano - 1),0,'C');
        } else {
            $db     = "sidial";
            $sql0 =  "select distinct NOMBRES,CE_TRABAJADOR,TIPOPERSONAL,CO_UBICACION, DESCRIPCION ,NU_CARGAS";
            $sql0 .= " from VW_ARC WHERE CE_TRABAJADOR = $this->cedula and ANO = $this->ano";

                if($this->ano >= 2011) {
			$sql = "select 
			(sum(MO_ASIGNACIONES)) as MO_ASIGNACIONES, 
			sum(MO_RETENIDO) as MO_RETENIDO, 
			sum(MO_VACACIONAL) as MO_VACACIONAL, 
			sum(MO_AGUINALDO) as MO_AGUINALDO, 
			sum(MO_RETRO_ACTUAL) as MO_RETRO_ACTUAL, 
			sum(MO_RETRO_ANTERIOR) as MO_RETRO_ANTERIOR, 
			sum(MO_SEGURO) as MO_SEGURO, 
			sum(MO_CAJA) as MO_CAJA, 
			sum(MO_SSO) as MO_SSO";
            $sql .= " from VW_ARC WHERE CE_TRABAJADOR = $this->cedula and ANO = $this->ano";
} else {
			$sql = "select (sum(MO_ASIGNACIONES)-sum(MO_VACACIONAL)-sum(MO_AGUINALDO)) as MO_ASIGNACIONES, sum(MO_RETENIDO) as MO_RETENIDO, sum(MO_VACACIONAL) as MO_VACACIONAL, sum(MO_AGUINALDO) as MO_AGUINALDO, sum(MO_DOCTOR) as MO_DOCTOR, sum(MO_RETRO_ACTUAL) as MO_RETRO_ACTUAL, sum(MO_RETRO_ANTERIOR) as MO_RETRO_ANTERIOR, sum(MO_SEGURO) as MO_SEGURO, sum(MO_CAJA) as MO_CAJA, sum(MO_SSO) as MO_SSO";
            $sql .= " from VW_ARC WHERE CE_TRABAJADOR = $this->cedula and ANO = $this->ano";
}

            $ds0     = new DataStore($db);
            $ds0->executesql($sql0);
            if($ds0->getNumRows()<1){
                $this->SetFont('Arial','B',14);
                $this->Cell(180,10,'No hay datos para la consulta',0,0,'C');
            } else {
                $ds = new DataStore($db);
                $ds->executesql($sql);
                $fila0 = $ds0->getValues(0);
                $fila = $ds->getValues(0);
                $this->SetFont('Arial','B',12);
                $this->Cell(180,6,'DATOS DEL TRABAJADOR',0,1,'L',true);
                $this->SetFont('Arial','',11);
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'APELLIDOS Y NOMBRES:',0,0,'L');
                $this->Cell(100,7,  utf8_decode($fila0['NOMBRES']),0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'CEDULA DE IDENTIDAD:',0,0,'L');
                $this->Cell(100,7,number_format($fila0['CE_TRABAJADOR'], 0, ',', '.'),0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'TIPO DE PERSONAL:',0,0,'L');
                switch (substr($fila0['TIPOPERSONAL'], 0, 1)){
                    case '2':
                        $tp = "ADMINISTRATIVO";
                        $smo = number_format($fila['MO_SEGURO'], 2, ',', '.');
                        $ipp = '0,00';
                        break;
                    case '1':
                        $tp = "DOCENTE";
                        $ipp = number_format($fila['MO_SEGURO'], 2, ',', '.');
                        $smo = '0,00';
                        break;
                    case '3':
                        $tp = "OBRERO";
                        break;
                }
                $this->Cell(100,7,$tp,0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'UBICACIÓN:',0,0,'L');
                $this->Cell(100,7,number_format($fila0['CO_UBICACION'], 0, '', '').' '.utf8_decode($fila0['DESCRIPCION']),0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'Nro. CARGAS FAMILIARES:',0,0,'L');
                $this->Cell(100,7,number_format($fila0['NU_CARGAS'], 0, ',', '.'),0,1,'L');
                $this->Ln();
                $this->SetFont('Arial','B',12);
                $this->Cell(180,6,'DATOS DEL AGENTE DE RETENCIÓN',0,1,'L',true);
                $this->SetFont('Arial','',11);
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'AGENTE DE RETENCIÓN NRO:',0,0,'L');
                $this->Cell(100,7,'1-9-1.220.500',0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'CÉDULA DE IDENTIDAD:',0,0,'L');
                $this->Cell(100,7,'V-02.878.798',0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'NOMBRE:',0,0,'L');
                $this->Cell(100,7,'DANIEL VERA CORDERO',0,1,'L');
                $this->Cell(20,7,'',0,0,'L');
                $this->Cell(60,7,'DIRECCIÓN:',0,0,'L');
                $this->Cell(100,7,'AV. 16 ZIRUMA - EDIFICIO RECTORADO',0,1,'L');
                $this->Ln();
                $this->SetFont('Arial','B',12);
                $this->Cell(180,6,'ACUMULADOS ANUALES',0,1,'L',true);
                $this->SetFont('Arial','',11);
                $this->Cell(55,7,'SUELDOS Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_ASIGNACIONES'], 2, ',', '.'),0,0,'R');
                $this->Cell(10,8);
                $this->Cell(55,7,'TOTAL RETENCIÓN ISLR Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_RETENIDO'], 2, ',', '.'),0,1,'R');

                $this->Cell(55,7,'BONO VACACIONAL Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_VACACIONAL'], 2, ',', '.'),0,0,'R');
                $this->Cell(10,8);
                $this->Cell(55,7,'TOTAL S.S.O. Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_SSO'], 2, ',', '.'),0,1,'R');

                $this->Cell(55,7,'AGUINALDO Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_AGUINALDO'], 2, ',', '.'),0,0,'R');
                $this->Cell(10,8);
                $this->Cell(55,7,'TOTAL S.M.O. Bs.:',0,0,'L');
                $this->Cell(30,7,$smo,0,1,'R');

                $this->Cell(55,7,'RETROACTIVO ESTE AÑO Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_RETRO_ACTUAL'], 2, ',', '.'),0,0,'R');
                $this->Cell(10,8);
                $this->Cell(55,7,'TOTAL I.P.P.L.U.Z. Bs.:',0,0,'L');
                $this->Cell(30,7,$ipp,0,1,'R');

                $this->Cell(55,7,'RETROACTIVO AÑOS ANT. Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_RETRO_ANTERIOR'], 2, ',', '.'),0,1,'R');

                $this->SetFont('Arial','B',12);
                $this->Cell(55,7,'TOTAL DEVENGADO Bs.:',0,0,'L');
                $this->Cell(30,7,number_format($fila['MO_ASIGNACIONES']+$fila['MO_AGUINALDO']+$fila['MO_VACACIONAL']+$fila['MO_RETRO_ACTUAL']+$fila['MO_RETRO_ANTERIOR'], 2, ',', '.'),0,0,'R');
                $this->SetFont('Arial','',11);

                $this->Ln();
                $this->Ln();
                $this->Ln();
                $this->Ln();
                $this->Image("images/firma_daniel_vera.jpg", 90, null,45,0,"jpg");
                $this->Cell(180,6,'Msc. DANIEL VERA CORDERO',0,1,'C');
                $this->Cell(180,6,'DIRECTOR DE ADMINISTRACIÓN',0,1,'C');
            }
        }
    }
    //Pie de pï¿½gina
    function Footer() {
 /*       $this->SetY(-50);
        $codigoBarras = new CodigoBarras();
        $codigoBarras->setCodigoBarras("CT",$this->id,date("d/m/Y"));
        $this->SetFont('Arial','B',10);
        $this->Cell(90,4,'ID digital: '.$codigoBarras->getCodigo(),0,0,'L');
        $this->Cell(90,4,'Fecha de impresiï¿½n: '.$codigoBarras->getFecha(),0,0,'R');
        $this->Ln();
        $this->Image($codigoBarras->getHTMLImage(), 90, null,40,0,"png");
        $this->SetFont('Arial','I',7);
        $this->Cell(180,3,'Para confirmar la validez de este documento, ingrese en nuestro sitio web www.luz.edu.ve/validador y siga las instrucciones.',0,1,'C');
		$this->Ln();
        $this->Cell(180,3,'La informaciï¿½n mostrada en este reporte es personal.',0,1,'C');*/
        $this->SetFont('Arial','I',7);
        $this->Cell(180,3,'www.vad.luz.edu.ve',0,1,'R');
    }
}

//Creaciï¿½n del objeto de la clase heredada
$pdf=new PDF('P','mm','letter');
$nid = getVar("cid");
if(strcmp($nid,"")!=0) {
	$pdf->cedula = $nid;
	$pdf->id     = 0;
} else {
	session_start();
	$pdf->cedula = $_SESSION["cedula"];
        //if($pdf->cedula==7978700) $pdf->cedula = 13912034;
	$pdf->id     = $_SESSION["id_user"];
}
$pdf->id     = getVar("id");
$pdf->ano    = getVar("ano");
$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("VAD / DITICLUZ");
$pdf->SetSubject("Total Remuneraciones Anuales");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

?>
