<?php 
    require_once "operacionesBD.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <link rel="stylesheet" href="./estilos/login.css">
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
        <div>
            <label for="usu">Usuario:</label>
            <input id="usu" type="text" name="usuario">
        </div>
        <div>
            <label for="pass">Contraseña:</label>
            <input id="pass" type="text" name="pass">
        </div>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>