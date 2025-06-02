<?php 
    require_once '../Includes/conexion.php';
    require_once '../Modelos/PedidoModelo.php';
    session_start();
	
	
	


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto']) && isset($_SESSION['idVisitante'])) {
        $idProducto = $_POST['idProducto'];
        $idVisitante = $_SESSION['idVisitante'];

        eliminarProductoDeCarrito($conexion, $idVisitante, $idProducto);
        
		
        header("Location: ../Controladores/controlCarrito.php");
        exit();
    }

    $idVisitante = $_SESSION['idVisitante']; 

	cancelarPedidosVencidos($conexion, $idVisitante);
	
    $carrito = obtenerCarritoPorVisitante($conexion, $idVisitante);

    $carritoEntregado = obtenerCarritoPorVisitanteEntregado($conexion, $idVisitante);
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    //die($total);
    include '../Html/carrito.php'; //loooooooooooooooooooooooooooooool

?>