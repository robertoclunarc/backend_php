<?php

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/relacion_areas_gerencia', function (Request $request, Response $response) {

    $sql = "SELECT * FROM adm_areas_relacion_gerencia";
    //$sql = "SELECT relacionATG.*,
    //(SELECT nombre FROM adm_areas_trabajo areas WHERE  areas.idAreaTrabajo = relacionATG.idAreaTrabajo) AS nombre_area,
	//(SELECT nombre FROM config_gerencias gerencias WHERE gerencias.idConfigGerencia = relacionATG.idConfigGerencia) AS nombre_gerencia

//FROM `adm_areas_relacion_gerencia` relacionATG";

    try {

        $db = new db();
        $db = $db->conectar();
        $ejecutar = $db->query($sql);
        $resultado = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($resultado);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/relacion_areas_gerencia/{idAreaTrabajo}', function (Request $request, Response $response) {
    $idArea = $request->getAttribute('idAreaTrabajo');
    $sql = "SELECT * FROM adm_areas_relacion_gerencia WHERE  idAreaTrabajo = $idArea";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0) {
            $areas = $resultado->fetchAll(PDO::FETCH_OBJ);

            echo json_encode($areas);

        } else {
            echo json_encode("no existe areas relacionadas");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' .$e->getMessage() . '}';
    }   

});

$app->post('/api/relacion_areas_gerencia', function (Request $request, Response $response) {

    $idGerencia = $request->getParam('idConfigGerencia');
    $idArea = $request->getParam('idAreaTrabajo');

    $sql = "INSERT INTO adm_areas_relacion_gerencia (IdConfigGerencia, idAreaTrabajo) VALUES ( :idGerencia, :idArea )";

    try {

        $db = new db();
        $db = $db->conectar();

        $resultado = $db->prepare($sql);
        $resultado->bindParam(':idGerencia', $idGerencia);
        $resultado->bindParam(':idArea', $idArea);

        $resultado->execute();
        $nuevoId = $db->lastInsertId();
        $db = null;

        $relacion = array('ObjectId' => $nuevoId);
        $response->withJson($relacion);
        //echo json_decode($relacion);

    } catch (PDOException $error) {
        echo '{"error: {"text":' . $error->getMessage() . '}}';
    }
});
/*
$app->put('/api/relacion_areas_gerencia/{idAreaTrabajo}', function(Request $request, Response $response) {
     $id = $request->getAttribute('idAreaTrabajo');
     $idGerencia = $request->getParam('idConfigGerencia');

     $sql ="UPDATE adm_areas_relacion_gerencia SET idConfigGerencia WHERE idAreaTrabajo = $id";

     try {
     $db = new db();
     $db = $db->conectar();

     $resultado = $db->prepare($sql);

     $resultado->bindParam(':idGerencia', $idGerencia);
     

     $resultado->execute();
     $response->withJson($resultado);

     $resultado = null;
     $db = null;

     //echo '{"message": {"text": actualizado correctamente"}}';

 } catch (PDOException $e) {
     echo '{"error" : {"text":' . $e->getMessage() . '}';
 }
});*/

$app->put('/api/relacion_areas_gerencia/{idAreaRelacionGerencia}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idAreaRelacionGerencia');
    $idGerencia = $request->getParam('idConfigGerencia');
    $idArea = $request->getParam('idAreaTrabajo');


    $sql = "UPDATE adm_areas_relacion_gerencia 
                SET
                    idConfigGerencia = :idGerencia,
                    idAreaTrabajo = :idArea
                WHERE  idAreaRelacionGerencia = $id";
    try {
        $db = new db();
        $db = $db->conectar();

        $resultado = $db->prepare($sql);

        $resultado->bindParam(':idGerencia', $idGerencia);
        $resultado->bindParam(':idArea', $idArea);

        $resultado->execute();
        $response->withJson($resultado);

        $resultado = null;
        $db = null;

        //echo '{"message": {"text": actualizado correctamente"}}';

    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

/*$app->delete('/api/relacion_areas_gerencia/{idAreaRelacionGerencia}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idAreaRelacionGerencia');

    $sql = "DELETE FROM adm_areas_relacion_gerencia WHERE idAreaRelacionGerencia = $id";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("Registro $id Eliminado.");
        } else {
            echo json_encode("no existe registro en la bbdd");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $error) {
        echo '{"error" : {"text":' . $error->getMessage() . '}';
    }
});*/


$app->delete('/api/relacion_areas_gerencia/{idAreaTrabajo}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idAreaTrabajo');

    $sql = "DELETE FROM adm_areas_relacion_gerencia WHERE idAreaTrabajo = $id";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("Registro $id Eliminado.");
        } else {
            echo json_encode("no existe registro en la bbdd");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $error) {
        echo '{"error" : {"text":' . $error->getMessage() . '}';
    }
});