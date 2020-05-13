<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/propiedadsubtipo', function (Request $request, Response $response) {

    $idAdmSubTipoClasificacion = $request->getParam('idAdmSubTipoClasificacion');
    $idAdmPropiedad = $request->getParam('idAdmPropiedad');

    $consulta = "INSERT INTO adm_propiedad_sub_tipo_clasificacion
                    (
                        idAdmSubTipoClasificacion,
                        idAdmPropiedad
                    )
                VALUES
                    (
                        :idAdmSubTipoClasificacion,
                        :idAdmPropiedad
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idAdmSubTipoClasificacion', $idAdmSubTipoClasificacion);
        $stmt->bindParam(':idAdmPropiedad', $idAdmPropiedad);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/propiedadsubtipo/{idAdmSubTipoClasificacion}/{idAdmPropiedad}', function (Request $request, Response $response) {

    $idAdmSubTipoClasificacion = $request->getAttribute('idAdmSubTipoClasificacion');
    $idAdmPropiedad = $request->getAttribute('idAdmPropiedad');

    $consulta = "DELETE FROM adm_propiedad_sub_tipo_clasificacion 
                    WHERE 
                    idAdmSubTipoClasificacion = $idAdmSubTipoClasificacion AND 
                    idAdmPropiedad = $idAdmPropiedad";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Item eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});