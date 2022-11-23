<?php 

function leer_configuracionBDD($nombre, $esquema) {
    $config = new DOMDocument();
	$config->load($nombre);
	$res = $config->schemaValidate($esquema);
    if ($res===FALSE){ 
        throw new InvalidArgumentException("Revise fichero de configuración");
    }
    $datos = simplexml_load_file($nombre);	
	$ip = $datos->xpath("//ip");
	$nombre = $datos->xpath("//nombre");
	$usu = $datos->xpath("//usuario");
	$clave = $datos->xpath("//clave");	
	$cad = sprintf("mysql:dbname=%s;host=%s", $nombre[0], $ip[0]);
	$resul = [];
	$resul[] = $cad;
	$resul[] = $usu[0];
	$resul[] = $clave[0];
	return $resul;
}


function comprobar_usuario_modificar($usuario, $correo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

    $consulta = "SELECT * FROM pacientes WHERE usuario = '$usuario' AND correo = '$correo'";

    $resultado = $bd->query($consulta);

    if ($resultado->rowCount() == 0) {
        $consulta = "SELECT * FROM medicos WHERE usuario = '$usuario' AND correo = '$correo'";

        $resultado = $bd->query($consulta);

        if ($resultado->rowCount() == 0) {
            return false;
        }
    } 

    return true;
}

function cambiar_pass($usuario, $nuevo_pass) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$nuevo_pass = password_hash($nuevo_pass, PASSWORD_DEFAULT);

	$consulta = "UPDATE medicos SET pass = '$nuevo_pass' WHERE usuario = '$usuario'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 0) {
		$consulta = "UPDATE pacientes SET pass = '$nuevo_pass' WHERE usuario = '$usuario'";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() == 0) {
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}


?>