<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;



$app->get('/api/materiales', function (Request $request, Response $response) {

    $consulta = "SELECT m.*, m.nombre as label, m.idAdmMaterialProducto as value FROM adm_material_producto m order by m.orden";

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


$app->get('/api/materiales/{id}', function (Request $request, Response $response) {

    $id_material = $request->getAttribute('id');

    
    $consulta = "SELECT * FROM adm_material_producto WHERE idAdmMaterialProducto= $id_material";

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

$app->post('/api/materiales', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");

    $consulta = "INSERT INTO adm_material_producto
                    (
                        nombre,
                        orden
                    )
                    VALUES
                    (
                        :nombre,
                        :orden
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $material = array('ObjectId' => $id_insertado);
        $response->withJson($material);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/materiales/{id}', function (Request $request, Response $response) {

    $id_material = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $orden = $request->getParam("orden");

    $consulta = "UPDATE adm_material_producto 
                    SET
                        nombre = :nombre,
                        orden = :orden
                WHERE 
                    idAdmMaterialProducto = :id_material";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":orden", $orden);
        $sentencia->bindParam(":id_material", $id_material);

        $sentencia->execute();

        $messaje = '{"message": {"text": "Material actualizado correctamente"}}';  
        echo $messaje;

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/materiales/{id}', function (Request $request, Response $response) {

    $id_material = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_material_producto WHERE idAdmMaterialProducto = $id_material";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        $messaje = '{"message": {"text": "Material eliminado correctamente"}}';  
        echo $messaje;

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

