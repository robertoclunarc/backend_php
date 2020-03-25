<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/perfilrol', function (Request $request, Response $response) {

    $idrol = $request->getParam('idSegRol');
    $idperfil = $request->getParam('idSegPerfil');

    $consulta = "INSERT INTO seg_roles_perfiles
                    (
                        idSegRol,
                        idSegPerfil
                    )
                VALUES
                    (
                        :idrol,
                        :idperfil
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idrol', $idrol);
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

$app->delete('/api/perfilrol/{idSegPerfil}/{idSegRol}', function (Request $request, Response $response) {

    //$idrol = $request->getParam('idSegRol');
    //$idperfil = $request->getParam('idSegPerfil');

    $idperfil = $request->getAttribute('idSegPerfil');
    $idrol = $request->getAttribute('idSegRol');

    $consulta = "DELETE FROM seg_roles_perfiles WHERE idSegPerfil = $idperfil AND idSegRol = $idrol";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Perfil rol eliminado "}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/perfilroles/{idSegPerfil}', function (Request $request, Response $response) {

    $idperfil = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT per.nombre AS nombrePerfil,
	                    per.codigo AS codigoPerfil,
	                    per.idSegPerfil,
	                    rol.nombre AS nombreRol,
	                    rol.codigo AS codigoRol,
	                    rol.idSegRol
                FROM seg_perfiles per
                JOIN seg_roles_perfiles perRol ON per.idSegPerfil = perRol.idSegPerfil
                JOIN seg_roles rol ON rol.idSegRol = perRol.idSegRol
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

$app->get('/api/noperfilroles/{idSegPerfil}', function (Request $request, Response $response) {

    $idperfil = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT rol.nombre AS nombreRol, 
	                    rol.codigo AS codigoRol,
	                    rol.idSegRol 
                FROM seg_roles rol
                LEFT JOIN (SELECT idSegRol, idSegRolPerfil FROM seg_roles_perfiles WHERE idSegPerfil= $idperfil) perRol
	            ON rol.idSegRol = perRol.idSegRol
                WHERE perRol.idSegRolPerfil IS NULL
                AND rol.estatus = 1";

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
