<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Papelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Estilos/catalogo.css">    
</head>
<body>
    <?php include '../Includes/header.php';?>

    <!--Principa -->
    <main class="container my-5">
        <!--alertas -->
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'producto_agregado'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Producto agregado correctamente al pedido.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div> <?php endif;
            if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'producto_no_agregado'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                El producto no se ha podido agregar al pedido.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>
        
        <h2 class="mb-4">Nuestros Productos</h2>
        
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-img-container">
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" class="product-img" alt="<?= htmlspecialchars($producto['nombreProducto']) ?>">
                            <?php else: ?>
                                <img src="../Recursos/Imgs/default.jpg" class="product-img" alt="Producto sin imagen">
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <h5 class="product-title"><?= htmlspecialchars($producto['nombreProducto']) ?></h5>
                            <p class="text-muted small mb-2"><?= htmlspecialchars($producto['nombreCategoria']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">$<?= number_format($producto['precio'], 2) ?></span>
                                <span class="text-warning small">
                                    <?php
                                    $rating = $producto['promedio'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                                    }
                                    ?>
                                </span>
                            </div>
                            <a href="controlDetalleProducto.php?id=<?= $producto['idProducto'] ?>" class="btn btn-sm btn-outline-primary w-100 mt-2">Ver detalle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!--paginacion -->
        <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de productos">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    $inicio = max(1, $paginaActual - 2);
                    $fin = min($totalPaginas, $paginaActual + 2);

                    if ($inicio > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?pagina=1">1</a></li>';
                        if ($inicio > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor;

                    if ($fin < $totalPaginas) {
                        if ($fin < $totalPaginas - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?pagina='.$totalPaginas.'">'.$totalPaginas.'</a></li>';
                    }
                    ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </main>
    
    <?php include '../Includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
