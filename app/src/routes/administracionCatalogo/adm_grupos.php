<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/grupos', function (Request $request, Response $response) {

    $consulta = "SELECT g.*, g.nombre as label, g.idAdmGrupoProducto as value FROM adm_grupos_productos g order by g.idAdmGrupoProducto";

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

$app->get('/api/grupos/{id}', function (Request $request, Response $response) {

    $id_grupo = $request->getAttribute('id');

    $consulta = "SELECT g.* FROM adm_grupos_productos g WHERE g.idAdmGrupoProducto = $id_grupo";

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

$app->get('/api/grupos/{id}/subgrupos', function (Request $request, Response $response) {

    $id_grupo = $request->getAttribute('id');

    $consulta = "SELECT 
                        sg.*, 
                        sg.nombre as label, 
                        sg.idAdmSubGrupoProducto as value , 
                        g.nombre as grupo
                        FROM 
                            adm_sub_grupos_productos sg 
                        INNER JOIN
                            adm_grupos_productos g
                        ON 
                            g.idAdmGrupoProducto = sg.idAdmGrupoProducto		
                        
                        WHERE sg.idAdmGrupoProducto = $id_grupo";

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




$app->post('/api/grupos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");

    $consulta = "INSERT INTO adm_grupos_productos
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

        $grupo = array('ObjectId' => $id_insertado);
        $response->withJson($grupo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/grupos/{id}', function (Request $request, Response $response) {

    $id_grupo = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");

    $consulta = "UPDATE adm_grupos_productos 
                    SET
                        nombre = :nombre,
                        orden = :orden
                 WHERE 
                    idAdmGrupoProducto = :id_grupo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_grupo", $id_grupo);

        $sentencia->execute();

        echo '{"message": {"text": "Grupo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/grupos/{id}', function (Request $request, Response $response) {

    $id_grupo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_grupos_productos WHERE idAdmGrupoProducto = $id_grupo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Grupo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});



