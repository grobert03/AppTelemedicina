<?php
// Función para leer y validar el XML de la base de datos
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

// Función para leer y validar el XML del correo
function leer_configuracionCorreo() {

}

// Funcion para comprobar los credenciales del login
function comprobar_credenciales($nombre, $clave) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	// Comprobar que el usuario existe entre los pacientes
	$consulta = "select usuario, pass from pacientes where usuario = '$nombre'";
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
		$consulta = "select usuario, pass from medicos where usuario = '$nombre'";
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

// Funcion para comprobar que no se crean usuarios repetidos
function comprobar_datos_registro($usuario, $correo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	//Comprobar usuario en pacientes
	$consulta = "Select * from pacientes where usuario = '$usuario'";
	$resultado = $bd->query($consulta);
	if ($resultado->rowCount() === 0) {
		//Comprobar usuario en medicos
		$consulta = "Select * from medicos where usuario = '$usuario'";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() != 0) {
			return false;
		}
	} else {
		return false;
	}

	// Comprobar correo en pacientes
	$consulta = "Select * from pacientes where correo = '$correo'";
	$resultado = $bd->query($consulta);
	if ($resultado->rowCount() === 0) {
		//Comprobar correo en medicos
		$consulta = "Select * from medicos where correo = '$correo'";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() != 0) {
			return false;
		}
	} else {
		return false;
	}

	return true;
}


function ingresar_usuario($nombre, $clave, $correo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	$clave = password_hash($clave, PASSWORD_DEFAULT);
	if (str_contains($correo, '@comem.es')) {
		$query = "insert into medicos values ('$nombre', '$clave', '$correo', NULL, NULL, NULL, NULL, NULL, NULL);";
		$resul = $bd->query($query);
		if ($resul->rowCount() != 1) {
			echo "UH OH";
		} else {
			echo "LINEA INSERTADA";
		}
	} else {
		$query = "insert into pacientes values ('$nombre', '$clave', '$correo', NULL);";
		$resul = $bd->query($query);
		if ($resul->rowCount() != 1) {
			echo "UH OH";
		} else {
			echo "LINEA INSERTADA";
		}
	}
}