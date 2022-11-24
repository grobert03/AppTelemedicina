<?php 
    require_once 'operacionesBD/operacionesCambiar.php';
    require_once 'operacionesBD/operacionesCorreo.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (comprobar_usuario_modificar($_POST['usuario'], $_POST['correo'])) {
            $cod_act = "act".rand(1000, 99999);
            $_SESSION['usu_cambio']['usuario'] = $_POST['usuario'];
            $_SESSION['usu_cambio']['correo'] = $_POST['correo'];
            $_SESSION['usu_cambio']['activacion'] = $cod_act;
            $carpeta = $_SERVER['PHP_SELF'] ;
            $contenido = "Para confirmar, acceda al enlace: <a href='http:localhost$carpeta?activacion=$cod_act'>Activar</a>";
            if (enviar_correo_verificacion('Cambio de contraseña', $contenido, $_POST['correo'])) {
                $error = false;
            } else {
                $error = true;
            }

        } else {
            session_destroy();
            $error = true;
        }
    } else {
        if (isset($_SESSION['usu_cambio']['activacion']) && isset($_GET['activacion']) && $_GET['activacion'] == $_SESSION['usu_cambio']['activacion']) {
            $cod_act = $_SESSION['usu_cambio']['activacion'];
            
            header("Location: cambio.php?activacion=$cod_act");
        } else if (isset($_SESSION['usu_cambio'])) {
            echo "<h1 style='color: red'>No has confirmado la operacion por correo! Intenta de nuevo</h1>";
            session_destroy();
        }
    }


     
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar pass</title>
    <link rel="stylesheet" href="estilos/cambiar_pass.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2>MediMadrid</h2>
        </div>
    </header>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
        <div>
            <label for="usuario">Usuario:</label>
            <input id="usuario" type="text" name="usuario">
        </div>
        <div>
            <label for="correo">Correo:</label>
            <input id="correo" type="text" name="correo">
        </div>
        <input type="submit">

        <?php 
            if (isset($error) && $error) {
                echo "<p style='color: red'>No existe ningún usuario con los datos proporcionados</p>";
            } else if (isset($error) && !$error) {
                echo "<p style='color: yellow'>Revisa el correo para la confirmación</p>";
            }
        ?>
    </form>
</body>
</html>