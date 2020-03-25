<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;



$app->get('/api/subtipos', function (Request $request, Response $response) {

    $consulta = "SELECT 
                        s.*,
                        t.nombre as tipoClasificacion 
                    FROM 
                        adm_sub_tipos_clasificacion s
                        INNER JOIN
                        adm_tipos_clasificacion t 
                        ON
                            s.idAdmTipoClasificacion = t.idAdmTipoClasificacion
                    order by 
                        s.idAdmTipoClasificacion,
                        s.idAdmSubTipoClasificacion";           

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

$app->get('/api/subtipos/{id}', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $consulta = "SELECT s.* FROM adm_sub_tipos_clasificacion s WHERE s.idAdmSubTipoClasificacion = $id_subtipo";

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


$app->get('/api/subtipos/{id}/propiedadesAsignadas', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $consulta = "SELECT 
                        p.*, 
                        p.idAdmPropiedad as value,
                        p.nombre as label

                    FROM 
                        adm_propiedades p 
                        INNER JOIN
                        adm_propiedad_sub_tipo_clasificacion stc
                        ON 
                        stc.idAdmPropiedad = p.idAdmPropiedad
                    WHERE 
                        stc.idAdmSubTipoClasificacion =  $id_subtipo";                    

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

$app->get('/api/subtipos/{id}/propiedadesNoAsignadas', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $consulta = "SELECT 
                        p.*, 
                        p.idAdmPropiedad as value,
                        p.nombre as label
                    FROM 
                        adm_propiedades p 
                    WHERE 
                        p.idAdmPropiedad not in (select idAdmPropiedad FROM adm_propiedad_sub_tipo_clasificacion WHERE 
                        idAdmSubTipoClasificacion = $id_subtipo)";
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


$app->get('/api/subtipos/{id}/propiedades', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $consulta = "SELECT 
                        p.*,
                        p.nombre as label,
                        p.idAdmPropiedad as value
                    FROM 
                            adm_sub_tipos_clasificacion s 
                        INNER JOIN 
                            adm_propiedades p
                        ON
                            p.idAdmSubTipo = s.idAdmSubTipoClasificacion

                    WHERE 
                        s.idAdmSubTipoClasificacion =  $id_subtipo
                    ORDER BY
                        p.orden";

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

$app->post('/api/subtipos', function (Request $request, Response $response) {

    $nombre = $request->getParam("nombre");
    $id_tipo = $request->getParam("idAdmTipoClasificacion");


    $consulta = "INSERT INTO adm_sub_tipos_clasificacion
                    (
                        nombre,
                        idAdmTipoClasificacion
                    )
                    VALUES
                    (
                        :nombre,
                        :idAdmTipoClasificacion
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":idAdmTipoClasificacion", $id_tipo);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $subtipo = array('ObjectId' => $id_insertado);
        $response->withJson($subtipo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->put('/api/subtipos/{id}', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $nombre = $request->getParam("nombre");
    $id_tipo = $request->getParam("idAdmTipoClasificacion");

    $consulta = "UPDATE adm_sub_tipos_clasificacion 
                    SET
                        nombre = :nombre,
                        idAdmTipoClasificacion = :idAdmTipoClasificacion
                 WHERE 
                    idAdmSubTipoClasificacion = :id_subtipo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":idAdmTipoClasificacion", $id_tipo);

        $sentencia->bindParam(":id_subtipo", $id_subtipo);

        $sentencia->execute();

        echo '{"message": {"text": "SubTipo actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/subtipos/{id}', function (Request $request, Response $response) {

    $id_subtipo = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_sub_tipos_clasificacion WHERE idAdmSubTipoClasificacion = $id_subtipo";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "SubTipo eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});




