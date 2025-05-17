<?php
    function obtenerVentasTotales($conexion) {
        $sql = "SELECT SUM(p.precio * dv.cantidad) AS total 
                FROM DetallesVenta dv 
                JOIN Productos p ON dv.idProducto = p.idProducto";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    function obtenerTotalPedidos($conexion){
        $sql = "SELECT COUNT(*) AS total FROM Pedidos";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function obtenerTotalArticulosVendidos($conexion) {
        $sql = "SELECT SUM(cantidad) AS total FROM DetallesVenta";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function obtenerClientesNuevos($conexion) {
        $sql = "SELECT COUNT(*) AS total FROM Visitantes";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    //consulta compleja, no quiero volver a tocar esto en mi vida 3 joinssssssssssssssssssssssssssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    function obtenerPedidosRecientes($conexion, $limite = 5) {

        /*"SELECT p.idPedido, v.nombre, v.apellidoPaterno, p.fecha, p.estado,
                    SUM(dp.cantidad * pr.precio) AS total
                FROM Pedidos p
                JOIN Visitantes v ON p.idVisitante = v.idVisitante
                JOIN DetallePedido dp ON p.idPedido = dp.idPedido
                GROUP BY p.idPedido
                ORDER BY p.fecha DESC
                LIMIT :limite";*/
        $sql = "SELECT p.idPedido, v.nombre, v.apellidoPaterno, p.fecha, p.estado,
                    SUM(dp.cantidad * pr.precio) AS total
                FROM Pedidos p
                JOIN Visitantes v ON p.idVisitante = v.idVisitante
                JOIN DetallePedido dp ON p.idPedido = dp.idPedido
                JOIN Productos pr ON dp.idProducto = pr.idProducto
                GROUP BY p.idPedido
                ORDER BY p.fecha DESC
                LIMIT :limite";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerArticulosMasPedidos($conexion, $limite = 5) {
        $sql = "SELECT p.idProducto, p.nombreProducto, p.precio, p.imagen,
                    SUM(dp.cantidad) AS totalPedidos,
                    SUM(dp.cantidad * p.precio) AS totalGenerado
                FROM DetallePedido dp
                JOIN Productos p ON dp.idProducto = p.idProducto
                GROUP BY p.idProducto
                ORDER BY totalPedidos DESC
                LIMIT :limite";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();//die();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerArticulosMejorValorados($conexion, $limite = 3) {
        $sql = "SELECT p.idProducto, p.nombreProducto, p.imagen, 
                    AVG(c.calificacion) AS promedio, COUNT(c.idCalificacion) AS totalReseñas
                FROM Calificaciones c
                JOIN Productos p ON c.idProducto = p.idProducto
                GROUP BY c.idProducto
                ORDER BY promedio DESC
                LIMIT :limite";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function obtenerCategoriasPopulares($conexion) {
        $sql = "SELECT c.nombreCategoria, COUNT(p.idProducto) AS total
                FROM Categorias c
                LEFT JOIN Productos p ON c.idCategoria = p.idCategoria
                GROUP BY c.idCategoria
                ORDER BY total DESC";
            //die();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalProductos = array_sum(array_column($categorias, 'total'));
        foreach ($categorias as &$cat) {
            $cat['porcentaje'] = $totalProductos > 0 ? round(($cat['total'] / $totalProductos) * 100) : 0;
        }
        return $categorias;
    }

?>