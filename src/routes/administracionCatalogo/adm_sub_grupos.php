<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;



$app->get('/api/subgrupos', function (Request $request, Response $response) {

    $consulta = "SELECT 
                        sg.*, 
                        sg.nombre as label, 
                        sg.idAdmSubGrupoProducto as value , 
                        g.nombre as grupo
                        FROM 
                            adm_sub_grupos_productos sg 
                        LEFT JOIN
                            adm_grupos_productos g
                        ON 
                            g.idAdmGrupoProducto = sg.idAdmGrupoProducto
                        order by sg.idAdmGrupoProducto, sg.idAdmSubGrupoProducto   
                ";

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


$app->get('/api/subgrupos/{id}', function (Request $request, Response $response) {

    $id_subgrupo = $request->getAttribute('id');

    $consulta = "SELECT * FROM adm_sub_grupos_productos WHERE idAdmSubGrupoProducto = $id_subgrupo";

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



$app->post('/api/subgrupos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");
    $id_grupo = $request->getParam("idAdmGrupoProducto");


    $consulta = "INSERT INTO adm_sub_grupos_productos
                    (
                        nombre,
                        orden,
                        idAdmGrupoProducto
                    )
                    VALUES
                    (
                        :nombre,
                        :orden,
                        :idAdmGrupo
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":idAdmGrupo", $id_grupo);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $grupo = array('ObjectId' => $id_insertado);
        $response->withJson($grupo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/subgrupos/{id}', function (Request $request, Response $response) {

    $id_subgrupo = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");
    $id_grupo = $request->getParam("idAdmGrupoProducto");

    $consulta = "UPDATE adm_sub_grupos_productos 
                    SET
                        nombre = :nombre,
                        orden = :orden,
                        idAdmGrupoProducto = :idAdmGrupo
                 WHERE 
                    idAdmSubGrupoProducto = :id_subgrupo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":idAdmGrupo", $id_grupo);
        $sentencia->bindParam(":id_subgrupo", $id_subgrupo);

        $sentencia->execute();

        echo '{"message": {"text": "SubGrupo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/subgrupos/{id}', function (Request $request, Response $response) {

    $id_subgrupo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_sub_grupos_productos WHERE idAdmSubGrupoProducto = $id_subgrupo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "SubGrupo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});





