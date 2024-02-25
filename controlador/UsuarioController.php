<?php


require_once "./modelos/usuarios.php";

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="API de Usuarios", version="1.0")
 */
class UsuarioController
{
    private $usuarioModelo;

    function __construct()
    {
        $this->usuarioModelo = new UsuarioModelo();
    }

    /**
     * @OA\Get(
     *     path="/usuarios",
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     @OA\Response(response="200", description="Lista de usuarios"),
     *     @OA\Response(response="404", description="No se encontraron usuarios"),
     * )
     */
    function obtenerUsuarios()
    {
        $usuario = $this->usuarioModelo->obtenerUsuario();
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
            $resultado = $this->usuarioModelo->crearUsuario($datosUsuario);

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

            $actualizacion = $this->usuarioModelo->actualizarUsuario($id, $actualizarUsuario);

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

        } else {
            $estado = $data["estado"];
            $actualizacion = $this->usuarioModelo->actualizarEstadoUsuario($id, $estado);
            return $actualizacion;
        }


    }
}