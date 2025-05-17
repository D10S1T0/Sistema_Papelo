<?php
    session_start();
    
    if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'empleado' || strtolower($_SESSION['puesto']) !== 'gerente') {
        header("Location: ../html/Login.php");
        exit();
    }

    //isset revisa si se paso seccion por get  si no pongo por defecto articulos  
    $seccionesPermitidas = ['articulos', 'personal', 'reportes', 'cobro', 'compras'];
    $seccionActual = isset($_GET['seccion']) ? $_GET['seccion'] : 'articulos';

    
    if (!in_array($seccionActual, $seccionesPermitidas)) {
        $seccionActual = 'articulos';
    }

    //Ruta pal include
    $rutaSeccion = '../Html/Secciones/' . $seccionActual . '.php';
    if (!file_exists($rutaSeccion)) {
        $rutaSeccion = 'includes/Secciones/articulos.php';
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Administración</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="..\Estilos\dashAdmi.css">
        
    </head>
    <body>  
        <!--Menu lateral -->
        <?php include '..\Includes\menuLateralAdmi.php';?>
        
        <div class="main-content">
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                   <h3>
                        <div class="user-profile d-flex align-items-center ">
                            <i class="bi bi-person"></i>
                            <span>Administrador: <?php echo $_SESSION['usuario'] ?></span>
                        </div>
                    </h3> 
                </div>
            </div>

             <!--contenido  segun seccion -->
            <?php include($rutaSeccion); ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>