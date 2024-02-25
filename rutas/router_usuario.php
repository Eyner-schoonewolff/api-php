<?php

require_once "./controladores/usuarios.php";


class RouterUsuario
{
    private $usuarioControlador;
    function __construct()
    {
        $this->usuarioControlador = new UsuarioControlador();

    }

    function obtenerUsuarios()
    {
        $usuario = $this->usuarioControlador->obtenerUsuario();
        return $usuario;
    }

    function registrarUsuario()
    {
        $body = file_get_contents('php://input');

        // Decodificar el JSON en un array asociativo de PHP
        $data = json_decode($body, true);

        // Verificar si la decodificación fue exitosa
        if ($data === null) {
            // La decodificación falló
            http_response_code(500); 
            return json_encode(array("error" => "Error al decodificar el JSON"));
        } else {

            $nombre = $data["nombre"];
            $correo = $data["correo"];

            // Crear un array asociativo con los datos del usuario
            $datosUsuario = array("nombre" => $nombre, "correo" => $correo);

            // Llamar al método para crear un usuario y pasarle los datos
            $resultado = $this->usuarioControlador->crearUsuario($datosUsuario);

            // Ahora puedes usar $resultado como desees
            return $resultado;
        }
    }

    function actualizar()
    {

        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id === null) {
            http_response_code(404); 
            return json_encode(array("error" => "No se proporcionó un ID"));
        }

        $body = file_get_contents("php://input");
        $data = json_decode($body, true);

        if ($data === null) {
            http_response_code(404); 
            return json_encode(array("error" => "Error al decodificar el JSON"));
        } else {

            $nombre = $data["nombre"];
            $correo = $data["correo"];

            $actualizarUsuario = array("nombre" => $nombre, "correo" => $correo);

            $actualizacion = $this->usuarioControlador->actualizarUsuario($id, $actualizarUsuario);

            return $actualizacion;
        }
    }

    function actualizarEstado()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id === null) {

            http_response_code(404); 
            return json_encode(array("error" => "No se proporcionó un ID"));
        }
        
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);

        if ($data === null) {
            http_response_code(404); 
            return json_encode(array("error" => "Error al decodificar el JSON"));

        }else{
            $estado = $data["estado"];
            $actualizacion = $this->usuarioControlador->actualizarEstadoUsuario($id,$estado);
            return $actualizacion;
        }


    }
}