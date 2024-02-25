<?php


class Database {

    private $host;
    private $usuario;
    private $contrasena;
    private $base_de_datos;
    private $conexion;
    private $port;

    public function __construct($host,$usuario,$contrasena,$base_de_datos,$port){

       $this->host = $host;
       $this->usuario = $usuario;
       $this->contrasena = $contrasena;
       $this-> base_de_datos = $base_de_datos;
       $this-> port = $port;

       $this->conectar();
    }

    private function conectar(){

        $this->conexion = new mysqli($this->host,$this->usuario,$this->contrasena,$this->base_de_datos, $this->port);

        if($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion-> connect_error);
        }
    }

    function obtner_id(){
       return $this->conexion->insert_id;
    }

    public function query($consulta){
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