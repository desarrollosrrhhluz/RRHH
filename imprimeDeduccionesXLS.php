<?php

error_reporting(0);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once 'phpexcel/Classes/PHPExcel.php';
include_once('app_class/DataStore.class.php');
//include_once('includes/Funciones.inc.php');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Universidad del Zuia")
							 ->setLastModifiedBy("Tonny Medina")
							 ->setTitle("Detalle de deducciones")
							 ->setSubject("Detalle")
							 ->setDescription("Documento xls creado desde php")
							 ->setKeywords("deducciones")
							 ->setCategory("xls");



$dbs="sidial2";

		$ano = $_GET['ano'];
		$mes = $_GET['mes'];
		$cod = $_GET['cod'];
		$tip = $_GET['tip'];
		
		
 		
	
		$sql =  "select *,(SELECT DISTINct NOMBRES FROM V_DATPER where CE_TRABAJADOR=HIS_DETALLE_PAGO.CE_TRABAJADOR) as NOMBRES from HIS_DETALLE_PAGO where CO_CONCEPTO='$cod' and ANO=$ano AND MES=$mes and IN_NOMINA='$tip' and  STATUS_DEDUCCION!=1 ";
        $dss     = new DataStore($dbs, $sql);
    	if($dss->getNumRows()<=0){
           
        } else {
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CE_TRABAJADOR')
            ->setCellValue('B1', 'NOMBRES')
            ->setCellValue('C1', 'ANO')
            ->setCellValue('D1', 'MES')
			->setCellValue('E1', 'CO_CONCEPTO')
			->setCellValue('F1', 'MO_CONCEPTO')
			->setCellValue('G1', 'MO_SALDO');
		$i=0;
		$k=2;
		$j=$dss->getNumRows();
		while($i < $j){
			$fila = $dss->getValues($i);
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$k.'', number_format($fila['CE_TRABAJADOR'],0,',','.'))
            ->setCellValue('B'.$k.'', utf8_encode($fila['NOMBRES']))
            ->setCellValue('C'.$k.'', $ano )
            ->setCellValue('D'.$k.'', $mes)
			->setCellValue('E'.$k.'', $cod)
			->setCellValue('F'.$k.'', number_format($fila['MO_CONCEP'],2,',','.'))
			->setCellValue('G'.$k.'', number_format($fila['MO_SALDO'],2,',','.'));	
			
	$i++;
	$k++;
			}
		}




// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);



// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$cod.'-'.$mes.''.$ano.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>