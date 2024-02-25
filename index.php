<?php



header('Content-Type: application/json');

require_once "./rutas/router_usuario.php";

$usuarioRutasUusario = new RouterUsuario();

// Obtener el método de la solicitud HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

// Obtener la ruta de la solicitud
$ruta = isset($_GET['ruta']) ? $_GET['ruta'] : '';

// Convertir la ruta a minúsculas para evitar problemas de mayúsculas y minúsculas
$ruta = strtolower($ruta);

// Manejar la solicitud según el método y la ruta

switch ($metodo) {
    case 'GET':
        switch ($ruta) {
            case 'usuarios':
                /**
                 * @OA\Get(
                 *     path="/usuarios",
                 *     summary="Obtener todos los usuarios",
                 *     tags={"Usuarios"},
                 *     @OA\Response(response="200", description="Lista de usuarios"),
                 *     @OA\Response(response="404", description="No se encontraron usuarios"),
                 * )
                 */

                echo $usuarioRutasUusario->obtenerUsuarios();
                break;
            default:
                // Ruta no válida para el método GET
                echo json_encode(array("error" => "Ruta GET no válida"));
                break;
        }
        break;
    case 'POST':
        switch ($ruta) {
            case 'usuario/registrar':
                echo $usuarioRutasUusario->registrarUsuario();
                break;
            default:
                // Ruta no válida para el método POST
                echo json_encode(array("error" => "Ruta POST no válida"));
                break;
        }
        break;
    case 'PUT':
        switch ($ruta) {
            case "usuario/actualizar":
                echo $usuarioRutasUusario->actualizar();
                break;

            case "usuario/actualizar-estado":
                echo $usuarioRutasUusario->actualizarEstado();
                break;
            default:
                echo json_encode(array("error" => "Ruta PUT no válida"));
                break;

        }
        break;
    default:
        echo json_encode(array("error" => "Método no permitido"));
        break;
}


