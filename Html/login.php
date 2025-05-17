<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Estilos/login.css">
</head>
    <body>    
        <div class="container">
            <div class="login-container">
                <div class="login-logo">
                    <h2 class="mb-3" style="color: #2c3e50;">
                        <i class="bi bi-shop"></i> Papelo
                    </h2>
                    <p class="text-muted">Ingresa a tu cuenta</p>
                </div>
                <form action="../Controladores/controlLogin.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="nombre@ejemplo.com" required>
                        <label for="email"><i class="bi bi-envelope me-2"></i>Correo electrónico</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Contraseña</label>
                    </div>
                    <button type="submit" class="btn btn-login btn-block w-100 text-white mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                    </button>
                    
                    <div class="text-center links">
                        <p>¿No tienes una cuenta? <a href="../Html/registrar.php">Regístrate aquí</a></p>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <?php include '../Includes/footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>