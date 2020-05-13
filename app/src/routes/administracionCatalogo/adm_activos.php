<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/activos', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM adm_activos";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($result);
        
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/activos/gerencia/{idgerencia}', function (Request $request, Response $response) {

    
    $idgerencia = $request->getAttribute("idgerencia");
    $consulta = "SELECT 	tri.idConfigGerencia,
                    act.idAdmActivo, 
                    act.nombre, 
                    act.descripcion, 
                    act.fechaAlta, 
                    act.fechaModificacion, 
                    act.idAdmProducto,
                    act.idComprasEmpresa 
                    FROM 
                    adm_activos act 
                INNER JOIN compras_activo_gerencia_area_negocio tri
                    ON tri.idAdmActivo = act.idAdmActivo
                WHERE tri.idConfigGerencia = $idgerencia GROUP BY act.idAdmActivo";


    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($result);
        
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/activos/{id}', function (Request $request, Response $response) {

    $idAdmActivo = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_activos WHERE idAdmActivo = $idAdmActivo";

    try {

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($result);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/activos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $serial = $request->getParam("serial");
    $idAdmProducto = $request->getParam("idAdmProducto");
    $idComprasEmpresa = $request->getParam("idComprasEmpresa");

    $consulta = "INSERT INTO adm_activos
                    (
                        nombre,
                        descripcion,
                        serial,
                        idAdmProducto,
                        idComprasEmpresa
                    )
                    VALUES
                    (
                        :nombre,
                        :descripcion,
                        :serial,
                        :idAdmProducto,
                        :idComprasEmpresa
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":serial", $serial);
        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idComprasEmpresa", $idComprasEmpresa);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $activo = array('ObjectId' => $id_insertado);
        $response->withJson($activo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/activos/{id}', function (Request $request, Response $response) {

    $idAdmActivo = $request->getAttribute('idAdmActivo');

    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $serial = $request->getParam("serial");
    $idAdmProducto = $request->getParam("idAdmProducto");
    $idComprasEmpresa = $request->getParam("idComprasEmpresa");

    $consulta = "UPDATE adm_activos 
                    SET
                        nombre  = :nombre,
                        descripcion   = :descripcion,
                        serial = :serial,
                        idAdmProducto = :idAdmProducto,
                        idComprasEmpresa = :idComprasEmpresa
                 WHERE 
                 idAdmactivo = :idAdmactivo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":serial", $serial);
        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idAdmactivo", $idAdmactivo);
        $sentencia->bindParam(":idComprasEmpresa", $idComprasEmpresa);

        $sentencia->execute();

        echo '{"message": {"text": "Activo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/activos/{id}', function (Request $request, Response $response) {

    $idAdmActivo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_activos WHERE idAdmActivo = $idAdmActivo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Activo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});