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

function comprobar_credenciales_login($nombre, $clave) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	// Comprobar que el usuario existe entre los pacientes
	$consulta = "select * from pacientes where usuario = '$nombre'";
	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() === 1) {
		// Verificar de que la contraseña es correcta
		$resultado = $resultado->fetch();
		if (password_verify($clave, $resultado['pass'])) {
			return $resultado;
		} else {
			return false;
		}
	} else {
		// Comprobar que el usuario existe entre los médicos
		$consulta = "select * from medicos where usuario = '$nombre'";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() === 1) {
			// Verificar de que la contraseña es correcta
			$resultado = $resultado->fetch();
			if (password_verify($clave, $resultado['pass'])) {
				return $resultado;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

?>