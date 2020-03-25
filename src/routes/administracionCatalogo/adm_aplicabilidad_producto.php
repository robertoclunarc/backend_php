<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/productos/{id}/aplicabilidad', function (Request $request, Response $response) {

    $id_producto_padre = $request->getAttribute('id');

    $consulta = "SELECT 
						a.*,
						p.nombre as producto,
						g.nombre as gerencia,
						g.idConfigGerencia,
						ag.nombre as areagerencia
						
					FROM
						adm_aplicabilidad_producto a
						LEFT JOIN
                            adm_productos p ON p.idAdmProducto = a.idAdmProductoHijo
						INNER JOIN
                            adm_areas_relacion_gerencia rela ON rela.idAreaTrabajo = a.idAreaTrabajoGerencia and a.idConfigGerencia = rela.idConfigGerencia
                        INNER JOIN 
                            config_gerencias g ON rela.idConfigGerencia = g.idConfigGerencia
                        INNER JOIN
                            adm_areas_trabajo ag ON ag.idAreaTrabajo = rela.idAreaTrabajo

					where 
						a.idAdmProductoPadre = $id_producto_padre";

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


$app->post('/api/aplicabilidad', function (Request $request, Response $response) {

    $idAreaTrabajoGerencia = $request->getParam("idAreaTrabajoGerencia");
    $idConfigGerencia = $request->getParam("idConfigGerencia");
    $idAdmProductoHijo = $request->getParam("idAdmProductoHijo");
    $idAdmProductoPadre = $request->getParam("idAdmProductoPadre");
    $descripcionUso = $request->getParam("descripcionUso");
    $observacion = $request->getParam("observacion");

    $consulta = "INSERT INTO adm_aplicabilidad_producto
                    (
                        idAreaTrabajoGerencia,
                        idConfigGerencia,
                        idAdmProductoHijo,
                        idAdmProductoPadre,
                        descripcionUso,
                        observacion
                    )
                    VALUES
                    (
						:idAreaTrabajoGerencia,
                        :idConfigGerencia,
                        :idAdmProductoHijo,
                        :idAdmProductoPadre,
                        :descripcionUso,
                        :observacion
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":idAreaTrabajoGerencia", $idAreaTrabajoGerencia);
        $sentencia->bindParam(":idConfigGerencia", $idConfigGerencia);
        $sentencia->bindParam(":idAdmProductoHijo", $idAdmProductoHijo);
        $sentencia->bindParam(":idAdmProductoPadre", $idAdmProductoPadre);
        $sentencia->bindParam(":descripcionUso", $descripcionUso);
        $sentencia->bindParam(":observacion", $observacion);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $complementaria = array('ObjectId' => $id_insertado);
        $response->withJson($complementaria);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/aplicabilidad/{id}', function (Request $request, Response $response) {

    $idAdmAplicabilidadProducto = $request->getAttribute("id");
    $idAreaTrabajoGerencia = $request->getParam("idAreaTrabajoGerencia");
    $idConfigGerencia = $request->getParam("idConfigGerencia");
    $idAdmProductoHijo = $request->getParam("idAdmProductoHijo");
    $idAdmProductoPadre = $request->getParam("idAdmProductoPadre");
    $descripcionUso = $request->getParam("descripcionUso");
    $observacion = $request->getParam("observacion");

    $consulta = "UPDATE adm_aplicabilidad_producto 
	    SET
	        idAreaTrabajoGerencia = :idAreaTrabajoGerencia, 
            idConfigGerencia = :idConfigGerencia,
	        idAdmProductoHijo = :idAdmProductoHijo, 
	        idAdmProductoPadre = :idAdmProductoPadre, 
	        descripcionUso = :descripcionUso, 
	        observacion = :observacion         
	
        WHERE
            idAdmAplicabilidadProducto = :idAdmAplicabilidadProducto 
        ";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":idAreaTrabajoGerencia", $idAreaTrabajoGerencia);
        $sentencia->bindParam(":idConfigGerencia", $idConfigGerencia);
        $sentencia->bindParam(":idAdmProductoHijo", $idAdmProductoHijo);
        $sentencia->bindParam(":idAdmProductoPadre", $idAdmProductoPadre);
        $sentencia->bindParam(":descripcionUso", $descripcionUso);
        $sentencia->bindParam(":observacion", $observacion);
        $sentencia->bindParam(":idAdmAplicabilidadProducto", $idAdmAplicabilidadProducto);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $complementaria = array('ObjectId' => $id_insertado);
        $response->withJson($complementaria);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/aplicabilidad/{id}', function (Request $request, Response $response) {

    $idAdmAplicabilidadProducto = $request->getAttribute('id');
    $consulta = "DELETE FROM adm_aplicabilidad_producto WHERE idAdmAplicabilidadProducto = $idAdmAplicabilidadProducto";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Aplicabilidad eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
