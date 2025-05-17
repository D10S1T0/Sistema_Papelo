<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto | Papelo</title>
    <link rel="stylesheet" href="../Estilos/DetalleProducto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

    <?php include '../Includes/header.php';?>
    <!-- main principal -->
    <main class="container my-5">
        <?php if ($producto): ?>
            <div class="product-container row">
                <div class="col-md-6 product-image">
                    <div class="text-center">
                        <?php if (!empty($producto['imagen'])): ?><!--decoficiar en base64 pa la img-->
                            <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" class="img-fluid rounded" style="max-height: 500px; width: auto; object-fit: contain;">
                        <?php else: ?>
                            <img src="../Recursos/Imgs/default.jpg" class="img-fluid rounded" alt="Sin imagen">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 product-info">
                    <h1 class="product-title"><?= htmlspecialchars($producto['nombreProducto']) ?></h1>
                    <p class="text-muted"><i class="bi bi-tag"></i> <strong>Categoría:</strong> <?= htmlspecialchars($producto['nombreCategoria']) ?></p>
                    <p class="lead"><?= htmlspecialchars($producto['descripcion']) ?></p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="price me-3">$<?= number_format($producto['precio'], 2) ?></span>
                        <?php 
                            if($producto['stock']>0){
                                echo '<span class="badge bg-success">Disponible</span>';
                            }else{  
                                echo '<span class="badge bg-danger">No disponible</span>';
                            } 
                        ?>
                        
                    </div>
                    
                    <div class="mb-4">
                        <strong>Calificación:</strong>
                        <span class="rating ms-2">
                            <?php
                            $rating = $producto['promedio'] ?? 0;
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                            }
                            ?>
                        </span>
                        <span class="text-muted">(<?= $rating ?> de 5)</span>
                    </div>
                    <?php
                        if($producto['stock']>0){
                    ?>
                            <div class="d-flex align-items-center">
                                <form action="../controladores/controlAñadirPedido.php" method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="idProducto" value="<?= $producto['idProducto'] ?>">
                                    <div class="input-group me-3" style="width: 120px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(-1)">-</button>
                                        <input type="number" id="cantidad" name="cantidad" class="form-control text-center" value="1" min="1" max="5" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(1)">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-cart"><i class="bi bi-cart-plus"></i> Añadir al carrito</button>
                                </form>

                                <script>
                                    function cambiarCantidad(valor) {
                                        const input = document.getElementById('cantidad');
                                        let cantidad = parseInt(input.value);
                                        if (!isNaN(cantidad)) {
                                            cantidad += valor;
                                            if (cantidad < 1) cantidad = 1;
                                            if (cantidad > 5) cantidad = 5;
                                            input.value = cantidad;
                                        }
                                    }
                                </script> 
                            </div> 
                    <?php
                            }else{  
                                echo "<span class='lead'>No hay stock disponible de: {$producto['nombreProducto']} por el momento</span>";
                            } 
                    ?>

                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-triangle-fill"></i> Producto no encontrado.
            </div>
        <?php endif; ?>
    </main>

   <?php include '../Includes/footer.php';?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>