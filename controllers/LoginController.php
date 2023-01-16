<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();
            if($alertas == null){
                $usuario = Usuario::where("email",$usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta("error","El usuario NO EXISTE o no esta confirmado");
                }else{
                    if(password_verify($_POST["password"],$usuario->password)){
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;
                        header("Location: /dashboard");
                    }else{
                        Usuario::setAlerta("error","password incorrecto");

                    }
                }
                $alertas = Usuario::getAlertas();
            }
        }
        $router->render("auth/login",[
            "titulo" => "Iniciar Sesion",
            "alertas" => $alertas
        ]);
    }
    
    public static function logout(){
        session_start();
        $_SESSION =[];
        header("Location: /");
        
    }
    public static function crear(Router $router){
        $usuario = new Usuario();
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){
                $existeUsuario = Usuario::where("email",$usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta("error","El usuario Ya esta Registrado");
                    $alertas = Usuario::getAlertas();
                }else{
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->crearToken();
                    $resultado = $usuario->guardar();
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();
                    if($resultado){
                        header("Location: /mensaje");
                    }
                    
                    
                    show($usuario);


                }  
            }
             
        }
        $router->render("auth/crear",[
            "titulo" => "Crear Cuenta",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    
    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if($alertas == null){
                $usuario = Usuario::where("email",$usuario->email);
                if($usuario && $usuario->confirmado){
                    $usuario->crearToken();
                    unset($usuario->password2);
                    $usuario->guardar();
                    Usuario::setAlerta("exito","Hemos Enviado las Instrucciones a tu Email");
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                }else{
                    Usuario::setAlerta("error","El usuario no existe o no esta confirmado");
                }
                $alertas = Usuario::getAlertas();   
            }
        }
        $router->render("auth/olvide",[
            "titulo" => "Olvide Password",
            "alertas" => $alertas
        ]);
    }
    
           
    public static function reestablecer(Router $router){
        $token = s($_GET["token"]);
        $mostrar = true;
        if(!$token){
            header("Location: /");
        }
        $usuario = Usuario::where("token", $token);
        if($usuario == null){
            Usuario::setAlerta("error","Token No Valido");
            $mostrar = false;
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if($alertas == null){
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header("Location: /");
                }
            }


        }

        $alertas = Usuario::getAlertas();
        $router->render("auth/restablecer",[
            "titulo" => "Restablecer Password",
            "alertas" => $alertas,
            "mostrar" => $mostrar
        ]); 
    }
    public static function mensaje(Router $router){
        $router->render("auth/mensaje",[
            "titulo" => "Restablecer Password"
        ]);        
    }
    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET["token"]);
        if(!$token){
            header("Location: /");
        };
        $usuario = Usuario::where("token",$token);
        if(empty($usuario)){
            Usuario::setAlerta("error","Token No Valido");
        }else{
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta("exito","Cuenta Creada Correctamente!");

        }
        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar",[
            "titulo" => "Confirma tu Cuenta UpTask",
            "alertas" => $alertas
        ]);          
    }
}