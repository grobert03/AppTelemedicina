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

function devolver_medicos() {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT usuario FROM medicos";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 0) {
		return $resultado->fetchAll();
	} else {
		return false;
	}
}

function enviar_mensaje($destinatarios, $asunto, $contenido) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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

function comprobar_carga($destinatario) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$consulta = "SELECT * FROM mensajes WHERE destinatario = '$destinatario' AND leido = 0";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() >= 20) {
		return false;
	} else {
		return true;
	}
}

?>