<?php
require_once 'Conexion.php';

class PersonaDB {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getDbh();
    }

    public function registrar($nombre, $apellidos, $telefono, $correo) {
        $sql = "INSERT INTO persona (nombre, apellidos, telefono, correo) VALUES (:nombre, :apellidos, :telefono, :correo)";
        $stmt = $this->conexion->prepare($sql);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        
        if ($stmt->execute()) {
            return $this->conexion->lastInsertId();
        }
        return false;
    }

    public function actualizarDireccionPorIdUsuario($usuario_id, $calle, $numero_exterior, $numero_interior, $colonia, $ciudad, $estado, $codigo_postal) {
        $sql = "UPDATE persona p 
                INNER JOIN usuario u ON u.persona_id = p.id 
                SET p.calle = :calle, p.numero_exterior = :n_ext, p.numero_interior = :n_int, 
                    p.colonia = :colonia, p.ciudad = :ciudad, p.estado = :estado, p.codigo_postal = :cp 
                WHERE u.id = :uid";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':calle', $calle);
        $stmt->bindParam(':n_ext', $numero_exterior);
        $stmt->bindParam(':n_int', $numero_interior);
        $stmt->bindParam(':colonia', $colonia);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cp', $codigo_postal);
        $stmt->bindParam(':uid', $usuario_id);
        
        return $stmt->execute();
    }

    public function getDireccionPorIdUsuario($usuario_id) {
        $sql = "SELECT p.calle, p.numero_exterior, p.numero_interior, p.colonia, p.ciudad, p.estado, p.codigo_postal 
                FROM persona p 
                JOIN usuario u ON u.persona_id = p.id 
                WHERE u.id = :uid";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':uid', $usuario_id);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}