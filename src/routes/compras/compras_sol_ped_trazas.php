<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/solpedtraza', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM compras_traza_solped";

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

$app->get('/api/solpedtraza/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM compras_traza_solped WHERE idTrazaSolped = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user[0]);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/solpedtrazaporsol/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM compras_traza_solped WHERE idSolpedCompras = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user[0]);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/solpedtraza', function (Request $request, Response $response) {
    $justificacion      = $request->getParam('justificacion');
    $idSolpedCompras   = $request->getParam('idSolpedCompras');
    $idEstadoSolped     = $request->getParam('idEstadoSolped');
    $estadoActual     = $request->getParam('estadoActual');
    $idSegUsuario       = $request->getParam('idSegUsuario');
    $estadoAnterior     = $request->getParam('estadoAnterior');


    $consulta = "INSERT INTO compras_traza_solped
                    (   
                        justificacion,
                        idSolpedCompras,
                        idEstadoSolped,
                        estadoActual,
                        idSegUsuario,
                        estadoAnterior
                    ) 
                VALUES 
                    (   
                        :justificacion,
                        :idSolpedCompras,
                        :idEstadoSolped,
                        :estadoActual,
                        :idSegUsuario,
                        :estadoAnterior
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idSolpedCompras', $idSolpedCompras);
        $stmt->bindParam(':idEstadoSolped', $idEstadoSolped);
        $stmt->bindParam(':estadoActual', $estadoActual);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':estadoAnterior', $estadoAnterior);
      
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});



$app->put('/api/solpedtraza/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $justificacion     = $request->getParam('justificacion');
    $idSolpedCompras   = $request->getParam('idSolpedCompras');
    $idEstadoSolped   = $request->getParam('idEstadoSolped');
    $idSegUsuario   = $request->getParam('idSegUsuario');
    $estadoAnterior   = $request->getParam('estadoAnterior');

    $consulta = "UPDATE compras_traza_solped SET

                        justificacion = :justificacion,
                        idSolpedCompras = :idSolpedCompras,
                        idEstadoSolped = :idEstadoSolped,
                        idSegUsuario = :idSegUsuario,
                        estadoAnterior = :estadoAnterior
                        
                        WHERE idTrazaSolped = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idSolpedCompras', $idSolpedCompras);
        $stmt->bindParam(':idEstadoSolped', $idEstadoSolped);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':estadoAnterior', $estadoAnterior);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "traza actualizada"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }

});


$app->delete('/api/solpedtraza/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "DELETE FROM compras_traza_solped WHERE idTrazaSolped = $id";

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
