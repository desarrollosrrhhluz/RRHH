<?php
//******************************************************************************
//******************************************e**********************************
//****************************** (include)************************************
//*****************************************************************************
//*******************************************************************************
    function open($host,$usuario,$clave){
	  $conn = mysql_connect($host,$usuario,$clave);
	   if (empty($conn)){
	      echo"No se Pudo Realizar la Conexion";
	   }else{
	     return $conn;
	   }
    }
//*****************e******************************
     function imprimir(){
	    echo "<center><input type='Button' value= 'Imprimir' onclick='javascript:print()' class='fib'></center>";
	 }

 //*****************e******************************
     function atras(){
	    echo "<center><input type='Button' value= 'Regresar' onclick='history.go(-1)'></center>";
	 }
  //*****************e******************************
     function cerrar(){
	    echo "<center><input type='Button' value= 'Cerrar' onclick='self.close()'></center>";
	 }
// ******* Encabezado y pie  para la Forma *********
   function formhead($void,$name){
       echo "<form name=\"".$name."\" action=\"". $void ."\" method=\"post\" >";
    }
// ***********************************************************************
   function formfoot(){
      echo "</form>";
   }
//************************************************************************
  function validar_email($email){
       $mail_correcto = 0;
        if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
           if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          if (substr_count($email,".")>= 1){
             $term_dom = substr(strrchr ($email, '.'),1);
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
    }
    if ($mail_correcto)
       return 1;
    else
       return 0;
}
 //******************************e*********************************
  function sendmensaje($asunto,$email,$ci,$login){
     if ($asunto ==1){
	    $titulo="BIENVENIDO";
		$nota="Gracias por formar parte de nuestros usuarios...";
		$clave=clave($ci);
	 }else{
	    $titulo="HASTA PRONTO";
		$nota="Su suscripci&oacute;n fue cancela";
		$clave="Su suscripci&oacute;n fue cancela";
     }
  $mensaje=" <table width=\"450\" border=\"0\" align=\"center\">
              <tr>
                <td bgcolor=\"#0000cc\"><font face=\"Arial, Helvetica, sans-serif\" font size=\"4\" color=\"#FFFFFF\"><b>Universidad del Zulia</b></font></div></td>
              </tr>
              <tr>
                 <td bgcolor=\"#cfe6fe\">
                   <table width=\"400\" border=\"0\" align=\"center\">
                     <tr>
                       <td>
                          <div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\"><b><font size=\"3\" color=\"#0000FF\">$titulo</font></b></font></div>
                       </td>
                     </tr>
                     <tr>
                         <td> <i><font size=\"3\" face=\"Arial, Helvetica, sans-serif\" color=\"#0000CC\">Su
                               nombre de usuarios es:$login</font></i></td>
                     </tr>
                     <tr>
                     <td> <font color=\"#0000CC\"><i><font size=\"3\" face=\"Arial, Helvetica, sans-serif\">Su
                     Clave es:$clave </font></i></font></td>
                     </tr>
                   </table>
                 <p> <b><font face=\"Arial, Helvetica, sans-serif\" size=\"2\" color=\"#0000FF\">$nota</font></b></p>
               </td>
             </tr>
            <tr>
             <td bgcolor=\"#0000cc\">&nbsp;</td>
            </tr>
           </table>";
     $headers = "MIME-Version: 1.0\r\n";
     $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
     $headers .= "FROM: ASIES <jaguilar@luz.ve>\n";
      mail($email,$asunto,$mensaje,$headers);
  }
   //******************************e*********************************
   function mensaje($mensaje){
     echo"<br><br><center><b><font color=\"#ccccCC\" size=\"3\" face=\"arial\">
	       $mensaje </fon></b></center><br><br>";
   }

   //******************************e*********************************
   function mensaje_t($tit,$mensaje){
     echo"<br>"
	      ."<table border='0' width= '70%' align='center'>"
		      ."<tr>"
			     ."<th align='center'> <b><font color=\"#FF3366\">$tit </font></b> </th>"
			  ."</tr>"
		     ."<tr>"
			   ."<td ><div align='justify'> <b>&nbsp; $mensaje. </b></div></td>"
			 ."</tr>"
		 ."</table> <br><br>"; 	    
   }
//**********************************************************************************************************   
function select_option($tabla,$name, $inf, $sub){
   //sub --> 1 indica si se trabaja con sub tabla ; 0 sin sub tabla
   global $conn ;
      if ($sub == 1){
	    echo "\n<script>\n"
		     ."function env_select(){\n"
			 ."  document.form1.submit(); \n"
			 ."}\n"
		    ."</script>\n"; 
		$onchange=" onchange =\"env_select() \""	; 
	  }
	 
      $ext_opc="select * from $tabla" ;
	  $x_ext_opc= mysql_query($ext_opc,$conn) or die (" Error al estraer datos del select". $name) ;
	    if (mysql_num_rows($x_ext_opc) > 0){	   
	     echo "<select name =\"$name\" ".$onchange." class=\"inputbox\"> \n" ;
		    $cnt= 0 ;
		   while ($res= mysql_fetch_array($x_ext_opc) ){
		      $cnt = $cnt + 1 ; // permite saber q elemnto se carga cuando se modifica o elimina
		      if($cnt==$inf){ 
			    $selected ="selected" ; 
			  }else{
			     $selected='' ;
			  }
		    echo "<option value ='".$res['id_p']."' ". $selected.">".$res['descripcion']."</option> \n" ;
		   } // end while
		  echo "</select> \n" ;  
		}else{
		  echo " No existen elementos cargados...<br> Debe ingresararlos en las tablas basicas";
		}
		 
  }
  //**************************************************************************
 function select_option2($tabla,$name,$inf,$id,$id2, $var){
     //var --> es el elemento q identifica a la tabla padre
	 //id --> es el nombre id de la tabla hijo
	 //id2 --> es el nombre id de la tabla padre
   global $conn ; 
      $ext_opc="select * from $tabla where $id=$var" ;
	  $x_ext_opc= mysql_query($ext_opc,$conn) or die (" Error al estraer datos del select" . $name) ;
	  if (mysql_num_rows($x_ext_opc) > 0){	   
	     echo "<select name = $name >" ;
		    $cnt= 0 ;
		   while ($res= mysql_fetch_array($x_ext_opc) ){
		      $cnt = $cnt + 1 ;
		      if($cnt==$inf){  // permite saber q elemnto se carga cuando se modifica o elimina
			    $selected ="selected" ; 
			  }else{
			     $selected='' ;
			  }
		    echo "<option value ='".$res['$id2']."' ". $selected.">".$res['descripcion']."</option>" ;
		   } // end while
		  echo "</select>" ;  
	  }else{
		  echo "<select name = $name >" ;
		  echo "<option value ='' > ----- </option>" ;
		  echo "</select>" ; 		  
	  }  
  }
//**************************************************************************
function add_registro($tabla,$ci){  
  global $conn ; 
    if ($_POST) { 
	 // Publicando variables que vienen del formulario 
	 
	    foreach($_POST as $nombre_campo => $valor){ 
          $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
          eval($asignacion);
	   }	   
	 // Contrucccion del sql Los campos que provienen del formulario deben comensar 
	 // con txtc txtn txtf para indicar el tipo de datos
	 
	   $sql = "insert into $tabla (ce_trabajador ,"  ;
	   foreach($_POST as $nombre_campo => $valor){ 
	      $campo= substr($nombre_campo,4) ;
		  $func=substr($nombre_campo,0,4) ;
	     if ($func == "txtc"  or $func == "txtf" or $func == "txtn") {		   		   
	        $sql .=$campo .", " ; 	    		 	
		 }   
	   } 
	   $sql=substr($sql,0,strlen($sql)- 2) ; //quita coma de mas
	   $sql.=") values ($ci," ;
	   foreach($_POST as $nombre_campo => $valor){ 	     
		  $func=substr($nombre_campo,0,4) ;		  
	     if ($func == "txtc" ) {				       		   
	        $sql .="'".$valor."', " ; 
	     }else{
		 	if ($func == "txtn"){	
			   if (empty($valor)){ //  en caso que un campo numerico venga vacio
			     $sql .= 0 .", " ;
			   }else{
		         $sql .= $valor .", " ; 
			   }	 
			}else{
			    if ($func == "txtf"){ // Realiza el cambio de fecha a formato mysql
			       $sql.="'".cambiaf_a_mysql($valor)."',";}
			} 
		 }   
	   } 
	   $sql = substr($sql,0,strlen($sql)- 2) ; //quita coma de mas 
	   $sql.= ")" ; 
	   // echo $sql ; // habilitar solo en caso de errores
       $x_sql=mysql_query($sql, $conn) or die (" No se puede insertar el registro en la tabla ". $tabla ." Error sql"); 	 
	  // echo "<br> <center> <b>Registro Almacenado con Exito</b> <br>" 
	    //  ."<p ><a href=\"=''\">Men&uacute; Principal</a> </p></center>" ;		  
   }	
}
//**************************************************************************
    function update_items($tabla,$alias){
	  global $dbi, $userinfo ;	   
	  if (!$_POST){ //#1
	?>
	  <table width="95%" border="0" cellspacing="6" cellpadding="6">
       <tr bgcolor="#CCCCCC"> 
         <td > <div align="center">Modificando <?php echo $alias ; ?></div></td>
       </tr>  
       <tr><td ><b>Seleccione <?php echo $alias ; ?> a Modificar </b>
     
	   <?php 
	       $sql ="select * from $tabla where cedula=".$userinfo['personalid'];
		   $inc= $tabla.".php" ;
		   $x_sql= mysql_query($sql,$dbi) or die ("Error 1 al extraer  ". $alias);
		    if (mysql_num_rows($x_sql) > 0) {
				$activar = "disabled='disabled'" ;			    			    
				while ($info_for= mysql_fetch_array($x_sql)){				 
                    include($inc);				  
				} // end while  
               
		    }else{			   
			   echo "No Existen elementos para modificar";
			}
		?>
		 </td></tr>  
       </table>
	<?php  
	}else{  //#1  
	    $fin_mod= $_POST['fin_mod'];
		$elem_mod =$_POST['elem_mod'];
		$id = $_POST['id'];
		if (!$fin_mod and $elem_mod){//#2 		   
		   $sql ="select * from $tabla where $id = $elem_mod and cedula=".$userinfo['personalid'] ;
		   $inc= $tabla.".php" ;
		   $x_sql= mysql_query($sql,$dbi) or die ("Error 2 al extraer ". $alias);
		    if (mysql_num_rows($x_sql) > 0) {							    			    
				while ($info_for= mysql_fetch_array($x_sql)){				 
                    include($inc);				  
				} // end while  
               
		    }else{			   
			   echo "No Existen elementos para modificar";
			}
		}else{//#2
		   //******************************************************************************************************** 
		  //Función General para la edicion de elementos de las tablas del modulo Elaborarado TSU Erik S. Medina G.**
		  // ************Contrucccion del sql con los campos que provienen del formulario deben comenzar************* 
	       // ***********con txtc txtn txtf para indicar el tipo de datos********************************************
		  //*********************************************************************************************************
		   if ($_POST) {  
	        $sql = "update $tabla set cedula = ".$userinfo['personalid'].","  ;
	         foreach($_POST as $nombre_campo => $valor){ 
	           $campo= substr($nombre_campo,4) ;
		       $func=substr($nombre_campo,0,4) ;
	           if ($func == "txtc"  or $func == "txtf" or $func == "txtn") {	
		 	    if ($func == "txtc" ) {				       		   
	              $sql .=" ". $campo." = '".$valor."', " ; 
	            }else{
		 	       if ($func == "txtn"){
				       if (empty($valor)){$valor= 0 ;}	
		             $sql .= $campo." = ". $valor .", " ; 
			       }else{
			          if ($func == "txtf"){ // Realiza el cambio de fecha a formato mysql
			           $sql.=$campo ." = '".cambiaf_a_mysql($valor)."',";}
			          } 
		           }   
	             }             	    		 	
		       }   
	         } 	   
		  $sql = substr($sql,0,strlen($sql)- 2) ; //quita coma de mas 
	      $sql.= " where $id = $fin_mod and cedula=".$userinfo['personalid'];		
	    echo $sql ; // habilitar solo en caso de errores
       $x_sql=mysql_query($sql, $dbi) or die (" No se puede insertar el registro en la tabla ". $tabla ." Error sql"); 	 
	   echo "<br> <center> <b>Registro actualizado con Exito</b> <br>" 
	       ."<p ><a href=\"modules.php?name=investigadores&op=''\">Menú Principal</a> </p></center>" ;
		  
    }//#2
   } //#1    
 }
 
//**************************************************************************
 function update_simple($tabla,$id,$value){ 
     global $conn ;
		 
		   if ($_POST) {  
	        $sql = "update $tabla set "  ;
	         foreach($_POST as $nombre_campo => $valor){ 
	           $campo= substr($nombre_campo,4) ;
		       $func=substr($nombre_campo,0,4) ;
	           if ($func == "txtc"  or $func == "txtf" or $func == "txtn") {	
		 	    if ($func == "txtc" ) {				       		   
	              $sql .=" ". $campo." = '".$valor."', " ; 
	            }else{
		 	       if ($func == "txtn"){
				       if (empty($valor)){$valor= 0 ;}	
		             $sql .= $campo." = ". $valor .", " ; 
			       }else{
			          if ($func == "txtf"){ // Realiza el cambio de fecha a formato mysql
			           $sql.=$campo ." = '".cambiaf_a_mysql($valor)."',";}
			          } 
		           }   
	             }             	    		 	
		       }   
	         } 	   
		  $sql = substr($sql,0,strlen($sql)- 2) ; //quita coma de mas 
	      $sql.= " where $id = $value";		
	    //echo $sql ; // habilitar solo en caso de errores
       $x_sql=mysql_query($sql, $conn) or die (" No se puede insertar el registro en la tabla ". $tabla ." Error sql"); 	 
	 /*  echo "<br> <center> <b>Registro actualizado con Exito</b> <br>" 
	       ."<p ><a href=\"index2.php?task=''\">Volver</a> </p></center>" ;*/
 }
 //********************************************************************************************
   function con_general($tabla,$id,$p1,$p2,$p3,$mod,$del){
     global $conn ;
			 $ext_opc="select * from $tabla" ;
			  $x_ext_opc= mysql_query($ext_opc,$conn) or die (" Error al estraer datos de $tabla") ;
				if (mysql_num_rows($x_ext_opc) > 0){	   
				 echo "<table class='table_redon'  align='center' cellspacing='6' cellpadding='6'> \n" ;
					echo "<tr><td><strong>$id </strong></td><td><strong>$p1</strong></td><td><strong>$p2</strong></td><td><strong>$p3</strong></td><td><td></td></tr>\n" ;
				   while ($res= mysql_fetch_array($x_ext_opc) ){						  
					 echo "<tr><td>".$res["$id"]."</td><td>".$res["$p1"]."</td><td>".$res["$p2"]."</td><td>".$res["$p3"]."</td><td><a href=\"index2.php?task2=$mod&id2=".$res["$id"]."\">Modificar /</a><td><a href=\"index2.php?task2=$del&id2=".$res["$id"]."\">Eliminar</a></td></tr>\n" ;
				   } // end while
				  echo "</table> \n" ;  
				}else{
				  echo " No existen elementos cargados...<br> Debe ingresararlos en las tabla de $tabla";
				}	
				 echo "<p align='center'><a href=\"index2.php?task=''\">Menú Principal</a> </p>" ; 
   }
//*************************************************************************
function cambiaf_a_normal($fecha){ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha = $mifecha[3]."/".$mifecha[2]."/".$mifecha[1] ; 
    return $lafecha; 
} 

//*************************************************************************
function cambiaf_a_mysql($fecha){ 
    ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha = $mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    return $lafecha; 
} 
//*************************************************************************
function edad($edad){// ejm 1981-10-24
	list($anio,$mes,$dia) = explode("-",$edad);
	$anio_dif = date("Y") - $anio;
	$mes_dif = date("m") - $mes;
	$dia_dif = date("d") - $dia;
	if ($dia_dif < 0 || $mes_dif < 0)
	$anio_dif--;
	return $anio_dif;
}

 ?>
