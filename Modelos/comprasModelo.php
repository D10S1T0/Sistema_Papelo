<?php
class ComprasModelo {
    public static function registrarProveedor($conexion, $nombreProveedor, $contacto, $telefono) {
        try {
            $sql = "INSERT INTO Proveedores (nombreProveedor, contacto, telefono) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            return $stmt->execute([$nombreProveedor, $contacto, $telefono]);
        } catch (PDOException $e) {
            error_log("Error " . $e->getMessage());
            return false;
        }
    }
    public static function obtenerTodosLosProveedores($conexion) {
        $sql = "SELECT * FROM Proveedores";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function buscarProveedorPorId($conexion, $id) {
        try {
            $sql = "SELECT * FROM Proveedores WHERE idProveedor = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("lol " . $e->getMessage());
            return false;
        }
    }

    public static function obtenerComprasConDetalles($conexion) {
        //join letsgooooooooooooo
        $sql = "
            SELECT 
                c.idCompra,
                c.fecha,
                e.nombreEmpleado AS nombreEmpleado,
                p.nombreProveedor,
                GROUP_CONCAT(CONCAT(pr.nombreProducto, ' x', dc.cantidad) SEPARATOR ', ') AS productos
            FROM Compras c
            INNER JOIN Empleados e ON c.idEmpleado = e.idEmpleado
            INNER JOIN Proveedores p ON c.idProveedor = p.idProveedor
            LEFT JOIN DetalleCompra dc ON c.idCompra = dc.idCompra
            LEFT JOIN Productos pr ON dc.idProducto = pr.idProducto
            GROUP BY c.idCompra, c.fecha, e.nombreEmpleado, p.nombreProveedor
            ORDER BY c.fecha DESC
        ";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCompra($conexion, $idCompra) {
        $sqlDetalles = "DELETE FROM DetalleCompra WHERE idCompra = ?";
        $stmtDetalles = $conexion->prepare($sqlDetalles);
        $stmtDetalles->execute([$idCompra]);

        $sqlCompra = "DELETE FROM Compras WHERE idCompra = ?";
        $stmtCompra = $conexion->prepare($sqlCompra);
        return $stmtCompra->execute([$idCompra]);
    }

}
