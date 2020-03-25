<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/parametros', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM config_parametros_sistema";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->patch('/api/parametros', function (Request $request, Response $response) {

    $tiempoEsperaPanelNotificacion = $request->getParam('tiempoEsperaPanelNotificacion');
    $tiempoEsperaRecibirNotificacion = $request->getParam('tiempoEsperaRecibirNotificacion');
    $dirServidor = $request->getParam('dirServidor');
    $tiempoActualizacionRoles = $request->getParam('tiempoActualizacionRoles');


    $consulta = "UPDATE config_parametros_sistema SET

                        tiempoEsperaPanelNotificacion  = :tiempoEsperaPanelNotificacion,
                        tiempoEsperaRecibirNotificacion = :tiempoEsperaRecibirNotificacion,
                        dirServidor = :dirServidor, 
                        tiempoActualizacionRoles = :tiempoActualizacionRoles
                        ";
    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':tiempoEsperaPanelNotificacion', $tiempoEsperaPanelNotificacion);
        $stmt->bindParam(':tiempoEsperaRecibirNotificacion', $tiempoEsperaRecibirNotificacion);
        $stmt->bindParam(':dirServidor', $dirServidor);
        $stmt->bindParam(':tiempoActualizacionRoles', $tiempoActualizacionRoles);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Parametros actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/parametros/{id}', function (Request $request, Response $response) {

    $idSegMenu = $request->getAttribute('id');

    $consulta = "DELETE FROM seg_menus WHERE idSegMenu = $idSegMenu";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Nodo del Menú eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
