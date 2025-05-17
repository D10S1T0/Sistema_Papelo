<?php
    session_start();
    session_unset(); 
    session_destroy();
    header("Location: ../Html/Login.php");//si hay otro bendito errores es esto, actualiza la ruta 
    exit();

?>