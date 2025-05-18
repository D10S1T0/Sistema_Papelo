<?php

    include_once(__DIR__ . '/../../Modelos/categoriaModelo.php');
    require_once __DIR__ . '/../../Modelos/ProductoModelo.php';
    require_once __DIR__ . '/../../Controladores/controlCobro.php';
    include '../Includes/conexion.php';
    $puesto = isset($_SESSION['puesto']) ? strtolower($_SESSION['puesto']) : '';

    $url = match ($puesto) {
        'gerente' => "Location: ../Html/dashboardAdmi.php?seccion=cobro",
        'cajero'  => "Location: ../Html/dashboardCajero.php?seccion=cobro",
        default   => "Location: ../Html/Login.php"
    };
    //Codigo spaguetti ;v
	$ticket = $_SESSION['ticket'] ?? [];
    $productoAgregado = null;
    $pesProducto = true;
    $mensajeExito = '';
    $mensajeError = '';

	//Si se esta mostrando un pedido se activa la pestaña pedidos
	if (isset($_GET['mostrarPedido'])) {
		$pesProducto = false;
	}

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
        $idProducto = $_POST['id_producto'];
        $productoAgregado = obtenerProductoPorId($conexion, $idProducto);

        if ($productoAgregado) {
            $pesProducto = true;
            
        }
    }
        

    if (isset($_GET['mensaje_cobro']) && $_GET['mensaje_cobro'] == 1 && isset($_GET['cambio'])) {
        $mensajeExito = "Cobro realizado exitosamente. Cambio: $" . htmlspecialchars($_GET['cambio']);
    } elseif (isset($_GET['error'])) {
        $mensajeError = htmlspecialchars($_GET['error']);
    }

	if (!isset($_SESSION['ticket'])) {
		$_SESSION['ticket'] = [];
	}


    //mostrar ticket si existe
    $mostrarTicket = isset($_GET['mostrarPedido']) && !isset($_GET['mensaje_cobro']);	
	if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Cobro</h4>
            <p class="text-muted mb-0">Sistema de cobro de productos y pedidos</p>
        </div>
        
		<div id="botonesPedidos" class="d-none">
			<a onclick="generarPDFTexto(event)" class="btn btn-primary me-2">
				<i class="bi bi-receipt me-1"></i> Imprimir pedido
			</a>

		</div>
		<div id="botonesProductos" class="d-none">
			<a onclick="imprimirTicketProductos(event)" class="btn btn-primary me-2">
				<i class="bi bi-printer me-2"></i> Imprimir ticket
			</a>
			
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
        <!--productos y pedidos-->
        <div class="col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    
                    <!--Pestañikas -->
                    <div class ="pestañitas">
                        <ul class="nav nav-tabs mb-4">
                            
                            <li class="nav-item">
                                <a class="nav-link <?= $pesProducto ? 'active' : '' ?>" data-bs-toggle="tab" href="#productos">Productos</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link <?= !$pesProducto ? 'active' : '' ?>" data-bs-toggle="tab" href="#pedidos">Pedidos</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!--cont pestañas alias dolores de cabeza -->
                    <div class="tab-content">
                        <!--Productos -->
                        <div class="tab-pane fade <?= $pesProducto ? 'show active' : '' ?>" id="productos">
                            <form method="POST" action="<?php $url?>">
                                <div class="mb-4">
                                    <label class="form-label">ID del producto</label>
                                    <input type="number" 
                                        name="id_producto"
                                        class="form-control form-control-lg" 
                                        placeholder="Presiona Enter después de ingresar el ID" 
                                        id="IDProducto"
                                        autofocus
                                        required>
                                </div>
                            </form>

                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                <?php if ($productoAgregado): ?>
                                    <div class="col">
                                        <div class="card product-card h-100 border-0 shadow-sm">
                                            <div class="ratio ratio-1x1">
                                               <img src="data:image/jpeg;base64,<?= base64_encode($productoAgregado['imagen']) ?>" 
                                                    class="card-img-top object-fit-cover" 
                                                    alt="Producto">
                                            </div>
                                            <div class="card-body text-center">
                                                <h6 class="card-title mb-1"><?= $productoAgregado['nombreProducto'] ?></h6>
                                                <p class="text-success fw-bold mb-2">$<?= number_format($productoAgregado['precio'], 2) ?></p>
                                                <small class="text-muted">Código: <?= $productoAgregado['idProducto'] ?></small>
                                                
												<form method="POST" action="../Controladores/controlCobro.php">
													<input type="hidden" name="idProducto" value="<?= $productoAgregado['idProducto'] ?>">
													<input type="number" name="cantidad" min="0" class="form-control form-control-lg" autofocus required>
													<button type="submit" class="btn btn-sm btn-primary w-100 mt-2" >
														<i class="bi bi-plus-lg me-1"></i> Agregar
													</button>
												</form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!--Pedidos -->
                        <div class="tab-pane fade <?= !$pesProducto ? 'show active' : '' ?>" id="pedidos">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th># Pedido</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pedidos as $pedido): ?>
                                            <tr>
                                                <td class="fw-bold">PED-<?php echo $pedido['idPedido']; ?></td>
                                                <td><?php echo $pedido['cliente']; ?></td>
                                                <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                                                <td>
                                                    <form method="POST" action="../Controladores/controlCobro.php">
                                                        <input type="hidden" name="idPedido" value="<?php echo $pedido['idPedido']; ?>">

                                                        <!-- Ver Ticket -->
                                                        <a href="?seccion=cobro&mostrarPedido=<?php echo $pedido['idPedido']; ?>#pedidos" class="btn btn-primary">
                                                            <i class="bi bi-eye me-1"></i> Ver Ticket
                                                        </a>

                                                        <!-- Cancelar Pedido (botón que envía el formulario) -->
                                                        <button type="submit" name="cancelarPedido" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">
                                                            <i class="bi bi-x-circle me-1"></i> Cancelar Pedido
                                                        </button>
                                                    </form>
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
        </div>
		
        <?php include_once __DIR__ . '/../../Includes/ticket.php'; ?>
		
	</div>	
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    const detallesPedido = <?= json_encode($detallesPedido); ?>;
    const subtotal = <?= $total ?>;
    const iva = <?= $total * 0.16 ?>;
    const total = <?= $total * 1.16 ?>;
    const fechaVenta = "<?= date('d/m/Y') ?>";

    async function generarPDFTexto(event) {
        event.preventDefault();
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [80, 200], // formato
        });

        let y = 10;

        doc.setFontSize(14); // Tamaño de fuente 14 
        doc.text("              Sistema Papelo", 5, y); // 
        y += 8;

        doc.setFontSize(11); // Tamaño de fuente 11 
        doc.text("Ticket de Venta", 5, y); 
        y += 8;

        doc.setFontSize(10);
        doc.text("Fecha: " + fechaVenta, 5, y);
        y += 6;

        doc.setFontSize(11);
        doc.text("Detalles del pedido:", 5, y);
        y += 6;

        doc.setFont("helvetica", "bold");
        doc.text("Producto", 5, y);
        doc.text("   Precio", 50, y);
        y += 6;

        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        detallesPedido.forEach(p => {
            const nombreProducto = p.nombreProducto.length > 25 ? p.nombreProducto.slice(0, 25) + "..." : p.nombreProducto;
            doc.text(nombreProducto+` X${p.cantidad}`, 5, y);
            doc.text(`   $${parseFloat(p.precio).toFixed(2)}`, 50, y);
            y += 6;
        });

        y += 6;
        doc.setFont("helvetica", "bold");
        doc.text(`Subtotal:                                     $${subtotal.toFixed(2)}`, 5, y); y += 6;
        doc.text(`IVA (16%):                                    $${iva.toFixed(2)}`, 5, y); y += 6;
        doc.text(`Total:                                            $${total.toFixed(2)}`, 5, y); y += 8;

        doc.save("ticket_venta.pdf");
    }
</script>


<script>
    const ticketProductos = <?= json_encode($ticket); ?>;
    const subtotalProductos = <?= $totalProductos ?>;
    const ivaProductos = <?= $totalProductos * 0.16 ?>;
    const totalProductos = <?= $totalProductos * 1.16 ?>;
    const fecha = "<?= date('d/m/Y') ?>";

    async function imprimirTicketProductos(event) {
        event.preventDefault();
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: [80, 200],
        });

        let y = 10;

        // Encabezado
        doc.setFontSize(14);
        doc.text("              Sistema Papelo", 5, y);
        y += 8;

        doc.setFontSize(11);
        doc.text("Ticket de Venta (Productos)", 5, y);
        y += 8;

        doc.setFontSize(10);
        doc.text("Fecha: " + fecha, 5, y);
        y += 6;

        doc.setFontSize(11);
        doc.text("Detalle de productos:", 5, y);
        y += 6;

        doc.setFont("helvetica", "bold");
        doc.setFontSize(9);
        doc.text("Producto", 5, y);
        doc.text("Precio", 45, y);
        doc.text("Cant.", 65, y);
        y += 6;

        doc.setFont("helvetica", "normal");

        ticketProductos.forEach(item => {
            const nombre = item.nombre.length > 25 ? item.nombre.slice(0, 25) + "..." : item.nombre;
            doc.text(nombre, 5, y);
            doc.text(`$${parseFloat(item.precio).toFixed(2)}`, 45, y);
            doc.text(`x${item.cantidad}`, 65, y);
            y += 6;
        });

        y += 6;
        doc.setFont("helvetica", "bold");
        doc.text(`Subtotal:                               $${subtotalProductos.toFixed(2)}`, 5, y); y += 6;
        doc.text(`IVA (16%):                             $${ivaProductos.toFixed(2)}`, 5, y); y += 6;
        doc.text(`Total:                                     $${totalProductos.toFixed(2)}`, 5, y); y += 8;

        doc.save("ticket_productos.pdf");
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        actualizarBotones();
        
        var tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function (tab) {
            tab.addEventListener('shown.bs.tab', actualizarBotones);
        });

        function actualizarBotones() {
            var ticketPedidos = document.getElementById('ticketPedidos');
            var ticketProductos = document.getElementById('ticketProductos');
            const pestaña = document.querySelector('.nav-link.active')?.getAttribute('href');
            const botonesPedidos = document.getElementById('botonesPedidos');
            const botonesProductos = document.getElementById('botonesProductos');

            if (pestaña === '#pedidos') {
                botonesPedidos.classList.remove('d-none');
                botonesProductos.classList.add('d-none');
                ticketPedidos.classList.remove('d-none');
                ticketProductos.classList.add('d-none');
            } else {
                botonesProductos.classList.remove('d-none');
                botonesPedidos.classList.add('d-none');
                ticketProductos.classList.remove('d-none');
                ticketPedidos.classList.add('d-none');
            }
        }
    });
</script>


