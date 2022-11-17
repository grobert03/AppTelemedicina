<?php 
    require_once "operacionesBD.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Comprobar de que las credenciales son correctas
        $usu = comprobar_credenciales($_POST['usuario'], $_POST['pass']);
        if ($usu===false) {
            $err = true;
            $usuario = $_POST['usuario'];
        }else {
            session_start();
            $_SESSION['usuario'] = $usu;
            header("Location: inicio.php");
            return;
        }	
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <link rel="stylesheet" href="./estilos/login.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2>MediMadrid</h2>
        </div>
    </header>
    <h1>Bienvenido a MediMadrid!</h1>
    <?php 
        if (isset($_GET['registrado']) && $_GET['registrado'] == 'true') {
            echo "<h3 style='color: yellow;'>Registro completo!</h3>";
        } else if (isset($_GET['registrado']) && $_GET['registrado'] == 'false') {
            echo "<h3 style='color: yellow;'>Fallo en el registro!</h3>";
        }
    ?>
    <section>
        <div id="formulario">
            <h2>Acceder a la cuenta:</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                <div>
                    <label for="usu">Usuario:</label>
                    <input id="usu" type="text" name="usuario" value="<?php if (isset($err) && $err === true) {echo $_POST['usuario'];}?>">
                </div>
                <div>
                    <label for="pass">Contraseña:</label>
                    <input id="pass" type="password" name="pass">
                </div>
                <input type="submit" value="Entrar">
            </form>
            <?php if (isset($err) && $err === true) {echo '<p style="color: red;">Revise usuario y contraseña!!!</p>';}?>
            <div id="registro">
                <p>¿No tiene una cuenta?</p>
                <a href="registro.php">Pulse aquí para registrarse</a>
            </div>
            <div id="nuevo-pass">
                <p>¿Ha olvidado su contraseña?</p>
                <a href="cambiar_pass.php"><p>Cambia tu contraseña<p></a>
            </div>
        </div>

        <div id="buscar-receta">
            
            <h2>Acceder a su receta:</h2>
            <p>Puede acceder a su receta sin cuenta introduciendo el identificador de la misma:</p>
           
            <form action="receta.php" method="POST">
                <input type="text" name="codigo">
                <input type="submit" value="Buscar">
            </form>
        </div>
    </section>

</body> 
</html>