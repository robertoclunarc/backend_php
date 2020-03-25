<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/perfilmodulo', function (Request $request, Response $response) {

    $idmodulo = $request->getParam('idSegModulo');
    $idperfil = $request->getParam('idSegPerfil');

    $consulta = "INSERT INTO seg_perfiles_modulos
                    (
                        idSegModulo,
                        idSegPerfil
                    )
                VALUES
                    (
                        :idmodulo,
                        :idperfil
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idmodulo', $idmodulo);
        $stmt->bindParam(':idperfil', $idperfil);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/perfilmdulo', function (Request $request, Response $response) {

    $idmodulo = $request->getParam('idSegModulo');
    $idperfil = $request->getParam('idSegPerfil');

    $consulta = "DELETE FROM seg_perfiles_modulos WHERE idSegPerfil = $idperfil AND idSegModulo = $idmodulo";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "modulo perfil eliminado"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});



$app->get('/api/perfilmodulos/{idSegPerfil}', function (Request $request, Response $response) {

    $idperfil = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT per.nombre AS nombrePerfil,
	                    per.codigo AS codigoPerfil,
	                    per.idSegPerfil,
	                    modu.nombre AS nombreModulo,
	                    modu.codigo AS codigoModulo,
	                    modu.idSegModulo
                FROM seg_perfiles per
                JOIN seg_perfiles_modulos perRol ON per.idSegPerfil = perRol.idSegPerfil
                JOIN seg_modulos modu ON modu.idSegModulo = perRol.idSegModulo
                WHERE per.idSegPerfil = $idperfil";

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

$app->get('/api/noperfilmodulos/{idSegPerfil}', function (Request $request, Response $response) {

    $idperfil = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT modu.nombre AS nombreModulo, 
	                    modu.codigo AS codigoModulo,
	                    modu.idSegModulo 
                FROM seg_modulos modu
                LEFT JOIN (SELECT idSegModulo, idSegPerfilModulo FROM seg_perfiles_modulos WHERE idSegPerfil= $idperfil) perRol
	            ON modu.idSegModulo = perRol.idSegModulo
                WHERE perRol.idSegPerfilModulo IS NULL
                AND modu.estatus = $idperfil";

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



