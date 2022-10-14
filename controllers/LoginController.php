<?php 
namespace Controllers;

use Model\Usuario;
use Classes\Email;
use MVC\Router;

$router = new Router();

class LoginController {
    // <<<<<<<< Login >>>>>>>> //
    public static function login(Router $router){    
        $alertas = [];
        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth -> validarLogin();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);

                if($usuario){
                    // Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Autenticar el usuario 
                        session_start();
                        $_SESSION['id'] = $usuario -> id;
                        $_SESSION['nombre'] = $usuario -> nombre ." ". $usuario -> apellido;
                        $_SESSION['email'] = $usuario -> email;
                        $_SESSION['login'] = true;
                        
                        // Redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null ;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                    }
                }else{
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router -> render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth   
        ]);
    }
    // <<<<<<<< Logout >>>>>>>> //
    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    // <<<<<<<< Olvide >>>>>>>> //
    public static function olvide(Router $router){
        $alertas = [];
      
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1"){
                    // Generar un token 
                    $usuario->crearToken();
                    $usuario->guardar();

                    // TODO: Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito','Revisa tu email');
                    $alertas = Usuario::getAlertas();
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');                   
                }
            }

        }
        $alertas = Usuario::getAlertas();
        $router -> render('auth/olvide-password' , [
            'alertas' => $alertas
        ]);
    }
    // <<<<<<<< Recuperar >>>>>>>> //
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        
        // Buscar usuario por token 
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $error = true;
        }
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    // <<<<<<<< Crear >>>>>>>> //
    public static function crear(Router $router){
        $usuario = new Usuario;
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario -> sincronizar($_POST);
            $alertas = $usuario -> validarNuevaCuenta();
            // Revisar que el arreglo esta vacio
            if(empty($alertas)){
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    // Hashear el password
                    $usuario->hashPassword();
                    // Generar un token único
                    $usuario->crearToken();    
                    // Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();       
                    // Crear el usuario 
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router -> render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas           
        ]);
    }
    // <<<<<<<< Confirmar >>>>>>>> //
    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
        }else{
            // Modificar al usuario como confirmado 
            $usuario -> confirmado = 1;
            $usuario -> token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router -> render('auth/confirmar-cuenta', [
            'alertas' => $alertas 
        ]);
    }
}