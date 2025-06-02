<div class="sidebar">
    <div class="sidebar-brand d-flex align-items-center">
        <i class="bi bi-shop me-2"></i>
        <a href="#" class="text-white text-decoration-none fs-3 fw-bold">PAPEL<span style="color: var(--secondary-color);">O</span></a>
    </div>
    <ul class="sidebar-nav">
        <li>
            <a href="dashboardAdmi.php?seccion=articulos" class="<?= $seccionActual == 'articulos' ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i> Gestión de Artículos
            </a>
        </li>
        <li>
            <a href="dashboardAdmi.php?seccion=personal" class="<?= $seccionActual == 'personal' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Gestión de Personal
            </a>
        </li>
        <li>
            <a href="dashboardAdmi.php?seccion=cobro" class="<?= $seccionActual == 'cobro' ? 'active' : '' ?>">
                <i class="bi bi-cash-coin"></i> Cobro
            </a>
        </li>
        
        <li>
            <a href="dashboardAdmi.php?seccion=compras" class="<?= $seccionActual == 'compras' ? 'active' : '' ?>">
                <i class="bi bi-cart-plus"></i> Compras
            </a>
        </li>
        <li>
            <a href="dashboardAdmi.php?seccion=reportes" class="<?= $seccionActual == 'reportes' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i> Reportes
            </a>
        </li>

         
        
        <li class="mt-4">
            <a href="../Controladores/cerrarSesion.php">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </li>
    </ul>
</div>