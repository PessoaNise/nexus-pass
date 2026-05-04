<?php

include_once 'Conexion.php';

class PedidoDB {

    public static function insertaOrden($idUsuario,$total) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            date_default_timezone_set('America/Mexico_City');
            $fecha_actual = date('Y-m-d H:i:s');
            
            $consulta = 'INSERT INTO pedido (usuario_id, total, estado, fecha) VALUES (?,?,?,?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $idUsuario);
            $stmt->bindParam(2, $total);
            $stmt->bindValue(3, 'pendiente');
            $stmt->bindValue(4, $fecha_actual);
            $resultado = $stmt->execute();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if($stmt->rowCount() > 0)
            return true;
        else
            return false;
//        return $resultado;
    }

    public static function getUltimaIdInsertada() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT LAST_INSERT_ID()';
            $stmt = $dbh->prepare($consulta);
            $stmt->execute();
            $resultado = $stmt->fetch();
            $id = $resultado[0];
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $id;
    }

    public static function getDatosPersonaOrdenPorIdOrden($idOrden) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT o.*, p.nombre, p.apellidos as a_paterno, p.calle, p.numero_exterior as numero, p.correo as correo_electronico
                FROM pedido o
                JOIN usuario u ON u.id = o.usuario_id  
                JOIN persona p ON p.id = u.persona_id 
                WHERE o.id = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1, $idOrden);
            $stmt->execute();
            $resultado = $stmt->fetch();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }


    public static function getOrdenesDeClientePorIdCliente($idCliente) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT * FROM pedido o WHERE o.usuario_id = ? ORDER BY o.fecha DESC';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1, $idCliente);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }

    public static function getOrdenes() {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT p.*, u.usuario as nombre_usuario FROM pedido p LEFT JOIN usuario u ON p.usuario_id = u.id ORDER BY p.fecha DESC';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }

    public static function getMembresiaActiva($idCliente) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // Buscamos si el usuario ha comprado algún producto 1, 2 o 3 (membresías)
            // Devuelve el ID del producto (1=Bronce, 2=Plata, 3=Oro)
            $consulta = 'SELECT dp.producto_id 
                         FROM pedido p 
                         JOIN detalle_pedido dp ON p.id = dp.pedido_id 
                         WHERE p.usuario_id = ? AND dp.producto_id IN (1, 2, 3) 
                         ORDER BY p.fecha DESC LIMIT 1';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $idCliente);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (int)$row['producto_id'];
            }
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public static function getCantidadBeneficiosUsados($idCliente) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            // Contamos la cantidad de detalles de pedido que tienen precio 0.00 (beneficios)
            $consulta = 'SELECT SUM(dp.cantidad) as total_usados 
                         FROM pedido p 
                         JOIN detalle_pedido dp ON p.id = dp.pedido_id 
                         WHERE p.usuario_id = ? AND dp.precio_unitario = 0.00';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $idCliente);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (int)$row['total_usados'];
            }
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public static function hasBeneficioActivo($idCliente, $idProducto) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT COUNT(dp.id) as count 
                         FROM pedido p 
                         JOIN detalle_pedido dp ON p.id = dp.pedido_id 
                         WHERE p.usuario_id = ? AND dp.producto_id = ? AND dp.precio_unitario = 0.00';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $idCliente);
            $stmt->bindParam(2, $idProducto);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return ((int)$row['count']) > 0;
            }
            return false;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
