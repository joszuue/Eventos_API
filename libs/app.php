<?php
    require_once 'controllers/ErrorController.php';
    class App{
        function __construct(){
            $url = isset($_GET["url"]) ? $_GET["url"] : null; 

            $url = rtrim($url ?? '', '/');
            $url = explode('/',$url);

            if(empty($url[0])){
                $archivoController = 'controllers/LoginController.php';
                require_once($archivoController);
                $controller = new LoginController();
                $controller->loadModel('Usuarios');
                $controller->login();
                return false;
            }

            $archivoController = 'controllers/' . $url[0]. 'Controller.php';

            $clase= $url[0]. 'Controller';
            if(file_exists($archivoController)){
                require_once $archivoController;
                $controller = new $clase;
                $controller->loadModel($url[0]);
                if(isset($url[1])){
                    if(method_exists($controller,$url[1])){
                        if(isset($url[2])){
                            $controller->{$url[1]}($url[2]);
                        }
                        $controller->{$url[1]}();
                    }else{
                        $controller = new ErrorController();
                    }

                }else{
                    $controller = new ErrorController();
                }
            }else{
                $controller = new ErrorController();
            }
        }
    }
?>