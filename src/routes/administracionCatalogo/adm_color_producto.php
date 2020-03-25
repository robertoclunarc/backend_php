<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/colores', function (Request $request, Response $response) {

    $consulta = "SELECT c.*, c.nombre as label, c.idAdmColorProducto as value FROM adm_color_producto c order by c.idAdmColorProducto";

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

$app->get('/api/colores/{id}', function (Request $request, Response $response) {

    $id_color = $request->getAttribute('id');

    
    $consulta = "SELECT * FROM adm_color_producto WHERE idAdmColorProducto= $id_color";

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

$app->post('/api/colores', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");


    $consulta = "INSERT INTO adm_color_producto
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

        $color = array('ObjectId' => $id_insertado);
        $response->withJson($color);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/colores/{id}', function (Request $request, Response $response) {

    $id_color = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");

    $consulta = "UPDATE adm_color_producto SET
                    nombre = :nombre,
                    orden = :orden
                WHERE 
                    idAdmColorProducto = :id_color";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_color", $id_color);

        $sentencia->execute();

        echo '{"message": {"text": "Color actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/colores/{id}', function (Request $request, Response $response) {

    $id_color = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_color_producto WHERE idAdmColorProducto = $id_color";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Color eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
