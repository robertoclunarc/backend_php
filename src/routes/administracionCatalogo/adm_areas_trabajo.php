<?php

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/areas_trabajo', function (Request $request, Response $response) {

    $sql = "SELECT a.*, b.nombre as areaNegocio FROM adm_areas_trabajo a 
            LEFT JOIN gen_area_negocio b ON a.idGenAreaNegocio = b.idGenAreaNegocio";

    try {

        $db = new db();
        $db = $db->conectar();
        $ejecutar = $db->query($sql);
        $resultado = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($resultado);

    } catch (PDOException $error) {
        echo '{"error": {"text": ' . $error->getMessage()() .'}}';
        }
});

$app->post('/api/areas_trabajo', function (Request $request, Response $response) {

    $nombre = $request->getParam('nombre');
    $idGenAreaNegocio = $request->getParam('idGenAreaNegocio');

    $sql = "INSERT INTO adm_areas_trabajo (nombre, idGenAreaNegocio) VALUES (:nombre, :idGenAreaNegocio)";

    try {
        
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
       
        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':idGenAreaNegocio', $idGenAreaNegocio);

        $resultado->execute();
        $nuevoId = $db->lastInsertId();
        $db = null;

        $area = array('ObjectId' => $nuevoId);
        $response->withJson($area);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }

});

$app->get('/api/areasporproducto/{idConfigGerencia}/{codigo}', function (Request $request, Response $response) {

    $codigoProducto = $request->getAttribute('codigo');
    $idConfigGerencia = $request->getAttribute('idConfigGerencia');

    $consulta = "SELECT areas.idAreaTrabajo,
	                    areas.nombre,
	                    apli.idAreaTrabajoGerencia as idConfigGerencia,
                        areas.idGenAreaNegocio,
                        areas.fechaAlta
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
});

$app->put('/api/areas_trabajo/{idAreaTrabajo}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idAreaTrabajo');
    $nombre = $request->getParam('nombre'); 
    $idGenAreaNegocio = $request->getParam('idGenAreaNegocio'); 

    $sql = "UPDATE adm_areas_trabajo SET nombre = :nombre, idGenAreaNegocio = :idGenAreaNegocio WHERE idAreaTrabajo = $id";

    try {
        $db = new db();
        $db = $db->conectar();

        $resultado = $db->prepare($sql);
        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':idGenAreaNegocio', $idGenAreaNegocio);
        $resultado->execute();
        $response->withJson($resultado);

        $resultado = null;
        $db = null;
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/areas_trabajo/{idAreaTrabajo}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idAreaTrabajo');

    $sql = "DELETE FROM adm_areas_trabajo WHERE idAreaTrabajo = $id";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("Area de trabajo eliminada");
        } else {
            echo json_encode("no existe el registro en la bbdd");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    
    }

});