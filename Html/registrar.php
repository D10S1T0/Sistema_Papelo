<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Papelería Sandy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Estilos/Registrar.css">
</head>
<body>

<div class="container">
    <div class="register-container">
        <div class="register-logo">
            <h2 style="color: #2c3e50;"><i class="bi bi-shop"></i> Papelo</h2>
            <p class="text-muted">Crear una nueva cuenta</p>
        </div>

        <form action="../Controladores/controlRegistro.php" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
                <label for="nombre"><i class="bi bi-person-fill me-2"></i>Nombre</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="apellidoPaterno" id="apellidoPaterno" placeholder="Apellido Paterno" required>
                <label for="apellidoPaterno"><i class="bi bi-person-badge-fill me-2"></i>Apellido Paterno</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="apellidoMaterno" id="apellidoMaterno" placeholder="Apellido Materno" required>
                <label for="apellidoMaterno"><i class="bi bi-person-badge-fill me-2"></i>Apellido Materno</label>
            </div>

            <div class="form-floating mb-3">
                <input type="date" class="form-control" name="fechaNacimiento" id="fechaNacimiento" required>
                <label for="fechaNacimiento"><i class="bi bi-calendar-date me-2"></i>Fecha de Nacimiento</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="correo" id="correo" placeholder="correo@ejemplo.com" required>
                <label for="correo"><i class="bi bi-envelope-fill me-2"></i>Correo electrónico</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Contraseña" required>
                <label for="pass"><i class="bi bi-lock-fill me-2"></i>Contraseña</label>
            </div>

            <button type="submit" class="btn btn-register w-100 text-white mb-3">
                <i class="bi bi-person-plus-fill me-2"></i>Registrarse
            </button>

            <div class="text-center links">
                <p>¿Ya tienes una cuenta? <a href="../Html/Login.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
