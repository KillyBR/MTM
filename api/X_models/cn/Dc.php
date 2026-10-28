<?php
class BaseDatosException extends Exception {}
class Dc {

    private $conexion;
  
	public function Conectar(){
		$conn = new mysqli("localhost", "root", "", "ara");
		if($conn->connect_error){
			die("No se puede conectar" . $conn->connect_error);
		}
		// die("Exito");
	    $this->conexion = $conn;
	}	
		
	public function Consulta($sql, $start, $limit){   
		$conn = $this->conexion;
		$conn->set_charset('utf8');
		
		if (is_null($start) || is_null($limit))
			$resultado = $conn->query( $sql );
		else
			$resultado = $conn->query( $sql . " LIMIT $start, $limit" );
        
		if (!$resultado){
			throw new BaseDatosException( $conn->errno . ": " . $conn->error );
		}
		
  		return $resultado; 
	}
	
	function Registros($query){
		$registro = null;
		$lista = null;

		while ( $registro = $query->fetch_assoc()) 
		{
			$lista[] = $registro;			
		}

		return $lista;
	}
	
	function ObtenerId($cid, $tabla){
		$id = null;
		$result = null;
		
		$query = "SELECT MAX(". $cid . ") AS id FROM " . $tabla;
		$result = $this->Consulta($query, null, null);
		$id = $result->fetch_assoc();

		return $id["id"];
	}
		
	public function Desconectar(){
		$conn = $this->conexion;
		$conn->close();
	}	


	function ValidarId($cid, $vid, $tabla){
		$result = null;
		
		$query = "SELECT $cid FROM $tabla WHERE $cid = $vid ";
		$result = $this->Consulta($query, null, null);
		
		return $result->num_rows;
	}

	//Funcion que se encarga de contar todos los registros de la tabla que no estan eliminados
	function Totalrt($tabla): int {
		$totalr  = null;
		$result = null;
		
		$query = "SELECT COUNT(*) AS trtbl FROM $tabla WHERE eliminado = 0";

		$result = $this->Consulta($query, null, null);
		$totalr  = $result->fetch_assoc();

		return $totalr["trtbl"];
	}


	function StrNull($val){

		//$val = is_null($val) ? 'NULL' : "'" . $val . "'";
		if (is_null($val))
			return 'NULL';

		if ($val == '')
			return 'NULL';

		return "'" . $val . "'";
	}

}	
?>