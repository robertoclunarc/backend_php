<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/perfilusuario', function (Request $request, Response $response) {

    $idusuario = $request->getParam('idSegUsuario');
    $idusr = $request->getParam('idSegPerfil');

    $consulta = "INSERT INTO seg_perfiles_usuarios
                    (
                        idSegUsuario,
                        idSegPerfil
                    )
                VALUES
                    (
                        :idusuario,
                        :idusr
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idusuario', $idusuario);
        $stmt->bindParam(':idusr', $idusr);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/perfilusuario/{idSegPerfil}/{idSegUsuario}', function (Request $request, Response $response) {

    //$idusuario = $request->getParam('idSegUsuario');
    //$idusr = $request->getParam('idSegPerfil');

    $idusr = $request->getAttribute('idSegPerfil');
    $idusuario = $request->getAttribute('idSegUsuario');

    $consulta = "DELETE FROM seg_perfiles_usuarios WHERE idSegPerfil = $idusr AND idSegUsuario = $idusuario";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Perfil per eliminado "}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/perfilesusuarios/{idSegUsuario}', function (Request $request, Response $response) {

    $idusr = $request->getAttribute('idSegUsuario');

    $consulta = "SELECT per.nombre AS nombrePerfil,
	                    per.codigo AS codigoPerfil,
	                    per.idSegPerfil,
	                    usrs.usuario AS nombreUsr,
	                    usrs.idSegUsuario
                FROM seg_perfiles per
                JOIN seg_perfiles_usuarios perUsr ON per.idSegPerfil = perUsr.idSegPerfil
                JOIN seg_usuarios usrs ON usrs.idSegUsuario = perUsr.idSegUsuario
                WHERE usrs.idSegUsuario = $idusr";

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

$app->get('/api/noperfilesusuario/{idSegPerfil}', function (Request $request, Response $response) {

    $idusr = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT per.nombre AS nombreper, 
	                    per.codigo AS codigoper,
	                    per.idSegPerfil
                FROM seg_perfiles per
                LEFT JOIN (SELECT idSegUsuario, idSegPerfilUsuario, idSegPerfil FROM seg_perfiles_usuarios WHERE idSegUsuario= $idusr) perusr
	            ON perusr.idSegPerfil = per.idSegPerfil
                WHERE perusr.idSegPerfilUsuario IS NULL
                AND per.estatus = 1";

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

$app->get('/api/porperfil/{idSegPerfil}', function (Request $request, Response $response) {

    $idperfil = $request->getAttribute('idSegPerfil');

    $consulta = "SELECT *
                FROM seg_perfiles_usuarios per
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



