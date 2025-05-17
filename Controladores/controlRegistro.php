<?php
require_once '../Includes/conexion.php'; 
require_once '../Modelos/usuarioModelo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apPaterno = $_POST['apellidoPaterno'];
    $apMaterno = $_POST['apellidoMaterno'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];

    if (!empty($nombre) && !empty($apPaterno) && !empty($apMaterno) && !empty($fechaNacimiento) && !empty($correo) && !empty($pass)) {

        $exito = registrarVisitante($conexion, $nombre, $apPaterno, $apMaterno, $fechaNacimiento, $correo, $pass);

        if ($exito) {
            //rediureccionamiento
            header('Location: ../Html/Login.php?registro=exitoso');
            exit;
        } else {
            echo "Error al registrar usuario.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
