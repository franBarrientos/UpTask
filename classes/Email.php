<?php 
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $nombre;
    public $email;
    public $token;
     public function __construct($email, $nombre, $token)
     {
         $this->nombre = $nombre;
         $this->email = $email;
         $this->token = $token;
     }
     public function enviarConfirmacion(){
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Port = 2525;
         $mail->Username = '14d9e40cdaa56d';
         $mail->Password = 'a14337ae343698';
         $mail->setFrom("UpTask@gmail.com");
         $mail->addAddress("CuentasUpTask@gmail.com","UpTask.com");
         $mail->Subject = "Confirmar tu Cuenta";
 
         $mail->isHTML(true);
         $mail->CharSet = "UTF-8"; 
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola ".$this->nombre."</strong> Haz creado tu cuenta en UpTask solo debes confirmarla presionando el siguiente enlace</p>";
         $contenido .= "<p>Presiona aqui: <a href='htpp://localhost:3000/confirmar?token=".$this->token."'>Confirmar Cuenta</a> </p>";
         $contenido .= "<p>Si tu no solicitaste esta cuenta, puede ignorar el mensaje</p>";
         $contenido .= "</html>";
 
         $mail->Body = $contenido;
         $mail->send();
     }
     public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '14d9e40cdaa56d';
        $mail->Password = 'a14337ae343698';
        $mail->setFrom("UpTask@gmail.com");
        $mail->addAddress("CuentasUpTask@gmail.com","UpTask.com");
        $mail->Subject = "Restablece tu Password";

        $mail->isHTML(true);
        $mail->CharSet = "UTF-8"; 
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".$this->nombre."</strong> Parece que haz olvidao el password haz click en el siguiente enlace para reestablecerlo</p>";
        $contenido .= "<p>Presiona aqui: <a href='htpp://localhost:3000/reestablecer?token=".$this->token."'>Reestablecer Token</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puede ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();

     }
    
 }
 
 
 ?>
 