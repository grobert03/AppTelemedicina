<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD.php';
    comprobar_sesion();


    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $image = $_FILES['foto']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 
        
        $fila = guardar_imagen($imgContent);
        
    }

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
            <img style='heigth: 100px; width: 100px;' src="data:image/jpg;charset=utf8;base64,<?php $fila = devolver_foto(); echo base64_encode($fila['foto']); ?>" /> 
            <form action="perfil.php" method="POST"  enctype="multipart/form-data">
                <input type="hidden" name='cambiar_foto' value='si'>
                <input type="file" name="foto" accept="image/png, image/jpeg">
                <input type="submit" value='cambiar_foto'>
            </form>
       
    
        </div>
    </div>
</body>
</html>