<?php
    $servidor = "localhost"; 
    $usuario = "root";    
    $contrasena = "";         
    $basedatos = "gestionpapeleria";  


    try {
        //conexion usando PDO con parametros
        $conexion = new PDO("mysql:host=$servidor;dbname=$basedatos;charset=utf8", $usuario, $contrasena);
        
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Lol"; //esto podriamos descomentar pa probar nomas
    } catch (PDOException $e) {
        echo "Error de conexion: " . $e->getMessage();
        exit;  //sirve para deteneer este script 
    }
?>                                                          