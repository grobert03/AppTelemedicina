<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD.php';
    comprobar_sesion();
    
    $mis_mensajes = mostrar_mensajes_recibidos($_SESSION['usuario']['usuario']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de entrada</title>
    <link rel="stylesheet" href="estilos/bandeja.css">
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
    <h1>Bandeja de entrada de: <?php echo $_SESSION['usuario']['usuario'] ?></h1>
    <div id="contenedor">
        <div id="contenido">
        <?php 
            if ($mis_mensajes == false) {
                echo "<h1>No has recibido ningun mensaje!</h1>";
            } else {
                for ($i = 0; $i < sizeof($mis_mensajes); $i++) {
                    $remitente = $mis_mensajes[$i]['remitente'];
                    $asunto = $mis_mensajes[$i]['asunto'];
                    $contenido = $mis_mensajes[$i]['contenido'];
                    $id = $mis_mensajes[$i]['id_mensaje'];
                    $status = $mis_mensajes[$i]['leido'];
                    $fecha = $mis_mensajes[$i]['fecha_envio'];
                    $hora = $mis_mensajes[$i]['hora_envio'];
                    $contenido = substr($contenido, 0, 7);

                    echo "<div class='mensaje'>";
                    if ($status) {
                        echo "<p style='color: lime'>Leido</p>";
                    } else {
                        echo "<p style='color: crimson'>No leido</p>";
                    }
                    echo "<h3>Asunto: $asunto</h3>";
                    echo "<p>Fecha: $fecha</p>";
                    echo "<p>Hora: $hora</p>";
                    echo "<p style='color: gray'>De: $remitente</p>";
                    echo "<p>$contenido...</p>";
                    echo "<a href='ver_mensaje.php?id=$id&modo=entrada'>Ver mensaje completo</a>";
                    echo "</div>";
                }
            }
        
        ?>
        </div>    
    </div>
</body>
</html>