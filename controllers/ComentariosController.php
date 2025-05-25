<?php
session_start();
header("Content-Type: application/json");

class ComentariosController extends Controller {
    function __construct() {}

    function comentarios() {
        if (empty($_SESSION['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No tiene acceso.']);
            return;
        }

        $metodo = $_SERVER['REQUEST_METHOD'];
        $id = $this->getIdFromUrl();

        switch ($metodo) {
            case 'GET':
                echo json_encode($this->model->obtener($id));
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $idInsertado = $this->model->insertar($data['id_evento'], $data['id_user'], $data['comentario']);
                echo json_encode(['success' => $idInsertado ? true : false, 'id' => $idInsertado]);
                break;
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode(['success' => $this->model->actualizar($id, $data['comentario'])]);
                break;
            case 'DELETE':
                echo json_encode(['success' => $this->model->eliminar($id)]);
                break;
        }
    }

    private function getIdFromUrl() {
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $path);
        return is_numeric(end($segments)) ? end($segments) : null;
    }
}
?>