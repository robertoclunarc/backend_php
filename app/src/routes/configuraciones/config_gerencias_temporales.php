<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/gerenciastemp', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM config_gerencias_temporales g";

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



$app->get('/api/gerenciastempusuario/{idUsuario}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idUsuario');

    $consulta = "SELECT gere.idConfigGerencia,
	                    gere.nombre
                FROM config_gerencias_temporales gtemp
                INNER JOIN config_gerencias gere ON gtemp.idConfigGerencia = gere.idConfigGerencia
                WHERE gtemp.idSegUsuario = $id";

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

$app->get('/api/gerenciastempnousuario/{idUsuario}/{idcargo}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idUsuario');
    $idcargo = $request->getAttribute('idcargo');

    $consulta = "SELECT gere.idConfigGerencia,
	                    gere.nombre	
                 FROM config_gerencias gere
                 LEFT JOIN (SELECT idSegUsuario, idConfigGerencia FROM config_gerencias_temporales t WHERE t.idSegUsuario = $id) tge
	                    ON gere.idConfigGerencia = tge.idConfigGerencia
                 WHERE tge.idSegUsuario IS NULL
                 AND gere.idConfigGerencia <> (SELECT c.idConfigGerencia FROM config_cargos c WHERE idConfigCargo = $idcargo)";

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

$app->post('/api/gerenciastemp', function (Request $request, Response $response) {

    $idSegUsuario = $request->getParam('idSegUsuario');
    $idConfigGerencia = $request->getParam('idConfigGerencia');

    $consulta = "INSERT INTO config_gerencias_temporales
                    (
                        idSegUsuario,
                        idConfigGerencia
                    )
                VALUES
                    (
                        :idSegUsuario,
                        :idConfigGerencia
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idConfigGerencia', $idConfigGerencia);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/gerenciastemp/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');

    $consulta = "UPDATE config_gerencias SET

                        nombre          =  :nombre,
                        descripcion     =  :descripcion

                    WHERE idConfigGerencia = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia actualizada correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/gerenciastemp/{idSegUsuario}/{idConfigGerencia}', function (Request $request, Response $response) {

    $idSegUsuario = $request->getAttribute('idSegUsuario');
    $idConfigGerencia = $request->getAttribute('idConfigGerencia');

    $consulta = "DELETE FROM config_gerencias_temporales WHERE idSegUsuario = $idSegUsuario AND idConfigGerencia = $idConfigGerencia";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Gerencia Temporal eliminada correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
