<?php
    $output = ob_get_contents();
    if (!empty($output)) {
        echo "<pre>Se ha detectado salida previa:\n$output</pre>";
        exit;
    }
    session_start();
    header("Content-Type: application/json");
    class LoginController extends Controller{
        function __construct(){
            $this->loadModel('Usuarios');
        }
 
        function login(){
            parent::__construct();
                        
            $metodo = $_SERVER['REQUEST_METHOD'];
            
            $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Elimina "/" al inicio y al final
            $buscarId = explode('/', $path);
            
            // Si el último valor es un número, lo consideramos como ID
            $id = (!empty($buscarId) && is_numeric(end($buscarId))) ? end($buscarId) : null;

            static $executed = false;
            
            switch($metodo){
                case 'POST': 
                    $this->authenticate();
                    break;    
                default:
                echo " Metodo no permitido";        
            }
        }

        function authenticate() {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
        
                if (empty($data['usuario']) || empty($data['contra'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode([
                        'success' => false,
                        'message' => 'Faltan datos requeridos o están vacíos'
                    ]);
                    return;
                }
        
                $usuario = $data['usuario'];
                $contra = $data['contra'];
        
                $contraBdd = $this->model->findPass($usuario);
                if (password_verify($contra, $contraBdd)){
                    $usu = $this->model->findUser($usuario, $contraBdd);
                    $_SESSION['id'] = $usu['id'];
                    echo json_encode($usu);
                }else{
                    http_response_code(401); //Unauthorized
                    echo json_encode([
                        'success' => false,
                        'message' => 'Credenciales inválidas. Verifique su usuario y contraseña.'
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

        function logOut(){

            // Eliminar todas las variables de sesión
            $_SESSION = array();

            // Destruir la cookie de sesión si es necesario
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Destruir la sesión
            session_destroy();

            http_response_code(200);
            echo json_encode([
                "success"  => true,
                "message"  => "Sesión cerrada correctamente."
            ]);
            
        }

        
        
        

       
    }
?>