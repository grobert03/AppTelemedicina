<?php 
 use PHPMailer\PHPMailer\PHPMailer;
// Función para leer y validar el XML del correo
function leer_configuracionCorreo($nombre, $esquema) {
    $config = new DOMDocument();
	$config->load($nombre);
	$res = $config->schemaValidate($esquema);
    if ($res===FALSE){ 
        throw new InvalidArgumentException("Revise fichero de configuración");
    }
    $datos = simplexml_load_file($nombre);	
    $host = $datos->xpath("//host");
    $auth = $datos->xpath("//auth");
    $port = $datos->xpath("//port");
    $username = $datos->xpath("//username");
    $password = $datos->xpath("//password");
	
	$resul = [];
	$resul[] = $host[0];
	$resul[] = $auth[0];
	$resul[] = $port[0];
    $resul[] = $username[0];
    $resul[] = $password[0];
	return $resul;
}

function enviar_correo_verificacion($asunto, $cuerpo, $destinatario) {
    $resul = leer_configuracionCorreo(dirname(__FILE__)."/configuracion/configuracionCorreo.xml", dirname(__FILE__)."/configuracion/configuracionCorreo.xsd");
 	require "vendor/autoload.php";
 	
    $mail = new PHPMailer();
 	$mail->IsSMTP();
 	// cambiar a 0 para no ver mensajes de error
 	$mail->SMTPDebug  = 0; 							
 	$mail->SMTPAuth   = $resul[1];
 	$mail->SMTPSecure = "tls";                 
	$mail->Host       = $resul[0];    
	$mail->Port       = $resul[2];                 
	// introducir usuario de google
	$mail->Username   = $resul[3]; 
	// introducir clave
	$mail->Password   = $resul[4];   	
	
	$mail->SetFrom('mediMadrid@madrid.org', 'MediMadrid');
	// asunto
	$mail->Subject = $asunto;
	// cuerpo
	$mail->MsgHTML($cuerpo);
	// adjuntos
	// destinatario
	$address = $destinatario;
	$mail->AddAddress($address, "Test");
	// enviar
	$resul = $mail->Send();
	if(!$resul) {
	  echo "Error" . $mail->ErrorInfo;
      return false;
	} else {
      return true;
	}
}


function enviar_correo($destinatarios, $asunto, $mensaje) {
	$resul = leer_configuracionCorreo(dirname(__FILE__)."/configuracion/configuracionCorreo.xml", dirname(__FILE__)."/configuracion/configuracionCorreo.xsd");
	require "vendor/autoload.php";
	$mail = new PHPMailer();
	$mail->IsSMTP();
	// cambiar a 0 para no ver mensajes de error
	$mail->SMTPDebug  = $resul[5]; 							
	$mail->SMTPAuth   = $resul[1];
	$mail->SMTPSecure = "tls";                 
	$mail->Host       = $resul[0];    
	$mail->Port       = $resul[2];                 
	// introducir usuario de google
	$mail->Username   = $resul[3]; 
	// introducir clave
	$mail->Password   = $resul[4];  
	$destinatarios = explode(",", $destinatarios);
	
	
	for ($i = 0; $i < sizeof($destinatarios); $i++) {
		 	
		
		$mail->SetFrom($_SESSION['usuario']['correo'], $_SESSION['usuario']['usuario']);
		// asunto
		$mail->Subject = $asunto;
		// cuerpo
		$mail->MsgHTML($mensaje);
		// adjuntos
		// destinatario
		$address = $destinatarios[$i];
		$mail->AddAddress($address, "Persona ".$i);
		// enviar
		$resul = $mail->Send();
		if(!$resul) {
			echo "Error" . $mail->ErrorInfo;
			return false;
		} 
	}
	return true;
}
?>