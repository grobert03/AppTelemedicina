<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD.php';
    comprobar_sesion();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        enviar_mensaje($_POST['remitente'], $_SESSION['usuario']['usuario'] ,$_POST['content']);
        echo "<h1>RESPUESTA ENVIADA!</h1>";
        header("Location: bandeja_salida.php");
    } else {
        if (isset($_GET['id']) && isset($_GET['modo'])) {
            if ($_GET['modo'] == 'entrada') {
                $mensaje = devolver_mensaje_entrada($_GET['id']);
                $entrada = true;
                if (!$mensaje) {
                    header('Location: bandeja_entrada.php');
                } 
            } else {
                $mensaje = devolver_mensaje_salida($_GET['id']);
                if (!$mensaje) {
                    header('Location: bandeja_entrada.php');
                } 
            }
        } else {
            header('Location: inicio.php');
        }
    }

    
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje</title>
</head>
<body>
    <?php
        $asunto = $mensaje['asunto'];
        $remitente = $mensaje['remitente'];
        $contenido = $mensaje['contenido'];


        echo "<div>"; 
        echo "<h3>Asunto: $asunto</h3><br>";
        echo "<p style='color: gray'>Desde: $remitente</p><br>";
        echo "<p>$contenido</p><br>";
        echo "</div>";

        if ($mensaje['leido'] == false) {
            actualizar_mensaje_leido($_GET['id']);
        }
        
        if (isset($entrada) && $entrada) {
            echo "<h2>Respuesta:</h2>";
            echo "<div>";
            echo "<h3>Re:$asunto</h3>";
            echo "<p>Para: $remitente</p>";
            echo "<form action='ver_mensaje.php' method='POST'>";
            echo "<input type='hidden' name='remitente' value='$remitente'>";
            echo "<textarea name='content' rows='4' columns='30'></textarea>";
            echo "<input type='submit' value='Responder'>";
            echo "</form>";
            echo "</div>";
        }
        
    
    ?>
</body>
</html>