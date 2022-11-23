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

function mostrar_mensajes_recibidos($destinatario) {
    $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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
    $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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

function verificar_varios_destinatarios($hora) {
	$res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);	

	$consulta = "SELECT * FROM mensajes WHERE hora_envio = '$hora'";

	$resultado = $bd->query($consulta);

	if ($resultado->rowCount() > 1) {
		return true;
	} else {
		return false;
	}
}

?>