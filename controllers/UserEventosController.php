<?php
$output = ob_get_contents();
if (!empty($output)) {
    echo "<pre>Se ha detectado salida previa:\n$output</pre>";
    exit;
}
session_start();
header("Content-Type: application/json");
    class UserEventosController extends Controller{
        function __construct(){
        }
 
        function eventos(){
            parent::__construct();          
            if(empty($_SESSION['id'])){
                http_response_code(401); //Unauthorized
                echo json_encode([
                    'success' => false,
                    'message' => 'No tiene acceso.'
                ]);
                return;
            }else{
                $metodo = $_SERVER['REQUEST_METHOD'];
            
                $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Elimina "/" al inicio y al final
                $buscarId = explode('/', $path);
                
                // Si el último valor es un número, lo consideramos como ID
                $id = (!empty($buscarId) && is_numeric(end($buscarId))) ? end($buscarId) : null;
    
                static $executed = false;

                switch($metodo){
                    case 'GET':
                        $this->get($id);
                        break;
                    case 'POST': 
                        $this->insert();
                        break; 
                    case 'PUT':
                        $this->update($id);
                        break;
                    case 'DELETE': 
                        $this->delete($id);
                        break;    
                    default:
                    echo " Metodo no permitido";        
                }
            }
        } 

        function get($id){
            global $executed;
            if ($executed) return; // Evita ejecución duplicada.
            $executed = true;
            
            $eventos = $this->model->misEventos($_SESSION['id']);

            
            echo json_encode($eventos);
        }

        function insert() {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
        
                if (empty($data['evento'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode([
                        'success' => false,
                        'message' => 'Faltan datos requeridos o están vacíos'
                    ]);
                    return;
                }
        
                $evento = $data['evento'];


                $insertedId = $this->model->confirmarEvento($_SESSION['id'], $evento);

                if ($insertedId) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Confirmación insertada con éxito',
                        'inserted_id' => $insertedId
                    ]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al insertar confirmación'
                    ]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error interno del servidor',
                    'error' => $e->getMessage()
                ]);
            }
        }

        function delete($id) { 
            global $executed;
            if ($executed) return; // Evita ejecución duplicada.
            $executed = true;
        
            $deleted = $this->model->eliminarConfirmacion($id);
        
            if ($deleted) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Confirmación eliminada con éxito'
                ]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar confirmación'
                ]);
            }
        }   
    }
?>