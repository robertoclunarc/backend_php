<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/usuariorol', function (Request $request, Response $response) {

    $idrol = $request->getParam('idSegRol');
    $idusuario = $request->getParam('idSegUsuario');

    $consulta = "INSERT INTO seg_roles_usuarios
                    (
                        idSegRol,
                        idSegUsuario
                    )
                VALUES
                    (
                        :idrol,
                        :idusuario
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idrol', $idrol);
        $stmt->bindParam(':idusuario', $idusuario);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $usuarios = array('ObjectId' => $id);
        echo json_encode($usuarios);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/usuariorol/{idSegUsuario}/{idSegRol}', function (Request $request, Response $response) {

    $idusuario = $request->getAttribute('idSegUsuario');
    $idrol = $request->getAttribute('idSegRol');

    $consulta = "DELETE FROM seg_roles_usuarios WHERE idSegUsuario = $idusuario AND idSegRol = $idrol";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Usuario rol eliminado "}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/usuarioroles/{idSegUsuario}', function (Request $request, Response $response) {

    $idusuario = $request->getAttribute('idSegUsuario');

        $consulta = "SELECT per.usuario AS nombreUsuario,
	                    per.idSegUsuario,
	                    rol.nombre AS nombreRol,
	                    rol.codigo AS codigoRol,
	                    rol.idSegRol
                FROM seg_usuarios per
                JOIN seg_roles_usuarios perRol ON per.idSegUsuario = perRol.idSegUsuario
                JOIN seg_roles rol ON rol.idSegRol = perRol.idSegRol
                WHERE per.idSegUsuario = $idusuario";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $usuarios = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($usuarios);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/usuarios-por-roles/{codigoRol}', function (Request $request, Response $response) {

    $codigoRol = $request->getAttribute('codigoRol');

        $consulta = "SELECT per.usuario AS nombreUsuario,
	                    per.idSegUsuario,
	                    rol.nombre AS nombreRol,
	                    rol.codigo AS codigoRol,
	                    rol.idSegRol
                FROM seg_usuarios per
                JOIN seg_roles_usuarios perRol ON per.idSegUsuario = perRol.idSegUsuario
                JOIN seg_roles rol ON rol.idSegRol = perRol.idSegRol
                WHERE rol.codigo = '$codigoRol'";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $usuarios = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($usuarios);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/nousuarioroles/{idSegUsuario}', function (Request $request, Response $response) {

    $idusuario = $request->getAttribute('idSegUsuario');

    $consulta = "SELECT rol.nombre AS nombreRol, 
	                    rol.codigo AS codigoRol,
	                    rol.idSegRol 
                FROM seg_roles rol
                LEFT JOIN (SELECT idSegRol, idSegRolUsuario FROM seg_roles_usuarios WHERE idSegUsuario = $idusuario) perRol
	            ON rol.idSegRol = perRol.idSegRol
                WHERE perRol.idSegRolUsuario IS NULL
                AND rol.estatus = 1";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $usuarios = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($usuarios);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
