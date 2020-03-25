<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->post('/api/usuarios/correos', function(Request $request, Response $response){


    $correo     = $request->getParam('correo');
    $principal  = $request->getParam('principal');
    $usuario    = $request->getParam('idUsuario');


    $consulta = "INSERT INTO seg_correos 
                    (   
                        correo,
                        principal,
                        idSegUsuario    
                    ) 
                VALUES 
                    (   
                        :correo,
                        :principal,
                        :idSegUsuario   
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':principal', $principal);
        $stmt->bindParam(':idSegUsuario', $usuario);

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

$app->put('/api/usuarios/correos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $correo = $request->getParam('correo');
    $principal = $request->getParam('principal');
    $usuario = $request->getParam('idUsuario');
    
    $consulta = "UPDATE seg_correos SET 

                        correo = :correo,
                        principal= :principal,
                        idSegUsuario=:idSegUsuario    

                    WHERE idSegCorreo = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':principal', $principal);
        $stmt->bindParam(':idSegUsuario', $usuario);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Correo actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/usuarios/correos/todos', function(Request $request, Response $response){

    $idSegUsuario = $request->getParam('idUsuario');
    
    $consulta = "DELETE FROM seg_correos WHERE idSegUsuario = $idSegUsuario";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Correo eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/usuarios/correos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM seg_correos WHERE idSegCorreo = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Correo eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

