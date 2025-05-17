<?php include_once('../Controladores/controlReportes.php'); ?>
<div class="container-fluid">
    
    <!--Btn descargaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-->
    <div class="btn-group">
        <button class="btn btn-outline-secondary" onclick="generarPDF()">
            <i class="bi bi-download me-1"></i> Descargar
        </button>
    </div>

<div id="reporte">
    <br>    
    <br>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes y Estadisticas</h4>
            <p class="text-muted mb-0">Analisis de ventas y desempeño</p>
        </div>
    </div>

    <div class="row mb-4">
        <!--ventas totales-->
        <div class="col-md-3">
            <div class="card dashboard-card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-primary">Ventas Totales</h6>
                            <h2 class="mb-0">$<?= number_format($ventasTotales, 2) ?></h2>
                        </div>
                        <i class="bi bi-currency-dollar text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!--total de pedidos-->
        <div class="col-md-3">
            <div class="card dashboard-card bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-success">Pedidos</h6>
                            <h2 class="mb-0"><?= $totalPedidos ?></h2>
                        </div>
                        <i class="bi bi-cart-check text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!--articulos vendidos-->
        <div class="col-md-3">
            <div class="card dashboard-card bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-warning">Articulos Vendidos</h6>
                            <h2 class="mb-0"><?= $articulosVendidos ?></h2>
                        </div>
                        <i class="bi bi-box-seam text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!--clientes nuevos-->
        <div class="col-md-3">
            <div class="card dashboard-card bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-info">Clientes Nuevos</h6>
                            <h2 class="mb-0"><?= $clientesNuevos ?></h2>
                        </div>
                        <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!--productos mas pedidos-->
        <div class="col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Articulos Mas Pedidos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Articulo</th>
                                    <th>Pedidos</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php foreach ($articulosMasPedidos as $articulo): ?>
                                    <tr>
                                        <td><?= $index++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="ratio ratio-1x1" style="width: 40px;">
                                                        <?php if (!empty($articulo['imagen'])): ?>
                                                            <img src="data:image/jpeg;base64,<?= base64_encode($articulo['imagen']) ?>"
                                                                class="img-thumbnail object-fit-cover" alt="<?= htmlspecialchars($articulo['nombreProducto']) ?>">
                                                        <?php else: ?>
                                                            <img src="https://via.placeholder.com/40"
                                                                class="img-thumbnail object-fit-cover" alt="Sin imagen">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($articulo['nombreProducto']) ?></h6>
                                                    <small class="text-muted">Ref. #PROD-<?= $articulo['idProducto'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $articulo['totalPedidos'] ?></td>
                                        <td>$<?= number_format($articulo['totalGenerado'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--pedidos recientes-->
        <div class="col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Pedidos Recientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total + IVA</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <tr>
                                        <td class="fw-bold">#PED-<?= str_pad($pedido['idPedido'], 4, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellidoPaterno']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></td>
                                        <td>$<?= number_format(($pedido['total'])*0.16 + $pedido['total'], 2) ?></td>
                                        <td>
                                            <span class="badge
                                                <?= $pedido['estado'] === 'Entregado' ? 'bg-success text-success bg-opacity-10' : 
                                                    ($pedido['estado'] === 'Pendiente' ? 'bg-warning text-warning bg-opacity-10' : 
                                                    'bg-danger text-danger bg-opacity-10') ?>">
                                                <?= $pedido['estado'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!--mejor valorados-->
        <div class="col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-star me-2"></i>Articulos Mejor Valorados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Articulo</th>
                                    <th>Valoracion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mejorValorados as $index => $producto): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="ratio ratio-1x1" style="width: 40px;">
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" 
                                                        class="img-thumbnail object-fit-cover" 
                                                        alt="<?= htmlspecialchars($producto['nombreProducto']) ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($producto['nombreProducto']) ?></h6>
                                                    <small class="text-muted">Ref. #<?= $producto['idProducto'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <span class="ms-1"><?= number_format($producto['promedio'], 1) ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Grafico de categorias mas populares-->
        <div class="col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Categorias Mas Populares</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($categoriasPopulares as $categoria): ?>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold"><?= htmlspecialchars($categoria['nombreCategoria']) ?></span>
                                        <span class="text-primary"><?= $categoria['porcentaje'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $categoria['porcentaje'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Scripts para generarpdf-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    async function generarPDF() {
        const { jsPDF } = window.jspdf;

        const elemento = document.getElementById('reporte');

        const canvas = await html2canvas(elemento, {
            scale: 2,
            useCORS: true
        });

        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'px',
            format: 'a4'
        });

        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        const marginSuperior = 20;
        const margin = 40;

        const imgWidth = pageWidth - margin * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', margin, marginSuperior, imgWidth, imgHeight);

        pdf.save("reporte_dashboard.pdf");
    }
</script>