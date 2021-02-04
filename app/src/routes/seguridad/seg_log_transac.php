<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->post('/api/log', function (Request $request, Response $response) {
    $ip_dealgo = $request->getParam('ipPc');

    $observacion  = $request->getParam('observacion');
    $usuario    = $request->getParam('idSegUsuario');
    $rol    = $request->getParam('idSegRol');
    $menu    = $request->getParam('idSegMenu');
    $clasificacion    = $request->getParam('idTipoAccion');
    $idgerencia    = $request->getParam('idGerencia');


    $consulta = "INSERT INTO seg_log_transac 
                    (   
                        ipPC,
                        observacion,
                        idSegUsuario,
                        idSegRol,
                        idSegMenu,
                        idTipoAccion,
                        idGerencia
                    ) 
                VALUES 
                    (   
                        :ipPc,
                        :observacion,
                        :idSegUsuario,
                        :idSegRol,
                        :idSegMenu,
                        :idTipoAccion,
                        :idGerencia
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':ipPc', $ip_dealgo);

        $stmt->bindParam(':observacion', $observacion);
        $stmt->bindParam(':idSegUsuario', $usuario);
        $stmt->bindParam(':idSegRol', $rol);
        $stmt->bindParam(':idSegMenu', $menu);
        $stmt->bindParam(':idTipoAccion', $clasificacion);
        $stmt->bindParam(':idGerencia', $idgerencia);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/log', function (Request $request, Response $response) {

    $request->getAttribute("");

    $consulta = "SELECT * FROM seg_log_transac";

    try {

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $roles = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($roles);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/getlog/{modulo}/{accion}/{rol}/{desde}/{hasta}', function (Request $request, Response $response) {

    $modulo = $request->getAttribute("modulo");
    $accion = $request->getAttribute("accion");
    $rol = $request->getAttribute("rol");
    $desde = $request->getAttribute("desde");
    $hasta = $request->getAttribute("hasta");

    $consulta = "SELECT 	t.idLogTransac, 
                    t.fechaRegistro, 
                    t.ipPc, 
                    t.observacion, 
                    t.idSegUsuario, 
                    (SELECT usuario FROM seg_usuarios us WHERE us.idSegUsuario = t.idSegUsuario) usuario,
                    idSegRol,
                    (SELECT codigo FROM seg_roles rols WHERE rols.idSegRol = t.idSegRol) rol, 
                    idTipoAccion, 
                    (SELECT nombre FROM gen_tipo_acciones tacc WHERE tacc.idTipoAccion = t.idTipoAccion) 
                        tipo_accion, 
                    idSegMenu,
                    (SELECT titulo FROM seg_menus men WHERE men.idSegMenu = t.idSegMenu) modulo, 
                    idGerencia,
                    (SELECT nombre FROM config_gerencias gen WHERE gen.idConfigGerencia = t.idGerencia) gerencia
	 
                FROM seg_log_transac t
                WHERE 1 = 1";
                
    if ($modulo != -1) {       
        $consulta .= " and idSegMenu = $modulo";
       // print_r($consulta);
    }

    if ($accion != -1) {
        $consulta .= " and idTipoAccion = $accion";
    }
    if ($rol != -1) {
        $consulta .= " and idSegRol = $rol";
    }

    if (($desde != -1) && ($hasta != -1)){
        $consulta .= " and DATE_FORMAT(fechaRegistro, '%Y-%m-%d') BETWEEN '$desde' and '$hasta'";
    }

    
    //print_r($consulta);

    try {

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $roles = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($roles);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/log/infocliente', function (Request $request, Response $response) {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    echo json_encode($ipaddress);
});
