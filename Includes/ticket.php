<!--- tickets alias dolores de cabeza 2.0 -->
<div id="ticketContainer" class="col-lg-4 mt-3 mt-lg-0">
    <!--productos-->
    <div id="ticketProductos">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Ticket Productos</h5>
            </div>
            <div class="card-header bg-white">
                <h5 class="mb-0">Fecha: <?php echo date('d/m/Y') ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive mb-3" style="max-height: 300px; overflow-y: auto;">
                    <?php if (!empty($ticket)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalProductos = 0;
                                foreach ($ticket as $item):
                                    $subtotal = $item['precio'] * $item['cantidad'];
                                    $totalProductos += $subtotal;
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                                    <td><?= $item['cantidad'] ?></td>
                                    <td>$<?= number_format($item['precio'], 2) ?></td>
                                    <td>$<?= number_format($subtotal, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?= number_format($totalProductos, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA (16%):</span>
                                <span>$<?= number_format($totalProductos * 0.16, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>Total:</span>
                                <span>$<?= number_format($totalProductos * 1.16, 2) ?></span>
                            </div>
                        </div>
                                    
                        <form method="POST" action="../Controladores/controlCobro.php">
                            <div class="mb-4">
                                <label class="form-label">Efectivo Recibido</label>
                                <input type="number" name="efectivo" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                            <button type="submit" name="cobrarTicketManual" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                Cobrar
                            </button>
                            <a href="../Controladores/controlCobro.php?accion=cancelar" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                            </form>
                    <?php else: ?>
                        <p class="text-muted">No hay productos agregados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!--pedidos -->
    <div id="ticketPedidos">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Ticket Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive mb-3" style="max-height: 300px; overflow-y: auto;">
                    <?php if (!empty($detallesPedido)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($detallesPedido as $detalle):
                                    $total += $detalle['total'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detalle['nombreProducto']) ?></td>
                                        <td><?= $detalle['cantidad'] ?></td>
                                        <td>$<?= number_format($detalle['precio'], 2) ?></td>
                                        <td>$<?= number_format($detalle['total'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?= number_format($total, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA (16%):</span>
                                <span>$<?= number_format($total * 0.16, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>Total:</span>
                                <span>$<?= number_format($total * 1.16, 2) ?></span>
                            </div>
                        </div>

                        <!--Form pa cobrar pedido-->
                        <form method="POST" action="../Controladores/controlCobro.php">
                            <input type="hidden" name="idPedido" value="<?= $_GET['mostrarPedido'] ?>">

                            <div class="mb-4">
                                <label class="form-label">Efectivo Recibido</label>
                                <input type="number" name="efectivo" class="form-control" 
                                       placeholder="0.00" step="0.01" required>
                            </div>

                            <button type="submit" name="cobrarPedidoFinal" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i> Cobrar
                            </button>
                            <a href="../Controladores/controlCobro.php?accion=cancelar" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">No hay pedido seleccionado</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>