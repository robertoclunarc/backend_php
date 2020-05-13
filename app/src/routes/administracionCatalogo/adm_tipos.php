<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/tipos', function (Request $request, Response $response) {

    $consulta = "SELECT t.*, t.nombre as label, t.idAdmTipoClasificacion as value FROM adm_tipos_clasificacion t 
    WHERE idAdmModulo=1 order by t.orden";

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

$app->get('/api/tipos/{id}', function (Request $request, Response $response) {

    $id_tipo = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_tipos_clasificacion WHERE idAdmTipoClasificacion = $id_tipo";

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

$app->get('/api/tipos/{id}/subtipos', function (Request $request, Response $response) {

    $id_tipo = $request->getAttribute('id');

    $consulta = "SELECT s.*, s.nombre as label, s.idAdmSubTipoClasificacion as value 
    FROM adm_sub_tipos_clasificacion s WHERE s.idAdmTipoClasificacion = $id_tipo order by s.orden";

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


$app->post('/api/tipos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $modulo = $request->getParam("idAdmModulo");


    $consulta = "INSERT INTO adm_tipos_clasificacion
                    (
                        nombre,
                        idAdmModulo
                    )
                    VALUES
                    (
                        :nombre,
                        :idAdmModulo
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":idAdmModulo", $modulo);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $grupo = array('ObjectId' => $id_insertado);
        $response->withJson($grupo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/tipos/{id}', function (Request $request, Response $response) {

    $id_tipo = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $modulo = $request->getParam("idAdmModulo");


    $consulta = "UPDATE adm_tipos_clasificacion 
                    SET
                        nombre = :nombre,
                        idAdmModulo = :id_modulo
                 WHERE 
                    idAdmTipoClasificacion = :id_tipo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":id_modulo", $modulo);

        $sentencia->bindParam(":id_tipo", $id_tipo);

        $sentencia->execute();

        echo '{"message": {"text": "Tipo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/tipos/{id}', function (Request $request, Response $response) {

    $id_tipo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_tipos_clasificacion WHERE idAdmTipoClasificacion = $id_tipo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Tipo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});






