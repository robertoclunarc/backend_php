<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/municipios', function(Request $request, Response $response){

    $consulta = "SELECT idConfigMunicipio, nombre FROM config_municipios";

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

$app->get('/api/municipios/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT idConfigMunicipio, nombre FROM config_municipios WHERE idConfigMunicipio = $id";

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

$app->get('/api/estados/{id}/municipios', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT idConfigMunicipio, nombre FROM config_municipios WHERE idConfigEstado = $id";

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

$app->get('/api/estados/{idEstado}/municipios/{idMunicipio}', function(Request $request, Response $response){

    $idEstado = $request->getAttribute('idEstado');
    $idMunicipio = $request->getAttribute('idMunicipio');
    
    $consulta = "SELECT * FROM config_municipios WHERE idConfigEstado = $idEstado AND idConfigMunicipio = $idMunicipio";

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

$app->post('/api/municipios', function(Request $request, Response $response){


    $nombre     = $request->getParam('nombre');
    $idEstado   = $request->getParam('idEstado');

    $consulta = "INSERT INTO config_municipios 
                    (   
                        nombre,
                        idConfigEstado
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :idEstado
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':idEstado', $idEstado);
      
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

$app->put('/api/municipios/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');


    $nombre     = $request->getParam('nombre');
    $idEstado   = $request->getParam('idEstado');
    
    $consulta = "UPDATE config_municipios SET 
                        
                        nombre = :nombre,
                        idConfigEstado = :idEstado
                        
                        WHERE idConfigMunicipio = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':idEstado', $idEstado);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Municipio actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/municipios/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM config_municipios WHERE idConfigMunicipio = $id";

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
