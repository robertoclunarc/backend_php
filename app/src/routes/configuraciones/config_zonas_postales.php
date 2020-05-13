<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/zonas_postales', function(Request $request, Response $response){

    $consulta = "SELECT idConfigZonaPostal, nombre, codigoPostal FROM config_zonas_postales";

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


$app->get('/api/zonas_postales/{idZonaPostal}', function(Request $request, Response $response){

    $idZonaPostal = $request->getAttribute('idZonaPostal');
    
    $consulta = "SELECT idConfigZonaPostal, nombre, codigoPostal FROM config_zonas_postales WHERE idConfigZonaPostal = $idZonaPostal";

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

$app->get('/api/estados/{idEstado}/zonas_postales', function(Request $request, Response $response){

    $idEstado = $request->getAttribute('idEstado');
    
    $consulta = "SELECT idConfigZonaPostal, nombre, codigoPostal FROM config_zonas_postales WHERE idConfigEstado = $idEstado";

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

$app->get('/api/estados/{idEstado}/zonas_postales/{idZonaPostal}', function(Request $request, Response $response){

    $idEstado = $request->getAttribute('idEstado');
    $idZonaPostal = $request->getAttribute('idZonaPostal');
    
    $consulta = "SELECT idConfigZonaPostal, nombre, codigoPostal FROM config_zonas_postales WHERE idConfigEstado = $idEstado AND idConfigZonaPostal = $idZonaPostal";

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

$app->post('/api/zonas_postales', function(Request $request, Response $response){


    $nombre         = $request->getParam('nombre');
    $codigoPostal      = $request->getParam('codigoPostal');
    $idEstado         = $request->getParam('idEstado');

    $consulta = "INSERT INTO config_zonas_postales 
                    (   
                        nombre,
                        codigoPostal,
                        idConfigEstado
                        
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :codigoPostal,
                        :idEstado
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':codigoPostal', $codigoPostal);        
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

$app->put('/api/zonas_postales/{idZonaPostal}', function(Request $request, Response $response){

    $idZonaPostal = $request->getAttribute('idZonaPostal');

    $nombre         = $request->getParam('nombre');
    $codigoPostal      = $request->getParam('codigoPostal');
    $idEstado         = $request->getParam('idEstado');
    
    $consulta = "UPDATE config_zonas_postales SET 
                        
                        nombre = :nombre,
                        codigoPostal = :codigoPostal,
                        idConfigEstado = :idEstado
                        
                        WHERE idConfigZonaPostal = $idZonaPostal";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':codigoPostal', $codigoPostal);        
        $stmt->bindParam(':idEstado', $idEstado);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Zona Postal actualizada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/zonas_postales/{idZonaPostal}', function(Request $request, Response $response){

    $idZonaPostal = $request->getAttribute('idZonaPostal');
    
    $consulta = "DELETE FROM config_zonas_postales WHERE idConfigZonaPostal = $idZonaPostal";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Zona postal eliminada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});