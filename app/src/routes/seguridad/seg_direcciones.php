<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->post('/api/usuarios/direcciones', function(Request $request, Response $response){


    $tipoResidencia     = $request->getParam('tipoResidencia');
    $tipoDireccion      = $request->getParam('tipoDireccion');
    $direccion          = $request->getParam('direccion');
    $puntoReferencia    = $request->getParam('puntoReferencia'); 
    $idSegUsuario       = $request->getParam('idSegUsuario');
    $idZonaPostal       = $request->getParam('idZonaPostal');
    $idParroquia        = $request->getParam('idParroquia'); 

    $consulta = "INSERT INTO seg_direcciones 
                    (   
                        tipoResidencia,
                        tipoDireccion,
                        direccion,
                        puntoReferencia,
                        idSegUsuario,
                        idConfigZonaPostal,
                        idConfigParroquia
                    ) 
                VALUES 
                    (   
                        :tipoResidencia,
                        :tipoDireccion,
                        :direccion,
                        :puntoReferencia,
                        :idSegUsuario,
                        :idZonaPostal,
                        :idParroquia 
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':tipoResidencia', $tipoResidencia);
        $stmt->bindParam(':tipoDireccion', $tipoDireccion);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':puntoReferencia', $puntoReferencia);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idZonaPostal', $idZonaPostal);
        $stmt->bindParam(':idParroquia', $idParroquia);

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


$app->put('/api/usuarios/direcciones/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $tipoResidencia     = $request->getParam('tipoResidencia');
    $tipoDireccion      = $request->getParam('tipoDireccion');
    $direccion          = $request->getParam('direccion');
    $puntoReferencia    = $request->getParam('puntoReferencia'); 
    $idSegUsuario       = $request->getParam('idSegUsuario');
    $idZonaPostal       = $request->getParam('idZonaPostal');
    $idParroquia        = $request->getParam('idParroquia'); 

    
    $consulta = "UPDATE seg_direcciones SET 
                        
                        tipoResidencia = :tipoResidencia,
                        tipoDireccion = :tipoDireccion,
                        direccion = :direccion,
                        puntoReferencia = :puntoReferencia,
                        idSegUsuario = :idSegUsuario,
                        idConfigZonaPostal = :idZonaPostal,
                        idConfigParroquia = :idParroquia

                    WHERE idSegDireccion = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':tipoResidencia', $tipoResidencia);
        $stmt->bindParam(':tipoDireccion', $tipoDireccion);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':puntoReferencia', $puntoReferencia);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idZonaPostal', $idZonaPostal);
        $stmt->bindParam(':idParroquia', $idParroquia);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Correo actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/usuarios/direcciones/todos', function(Request $request, Response $response){

    $idSegUsuario = $request->getParam('idUsuario');
    
    $consulta = "DELETE FROM seg_direcciones WHERE idSegUsuario = $idSegUsuario";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Direcciones eliminadas correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/usuarios/direcciones/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM seg_direcciones WHERE idSegDireccion = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Direccion eliminada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});