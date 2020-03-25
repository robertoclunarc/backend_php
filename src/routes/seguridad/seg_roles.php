<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/roles', function(Request $request, Response $response){

    $consulta = "SELECT * FROM seg_roles";

    try{

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $roles = $ejecutar->fetchAll(PDO::FETCH_OBJ);

         $db = null;

        echo json_encode($roles);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/tipoacciones', function(Request $request, Response $response){

    $consulta = "SELECT idTipoAccion, 
	                    fecha_alta, 
	                    nombre
	            FROM 
                    gen_tipo_acciones 
                ORDER BY nombre ASC";

    try{

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $roles = $ejecutar->fetchAll(PDO::FETCH_OBJ);

         $db = null;

        echo json_encode($roles);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/rol/{id}', function(Request $request, Response $response){

    $id_rol = $request->getAttribute('id');
    $consulta = "SELECT * FROM seg_roles WHERE idSegRol = $id_rol";

    try{

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $rol = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($rol);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->post('/api/rol', function(Request $request, Response $response){
    
    $codigo = $request->getParam("codigo");
    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $estatus = $request->getParam("estatus");
    $idmenu = $request->getParam("idSegMenu");
    $auditable = $request->getParam("auditable");
   
   $consulta = "INSERT INTO seg_roles
                    (
                        codigo,
                        nombre,
                        descripcion,
                        estatus,
                        auditable,
                        IdSegMenu
                    )
                    VALUES
                    (
                        :codigo,
                        :nombre,
                        :descripcion,
                        :estatus,
                        :auditable,
                        :idmenu
                    )
                ";

    try{

        $db = new db();
        $db = $db->conectar();



        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":codigo", $codigo);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":estatus", $estatus);
        $sentencia->bindParam(":idmenu", $idmenu);
        $sentencia->bindParam(":auditable", $auditable);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $rol = array('ObjectId' => $id_insertado);
        echo json_encode($rol);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->post('/api/rolesprocess', function(Request $request, Response $response){
    
    //Asi deberia ser...pasar JSON mandar JSON....
    $json = $request->getBody();
    $data = json_decode($json, true);  

   // $body = json_decode($request->getBody());
    
    $result = "pasaste esto: " . $data['algo'];

    $response->getBody()->write(json_encode($result));
    return $response ->withHeader('Content-Type', 'application/json')
                        ->withStatus(201);

   
});

$app->put('/api/rol/{id}', function(Request $request, Response $response){
    
    $id_rol = $request->getAttribute('id');
    
    $codigo = $request->getParam("codigo");
    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $estatus = $request->getParam("estatus");
    $idmenu = $request->getParam("idSegMenu");
    $auditable = $request->getParam("auditable");
   
   $consulta = "UPDATE seg_roles SET
                    codigo = :codigo,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    estatus = :estatus,
                    auditable = :auditable,
                    idSegMenu = :idmenu
                WHERE idSegRol = $id_rol";

    try{

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":codigo", $codigo);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":estatus", $estatus);
        //$sentencia->bindParam(":id_rol", $id_rol);
        $sentencia->bindParam(":idmenu", $idmenu);
        $sentencia->bindParam(":auditable", $auditable);

        $sentencia->execute();

        
        $db = null;

        echo '{"message": {"text": "Rol actualizado correctamente"}}';  

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->delete('/api/rol/{id}', function(Request $request, Response $response){
    
    $id_rol = $request->getAttribute('id');
   
   $consulta = "DELETE FROM seg_roles WHERE idSegRol = $id_rol";

    try{

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        
        $db = null;

        echo '{"message": {"text": "Rol eliminado correctamente"}}';   

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});



$app->get('/api/userLocalStorage/obtenerRoles/{id}', function(Request $request, Response $response){

    $idUser = $request->getAttribute('id');

    $consulta = "CALL obtenerRolesLocalStorage(:idUser)";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        $db = null;
        
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

?>