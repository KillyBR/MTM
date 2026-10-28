<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


function correo($rpd){

   $mail = new PHPMailer(true);
    
    //establecer zona horaria
    date_default_timezone_set('America/Mexico_city');

    $message = "<html>
                <head>
                    <meta charset='UTF-8'>
                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                </head>
                <body style='background-color: #ffffff;'>";
    $message .= "<meta name='viewport' content='initial-scale=1, maximum-scale=1'>";
    $message .= "<style></style>";
    $message .= "<table align='left' cellpadding='0' cellspacing='0' style='border-collapse: collapse;'>

                <tr>
                <td align='left' style='padding: 10px 0 10px 0;'>
                    <b>" . $rpd->nombre ." escribió: </b>
                </td>
                </tr>

                <tr>
                <td align='left' style='padding: 5px 0 5px 0;'>
                    <p>" . $rpd->motivo . "</p>
                </td>
                </tr>

                <tr>
                <td align='left' style='padding: 40px 0 10px 0;'>
                    <b>Información de contacto</b>
                </td>
                </tr> 
                
                <tr>
                <td align='left' style='padding: 5px 0 5px 0;'>
                    <p>Correo: " . $rpd->email . "</p>
                    <p>Teléfono: " . $rpd->telefono . "</p>
                </td>
                </tr>                 
                </table>";
    $message .= "</body></html>";

    // try {

        // $mail->SMTPOptions = array(
        // 	'ssl' => array(
        // 	'verify_peer' => false,
        // 	'verify_peer_name' => false,
        // 	'allow_self_signed' => true
        // 	)
        // );

        //Server settings
        $mail->SMTPDebug = 0;                                       // SMTP::DEBUG_SERVER Enable verbose debug output 0 = no muestra nada, 2 = muestra toda la lista de errores
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'mail.devness.mx';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'appmailer@devness.mx';         //usuario SMTP
        $mail->Password   = 'devness@M315_L8';                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->CharSet = 'UTF-8';

        //Recipients
        $mail->setFrom('appmailer@devness.mx', 'Miguel Torres Compositor');
        // foreach ($arMails as $correo) {
        $mail->addAddress('abarron@devness.mx');     // Add a recipient
        // }
        //$mail->addAddress('jadiaz@devness.mx');     // Add a recipient
        // $mail->addAddress('ellen@example.com');               // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('hustla9524@gmail.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $subject = 'Mensaje de: ' . $rpd->nombre;
        $subject = "=?UTF-8?B?".base64_encode($subject)."=?=";
        // $mail->AddEmbeddedImage('controllers/fn/mail/img/Logo_devness3.png','logotipo');
        // $mail->AddEmbeddedImage('controllers/fn/mail/img/background-mail-header.png','header');
        
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;

        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->Body = $message;

        $mail->send();
    // } catch (Exception $e) {
        // echo json_encode(["success"=>false, "mensaje" => 'MAIL' . $mail->ErrorInfo]);
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    // }
    return true;

}
?>