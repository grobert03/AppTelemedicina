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
    
    function ver_receta($codigo) {
        $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
        $bd = new PDO($res[0], $res[1], $res[2]);
    
        $consulta = "SELECT * FROM RECETAS WHERE id_receta = '$codigo'";
    
        $resultado = $bd->query($consulta);
    
        if ($resultado->rowCount() == 1) {
            return $resultado->fetch();
        }
        return false;
    }

    function comprobar_es_paciente($paciente) {
        $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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
        $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
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

    function borrar_receta($id) {
        $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
        $bd = new PDO($res[0], $res[1], $res[2]);
    
        $consulta = "DELETE FROM recetas where id_receta = '$id'";
    
        $resultado = $bd->query($consulta);
    
        if ($resultado->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ver_mis_recetas($usuario) {
        $res = leer_configuracionBDD(dirname(__FILE__)."/../configuracion/configuracionBBDD.xml", dirname(__FILE__)."/../configuracion/configuracionBBDD.xsd");
        $bd = new PDO($res[0], $res[1], $res[2]);
    
        $consulta = "SELECT * FROM recetas where medico = '$usuario'";
    
        $resultado = $bd->query($consulta);
    
        if ($resultado->rowCount() > 0) {
            return $resultado->fetchAll();
        } else {
            return false;
        }
    }


?>