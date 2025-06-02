<?php
    class UserEventosModel extends Model{
        public $conexion;
        function __construct(){
            parent::__construct();
        }
 
        function misEventos($id){
            $query = "SELECT e.* FROM user_eventos ue INNER JOIN eventos e ON ue.id_evento = e.id WHERE ue.id_user = :id";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->bindParam(':id', $id);
            $resultado->execute();
            $array = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }
        
        function confirmarEvento($id_user, $id_evento){
            $query = "INSERT INTO user_eventos VALUES(null, :id_user, :id_evento)";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':id_user', $id_user); //enviamos parametros a la consulta, esto evita inyecciones SQL
            $row->bindParam(':id_evento', $id_evento);
            if ($row->execute()) {
                return $this->conexion->lastInsertId(); // Devuelve el ID insertado
            } else {
                return false; // Si la inserción falla, devuelve false
            }
        }

        function eliminarConfirmacion($idEvento){
            $query = "DELETE FROM user_eventos WHERE id = :id";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':id', $idEvento);
            return $row->execute(); //devolvera un booleano dependiendo si la consulta y conexion fue exitosa
        }
    }
?>