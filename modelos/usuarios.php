<?php

require_once './database/database.php';
require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('./config');
$dotenv->load();

class UsuarioModelo
{

    private $conexion;

    function __construct()
    {
        $this->conexion = new Database($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
    }



    function obtenerUsuario()
    {
        try {
            $query = "SELECT * FROM usuarios WHERE estado = 0;";
            $resultados = $this->conexion->query($query);


            // Obtener todas las filas como un array asociativo de arrays
            $usuarios = mysqli_fetch_all($resultados, MYSQLI_ASSOC);

            $this->conexion->cerrar();
            // Devolver los usuarios como JSON
            return json_encode($usuarios);
        } catch (Exception $e) {
            // Manejar la excepción
            return json_encode(array("error" => $e->getMessage()));
        }
    }


    function crearUsuario($usuario)
    {
        try {

            $usuario_nombre = $usuario["nombre"];
            $email = $usuario["correo"];

            $query = "INSERT INTO usuarios (usuario, email) VALUES ('$usuario_nombre', '$email')";

            $resultados = $this->conexion->query($query);


            if ($resultados) {
                $id_insertado = $this->conexion->obtner_id();
                $this->conexion->cerrar();

                http_response_code(202);
                return json_encode(array("id" => $id_insertado, "mensaje" => "Registro exitoso"));
            } else {
                throw new Exception("Error al registrar usuario");
            }
        } catch (Exception $e) {
            // Manejar la excepción
            return json_encode(array("error" => $e->getMessage()));
        }
    }
    function actualizarUsuario($id, $usuario)
    {
        try {

            if (!$this->existeIdUsuario($id) || $id == null) {
                // El ID no existe, retornar un JSON con un mensaje de error
                http_response_code(404);
                return json_encode(array("error" => "El ID de usuario no existe"));
            }

            $usuario_nombre = $usuario["nombre"];
            $email = $usuario["correo"];

            $query = "UPDATE usuarios SET usuario = '$usuario_nombre ', email = '$email' WHERE id = '$id';";

            // Ejecutar la consulta de actualización
            $resultados = $this->conexion->query($query);

            // Cerrar la conexión
            $this->conexion->cerrar();

            if ($resultados) {
                // La actualización fue exitosa
                http_response_code(200);
                return json_encode(array("id" => $id, "mensaje" => "Usuario actualizado correctamente."));
            } else {
                // Ocurrió un error durante la actualización
                return json_encode(array("error" => "No se pudo actualizar el usuario."));
            }
        } catch (Exception $e) {
            // Manejar la excepción
            return json_encode(array("error" => $e->getMessage()));
        }
    }

    function actualizarEstadoUsuario($id, $estado)
    {
        try {
            if (!$this->existeIdUsuario($id) || $id == null) {
                // El ID no existe, retornar un JSON con un mensaje de error
                return json_encode(array("error" => "El ID de usuario no existe"));
            }

            $query = "UPDATE usuarios SET estado = '$estado' WHERE id = '$id';";

            $resultados = $this->conexion->query($query);
            $this->conexion->cerrar();

            if ($resultados) {

                $mensaje = $resultados ? ($estado == 0 ? "Usuario activado correctamente." : ($estado == 1 ? "Usuario desactivado correctamente." : "Estado del usuario actualizado correctamente.")) : "No se pudo actualizar el usuario";
                return json_encode($resultados ? array("id" => $id, "mensaje" => $mensaje) : array("error" => $mensaje));
            } else {
                return json_encode(array("error" => "No se pudo actualizar el usuario."));
            }
        } catch (Exception $e) {
            return json_encode(array("error" => $e->getMessage()));
        }
    }
    function existeIdUsuario($id): bool
    {
        try {
            $query = "SELECT * FROM usuarios WHERE id = '$id'";
            $resultados = $this->conexion->query($query);
            $existe = $resultados && $resultados->num_rows > 0;

            return $existe;
        } catch (Exception $e) {
            return false; // En caso de error, retornar false
        }
    }


}