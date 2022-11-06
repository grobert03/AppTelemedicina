<?php 
    require_once 'sesiones.php';
    comprobar_sesion();
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
    <?php 
        if (isset($_SESSION['usuario'])) {
            echo "Bienvenido ".$_SESSION['usuario']['usuario'];
        } else {
            echo "<h1>FUERA!</h1>";
        }
    ?>
    <a href="logout.php">Salir</a>
</body>
</html>