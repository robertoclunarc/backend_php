<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/trazas', function(Request $request, Response $response){

    $consulta = "SELECT * FROM ts_traza_ticket_servicio";

    try{

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $users = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($users);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/trazas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM ts_traza_ticket_servicio WHERE idConfigMunicipio = $id";

    try{

        $db = new db();

        $db = $db->conectar();  
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});


$app->post('/api/trazas', function(Request $request, Response $response){

    $justificacion      = $request->getParam('justificacion');
    $idTicketServicio   = $request->getParam('idTicketServicio');
    $idEstadoTicket     = $request->getParam('idEstadoTicket');
    $idSegUsuario       = $request->getParam('idSegUsuario');
    $estadoAnterior     = $request->getParam('estadoAnterior');


    $consulta = "INSERT INTO ts_traza_ticket_servicio 
                    (   
                        justificacion,
                        idTicketServicio,
                        idEstadoTicket,
                        idSegUsuario,
                        estadoAnterior
                    ) 
                VALUES 
                    (   
                        :justificacion,
                        :idTicketServicio,
                        :idEstadoTicket,
                        :idSegUsuario,
                        :estadoAnterior
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);
        $stmt->bindParam(':idEstadoTicket', $idEstadoTicket);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':estadoAnterior', $estadoAnterior);
      
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->put('/api/trazas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $justificacion     = $request->getParam('justificacion');
    $idTicketServicio   = $request->getParam('idTicketServicio');
    $idEstadoTicket   = $request->getParam('idEstadoTicket');
    $idSegUsuario   = $request->getParam('idSegUsuario');
    $estadoAnterior   = $request->getParam('estadoAnterior');
    
    $consulta = "UPDATE ts_traza_ticket_servicio SET

                        justificacion = :justificacion,
                        idTicketServicio = :idTicketServicio,
                        idEstadoTicket = :idEstadoTicket,
                        idSegUsuario = :idSegUsuario,
                        estadoAnterior = :estadoAnterior
                        
                        WHERE idTrazaTicket = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);
        $stmt->bindParam(':idEstadoTicket', $idEstadoTicket);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':estadoAnterior', $estadoAnterior);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "traza actualizada"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/trazas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM ts_traza_ticket_servicio WHERE idTarzaTicket = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Municipio eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});
