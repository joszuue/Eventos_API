<?php
    class EventosModel extends Model{
        public $conexion;
        function __construct(){
            parent::__construct(); 
        }
 
        function listaEventos(){
            $query = "SELECT * FROM eventos WHERE estado = 'Activo'";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->execute();
            $array = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }

        function selectEvento($id){
            $query = "SELECT * FROM eventos WHERE estado = 'Activo' AND id = :id";
            $this->conexion = $this->con->conectar();
            $resultado = $this->conexion->prepare($query); 
            $resultado->bindParam(':id', $id);
            $resultado->execute();
            $array = $resultado->fetch(PDO::FETCH_ASSOC);
            $this->con->desconectar($this->conexion); //cerramos la conexion
            return $array; //retornamos el arreglo
        }
        
        function agregarEvento($descripcion, $fecha, $hora, $ubicacion, $estado){
            $query = "INSERT INTO eventos VALUES(null, :descripcion, :fecha, :hora, :ubicacion, :estado)";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':descripcion', $descripcion); //enviamos parametros a la consulta, esto evita inyecciones SQL
            $row->bindParam(':fecha', $fecha);
            $row->bindParam(':hora', $hora);
            $row->bindParam(':ubicacion', $ubicacion);
            $row->bindParam(':estado', $estado);
            if ($row->execute()) {
                return $this->conexion->lastInsertId(); // Devuelve el ID insertado
            } else {
                return false; // Si la inserción falla, devuelve false
            }
        }

        function actualizarEvento($id, $descripcion, $fecha, $hora, $ubicacion){
            $query = "UPDATE eventos SET descripcion = :descripcion, fecha = :fecha, hora = :hora, ubicacion = :ubicacion WHERE id = :id";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':descripcion', $descripcion); //enviamos parametros a la consulta, esto evita inyecciones SQL
            $row->bindParam(':fecha', $fecha);
            $row->bindParam(':hora', $hora);
            $row->bindParam(':ubicacion', $ubicacion);
            $row->bindParam(':id', $id);
            return $row->execute();//devolvera un booleano dependiendo si la consulta y conexion fue exitosa
        }

        function eliminarEvento($id, $estado){
            $query = "UPDATE eventos SET estado = :estado WHERE id = :id";
            $this->conexion = $this->con->conectar();
            $row = $this->conexion->prepare($query);
            $row->bindParam(':id', $id);
            $row->bindParam(':estado', $estado);
            return $row->execute(); //devolvera un booleano dependiendo si la consulta y conexion fue exitosa
        }
    }
?>