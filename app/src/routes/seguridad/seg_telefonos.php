<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/usuarios/telefonos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM seg_telefonos WHERE idSegTelefono = $id";

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


$app->post('/api/usuarios/telefonos', function(Request $request, Response $response){


    $numero = $request->getParam('numero');
    $tipoTelefono = $request->getParam('tipo');
    $usuario = $request->getParam('idUsuario');


    $consulta = "INSERT INTO seg_telefonos 
                    (   
                        valor,
                        tipoTelefono,
                        idSegUsuario    
                    ) 
                VALUES 
                    (   
                        :valor,
                        :tipoTelefono,
                        :idSegUsuario   
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':valor', $numero);
        $stmt->bindParam(':tipoTelefono', $tipoTelefono);
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

$app->put('/api/usuarios/telefonos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $numero = $request->getParam('numero');
    $tipoTelefono = $request->getParam('tipo');
    $usuario = $request->getParam('idUsuario');
    
    $consulta = "UPDATE seg_telefonos SET 

                        valor        = :numero,
                        tipoTelefono = :tipoTelefono,
                        idSegUsuario = :idSegUsuario

                    WHERE idSegTelefono = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':tipoTelefono', $tipoTelefono);
        $stmt->bindParam(':idSegUsuario', $usuario);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Telefono actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/usuarios/telefonos/todos', function(Request $request, Response $response){

    $id = $request->getParam('idUsuario');
    
    $consulta = "DELETE FROM seg_telefonos WHERE idSegUsuario = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Telefonos eliminados correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});




$app->delete('/api/usuarios/telefonos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM seg_telefonos WHERE idSegTelefono = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Telefono eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


