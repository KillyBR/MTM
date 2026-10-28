<?php

require_once 'fn/Fns.php';
require_once 'fn/mail/Mailer.php';


class Mensajes extends Fns{
    public $success = true;
    public $msg = "OK";
    public $total = null;
    public $list = null;
    public $trtbl = null;

    public function Enviar(){
        $rpd = file_get_contents("php://input");
        $rpd = json_decode($rpd);        

        $estatus = false;
        


        if (filter_var($rpd->email, FILTER_VALIDATE_EMAIL)){
            if(correo($rpd)){
                $estatus = true;
                $this->msg="Mensaje enviado";
            }
            else {
                $this->msg="Error al enviar el correo";
            }
        }
         else {
            $this->msg="Correo no valido";
        }
        
        $this->success = $estatus;
    }    

}
?>