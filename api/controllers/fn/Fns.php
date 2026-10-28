<?php

class Fns {

    public function params_list($call){
        if($call=="L"){
            $se = $this->valNull($_REQUEST['se']);
            $st = $this->valNull($_REQUEST['st']);
            $li = $this->valNull($_REQUEST['li']);
        }
        else{
            $se = null;
            $st = null;
            $li = null;
        }
            
        $obj = (object) array(
            'se' => $se,
            'st' => $st,
            'li' => $li
        );

        return $obj;
    }    

    private function valNull($val){

			if($val=='null'){
				return null;
			}
			if($val == "''"){
				return null;
			}
            if($val == ""){
				return null;
			}    

        return $val;
    }

    public function saludar($var){
        return 'hola '. $var;   
    }


}


function logFile($nomFile, $msj){
    $lf = fopen("logs/".$nomFile.".txt", 'a') or die ("Error creando archivo");
    fwrite($lf, date("Y-m-d h:i.s a") .": ". $msj."\n") or die("Error escribiendo el archivo");
    fclose($lf);
}

?>