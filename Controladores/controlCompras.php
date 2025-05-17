<?php
    require_once '../Includes/conexion.php';
    require_once '../Modelos/comprasModelo.php';
    require_once '../Modelos/productoModelo.php';

    //debi haber conocido esto antes para las sessiones
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['registrarProveedor'])) {
        $nombreProveedor = $_POST['nombreProveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];

        $resultado = ComprasModelo::registrarProveedor($conexion, $nombreProveedor, $contacto, $telefono);

        if ($resultado) {
            header("Location: ../Html/dashboardAdmi.php?seccion=compras&mensaje_cobro=1");
        } else {
            header("Location: ../Html/dashboardAdmi.php?seccion=compras&error=1");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'listarProveedores') {
            $id = $_POST['idProveedor'] ?? '';

            require_once '../Includes/conexion.php';
            require_once '../Modelos/comprasModelo.php';

            if ($id !== '') {
                $proveedor = ComprasModelo::buscarProveedorPorId($conexion, $id);
                if ($proveedor) {
                    echo json_encode([$proveedor]);
                } else {
                    echo json_encode([]);
                }
            } else {
                echo json_encode(ComprasModelo::obtenerTodosLosProveedores($conexion));
            }

            exit;
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //agregar producto
        if (isset($_POST['accion']) && $_POST['accion'] === 'agregarProducto') {
            $id = trim($_POST['codigoProducto']);
            $producto = obtenerProductoPorId($conexion, $id);

            if ($producto) {
                $producto['cantidad'] = 1;//mod cantidad
                $producto['precioCompra'] = $producto['precio'];
                $producto['imagen'] = "";

                if (!isset($_SESSION['productosCompra'])) {
                    $_SESSION['productosCompra'] = [];
                }

                if (isset($_SESSION['productosCompra'][$id])) {
                    $_SESSION['productosCompra'][$id]['cantidad'] += 1;
                } else {
                    $_SESSION['productosCompra'][$id] = $producto;
                }
            }

            header('Location: ../HTML/dashboardAdmi.php?seccion=compras');
            exit;
        }

    //eliminar producto
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarProducto') {
            $id = trim($_POST['idProducto']);
            if (isset($_SESSION['productosCompra'][$id])) {
                unset($_SESSION['productosCompra'][$id]);
            }
            header('Location: ../HTML/dashboardAdmi.php?seccion=compras');
            exit;
    }

    //Actualizar cantidad 
    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarCantidad') {
        $id = trim($_POST['idProducto']);
        $cantidad = (int)$_POST['cantidad'];

        if (isset($_SESSION['productosCompra'][$id]) && $cantidad > 0) {
            $_SESSION['productosCompra'][$id]['cantidad'] = $cantidad;
        }

        header('Location: ../HTML/dashboardAdmi.php?seccion=compras');
        exit;
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion']) && $_POST['accion'] === 'registrarCompra') {
            $idProveedor = $_POST['idProveedor'] ?? null;
            echo ($_POST['fecha']);
            print_r ($_SESSION['productosCompra']);
            print_r ($_POST['cantidades']);
            echo ("provedsdk".$idProveedor);
        

            $idEmpleado = $_SESSION['idEmpleado'];
            $fecha = $_POST['fecha'];
            $productos = $_POST['productos'];      
            $cantidades = $_POST['cantidades'];     

            try {
               
                $conexion->beginTransaction();

                
                $sqlCompra = "INSERT INTO Compras (idEmpleado, idProveedor, fecha) VALUES (?, ?, ?)";
                $stmtCompra = $conexion->prepare($sqlCompra);
                $stmtCompra->execute([$idEmpleado, $idProveedor, $fecha]);

                $idCompra = $conexion->lastInsertId(); 
                $sqlDetalle = "INSERT INTO DetalleCompra (idCompra, idProducto, cantidad) VALUES (?, ?, ?)";
                $stmtDetalle = $conexion->prepare($sqlDetalle);

                for ($i = 0; $i < count($productos); $i++) {
                    $idProducto = $productos[$i];
                    $cantidad = $cantidades[$i];

                    if ($cantidad > 0) {
                        $stmtDetalle->execute([$idCompra, $idProducto, $cantidad]);
                    }
                }

                $conexion->commit();

                
                header("Location: ../Html/dashboardAdmi.php?seccion=compras&mensaje_cobro=2");
                exit;

            } catch (PDOException $e) {
                $conexion->rollBack();
                echo "Error al registrar compraa: " . $e->getMessage();
            }
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idEliminarCompra'])) {
        $accion = $_POST['accion'] ?? '';
        //die("entro al if");
        $idCompra = $_POST['idEliminarCompra'];
        if ($accion === 'eliminarCompra') {
            if (ComprasModelo::eliminarCompra($conexion, $idCompra)) {
                header("Location: ../Html/dashboardAdmi.php?seccion=compras");
                exit;
            } else {
                echo "<script>alert('Error al eliminar la compraa');</script>";
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'cancelarCompra') {
            unset($_SESSION['productosCompra']);
            $exito = "Compra cancelada y productos eliminados.";
        }
        header("Location: ../Html/dashboardAdmi.php?seccion=compras&mensaje_cobro=2");
        exit;
        
    }



    
?>