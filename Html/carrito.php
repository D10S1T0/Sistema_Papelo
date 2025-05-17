<?php 
    if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'visitante') {
        header("Location: ../html/Login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Estilos/carrito.css">
</head>
<body>
    
    <?php include '../Includes/header.php';?>
    
    <main class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-container">
                    <h2 class="cart-title"><i class="bi bi-cart"></i> Carrito de <?php echo $_SESSION['usuario']?></h2>
                    
                    <?php
                        //productos por idProducto
                        $productosAgrupados = [];

                        foreach ($carrito as $item) {
                            $id = $item['idProducto'];
                            if (isset($productosAgrupados[$id])) {
                                $productosAgrupados[$id]['cantidad'] += $item['cantidad'];
                            } else {
                                $productosAgrupados[$id] = $item;
                            }
                        }
                    ?>

                    <!--carrito prod pedidos-->
                    <?php foreach ($productosAgrupados as $producto): ?>
                        <div class="cart-item">
                            <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" alt="<?= $producto['nombreProducto'] ?>" class="cart-item-img">
                            <div class="cart-item-info">
                                <h4 class="cart-item-title"><?= $producto['nombreProducto'] ?></h4>
                                <p class="text-muted mb-2"><?= $producto['descripcion'] ?></p>
                                <span class="cart-item-price">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <div class="quantity-control">
                                <label ><?= $producto['cantidad'] ?></label>
                            </div>
                            <form action="../Controladores/controlCarrito.php" method="POST">
                                <input type="hidden" name="idProducto" value="<?= $producto['idProducto'] ?>">
                                <button class="remove-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto de tu pedido?');"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="summary-title">Resumen del Pedido</h4>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($total, 2) ?></span>
                    </div>

                    <div class="summary-row total">
                        <span>Total (iVA 16%):</span>
                        <span>$<?= number_format(($total * 0.16)+$total, 2) ?></span> <!-- con 10% de impuesto, si aplica -->
                    </div>

                </div>
            </div>


            <div class="col-lg-8">
                <div class="cart-container">
                    <h2 class="cart-title"><i class="bi bi-cart"></i> Productos entregados a <?php echo $_SESSION['usuario']?></h2>
                    
                    <?php
                        $productosAgrupados = [];

                        foreach ($carritoEntregado as $item) {
                            $id = $item['idProducto'];
                            if (isset($productosAgrupados[$id])) {
                                $productosAgrupados[$id]['cantidad'] += $item['cantidad'];
                            } else {
                                $productosAgrupados[$id] = $item;
                            }
                        }
                    ?>
                     
                    <!--carrito prod entregados-->
                    <?php foreach ($productosAgrupados as $producto): ?>
                        <div class="cart-item">
                            <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" alt="<?= $producto['nombreProducto'] ?>" class="cart-item-img">
                            <div class="cart-item-info">
                                <h4 class="cart-item-title"><?= $producto['nombreProducto'] ?></h4>
                                <p class="text-muted mb-2"><?= $producto['descripcion'] ?></p>
                                <span class="cart-item-price">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                            <div class="quantity-control">
                                <label ><?= $producto['cantidad'] ?></label>
                            </div>
                            <form action="" method="POST">
                                <input type="hidden" name="idProducto" value="<?= $producto['idProducto'] ?>">
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </main>

     <?php include '../Includes/footer.php';?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>