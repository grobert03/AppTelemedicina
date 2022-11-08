<?php
include_once "operacionesCorreo.php";
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



// Funcion para comprobar los credenciales del login
function comprobar_credenciales($nombre, $clave) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
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
	if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
		return false;
	}

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


function comprobar_confirmar_ingreso($nombre, $clave, $correo, $cod_activacion) {
	if (comprobar_datos_registro($nombre, $correo)) {
		$carpeta = $_SERVER['PHP_SELF'] ;
		if (enviar_correo_verificacion("Confirmacion registro", "Click en el enlace para verificar: <a href='http:localhost$carpeta?confirmado=$cod_activacion'>Activar</a>", $correo)) {
			return true;
		}
		return false;
	} else {
		return false;
	}
}

function ingresar_usuario($nombre, $clave, $correo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

	if (str_contains($correo, "@comem.es")) {
		$consulta = "INSERT INTO medicos VALUES('$nombre', '$clave_cifrada', '$correo', 0, NULL, NULL, NULL, NULL, NULL, NULL);";
	} else {
		$consulta = "INSERT INTO pacientes VALUES('$nombre', '$clave_cifrada', '$correo', 0, NULL);";
	}
	$resultado = $bd->query($consulta);
	if ($resultado->rowCount() == 1) {
		return true;
	} else {
		return false;
	}
}

function comprobar_destinatarios_validos($destinatarios) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	if (!str_contains($destinatarios, ',')) {
		$destinatarios = $destinatarios + ',';
	}

	$destinatarios = explode(',', $destinatarios);

	for ($i = 0; $i < sizeof($destinatarios); $i++) {
		$destino = $destinatarios[$i];

		$consulta = "SELECT * FROM medicos WHERE usuario = '$destino'";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() == 0) {
			return false;
		}
	}
	return true;
}

function enviar_mensaje($destinatarios, $asunto, $contenido) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	if (!str_contains($destinatarios, ',')) {
		$destinatarios = $destinatarios + ',';
	}

	$destinatarios = explode(',', $destinatarios);
	$usu = $_SESSION['usuario']['usuario'];
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");

	for ($i = 0; $i < sizeof($destinatarios); $i++) {
		$destino = $destinatarios[$i];
		$consulta = "INSERT INTO mensajes (remitente, destinatario, asunto, contenido, fecha_envio, hora_envio, leido) VALUES ('$usu', '$destino', '$asunto', '$contenido', cast('$fecha' AS DATE), cast('$hora' AS TIME), 0);";
		$resultado = $bd->query($consulta);
		if ($resultado->rowCount() == 0) {
			return false;
		}
	}
	return true;

}

function mostrar_mensajes($destinatario) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$usu = $_SESSION['usuario']['usuario'];

	$consulta = "SELECT * FROM mensajes where destinatario = '$usu'";

	$datos = $bd->query($consulta);

	if ($datos->rowCount() == 0) {
		return false;
	} else {
		return $datos->fetchAll();
	}
}

function devolver_mensaje($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$usu = $_SESSION['usuario']['usuario'];

	$consulta = "SELECT * FROM mensajes where id_mensaje = $id and destinatario = '$usu'";
	$datos = $bd->query($consulta);

	if ($datos -> rowCount() == 0) {
		return false;
	} else {
		return $datos->fetch();
	}
}

function actualizar_mensaje_leido($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "UPDATE mensajes SET leido = TRUE WHERE id_mensaje = $id";
	$datos = $bd->query($consulta);

	if ($datos -> rowCount() == 0) {
		return false;
	} else {
		return true;
	}
}