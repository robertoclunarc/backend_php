<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/empresacompras', function(Request $request, Response $response){

    $consulta = "SELECT * FROM compras_empresa WHERE cerrada = 0";

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

$app->get('/api/empresacomprastodas', function(Request $request, Response $response){

    $consulta = "SELECT * FROM compras_empresa ORDER BY cerrada, IdComprasEmpresa";

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

$app->get('/api/empresacompras/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM compras_empresa WHERE IdComprasEmpresa = $id";

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


$app->get('/api/empresacomprasgerencia/{id}/{idarea}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $idarea = $request->getAttribute('idarea');
    
    $consulta = "SELECT em.*, gea.*
                FROM compras_empresa em
                INNER JOIN gen_empre_area_gerencia gea ON em.IdComprasEmpresa = gea.idComprasEmpresa
                WHERE gea.idConfigGerencia = $id and gea.idGenAreaNegocio = $idarea
                GROUP BY em.idComprasEmpresa";

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

$app->post('/api/empresacompras', function(Request $request, Response $response){


    $nombre_empresa         = $request->getParam('nombre_empresa');
    $rif    = $request->getParam('rif');
    $base_de_datos    = $request->getParam('base_de_datos');
    $fecha_ope    = $request->getParam('fecha_ope');
   // $logo    = $request->getParam('logo');
    $cerrada    = $request->getParam('cerrada');
    $direccion_fiscal    = $request->getParam('direccion_fiscal');

    $consulta = "INSERT INTO compras_empresa 
                    (   
                        nombre_empresa,
                        rif,
                        base_de_datos,
                        fecha_ope,
                        cerrada,
                        direccion_fiscal                    
                    ) 
                VALUES 
                    (   
                        :nombre_empresa,
                        :rif,
                        :base_de_datos,
                        :fecha_ope,
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



$app->put('/api/empresacompras/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $nombre_empresa         = $request->getParam('nombre_empresa');
    $rif    = $request->getParam('rif');
    $base_de_datos    = $request->getParam('base_de_datos');
    $fecha_ope    = $request->getParam('fecha_ope');
    $cerrada    = $request->getParam('cerrada');
    $direccion_fiscal    = $request->getParam('direccion_fiscal');
    
    $consulta = "UPDATE compras_empresa SET 
                        
                        nombre_empresa = :nombre_empresa,
                        rif = :rif,
                        base_de_datos = :base_de_datos,
                        fecha_ope = :fecha_ope,
                        cerrada = :cerrada,
                        direccion_fiscal = :direccion_fiscal
                        
                        WHERE IdComprasEmpresa = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre_empresa', $nombre_empresa);
        $stmt->bindParam(':rif', $rif);
        $stmt->bindParam(':base_de_datos', $base_de_datos);
        $stmt->bindParam(':fecha_ope', $fecha_ope); 
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


$app->delete('/api/empresacompras/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM compras_empresa WHERE IdComprasEmpresa = $id";

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