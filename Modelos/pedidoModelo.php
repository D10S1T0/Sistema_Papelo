<?php   
    function obtenerCarritoPorVisitante($conexion, $idVisitante) {
        $sql = "SELECT 
                    p.idProducto,
                    p.nombreProducto,
                    p.descripcion,
                    p.precio,
                    p.imagen,
                    dp.cantidad
                FROM DetallePedido dp
                INNER JOIN Productos p ON dp.idProducto = p.idProducto
                INNER JOIN Pedidos ped ON dp.idPedido = ped.idPedido
                WHERE ped.idVisitante = :idVisitante AND ped.estado = 'pendiente'";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idVisitante', $idVisitante, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerCarritoPorVisitanteEntregado($conexion, $idVisitante) {

        $sql = "SELECT 
                    p.idProducto,
                    p.nombreProducto,
                    p.descripcion,
                    p.precio,
                    p.imagen,
                    dp.cantidad
                FROM DetallePedido dp
                INNER JOIN Productos p ON dp.idProducto = p.idProducto
                INNER JOIN Pedidos ped ON dp.idPedido = ped.idPedido
                WHERE ped.idVisitante = :idVisitante AND ped.estado = 'entregado'";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idVisitante', $idVisitante, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function actualizarArticulos($idProducto, $nombre, $descripcion, $precio, $stock, $conexion){
        // Se llama desde el formulario de edición
        $sql = "UPDATE Productos SET nombreProducto = :nombre, descripcion = :descripcion, 
                precio = :precio, stock = :stock WHERE idProducto = :id";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':id', $idProducto);
        $stmt->execute();
    }

    function eliminarProductoDeCarrito($conexion, $idVisitante, $idProducto) {
        $sqlPedido = "SELECT idPedido FROM Pedidos WHERE idVisitante = :idVisitante AND estado = 'pendiente' LIMIT 1";
        $stmt = $conexion->prepare($sqlPedido);
        $stmt->bindParam(':idVisitante', $idVisitante, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            $idPedido = $pedido['idPedido'];

            $sqlEliminar = "DELETE FROM DetallePedido WHERE idPedido = :idPedido AND idProducto = :idProducto";
            $stmtEliminar = $conexion->prepare($sqlEliminar);
            $stmtEliminar->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $stmtEliminar->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $stmtEliminar->execute();
        }
    }
	
	function cancelarPedidosVencidos($conexion, $idVisitante) {
    try {
        $sql = "SELECT idPedido FROM pedidos 
                WHERE estado = 'Pendiente'
                AND fecha <= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND idVisitante = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$idVisitante]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($pedidos) > 0) {
            foreach ($pedidos as $pedido) {
                $idPedido = $pedido['idPedido'];

                $sqlDetalles = "SELECT idProducto, cantidad FROM DetallePedido WHERE idPedido = ?";
                $stmtDetalles = $conexion->prepare($sqlDetalles);
                $stmtDetalles->execute([$idPedido]);
                $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

                foreach ($detalles as $detalle) {
                    $sqlUpdate = "UPDATE Productos SET stock = stock + ? WHERE idProducto = ?";
                    $stmtUpdate = $conexion->prepare($sqlUpdate);
                    $stmtUpdate->execute([$detalle['cantidad'], $detalle['idProducto']]);
                }

                $sqlCancelar = "UPDATE Pedidos SET estado = 'Cancelado' WHERE idPedido = ?";
                $stmtCancelar = $conexion->prepare($sqlCancelar);
                $stmtCancelar->execute([$idPedido]);
            }

            header("Location: ../Controladores/controlCarrito.php?mensaje=pedido_cancelado");
            exit();
        }

    } catch (PDOException $e) {
        echo("Error al cancelar pedidos vencidos: " . $e->getMessage());
        die();
    }
}

?>