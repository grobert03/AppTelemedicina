<?php 
    require_once 'sesiones.php';
    comprobar_sesion();
    if (str_contains($_SESSION['usuario']['correo'], '@comem.es')) {
        $_SESSION['usuario']['tipo'] = 'medico';
    } else {
        $_SESSION['usuario']['tipo'] = 'paciente';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="estilos/inicio.css">
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
    <h1>Bienvenido <?php echo $_SESSION['usuario']['usuario'] ?>!</h1>
    <div id="content">
        <?php 
            if ($_SESSION['usuario']['tipo'] == 'paciente') {
                echo "<button style='background-color: black;'><a href='escribir.php'>Escribir mensaje</a></button>";
            }
        ?>
        <button><a href="bandeja_entrada.php">Bandeja de entrada</a></button>
        <button><a href="bandeja_salida.php">Bandeja de salida</a></button>
        <?php 
            if ($_SESSION['usuario']['tipo'] == 'medico') {
                echo '<button style="background-color: green"><a href="consultar_recetas.php">Recetas</a></button>';
            }
            if ($_SESSION['usuario']['rol'] == 1) {
                echo "<button style='background-color: red;'><a href='admin.php'>Zona admin</a></button>";
            }
        ?>
    </div>
</body>
</html>