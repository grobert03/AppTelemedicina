<?php 
    require_once 'operacionesBD.php';
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        ingresar_usuario($_POST['usuario'], $_POST['pass'], $_POST['correo']);
    }

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
    <form action="registro.php" method="POST">
        Usuario:<input type="text" name="usuario">
        Contraseña:<input type="password" name="pass">
        Correo:<input type="text" name="correo">
        <input type="submit">
    </form>
</body>
</html>