<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/noticiasPublico', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM config_noticias WHERE activo = 1
                ORDER BY fechaAlta DESC";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/noticias', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM config_noticias  
                ORDER BY fechaAlta DESC";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/noticia/{id}', function (Request $request, Response $response) {

    $id_noticia = $request->getAttribute('id');
    $consulta = "SELECT * FROM config_noticias WHERE idConfigNoticia = $id_noticia";

    try {

        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $noticias = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($noticias);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/noticia', function (Request $request, Response $response) {

    $titulo = $request->getParam("titulo");
    $descripcion = $request->getParam("descripcion");
    $rutaImagen = $request->getParam("rutaImagen");
    $nombreImg = $request->getParam("nombreImg");
    $activo = $request->getParam("activo");

    $consulta = "INSERT INTO config_noticias
                    (
                        titulo,
                        descripcion,
                        rutaImagen,
                        nombreImg,
                        activo
                    )
                    VALUES
                    (
                        :titulo,
                        :descripcion,
                        :rutaImagen,
                        :nombreImg,
                        :activo
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":titulo", $titulo);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":rutaImagen", $rutaImagen);
        $sentencia->bindParam(":nombreImg", $nombreImg);
        $sentencia->bindParam(":activo", $activo);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        //Ultimo row insertado
        $ejecutar = $db->query("SELECT * from config_noticias WHERE idConfigNoticia = " . $db->lastInsertId());
        $newNoticia = $ejecutar->fetchAll(PDO::FETCH_OBJ); 
        //****** */
        $db = null;

        //return $response->withJson(['success' => $success]);
        //return $response->withJson($newNoticia);
        $rol = array('ObjectId' => $id_insertado);
        echo json_encode($rol);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/subirimagen/{archAnterior}', function (Request $request, Response $response) {
    $files = $request->getUploadedFiles();
    $archivoAnterior = $request->getAttribute('archAnterior');
    if (empty($files['myfile'])) {
        throw new Exception('Expected a newfile');
    }

    $newfile = $files['myfile'];

    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        //echo $newfile->getClientMediaType();
        if ($archivoAnterior != "-1") {
            if (file_exists("../public/subidos/$archivoAnterior")) {
                unlink("../public/subidos/$archivoAnterior");
            }
        }
        $newfile->moveTo("../public/subidos/$uploadFileName");
    }
    //echo json_encode($newfile);
    echo '{"nombreArchivo": "' . $uploadFileName . '"}';
});

$app->post('/api/quitarimagen/{archAnterior}', function (Request $request, Response $response) {
    $archivoAnterior = $request->getAttribute('archAnterior');
    if (file_exists("../public/subidos/$archivoAnterior")) {
        unlink("../public/subidos/$archivoAnterior");
    }
    echo '{"nombreArchivo": "elimino archivo"}';
});

$app->put('/api/noticia/{id}', function (Request $request, Response $response) {

    $id_noticia = $request->getAttribute('id');
    $titulo = $request->getParam("titulo");
    $descripcion = $request->getParam("descripcion");
    $rutaImagen = $request->getParam("rutaImagen");
    $nombreImg = $request->getParam("nombreImg");
    $activo = $request->getParam("activo");

    $consulta = "UPDATE config_noticias SET
                    titulo = :titulo,
                    descripcion = :descripcion,
                    rutaImagen = :rutaImagen,
                    nombreImg = :nombreImg,
                    activo = :activo
                WHERE idConfigNoticia = :id_noticia";

    /* $parsedBody = $request->getParsedBody();
    print_r(json_encode($parsedBody));
    return true; */

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":titulo", $titulo);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":rutaImagen", $rutaImagen);
        $sentencia->bindParam(":nombreImg", $nombreImg);
        $sentencia->bindParam(":activo", $activo);
        $sentencia->bindParam(":id_noticia", $id_noticia);

        $sentencia->execute();

        $ejecutar = $db->query("SELECT * from config_noticias WHERE idConfigNoticia = " . $id_noticia);
        $updatedNoticia = $ejecutar->fetchAll(PDO::FETCH_OBJ); 

        $db = null;

        //$parsedBody = $request->getParsedBody();
       // print_r(json_encode($parsedBody));
       return $response->withJson($updatedNoticia);
       //echo '{"message": {"text": "Noticia eliminado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/noticia/{id}', function (Request $request, Response $response) {

    $id_noticia = $request->getAttribute('id');

    $consulta = "DELETE FROM config_noticias WHERE idConfigNoticia = $id_noticia";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->execute();

        $db = null;

        echo '{"message": {"text": "Noticia eliminado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
