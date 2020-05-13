<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/complementarias', function (Request $request, Response $response) {

    $aplicaComplementaria = $request->getParam("aplicaComplementaria");

    $consulta = "SELECT 
                        c.* 
                    FROM 
                        adm_complementarias_producto c
                    WHERE 
                        c.aplicaComplementaria =  $aplicaComplementaria
                    ORDER BY 
                        c.idAdmComplementariaProducto";
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


$app->get('/api/complementarias/{id}', function (Request $request, Response $response) {

    $idAdmComplementariaProducto = $request->getAttribute('id');

    $consulta = "SELECT 
                    c.*
                 FROM 
                    adm_complementarias_producto c 
                WHERE 
                    c.idAdmComplementariaProducto = $idAdmComplementariaProducto";

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

$app->post('/api/complementarias', function (Request $request, Response $response) {

    $idAdmProducto = $request->getParam("idAdmProducto");
    $idAdmPropiedad = $request->getParam("idAdmPropiedad"); 
    $idAdmSubTipoClasificacion = $request->getParam("idAdmSubTipoClasificacion");
    $valor = $request->getParam("valor");
    $observacion = $request->getParam("observacion");
    $idAdmUnidadMedida = $request->getParam("idAdmUnidadMedida");
    $aplicaComplementaria = $request->getParam("aplicaComplementaria");


    $consulta = "INSERT INTO adm_complementarias_producto
                    (
                        idAdmProducto,
                        idAdmPropiedad,
                        idAdmSubTipoClasificacion,
                        valor,
                        observacion,
                        idAdmUnidadMedida,
                        aplicaComplementaria
                    )
                    VALUES
                    (
                        :idAdmProducto,
                        :idAdmPropiedad,
                        :idAdmSubTipoClasificacion,
                        :valor,
                        :observacion,
                        :idAdmUnidadMedida,
                        :aplicaComplementaria
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idAdmPropiedad", $idAdmPropiedad);
        $sentencia->bindParam(":idAdmSubTipoClasificacion", $idAdmSubTipoClasificacion);
        $sentencia->bindParam(":valor", $valor);
        $sentencia->bindParam(":observacion", $observacion);
        $sentencia->bindParam(":idAdmUnidadMedida", $idAdmUnidadMedida);
        $sentencia->bindParam(":aplicaComplementaria", $aplicaComplementaria);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $complementaria = array('ObjectId' => $id_insertado);
        $response->withJson($complementaria);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/complementarias/{id}', function (Request $request, Response $response) {

    $idAdmComplementariaProducto = $request->getAttribute('id');

    $idAdmProducto = $request->getParam("idAdmProducto");
    $idAdmPropiedad = $request->getParam("idAdmPropiedad");
    $idAdmSubTipoClasificacion = $request->getParam("idAdmSubTipoClasificacion");
    $valor = $request->getParam("valor");
    $observacion = $request->getParam("observacion");
    $idAdmUnidadMedida = $request->getParam("idAdmUnidadMedida");
    $aplicaComplementaria = $request->getParam("aplicaComplementaria");

    $consulta = "UPDATE adm_complementarias_producto 
                    SET
                    idAdmProducto = :idAdmProducto,                    
                    idAdmPropiedad   = :idAdmPropiedad,
                    idAdmSubTipoClasificacion   = :idAdmSubTipoClasificacion,
                    valor = :valor,
                    observacion = :observacion,
                    idAdmUnidadMedida = :idAdmUnidadMedida,
                    aplicaComplementaria = :aplicaComplementaria
                 WHERE 
                    idAdmComplementariaProducto  = :idAdmComplementariaProducto";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":idAdmPropiedad", $idAdmPropiedad);
        $sentencia->bindParam(":idAdmSubTipoClasificacion", $idAdmSubTipoClasificacion);
        $sentencia->bindParam(":valor", $valor);
        $sentencia->bindParam(":observacion", $observacion);
        $sentencia->bindParam(":valor", $valor);
        $sentencia->bindParam(":idAdmUnidadMedida", $idAdmUnidadMedida);
        $sentencia->bindParam(":aplicaComplementaria", $aplicaComplementaria);
        $sentencia->bindParam(":idAdmComplementariaProducto", $idAdmComplementariaProducto);

        $sentencia->execute();

        echo '{"message": {"text": "Complementaria actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
$app->delete('/api/complementarias/{id}', function (Request $request, Response $response) {

    $idAdmComplementariaProducto = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_complementarias_producto WHERE idAdmComplementariaProducto = $idAdmComplementariaProducto";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Complementaria eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});