<?php
function buscarEmpleadoPorCorreo($correo, $conexion) {
    $sql = "SELECT e.*, p.nombrePuesto FROM Empleados e 
            JOIN Puestos p ON e.idPuesto = p.idPuesto 
            WHERE e.correo = :correo LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function buscarVisitantePorCorreo($correo, $conexion) {
    $sql = "SELECT * FROM Visitantes WHERE correo = :correo LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registrarVisitante($conexion, $nombre, $apPaterno, $apMaterno, $fechaNacimiento, $correo, $pass) {
        $sql = "INSERT INTO visitantes (nombre, apellidoPaterno, apellidoMaterno, fechaNacimiento, correo, pass)
                VALUES (:nombre, :apPaterno, :apMaterno, :fechaNacimiento, :correo, :pass)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apPaterno', $apPaterno);
        $stmt->bindParam(':apMaterno', $apMaterno);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':pass', $pass);
        
        return $stmt->execute(); //true
}

function agregarNuevoEmpleado($conexion, $datos) {
        $sql = "INSERT INTO Empleados (nombreEmpleado, apellidoPaterno, apellidoMaterno, fechaNacimiento, fechaContratacion, correo, telefono, pass, idPuesto)
                VALUES (:nombre, :apPaterno, :apMaterno, :nacimiento, :contratacion, :correo, :telefono, :pass, :idPuesto)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre'      => $datos['nombreEmpleado'],
            ':apPaterno'   => $datos['apellidoPaterno'],
            ':apMaterno'   => $datos['apellidoMaterno'],
            ':nacimiento'  => $datos['fechaNacimiento'],
            ':contratacion'=> $datos['fechaContratacion'],
            ':correo'      => $datos['correo'],
            ':telefono'    => $datos['telefono'],
            ':pass'        => $datos['pass'],
            ':idPuesto'    => $datos['idPuesto']
        ]);
    }
    

    function obtenerEmpleados($conexion) {
        $sql = "SELECT e.idEmpleado, e.nombreEmpleado, e.apellidoPaterno, e.apellidoMaterno, e.correo, e.telefono, 
                       e.fechaContratacion, e.fechaNacimiento, e.pass, e.idPuesto, p.nombrePuesto 
                FROM Empleados e 
                JOIN Puestos p ON e.idPuesto = p.idPuesto
                ORDER BY e.idEmpleado ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

function agregarNuevoPuesto($conexion, $nombrePuesto, $salario) {
        $sql = "INSERT INTO Puestos (nombrePuesto, salario) VALUES (:nombre, :salario)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombrePuesto, PDO::PARAM_STR);
        $stmt->bindParam(':salario', $salario, PDO::PARAM_STR);
        $stmt->execute();
}
    
function obtenerPuestos($conexion) {
        $sql = "SELECT idPuesto, nombrePuesto FROM Puestos";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function editarEmpleado($conexion, $datos) {
        $sql = "UPDATE Empleados SET 
                    nombreEmpleado = :nombre,
                    apellidoPaterno = :apellidoPaterno,
                    apellidoMaterno = :apellidoMaterno,
                    fechaNacimiento = :fechaNacimiento,
                    fechaContratacion = :fechaContratacion,
                    correo = :correo,
                    telefono = :telefono,
                    pass = :pass,
                    idPuesto = :idPuesto
                WHERE idEmpleado = :idEmpleado";
    
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombreEmpleado'],
            ':apellidoPaterno' => $datos['apellidoPaterno'],
            ':apellidoMaterno' => $datos['apellidoMaterno'],
            ':fechaNacimiento' => $datos['fechaNacimiento'],
            ':fechaContratacion' => $datos['fechaContratacion'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono'],
            ':pass' => $datos['pass'],
            ':idPuesto' => $datos['idPuesto'],
            ':idEmpleado' => $datos['idEmpleado']
        ]);
}

function eliminarEmpleado($conexion, $idEmpleado) {
        $sql = "DELETE FROM Empleados WHERE idEmpleado = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idEmpleado, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    
    
?> 
