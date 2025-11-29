<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../Includes/conexion.php";

function fetchData($conexion, $sql) {
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    $data = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $fila;
    }
    return $data;
}

$datos = [];

/* ============================================================
   REPORTES DE VENTAS
============================================================ */

$datos["ventasPorDia"] = fetchData($conexion,"
    SELECT 
        v.fecha,
        p.idProducto,
        p.nombreProducto,
        dv.cantidad,
        p.precio,
        (p.precio * dv.cantidad) AS total
    FROM Ventas v
    JOIN DetallesVenta dv ON v.idVenta = dv.idVenta
    JOIN Productos p ON dv.idProducto = p.idProducto
    ORDER BY v.fecha, p.idProducto
");


$datos["ventasPorMes"] = fetchData($conexion,"
    SELECT 
        DATE_FORMAT(v.fecha, '%Y-%m') AS mes,
        p.idProducto,
        p.nombreProducto,
        dv.cantidad,
        p.precio,
        (p.precio * dv.cantidad) AS total
    FROM Ventas v
    JOIN DetallesVenta dv ON v.idVenta = dv.idVenta
    JOIN Productos p ON dv.idProducto = p.idProducto
    ORDER BY mes, p.idProducto
");


$datos["ventasPorAño"] = fetchData($conexion,"
    SELECT 
        YEAR(v.fecha) AS año,
        p.idProducto,
        p.nombreProducto,
        dv.cantidad,
        p.precio,
        (p.precio * dv.cantidad) AS total
    FROM Ventas v
    JOIN DetallesVenta dv ON v.idVenta = dv.idVenta
    JOIN Productos p ON dv.idProducto = p.idProducto
    ORDER BY año, p.idProducto
");


$datos["ventasPorEmpleado"] = fetchData($conexion,"
    SELECT e.nombreEmpleado,
           SUM(p.precio * dv.cantidad) AS total
    FROM Ventas v
    JOIN Empleados e ON v.idEmpleado = e.idEmpleado
    JOIN DetallesVenta dv ON v.idVenta = dv.idVenta
    JOIN Productos p ON dv.idProducto = p.idProducto
    GROUP BY e.idEmpleado
");

/* ============================================================
   REPORTES EXTRA (SIN IMÁGENES)
============================================================ */

$datos["productosMasVendidos"] = fetchData($conexion,"
    SELECT p.idProducto, p.nombreProducto,
           SUM(dv.cantidad) AS cantidadVendida
    FROM DetallesVenta dv
    JOIN Productos p ON dv.idProducto = p.idProducto
    GROUP BY p.idProducto
    ORDER BY cantidadVendida DESC
");

$datos["ventasPorCategoria"] = fetchData($conexion,"
    SELECT c.nombreCategoria,
           SUM(dv.cantidad * p.precio) AS total
    FROM Productos p
    JOIN Categorias c ON p.idCategoria = c.idCategoria
    JOIN DetallesVenta dv ON p.idProducto = dv.idProducto
    GROUP BY c.idCategoria
");

$datos["stockBajo"] = fetchData($conexion,"
    SELECT idProducto, nombreProducto, stock
    FROM Productos
    WHERE stock < 10
");

$datos["productosSinVender"] = fetchData($conexion,"
    SELECT p.idProducto, p.nombreProducto, p.stock
    FROM Productos p
    LEFT JOIN DetallesVenta dv ON p.idProducto = dv.idProducto
    WHERE dv.idProducto IS NULL
");

$datos["movimientosInventario"] = fetchData($conexion,"
    SELECT 'Venta' AS tipo,
           v.fecha,
           p.nombreProducto,
           dv.cantidad
    FROM DetallesVenta dv
    JOIN Ventas v ON dv.idVenta = v.idVenta
    JOIN Productos p ON dv.idProducto = p.idProducto

    UNION ALL

    SELECT 'Compra',
           c.fecha,
           p.nombreProducto,
           dc.cantidad
    FROM DetalleCompra dc
    JOIN Compras c ON dc.idCompra = c.idCompra
    JOIN Productos p ON dc.idProducto = p.idProducto
");

$datos["comprasPorProveedor"] = fetchData($conexion,"
    SELECT pr.nombreProveedor, COUNT(c.idCompra) AS totalCompras
    FROM Compras c
    JOIN Proveedores pr ON c.idProveedor = pr.idProveedor
    GROUP BY pr.idProveedor
");

$datos["productosMasComprados"] = fetchData($conexion,"
    SELECT p.idProducto, p.nombreProducto,
           SUM(dc.cantidad) AS total
    FROM DetalleCompra dc
    JOIN Productos p ON dc.idProducto = p.idProducto
    GROUP BY p.idProducto
");

/* ============================================================
   REPORTES DE CLIENTES
============================================================ */

$datos["clientesTop"] = fetchData($conexion,"
    SELECT v.nombre, v.apellidoPaterno,
           SUM(dp.cantidad * p.precio) AS totalComprado
    FROM Visitantes v
    JOIN Pedidos pd ON v.idVisitante = pd.idVisitante
    JOIN DetallePedido dp ON pd.idPedido = dp.idPedido
    JOIN Productos p ON dp.idProducto = p.idProducto
    GROUP BY v.idVisitante
    ORDER BY totalComprado DESC
");

$datos["pedidosPorEstado"] = fetchData($conexion,"
    SELECT estado, COUNT(*) AS total
    FROM Pedidos
    GROUP BY estado
");

$datos["pedidosPorCliente"] = fetchData($conexion,"
    SELECT v.nombre,
           COUNT(*) AS pedidos
    FROM Pedidos p
    JOIN Visitantes v ON p.idVisitante = v.idVisitante
    GROUP BY v.idVisitante
");

/* ============================================================
   REPORTES DE EMPLEADOS
============================================================ */

$datos["empleadosVentas"] = fetchData($conexion,"
    SELECT e.nombreEmpleado,
           COUNT(*) AS ventas
    FROM Ventas v
    JOIN Empleados e ON v.idEmpleado = e.idEmpleado
    GROUP BY e.idEmpleado
");

$datos["empleadosCompras"] = fetchData($conexion,"
    SELECT e.nombreEmpleado,
           COUNT(*) AS compras
    FROM Compras c
    JOIN Empleados e ON c.idEmpleado = e.idEmpleado
    GROUP BY e.idEmpleado
");

/* ============================================================
   CALIFICACIONES
============================================================ */

$datos["productosMejorCalificados"] = fetchData($conexion,"
    SELECT p.nombreProducto,
           AVG(c.calificacion) AS promedio
    FROM Calificaciones c
    JOIN Productos p ON c.idProducto = p.idProducto
    GROUP BY p.idProducto
    ORDER BY promedio DESC
");

$datos["productosMasCalificaciones"] = fetchData($conexion,"
    SELECT p.nombreProducto,
           COUNT(*) AS total
    FROM Calificaciones c
    JOIN Productos p ON c.idProducto = p.idProducto
    GROUP BY p.idProducto
    ORDER BY total DESC
");

$datos["productosCalifBaja"] = fetchData($conexion,"
    SELECT p.nombreProducto,
           AVG(c.calificacion) AS promedio
    FROM Calificaciones c
    JOIN Productos p ON c.idProducto = p.idProducto
    GROUP BY p.idProducto
    HAVING promedio <= 2
");

/* ============================================================
   PROVEEDORES
============================================================ */

$datos["proveedoresFrecuentes"] = fetchData($conexion,"
    SELECT pr.nombreProveedor,
           COUNT(*) AS compras
    FROM Compras c
    JOIN Proveedores pr ON c.idProveedor = pr.idProveedor
    GROUP BY pr.idProveedor
");

$datos["productosPorProveedor"] = fetchData($conexion,"
    SELECT pr.nombreProveedor,
           p.nombreProducto
    FROM Compras c
    JOIN Proveedores pr ON c.idProveedor = pr.idProveedor
    JOIN DetalleCompra dc ON c.idCompra = dc.idCompra
    JOIN Productos p ON dc.idProducto = p.idProducto
");

/* ============================================================
   FINANZAS
============================================================ */

$datos["ingresosVentas"] = fetchData($conexion,"
    SELECT SUM(p.precio * dv.cantidad) AS ingresos
    FROM DetallesVenta dv
    JOIN Productos p ON dv.idProducto = p.idProducto
");

$datos["gastosCompras"] = fetchData($conexion,"
    SELECT SUM(dc.cantidad * 10) AS gastos
    FROM DetalleCompra dc
");

/* ============================================================
   CLIENTES QUE CALIFICAN
============================================================ */

$datos["clientesCalificadores"] = fetchData($conexion,"
    SELECT v.nombre,
           COUNT(*) AS calificaciones
    FROM Calificaciones c
    JOIN Visitantes v ON c.idVisitante = v.idVisitante
    GROUP BY v.idVisitante
");

/* ============================================================
   RESPUESTA FINAL JSON
============================================================ */

header("Content-Type: application/json; charset=utf-8");
echo json_encode($datos);

