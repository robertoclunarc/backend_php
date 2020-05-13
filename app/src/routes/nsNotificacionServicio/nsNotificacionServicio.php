<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/notificaciones/usuarios/{id}/todas', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $accion = $request->getParam('accion');
    $desde = $request->getParam('desde');
    $hasta = $request->getParam('hasta');

    $consulta = "CALL getAllNotificacionesPorUsuario(:idUser, :accion, :desde, :hasta)";

    try{

       $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){  
            $stmt->bindParam(':idUser', $id);
            $stmt->bindParam(':accion', $accion);
            $stmt->bindParam(':desde', $desde, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $hasta, PDO::PARAM_STR);  

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/notificaciones/usuarios/{id}/ultimas', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "CALL getUltNotificacionPorUsuario(:idUser);";


    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':idUser', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});


$app->get('/api/notificaciones/usuarios/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "CALL getNotificacionPorUsuario(:idUser);";

    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':idUser', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->post('/api/notificaciones', function(Request $request, Response $response){

    $mensaje        = $request->getParam('mensaje');
    $idServGer      = $request->getParam('idServiciosGerencias');
    $idRol          = $request->getParam('idSegRol');
    $idUserEnvio    = $request->getParam('idUsuarioEnvio');


    $consulta = "CALL registrarNotificacion(:mensaje, :idServGer, :idRol, :idUserEnv);";
    
    try
    {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':idServGer', $idServGer);
        $stmt->bindParam(':idRol', $idRol);
        $stmt->bindParam(':idUserEnv', $idUserEnvio);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null; 

        $notificacion = array('Data' => $result);
        echo json_encode($notificacion); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->post('/api/notificaciones/recibe', function(Request $request, Response $response){

    $mensaje        = $request->getParam('mensaje');
    $idUserEnvio    = $request->getParam('idUsuarioEnvio');
    $idUserRecibe    = $request->getParam('idUsuarioRecibe');
    $idServiciosGerencias    = $request->getParam('idServiciosGerencias');


    $consulta = "CALL registrarNotificacionPorUsuarioRecibe(:mensaje, :idUserEnv, :idUserRec, :idServGer);";
    
    try
    {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':idUserEnv', $idUserEnvio);
        $stmt->bindParam(':idUserRec', $idUserRecibe);
        $stmt->bindParam(':idServGer', $idServiciosGerencias);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null; 

        $notificacion = array('Data' => $result);
        echo json_encode($notificacion); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->patch('/api/notificaciones/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $estado     = $request->getParam('estado');
    $fecha= date("Y-m-d G:i:s");
   
    if($estado==3){

        $consulta = "UPDATE ns_notificacion_servicios SET estado  = :estado, fechaLectura = :fechaLectura
                        WHERE idNotificacionServicio = $id";
    }
    else{
        $consulta = "UPDATE ns_notificacion_servicios SET estado  = :estado
                        WHERE idNotificacionServicio = $id";

    }


    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        if($estado==3)
            $stmt->bindParam(':fechaLectura', $fecha);
  
        $stmt->bindParam(':estado', $estado);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Estado de la notificaciones actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->post('/api/notificaciones/enviar', function(Request $request, Response $response){

    $accion         = $request->getParam('accion');
    $mensaje        = $request->getParam('mensaje');
    $usuarioEnvio   = $request->getParam('usuarioEnvio');
    $usuarioRecibe  = $request->getParam('usuarioRecibe');
    $servicio       = $request->getParam('servicio');
    $rol            = $request->getParam('rol');


    $consulta = "CALL enviarNotificacion(:accion, :msj, :uEnv, :uRecib, :serv, :rol);";
    
    try
    {
        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);

        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':msj', $mensaje);
        $stmt->bindParam(':uEnv', $usuarioEnvio);
        $stmt->bindParam(':uRecib', $usuarioRecibe);
        $stmt->bindParam(':serv', $servicio);
        $stmt->bindParam(':rol', $rol);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null; 

        $notificacion = array('Data' => $result);
        echo json_encode($notificacion); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

