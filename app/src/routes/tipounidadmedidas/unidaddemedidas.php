<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//get tipos de medidas
$app->get('/api/medidasporunidad', function (Request $request, Response $response) {

    //$sql = "SELECT * FROM adm_unidad_medidas";
    $sql = "SELECT medidas.*,
	(SELECT nombre FROM adm_tipo_medidas tipo WHERE tipo.idAdmTipoMedida = medidas.idAdmTipoMedida) nombre_tipo
    FROM adm_unidad_medidas medidas"; 

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->query($sql);
        $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($medidas);
      /*  if ($resultado->rowcount() > 0) {
            $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);

            echo json_encode($medidas);
        } else {
            echo json_encode("La Tabla esta vacia.");
        }*/
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

//get tipos de medidas POR INDICE
$app->get('/api/medidasporunidad/{idAdmUnidadMedida}', function (Request $request, Response $response) {
    $id_tabla = $request->getAttribute('idAdmUnidadMedida');
    $sql = "SELECT * FROM adm_unidad_medidas WHERE idAdmUnidadMedida = $id_tabla";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->query($sql);

        if ($resultado->rowcount() > 0) {
            $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);

            echo json_encode($medidas);
        } else {
            echo json_encode("No existe el registro indicado.");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});


//POST INGRESAR NUEVA UNIDAD DE MEDIDA
$app->post('/api/medidasporunidad', function (Request $request, Response $response) {

    $nombre = $request->getParam('nombre');
    $abrev = $request->getParam('abrev');
    $orden = $request->getParam('orden');
    $id_tipoUnidad = $request->getparam('idAdmTipoMedida');

    $sql = "INSERT INTO adm_unidad_medidas (nombre, abrev, orden, idAdmTipoMedida) VALUES 
    (:nombre, :abrev, :orden, :idAdmTipoMedida)";
    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':abrev', $abrev);
        $resultado->bindParam(':orden', $orden);
        $resultado->bindparam(':idAdmTipoMedida', $id_tipoUnidad);

        $resultado->execute();
        $id_insertado = $db->lastInsertId();
        $producto = array('ObjectId' => $id_insertado);

        $response->withJson($producto);
        // echo json_encode("La unidad de medida $nombre ha sido registrada");
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

/*CONSULTA PARA CAMPO RELACIONAL
$app->get('/api/medidasporunidad/consulta_medidas/{id_tipo}',  function (Request $request, Response $response) {
    
    $id_tipoUnidad = $request->getAttribute('id_tipo');
    $sql = " SELECT medidas.*,
	    (SELECT nombre FROM adm_tipo_medidas tipo WHERE tipo.idAdmTipoMedida = $id_tipoUnidad) nombre_tipo
     FROM adm_unidad_medidas medidas ";

    try {

        $db = new db();
        $db = $db->conectar();

        $resultado = $db->query($sql);
        $medidas = $resultado->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->withJson($medidas);
        } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}}';
    }

});*/


//PUT modificar
$app->put('/api/medidasporunidad/{idAdmUnidadMedida}', function (Request $request, Response $response) {
    
    $id_tabla = $request->getAttribute('idAdmUnidadMedida');
   
    $nombre         = $request->getParam('nombre');
    $abrev          = $request->getParam('abrev');
   // $orden        = $request->getParam('orden');
    $id_tipoUnidad  = $request->getParam('idAdmTipoMedida');

   

    $sql = "UPDATE adm_unidad_medidas 
                SET
                    nombre = :nombre,
                    abrev = :abrev,
                    idAdmTipoMedida = :idAdmTipoMedida
            WHERE  idAdmUnidadMedida = $id_tabla";
    try {
        $db = new db();
        $db = $db->conectar();
       
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':nombre', $nombre);
        $resultado->bindParam(':abrev', $abrev);
        //$resultado->bindParam(':orden', $orden);
        $resultado->bindParam(':idAdmTipoMedida', $id_tipoUnidad);
   
        $resultado->execute();
        $response->withJson($resultado);
        //echo json_encode("Unidad de medida $nombre modificado");
        $resultado = null;
        $db = null;
    
        //echo '{"message": {"text": actualizado correctamente"}}';

    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});

//ELIMINAR
$app->delete('/api/medidasporunidad/{idAdmUnidadMedida}', function (Request $request, Response $response) {
    $id_tabla = $request->getAttribute('idAdmUnidadMedida');

    $sql = "DELETE FROM adm_unidad_medidas WHERE idAdmUnidadMedida = $id_tabla";

    try {
        $db = new db();
        $db = $db->conectar();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowcount() > 0) {
            echo json_encode("Registro $id_tabla Eliminado.");
        } else {
            echo json_encode("no existe registro en la bbdd");
        }

        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error" : {"text":' . $e->getMessage() . '}';
    }
});
