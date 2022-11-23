<?php 
    require_once 'operacionesBD/operacionesReceta.php';
    if (isset($_POST['codigo'])) {
        $receta = ver_receta($_POST['codigo']);
        if (!$receta) {
            echo "<h1>No hemos encontrado su receta!</h1>";
        } 
    } else {
        header("Location: login.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta</title>
    <link rel="stylesheet" href="estilos/receta.css">
</head>
<body>
    <header>
        <div id="left">
            <img src="./imgs/stethoscope.png" alt="stethoscope">
            <h2>MediMadrid</h2>
        </div>
    </header>
    <?php if (isset($receta['medicamento'])) {
        $cod = $_POST['codigo'];
       echo  "<h1>Receta: $cod </h1>";
    } ?>
    <div id='boton'><button><a href='login.php'>Inicio</a></button></div>
    <div id="contenedor">
    <?php 
        if (isset($receta['medicamento'])) {
            $medicamento = $receta['medicamento'];
            $paciente = $receta['paciente'];
            $medico = $receta['medico'];
            $activo = $receta['principio_activo'];
            $dosis = $receta['dosis'];
            echo "<div id='receta'>";
            echo "<h2>Medicamento: $medicamento</h2>";
            echo "<p>Para: $paciente</p>";
            echo "<p>Medico: $medico</p>";
            echo "<p>Principio activo: $activo</p>";
            echo "<p>Dosis: $dosis</p>";
            echo "</div>";
        }
    ?>
    </div>
</body>
</html>