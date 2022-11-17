<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD.php';
    comprobar_sesion();
    $medicos = devolver_medicos();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!enviar_mensaje($_POST['destinatarios'], $_POST['asunto'], $_POST['mensaje'])) {
            $envio = false;
        } else {
            $envio = true;
        }
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
                <p>Destinatario(s):</p>
                <?php 
                    for ($i = 0; $i < sizeof($medicos); $i++) {
                        $destinatario = $medicos[$i][0];
                        
                        if (comprobar_carga($destinatario)) {
                            echo "<input type='checkbox' id='$destinatario' name='destinatarios[]' value='$destinatario'>";
                            echo "<label for='$destinatario'>$destinatario</label>";
                        } else {
                            echo "<span style='color: red'> $destinatario (no disponible)</span>";
                        }
                    }
                    
                ?>
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
        <?php 

            if (isset($envio) && !$envio) {
                echo "<h3 style='color: red'>FALLO EN EL ENVIO</h3>";
            } else if (isset($envio) && $envio) {
                echo "<h3 style='color: green'>MENSAJE ENVIADO!</h3>";
            }
        ?>
    </div>
</body>
</html>