<?php
function agregarCategoria($conexion, $nombreCategoria) {
    $sql = "INSERT INTO Categorias (nombreCategoria) VALUES (:nombre)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombreCategoria);
    $stmt->execute();
}

function obtenerTodasLasCategorias($conexion) {
    $sql = "SELECT * FROM Categorias";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
