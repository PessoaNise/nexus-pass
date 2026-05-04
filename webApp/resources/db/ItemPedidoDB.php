<?php

include_once 'Conexion.php';

class ItemPedidoDB {

    public static function insertaOrden($idOrden, $idProducto, $cantidad, $precio=0) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)';
            $stmt = $dbh->prepare($consulta);
            $stmt->bindParam(1, $idOrden);
            $stmt->bindParam(2, $idProducto);
            $stmt->bindParam(3, $cantidad);
            $stmt->bindParam(4, $precio);
            $resultado = $stmt->execute();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }

    public static function getDatosItemsOrdenPorIdOrden($idOrden) {
        $conexion = Conexion::getInstancia();
        $dbh = $conexion->getDbh();
        try {
            $consulta = 'SELECT io.*, p.nombre, p.precio, p.imagen as nombre_archivo  
                FROM detalle_pedido io
                JOIN pedido o ON o.id = io.pedido_id 
                JOIN producto p ON p.id = io.producto_id 
                WHERE o.id = ?';
            $stmt = $dbh->prepare($consulta);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1, $idOrden);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            $dbh = null; // cierra la conexion
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }

}
