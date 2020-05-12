<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/areanegocio', function(Request $request, Response $response){

    $consulta = "SELECT * FROM gen_area_negocio";

    try{

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $users = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;
      /*   $data = $request->getAttribute('foo');
        print_r($data); */
        echo json_encode($users);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/areanegocio/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM gen_area_negocio WHERE idGenAreaNegocio = $id";

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

$app->get('/api/areanegociogerencia/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT arean.*,
	                    gea.*
                 FROM gen_area_negocio arean
                 INNER JOIN gen_empre_area_gerencia gea ON gea.idGenAreaNegocio = arean.idGenAreaNegocio
                 WHERE gea.idConfigGerencia = $id";

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

$app->post('/api/areanegocio', function(Request $request, Response $response){


    $codigo         = $request->getParam('codigo');
    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');
    $idAdmTipo    = $request->getParam('idAdmTipo');

    $consulta = "INSERT INTO gen_area_negocio 
                    (   
                        codigo,
                        nombre,
                        descripcion,
                        idAdmTipo                    
                    ) 
                VALUES 
                    (   
                        :codigo,
                        :nombre,
                        :descripcion,
                        :idAdmTipo
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);        
        $stmt->bindParam(':idAdmTipo', $idAdmTipo); 
      
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



$app->put('/api/areanegocio/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $codigo         = $request->getParam('codigo');
    $nombre         = $request->getParam('nombre');
    $descripcion    = $request->getParam('descripcion');
    $idAdmTipo    = $request->getParam('idAdmTipo');
    
    $consulta = "UPDATE gen_area_negocio SET 
                        
                        codigo = :codigo,
                        nombre = :nombre,
                        descripcion = :descripcion,
                        idAdmTipo = :idAdmTipo
                        
                        WHERE idGenAreaNegocio = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idAdmTipo', $idAdmTipo);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Centro de costos actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/areanegocio/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM gen_area_negocio WHERE idGenAreaNegocio = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Centro de costos eliminado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});