<style>
    .header {
        background-color: var(--primary-color);
        color: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

</style>

<header class="header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="#" class="text-white text-decoration-none fs-3 fw-bold">PAPEL<span style="color: var(--secondary-color);">O</span></a>
            <nav class="d-flex">
                    <a href="../Html/home.php" class="text-white mx-3 text-decoration-none"><i class="bi bi-house-door"></i> Inicio</a>
                    <a href="../Controladores/controlCatalogo.php" class="text-white mx-3 text-decoration-none"><i class="bi bi-shop"></i> Productos</a>
                    <a href="../Controladores/controlCarrito.php" class="text-white mx-3 text-decoration-none"><i class="bi bi-cart"></i> Carrito</a>
                    <a  title="Cerrar sesión" href="../Controladores/cerrarSesion.php" class="text-white mx-3 text-decoration-none"><i class="bi bi-box-arrow-right"></i></a>
            </nav>
        </div>
    </div>
</header>