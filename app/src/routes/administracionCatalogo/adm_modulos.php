<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/modulos', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM adm_modulos order by orden";

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


$app->get('/api/modulos/{id}', function (Request $request, Response $response) {

    $id_modulo = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_modulos WHERE idAdmModulo = $id_modulo";

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


$app->post('/api/modulos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");


    $consulta = "INSERT INTO adm_modulos
                    (
                        nombre,
                        orden
                    )
                    VALUES
                    (
                        :nombre,
                        :orden
                    )";
    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $modulo = array('ObjectId' => $id_insertado);
        $response->withJson($modulo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/modulos/{id}', function (Request $request, Response $response) {

    $id_modulo = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");


    $consulta = "UPDATE adm_modulos
                    SET
                        nombre = :nombre,
                        orden = :orden
                 WHERE 
                    idAdmModulo = :id_modulo";
    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_modulo", $id_modulo);

        $sentencia->execute();

        echo '{"message": {"text": "Modulo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/modulos/{id}', function (Request $request, Response $response) {

    $id_modulo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_modulos WHERE idAdmModulo = $id_modulo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Modulo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
