<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/empresa', function(Request $request, Response $response){

    $consulta = "SELECT * FROM gen_empresa WHERE cerrada = 'No'";

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

$app->get('/api/empresa/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM gen_empresa WHERE IdGenEmpresa = $id";

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

$app->post('/api/empresa', function(Request $request, Response $response){


    $nombre_empresa         = $request->getParam('nombre_empresa');
    $rif    = $request->getParam('rif');
    $base_de_datos    = $request->getParam('base_de_datos');
    $fecha_ope    = $request->getParam('fecha_ope');
    $logo    = $request->getParam('logo');
    $cerrada    = $request->getParam('cerrada');
    $direccion_fiscal    = $request->getParam('direccion_fiscal');

    $consulta = "INSERT INTO gen_empresa 
                    (   
                        nombre_empresa,
                        rif,
                        base_de_datos,
                        fecha_ope,
                        logo,
                        cerrada,
                        direccion_fiscal                    
                    ) 
                VALUES 
                    (   
                        :nombre_empresa,
                        :rif,
                        :base_de_datos,
                        :fecha_ope,
                        :logo,
                        :cerrada,
                        :direccion_fiscal
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre_empresa', $nombre_empresa);
        $stmt->bindParam(':rif', $rif);        
        $stmt->bindParam(':base_de_datos', $base_de_datos); 
        $stmt->bindParam(':fecha_ope', $fecha_ope); 
        $stmt->bindParam(':logo', $logo); 
        $stmt->bindParam(':cerrada', $cerrada); 
        $stmt->bindParam(':direccion_fiscal', $direccion_fiscal); 
        
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



$app->put('/api/empresa/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $nombre_empresa         = $request->getParam('nombre_empresa');
    $rif    = $request->getParam('rif');
    $base_de_datos    = $request->getParam('base_de_datos');
    $fecha_ope    = $request->getParam('fecha_ope');
    $logo    = $request->getParam('logo');
    $cerrada    = $request->getParam('cerrada');
    $direccion_fiscal    = $request->getParam('direccion_fiscal');
    
    $consulta = "UPDATE gen_empresa SET 
                        
                        nombre_empresa = :nombre_empresa,
                        rif = :rif,
                        base_de_datos = :base_de_datos,
                        fecha_ope = :fecha_ope,
                        logo = :logo,
                        cerrada = :cerrada,
                        direccion_fiscal = :direccion_fiscal
                        
                        WHERE IdGenEmpresa = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre_empresa', $nombre_empresa);
        $stmt->bindParam(':rif', $rif);
        $stmt->bindParam(':base_de_datos', $base_de_datos);
        $stmt->bindParam(':fecha_ope', $fecha_ope); 
        $stmt->bindParam(':logo', $logo); 
        $stmt->bindParam(':cerrada', $cerrada); 
        $stmt->bindParam(':direccion_fiscal', $direccion_fiscal); 
        
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Centro de costos actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/empresa/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM gen_empresa WHERE IdGenEmpresa = $id";

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