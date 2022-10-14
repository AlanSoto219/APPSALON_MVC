<?php 

namespace Classes ;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre,$email,$token){
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail -> isSMTP();
        $mail -> Host = 'smtp.mailtrap.io';
        $mail -> SMTPAuth = true;
        $mail -> Port = 2525;
        $mail -> Username = 'e67e350c725199';
        $mail -> Password = '1850998c51784c';

        $mail -> setFrom('cuentas@appsalon.com');
        $mail -> addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail -> Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail -> isHTML(TRUE);
        $mail -> CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola ". $this->nombre ." has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail -> Body = $contenido;
        // Enviar el email 
        $mail -> send();
    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail -> isSMTP();
        $mail -> Host = 'smtp.mailtrap.io';
        $mail -> SMTPAuth = true;
        $mail -> Port = 2525;
        $mail -> Username = 'e67e350c725199';
        $mail -> Password = '1850998c51784c';

        $mail -> setFrom('cuentas@appsalon.com');
        $mail -> addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail -> Subject = 'Reestablece tu Password';

        // Set HTML
        $mail -> isHTML(TRUE);
        $mail -> CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola ". $this->nombre ." has solicitado reestablecer tu password, siguel el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/recuperar?token=".$this->token."'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail -> Body = $contenido;
        // Enviar el email 
        $mail -> send();
    }
}