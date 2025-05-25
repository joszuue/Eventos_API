<?php
$output = ob_get_contents();
if (!empty($output)) {
    echo "<pre>Se ha detectado salida previa:\n$output</pre>";
    exit;
}
session_start();
header("Content-Type: application/json");
    class UsuariosController extends Controller{
        function __construct(){
        }
 
        function usuarios(){
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
            
            if($id == null){
                $usuarios = $this->model->listaUsuarios();
            }else{
                $usuarios = $this->model->selectUsuario($id);
            }

            
            echo json_encode($usuarios);
        }

        function insert() {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
        
                if (empty($data['nombres']) || empty($data['apellidos']) || empty($data['contra'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode([
                        'success' => false,
                        'message' => 'Faltan datos requeridos o están vacíos'
                    ]);
                    return;
                }
        
                $nombres = $data['nombres'];
                $apellidos = $data['apellidos'];
                $usuario = $data['usuario'];
                $contra = password_hash($data['contra'], PASSWORD_DEFAULT);
                $estado = "Activo";


                if(empty($this->model->validateUser($usuario))){
                    // Llamamos a agregarUsuario(), que devuelve el ID insertado
                    $insertedId = $this->model->agregarUsuario($nombres, $apellidos, $usuario, $contra, $estado);

                    if ($insertedId) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Usuario insertado con éxito',
                            'inserted_id' => $insertedId
                        ]);
                    } else {
                        http_response_code(500); // Internal Server Error
                        echo json_encode([
                            'success' => false,
                            'message' => 'Error al insertar usuario'
                        ]);
                    }
                }else{
                    http_response_code(500); // Internal Server Error
                    echo json_encode([
                        'success' => false,
                        'message' => 'El usuario ingresado ya se encuentra registrado'
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

        function update($id) {
            global $executed;
            if ($executed) return; // Evita ejecución duplicada.
            $executed = true;

            try {
                $data = json_decode(file_get_contents('php://input'), true);
        
                // Verificar que todos los datos requeridos están presentes y no están vacíos
                if (empty($id) || empty($data['nombres']) || empty($data['apellidos']) || empty($data['contra'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode([
                        'success' => false,
                        'message' => 'Faltan datos requeridos o están vacíos'
                    ]);
                    return;
                }
        
                $nombres = $data['nombres'];
                $apellidos = $data['apellidos'];
                $usuario = $data['usuario'];
                $contra = $data['contra'];
        
                $updated = $this->model->actualizarUsuario($id, $nombres, $apellidos, $usuario, $contra);
        
                if ($updated) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Usuario actualizado con exito'
                    ]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al actualizar usuario'
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
        
            $deleted = $this->model->eliminarUsuario($id, "Eliminado");
        
            if ($deleted) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado con éxito'
                ]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar usuario'
                ]);
            }
        }   
    }
?>