<?php
session_start();
include_once(__DIR__ . '/../Includes/conexion.php');
include_once(__DIR__ . '/../Modelos/categoriaModelo.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'agregar') {
        $nombre = trim($_POST['nombreCategoria']);
        if (!empty($nombre)) {
            agregarCategoria($conexion, $nombre);
        }

        $url = "Location: ../Html/dashboardAdmi.php?seccion=articulos";

        $url = (strtolower($_SESSION['puesto']) == 'gerente') ? "Location: ../Html/dashboardAdmi.php?seccion=articulos" : "Location: ../Html/Login.php" ;
        $url = (strtolower($_SESSION['puesto']) == 'cajero') ? "Location: ../Html/dashboardCajero.php?seccion=articulos" : "Location: ../Html/Login.php" ;

        header($url);
        exit;
        //die($url);
    }
} catch (Exception $e) {
    //error cat .w. 
    header("Location: ../Html/dashboardAdmi.php?seccion=articulos&error=categoria");
    exit;
}
