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

function responder($destinatario, $usuario, $asunto, $contenido) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$fecha = date("Y-m-d");
	$hora = date("H:i:s");

	$consulta = "INSERT INTO mensajes (remitente, destinatario, asunto, contenido, fecha_envio, hora_envio, leido) VALUES ('$usuario', '$destinatario', '$asunto', '$contenido', cast('$fecha' AS DATE), cast('$hora' AS TIME), 0);";
	$resultado = $bd->query($consulta);
	if ($resultado->rowCount() == 0) {
		return false;
	} 
	return true;
}

function mostrar_mensajes_recibidos($destinatario) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$usu = $_SESSION['usuario']['usuario'];

	$consulta = "SELECT * FROM mensajes where destinatario = '$usu' ORDER BY fecha_envio DESC, hora_envio DESC";

	$datos = $bd->query($consulta);

	if ($datos->rowCount() == 0) {
		return false;
	} else {
		return $datos->fetchAll();
	}
}

function mostrar_mensajes_enviados($remitente) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$usu = $_SESSION['usuario']['usuario'];

	$consulta = "SELECT * FROM mensajes where remitente = '$usu' ORDER BY fecha_envio DESC, hora_envio DESC";

	$datos = $bd->query($consulta);

	if ($datos->rowCount() == 0) {
		return false;
	} else {
		return $datos->fetchAll();
	}
}

function devolver_mensaje_entrada($id) {
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

function devolver_mensaje_salida($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$usu = $_SESSION['usuario']['usuario'];

	$consulta = "SELECT * FROM mensajes where id_mensaje = $id and remitente = '$usu'";
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

function cambiar_pass($usuario, $nuevo_pass) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
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

function ver_receta($codigo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT * FROM RECETAS WHERE id_receta = '$codigo'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 1) {
		return $resultado->fetch();
	}
	return false;
}

function comprobar_es_paciente($paciente) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT * FROM pacientes WHERE usuario = '$paciente'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 1) {
		return true;
	} else {
		return false;
	}
}

function crear_receta($medicamento, $paciente, $pactivo, $dosis) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$id = "rec".rand(10000, 99999);
	$medico = $_SESSION['usuario']['usuario'];

	$consulta = "INSERT into recetas values('$id', '$medicamento', '$paciente', '$medico', '$pactivo', '$dosis');";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 1) {
		return true;
	}
	return false;
}

function ver_mis_recetas($usuario) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT * FROM recetas where medico = '$usuario'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return $resultado->fetchAll();
	} else {
		return false;
	}
}

function borrar_receta($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "DELETE FROM recetas where id_receta = '$id'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return true;
	} else {
		return false;
	}
}

function devolver_medicos() {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT usuario FROM medicos";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return $resultado->fetchAll();
	} else {
		return false;
	}
}

function comprobar_carga($destinatario) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT * FROM mensajes WHERE destinatario = '$destinatario' AND leido = 0";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 20) {
		return false;
	} else {
		return true;
	}
}

function comprobar_usuario_modificar($usuario, $correo) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	


}

function verificar_varios_destinatarios($hora) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$consulta = "SELECT * FROM mensajes WHERE hora_envio = '$hora'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 1) {
		return true;
	} else {
		return false;
	}
}

function devolver_destinatarios($hora) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$consulta = "SELECT * FROM mensajes WHERE hora_envio = '$hora'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return $resultado->fetchAll();
	} else {
		return false;
	}
}

function guardar_imagen($img) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$usu = $_SESSION['usuario']['usuario'];

	if ($_SESSION['usuario']['tipo'] == 'medico') {
		$consulta = "UPDATE medicos set foto = '$img' WHERE usuario = '$usu';";
	} else {
		$consulta = "UPDATE pacientes set foto = '$img' WHERE usuario = '$usu';";
	}
	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 0) {
		return false;
	} else {
		return true;
	}
}

function devolver_foto() {
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracion/configuracionBBDD.xml", dirname(__FILE__)."/configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$usu = $_SESSION['usuario']['usuario'];

	if ($_SESSION['usuario']['tipo'] == 'medico') {
		$consulta = "SELECT foto from medicos WHERE usuario = '$usu';";
	} else {
		$consulta = "SELECT foto from pacientes WHERE usuario = '$usu';";
	}
	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() == 0) {
		return false;
	} else {
		return $resultado->fetch();
	}
}