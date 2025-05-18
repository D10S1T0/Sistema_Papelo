<?php
    require_once '../Includes/conexion.php';
    include_once(__DIR__ . '/../../Modelos/comprasModelo.php');
    $proveedores = ComprasModelo::obtenerTodosLosProveedores($conexion);

    $mensajeExito = '';
    $mensajeError = '';
    if (isset($_GET['mensaje_cobro'])) {
        if($_GET['mensaje_cobro']==1){$mensajeExito =  "El proveedor se agrego correctamente";}
        if($_GET['mensaje_cobro']==2){$mensajeExito =  "La compra se agrego correctamente";}
    } elseif (isset($_GET['error'])) {
        $mensajeError = htmlspecialchars($_GET['error']);
    }
    
    $comprasRegistradas = ComprasModelo::obtenerComprasConDetalles($conexion);

?>
<?php  ?>
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="bi bi-cart-plus me-2"></i> Registro de Compras</h4>
            <p class="text-muted mb-0">Gestión de proveedores y compras de productos</p>
        </div>
        <div>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
                <i class="bi bi-building-add me-1"></i> Nuevo Proveedor
            </button>
            <form action="../Controladores/controlCompras.php" method="POST" style="display: inline;">
                <input type="hidden" name="accion" value="cancelarCompra">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle me-1"></i> Cancelar Compra
                </button>
            </form>
        </div>
    </div>
    <!--Mensajaes de exito o error-->
    <?php if ($mensajeExito): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= $mensajeExito ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <?php if ($mensajeError): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $mensajeError ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <!-- C: Datos de compra -->
        <div class="col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    

                    <!-- entrada productos -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i> Productos a Comprar</h5>
                        
                        <div class="input-group mb-3">
                            <form action="../Controladores/controlCompras.php" method="POST" class="input-group mb-3">
                                <input type="text" name="codigoProducto" class="form-control form-control-lg" placeholder="ID de producto" autofocus required>
                                <input type="hidden" name="accion" value="agregarProducto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Agregar
                                </button>
                            </form>

                        </div>
                        <p>Una vez agregada la cantidad de producto presione la tecla enter para confirmar.</p>
                        <!--Tabla productos -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="">
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>P. Compra</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $total = 0;
                                        if (isset($_SESSION['productosCompra']) && !empty($_SESSION['productosCompra'])):
                                            foreach ($_SESSION['productosCompra'] as $prod):
                                                $subtotal = $prod['cantidad'] * $prod['precioCompra'];
                                                $total += $subtotal;
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($prod['idProducto']) ?></td>
                                            <td><?= htmlspecialchars($prod['nombreProducto']) ?></td>
                                            <td style="width: 100px;">
                                                <form action="../Controladores/controlCompras.php" method="POST" class="form-actualizar-cantidad">
                                                    <input type="hidden" name="accion" value="actualizarCantidad">
                                                    <input type="hidden" name="idProducto" value="<?= $prod['idProducto'] ?>">
                                                    <input type="number" name="cantidad" class="form-control form-control-sm cantidad-input"
                                                        value="<?= $prod['cantidad'] ?>" min="1" required>
                                                </form>
                                            </td>
                                            <td style="width: 120px;">
                                                <input type="number" class="form-control form-control-sm" value="<?= $prod['precioCompra'] ?>" readonly>
                                            </td>
                                            <td>$<?= number_format($subtotal, 2) ?></td>
                                            <td style="width: 40px;">
                                                <form action="../Controladores/controlCompras.php" method="POST">
                                                    <input type="hidden" name="accion" value="eliminarProducto">
                                                    <input type="hidden" name="idProducto" value="<?= $prod['idProducto'] ?>">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No hay productos agregados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $subtotal = 0;
            if (isset($_SESSION['productosCompra']) && !empty($_SESSION['productosCompra'])) {
                foreach ($_SESSION['productosCompra'] as $prod) {
                    $subtotal += $prod['cantidad'] * $prod['precioCompra'];
                }
            }
            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;
            //print_r($_SESSION['productosCompra']);
        ?>

        <!--Ressumen -->
        <div class="col-lg-4 mt-3 mt-lg-0">
            <form action="../Controladores/controlCompras.php" method="POST">
                <input type="hidden" name="accion" value="registrarCompra">
                <div class="card dashboard-card h-100">
                    <?php if (isset($_SESSION['productosCompra']) && is_array($_SESSION['productosCompra']) && count($_SESSION['productosCompra']) > 0): 
                        foreach ($_SESSION['productosCompra'] as $producto): ?>
                            <input type="hidden" name="productos[]" value="<?= $producto['idProducto'] ?>">
                            <input type="hidden" name="cantidades[]" value="<?= $producto['cantidad'] ?>">
                        <?php endforeach; endif; ?>

                
                
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Resumen de Compra</h5>
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Fecha de compra</label>
                            <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Proveedor</label>
                            <select name="idProveedor" id="idProveedor" class="form-select" required>
                                
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?= htmlspecialchars($proveedor['idProveedor']) ?>">
                                        <?= htmlspecialchars($proveedor['nombreProveedor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <!--totales -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA (16%):</span>
                                <span>$<?= number_format($iva, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>Total:</span>
                                <span>$<?= number_format($total, 2) ?></span>
                            </div>
                        </div>

                        <!-- Botón de registrar compra -->
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Registrar compra
                        </button>
                    </div>
                </div>
            </form>
        </div>
        

        <!--compras registradas -->
        <div class="col-lg-12 mt-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4"><i class="bi bi-list-check me-2"></i> Historial de Compras</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Empleado</th>
                                    <th>Proveedor</th>
                                    <th>Productos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($comprasRegistradas)) : ?>
                                    <?php foreach ($comprasRegistradas as $compra) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($compra['idCompra']) ?></td>
                                            <td><?= htmlspecialchars($compra['fecha']) ?></td>
                                            <td><?= htmlspecialchars($compra['nombreEmpleado']) ?></td>
                                            <td><?= htmlspecialchars($compra['nombreProveedor']) ?></td>
                                            <td>
                                                <?php
                                                if (!empty($compra['productos'])) {
                                                    $productos = explode(',', $compra['productos']);
                                                    foreach ($productos as $producto) {
                                                        echo htmlspecialchars(trim($producto)) . '<br>';
                                                    }
                                                } else {
                                                    echo "Sin productos";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="../Controladores/controlCompras.php">
                                                    <input type="hidden" name="accion" value="eliminarCompra">
                                                    <input type="hidden" name="idEliminarCompra" value="<?= $compra['idCompra'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta compra?')">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay compras registradas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>

<!-- modasl para nuevo proveedor -->
<div class="modal fade" id="nuevoProveedorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../Controladores/controlCompras.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Proveedor</label>
                            <input type="text" class="form-control" name="nombreProveedor" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control" name="contacto" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" required>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="registrarProveedor" class="btn btn-primary">Guardar Proveedor</button>
                    </div>
                </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.cantidad-input');

        inputs.forEach(input => {
            input.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault(); // evita que se envíe el formulario completo
                    const form = input.closest('form');
                    if (form) form.submit();
                }
            });
        });
    });
</script>


