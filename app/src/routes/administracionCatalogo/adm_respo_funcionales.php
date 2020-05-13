<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/respofuncionales', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM adm_resp_funcional_prod";

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

$app->get('/api/respofunporprod/{idAdmProducto}', function (Request $request, Response $response) {

    $idAdmProducto = $request->getAttribute('idAdmProducto');

    $consulta = "SELECT * FROM adm_resp_funcional_prod WHERE idAdmProducto = $idAdmProducto";

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

$app->post('/api/respofuncionales', function (Request $request, Response $response) {

    
    $idAdmProducto = $request->getParam("idAdmProducto");
    $idConfigGerencia = $request->getParam("idConfigGerencia");
    

    $consulta = "INSERT INTO adm_resp_funcional_prod
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


$app->put('/api/respofuncionales/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $idAdmProducto = $request->getParam("idAdmProducto");
    $idConfigGerencia = $request->getParam("idConfigGerencia");

    $consulta = "UPDATE adm_resp_funcional_prod 
                    SET
                        idAdmProducto = :idAdmProducto,
                        idConfigGerencia = :idConfigGerencia
                 WHERE 
                        idAdmResFuncional = :id";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idConfigGerencia", $idConfigGerencia);

        $sentencia->bindParam(":idAdmactivo", $id);

        $sentencia->execute();

        echo '{"message": {"text": "Activo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/respofuncionales/{id}', function (Request $request, Response $response) {

    $idAdmResFuncional = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_resp_funcional_prod WHERE idAdmResFuncional = $idAdmResFuncional";

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

$app->delete('/api/respofunporprod/{idAdmProducto}', function (Request $request, Response $response) {

    $idAdmProducto = $request->getAttribute('idAdmProducto');

    $consulta = "DELETE FROM adm_resp_funcional_prod WHERE idAdmProducto = $idAdmProducto";

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