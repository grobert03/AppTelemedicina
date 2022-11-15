<?php 
    require_once 'operacionesBD.php';
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
</head>
<body>
    <?php 
        if (isset($receta['medicamento'])) {
            $medicamento = $receta['medicamento'];
            $paciente = $receta['paciente'];
            $medico = $receta['medico'];
            $activo = $receta['principio_activo'];
            $dosis = $receta['dosis'];
            echo "<div>";
            echo "<h2>Medicamento:</h2>";
            echo "<p>Para: $paciente</p>";
            echo "<p>Medico: $medico</p>";
            echo "<p>Principio activo: $activo</p>";
            echo "<p>Dosis: $dosis</p>";
            echo "</div>";
        }
    ?>
</body>
</html>