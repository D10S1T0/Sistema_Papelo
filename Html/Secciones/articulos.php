<?php 
  include_once __DIR__ . '/../../Controladores/controlArticulos.php'; 
  include_once(__DIR__ . '/../../Modelos/categoriaModelo.php');
  //tcategorias .w.
  $categorias = obtenerTodasLasCategorias($conexion);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Gestión de Artículos</h4>
            <p class="text-muted mb-0">Aqui puedes ver y editar los productos</p>
        </div>
        
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarArticulo">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Artículo
        </button>
    </div>                    

    <!--buscar y filtrar productos -->
    <div class="card dashboard-card mb-4">
        <div class="card-body py-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="inputBuscar" placeholder="Busca productos..."
                            value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                    </div>
                </div>
                
                <!--por categoria -->
                <div class="col-md-3">
                <select class="form-select" id="selectCategoria" name="categoria">
                    <option value="" <?= empty($_GET['categoria']) ? 'selected' : '' ?>>Todas</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['nombreCategoria']) ?>" 
                            <?= ($_GET['categoria'] ?? '') === $cat['nombreCategoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombreCategoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>
                
                <!--res -->
                <div class="col-md-3">
                    <select class="form-select" id="selectOrden">
                        <option value="" <?= empty($_GET['orden']) ? 'selected' : '' ?>>Ordenar</option>
                        <option value="nombre_asc" <?= ($_GET['orden'] ?? '') === 'nombre_asc' ? 'selected' : '' ?>>Nombre (A-Z)</option>
                        <option value="nombre_desc" <?= ($_GET['orden'] ?? '') === 'nombre_desc' ? 'selected' : '' ?>>Nombre (Z-A)</option>
                        <option value="stock_desc" <?= ($_GET['orden'] ?? '') === 'stock_desc' ? 'selected' : '' ?>>Stock (Mayor)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!--Tabla productos -->
    <div class="card dashboard-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th style="width: 80px;">Imagen</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td>
                                <h6 class="mb-1"><?= htmlspecialchars($producto['idProducto']) ?></h6>
                                <p class="text-muted small mb-0">Ref. #PROD-<?= $producto['idProducto'] ?></p>
                            </td>
                            <td>
                                <div class="ratio ratio-1x1" style="width: 60px;">
                                    <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" 
                                        class="img-thumbnail object-fit-cover" 
                                        alt="<?= htmlspecialchars($producto['nombreProducto']) ?>">
                                </div>
                            </td>
                            <td>
                                <h6 class="mb-1"><?= htmlspecialchars($producto['nombreProducto']) ?></h6>
                                <p class="text-muted small mb-0">Ref. #PROD-<?= $producto['idProducto'] ?></p>
                            </td>
                            <td><span class="badge bg-info bg-opacity-10 text-info"><?= htmlspecialchars($producto['nombreCategoria']) ?></span></td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td class="fw-bold"><?= $producto['stock'] ?></td>
                            <td>
                                <?php
                                //para ver el estado segun cuantos quedan
                                $estado = $producto['stock'] <= 5 ? 'Agotándose' : ($producto['stock'] < 20 ? 'Bajo stock' : 'Disponible');
                                $colores = ['Agotándose' => 'danger', 'Bajo stock' => 'warning', 'Disponible' => 'success'];
                                ?>
                                <span class="badge bg-<?= $colores[$estado] ?> bg-opacity-10 text-<?= $colores[$estado] ?>"><?= $estado ?></span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <!-- Boton pa editar -->
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarArticulo"
                                        data-id="<?= $producto['idProducto'] ?>"
                                        data-nombre="<?= htmlspecialchars($producto['nombreProducto']) ?>"
                                        data-descripcion="<?= htmlspecialchars($producto['descripcion']) ?>"
                                        data-precio="<?= $producto['precio'] ?>"
                                        data-stock="<?= $producto['stock'] ?>">
                                        <i class="bi bi-pencil"></i>
                                     </button>
                                     <!-- Boton pa borrar -->
                                     <button class="btn btn-sm btn-outline-danger btn-eliminar"
                                        data-id="<?= $producto['idProducto'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <!--borrar productos -->
                <form id="formEliminarProducto" action="../Controladores/controlArticulos.php" method="POST" style="display: none;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="idProducto" id="inputEliminarId">
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal editar productos -->
<div class="modal fade" id="modalEditarArticulo" tabindex="-1" aria-labelledby="modalEditarArticuloLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../Controladores/controlArticulos.php" method="POST" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarArticuloLabel">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="idProducto" id="editar-idProducto">

        <div class="mb-3">
          <label for="editar-nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="editar-nombre" name="nombreProducto" required>
        </div>
        
        <div class="mb-3">
            <label for="editar-categoria" class="form-label">Categoría</label>
            <select class="form-select" id="selectCategoria" name="categoria">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['idCategoria']) ?>">
                        <?= htmlspecialchars($cat['nombreCategoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
          <label for="editar-descripcion" class="form-label">Descripción</label>
          <textarea class="form-control" id="editar-descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="editar-precio" class="form-label">Precio</label>
          <input type="number" class="form-control" id="editar-precio" name="precio" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="editar-stock" class="form-label">Stock</label>
          <input type="number" class="form-control" id="editar-stock" name="stock" required>
        </div>
        <div class="mb-3">
          <label for="editar-imagen" class="form-label">Imagen</label>
          <input type="file" class="form-control" id="editar-imagen" name="imagen" accept="image/*">
          <div class="form-text">Sube nueva imagen si quieres cambiarla</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!--modal pa agregar productos-->
<div class="modal fade" id="modalAgregarArticulo" tabindex="-1" aria-labelledby="modalAgregarArticuloLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../Controladores/controlArticulos.php" method="POST" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarArticuloLabel">Agregar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="agregar">

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombreProducto" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Categoría</label>
          <select class="form-select" id="selectCategoria" name="categoria">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['idCategoria']) ?>" 
                        <?= ($_GET['categoria'] ?? '') === $cat['nombreCategoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombreCategoria']) ?>
                    </option>
                    <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea class="form-control" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Precio</label>
          <input type="number" class="form-control" name="precio" step="0.01" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Imagen</label>
          <input type="file" class="form-control" name="imagen" accept="image/*" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Agregar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!--modal pa agregar categorias -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-labelledby="modalAgregarCategoriaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../Controladores/controlCategorias.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarCategoriaLabel">Nueva Categoría</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="agregar">
        <div class="mb-3">
          <label for="nombreCategoria" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombreCategoria" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Agregar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>


<script>
    //elementos pa los filtros
    const buscar = document.getElementById('inputBuscar');
    const categoria = document.getElementById('selectCategoria');
    const orden = document.getElementById('selectOrden');

    //buscar al presionar enter
    buscar.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            aplicarFiltros();
        }
    });

    categoria.addEventListener('change', aplicarFiltros);
    orden.addEventListener('change', aplicarFiltros);
    function aplicarFiltros() {
        const query = new URLSearchParams(window.location.search);
        query.set('buscar', buscar.value);
        query.set('categoria', categoria.value);
        query.set('orden', orden.value);
        window.location.href = '?' + query.toString();
    }


    //llenado el modal
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modalEditarArticulo');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            //datos dle boton
            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var descripcion = button.getAttribute('data-descripcion');
            var precio = button.getAttribute('data-precio');
            var stock = button.getAttribute('data-stock');

            //valores en el form
            document.getElementById('editar-idProducto').value = id;
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-descripcion').value = descripcion;
            document.getElementById('editar-precio').value = precio;
            document.getElementById('editar-stock').value = stock;
        }); 
    });

  //Script pa eliminar productos
  document.querySelectorAll('.btn-eliminar').forEach(button => {
      button.addEventListener('click', function () {
          const id = this.getAttribute('data-id');
          if (confirm("¿Seguro que quieres borrar este producto?")) {
              document.getElementById('inputEliminarId').value = id;
              document.getElementById('formEliminarProducto').submit();
          }
      });
  });
</script>