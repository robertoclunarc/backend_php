<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//get tipos de medidas
$app->get('/api/tipomedidas', function (Request $request, Response $response) {
    $sql = "SELECT * FROM adm_tipo_medidas";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->query($sql);

        if ($resultado->rowcount() > 0) {
            $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);
            // $response = $resultado->fetchAll(PDO::FETCH_OBJ));
            // return $resultado->Response->withJson($medidas);
            echo json_encode($medidas);
        } else {
            //echo json_encode("La Tabla esta vacia.");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

//get tipos de medidas por indice
$app->get('/api/tipomedidas/{idAdmTipoMedida}', function (Request $request, Response $response) {
    $ID_TIPO = $request->getAttribute('idAdmTipoMedida');
    $sql = "SELECT * FROM adm_tipo_medidas WHERE idAdmTipoMedida = $ID_TIPO";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->query($sql);

        if ($resultado->rowcount() > 0) {
            $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);

            echo json_encode($medidas);
        } else {
            echo json_encode("No existe el registro");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});
//POST INGRESAR NUEVA TIPO DE MEDIDA
$app->post('/api/tipomedidas', function (Request $request, Response $response) {

    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');
    $orden = $request->getParam('orden');

    $sql = "INSERT INTO adm_tipo_medidas (nombre, descripcion, orden) VALUES (:nombre, :descripcion, :orden)";
    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':descripcion', $descripcion);
        $resultado->bindParam(':orden', $orden);

        $resultado->execute();
        $id_insertado = $db->lastInsertId();
        $db = null;

        // $rol = array('ObjectId' => $id_insertado);
        //echo json_encode($rol);
        // $id_insertado = $db->lastInsertId();
        $producto = array('ObjectId' => $id_insertado);
        $response->withJson($producto);
        // echo json_encode($producto);
        //echo json_encode("Has registrado un nuevo tipo de medida con el nombre $nombre");
        //$resultado = null;

    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

//PUT MODIFY TIPO DE MEDIDA BY ID
$app->put('/api/tipomedidas/{idAdmTipoMedida}', function (Request $request, Response $response) {
    
    $ID_TIPO = $request->getAttribute('idAdmTipoMedida');
    
    $nombre = $request->getParam('nombre');
    $descripcion = $request->getParam('descripcion');
    $orden = $request->getParam('orden');

    $sql = "UPDATE adm_tipo_medidas SET
            nombre = :nombre,
            descripcion = :descripcion,
            orden = :orden
            WHERE idAdmTipoMedida = $ID_TIPO";
    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':descripcion', $descripcion);
        $resultado->bindParam(':orden', $orden);

        $resultado->execute();
        echo json_encode("El tipo de medida $nombre ha sido modificado");
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

//ELIMINAR
$app->delete('/api/tipomedidas/{idAdmTipoMedida}', function (Request $request, Response $response) {
    $id_tabla = $request->getAttribute('idAdmTipoMedida');

    $sql = "DELETE FROM adm_tipo_medidas WHERE idAdmTipoMedida = $id_tabla";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("Registro Eliminado.");
        } else {
            echo json_encode("no existe registro en la bbdd");
        }

        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});
