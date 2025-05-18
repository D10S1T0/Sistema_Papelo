<?php
    session_start();
    require_once '../Includes/conexion.php';
    require_once '../Modelos/CalificacionModelo.php';

    $idProducto = $_POST['idProducto'] ?? null;
    $calificacion = $_POST['calificacion'] ?? null;
    $idVisitante = $_SESSION['idVisitante'] ?? null;

    if (!$idProducto || !$calificacion || !$idVisitante || $calificacion < 1 || $calificacion > 5) {
        echo "Datos inválidos.";
        exit;
    }

    $modelo = new CalificacionModelo($conexion);

    try {
        $modelo->guardarCalificacion($idProducto, $idVisitante, $calificacion);
        header("Location: ../Controladores/controlDetalleProducto.php?id=$idProducto");
        exit;
    } catch (PDOException $e) {
        echo "Error al guardar calificación: " . $e->getMessage();
    }
?>