<?php 
    require_once 'operacionesBD/operacionesCambiar.php';
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_SESSION['usu_cambio']['usuario']) || $_GET['activacion'] != $_SESSION['usu_cambio']['activacion']) {
            header("Location: login.php");

            session_destroy();
        } 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pass'] == $_POST['confirmar']) {
        cambiar_pass($_SESSION['usu_cambio']['usuario'], $_POST['pass']);
        header("Location: login.php");
        session_destroy();
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
        Contraseña nueva:<input type="password" name="pass">
        Confirmar contraseña:<input type="password" name="confirmar">
        <input type="submit">
    </form>

    <?php 
        if (isset($error) && $error) {
            echo "<h1>Revisa los datos!</h1>";
        }
    ?>
</body>
</html>