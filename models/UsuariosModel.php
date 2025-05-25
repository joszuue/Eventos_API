<?php
    class UsuariosModel extends Model{
        public $conexion;
        function __construct(){
            parent::__construct();
        }
 
        function listaUsuarios(){
            $query = "SELECT * FROM user WHERE estado = 'Activo'";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->execute();
            $array = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }

        function selectUsuario($id){
            $query = "SELECT * FROM user WHERE estado = 'Activo' AND id = :id";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->bindParam(':id', $id);
            $resultado->execute();
            $array = $resultado->fetch(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }
        
        function agregarUsuario($nombres, $apellidos, $usuario, $contra, $estado){
            $query = "INSERT INTO user VALUES(null, :nombres,:apellidos, :usuario, :contra, :estado)";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':nombres', $nombres); //enviamos parametros a la consulta, esto evita inyecciones SQL
            $row->bindParam(':apellidos', $apellidos);
            $row->bindParam(':usuario', $usuario);
            $row->bindParam(':contra', $contra);
            $row->bindParam(':estado', $estado);
            if ($row->execute()) {
                return $this->conexion->lastInsertId(); // Devuelve el ID insertado
            } else {
                return false; // Si la inserción falla, devuelve false
            }
        }

        function actualizarUsuario($id, $nombres, $apellidos, $usuario, $contra){
            $query = "UPDATE user SET nombres = :nombres, apellidos = :apellidos, usuario = :usuario, contra = :contra WHERE id = :id";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':nombres', $nombres); //enviamos parametros a la consulta, esto evita inyecciones SQL
            $row->bindParam(':apellidos', $apellidos);
            $row->bindParam(':usuario', $usuario);
            $row->bindParam(':contra', $contra);
            $row->bindParam(':id', $id);
            return $row->execute();//devolvera un booleano dependiendo si la consulta y conexion fue exitosa
        }

        function eliminarUsuario($id, $estado){
            $query = "UPDATE user SET estado = :estado WHERE id = :id";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':id', $id);
            $row->bindParam(':estado', $estado);
            return $row->execute(); //devolvera un booleano dependiendo si la consulta y conexion fue exitosa
        }


        //LOGICA DE NEGOCIOS 

        //Funcion para obtener la contraseña
        function findPass($usuario){
            $query = "SELECT * FROM user WHERE estado = :estado AND usuario = :usuario";
            $this->conexion = $this->con->conectar();//accedemos a la funcion conectar, y por ende su return, el cual recordara es la bdd
            $resultado = $this->conexion->prepare($query); //preparamos la consulta
            $resultado->bindValue(':estado', "Activo");
            $resultado->bindParam(':usuario', $usuario);
            $resultado->execute();//ejecutamos la consulta
            $contra = " ";
            while ($row = $resultado->fetch()) {//obtenemos los resultados de la consulta, aqui se convertiran en arreglos nativos de php que podemos recorrer y usar
                $contra = $row['contra'];
            }
            $this->con->desconectar($this->conexion);//cerramos la conexion
            return $contra;//retornamos el arreglo
        }

        function findUser($usuario, $contra){
            $query = "SELECT * FROM user WHERE usuario = :usuario AND contra = :contra";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->bindParam(':usuario', $usuario);
            $resultado->bindParam(':contra', $contra);
            $resultado->execute();
            $array = $resultado->fetch(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }

        function validateUser($usuario){
            $query = "SELECT * FROM user WHERE usuario = :usuario AND estado = 'Activo'";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->bindParam(':usuario', $usuario);
            $resultado->execute();
            $array = $resultado->fetch(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }
    }
?>