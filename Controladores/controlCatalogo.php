<?php
require_once '../Includes/conexion.php';
require_once '../Modelos/productoModelo.php';
require_once '../Modelos/categoriaModelo.php';

$productosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $productosPorPagina;

// Capturar filtros
$buscar = $_GET['buscar'] ?? '';
$categoria = $_GET['categoria'] ?? '';

// Obtener productos filtrados con paginación
$productos = obtenerProductosFiltradosPaginados($conexion, $buscar, $categoria, $productosPorPagina, $offset);

// Obtener total de productos filtrados para calcular páginas
$totalProductos = contarProductosFiltrados($conexion, $buscar, $categoria);
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Obtener todas las categorías para el filtro
$categorias = obtenerTodasLasCategorias($conexion);

// Cargar vista
require_once '../Html/catalogo.php';