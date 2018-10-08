<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : ConsultaDB.class.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creación      : 25/02/2008
		Objetivo / Resumen  : Clase que realiza consultas a BBDD y regresar una consulta html o xml
		Ultima Modificación : 25/02/2008
------------------------------------------------------------------------------------------------ */
include_once("../FrameWork/Class/DataStore.class.php");
//------------------------------------------------------ pendiente modificacion

class DataWindow{

	private $datastore;
	private $titulo;
	private $titCols;
	private $enableCols;
	
	function __construct($titulo, $titcols, $datastore, $enableCols=""){
		$this->setTitulo($titulo);
		$this->setTitCols($titcols);
		$this->setEnableCols($enableCols);
		$this->setDataStore($datastore);
	}

	function setDataStore($datastore){
		$this->datastore        = $datastore;
	} 

	function setTitulo($titulo){
		$this->titulo        = $titulo;
	}

	function setEnableCols($enableCols){
		if(is_array($enableCols)) $this->enableCols    = $enableCols;
		else for($i=0; $i < count($this->titCols); $i++) $this->enableCols[] = true;
	}

	function setTitCols($titcols){
		$this->titCols        = $titcols;
	}
	
	private function cambiaColor($tr){
	  	if($tr == "<tr class=par>") $tr = "<tr class=impar>";
	  	else $tr = "<tr class=par>";
		return $tr;
	}

	private function celdaMantenimiento(){
		return '<th colspan="3">Acción</th>';
	}

	private function celdasMantenimiento($id,$url){
		$cad = '<td><a href="'.$url.'?accion=ver&'.$this->datastore->getid().'='.$id.'"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Ver registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/find.png" width=24 height=24 border=0></a></td>';
		
		$cad .= '<td><a href="'.$url.'?accion=edit&'.$this->datastore->getid().'='.$id.'"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Editar registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/edit.png"  width=24 height=24 border=0></a></td>';

		$cad .= '<td><a href="'.$url.'?accion=del&'.$this->datastore->getid().'='.$id.'"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Eliminar registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/delete.png"  width=24 height=24 border=0></a></td>';

		return $cad;
	}
	private function celdaImpresion($id,$url){
/*		$window = "javascript:window.open('".$url."?".$this->datastore->getid()."=".$id."')";//,'Vista Previa','toolbar=no,menubar=no,location=no,scrollbars=yes')";
		$cad = '<td><a href="'.$window.'" '; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Imprimir Registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/print.png"  width=24 height=24 border=0></a></td>';
		return $cad;
*/
		$window = "javascript:window.open('".$url.'?'.$this->datastore->getid().'='.$id."','_blank','toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes')";
		$cad = '<td><a href="#" onClick="'.$window.'" '; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Imprimir Registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/print.png"  width=24 height=24 border=0></a></td>';
		return $cad;
/*
		$cad = '<td><a href="'.$url.'?accion=print&'.$this->datastore->getid().'='.$id.'"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Imprimir Registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
*/
	}

	private function celdaSeleccion($id,$url){
		$cad = '<td><input name="chk_'.$id.'" type="checkbox" value="'.$id.'"/></td>';
		return $cad;
	}

	private function boton($url){
		$cad  = '<table class="consulta"><tr><th>'; 
		$cad .= '<a href="'.$url.'?accion=add"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Agregar registro.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/add.png"  width=24 height=24 border=0>Agregar registro</a></th>';
		
		$cad .= '<th><a href="'.$url.'?accion=refresh"'; 
		$cad .= 'onMouseOver="window.status='."''; overlib('Actualizar vista.'); return true;".'"';
		$cad .= 'onMouseOut="window.status='."''; nd(); return true;".'">';
		$cad .= '<img src="../FrameWork/Images/iconos/refresh.png"  width=24 height=24 border=0>Actualizar la vista</a>';
		$cad .= '</th></tr></table>';

		return $cad;
	}
	
	public function getTabla($mantenimiento=false, $url="",$imprime=false, $url_prn="",$seleccion=false, $url_sel=""){
		$cadena	= "<h3>" . $this->titulo . "</h3>";
    $this->datastore->retrieve();
		$fila = $this->datastore->getDataSet();
		if($fila < 0){
			$cadena .= "<h4>No hay Datos para la Consulta</h4>";
		} else {
			//----------------------------------------------------------------- Titulos de consulta
			$cadena .= '<table class="consulta"><tr>';
			if($seleccion) $cadena .= "<th>Sel.</th>";
			for($i=0; $i < count($this->titCols); $i++)
				if($this->enableCols[$i]) $cadena .= "<th>" . $this->titCols[$i] . "</th>";
			if($mantenimiento) $cadena .= $this->celdaMantenimiento();
			$cadena .= "</tr>";
			//------------------------------------------------------------- Ejecucion del query sobre la tabla talypascual
			$tr = "";
			for($i=0; $i < count($fila); $i++){
				$tr = $this->cambiaColor($tr);
				$columna = $fila[$i];
				$cadena .= $tr;
				if($seleccion) $cadena .= $this->celdaSeleccion($fila[$i][$this->datastore->getId()],$url_sel);
				for($j=0; $j < count($columna)/2; $j++){
					if($this->enableCols[$j]) $cadena .= "<td>" . $columna[$j] . "</td>";
				}
				if($mantenimiento) $cadena .= $this->celdasMantenimiento($fila[$i][$this->datastore->getId()],$url);
				if($imprime) $cadena .= $this->celdaImpresion($fila[$i][$this->datastore->getId()],$url_prn);
				$cadena .= "</tr>";
			}
		}
		$cadena .= "</table>";
		if($mantenimiento) $cadena .= $this->boton($url);
		return $cadena;
	}
	public function getTablaColumnas($columnas){
		$cadena	= "<h3>" . $this->titulo . "</h3>";
    $this->datastore->retrieve();
		$fila = $this->datastore->getDataSet();
		if($fila < 0){
			$cadena .= "<h4>No hay Datos para la Consulta</h4>";
		} else {
			//----------------------------------------------------------------- Titulos de consulta
			$cadena .= '<table class="consulta"><tr>';
			for($i=0; $i < count($this->titCols); $i++)
				if($this->enableCols[$i]) $cadena .= "<th>" . $this->titCols[$i] . "</th>";
			$cadena .= "</tr>";
			//------------------------------------------------------------- Ejecucion del query sobre la tabla talypascual
			$tr = "";
			for($i=0; $i < count($fila); $i++){
				$tr = $this->cambiaColor($tr);
				$columna = $fila[$i];
				$cadena .= $tr;
				for($j=0; $j < count($columnas); $j++){
					if($this->enableCols[$j]) $cadena .= "<td>" . $columna[$columnas[$j]] . "</td>";
				}
				$cadena .= "</tr>";
			}
		}
		$cadena .= "</table>";
		return $cadena;
	}

	public function getConsultaLibre(){
		$cadena	= "<h3>" . $this->titulo . "</h3>";
		$resultado = $this->datastore->doConsulta();
		$fila = $resultado["resultset"];
		if($fila < 0){
			$cadena .= "<h4>No hay Datos para la Consulta</h4>";
			return $cadena;
		}
		//----------------------------------------------------------------- Titulos de consulta
		$cadena .= '<table class="consulta">';
		$tr = "";
		$titcampo = $this->datastore->getTitColumns();
		for($i=0; $i < count($titcampo); $i++) {
			$columna = $fila[0];
			$tr = $this->cambiaColor($tr);
			$cadena .= $tr;
			$cadena .= "<th>" . $titcampo[$i] . "</th>";
			$cadena .= "<td>" . $columna[$i] . "</td>";
			$cadena .= "</tr>";
		}
		$cadena .= "</table>";
		return $cadena;
	}

}


?>
