<?php

include_once(__DIR__ . '/../Modelos/productoModelo.php');
include_once(__DIR__ . '/../Includes/conexion.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
        $datos = $_POST;
    
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        }
    
        agregarProducto($conexion, $datos, $imagen);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=articulos",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=articulos",
            default   => "Location: ../Html/Login.php"
        };

        header($url);
        exit;

    }
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar') {
        $idProducto = $_POST['idProducto'];
        $sql = "DELETE FROM Productos WHERE idProducto = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);
        $stmt->execute();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=articulos",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=articulos",
            default   => "Location: ../Html/Login.php?"
        };

        header($url);
        exit;
    }
     

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'editar') {
        $datos = $_POST;
        $imagenNueva = null;

        // Verificar si hay nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenNueva = file_get_contents($_FILES['imagen']['tmp_name']);
        }

        editarProducto($conexion, $datos, $imagenNueva);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
                                                //pasar a minusculas pa comparar
        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=articulos",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=articulos",
            default   => "Location: ../Html/Login.php"
        };

        header($url);
        exit;
    }

     // Parámetros de paginación
    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
    $productosPorPagina = 10; // Puedes ajustar este número
    $offset = ($pagina - 1) * $productosPorPagina;

    // Parámetros de filtros
    $buscar = $_GET['buscar'] ?? '';
    $categoria = $_GET['categoria'] ?? '';
    $orden = $_GET['orden'] ?? '';

    // Obtener productos paginados
    $productos = obtenerProductosConFiltroPaginados($conexion, $buscar, $categoria, $orden, $productosPorPagina, $offset);
    
    // Obtener total de productos para la paginación
    $totalProductos = contarProductosConFiltro($conexion, $buscar, $categoria);
    $totalPaginas = ceil($totalProductos / $productosPorPagina);

} catch (Exception $e) {
    $productos = [];
    $totalPaginas = 1;
    $pagina = 1;
}

?>
