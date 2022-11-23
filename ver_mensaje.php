<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD/operacionesMensaje.php';
    comprobar_sesion();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST['content'] == '') {
            $id = $_SESSION['ver_mensaje']['id'];
            $modo = $_SESSION['ver_mensaje']['modo'];
            unset($_SESSION['ver_mensaje']);
            header("Location: ver_mensaje.php?id=$id&modo=$modo&error_envio=true");
        } else {
            responder($_POST['remitente'], $_SESSION['usuario']['usuario'], $_POST['asunto'], $_POST['content']);
            header("Location: bandeja_salida.php");
        }
    } else {
        if (isset($_GET['id']) && isset($_GET['modo'])) {
            if ($_GET['modo'] == 'entrada') {
                $mensaje = devolver_mensaje_entrada($_GET['id']);
                $entrada = true;
                if (!$mensaje) {
                    header('Location: bandeja_entrada.php');
                } 
                $_SESSION['ver_mensaje']['id'] = $_GET['id'];
                $_SESSION['ver_mensaje']['modo'] = $_GET['modo'];
                
            } else {
                $mensaje = devolver_mensaje_salida($_GET['id']);
                if (isset($_GET['destinatarios']) && $_GET['destinatarios'] == 'varios' && $mensaje['remitente'] == $_SESSION['usuario']['usuario']) {
                    $array = devolver_destinatarios($mensaje['hora_envio']);
                    $destinatarios = "";
                    for ($i = 0; $i < sizeof($array); $i++) {
                        $destinatarios = $destinatarios.$array[$i]['destinatario'].", ";
                    }

                } 
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
    <link rel='stylesheet' href='estilos/ver_mensaje.css'>
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
    <div id='contenido'>
    <?php
        $asunto = $mensaje['asunto'];
        $remitente = $mensaje['remitente'];
        $contenido = $mensaje['contenido'];


        echo "<div id='mensaje'>"; 
        echo "<h3>Asunto: $asunto</h3><br>";
        echo "<p style='color: gray'>Desde: $remitente</p><br>";
        if (isset($destinatarios)) {
            echo "<p>Para: $destinatarios</p><br>";
        }
        echo "<p class='textarea' style='white-space: pre-line'>$contenido</p><br>";
        echo "</div>";

        if ($mensaje['leido'] == false && $_SESSION['usuario']['usuario'] != $remitente) {
            actualizar_mensaje_leido($_GET['id']);
        }
        
        if (isset($entrada) && $entrada) {
            echo "<div id='respuesta'>";
            echo "<h2>Respuesta:</h2>";
            echo "<div>";
            echo "<h3>Re:$asunto</h3>";
            echo "<p>Para: $remitente</p>";
            echo "<form action='ver_mensaje.php' method='POST'>";
            echo "<input type='hidden' name='remitente' value='$remitente'>";
            echo "<input type='hidden' name='asunto' value='Re:$asunto'>";
            echo "<textarea name='content' rows='5' columns='60'></textarea>";
            echo "<input type='submit' value='Responder'>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }

        if (isset($_GET['error_envio'])) {
            echo "<p style='color:red'>NO PUEDE ENVIAR UN MENSAJE VACIO</p>";
        }
        
    
    ?>
    </div>
</body>
</html>