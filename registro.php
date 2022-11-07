<?php 
    require_once 'operacionesBD.php';
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cod_act = "act".rand(1000, 99999);
        setcookie("cod_act", $cod_act, time() + 3600);
        if (comprobar_confirmar_ingreso($_POST['usuario'], $_POST['pass'], $_POST['correo'], $cod_act)) {
            $correo_enviado = true;
            setcookie("tempUsu", $_POST['usuario'], time() + 3600);
            setcookie("tempPass", $_POST['pass'], time() + 3600);
            setcookie("tempCorreo", $_POST['correo'], time() + 3600);
        } else {
            $error = true;
            setcookie("cod_act", "", time() - 3600);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['confirmado']) && isset($_COOKIE["cod_act"]) && $_GET['confirmado'] == $_COOKIE["cod_act"]) {
        if (ingresar_usuario($_COOKIE["tempUsu"], $_COOKIE["tempPass"], $_COOKIE["tempCorreo"])) {
            setcookie("tempUsu", "", time() - 3600);
            setcookie("tempPass", "", time() - 3600);
            setcookie("tempCorreo", "", time() - 3600);
            setcookie("cod_act", "", time() - 3600);
            header("Location: login.php?registrado=true");
        } else {
            setcookie("tempUsu", "", time() - 3600);
            setcookie("tempPass", "", time() - 3600);
            setcookie("tempCorreo", "", time() - 3600);
            setcookie("cod_act", "", time() - 3600);
            header("Location: login.php?registrado=false");
        }
    } else {
        header("Location: login.php?registrado=false");
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
        ?>
        
    </section>
</body>
</html>