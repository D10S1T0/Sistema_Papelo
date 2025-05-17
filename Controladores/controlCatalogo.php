<?php
require_once '../Includes/conexion.php';

$productosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $productosPorPagina;

$totalProductosStmt = $conexion->query("SELECT COUNT(*) as total FROM Productos");
$totalProductos = $totalProductosStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// join diooooooooooooosssss
$query = "
    SELECT 
        p.idProducto,
        p.nombreProducto,
        p.descripcion,
        p.precio,
        p.imagen,
        c.nombreCategoria,
        ROUND(AVG(cal.calificacion), 1) AS promedio
    FROM Productos p
    LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
    LEFT JOIN Calificaciones cal ON p.idProducto = cal.idProducto
    GROUP BY p.idProducto
    LIMIT $productosPorPagina OFFSET $offset
";

$stmt = $conexion->query($query);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conexion = null;
//die($query);
//Cargar la vista
require_once '../Html/catalogo.php';
