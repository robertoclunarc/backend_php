<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/perfiles', function(Request $request, Response $response){

    $consulta = "SELECT * FROM seg_perfiles";

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

$app->get('/api/perfiles/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM seg_perfiles WHERE idSegPerfil = $id";

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

$app->post('/api/perfiles', function(Request $request, Response $response){

    $codigo         = $request->getParam('codigo');
    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');
    $estatus        = $request->getParam('estatus');
 

    $consulta = "INSERT INTO seg_perfiles 
                    (   
                        codigo,
                        nombre,
                        descripcion,
                        estatus
                    ) 
                VALUES 
                    (   
                        :codigo,
                        :nombre,
                        :descripcion,
                        :estatus
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':estatus', $estatus);
      
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


$app->put('/api/perfiles/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $codigo         = $request->getParam('codigo');
    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');
    $estatus        = $request->getParam('estatus');
    
    $consulta = "UPDATE seg_perfiles SET 

                        codigo          =  :codigo,
                        nombre          =  :nombre,
                        descripcion     =  :descripcion,
                        estatus         =  :estatus

                    
                    WHERE idSegPerfil = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':estatus', $estatus);
      

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Perfil actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

$app->delete('/api/perfiles/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM seg_perfiles WHERE idSegPerfil = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

       echo '{"message": {"text": "Perfil eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});
