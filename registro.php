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
    <title>Registro</title>
    <link rel="stylesheet" href="./estilos/registro.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2>MediMadrid</h2>
        </div>
    </header>
    <section>
        <h1>Crear una cuenta:</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
            <div>
                <label for="usuario">Usuario:</label>
                <input id="usuario" type="text" name="usuario">
            </div>
            <div>
                <label for="pass">Contraseña:</label>
                <input id="pass" type="password" name="pass">
            </div>
            <div>
                <label for="correo">Correo:</label>
                <input id="correo" type="text" name="correo">
            </div>
            <input type="submit">
        </form>

        <p>La creación de la cuenta se llevará a cabo después de confirmar la operación mediante correo electrónico!</p>
    </section>
</body>
</html>