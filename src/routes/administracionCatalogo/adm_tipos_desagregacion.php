<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/tiposdesagregacion', function (Request $request, Response $response) {

    $consulta = "SELECT t.*, t.nombre as label, t.idAdmTipoDesagregacionProducto as value FROM adm_tipos_desagregacion_productos t order by t.orden";

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

$app->get('/api/tiposdesagregacion/{id}', function (Request $request, Response $response) {

    $idAdmTipoDesagregacion = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_tipos_desagregacion_productos WHERE idAdmTipoDesagregacionProducto = $idAdmTipoDesagregacion";

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

$app->post('/api/tiposdesagregacion', function (Request $request, Response $response) {

    $nombre         = $request->getParam("nombre");
    $descripcion    = $request->getParam("descripcion");
    $orden          = $request->getParam("orden");

    $consulta = "INSERT INTO adm_tipos_desagregacion_productos
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

        $tipo_desegregacion = array('ObjectId' => $id_insertado);
        $response->withJson($tipo_desegregacion);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/tiposdesagregacion/{id}', function (Request $request, Response $response) {

    $idAdmTipoDesagregacion = $request->getAttribute('id');

    $nombre         = $request->getParam("nombre");
    $descripcion    = $request->getParam("descripcion");
    $orden          = $request->getParam("orden");

    $consulta = "UPDATE adm_tipos_desagregacion_productos 
                    SET
                        nombre  = :nombre,
                        descripcion   = :descripcion,
                        orden = :orden
                 WHERE 
                    idAdmTipoDesagregacionProducto = :idAdmTipoDesagregacion";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":idAdmTipoDesagregacion", $idAdmTipoDesagregacion);

        $sentencia->execute();

        echo '{"message": {"text": "Tipo de Desegregación actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/tiposdesagregacion/{id}', function (Request $request, Response $response) {

    $idAdmTipoDesagregacion = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_tipos_desagregacion_productos WHERE idAdmTipoDesagregacionProducto = $idAdmTipoDesagregacion";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Tipo de Desegregación eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});