<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/areastrabajo', function (Request $request, Response $response) {

    $consulta = "SELECT 
                        atg.*,
                        g.nombre as gerencia 
                    FROM 
                        adm_areas_trabajo_gerencias atg
                        INNER JOIN
                        config_gerencias g
                        ON
                        g.idConfigGerencia = atg.idConfigGerencia
                    ORDER BY
                        atg.idConfigGerencia,
                        atg.idAdmAreaTrabajoGerencia
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

$app->get('/api/areastrabajo/{id}', function (Request $request, Response $response) {

    $id_area = $request->getAttribute('id');

    
    $consulta = "SELECT * FROM adm_areas_trabajo_gerencias WHERE idAdmAreaTrabajoGerencia = $id_area";

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


$app->post('/api/areastrabajo', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $id_gerencia = $request->getParam("idConfigGerencia");


    $consulta = "INSERT INTO adm_areas_trabajo_gerencias
                    (
                        nombre,
                        idConfigGerencia
                    )
                    VALUES
                    (
                        :nombre,
                        :id_gerencia
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":id_gerencia", $id_gerencia);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $area = array('ObjectId' => $id_insertado);
        $response->withJson($area);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

/* $app->get('/api/areasporproducto/{idConfigGerencia}/{codigo}', function (Request $request, Response $response) {

    $codigoProducto = $request->getAttribute('codigo');
    $idConfigGerencia = $request->getAttribute('idConfigGerencia');

    $consulta = "SELECT areas.idAreaTrabajo,
	                    areas.nombre,
	                    apli.idAreaTrabajoGerencia as idConfigGerencia
	
                FROM adm_productos p
                INNER JOIN adm_aplicabilidad_producto apli ON apli.idAdmProductoPadre = p.idAdmProducto
                INNER JOIN adm_areas_trabajo areas ON areas.idAreaTrabajo = apli.idAreaTrabajoGerencia
                WHERE p.codigo = '$codigoProducto' 
                AND apli.idConfigGerencia = $idConfigGerencia";
                
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
}); */

$app->put('/api/areastrabajo/{id}', function (Request $request, Response $response) {

    $id_area = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $id_gerencia = $request->getParam("idConfigGerencia");

    $consulta = "UPDATE adm_areas_trabajo_gerencias 
                    SET
                        nombre = :nombre,
                        idConfigGerencia = :id_gerencia
                     WHERE 
                        idAdmAreaTrabajoGerencia = :id_area";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":id_gerencia", $id_gerencia);

        $sentencia->bindParam(":id_area", $id_area);

        $sentencia->execute();

        echo '{"message": {"text": "Area de Trabajo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/areastrabajo/{id}', function (Request $request, Response $response) {

    $id_area = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_areas_trabajo_gerencias WHERE idAdmAreaTrabajoGerencia = $id_area";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Area de Trabajo eliminada correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});



