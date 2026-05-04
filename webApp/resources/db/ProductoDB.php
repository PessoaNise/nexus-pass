<?php

include_once 'Conexion.php';

class ProductoDB {

    public static function getProductos() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT p.id, p.categoria_id, c.nombre as categoria_nombre, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.activo, p.fecha_creacion ' .
                        'FROM producto p ' .
                        'JOIN categoria c ON c.id = p.categoria_id ' .
                        'ORDER BY p.nombre ASC';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $productos = $stmt->fetchAll();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
            return array();
        }
        return $productos;
    }

    public static function getProductoPorId($id) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT p.id, p.categoria_id, c.nombre as categoria_nombre, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.activo, p.fecha_creacion ' .
                        'FROM producto p ' .
                        'JOIN categoria c ON c.id = p.categoria_id ' .
                        'WHERE p.id = :id LIMIT 1';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $producto = $stmt->fetch();
            $dbh = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        return $producto;
    }

    public static function getProductosPorCategoriaId($idCategoria) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT p.id, p.categoria_id, c.nombre as categoria_nombre, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.activo, p.fecha_creacion ' .
                        'FROM producto p ' .
                        'JOIN categoria c ON c.id = p.categoria_id ' .
                        'WHERE p.categoria_id = :idCategoria ' .
                        'ORDER BY p.nombre ASC';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->execute();
            $productos = $stmt->fetchAll();
            $dbh = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return array();
        }
        return $productos;
    }
        
    public static function getProductosAleatorios($cant) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT p.id, p.categoria_id, c.nombre as categoria_nombre, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.activo, p.fecha_creacion ' .
                        'FROM producto p ' .
                        'JOIN categoria c ON c.id = p.categoria_id ' .
                        'WHERE p.activo = 1 ' .
                        'ORDER BY RAND() LIMIT :cant';
            
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindValue(':cant', (int)$cant, PDO::PARAM_INT);
            $stmt->execute();
            
            $productos = $stmt->fetchAll();
            $dbh = null; 
            
            return $productos;
            
        } catch (PDOException $e) {
            print($e->getMessage());
            return array();
        }
    }

    public static function insertaProducto($arreglo) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES (:categoria_id, :nombre, :descripcion, :precio, :imagen, :stock, :activo)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(':categoria_id', $arreglo['categoria_id']);
            $stmt->bindParam(':nombre', $arreglo['nombre']);
            $stmt->bindParam(':descripcion', $arreglo['descripcion']);
            $stmt->bindParam(':precio', $arreglo['precio']);
            $stmt->bindParam(':imagen', $arreglo['imagen']);
            $stmt->bindParam(':stock', $arreglo['stock']);
            $stmt->bindParam(':activo', $arreglo['activo']);
            
            if ($stmt->execute()) {
                $lastId = $dbh->lastInsertId();
                $dbh = null;
                return $lastId;
            }
            $dbh = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }

    public static function modificaProducto($arreglo) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'UPDATE producto SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen, stock = :stock, activo = :activo WHERE id = :id';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(':categoria_id', $arreglo['categoria_id']);
            $stmt->bindParam(':nombre', $arreglo['nombre']);
            $stmt->bindParam(':descripcion', $arreglo['descripcion']);
            $stmt->bindParam(':precio', $arreglo['precio']);
            $stmt->bindParam(':imagen', $arreglo['imagen']);
            $stmt->bindParam(':stock', $arreglo['stock']);
            $stmt->bindParam(':activo', $arreglo['activo']);
            $stmt->bindParam(':id', $arreglo['id']);
            
            $renglones = $stmt->execute();
            $dbh = null; 
            return $renglones;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }

    public static function sustraerStockProducto($id, $cantidad) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'UPDATE producto SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $renglones = $stmt->execute();
            $dbh = null; 
            return $renglones;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }
}