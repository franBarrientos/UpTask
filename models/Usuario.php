<?php

namespace Model;
class Usuario extends ActiveRecord{
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id","nombre","email","password","token","confirmado"];
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;
    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->token = $args["token"] ?? "";
        $this->confirmado = $args["confirmado"] ?? 0;
    }
    public function validarNuevaCuenta(){
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->password || strlen($this->password) < 6){
            self::$alertas["error"][]="El Password del Usuario es Obligatorio Y Debe contener al menos 6 caracteres";
        }
        if($this->password != $this->password2){
            self::$alertas["error"][]="Los Passwords no son Iguales";
        }
        return self::$alertas;
    }
    public function hashPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }
    public function crearToken(){
        $this->token = uniqid();
    }
    public function validarEmail(){
        if(!$this->email){
            self::$alertas["error"][]="El email es obligatorio";
        }
        if(!strpos($this->email,"@")){
            self::$alertas["error"][]="El email no posee un formato Valido";
        }
        return self::$alertas;
    }
    public function validarPassword(){
        if(!$this->password || strlen($this->password) < 6){
            self::$alertas["error"][]="El Password del Usuario es Obligatorio Y Debe contener al menos 6 caracteres";
        }
        return self::$alertas;

    }
    public function validarLogin(){
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->password || strlen($this->password) < 6){
            self::$alertas["error"][]="El Password del Usuario es Obligatorio Y Debe contener al menos 6 caracteres";
        }
        if(!strpos($this->email,"@")){
            self::$alertas["error"][]="El email no posee un formato Valido";
        }
        return self::$alertas;
    }
    public function validar_perfil(){
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->nombre){
            self::$alertas["error"][]="El Nombre del Usuario es Obligatorio";
        }
        return self::$alertas;
    }
    public static function validarNuevosPasswords($password1 ,$password2){
        if(!$password1 || strlen($password1) < 6){
            self::$alertas["error"][]="El Password del Usuario es Obligatorio Y Debe contener al menos 6 caracteres";
        }
        if(!$password2 || strlen($password2) < 6){ 
            self::$alertas["error"][]="El Password del Usuario es Obligatorio Y Debe contener al menos 6 caracteres";
        }
        return self::$alertas;

    }
}