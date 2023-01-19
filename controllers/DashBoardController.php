<?php
namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashBoardController{
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION["id"];
        $proyectos = Proyecto::belongsTo("propietarioid",$id);
        $router->render("dashboard/index",[
            "titulo" => "Proyectos",
            "proyectos" => $proyectos
        ]);
    }
    public static function crear_proyecto(Router $router){
        session_start();
        $alertas = [];
        isAuth();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioid = $_SESSION["id"];
                $proyecto->guardar();
                header("Location: /proyecto?id=".$proyecto->url);
            }
        }
        $router->render("dashboard/crear-proyecto",[
            "titulo" => "Crear Proyecto",
            "alertas" => $alertas
        ]); 
    }
    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION["id"]);
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();
            if(empty($alertas)){
                $existeUsuario = Usuario::where("email",$usuario->email);
                if($existeUsuario && $existeUsuario->id != $usuario->id){
                    Usuario::setAlerta("error","Email no valido, Cuenta ya registrada");
                }else{
                $usuario->guardar();
                Usuario::setAlerta("exito","Guardado Correctamente");
                $_SESSION["nombre"] = $usuario->nombre;
                }
                $alertas = Usuario::getAlertas();

            }
        }
        $router->render("dashboard/perfil",[
            "titulo" => "Perfil",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]); 
    }
    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET["id"];
        if(!$token) header("Location: /dashboard");
        $proyecto = Proyecto::where("url",$token);
        if($proyecto->propietarioid != $_SESSION["id"]) header("Location: /dashboard");
        $titulo = $proyecto->proyecto;
        $router->render("dashboard/proyecto",[
            "titulo" => $titulo
        ]); 
    }
    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION["id"]);
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $passwordActual = $_POST["password_actual"];
            $passwordNuevo = $_POST["password_nuevo"];
            $alertas = Usuario::validarNuevosPasswords($passwordNuevo,$passwordActual);
            if(empty($alertas)){
                if(password_verify($passwordActual,$usuario->password)){
                    $usuario->password = $passwordNuevo;
                    $usuario->hashPassword();
                    $resultado =$usuario->guardar();
                    if($resultado){
                        Usuario::setAlerta("exito","Contraseña Cambiada Correctamnete");
                    }
                }else{
                    Usuario::setAlerta("error","Contraseña Incorrecta");
                }
            };
            $alertas = Usuario::getAlertas();
        }
        $router->render("dashboard/cambiar-password",[
            "titulo" => "Cambiar Password",
            "alertas" => $alertas
        ]);
    }
}