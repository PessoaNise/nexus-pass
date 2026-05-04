<?php

include_once 'Conexion.php';

class CategoriaDB {
    
    public static function getCategorias() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT * FROM categoria ORDER BY nombre ASC';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $categorias = $stmt->fetchAll();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
            return array();
        }
        return $categorias;
    }

    public static function getCategoriaPorId($id) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT * FROM categoria WHERE id = :id LIMIT 1';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $categoria = $stmt->fetch();
            $dbh = null; 
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        return $categoria;
    }
}
