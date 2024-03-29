<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/estadosolped', function(Request $request, Response $response){

    $consulta = "SELECT * FROM compras_estados_solped";

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

$app->get('/api/estadosolped/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM compras_estados_solped WHERE idComprasEstadosSolped = $id";

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



$app->post('/api/estadosolped', function(Request $request, Response $response){


    $fechaAOrdenC         = $request->getParam('fechaAOrdenC');
    $idTicketServicio    = $request->getParam('idTicketServicio');
    $idEstadoActual    = $request->getParam('idEstadoActual');
    $estadoactual    = $request->getParam('estadoActual');

    $consulta = "INSERT INTO compras_solped 
                    (   
                        fechaAOrdenC,
                        idTicketServicio,
                        idEstadoActual,
                        estadoActual                    
                    ) 
                VALUES 
                    (   
                        :fechaAOrdenC,
                        :idTicketServicio,
                        :idEstadoActual,
                        :estadoActual
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':fechaAOrdenC', $fechaAOrdenC);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);        
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);        
        $stmt->bindParam(':estadoActual', $estadoactual);        
      
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});



$app->put('/api/estadosolped/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $fechaAOrdenC         = $request->getParam('fechaAOrdenC');
    $idTicketServicio    = $request->getParam('idTicketServicio');
    $idEstadoActual    = $request->getParam('idEstadoActual');
    $estadoactual    = $request->getParam('estadoActual');

    
    $consulta = "UPDATE compras_solped SET 
                        
                        fechaAOrdenC = :fechaAOrdenC,
                        idTicketServicio = :idTicketServicio,
                        idEstadoActual = :idEstadoActual,
                        estadoActual = :estadoActual
                        
                        WHERE idSolpedCompras = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':fechaAOrdenC', $fechaAOrdenC);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);        
        $stmt->bindParam(':estadoActual', $estadoactual);   

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Cargo actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/estadosolped/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM compras_estados_solped WHERE idComprasEstadosSolped = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Cargo eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});