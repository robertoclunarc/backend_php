<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/respovalidacion', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM adm_resp_validacion_prod";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($result);
        
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/respovalporprod/{idAdmProducto}', function (Request $request, Response $response) {

    $idAdmProducto = $request->getAttribute('idAdmProducto');

    $consulta = "SELECT * FROM adm_resp_validacion_prod WHERE idAdmProducto = $idAdmProducto";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($result);
        
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/respovalidacion', function (Request $request, Response $response) {

    
    $idAdmProducto = $request->getParam("idAdmProducto");
    $idConfigGerencia = $request->getParam("idConfigGerencia");
    

    $consulta = "INSERT INTO adm_resp_validacion_prod
                    (
                        idAdmProducto,
                        idConfigGerencia
                    )
                    VALUES
                    (
                        :idAdmProducto,
                        :idConfigGerencia
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idConfigGerencia", $idConfigGerencia);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
    
        $db = null;

        $activo = array('ObjectId' => $id_insertado);
        $response->withJson($activo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/respovalidacion/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $idAdmProducto = $request->getParam("idAdmProducto");
    $idConfigGerencia = $request->getParam("idConfigGerencia");

    $consulta = "UPDATE adm_resp_validacion_prod 
                    SET
                        idAdmProducto = :idAdmProducto,
                        idConfigGerencia = :idConfigGerencia
                 WHERE 
                        idAdmResValidacion = :id";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idConfigGerencia", $idConfigGerencia);

        $sentencia->bindParam(":idAdmResValidacion", $id);

        $sentencia->execute();

        echo '{"message": {"text": "Activo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/respovalidacion/{id}', function (Request $request, Response $response) {

    $idAdmResFuncional = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_resp_validacion_prod WHERE idAdmResValidacion = $idAdmResFuncional";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Activo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/respovalporprod/{idAdmProducto}', function (Request $request, Response $response) {

    $idAdmProducto = $request->getAttribute('idAdmProducto');

    $consulta = "DELETE FROM adm_resp_validacion_prod WHERE idAdmProducto = $idAdmProducto";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Activo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});