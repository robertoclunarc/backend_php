<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/tiposmedida', function (Request $request, Response $response) {

    $consulta = "SELECT tm.*, tm.nombre as label, tm.idAdmTipoMedida as value FROM adm_tipo_medidas tm order by tm.idAdmTipoMedida";

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

$app->get('/api/tiposmedida/{id}', function (Request $request, Response $response) {

    $id_tipo_medida = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_tipo_medidas WHERE idAdmTipoMedida = $id_tipo_medida";

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


$app->get('/api/tiposmedida/{id}/unidadmedidas', function (Request $request, Response $response) {

    $id_tipo_medida = $request->getAttribute('id');

    $consulta = "SELECT u.*, concat(u.nombre, concat(' ', u.abrev)) as label, u.idAdmUnidadMedida as value FROM adm_unidad_medidas u 
    WHERE u.idAdmTipoMedida = $id_tipo_medida  order by u.orden";
    
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

$app->post('/api/tiposmedida', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $orden = $request->getParam("orden");


    $consulta = "INSERT INTO adm_tipo_medidas
                    (
                        nombre,
                        descripcion,
                        orden
                    )
                    VALUES
                    (
                        :nombre,
                        :descripcion,
                        :orden
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":orden", $orden);


        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $tipo_medida = array('ObjectId' => $id_insertado);
        $response->withJson($tipo_medida);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/tiposmedida/{id}', function (Request $request, Response $response) {

    $id_tipo_medida = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $orden = $request->getParam("orden");



    $consulta = "UPDATE adm_tipo_medidas 
                    SET
                        nombre = :nombre,
                        descripcion = :descripcion,
                        orden = :orden

                 WHERE 
                 idAdmTipoMedida = :id_tipo_medida";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":orden", $orden);


        $sentencia->bindParam(":id_tipo_medida", $id_tipo_medida);

        $sentencia->execute();

        echo '{"message": {"text": "Tipo de Medida actualizada correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/tiposmedida/{id}', function (Request $request, Response $response) {

    $id_tipo_medida= $request->getAttribute('id');

    $consulta = "DELETE FROM adm_tipo_medidas WHERE idAdmTipoMedida = $id_tipo_medida";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Tipo de medida eliminada correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

