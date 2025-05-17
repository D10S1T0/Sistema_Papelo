<?php
    session_start();
    require_once '../Includes/conexion.php';
    require_once '../Modelos/usuarioModelo.php';

    //verifico si se enviaron datos por post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $correo = $_POST['correo'] ?? '';
        $pass = $_POST['pass'] ?? '';     

        //busco en empleados
        $empleado = buscarEmpleadoPorCorreo($correo, $conexion);

        if ($empleado && (password_verify($pass, $empleado['pass']) || $empleado['pass'] === $pass)) {
            //guardo los datos en la sesion
            $_SESSION['usuario'] = $empleado['nombreEmpleado'];
            $_SESSION['tipo'] = 'empleado';
            $_SESSION['puesto'] = $empleado['nombrePuesto'];
            $_SESSION['idEmpleado'] = $empleado['idEmpleado'];
            
            //strtolower sirve para minusculas
            $puesto = strtolower($empleado['nombrePuesto']);

            //reenvio al empleado  segun el puesto
            if ($puesto === 'gerente') {
                header("Location: ../Html/dashboardAdmi.php");
            } elseif ($puesto === 'cajero') {
                header("Location: ../Html/dashboardCajero.php");
            } else {
                header("Location: ../Html/dashboard_empleado.php");
            }
            exit();
        }

        //Checo si  es visitante
        $visitante = buscarVisitantePorCorreo($correo, $conexion);

        if ($visitante && (password_verify($pass, $visitante['pass']) || $visitante['pass'] === $pass)) {
            //guardo los datos del visitante en sesion
            $_SESSION['usuario'] = $visitante['nombre'];
            $_SESSION['tipo'] = 'visitante';
            $_SESSION['idVisitante'] = $visitante['idVisitante'];
            header("Location: ../Controladores/controlCatalogo.php");
            exit();
        }

        //si no coincide nada muestro unma alerta
        echo "<script>alert('Correo o contraseña incorrectos.'); window.history.back();</script>";
    }
?>