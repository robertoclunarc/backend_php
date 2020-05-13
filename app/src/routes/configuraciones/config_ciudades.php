<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/ciudades', function(Request $request, Response $response){

    $consulta = "SELECT idConfigCiudad, nombre, esCapital FROM config_ciudades";

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

$app->get('/api/ciudades/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM config_ciudades WHERE idConfigCiudad = $id";

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

$app->get('/api/estados/{id}/ciudades', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM config_ciudades WHERE idConfigEstado = $id";

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

$app->get('/api/estados/{idEstado}/ciudades/{idCiudad}', function(Request $request, Response $response){

    $idEstado = $request->getAttribute('idEstado');
    $idCiudad = $request->getAttribute('idCiudad');
    
    $consulta = "SELECT * FROM config_ciudades WHERE idConfigEstado = $idEstado AND idConfigCiudad = $idCiudad";

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


$app->post('/api/ciudades', function(Request $request, Response $response){


    $nombre         = $request->getParam('nombre');
    $esCapital      = $request->getParam('esCapital');
    $idEstado         = $request->getParam('idEstado');

    $consulta = "INSERT INTO config_ciudades 
                    (   
                        nombre,
                        esCapital,
                        idConfigEstado
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :esCapital,
                        :idEstado
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':esCapital', $esCapital);        
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

$app->put('/api/ciudades/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $nombre         = $request->getParam('nombre');
    $esCapital      = $request->getParam('esCapital');
    $idEstado         = $request->getParam('idEstado');
    
    $consulta = "UPDATE config_ciudades SET 
                        
                        nombre = :nombre,
                        esCapital = :esCapital,
                        idConfigEstado = :idEstado
                        
                        WHERE idConfigCiudad = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':esCapital', $esCapital);        
        $stmt->bindParam(':idEstado', $idEstado);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Ciudad actualizada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/ciudades/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM config_ciudades WHERE idConfigCiudad = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Ciudad del pais eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

