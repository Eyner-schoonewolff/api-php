<?php

class Database {

    private $host;
    private $usuario;
    private $contrasena;
    private $base_de_datos;
    private $conexion;

    public function __construct($host,$usuario,$contrasena,$base_de_datos){

       $this->host = $host;
       $this->usuario = $usuario;
       $this->contrasena = $contrasena;
       $this-> base_de_datos = $base_de_datos;

       $this->conectar();
    }

    private function conectar(){

        $this->conexion = new mysqli($this->host,$this->usuario,$this->contrasena,$this->base_de_datos);

        if($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion-> connect_error);
        }
    }

    function query($consulta){
        try {
            $resultado = $this-> conexion -> query($consulta);

            if(!$resultado){
                die('Error en la consulta: ' . $this->conexion->error);
            }

            return $resultado;
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    function cerrar() {
        $this->conexion->close();
    }
}