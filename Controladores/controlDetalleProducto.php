<?php
    require_once '../Includes/Conexion.php';
    require_once '../Modelos/productoModelo.php';

    $idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $producto = null;

    if ($idProducto > 0) {
        $producto = obtenerProductoPorId($conexion, $idProducto);
    }

    require_once '../Html/detalleProducto.php';

?>