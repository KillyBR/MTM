<?php
require_once 'cn/Dc.php';

class Asistencia extends Dc{
    public function Listado($idAsistencia, $pr, $de){
        $this->totalRow = 0;
        $this->trtblRow = 0;
        $this->Conectar();
        $buscar = "";
        
        if (!is_null($pr->se)){
            $buscar = "AND CONCAT(
                           COALESCE(AT.fecha, ''),
                           COALESCE(AT.so, '')
                           ) LIKE '%$pr->se%' ";
        }

        $sql = "SELECT AT.idAsistencia,
                       AT.idPersona,
                       AT.registro,
                       AT.so,
                       AT.ip,
                       ST_AsText(AT.ubicacion) AS ubicacion,       
                       AT.fecha,
                       AT.hora,
                       AT.fechac,
                       AT.fecham
                  FROM asistencias AS AT                  
                 WHERE (AT.idAsistencia = '$idAsistencia' OR '$idAsistencia' = '')
                       $buscar
                   AND AT.eliminado = $de";
        
        $query = $this->Consulta($sql, $pr->st, $pr->li);        
        $this->totalRow = $query->num_rows;
        $this->Data = $this->totalRow > 0 ? $this->Registros($query) : null;
        
        if (is_null($idAsistencia)){
            $query = $this->Consulta($sql, null, null);
            $this->trtblRow = $query->num_rows;
        }
        
        $this->Desconectar();
    }

    public function Insertar($obj, $lastId){
        $this->Conectar();
        
        $sql = "INSERT INTO asistencias (idPersona,
                                    registro,
                                    so,
                                    ip,
                                    ubicacion,
                                    fecha,
                                    hora,
                                    fechac,
                                    eliminado
                                    )
                            VALUES ('$obj->idPersona',
                                    '$obj->registro',
                                    '$obj->so',
                                    '$obj->ip',
                                    ST_PointFromText('$obj->ubicacion', 4326),
                                    CURDATE(),
                                    CURTIME(),
                                    NOW(),
                                    0
                                    )";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        if ($lastId==true) {
           $this->idRow= $this->ObtenerId('idAsistencia', 'asistencias');
        }

        $this->Desconectar();
    }


    public function Actualizar($obj){
        $this->Conectar();
        
        $sql = "UPDATE asistencias 
                   SET registro = '$obj->registro',
                       so = '$obj->so',
                       fecham = NOW()
                 WHERE idAsistencia = $obj->idAsistencia
                       ";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        $this->Desconectar();
    }

  
    public function Eliminar($obj){
        $this->Conectar();
        
        $sql = "UPDATE asistencias 
                   SET fecham = NOW(),
                       eliminado = 1
                 WHERE idAsistencia = $obj->idAsistencia
                       ";

        $query = $this->Consulta($sql, null, null);                        
        $this->Result = $query;

        $this->Desconectar();
    } 


}
?>