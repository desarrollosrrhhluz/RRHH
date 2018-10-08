<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="rrhh";
$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$nombreTrabajador=$_SESSION['nombres'];
$tipop=substr($_SESSION['tipopersonal'], 0,1 );
$anno=$_SESSION['ano_proceso']=2019;
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	         case 'consultaInicialHijos':
	         consultaInicialHijos();
			 break;	
			 case 'guarda_familiar':
	        guarda_familiar();
			 break;	
			 case 'eliminaFamiliarSel':
			 $idf=$_REQUEST['id'];
	         eliminaFamiliarSel($idf);
			 break;	
			 case 'query_jsonFamiliar':
			 $idf=$_REQUEST['id'];
	         query_jsonFamiliar($idf);
			 break;	
			 case 'UpdateFamiliar':
			 UpdateFamiliar();
			 break;
		   
			 //*************************
				

			 //*******************
			 case 'insertaBeneficios':
			 $cadena=$_REQUEST['id'];
	         insertaBeneficios($cadena);
			 break;
			 
			
			 
			  
			 }

	/** Inicio de la función */
	function consultaInicialHijos(){

		global $db, $cedula, $anno;
		$var=0;

	 	$sql2 ="SELECT *,fe_verificado::date as fe_verifica, fecha_actualizacion::date as fe_actualiza ,EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad, EXTRACT(year FROM age(date('30/11/2018'),date(fe_nacimiento) ) ) as edad_j, EXTRACT(year FROM age( date('30/01/2018') ,date(fe_nacimiento) ) )  as edad_b ,
 			(select b.ce_trabajador  from admon_personal.beneficios_contractuales as a, admon_personal.tra_solicitud_beneficios as b 
			where a.id_solicitud=b.id_solicitud
			and a.id_familiar=admon_personal.mst_familiares_beneficios.id_familiar
			and b.anno=$anno
			 group by b.ce_trabajador) as tiene_beneficios
			 FROM admon_personal.mst_familiares_beneficios where  parentesco='D' and nuevo!=1 
			 and  
			 ( EXTRACT(year FROM age( date('31/01/2018') ,date(fe_nacimiento) ) )<26  or condicion_personal=2 )
			 and estatus=1 
			 and (ce_trabajador=$cedula or ce_otro_padre=$cedula) 
			 order by id_familiar 
			";

        $ds     = new DataStore($db, $sql2);

    	if($ds->getNumRows()==0) {
		
			echo "Aun no tiene hijos registrados";
		
		} else {

			$i=$ds->getNumRows();
			$j=0;
			$concat="";
		
			echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='table table-condensed'> <thead><tr><td><b>Nombres</b></td><td class='visible-lg'><b>Cedula</b></td><td class='visible-lg'><b>Sexo</b></td><td class='visible-lg'><b>Fecha Nacimiento</b></td><td></td><td></td><td></td></tr> </thead><tbody>";
		
			while ($j<$i) {

				$row = $ds->getValues($j);
				$resul= array(
					"98"  => "C.E.I Negado, Ayuda Textos Aprobada",
					"3"   => "Negado por falta constacia de estudio",
					"33"  => "Negado por falta constacia de inscripcion",
					"333" => "Negado por Familiar EGRESADO",
					"5"   => "Negado por limite de edad",
					"7"   => "Beneficios solicitados por conyuge",
					"16"  => "Negar por documentos forjados",
					"18"  => "Negar por Indepencia Economica"
		    	);
	
				if($row['resultado_verificacion']=='Aprobado' and $row['fe_verifica']>=$_SESSION['fe_inicio_bc']) {
					
					if($row['observacion_verificacion']=='98') {

						$textoResultado = "<span class='label label-warning boton_negado' data-toggle='tooltip' data-placement='top' title='".$resul[$row['observacion_verificacion']]."'><span class='glyphicon glyphicon-warning-sign  ' ></span> Aprobado  </span>";
						
					} else {
						$textoResultado = "<span class='label label-info'><span class='glyphicon glyphicon-thumbs-up'></span> Aprobado</span>";
					}
				}

				if($row['resultado_verificacion']=='Negado' and $row['fe_verifica']>=$_SESSION['fe_inicio_bc']) {

					$textoResultado = "<span class='label label-danger boton_negado' data-toggle='tooltip' data-placement='top' title='".$resul[$row['observacion_verificacion']]."'><span class='glyphicon glyphicon-thumbs-down  ' ></span> Negado </span>";
				}
	
				if( $row['tiene_beneficios'] == $cedula ) {
					
					if(is_null($row['resultado_verificacion'])) {
						
						$boton = "<button type='button' id=".$row['id_familiar']." class='clic_editar btn btn-primary btn-sm'><span class='glyphicon glyphicon-pencil'></span> Editar</button> <button type='button' id=".$row['id_familiar']." class='clic_planilla btn btn-warning btn-sm' title='Ver Planilla'><span class='glyphicon glyphicon-download-alt'></span></button>";  
					
					}else {
						
						if($row['fe_verifica'] >= $_SESSION['fe_inicio_bc']) {
						
							$boton=$textoResultado;
						
						} else {

							$boton="<button type='button' id=".$row['id_familiar']." class='clic_editar btn btn-primary btn-sm'><span class='glyphicon glyphicon-pencil'></span> Editar</button> <button type='button' id=".$row['id_familiar']." class='clic_planilla btn btn-warning btn-sm' title='Ver Planilla'><span class='glyphicon glyphicon-download-alt'></span></button>";  	 
						}
					}

				} else {
					
					if(is_null($row['tiene_beneficios'])) {
							
						if(is_null($row['resultado_verificacion'])) {

					        $boton = "<button type='button' id=".$row['id_familiar']." class='clic_editar btn btn-primary btn-sm '><span class='glyphicon glyphicon-pencil'></span> Editar</button> ";  
						
						} else {

							if($row['fe_verifica']>=$_SESSION['fe_inicio_bc']) {
								
								$boton=$textoResultado;

						 	} else {

								$boton = "<button type='button' id=".$row['id_familiar']." class='clic_editar btn btn-primary btn-sm '><span class='glyphicon glyphicon-pencil'></span> Editar</button> ";			
							}	
						}
						
					} else { 
						
						if(is_null($row['resultado_verificacion'])) {
					        
					        $boton="<button type='button' id=".$row['tiene_beneficios']." class='clic_otro_padre btn btn-warning btn-sm'><span class='glyphicon glyphicon-warning-sign' title='Solicitado Otro Padre'></span> Solicitado</button>";  
						
						} else {

							if( $row['fe_verifica'] >= $_SESSION['fe_inicio_bc']) {

								$boton="<button type='button' id=".$row['tiene_beneficios']." class='clic_otro_padre btn btn-warning btn-sm'><span class='glyphicon glyphicon-warning-sign' title='Solicitado Otro Padre'></span> Solicitado</button>";  
							
							} else {
									
								//$boton=$textoResultado;
								$boton="<button type='button' id=".$row['tiene_beneficios']." class='clic_otro_padre btn btn-warning btn-sm'><span class='glyphicon glyphicon-warning-sign' title='Solicitado Otro Padre'></span> Solicitado</button>"; 
							}			
						}
						
					}
				}
		
				//////////////////////////////
				if(!is_null($row['tiene_beneficios'])){
				
					$concat.="";
				
				}else{
				
					$concat.="1";				
				}
	
				/////////////////////////////	
				if($row['fe_actualiza'] >= $_SESSION['fe_inicio_bc']) {

					if($row['activo_estudio']==1 and $row['edad_b']<26 ) {
						$textos='<span class="label label-success" title="Ayuda Textos Utiles Activa">T</span>';
					} else { 
						$textos='<span class="label label-default" title="Ayuda Textos Utiles">T</span>';
					}

					if($row['activo_estudio']==1 and $row['edad_b']<26  and $row['nivel']!=5) {
						$beca='<span class="label label-success" title="Beca Activa">B</span>';
					} else { 
						$beca='<span class="label label-default" title="Beca Inactiva">B</span>';
					}
					
					if($row['edad_b']<7 and $row['nivel']==5 and  $row['activo_estudio']==1) {
						$guarderia='<span class="label label-success" title="C.E.I Activo">G</span>';
					} else { 
						$guarderia='<span class="label label-default" title="C.E.I Inactivo">G</span>';
					}

					if($row['edad_j']<14) {
						$juguetes='<span class="label label-success" title="Juguetes Activo">J</span>';
					} else { 
						$juguetes='<span class="label label-default" title="Juguetes Inactivo">J</span>';
					}

					if($row['activo_estudio']==1 and $row['condicion_personal']==2 ) { 
						$discap='<span class="label label-success" title="Educacion para hijos con discapacidad">D</span>';
					} else { 
						$discap='<span class="label label-default" title="Educacion para hijos con discapacidad">D</span>';
					}
					
					$situacion=$beca.' '.$textos.'<br>'.$guarderia.' '.$juguetes.' '.$discap;

				} else {

					$situacion='<span class="label label-default" title="Beca Inactiva">B</span> <span class="label label-default" title="Ayuda Textos y Utiles Inactiva">T</span><br><span class="label label-default" title="C.E.I Inactivo">G</span> <span class="label label-default" title="Juguetes Inactiva">J</span>';
				}

			   	/////////////////////////////
			   	if($row['fe_actualiza'] >= $_SESSION['fe_inicio_bc']) {

				   if(!is_null($row['tiene_beneficios'])) {

					   	if($row['tiene_beneficios'] == $cedula) {
						    $check="<input name='solicita' type='checkbox' disabled='disabled' class='checkFam' id=".$row['id_familiar']." value=".$row['id_familiar']." />";
						
						} else {

							$check=" - ";    
						}

					} else {

						$check="<input name='solicita' type='checkbox'  class='checkFam' id=".$row['id_familiar']." value=".$row['id_familiar']." />";   
					}

				} else {
				
					$check=" - ";   
				}

  				///////////////////////////// 
				echo"<tr><td>".(empty($row['nombre1'])? "".strtoupper($row['nombres'])."":"".strtoupper("".strtoupper($row['apellido1'])." ".strtoupper($row['apellido2'])." ".strtoupper($row['nombre1'])." ".strtoupper($row['nombre2'])."")."")."</td>
				<td class='visible-lg'>".$row['ce_familiar']."</td><td class='visible-lg'>".$row['sexo']."</td>
				<td class='visible-lg'>".$row['fe_nacimiento']."</td>
				<td>".$situacion."</td>
				<td>".$boton."</td><td align='center'>".$check."</td></tr>";

				if( $row['fe_actualiza'] >= $_SESSION['fe_inicio_bc']){
					$var=1;
				}
 
 				$j++;

			} /** Final del While */

			if($var==1){

				if(strlen($concat)>0) {

					$botoSolicitud="<button type='button' id='sol_bene'  class='btn btn-success btn-sm'><span class='glyphicon glyphicon-star-empty'></span> Solicitar</button>";
				
				} else {
				
					$botoSolicitud="<button type='button' disabled='disabled'  class='btn btn-success btn-sm'><span class='glyphicon glyphicon-star-empty'></span> Solicitar</button>";	
				
				}	
			
			} else {
			
			}
		  
			echo "</tbody>
			<tfoot><tr><td></td><td class='visible-lg'></td><td class='visible-lg'></td><td class='visible-lg'></td><td></td><td>".$botoSolicitud."</td></tr> </tfoot></table>";
	  
		} /** Final del Else */

	} 
	/* Final de la función */


	/* Inicio de la función */
	function guarda_familiar() {

		global $db, $cedula, $sexo, $anno;
		extract($_POST);
		$ds	       = new DataStore($db);
		$nombres_mayus=addslashes(strtoupper($nombres_fam));

		if($motivo_incapacidad<1) {
			$motivo_incapacidad=0;
		}

		if($estudia==2) {
			$sitio_estudio= 0; $nivel_estudio=0; $grado_estudio=0; $regimen=0;
		}

		$fecharegistro=date("d/m/Y");
		
		$sql="INSERT INTO  admon_personal.mst_familiares_beneficios
		( ce_trabajador, ce_familiar, nombres, sexo, fe_nacimiento, condicion_personal, ce_otro_padre, parentesco, motivo_incapacidad,activo_estudio, tipo_sitio_estudio ,sitio_estudio,  nivel,  grad_sem_trim,regimen_educativo, fe_cad_ce, estatus, nuevo, fecha_actualizacion, fecha_esp,modificado, nombre_trabajador,nombre_conyuge, responsable) 
		VALUES ( $cedula, $ce_familiar, '$nombres_mayus', '$sexo_fam', '$fe_nac_fam', $condicion_fam, $ce_otro_p, 'D', $motivo_incapacidad, $estudia ,".( empty ( $tipo_sitio ) ? "null" : "'" . $tipo_sitio  . "'" ).",".( empty ( $sitio_estudio ) ? "null" : "'" . $sitio_estudio  . "'" ).", $nivel_estudio, ".( empty ( $grado_estudio ) ? "null" : "'" . $grado_estudio  . "'" ).", $regimen, NULL, 1,1, '$fecharegistro', NULL,1,'".addslashes(utf8_encode($_SESSION['nombres']))."' ,'".addslashes(utf8_encode($nomb_otro_p))."', $cedula )";
		
		$rs = $ds->executesql($sql);

		if($rs>0) {
			echo '{ "message": "Datos de familiar almacenados con exito." }'; 
		} else {
			echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo" }'; 
		} 
	}
	/* Final de la función */

	/* Inicio de la función */
	function query_jsonFamiliar($idf) {
		
		global $db, $cedula, $anno;
		
		$sql =  "SELECT  * FROM admon_personal.mst_familiares_beneficios where id_familiar= $idf";
	    $ds  = new DataStore($db, $sql);
	    
	    if($ds->getNumRows()<=0) {

			$res=0; 
			echo json_encode($res) ;	
		
		} else {

			$rows=$ds->getValues(0);
			$res[]=$rows;
			$res[0]['fe_nacimiento']=cambiaf_a_normal($rows['fe_nacimiento']);
			echo json_encode($res) ;	
		}
	}
	/* Final de la función */

	/* Inicio de la función */
	function  UpdateFamiliar(){
		
		global $db, $cedula, $sexo, $anno,$nombreTrabajador;
		extract($_POST);

		$ds	= new DataStore($db);
		$fecharegistro=date("d/m/Y");
		$nombres_mayus=strtoupper($nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2);

		if($motivo_incapacidad<1) {
			$motivo_incapacidad=0;
		}

		if($estudia==2) {
			$sitio_estudio= 0; $nivel_estudio=0; $grado_estudio=0; $regimen=0;
			$rif=0;
		}

		$sql="UPDATE 
	  			admon_personal.mst_familiares_beneficios  
			  SET 
			  ce_familiar = $ce_familiar,
			  nombre_trabajador='".str_replace("'", " ", $nombreTrabajador)."',
			  nombre_conyuge='".addslashes($nomb_otro_p)."',
			  sexo = '$sexo_fam',
			  nombres= '$nombres_mayus',
			  condicion_personal = $condicion_fam,
			  ce_trabajador = $cedula,
			  responsable = $cedula,
			  ce_otro_padre = $ce_otro_p,
			  activo_estudio = $estudia,
			  nivel = $nivel_estudio,
			  grad_sem_trim = $grado_estudio,
			  regimen_educativo = $regimen,
			  tipo_sitio_estudio= ".( empty ( $tipo_sitio) ? "null" : "'" . $tipo_sitio  . "'" ).",
			  rif_sitio_estudio = ".( empty ( $rif ) ? "null" : "'" . $rif  . "'" ).",
			  conapdis = ".( empty ( $conapdis ) ? "null" : "'" . $conapdis  . "'" ).",
			  nombre_sitio_estudio = ".( empty ( $sitio_estudio ) ? "null" : "'" . addslashes($sitio_estudio)  . "'" ).",
			  modificado=2,
			  nombre1='".addslashes(($nombre1))."',
			  nombre2=".( empty ( $nombre2 ) ? "null" : "'" . addslashes(($nombre2))  . "'" ).",
			  apellido1='".addslashes(($apellido1))."',
			  apellido2='".addslashes(($apellido2))."',
			  fecha_actualizacion = '$fecharegistro'
			WHERE 
			  id_familiar = $id_fo";

		$rs = $ds->executesql($sql);

		if($rs>0) {
			echo '{ "message": "Datos almacenados con exito" }';  
		} else {
			echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo   " }'; 
		} 

		$sql =  "select * from admon_personal.tra_solicitud_beneficios where anno=$anno and  
				id_solicitud in (select id_solicitud from admon_personal.beneficios_contractuales where id_familiar=$id_fo)";
	    
	    $ds = new DataStore($db, $sql);
	   	
	   	if($ds->getNumRows()<=0) {
	           
	    } else {

		   $fila = $ds->getValues(0);
		   $id_solicitud=$fila['id_solicitud'];
		   ////////////////////////////////////
		   $buscaFam="select *, EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad, EXTRACT(year FROM age(date('30/11/2018'),date(fe_nacimiento) ) ) as edad_j,EXTRACT(year FROM age( date('30/01/2018') ,date(fe_nacimiento) ) )  as edad_b from admon_personal.mst_familiares_beneficios where id_familiar in ($id_fo)";
			
			$ds2     = new DataStore($db, $buscaFam);
	    	
	    	if($ds2->getNumRows()<=0) {
			
			} else {

				$j=$ds2->getNumRows();
				$i=0;
				$sqlBeneficios="";

				while($i<$j) {

					$row = $ds2->getValues($i);
					$idfam=$row['id_familiar'];
					$nivel=$row['nivel'];

					if($row['edad']<=12) {
						$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
						( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
						VALUES ($idfam, 'J',$nivel,CURRENT_DATE,$anno ,$cedula,$id_solicitud);";		
					} else {
						$sqlBeneficios.="";	
					}
				
					if($row['activo_estudio']==1) {

						if($row['condicion_personal']==2 and $row['nivel']==5 and $row['edad_b']<7) {

							$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
											( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
											VALUES ($idfam, 'D',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";
						} else {

							if($row['nivel']==5 and $row['edad_b']<7) {
								//guarderia y textos
								$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
												( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
												VALUES ($idfam, 'G',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";
							
							} else {
								//beca y textos	
								$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
												( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
												VALUES ($idfam, 'B',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";	
							}

						}

					}else{
						$sqlBeneficios.="";
					}
						$i++;
				}
				 
				$sqlFinal="BEGIN; DELETE FROM  admon_personal.beneficios_contractuales WHERE  id_familiar=$id_fo and id_solicitud=$id_solicitud; ".$sqlBeneficios." COMMIT;";
				$ds3  = new DataStore($db);
				$rs   = $ds3->executesql($sqlFinal);
			}
		}
	}
	/* Final de la función */

	/* Inicio Función */
	function eliminaFamiliarSel($idf){
		global $db, $cedula, $anno;
	 	$ds	= new DataStore($db);
		$sql="DELETE FROM admon_personal.mst_familiares_beneficios WHERE  id_familiar = $idf; DELETE FROM admon_personal.beneficios_contractuales WHERE  id_familiar = $idf and id_solicitud in(select id_solicitud from admon_personal.tra_solicitud_beneficios where ce_trabajador=$cedula and anno=$anno)";
		$rs = $ds->executesql($sql);
	}
	/* Final función */

	/* Inicio Función */
	function insertaBeneficios($cadena) {

		global $db, $cedula, $anno,$tipop;
		$letras = '0x1o2m3b4r5a6H7b8c9dZ'; // letras y numeros que usaremos 
		srand((double)microtime()*1000000);
		$i = 1;
		$largo_clave = 6; // tamaño maximo de clave generada
		$largo = strlen($letras);
		$clave_usuario='';

		while ($i <= $largo_clave) { 
			$lee = rand( 1,$largo);
		    $clave_usuario .= substr($letras, $lee, 1); 
		    $i++;                 
		}

		$clave_usuario = trim($clave_usuario);
		$pass = md5($clave_usuario); 

		$sql = "INSERT INTO admon_personal.tra_solicitud_beneficios
				( ce_trabajador, password, anno, fecha_solicitud, tipo_personal, tipo_solicitud, estatus)  VALUES ( $cedula, '$pass',$anno, CURRENT_TIMESTAMP,'$tipop','F','C')";
		$ds  = new DataStore($db);
		$rs  = $ds->executesql($sql);
		$last= "SELECT currval('admon_personal.tra_solicitud_beneficios_id_solicitud_seq') as id";
		$ds7 = new DataStore($db,$last);
		$fila= $ds7->getValues(0);
		$id_solicitud=$fila['id'];
		//return $id;


		$buscaFam="select *, EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad, EXTRACT(year FROM age(date('30/11/2018'),date(fe_nacimiento) ) ) as edad_j, EXTRACT(year FROM age( date('31/01/2018') ,date(fe_nacimiento) ) )  as edad_b from admon_personal.mst_familiares_beneficios where id_familiar in ($cadena)";
		$ds2 = new DataStore($db, $buscaFam);
	    
	    if($ds2->getNumRows()<=0) {
		
		} else {

			$j=$ds2->getNumRows();
			$i=0;
			$sqlBeneficios="";
			
			while($i<$j) {

				$row = $ds2->getValues($i);
				$idfam=$row['id_familiar'];
				$nivel=$row['nivel'];

				if($row['edad_j']<14) {

					$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
									( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
									VALUES ($idfam, 'J',$nivel,CURRENT_DATE,$anno ,$cedula,$id_solicitud);";		
				}else{
					$sqlBeneficios.="";	
				}
					
				if($row['activo_estudio']==1) {

					if($row['condicion_personal']==2 and $row['nivel']==5 and $row['edad_b']<7) {

						$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
										( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
										VALUES ($idfam, 'D',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";
					} else {

						if($row['nivel']==5 and $row['edad_b']<7) {

							//guarderia y textos
							$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
											( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
											VALUES ($idfam, 'G',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";
						} else {
							
							//beca y textos	
							$sqlBeneficios.="INSERT INTO  admon_personal.beneficios_contractuales
											( id_familiar, beneficio,nivel, fecha_solicitud, anno, solicitante, id_solicitud) 
											VALUES ($idfam, 'B',$nivel,CURRENT_DATE, $anno, $cedula,$id_solicitud);";	
						}
					}
				
				}else {
					
					$sqlBeneficios.="";
				}
					
				$i++;
			}
					
			$sqlFinal="BEGIN;".$sqlBeneficios." COMMIT;";
			$ds3  = new DataStore($db);
			$rs   = $ds3->executesql($sqlFinal);
			
			if($rs==0) {
				echo '{ "message": "Solictud almacenada con exito " }';  

			} else {
				echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo" }'; 
			} 
		}
	}
	/* Final Función */



?>