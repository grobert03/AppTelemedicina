<?php 
    require_once 'sesiones.php';	
    comprobar_sesion();
    $_SESSION = array();
    session_destroy();	// eliminar la sesion
    setcookie(session_name(), 123, time() - 1000);
    echo "<h1>SESION BORRADA</h1>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="login.php">Login</a>
</body>
</html>