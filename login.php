<?php 
    require_once "operacionesBD.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usu = comprobar_usuario($_POST['usuario'], $_POST['pass']);
        if($usu===false){
            $err = true;
            $usuario = $_POST['usuario'];
        }else{
            session_start();
            // $usu tiene campos correo y codRes, correo 
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

    </header>
    <section>
        <?php if(isset($_GET["redirigido"])){
			echo "<p>Haga login para continuar</p>";
		}?>
		<?php if(isset($err) and $err == true){
			echo "<p> Revise usuario y contraseña</p>";
		}?>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
        <div>
            <label for="usu">Usuario:</label>
            <input id="usu" type="text" name="usuario">
        </div>
        <div>
            <label for="pass">Contraseña:</label>
            <input id="pass" type="password" name="pass">
        </div>
        <input type="submit" value="Entrar">
    </form>
    <div id="registro">
        <p>¿No tiene una cuenta?</p>
        <a href="registro.php">Pulse aquí para registrarse</a>
    </div>
    </section>
</body>
</html>