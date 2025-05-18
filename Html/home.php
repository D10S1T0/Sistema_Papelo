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
    <title>Inicio | Papelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Estilos/home.css">
</head>
<body>
    <?php include '../Includes/header.php'; ?>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a Papelo</h1>
            <p class="lead mb-5">Tu solución integral para materiales de oficina y papelería de alta calidad</p>
        </div>
    </section>

    <main class="container my-5">
    <section class="mb-5">
        <h2 class="text-center mb-5">Cómo funciona</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-cart-plus feature-icon"></i>
                    <h3>Selecciona tus productos</h3>
                    <p>Explora nuestro catálogo y agrega los artículos a tu carrito de compras.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-person-check feature-icon"></i>
                    <h3>2. Solo di tu nombre</h3>
                    <p>Al finalizar, tu pedido quedará asociado a tu nombre de usuario.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-cash feature-icon"></i>
                    <h3>3. Paga en tienda</h3>
                    <p>Acude a la papelería, menciona tu nombre y realiza el pago.</p>
                </div>
            </div>
        
        <section class="cta-section text-center">
            <div class="container">
                <h2 class="mb-4">¿Listo para comenzar?</h2>
                <p class="lead mb-4">Explora nuestro catálogo y descubre todo lo que tenemos para ofrecerte</p>
                <a href="../Controladores/controlCatalogo.php" class="btn btn-primary btn-lg px-4 me-2" style="background-color: #2c3e50; border-color: #2c3e50;">Ver prouctos</a>
            </div>
        </section>
    </main>
    
    <?php include '../Includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>