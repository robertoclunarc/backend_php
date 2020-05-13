<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/cargos', function(Request $request, Response $response){

    $consulta = "SELECT * FROM config_cargos";

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

$app->get('/api/cargos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM config_cargos WHERE idConfigCargo = $id";

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

$app->post('/api/cargos', function(Request $request, Response $response){


    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');

    $consulta = "INSERT INTO config_cargos 
                    (   
                        nombre,
                        descripcion                    
                    ) 
                VALUES 
                    (   
                        :nombre,
                        :descripcion
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);        
      
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

$app->post('/api/cargos/asociar', function(Request $request, Response $response){


    $idConfigCargo         = $request->getParam('idConfigCargo');
    $idConfigGerencia      = $request->getParam('idConfigGerencia');

    $consulta = "INSERT INTO config_gerencias_cargos
                    (   
                        idConfigCargo,
                        idConfigGerencia                    
                    ) 
                VALUES 
                    (   
                        :idConfigCargo,
                        :idConfigGerencia
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':idConfigCargo', $idConfigCargo);
        $stmt->bindParam(':idConfigGerencia', $idConfigGerencia);        
      
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


$app->put('/api/cargos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');
    
    $consulta = "UPDATE config_cargos SET 
                        
                        nombre = :nombre,
                        descripcion = :descripcion
                        
                        WHERE idConfigCargo = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Cargo actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/cargos/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM config_cargos WHERE idConfigCargo = $id";

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