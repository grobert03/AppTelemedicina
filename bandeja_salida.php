<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD/operacionesBandeja.php';
    comprobar_sesion();
    
    $mis_mensajes = mostrar_mensajes_enviados($_SESSION['usuario']['usuario']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de salida</title>
    <link rel="stylesheet" href="estilos/bandeja.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2><a href="inicio.php">MediMadrid</a></h2>
        </div>
        <div id="right">
            <a href="logout.php"><img src="imgs/logout.png"></a>
        </div>
    </header>
    <h1>Bandeja de salida de: <?php echo $_SESSION['usuario']['usuario'] ?></h1>
    <div id="contenedor-boton"><a href="inicio.php"><button>Inicio</button></a></div>
    <div id="contenedor">
        <div id="contenido">
        <?php 
            $mensajes_duplicados = [];
            if ($mis_mensajes == false) {
                echo "<h1 style='color: red;'>No has enviado ningun mensaje!</h1>";
            } else {
                for ($i = 0; $i < sizeof($mis_mensajes); $i++) {
                    if (!in_array($mis_mensajes[$i]['hora_envio'], $mensajes_duplicados)) {
                        if (verificar_varios_destinatarios($mis_mensajes[$i]['hora_envio'])) {
                            array_push($mensajes_duplicados, $mis_mensajes[$i]['hora_envio']);
                            $destinatario = 'varios';
                        } else {
                            $destinatario = $mis_mensajes[$i]['destinatario'];
                        }
    
                        $asunto = $mis_mensajes[$i]['asunto'];
                        $contenido = $mis_mensajes[$i]['contenido'];
                        $id = $mis_mensajes[$i]['id_mensaje'];
                        $fecha = $mis_mensajes[$i]['fecha_envio'];
                        $hora = $mis_mensajes[$i]['hora_envio'];
                        $contenido = substr($contenido, 0, 10);
    
                        echo "<div class='mensaje'>";
                        echo "<h3>Asunto: $asunto</h3>";
                        echo "<p>Fecha: $fecha</p>";
                        echo "<p>Hora: $hora</p>";
                        echo "<p style='color: gray'>Para: $destinatario</p>";
                        echo "<p>$contenido...</p>";
                        if ($destinatario == 'varios') {
                            echo "<a href='ver_mensaje.php?id=$id&modo=salida&destinatarios=varios'>Ver mensaje completo</a>";
                        } else {
                            echo "<a href='ver_mensaje.php?id=$id&modo=salida'>Ver mensaje completo</a>";
                        }
                        echo "</div>";
                    }
                }
            }
        
        ?>
        </div>    
    </div>
</body>
</html>