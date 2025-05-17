<?php
//turip ip ip ojala fueran todos asi
    require_once '../Includes/conexion.php';
    require_once '../Modelos/ReporteModelo.php';

    $ventasTotales = obtenerVentasTotales($conexion);
    $totalPedidos = obtenerTotalPedidos($conexion);
    $articulosVendidos = obtenerTotalArticulosVendidos($conexion);
    $clientesNuevos = obtenerClientesNuevos($conexion);
    $pedidosRecientes = obtenerPedidosRecientes($conexion);

    $articulosMasPedidos = obtenerArticulosMasPedidos($conexion);
    $mejorValorados = obtenerArticulosMejorValorados($conexion);
    $categoriasPopulares = obtenerCategoriasPopulares($conexion);
?>
