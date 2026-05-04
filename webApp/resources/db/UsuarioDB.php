<?php
require_once 'Conexion.php';

class UsuarioDB {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getDbh();
    }

    public function registrar($persona_id, $usuario, $password, $tipo_usuario = 'cliente') {
        // Encriptación obligatoria
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuario (persona_id, usuario, password, tipo_usuario, activo) VALUES (:persona_id, :usuario, :password, :tipo_usuario, 0)";
        $stmt = $this->conexion->prepare($sql);
        
        $stmt->bindParam(':persona_id', $persona_id);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        
        if ($stmt->execute()) {
            return $this->conexion->lastInsertId(); // Devolver el ID del usuario creado
        }
        return false;
    }

    public function login($usuario, $password) {
        $sql = "SELECT id, persona_id, tipo_usuario as rol, usuario, password, activo FROM usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // password_verify compara el texto plano con el hash almacenado
        if ($usuarioData && password_verify($password, $usuarioData['password'])) {
            unset($usuarioData['password']); // Eliminar el hash
            return $usuarioData; // Contiene 'rol', 'activo' etc.
        }
        return false;
    }

    public static function activaUsuarioById($id) {
        $conexion = Conexion::getInstancia()->getDbh();
        $sql = "UPDATE usuario SET activo = 1 WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    // Helper en caso de requerir el perfil entero del usuario por el nombre
    public static function getUsuarioTipoCientePorUsuario($usuarioNick) {
        $conexion = Conexion::getInstancia()->getDbh();
        $sql = "SELECT id, tipo_usuario, activo FROM usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuarioNick);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTodosClientes() {
        $conexion = Conexion::getInstancia()->getDbh();
        $sql = "SELECT id, usuario FROM usuario WHERE tipo_usuario = 'cliente'";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}