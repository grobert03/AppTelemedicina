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

// Funcion para comprobar que el usuario y la clave están correctas en el login
function comprobar_usuario($nombre, $clave) {
    // Verificar la contraseña
    $clave2 = devolver_clave($nombre)['clave'];
	if (!password_verify($clave, $clave2)) {
		return false;
	}
    // Devolver el usuario y la contraseña
	$res = leer_configuracionBDD(dirname(__FILE__)."/configuracionBDD.xml", dirname(__FILE__)."/configuracionBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
	$ins = "select usuario, correo from restaurantes where correo = '$nombre' 
			and clave = '$clave2'";
	$resul = $bd->query($ins);	
	if($resul->rowCount() === 1){		
		return $resul->fetch();		
	}else{
		return FALSE;
	}
}

// Funcion para buscar la clave de un usuario
function devolver_clave($nombre) {
    $res = leer_configuracionBDD(dirname(__FILE__)."/configuracionBDD.xml", dirname(__FILE__)."/configuracionBDD.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);
    // Verificar en la tabla de pacientes
	$ins = "select pass from pacientes where usuario = '$nombre'";
	$resul = $bd->query($ins);	
	if($resul->rowCount() === 1){		
		return $resul->fetch();		
	}else{
        // Verificar en la tabla de medicos
        $ins = "select pass from medicos where usuario = '$nombre'";
	    $resul = $bd->query($ins);
        if ($resul->rowCount() === 1) {
            return $resul->fetch();
        } else {
            return FALSE;
        }
		
	}
}

