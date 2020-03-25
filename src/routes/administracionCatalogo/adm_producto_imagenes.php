<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/productos/{id}/imagenes', function (Request $request, Response $response) {

    $id_producto = $request->getAttribute('id');

    $consulta = "SELECT 
                        pi.imagePath AS imagePath,
                        pi.titulo, pi.idAdmImgProducto
                    FROM 
                        adm_producto_imagenes pi
                    WHERE
                        pi.idAdmProducto = $id_producto";

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



$app->post('/api/productos/imagenes', function (Request $request, Response $response) {

    $idAdmProducto = $request->getParam("idAdmProducto");
    $imagen = $request->getParam("imagePath");
    $titulo = $request->getParam("titulo");

    $consulta = "INSERT INTO adm_producto_imagenes
                    (
                        idAdmProducto,
                        imagePath,
                        titulo
                    )
                    VALUES
                    (
                        :idAdmProducto,
                        :imagePath,
                        :titulo
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":idAdmProducto", $idAdmProducto);
        $sentencia->bindParam(":imagePath", $imagen);
        $sentencia->bindParam(":titulo", $titulo);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $productoImagen = array('ObjectId' => $id_insertado);
        $response->withJson($productoImagen);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/productos/{id}/imagenes', function (Request $request, Response $response) {

    $id_producto = $request->getAttribute('id');

    $consulta = "DELETE FROM adm_producto_imagenes WHERE idAdmProducto = $id_producto";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Imagenes del producto eliminadas correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->post('/api/subirimagenesproducto/{archAnterior}', function (Request $request, Response $response) {


    $files = $request->getUploadedFiles();
    $archivoAnterior = $request->getAttribute('archAnterior');

    if (empty($files['imagenesProd'])) {
        throw new Exception('Expected a newfile');
    }

    //print_r($files);

    foreach ($files['imagenesProd'] as $file) {
        $newfile = $file;
        //echo $newfile->getClientFilename();
        //$files['imgsTickets'];
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            /* if ($archivoAnterior != "-1") {
                if (file_exists("../public/subidos/imgstickets/$archivoAnterior")) {
                    unlink("../public/subidos/imgstickets/$archivoAnterior");
                }
            }*/
            /* if (file_exists("../public/subidos/imgstickets/$archivoAnterior")) {
                unlink("../public/subidos/imgstickets/$archivoAnterior");
            }*/
            $newfile->moveTo("../public/subidos/fotoproductos/$uploadFileName");

            /* $resize = new ResizeImage("../public/subidos/imgstickets/$uploadFileName");
            $resize->resizeTo(300, 200);
            $resize->saveImage("../public/subidos/imgstickets/$uploadFileName"); sss*/
        }
        echo '{"nombreArchivo": "' . $uploadFileName . '"}';
    }
});

$app->delete('/api/productos/imagenes/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute("id");

    try {
        $consulta = "DELETE FROM adm_producto_imagenes WHERE idAdmImgProducto = :id";

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":id", $id);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Imagenes del producto eliminadas correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
