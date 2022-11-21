<?php 
    require_once 'operacionesBD.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cod_act = "act".rand(1000, 99999);
        
        $_SESSION['registro']['codigo'] = $cod_act;
    
        if (comprobar_confirmar_ingreso($_POST['usuario'], $_POST['pass'], $_POST['correo'], $cod_act)) {
            $correo_enviado = true;
            $_SESSION['registro']['usuario'] = $_POST['usuario'];
            $_SESSION['registro']['pass'] = $_POST['pass'];
            $_SESSION['registro']['correo'] = $_POST['correo'];
        } else {
            $error = true;
            $_SESSION = array();
            session_destroy();	// eliminar la sesion
            setcookie(session_name(), 123, time() - 10000);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['confirmado']) && $_GET['confirmado'] == $_SESSION['registro']['codigo']) {
        if (ingresar_usuario($_SESSION['registro']['usuario'], $_SESSION['registro']['pass'], $_SESSION['registro']['correo'])) {
            $_SESSION = array();
            session_destroy();
            setcookie(session_name(), 123, time() - 10000);
            header("Location: login.php?registrado=true");
        } else {
            header("Location: login.php?registrado=false");
        }
    } else if (isset($_GET['confirmado'])) {
        $error_codigo = true;
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
        <?php 
            if (isset($error) && $error) {
                echo "<p style='color: red;'>Datos inválidos o ya en uso!</p>";
            }

            if (isset($correo_enviado) && $correo_enviado) {
                echo "<p style='color: yellow;'>Revisa el correo electrónico</p>";
            }

            if (isset($error_codigo)) {
                echo "<p style='color: red;'>Revisa el código!</p>";
            }
        ?>
        
    </section>
</body>
</html>