<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/parroquias', function(Request $request, Response $response){

    $consulta = "SELECT idConfigParroquia, nombre FROM config_parroquias";

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

$app->get('/api/parroquias/{idParroquia}', function(Request $request, Response $response){

    $idParroquia = $request->getAttribute('idParroquia');
    
    $consulta = "SELECT idConfigParroquia, nombre FROM config_parroquias WHERE idConfigParroquia = $idParroquia";

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

$app->get('/api/municipios/{idMunicipio}/parroquias', function(Request $request, Response $response){

    $idMunicipio = $request->getAttribute('idMunicipio');
    
    $consulta = "SELECT idConfigParroquia, nombre FROM config_parroquias WHERE idConfigMunicipio = $idMunicipio";

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

$app->get('/api/municipios/{idMunicipio}/parroquias/{idParroquia}', function(Request $request, Response $response){

    $idMunicipio = $request->getAttribute('idMunicipio');
    $idParroquia = $request->getAttribute('idParroquia');
    
    $consulta = "SELECT idConfigParroquia, nombre FROM config_parroquias WHERE idConfigParroquia = $idParroquia AND idConfigMunicipio = $idMunicipio";

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

$app->post('/api/parroquias', function(Request $request, Response $response){


    $nombre     = $request->getParam('nombre');
    $idMunicipio   = $request->getParam('idMunicipio');

    $consulta = "INSERT INTO config_parroquias 
                    (   
                        nombre,
                        idConfigMunicipio
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :idMunicipio
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':idMunicipio', $idMunicipio);
      
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

$app->put('/api/parroquias/{idParroquia}', function(Request $request, Response $response){

    $idParroquia = $request->getAttribute('idParroquia');

    $nombre         = $request->getParam('nombre');
    $idMunicipio    = $request->getParam('idMunicipio');
    
    $consulta = "UPDATE config_parroquias SET 
                        
                        nombre = :nombre,
                        idConfigMunicipio = :idMunicipio
                        
                        WHERE idConfigParroquia = $idParroquia";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':idMunicipio', $idMunicipio);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Parroquia actualizada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/parroquias/{idParroquia}', function(Request $request, Response $response){

    $idParroquia = $request->getAttribute('idParroquia');
    
    $consulta = "DELETE FROM config_parroquias WHERE idConfigParroquia = $idParroquia";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Parroquia eliminada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


