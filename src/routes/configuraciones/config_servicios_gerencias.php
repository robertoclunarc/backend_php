<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/serviciosgerencias', function(Request $request, Response $response){

    $consulta = "SELECT * FROM config_servicios_gerencias";

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

$app->get('/api/serviciosgerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM config_servicios_gerencias WHERE idServiciosGerencias = $id";

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

$app->get('/api/serviciosporgerencias/{idGerencia}', function(Request $request, Response $response){

    $id = $request->getAttribute('idGerencia');
    
    $consulta = "SELECT * FROM config_servicios_gerencias WHERE idGerencia = $id";

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

$app->post('/api/serviciosgerencias', function(Request $request, Response $response){


    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');
    $idGerencia = $request->getParam('idGerencia');

    $consulta = "INSERT INTO config_servicios_gerencias 
                    (   
                        nombre,
                        descripcion,
                        idGerencia    
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :descripcion,
                        :idGerencia
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idGerencia', $idGerencia);

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

$app->put('/api/serviciosgerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');
    $idGerencia = $request->getParam('idGerencia');
    
    $consulta = "UPDATE config_servicios_gerencias SET 

                        nombre          =  :nombre,
                        descripcion     =  :descripcion,
                        idGerencia      =  :idGerencia

                    WHERE idServiciosGerencias = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idGerencia', $idGerencia);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia actualizada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/serviciosgerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM config_servicios_gerencias WHERE idServiciosGerencias = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia eliminada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});