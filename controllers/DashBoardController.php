<?php
namespace Controllers;

use Model\Proyecto;
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
        $router->render("dashboard/perfil",[
            "titulo" => "Perfil"
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
}