<?php
class ComentariosModel extends Model {
    public $conexion;

    function __construct() {
        parent::__construct();
    }

    function obtener($id = null) {
        $query = $id 
            ? "SELECT * FROM comentarios WHERE id_evento = :id" 
            : "SELECT * FROM comentarios";
        $this->conexion = $this->con->conectar();
        $stmt = $this->conexion->prepare($query);
        if ($id) $stmt->bindParam(':id', $id);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->con->desconectar($this->conexion);
        return $datos;
    }

    function insertar($id_evento, $id_user, $comentario) {
        $query = "INSERT INTO comentarios VALUES (NULL, :id_evento, :id_user, :comentario, NOW())";
        $this->conexion = $this->con->conectar();
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_evento', $id_evento);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':comentario', $comentario);
        return $stmt->execute() ? $this->conexion->lastInsertId() : false;
    }

    function actualizar($id, $comentario) {
        $query = "UPDATE comentarios SET comentario = :comentario WHERE id = :id";
        $this->conexion = $this->con->conectar();
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':comentario', $comentario);
        return $stmt->execute();
    }

    function eliminar($id) {
        $query = "DELETE FROM comentarios WHERE id = :id";
        $this->conexion = $this->con->conectar();
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>