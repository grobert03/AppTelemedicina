<?php 
    require_once 'sesiones.php';	
    comprobar_sesion();
    $_SESSION = array();
    session_destroy();	// eliminar la sesion
    setcookie(session_name(), 123, time() - 1000);
    header("Location: login.php");
?>
