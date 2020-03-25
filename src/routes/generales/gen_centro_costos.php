<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/centrocostos', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM gen_centro_costos";

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

$app->get('/api/centrocostos/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM gen_centro_costos WHERE idGenCentroCostos = $id";

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

$app->get('/api/centrocostosempregerencia/{idEmpre}/{idGerencia}', function (Request $request, Response $response) {

    $idEmpresa = $request->getAttribute('idEmpre');
    $idGerencia = $request->getAttribute('idGerencia');

    $consulta = "SELECT cc.*, ecg.*
                 FROM gen_centro_costos cc
                 INNER JOIN gen_empre_cc_gerencia ecg ON cc.idGenCentroCostos = ecg.idGenCentroCostos
                WHERE ecg.IdComprasEmpresa = $idEmpresa AND ecg.idGerencia = $idGerencia
                GROUP BY cc.idGenCentroCostos";

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

$app->post('/api/centrocostos', function (Request $request, Response $response) {


    $codigo         = $request->getParam('codigo');
    $descripcion    = $request->getParam('descripcion');
    $observaciones    = $request->getParam('observaciones');

    $consulta = "INSERT INTO gen_centro_costos 
                    (   
                        codigo,
                        descripcion,
                        observaciones                    
                    ) 
                VALUES 
                    (   
                        :codigo,
                        :descripcion,
                        :observaciones
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':observaciones', $observaciones);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post("/api/empreccgerencia", function (Request $request, Response $response) {

    $idGerencia = $request->getParam("idGerencia");
    $idGenCentroCostos = $request->getParam("idGenCentroCostos");
    $IdComprasEmpresa = $request->getParam("IdComprasEmpresa");

    try {

        $db = new db();
        $db = $db->conectar();
        //$db = new PDO("mysql:host=localhost;dbname=intranet","root", "");

        $consulta = "INSERT INTO gen_empre_cc_gerencia 
                        (
                        idGerencia, 
                        idGenCentroCostos, 
                        IdComprasEmpresa
                        )
                        VALUES
                        (                           
                            :idGerencia, 
                            :idGenCentroCostos, 
                            :IdComprasEmpresa
                        )";

        $sentencia  = $db->prepare($consulta);
        $sentencia->bindParam("idGerencia", $idGerencia);
        $sentencia->bindParam("idGenCentroCostos", $idGenCentroCostos);
        $sentencia->bindParam("IdComprasEmpresa", $IdComprasEmpresa);

        $sentencia->execute();

        $ccempre = array("ObjectId"=> $db->lastInsertId());

        echo json_encode($ccempre);

        $db = null;

    } catch (PDOException $error) {
        echo "{error:" . $error->getMessage() . "}";
    }
});

$app->put('/api/centrocostos/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $codigo         = $request->getParam('codigo');
    $descripcion    = $request->getParam('descripcion');
    $observaciones    = $request->getParam('observaciones');

    $consulta = "UPDATE gen_centro_costos SET 
                        
                        codigo = :codigo,
                        descripcion = :descripcion,
                        observaciones = :observaciones
                        
                        WHERE idGenCentroCostos = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':observaciones', $observaciones);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Centro de costos actualizado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/centrocostos/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "DELETE FROM gen_centro_costos WHERE idGenCentroCostos = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Centro de costos eliminado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
