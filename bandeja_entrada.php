<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD.php';
    comprobar_sesion();
    
    $mis_mensajes = mostrar_mensajes($_SESSION['usuario']['usuario']);

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
        if ($mis_mensajes == false) {
            echo "<h1>No has enviado ningun mensaje!</h1>";
        } else {
            for ($i = 0; $i < sizeof($mis_mensajes); $i++) {
                $remitente = $mis_mensajes[$i]['remitente'];
                $asunto = $mis_mensajes[$i]['asunto'];
                $contenido = $mis_mensajes[$i]['contenido'];
                $id = $mis_mensajes[$i]['id_mensaje'];
                $status = $mis_mensajes[$i]['leido'];
                $contenido = substr($contenido, 0, 7);

                echo "<div class='mensaje'>";
                if ($status) {
                    echo "<p style='color: lime'>Leido</p>";
                } else {
                    echo "<p style='color: crimson'>No leido</p>";
                }
                echo "<h3>Asunto: $asunto</h3><br>";
                echo "<p style='color: gray'>Desde: $remitente</p><br>";
                echo "<p>$contenido...</p><br>";
                echo "<a href='ver_mensaje?id=$id'>Ver mensaje completo</a>";
                echo "</div>";
            }
        }
    
    ?>
</body>
</html>