<?php
require_once __DIR__ . '/../Includes/conexion.php';

class CobroModelo {
    public static function obtenerPedidosPendientes() {
        global $conexion;
        $sql = "SELECT 
            p.idPedido, 
            v.nombre AS cliente,
            ROUND(SUM(dp.cantidad * pr.precio) * 1.16, 2) AS total
        FROM Pedidos p
        JOIN Visitantes v ON p.idVisitante = v.idVisitante
        JOIN DetallePedido dp ON p.idPedido = dp.idPedido
        JOIN Productos pr ON dp.idProducto = pr.idProducto
        WHERE p.estado = 'pendiente'
        GROUP BY p.idPedido";


        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cobrarPedido($idPedido, $efectivo, $idEmpleado) {
        global $conexion;

        try {
            $conexion->beginTransaction();

            //dios el join
            $sqlDetalles = "SELECT dp.idProducto, dp.cantidad, p.precio 
                            FROM DetallePedido dp
                            JOIN Productos p ON dp.idProducto = p.idProducto
                            WHERE dp.idPedido = ?";
            $stmtDetalles = $conexion->prepare($sqlDetalles);
            $stmtDetalles->execute([$idPedido]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            $subtotal = 0;
            foreach ($detalles as $detalle) {
                $subtotal += $detalle['cantidad'] * $detalle['precio'];
            }
            $totalConIVA = $subtotal * 1.16;

            if ($efectivo < $totalConIVA) {
                throw new Exception("El efectivo proporcionado es insuficiente.");
            }

            $sqlVenta = "INSERT INTO Ventas (idEmpleado, fecha) VALUES (?, NOW())";
            $stmtVenta = $conexion->prepare($sqlVenta);
            $stmtVenta->execute([$idEmpleado]);
            $idVenta = $conexion->lastInsertId();

            $sqlInsertDetalle = "INSERT INTO DetallesVenta (idVenta, idProducto, cantidad)
                                VALUES (?, ?, ?)";
            $stmtDetalle = $conexion->prepare($sqlInsertDetalle);

            foreach ($detalles as $detalle) {
                $stmtDetalle->execute([
                    $idVenta,
                    $detalle['idProducto'],
                    $detalle['cantidad']
                ]);
            }

            $sqlEstado = "UPDATE Pedidos SET estado = 'Entregado' WHERE idPedido = ?";
            $stmtEstado = $conexion->prepare($sqlEstado);
            $stmtEstado->execute([$idPedido]);

            $conexion->commit();
            return [
                'total' => round($totalConIVA, 2),
                'cambio' => round($efectivo - $totalConIVA, 2)
            ];
        } catch (Exception $e) {
            $conexion->rollBack();
            return ['error' => $e->getMessage()];
        }
    }


    public static function obtenerDetallesPedido($idPedido) {
        global $conexion;

        $sql = "SELECT 
                    dp.idProducto,
                    pr.nombreProducto,
                    dp.cantidad,
                    pr.precio,
                    ROUND(dp.cantidad * pr.precio, 2) AS total
                FROM DetallePedido dp
                JOIN Productos pr ON dp.idProducto = pr.idProducto
                WHERE dp.idPedido = :idPedido";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelarPedido($idPedido) {
        require_once '../Includes/conexion.php';
        global $conexion;

        try {
            //bendito beginTransaction
            if (!$conexion->inTransaction()) {
                $conexion->beginTransaction();
            }

            $stmt = $conexion->prepare("SELECT idProducto, cantidad FROM DetallePedido WHERE idPedido = ?");
            $stmt->execute([$idPedido]);
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $detalle) {
                $stmtUpdate = $conexion->prepare("UPDATE Productos SET stock = stock + ? WHERE idProducto = ?");
                $stmtUpdate->execute([$detalle['cantidad'], $detalle['idProducto']]);
            }

            // estado del pedidooooooooooooooooooooo
            $stmt = $conexion->prepare("UPDATE Pedidos SET estado = 'Cancelado' WHERE idPedido = ?");
            $stmt->execute([$idPedido]);

            $conexion->commit();
            return true;

        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            error_log("Error al cancelar pedido: " . $e->getMessage());
            return false;
        }
    }

    public static function registrarVentaProductos($productos, $idEmpleado, $efectivo){
        require __DIR__ . '/../Includes/conexion.php';

        try {
            $fecha = date('Y-m-d');

            foreach ($productos as $item) {
                $stmtStock = $conexion->prepare("SELECT stock FROM Productos WHERE idProducto = ?");
                $stmtStock->execute([$item['id']]);
                $stockActual = $stmtStock->fetchColumn();

                if ($stockActual === false) {
                    return ['exito' => false, 'error' => "Producto ID {$item['id']} no encontrado."];
                }

                if ($stockActual < $item['cantidad']) {
                    return ['exito' => false, 'error' => "Stock insuficiente para el producto '{$item['nombre']}'. Stock disponible: $stockActual, cantidad solicitada: {$item['cantidad']}."];
                }
            }

            $stmt = $conexion->prepare("INSERT INTO Ventas (idEmpleado, fecha) VALUES (?, ?)");
            $stmt->execute([$idEmpleado, $fecha]);

            $idVenta = $conexion->lastInsertId();
            $stmtDetalle = $conexion->prepare("INSERT INTO DetallesVenta (idVenta, idProducto, cantidad) VALUES (?, ?, ?)");
            $stmtActualizarStock = $conexion->prepare("UPDATE Productos SET stock = stock - ? WHERE idProducto = ?");

            $total = 0;
            foreach ($productos as $item) {
                $stmtDetalle->execute([
                    $idVenta,
                    $item['id'],
                    $item['cantidad']
                ]);

                $stmtActualizarStock->execute([$item['cantidad'], $item['id']]);

                $total += $item['precio'] * $item['cantidad'];
            }

            $cambio = $efectivo - ($total*1.16);

            if ($cambio < 0) {
                return ['exito' => false, 'error' => "El efectivo recibido es menor al total de la compra."];
            }

            return ['exito' => true, 'cambio' => $cambio];

        } catch (PDOException $e) {
            return ['exito' => false, 'error' => $e->getMessage()];
        }
    }

}
?>
