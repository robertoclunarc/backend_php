<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/propiedades', function (Request $request, Response $response) {

    $consulta = "SELECT 
                        p.* 
                    FROM 
                        adm_propiedades p 
                    ORDER BY 
                        p.idAdmPropiedad";

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

$app->get('/api/propiedades/{id}', function (Request $request, Response $response) {

    $id_propiedad = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_propiedades WHERE idAdmPropiedad = $id_propiedad";

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

$app->post('/api/propiedades', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");

    $consulta = "INSERT INTO adm_propiedades
                    (
                        nombre
                    )
                    VALUES
                    (
                        :nombre
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $propiedad = array('ObjectId' => $id_insertado);
        $response->withJson($propiedad);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/propiedades/{id}', function (Request $request, Response $response) {

    $id_propiedad = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");


    $consulta = "UPDATE adm_propiedades 
                    SET
                        nombre  = :nombre
                 WHERE 
                 idAdmPropiedad = :id_propiedad";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":id_propiedad", $id_propiedad);

        $sentencia->execute();

        echo '{"message": {"text": "Propiedad actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/propiedades/{id}', function (Request $request, Response $response) {

    $idAdmPropiedad = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_propiedades WHERE idAdmPropiedad = $idAdmPropiedad";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Propiedad eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});