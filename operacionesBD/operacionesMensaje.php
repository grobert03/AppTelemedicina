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

function responder($destinatario, $usuario, $asunto, $contenido) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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

function devolver_mensaje_entrada($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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


function devolver_destinatarios($hora) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$consulta = "SELECT * FROM mensajes WHERE hora_envio = '$hora'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return $resultado->fetchAll();
	} else {
		return false;
	}
}

function actualizar_mensaje_leido($id) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "UPDATE mensajes SET leido = TRUE WHERE id_mensaje = $id";
	$datos = $bd->query($consulta);

	if ($datos -> rowCount() == 0) {
		return false;
	} else {
		return true;
	}
}
?>