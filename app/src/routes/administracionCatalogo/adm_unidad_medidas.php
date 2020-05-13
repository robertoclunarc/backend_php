<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/unidadmedidas', function (Request $request, Response $response) {

    $consulta = "SELECT 
                        um.*, 
                        tm.nombre as tipoMedida,
                        um.nombre as label, 
                        um.idAdmUnidadMedida as value 
                    FROM 
                        adm_unidad_medidas um
                        INNER JOIN
                        adm_tipo_medidas tm
                        ON
                        tm.idAdmTipoMedida = um.idAdmTipoMedida 

                    order by tm.idAdmTipoMedida";

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

$app->get('/api/unidadmedidas/{id}', function (Request $request, Response $response) {

    $id_unidad_medida = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_unidad_medidas WHERE idAdmUnidadMedida = $id_unidad_medida";

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

$app->post('/api/unidadmedidas', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $abrev = $request->getParam("abrev");
    $orden = $request->getParam("orden");
    $id_tipo_medida = $request->getParam("idAdmTipoMedida");



    $consulta = "INSERT INTO adm_unidad_medidas
                    (
                        nombre,
                        abrev,
                        orden,
                        idAdmTipoMedida
                    )
                    VALUES
                    (
                        :nombre,
                        :abrev,
                        :orden,
                        :id_tipo_medida
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":abrev", $abrev);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_tipo_medida", $id_tipo_medida);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $unidad_medida = array('ObjectId' => $id_insertado);
        $response->withJson($unidad_medida);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/unidadmedidas/{id}', function (Request $request, Response $response) {

    $id_unidad_medida = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $abrev = $request->getParam("abrev");
    $orden = $request->getParam("orden");
    $id_tipo_medida = $request->getParam("idAdmTipoMedida");

    $consulta = "UPDATE adm_unidad_medidas 
                    SET
                        nombre  = :nombre,
                        abrev   = :abrev,
                        orden = :orden,
                        idAdmTipoMedida = :id_tipo_medida
                 WHERE 
                    idAdmUnidadMedida = :id_unidad_medida";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":abrev", $abrev);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_tipo_medida", $id_tipo_medida);

        $sentencia->bindParam(":id_unidad_medida", $id_unidad_medida);

        $sentencia->execute();

        echo '{"message": {"text": "Unidad de Medida actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/unidadmedidas/{id}', function (Request $request, Response $response) {

    $id_unidad_medida = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_unidad_medidas WHERE idAdmUnidadMedida = $id_unidad_medida";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Unidad de medida eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});