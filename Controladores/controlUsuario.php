<?php
include_once(__DIR__ . '/../Includes/conexion.php');
include_once(__DIR__ . '/../Modelos/usuarioModelo.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'agregarPuesto') {
        $nombre = $_POST['nombrePuesto'] ?? '';
        $salario = $_POST['salario'] ?? 0;

        if (!empty($nombre) && $salario > 0) {
            agregarNuevoPuesto($conexion, $nombre, $salario);
        }

        header("Location: ../Html/dashboardAdmi.php?seccion=personal");
        exit;
    } 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'agregarEmpleado') {
        agregarNuevoEmpleado($conexion, $_POST);
        header("Location: ../Html/dashboardAdmi.php?seccion=personal");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'editar') {

    editarEmpleado($conexion, $_POST); //funcionaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    header("Location: ../Html/dashboardAdmi.php?seccion=personal");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'eliminar') {
        $idEmpleado = $_POST['idEmpleado'];

        try {
         $resultado = eliminarEmpleado($conexion, $idEmpleado);
            if ($resultado) {
                echo json_encode(['success' => true]);
                //die($resultado)
                http_response_code(200);
            } else {
                echo json_encode(['success' => false]);
                http_response_code(500);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            http_response_code(500);
        }
    }
}

