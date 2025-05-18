<?php
    require_once __DIR__ . '/../Modelos/cobroModelo.php';
    require_once __DIR__ . '/../Modelos/productoModelo.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cobrarPedidoFinal'])) {
		$idPedido = $_POST['idPedido'];
		$efectivo = $_POST['efectivo'];
		session_start();
		$idEmpleado = $_SESSION['idEmpleado'] ?? 1; 

		$resultado = CobroModelo::cobrarPedido($idPedido, $efectivo, $idEmpleado);
		
		if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
            default   => "Location: ../Html/Login.php"
        };

		if (isset($resultado['error'])) {
			header($url."&error=" . urlencode($resultado['error']));
		} else {
			$cambio = number_format($resultado['cambio'], 2);
			header($url."&mensaje_cobro=1&cambio=$cambio");
		}
		exit;
	}


    //agre producto
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'])) {
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $producto = obtenerProductoPorId($conexion, $idProducto);
        
        if ($producto) {
            session_start();
            $encontrado = false;
            
            //sigifiacdo de ?? : Si $a existe y no es null, devuelve $a; de lo contrario, devuelve $b
            foreach ($_SESSION['ticket'] ?? [] as &$item) {
                if ($item['id'] == $producto['idProducto']) {
                    $item['cantidad'] += 1;
                    $encontrado = true;
                    break;
                }
            }
            unset($item);

            //agregarlo si no esta
            if (!$encontrado) {
                $_SESSION['ticket'][] = [
                    'id' => $producto['idProducto'],
                    'nombre' => $producto['nombreProducto'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad,
                ];
            }
        } else {
            $_SESSION['error'] = "Producto no encontrado";
        }

        //redireccion segun puesto ;V
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
            default   => "Location: ../Html/Login.php?"
        };

        header($url);
        exit;
    }

    //pedidos pendientes
    $pedidos = CobroModelo::obtenerPedidosPendientes();
    $detallesPedido = [];

    //detalles si se solicito
    if (isset($_GET['mostrarPedido'])) {
        $idPedidoMostrar = $_GET['mostrarPedido'];
        $detallesPedido = CobroModelo::obtenerDetallesPedido($idPedidoMostrar);
    }

    //cancelar ticket
    if (isset($_GET['accion']) && $_GET['accion'] === 'cancelar') {
        session_start(); 
        unset($_SESSION['ticket']);

        //redireccion segun puesto ;v
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
            default   => "Location: ../Html/Login.php?"
        };

        header($url);
        exit;
    }

    //cancelar pedido existente
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelarPedido'])) {
        $idPedido = $_POST['idPedido'];
        CobroModelo::cancelarPedido($idPedido);

        //redireccion segun puesto ;v
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

        $url = match ($puesto) {
            'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
            'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
            default   => "Location: ../Html/Login.php?"
        };

        header($url);
        exit;
    }

    //cobrar productos 
    if (isset($_POST['cobrarTicketManual'])) {
        session_start();

        if (!empty($_SESSION['ticket']) && isset($_SESSION['idEmpleado'])) {
            $productos = $_SESSION['ticket'];
            $idEmpleado = $_SESSION['idEmpleado'];
            $efectivo = floatval($_POST['efectivo']);

            $resultado = CobroModelo::registrarVentaProductos($productos, $idEmpleado, $efectivo);

            //redireccion segun puesto ;v
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

            $url = match ($puesto) {
                'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
                'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
                default   => "Location: ../Html/Login.php?"
            };

            if ($resultado['exito']) {
                unset($_SESSION['ticket']);                
                header($url."&mensaje_cobro=1&cambio=" . $resultado['cambio']);
            } else {
                header($url."&error=".$resultado['error']); //die($url2);
            }
        } else {
            header($url."&error=datos");
        }
        exit;
    }
?>