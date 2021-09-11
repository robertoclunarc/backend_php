<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/solped', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM compras_solped";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $users = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($users);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/solped/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM compras_solped WHERE idSolpedCompras = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/solpedticket/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM compras_solped WHERE idTicketServicio = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->post('/api/solped', function (Request $request, Response $response) {


    $fechaAOrdenC         = $request->getParam('fechaAOrdenC');
    $fechaRequerida         = $request->getParam('fechaRequerida');
    $idTicketServicio    = $request->getParam('idTicketServicio');
    $idEstadoActual    = $request->getParam('idEstadoActual');
    $estadoactual    = $request->getParam('estadoActual');
    $idSolpedPadre    = $request->getParam('idSolpedPadre');
    $idConfigGerencia    = $request->getParam('idConfigGerencia');
    $idAdmActivo    = $request->getParam('idAdmActivo');
    $descripcion    = $request->getParam('descripcion');
    $idUsuarioRegistro    = $request->getParam('idUsuarioRegistro');
    $justificacion    = $request->getParam('justificacion');

    $consulta = "INSERT INTO compras_solped 
                    (   
                        fechaAOrdenC,
                        idTicketServicio,
                        idEstadoActual,
                        estadoActual  ,
                        idSolpedPadre ,
                        idConfigGerencia,
                        idAdmActivo,
                        descripcion,
                        idUsuarioRegistro,
                        justificacion,
                        fechaRequerida                
                    ) 
                VALUES 
                    (   
                        :fechaAOrdenC,
                        :idTicketServicio,
                        :idEstadoActual,
                        :estadoActual,
                        :idSolpedPadre,
                        :idConfigGerencia,
                        :idAdmActivo,
                        :descripcion,
                        :idUsuarioRegistro,
                        :justificacion,
                        :fechaRequerida
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':fechaAOrdenC', $fechaAOrdenC);
        $stmt->bindParam(':fechaRequerida', $fechaRequerida);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);
        $stmt->bindParam(':estadoActual', $estadoactual);
        $stmt->bindParam(':idSolpedPadre', $idSolpedPadre);
        $stmt->bindParam(':idConfigGerencia', $idConfigGerencia);
        $stmt->bindParam(':idAdmActivo', $idAdmActivo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idUsuarioRegistro', $idUsuarioRegistro);
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->execute();

        $id = $db->lastInsertId();

        //paa colocar la descomposicion del mismo padre
        if (($idSolpedPadre) || ($idSolpedPadre == -1)) {
            $consulta2 = "UPDATE compras_solped SET idSolpedPadre = :id WHERE idSolpedCompras = :id";
            $stmt = $db->prepare($consulta2);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        $cargo = array('ObjectId' => $id);
        $db = null;
        echo json_encode($cargo);
        // $response->getBody()->write($cargo);
        // return $response
        //     ->withHeader('Content-Type', 'application/json')
        //     ->withStatus(201);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
        // $datosError = array('status' => 'error', 'data' => $error->getMessage());
        // $response->getBody()->write($datosError);
        // return $response
        //     ->withHeader('Content-Type', 'application/json')
        //     ->withStatus(400);
    }
});



$app->put('/api/solped/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $fechaAOrdenC         = $request->getParam('fechaAOrdenC');
    $idTicketServicio    = $request->getParam('idTicketServicio');
    $idEstadoActual    = $request->getParam('idEstadoActual');
    $estadoactual    = $request->getParam('estadoActual');


    $consulta = "UPDATE compras_solped SET 
                        
                        fechaAOrdenC = :fechaAOrdenC,
                        idTicketServicio = :idTicketServicio,
                        idEstadoActual = :idEstadoActual,
                        estadoActual = :estadoActual
                        
                        WHERE idSolpedCompras = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':fechaAOrdenC', $fechaAOrdenC);
        $stmt->bindParam(':idTicketServicio', $idTicketServicio);
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);
        $stmt->bindParam(':estadoActual', $estadoactual);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Cargo actualizado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/solped/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "DELETE FROM compras_solped WHERE idSolpedCompras = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Cargo eliminado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
