<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/gerencias', function(Request $request, Response $response){

    $consulta = "SELECT g.*, g.nombre as label, g.idConfigGerencia as value FROM config_gerencias g";

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

$app->get('/api/gerenciassinactual/{id}', function(Request $request, Response $response){

    $idgerencia = $request->getAttribute('id'); 
    $consulta = "SELECT * FROM config_gerencias WHERE idConfigGerencia <> $idgerencia";

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

$app->get('/api/gerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "SELECT * FROM config_gerencias WHERE idConfigGerencia = $id";

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

$app->get('/api/gerencias/{idGerencia}/cargos', function(Request $request, Response $response){

    $id = $request->getAttribute('idGerencia');
    
    $consulta = "select c.* from config_cargos c inner join config_gerencias_cargos gc on 
    gc.idConfigCargo = c.idConfigCargo where gc.idConfigGerencia = $id";

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

$app->get('/api/gerencias/{id}/areasTrabajo', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
   /*  $consulta = "SELECT 
                        atg.*,
                        atg.nombre as label,
                        atg.idAdmAreaTrabajoGerencia as value
                    FROM 
                        adm_areas_trabajo_gerencias atg
                    WHERE
                        atg.idConfigGerencia = $id"; */

    $consulta = "SELECT ar.*, ar.nombre as label, ar.idAreaTrabajo as value, ar.idAreaTrabajo as idAdmAreaTrabajoGerencia
                    FROM adm_areas_trabajo ar
                INNER JOIN adm_areas_relacion_gerencia rela ON ar.idAreaTrabajo = rela.idAreaTrabajo
                WHERE rela.idConfigGerencia =  $id"; 

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

$app->post('/api/gerencias', function(Request $request, Response $response){


    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');

    $consulta = "INSERT INTO config_gerencias 
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

        $user = array('ObjectId' => $id);
        echo json_encode($user); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->put('/api/gerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');
    
    $consulta = "UPDATE config_gerencias SET 

                        nombre          =  :nombre,
                        descripcion     =  :descripcion

                    WHERE idConfigGerencia = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia actualizada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->delete('/api/gerencias/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    
    $consulta = "DELETE FROM config_gerencias WHERE idConfigGerencia = $id";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia eliminada correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});