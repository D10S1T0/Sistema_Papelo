<?php
session_start();
require_once '../Includes/conexion.php';
require_once '../Modelos/PedidoModelo.php';

if (!isset($_SESSION['idVisitante'])) {
    echo "Debes iniciar sesión para agregar productso";
    exit;
}

$idVisitante = $_SESSION['idVisitante'];
$idProducto = $_POST['idProducto'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;

//val cantidad
if (!$idProducto || $cantidad < 1 || $cantidad > 5) {
    echo "Cantidad inválida.";
    exit;
}

//dios ayudame...
$sql = "SELECT SUM(dp.cantidad) AS total_pedida
        FROM detallepedido dp
        INNER JOIN pedidos p ON dp.idPedido = p.idPedido
        WHERE p.idVisitante = ? AND dp.idProducto = ? AND p.estado = 'pendiente'";
$stmt = $conexion->prepare($sql);
$stmt->execute([$idVisitante, $idProducto]);
$totalExistente = (int) $stmt->fetchColumn();

if (($totalExistente + $cantidad) > 5) {
    header('Location: ../Controladores/controlCatalogo.php?mensaje=producto_no_agregado');
    exit;
}

//vhay stock disponible
$sqlStock = "SELECT stock FROM productos WHERE idProducto = ?";
$stmt = $conexion->prepare($sqlStock);
$stmt->execute([$idProducto]);
$stock = $stmt->fetchColumn();

if ($stock === false || $stock < $cantidad) {
    echo "No hay suficiente stock disponible.";
    exit;
}

//pedido pendiente
$sql = "SELECT idPedido FROM pedidos WHERE idVisitante = ? AND estado = 'pendiente'";
$stmt = $conexion->prepare($sql);
$stmt->execute([$idVisitante]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    $idPedido = $pedido['idPedido'];
} else {
    //si no hay pedido, le creo uno nuevo
    $sql = "INSERT INTO pedidos (idVisitante, fecha, estado) VALUES (?, NOW(), 'pendiente')";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idVisitante]);
    $idPedido = $conexion->lastInsertId();
}

//agrego el producto al pedido
$sql = "INSERT INTO detallepedido (idPedido, idProducto, cantidad) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->execute([$idPedido, $idProducto, $cantidad]);


$sql = "UPDATE productos SET stock = stock - ? WHERE idProducto = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$cantidad, $idProducto]);

header('Location: ../Controladores/controlCatalogo.php?mensaje=producto_agregado');//eje de como agregar mensahjes
?>