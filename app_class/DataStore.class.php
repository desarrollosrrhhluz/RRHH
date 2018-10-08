<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : ConsultaDB.class.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creación      : 25/02/2008
		Objetivo / Resumen  : Clase que realiza consultas a BBDD y regresar una consulta html o xml
		Ultima Modificación : 25/02/2008
------------------------------------------------------------------------------------------------ */

include_once("Database.class.php");
//------------------------------------------------------ pendiente modificacion

class DataStore{

	private $db;
	private $dataset;
	private $sql="";
	private $table="";
	private $columns=array("");
	private $updatecolumns=array();
	private $where="";
	private $order="";
	private $distinct=false;
	private $resultset=array("");
	private $id="";

	public function getColumns(){
		return $this->db->getColumnsNames();
	}

	public function getNumRows(){
		return $this->db->getNumRows();
	}

	public function getColumnsLen(){
		return $this->db->getColumnsLen();
	}

	public function getSQL(){
		return $this->sql;
	}

	public function getValues($row){
		if($row<0) $values = array();
		else       $values = $this->db->fetch($row); 
		return $values;
	}

	public function getValueCol($row,$col){
		if($row<0) $value = "";
		else {
			$fila = $this->db->fetch($row); 
			$value = $fila[$col]; 
		}
		return $value;
	}

	public function getValuesCol($col){
		for($i=0 ; $i< $this->db->getNumRows() ; $i++){
			$fila  = $this->db->fetch($i); 
			$valuescol[] = $fila[$col];
		}
		return $valuescol;		
	}
	
	public function getValuesId($id){
		$values = array();
		foreach($this->dataset as $fila)
			if($fila[$this->id]==$id) {
		       $values = $fila;
		       break;
			}   
		return $values;
	}
	
	function __construct($sistema="", $sql="", $table='', $columns=array(""), $where='', $order='', $distinct=false, $id="", $updcols=array()){
		$this->setDataStore($sistema, $sql, $table, $columns, $where, $order,$distinct,$id, $updcols);
	}

	function setDataStore($sistema, $sql="", $table='', $columns=array(""), $where='', $order='', $distinct=false, $id="", $updcols=array()){
		$this->db = new Db($sistema);
        if(strcmp($sql,"")!=0 || strcmp($table,'')!=0){
            if($sql!=""){
                $this->sql            = $sql;
                $this->columns        = $columns;
            } else {
                $this->table          = $table;
                $this->columns        = $columns;
                $this->updatecolumns  = $updcols;
                $this->where          = $where;
                $this->order          = $order;
                $this->distinct       = $distinct;
                $this->id             = $id;
                $cols                 = get_commasA($this->columns, false);
                if(is_array($this->table)){
                    $this->sql            = get_select(get_commasA($this->table, false), $cols, $this->where, $this->order, $this->distinct);
                } else {
                    $this->sql            = get_select($this->table, $cols, $this->where, $this->order, $this->distinct);
                }
                //echo $this->sql;
            }
            $this->retrieve();
        }

	}
	
	public function retrieve(){
		if($this->db < 0) return -1;
		if($this->sql!=""){
			$sql = $this->sql;// .";";
			$this->dataset = array();
			if($this->db->query($sql) < 1)
				return -1;
			else {
				for($i=0 ; $i< $this->db->getNumRows() ; $i++){
					$this->dataset[] = $this->db->fetch($i); 
				}
			}
		}
		return $this->dataset;		
	}

	public function getDataSet(){
		return	$this->dataset;
	}

	public function getId(){
		return	$this->id;
	}
	
	public function getXML(){
		//-------------------------generar tag para informacion de la tabla y campos y titulos
		//-------------------------- nodo principal
		//-------------------------- nodo elemento de titulos
		//-------------------------- nodo elemento de columnas
        $cadena	= '<?xml version="1.0" encoding="UTF-8"?>';
		$cadena	.= "\n<consulta>\n";
		$fila = $this->retrieve();
		if($fila < 0){
			$cadena .= "<error>No hay Datos para la Consulta</error>\n";
			return $cadena;
		}
		//------------------------------------------------------------- Ejecucion del query sobre la tabla talypascual
		for($i=0; $i < count($fila); $i++){ 
			$columna = $fila[$i];
			$campos = $this->getColumns();
			$cadena .= '<registro row="'.$i.'">'."\n";
			for($j=0; $j < count($campos); $j++){ 
				$cadena .= "<".$campos[$j].">" . $columna[$j] . "</".$campos[$j].">\n";
			}
			$cadena .= "</registro>\n";
		}
		$cadena .= "</consulta>\n";
		return $cadena;
	}

	public function executesql($sql){
		if($this->db < 0) return -1;
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}

	public function insertRow($columns=array(), $values=array()){
		if($this->db < 0) return -1;
		$cols  = get_commasA($columns, false);
		$vals  = get_commasA($values);
		if(is_array($this->table)){
			$sql   = get_insert($this->table[0], $cols, $vals);  
		} else {
			$sql   = get_insert($this->table, $cols, $vals);  
		}
		//echo $sql;
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}

	public function updateRow($row=-1,$columns=array(), $values=array()){
		if($row<0) return 0;
		if($this->db < 0) return -1;
		$msetwhere = get_mult_set($this->getColumns(),$this->getValues($row),"and");
		$mset      = get_mult_set($columns, $values);
		if(is_array($this->table)){
			$sql       = get_update($this->table[0], $mset, $msetwhere);  
		} else {
			$sql       = get_update($this->table, $mset, $msetwhere);  
		}
		//echo $sql;
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}

	public function updateRowId($id=-1,$columns=array(), $values=array()){
		if($row<0) return 0;
		if($this->db < 0) return -1;
		$msetwhere = get_simp_set($this->id, $id,'=',false);
		$mset      = get_mult_set($columns, $values);
		if(is_array($this->table)){
			$sql       = get_update($this->table[0], $mset, $msetwhere);  
		} else {
			$sql       = get_update($this->table, $mset, $msetwhere);  
		}
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}
	
	public function deleteRowId($id=-1){
		if($row<0) return 0;
		if($this->db < 0) return -1;
		$msetwhere = get_simp_set($this->id, $id,'=',false);
		if(is_array($this->table)){
			$sql       = get_delete($this->table[0], $msetwhere);  
		} else {
			$sql       = get_delete($this->table, $msetwhere);  
		}
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}

	public function updateRowIdRequest($id=-1,$request=""){
		if($row<0) return 0;
		if($this->db < 0) return -1;
		$values    = $this->getValuesRequest($request);
		$columns   = $this->getUpdateableColumns();
		$msetwhere = get_simp_set($this->id, $id,'=',false);
		$mset      = get_mult_set($columns, $values);
		if(is_array($this->table)){
			$sql       = get_update($this->table[0], $mset, $msetwhere);  
		} else {
			$sql       = get_update($this->table, $mset, $msetwhere);  
		}
		//echo $sql;
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}

	public function insertRowRequest($request=array()){
		if($this->db < 0) return -1;
		$values    = $this->getValuesRequest($request);
		$columns   = $this->getUpdateableColumns();
		$cols  = get_commasA($columns, false);
		$vals  = get_commasA($values);
		if(is_array($this->table)){
			$sql   = get_insert($this->table[0], $cols, $vals);  
		} else {
			$sql   = get_insert($this->table, $cols, $vals);  
		}
		//echo $sql;
		$this->db->query($sql);
		return $this->db->getAffectedRows();
	}
	
	public function getValuesRequest($request=array()){
		$values=array();
		$updCols = $this->getUpdateableColumns();
		for($i=0; $i < count($updCols); $i++){
			$values[] = $request[$updCols[$i]];
		}
		return $values;
	}
	
	private function getUpdateableColumns(){
		$values=array();
		for($i=0; $i < count($this->updatecolumns); $i++){
			if($this->updatecolumns[$i]) $values[] = $this->columns[$i];
		}
		return $values;
	}
}


?>
