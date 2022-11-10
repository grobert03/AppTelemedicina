<?php 
    require_once 'sesiones.php';
    comprobar_sesion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="estilos/perfil.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2><a href="inicio.php">MediMadrid</a></h2>
        </div>
        <div id="right">
            <a href="logout.php"><img src="imgs/logout.png"></a>
        </div>
    </header>
    <div id="contenido">
        <div id="perfil">
            <form action="" method="POST">
                <?php echo "<" ?>
                <input type="file" name="foto" accept="image/png, image/jpeg">
                <input type="submit">
            </form>
       
    
        </div>
    </div>
</body>
</html>