<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\RequestInterface as Request;

$app->get ('/api/config_gerencias', function (Request  $request, Response $response) {

    $sql = "SELECT * FROM config_gerencias";

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

$app->post('/api/config_gerencias', function (Request $request, Response $response){
    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');

    $sql = "INSERT INTO config_gerencias (nombre, descripcion) VALUES (:nombre, :descripcion)";

    try {
        $db = new db();
        $db = $db->conectar();

        $resultado= $db->prepare($sql);
        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':descripcion', $descripcion);

        $resultado->execute();
        $nuevagcia = $db->lastInsertId();
        $db = null;

        $gerencia = array('ObjectId' => $nuevagcia);
        $response->withJson($gerencia);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
    
});

$app->put('/api/config_gerencias/{idConfigGerencia}', function (Request $request, Response $response) {
    $id = $request->getAttribute('idConfigGerencia');

    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');

    $sql = "UPDATE config_gerencias SET 
                nombre = :nombre,
                descripcion = :descripcion 
            WHERE idConfigGerencia = $id";
    try {
        $db = new db();
        $db = $db->conectar();
        
        $resultado = $db->prepare($sql);
        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':descripcion', $descripcion);

        $resultado->execute();
        $response->withJson($resultado);

        $resultado = null;
        $db = null;

        } catch (PDOException $error) {
            echo '{"error": {"text":' . $error->getMessage() . '}}';
        }
});

$app->delete('/api/config_gerencias/{idConfigGerencia}', function ( Request $request, Response $response) {
    $id = $request->getAttribute('idConfigGerencia');

    $sql = "DELETE FROM config_gerencias WHERE idConfigGerencia = $id";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("REGISTRO ELIMINADO");
        } else {
            echo json_encode("no existe el registro en bbdd");
        }
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }

});