<?php

class Rse{
    
//  variables que espera $succes, $msg, $list, $total, $trtbl en caso de ser metodos guardar y eliminar se informan $l=null y $t=null     
//  params (bool, str, array, int) return str
    public function sendJason($s, $m, $l, $t, $r){
        $arr = [];
        if (is_null($l) && is_null($t))
            $arr = array("success" => $s, "msg" => $m);
        else
            if (is_null($r))
                $arr = array("success" => $s, "total" => $t, "list" => $l, "msg" => $m);
            else
                $arr = array("success" => $s, "total" => $t, "list" => $l, "msg" => $m, "trtbl" => $r);
        
        echo json_encode($arr);
    }

//  params (str, object) return str
    public function msgErr($t, $e){
        $m = 'Error desconocido';

        switch($t){
            case 'ses':
                $m = 'Sesión Usuario: ' . $e->getMessage();
                break;
            case 'sec':
                $m = 'Seguridad: ' . $e->getMessage();
                break;
            case 'dba':                
                $m = 'Base de Datos: ' . $this->msgErrDB($e);   
                break;
            case 'ser':
                $m = 'Servidor: ' . $e->getMessage();
                break;
        }

        return $m;
    }

//  params (object) return str
    function msgErrDB($e){

        // print_r($e); 
        $msj = "";
        $m = $e->getMessage();
        $n = strlen($m); 
        $errno = "no inf.";
        $error = null;            

        if ($n > 5){
            $errno = substr($m, 0, 4);
            $error = substr($m, 6, $n);
        }

        $arr = [                
                1062 => 'Registro duplicado',
                1064 => 'Error en script model, verificar la consulta',
                1136 => 'Los campos de la consulta no coinciden',
                1146 => 'La tabla no existe! ' . $error,
                1054 => 'El campo de la consulta no existe. ' . $error,
                1452 => 'Error de llave foranea, alguna id al que se hace referencia no existe'
            ];

        if ($errno){        
            foreach($arr as $key => $value){
                if ($errno == $key){
                    $msj = $value;
                    break;
                }            
            }           
        }

        if($msj == "")
            $msj = "!Error no tratado! <br> No. Error: " . $errno . " " . $error;

        return $msj;
    }

}
?>