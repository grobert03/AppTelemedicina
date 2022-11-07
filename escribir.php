<?php 
    require_once 'sesiones.php';
    require_once 'operacionesCorreo.php';
    comprobar_sesion();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        enviar_correo($_POST['destinatarios'], $_POST['asunto'], $_POST['mensaje']);
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escribir</title>
    <link rel="stylesheet" href="estilos/escribir.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2><a href="inicio.php">MediMadrid</a></h2>
        </div>
        <div id="right">
            <a href="perfil.php"><img src="imgs/user.png"></a>
            <a href="logout.php"><img src="imgs/logout.png"></a>
        </div>
    </header>

    <div id="content">
        <form action="escribir.php" method="POST">
            <div>
                <label for="destino">Destinatario(s):</label>
                <input id="destino" type="text" name="destinatarios" placeholder="separados mediante ,">
            </div>
            <div>
                <label for="asunto">Asunto:</label>
                <input id="asunto" type="text" name="asunto">
            </div>
            <div>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" rows="10" cols="60"></textarea>
            </div>
            <input type="submit">
        </form>
    </div>
</body>
</html>