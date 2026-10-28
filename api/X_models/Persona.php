<?php
require_once 'cn/Dc.php';

class Persona extends Dc{
    public function Listado($idPersona, $pr, $de){
        $this->totalRow = 0;
        $this->trtblRow = 0;
        $this->Conectar();
        $buscar = "";
        
        if (!is_null($pr->se)){
            $buscar = "AND CONCAT(
                           COALESCE(PE.nombre, ''),
                           COALESCE(PE.apellidop, ''),
                           COALESCE(PE.apellidom, '')
                           ) LIKE '%$pr->se%' ";
        }

        $sql = "SELECT PE.idPersona,
                       PE.nombre,
                       PE.apellidop,
                       PE.apellidom,
                       PE.fechac,       
                       PE.fecham
                  FROM personas AS PE                   
                 WHERE (PE.idPersona = '$idPersona' OR '$idPersona' = '')
                       $buscar
                   AND PE.eliminado = $de";
        
        $query = $this->Consulta($sql, $pr->st, $pr->li);        
        $this->totalRow = $query->num_rows;
        $this->Data = $this->totalRow > 0 ? $this->Registros($query) : null;
        
        if (is_null($idPersona)){
            $query = $this->Consulta($sql, null, null);
            $this->trtblRow = $query->num_rows;
        }
        
        $this->Desconectar();
    }

    public function Insertar($obj, $lastId){
        $this->Conectar();
        
        $sql = "INSERT INTO personas (nombre,
                                    apellidop,
                                    apellidom,
                                    fechac,
                                    eliminado
                                    )
                            VALUES ('$obj->nombre',
                                    '$obj->apellidop',
                                    '$obj->apellidom',
                                    NOW(),
                                    0
                                    )";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        if ($lastId==true) {
           $this->idRow= $this->ObtenerId('idPersona', 'empresas');
        }

        $this->Desconectar();
    }


    public function Actualizar($obj){
        $this->Conectar();
        
        $sql = "UPDATE personas 
                   SET nombre = '$obj->nombre',
                       apellidop = '$obj->apellidop',
                       apellidom = '$obj->apellidom',
                       fecham = NOW()
                 WHERE idPersona = $obj->idPersona
                       ";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        $this->Desconectar();
    }

  
    public function Eliminar($obj){
        $this->Conectar();
        
        $sql = "UPDATE personas 
                   SET nombre = CONCAT($obj->idPersona, nombre),
                       fecham = NOW(),
                       eliminado = 1
                 WHERE idPersona = $obj->idPersona
                       ";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        $this->Desconectar();
    } 


}
?>