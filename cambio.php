<?php 
    require_once 'operacionesBD.php';
    session_start();
    var_dump($_SESSION);
    if (!isset($_SESSION['usu_cambio']['usuario']) || $_GET['activacion'] != $_SESSION['usu_cambio']['activacion']) {
        // header("Location: login.php");
        echo "OH NOOO";
    } 

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pass'] == $_POST['confirmar']) {
        cambiar_pass($_SESSION['usu_cambio']['usuario'], $_POST['pass']);
        echo "<h1>Contraseña cambiada!";
        session_destroy();
    } else {
        $error = true;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de cambio</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
        <input type="password" name="pass">
        <input type="password" name="confirmar">
        <input type="submit">
    </form>

    <?php 
        if (isset($error) && $error) {
            echo "<h1>Revisa los datos!</h1>";
        }
    ?>
</body>
</html>