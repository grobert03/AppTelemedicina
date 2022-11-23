<?php 
    require_once 'sesiones.php';
    require_once 'operacionesBD/operacionesReceta.php';
    comprobar_sesion();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['crear'])) {
            if (comprobar_es_paciente($_POST['paciente'])) {
                crear_receta($_POST['medicamento'], $_POST['paciente'], $_POST['principio_activo'], $_POST['dosis']);
                $receta_creada = true;
            } else {
                $error_paciente = true;
            }
        } else {
            borrar_receta($_POST['borrar']);
            
        }
    }
    $mis_recetas = ver_mis_recetas($_SESSION['usuario']['usuario']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis recetas</title>
    <link rel="stylesheet" href="estilos/consultar_recetas.css">
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
    <h1>Recetas de: <?php echo $_SESSION['usuario']['usuario'] ?></h1>

    <div id="contenedor">
        <div class="ver">
            <h3>Tus recetas:</h3>
            <?php 
                if (!$mis_recetas) {
                    echo "<p>No tienes ninguna receta!</p>";
                } else {
                    for ($i = 0; $i < sizeof($mis_recetas); $i++) {
                        $id = $mis_recetas[$i]['id_receta'];
                        echo "<div class='receta'>";
                        echo "<p>Identificador: ".$id."<p>";
                        echo "<form action='consultar_recetas.php' method='POST'>";
                        echo "<input type='hidden' name='borrar' value='$id'>";
                        echo "<input id='boton' type='submit' value='borrar'>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
            ?>
        </div>
        <div class="escribir">
            <h3>Nueva receta:</h3>
            <form action="consultar_recetas.php" method="POST">
                <input type="hidden" name="crear" value="si">
                <div>
                    <label for="medicamento">Medicamento:</label>
                    <input type="text" id="medicamento" name="medicamento">
                </div>
                <div>
                    <label for="paciente">Paciente:</label>
                    <input type="text" id="paciente" name="paciente">
                </div>
                <div>
                    <label for="pactivo">Principio Activo:</label>
                    <input type="text" id="pactivo" name="principio_activo">
                </div>
                <div>
                    <label for="dosis">Dosis:</label>
                    <input type="text" id="dosis" name="dosis">
                </div>
                <input type="submit">
                <?php 
                    if (isset($error_paciente) && $error_paciente) {
                        echo "<p style='color: red'>Comprueba el paciente!</p>";
                    }
                    if (isset($receta_creada) && $receta_creada) {
                        echo "<p style='color: green'>Receta creada!</p>";
                    }
                ?>
            </form>
        </div>
    </div>
</body>
</html>