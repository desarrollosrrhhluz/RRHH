<?php
/******************************************/
//Funcion que dada una fecha y una cantidad 
//de dias determinados devuelve un dia habil
/******************************************/

function diaHabil($fecha0, $cantidad){
	global $fecha0, $cantidad; 
	 $fecha0;
	   $fechaJunio=date("Y-m-d", mktime(0, 0, 0, 06, 01,date("Y")));
	   $finJulio=date("Y-m-d", mktime(0, 0, 0, 07, 31,date("Y")));
	   $finJulio2=date("d/m/Y", mktime(0, 0, 0, 07, 31,date("Y")));
	   $fecha_mod = cambiaf_a_mysql($fecha0);
	   
	   if($fecha_mod>= $fechaJunio and $fecha_mod <= $finJulio){
		$resta= restaFechas($fecha0,$finJulio2);
		$cantidadReal= $cantidad - $resta;
		$fecha0="18/09/".date("Y");
		$fecha= suma_fechas($fecha0, $cantidadReal );
		}else{
		

		$fechaOctubre=date("Y-m-d", mktime(0, 0, 0, 10, 15,date("Y")));
	    $finDiciembre=date("Y-m-d", mktime(0, 0, 0, 12, 14,date("Y")));	
		 $finDiciembre2=date("d/m/Y", mktime(0, 0, 0, 12, 14,date("Y")));		
		if($fecha_mod > $fechaOctubre and $fecha_mod < $finDiciembre){
			 $resta= $cantidad - restaFechas($fecha0, $finDiciembre2);
			 $cantidadReal= $resta;
		  $fecha0="11/01/". (date("Y")+1) ;
		 $fecha= suma_fechas($fecha0, $cantidadReal);
			 
			}else{
			 $fecha=suma_fechas($fecha0,$cantidad);

			}   
	   
	    }
	
$diaFijo=array('01/01','02/01','03/01','04/01','05/01','06/01','07/01','08/01','09/01','10/01','15/12','16/12',
				'17/12','18/12','19/12','20/12','21/12','22/12','23/12','14/12',
				'25/12','26/12','27/12','28/12','29/12','30/12','31/12','01/08',
				'02/08','03/08','04/08','05/08','06/08','07/08','08/08','09/08',
				'10/08','11/08','12/08','13/08','14/08','15/08','16/08','17/08',
				'18/08','19/08','20/08','21/08','22/08','23/08','24/08','25/08',
				'26/08','27/08','28/08','29/08','30/08','31/08','01/09','02/09',
				'03/09','04/09','05/09','06/09','07/09','08/09','09/09','10/09',
				'11/09','12/09','13/09','14/09','15/09','01/05','05/07','24/10',
				'24/07','15/05','01/10');	

$diaFortuito=array('07/06/2014','21/11/2014');	
$carnavales=array(2014 => '02/03/2014', 2015 => '15/02/2015',2016 => '07/02/2016', 2017 => '26/02/2017', 2018 => '11/02/2018',
				  2019 => '03/03/2019', 2020 => '23/02/2020',2021 => '14/02/2021', 2022 => '27/02/2022', 2023 => '19/02/2023',
				  2024 => '11/02/2024');

$fechaVariada=$fecha;
$veces=0;
$valida=false;
while($valida!=true){
	
	 $sub_fecha=substr($fechaVariada,0,5);
	
	if(in_array($sub_fecha,$diaFijo)){
		 $mes=substr($fechaVariada,3,2) ;
		 $dia=substr($fechaVariada,0,2);
		  $anno=substr($fechaVariada,6,4);
		  $fechaVariada=date("d/m/Y", mktime(0, 0, 0, $mes, $dia, $anno));
	//	$dia=date('l', strtotime($fechaVariada));
		$fechaVariada=suma_fechas($fechaVariada,1);
	
	 $veces=$veces+1 ;
		   $valida=false;
		}else{
		
		//////////////////////////////////////////////////
	     $mes=substr($fechaVariada,3,2) ;
		 $dia= substr($fechaVariada,0,2);
		 $anno = substr($fechaVariada,6,4);
	 	 $fechaVariada = date("d/m/Y", mktime(0, 0, 0, $mes, $dia, $anno));
		 $fecha2 = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anno));
		 
		 if(array_key_exists($anno, $carnavales)){
					//echo "Hay unos carnavales en fecha ".$carnavales[$anno]."<br>"; 
				if($fechaVariada == suma_fechas($carnavales[$anno],2) or $fechaVariada == suma_fechas($carnavales[$anno],1) ){//que no sea carnaval
					
				 $fechaVariada = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anno));
				 $fechaVariada=date('d/m/Y',strtotime("next Wednesday", strtotime($fechaVariada)));
				 $valida= true;
					}else{
					$fechaSS=suma_fechas($carnavales[$anno],43);
					$mesSS=substr($fechaSS,3,2) ;
		 			$diaSS= substr($fechaSS,0,2);
		 			$annoSS = substr($fechaSS,6,4);
					 $fechaSS2 = date("Y-m-d", mktime(0, 0, 0, $mesSS, $diaSS, $annoSS));
					 $semNro= date('W', strtotime($fecha2));
					 $nroSemSanta= date('W', strtotime($fechaSS2));	
					/////////////////////////////////////////
							if( $semNro == $nroSemSanta){// que no sea semana santa
							 $fecha2 = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anno));
							 $fechaVariada=date('d/m/Y',strtotime("next Monday", strtotime($fecha2)));
							// $fechaVariada=date('d/m/Y',strtotime("next Monday"));
							$valida=true;
							
						     }else{// que no sea sabado o domingo
							 $diaNombre= date('l', strtotime($fecha2));
							
								  if($diaNombre=="Sunday"){
									 $fecha2 = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anno));
									  $fechaVariada=date('d/m/Y',strtotime("next Monday", strtotime($fecha2)));
									  $valida=true;
									  }else{
								  if($diaNombre=="Saturday"){
									 $fecha2 = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $anno));
									  $fechaVariada=date('d/m/Y',strtotime("next Monday", strtotime($fecha2)));
									  $valida=true;
									  }else{
									  $valida=true;
										  }	
							  }	
											
								
								}
							
					
					
					}	
					
					
				/*$valida=true;	
					}else{
					
			$valida=true;*/
			
				//	}
		 
		}
      
}
	
}
return $fechaVariada;

}

?>