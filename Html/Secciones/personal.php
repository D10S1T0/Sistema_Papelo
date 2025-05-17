<?php 
require_once '../Includes/conexion.php';
require_once '../Modelos/usuarioModelo.php';

$puestos = obtenerPuestos($conexion);

$empleados = obtenerEmpleados($conexion);
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="bi bi-people me-2"></i>Gestión de Personal</h4>
            <p class="text-muted mb-0">Administra el registro de empleados</p>
        </div>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPuesto" style="border: none; background-color: #2c3e50; color: white;">
            <i class="bi bi-person-plus me-1"></i> Nuevo puesto
        </button>

        <button class="btn" data-bs-toggle="modal" data-bs-target="#nuevoEmpleadoModal" style="border: none; background-color: #2c3e50; color: white;">
            <i class="bi bi-person-plus me-1"></i> Contratar Empleado
        </button>
    </div>

    <!--tabla empleads -->
    <div class="card dashboard-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Puesto</th>
                            <th>Contratación</th>
                            <th style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td class="fw-bold">EMP-<?php echo str_pad($empleado['idEmpleado'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="ratio ratio-1x1" style="width: 40px;">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($empleado['nombreEmpleado']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($empleado['apellidoPaterno'] . ' ' . $empleado['apellidoMaterno']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($empleado['correo']); ?></td>
                                <td><?php echo htmlspecialchars($empleado['telefono']); ?></td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo htmlspecialchars($empleado['nombrePuesto']); ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($empleado['fechaContratacion'])); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button 
                                            class="btn btn-sm btn-outline-primary me-1 btn-editar-empleado"
                                            data-id="<?= $empleado['idEmpleado'] ?>"
                                            data-nombre="<?= htmlspecialchars($empleado['nombreEmpleado']) ?>"
                                            data-apellido-paterno="<?= htmlspecialchars($empleado['apellidoPaterno']) ?>"
                                            data-apellido-materno="<?= htmlspecialchars($empleado['apellidoMaterno']) ?>"
                                            data-fecha-nacimiento="<?= $empleado['fechaNacimiento'] ?>"
                                            data-fecha-contratacion="<?= $empleado['fechaContratacion'] ?>"
                                            data-correo="<?= htmlspecialchars($empleado['correo']) ?>"
                                            data-telefono="<?= $empleado['telefono'] ?>"
                                            data-idpuesto="<?= $empleado['idPuesto'] ?>"
                                        ><i class="bi bi-pencil"></i>
                                        </button>

                                        <button 
                                            class="btn btn-sm btn-outline-danger btn-eliminar-empleado"
                                            data-id="<?= $empleado['idEmpleado'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>

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

<!-- nuevo Puesto -->
<div class="modal fade" id="nuevoPuesto" tabindex="-1" aria-labelledby="nuevoPuestoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="../Controladores/controlUsuario.php">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoPuestoLabel">Agregar nuevo puesto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nombrePuesto" class="form-label">Nombre del puesto</label>
          <input type="text" class="form-control" name="nombrePuesto" id="nombrePuesto" required>
        </div>
        <div class="mb-3">
          <label for="salario" class="form-label">Salario mensual</label>
          <input type="number" step="0.01" class="form-control" name="salario" id="salario" required>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="accion" value="agregarPuesto">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar puesto</button>
      </div>
    </form>
  </div>
</div>


<!--nuevo empleado -->
<div class="modal fade" id="nuevoEmpleadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../Controladores/controlUsuario.php">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre(s)</label>
                            <input type="text" class="form-control" name="nombreEmpleado" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" name="apellidoPaterno" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" name="apellidoMaterno">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="fechaNacimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Contratación</label>
                            <input type="date" class="form-control" name="fechaContratacion" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="pass" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Puesto</label>
                            <select class="form-select" name="idPuesto" required>
                                <option value="">Seleccionar puesto</option>
                                <?php foreach ($puestos as $puesto): ?>
                                    <option value="<?= $puesto['idPuesto'] ?>"><?= htmlspecialchars($puesto['nombrePuesto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="accion" value="agregarEmpleado">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Empleado</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<!--editar un empleado -->
<div class="modal fade" id="modalEditarEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="../Controladores/controlUsuario.php" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Editar Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="idEmpleado" id="editarIdEmpleado">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" name="nombreEmpleado" id="editarNombre" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" name="apellidoPaterno" id="editarApellidoPaterno" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" name="apellidoMaterno" id="editarApellidoMaterno">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fechaNacimiento" id="editarFechaNacimiento" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Contratación</label>
                        <input type="date" class="form-control" name="fechaContratacion" id="editarFechaContratacion" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" id="editarCorreo" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" id="editarTelefono" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puesto</label>
                        <select class="form-select" name="idPuesto" id="editarIdPuesto" required>
                            <option value="">Seleccionar puesto</option>
                            <?php foreach ($puestos as $puesto): ?>
                                <option value="<?= $puesto['idPuesto'] ?>"><?= $puesto['nombrePuesto'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-editar-empleado').forEach(boton => {
        boton.addEventListener('click', () => {
            document.getElementById('editarIdEmpleado').value = boton.dataset.id;
            document.getElementById('editarNombre').value = boton.dataset.nombre;
            document.getElementById('editarApellidoPaterno').value = boton.dataset.apellidoPaterno;
            document.getElementById('editarApellidoMaterno').value = boton.dataset.apellidoMaterno;
            document.getElementById('editarFechaNacimiento').value = boton.dataset.fechaNacimiento;
            document.getElementById('editarFechaContratacion').value = boton.dataset.fechaContratacion;
            document.getElementById('editarCorreo').value = boton.dataset.correo;
            document.getElementById('editarTelefono').value = boton.dataset.telefono;
            document.getElementById('editarIdPuesto').value = boton.dataset.idpuesto;
            new bootstrap.Modal(document.getElementById('modalEditarEmpleado')).show();
        });
    });

    document.querySelectorAll('.btn-eliminar-empleado').forEach(boton => {
        boton.addEventListener('click', () => {
            const idEmpleado = boton.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
                fetch('../Controladores/controlUsuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `accion=eliminar&idEmpleado=${idEmpleado}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Empleado eliminado correctamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar el empleado.');
                    }
                })
                .catch(() => alert('Error de conexión al eliminar.'));
            }
        });
    });

</script>

