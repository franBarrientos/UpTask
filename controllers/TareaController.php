<?php 
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController{

    public static function index(){
        $proyectoid = $_GET["id"];
        if(!$proyectoid) header("Location: /dashboard");
        $proyecto = Proyecto::where("url",$proyectoid);
        session_start();

        if(!$proyecto || $proyecto->propietarioid != $_SESSION["id"]) header("Location: /404"); 
        $tareas = Tarea::belongsTo("proyectoid",$proyecto->id);
        echo json_encode($tareas);
    }

    public static function crear(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            session_start();
            $proyectoid = $_POST["proyectoid"];
            $proyecto = Proyecto::where("url",$proyectoid);
            if(!$proyecto || $proyecto->propietarioid =! $_SESSION["id"]){
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un Error al Agregar la Tarea"
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                "tipo" => "exito",
                "mensaje" => "Tarea Agregada Correctamente",
                "id" => $resultado["id"]
                ];
            echo json_encode($respuesta);
            
         }

     
     
        }

        public static function actualizar(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
    
            }
        }
        public static function eliminar(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
    
            }
        }







    }
    
