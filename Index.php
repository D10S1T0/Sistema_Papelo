<?php
    header("Location: ../Sistema Papelo/Html/login.php");
    exit;
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            a{
                font-size: 30px;
            }
        </style>
    </head>
    <body>
        <!--desde aca provaba el sistema, ahora no se como cambiar de index,
         si lo vuelvo .php tendre que cambiar todos los archivos ;C, así se quedaaaaaaaaa-->
        <br>
        <br>
        <br>
        <br>
        <center>
            <h1>Acceso rapido a cada pagina del sistema (god mode)</h1>
            <br>
            <br>
            <a href="../Sistema Papelo/Html/login.php">Login</a>
            <br>
            <br>
            <a href="../Sistema Papelo/Html/registrar.php">Registrar visitante</a>
            <br>
            <br>
            <a href="../Sistema Papelo/Controladores/controlCatalogo.php">Catalogo</a>
            <br>
            <br>
            <a href="../Sistema Papelo/Controladores/controlDetalleProducto?id=1.php">Detalle de un producto</a>
            <br>
            <br>
            <a href="../Sistema Papelo/Controladores/controlCarrito.php">Carrito</a>
            <br>
            <br>
            <a href="../Sistema Papelo/Html/dashboardAdmi.php">Dashboar del administrador</a>
        </center>
        
    </body>
</html>