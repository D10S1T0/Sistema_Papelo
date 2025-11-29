<?php 
    session_start();
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
    <title>Productos | Papelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Estilos/catalogo.css?v=1.1">    
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
		
		<!-- Barra de búsqueda con diseño mejorado -->
		<form id="formBusqueda" method="GET" action="../Controladores/controlCatalogo.php">
			<div class="mb-5">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<h2>Nuestros Productos</h2>
					<div class="d-flex align-items-center gap-2">
						<div class="input-group" style="width: 320px;">
							<span class="input-group-text bg-white border-end-0">
								<i class="bi bi-search"></i>
							</span>
							<input type="text" class="form-control border-start-0" name="buscar"
								   placeholder="Buscar por nombre..."
								   value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
						</div>

						<select class="form-select" name="categoria" id="selectCategoria" style="width: 220px;">
							<option value="" <?= empty($_GET['categoria']) ? 'selected' : '' ?>>Todas las categorías</option>
							<?php foreach ($categorias as $cat): ?>
								<option value="<?= htmlspecialchars($cat['nombreCategoria']) ?>"
									<?= ($_GET['categoria'] ?? '') === $cat['nombreCategoria'] ? 'selected' : '' ?>>
									<?= htmlspecialchars($cat['nombreCategoria']) ?>
								</option>
							<?php endforeach; ?>
						</select>

						<button type="submit" class="btn btn-primary">
							Buscar
						</button>
					</div>
				</div>
			</div>
		</form>

        
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
        

        
        <?php if ($totalPaginas > 1): ?>
			<nav class="mt-4">
				<ul class="pagination justify-content-center">
					<?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
						<li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>">
							<a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&categoria=<?= urlencode($categoria) ?>">
								<?= $i ?>
							</a>
						</li>
					<?php endfor; ?>
				</ul>
			</nav>
		<?php endif; ?>
    </main>
    
    <?php include '../Includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formBusqueda');
        const input = form.querySelector('input[name="buscar"]');
        const select = document.getElementById('selectCategoria');

        // Al presionar Enter en el input de búsqueda
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                form.submit();
            }
        });

        // Al cambiar la categoría
        select.addEventListener('change', function () {
            form.submit();
        });
    });
</script>


</html>
