<?php

require_once 'fn/Fns.php';

class Turnstile extends Fns{
    public $success = true;
    public $msg = "OK";
    public $total = null;
    public $list = null;
    public $trtbl = null;


    public function Valida(){
        $rpd = file_get_contents("php://input");
        $rpd = json_decode($rpd);  

        $status = true;
        $token = $rpd->captchaToken;
        
        if (empty($token)){
            http_response_code(400);
            $this->success = false;
            $this->msg = "El token de Cloudflare es requerido";
            return;        
        }

        $secretKey = "0x4AAAAAAEuak5QT90Q_iAjJ_jABNTGBGm4";
        $remoteIp = $_SERVER['REMOTE_ADDR'];

        $postData = [
            "secret" => $secretKey,
            "response" => $token,
            "remoteip" => $remoteIp
        ];

        $ch = curl_init('https://cloudflare.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            http_response_code(500);
            $this->success = false;
            $this->msg = "Error al conectar con Cloudflare";
            return;
        }

        $result = json_decode($response, true);

        if (isset($result['success']) && $result['success'] === true) {
            http_response_code(200);
            $this->msg = "CAPTCHA OK";
        }
        else {
            $errorCodes = $result['error-codes'] ?? ['unknown-error'];
            http_response_code(400);
            $status = false;
            $this->msg = "Validación de seguridad fallida (Turnstile). ErrorCode:" . implode(" |", $errorCodes);
        }

        $this->success = $status;
    }    


}
?>