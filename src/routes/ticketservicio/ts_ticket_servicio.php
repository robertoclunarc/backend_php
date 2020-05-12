<?php
/* 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Credentials: true'); */

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\UploadedFile;


$app->get('/api/tickets', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM ts_ticket_servicio ";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //echo json_encode($result);
        $response->withJson($result);
        //echo ;

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/tickets2', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM ts_ticket_servicio";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $tickets = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($tickets);
        $db = null;
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/rolesinsertaruno', function (Request $request, Response $response) {

    $codigo = $request->getParam("codigo");
    $nombre = $request->getParam("nombre");
    $descripcion = $request->getParam("descripcion");
    $estatus = $request->getParam("estatus");
    $idmenu = $request->getParam("idSegMenu");
    $auditable = $request->getParam("auditable");

    $consulta = "INSERT INTO seg_roles
                    (
                        codigo,
                        nombre,
                        descripcion,
                        estatus,
                        auditable,
                        IdSegMenu
                    )
                    VALUES
                    (
                        :codigo,
                        :nombre,
                        :descripcion,
                        :estatus,
                        :auditable,
                        :idmenu
                    )
                ";

    try {

        $db = new db();
        $db = $db->conectar();

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":codigo", $codigo);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":descripcion", $descripcion);
        $sentencia->bindParam(":estatus", $estatus);
        $sentencia->bindParam(":idmenu", $idmenu);
        $sentencia->bindParam(":auditable", $auditable);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $rol = array('ObjectId' => $id_insertado);
        echo json_encode($rol);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/tickettrazas/{id}', function (Request $request, Response $response) {

    $id_ticket = $request->getAttribute('id');
    $consulta = "SELECT tra.*,
                        (SELECT e.nombre FROM ts_estados_ticket e WHERE e.idEstadoTicket = tra.idEstadoTicket) as nombreEstado,
                        (SELECT usr.usuario FROM seg_usuarios usr WHERE usr.idSegUsuario = tra.idSegUsuario) as Usuario
                  FROM ts_traza_ticket_servicio tra
                  WHERE tra.idTicketServicio = $id_ticket";

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

//ticketsporusr

$app->get('/api/ticketsporusr/{idUsuario}', function (Request $request, Response $response) {

    $idSegUsuario = $request->getAttribute('idUsuario');
    $consulta = "SELECT * FROM ts_ticket_servicio WHERE idSegUsuario = $idSegUsuario";

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

$app->get('/api/ticketsporgerencia/{idgerencia}', function (Request $request, Response $response) {

    $idgerencia = $request->getAttribute('idgerencia');
    $consulta = "SELECT *
                FROM ts_ticket_servicio
                WHERE idGerenciaOrigen = $idgerencia";

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

$app->get('/api/algo', function (Request $request, Response $response) {

    echo '{"error": {"text":' . "algo" . '}}';
});

$app->get('/api/ticketshisRecibidos/{idgerencia}', function (Request $request, Response $response) {
    $idgerencia = $request->getAttribute('idgerencia');
    $consulta = "SELECT tickets.*,
                    (SELECT nombre FROM config_gerencias ger WHERE ger.idConfigGerencia = tickets.idGerenciaOrigen) gerenciaOrigen
                    FROM ts_ticket_servicio tickets
                    WHERE idGerenciaDestino = $idgerencia
                    AND ((tickets.idEstadoActual > 5 AND tickets.idEstadoActual <= 9) or (tickets.idEstadoActual < 0))
                   ";
    try {
        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $hisRecibidos = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($hisRecibidos);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/ticketshisEnviados/{idgerencia}', function (Request $request, Response $response) {
    $idgerencia = $request->getAttribute('idgerencia');
    $consulta = "SELECT tickets.*,
                    (SELECT nombre FROM config_gerencias ger WHERE ger.idConfigGerencia = tickets.idGerenciaDestino) gerenciaDestino
                    FROM ts_ticket_servicio tickets
                    WHERE idGerenciaOrigen = $idgerencia
                    AND ((tickets.idEstadoActual > 5 AND tickets.idEstadoActual <= 9) or (tickets.idEstadoActual < 0))
                    ";
    try {
        $db = new db();
        $db = $db->conectar();

        $ejecutar = $db->query($consulta);
        $hisEnvi = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($hisEnvi);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/ticketsenviados/{idgerencia}', function (Request $request, Response $response) {

    $idgerencia = $request->getAttribute('idgerencia');
    $consulta = "SELECT tickets.*,
                (SELECT nombre FROM config_gerencias ger WHERE ger.idConfigGerencia = tickets.idGerenciaDestino) gerenciaDestino,
                IF(tickets.idEstadoActual = 1 OR tickets.idEstadoActual = 2 OR tickets.idEstadoActual = 5, 0, 1) orden_mod
                FROM ts_ticket_servicio tickets 
                    WHERE idGerenciaOrigen = $idgerencia
                    AND ((tickets.IdEstadoActual >= 1 and tickets.IdEstadoActual < 6) OR (tickets.IdEstadoActual = 10))
                    ORDER BY orden_mod, idEstadoActual ASC";
    //and tickets.idEstadoActual <> 9 and tickets.idEstadoActual > 0


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

$app->get('/api/ticketsrecibidos/{idgerencia}/{idusuario}', function (Request $request, Response $response) {

    $idgerencia = $request->getAttribute('idgerencia');
    $idusuario = $request->getAttribute('idusuario');
    $consulta = "SELECT tickets.*,
                    (SELECT nombre FROM config_gerencias ger WHERE ger.idConfigGerencia = tickets.idGerenciaOrigen) gerenciaOrigen,
                    (SELECT accion_adicional FROM ts_estados_ticket est WHERE est.idEstadoTicket = tickets.idEstadoActual) estado_actual_accion_adic,
                    (SELECT orden FROM ts_estados_ticket est WHERE est.idEstadoTicket = tickets.idEstadoActual) orden
                FROM ts_ticket_servicio tickets
                WHERE tickets.idGerenciaDestino = $idgerencia
                    AND ((tickets.IdEstadoActual > 1 and tickets.IdEstadoActual < 6) OR (tickets.IdEstadoActual = 10))
                    OR (tickets.idGerenciaDestino IN (SELECT gt.idConfigGerencia FROM config_gerencias_temporales gt WHERE gt.idSegUsuario = $idusuario))
                ORDER BY orden, tickets.idEstadoActual ASC, tickets.idTicketServicio DESC";

    /*AND tickets.estadoActual <> 'Registrado'
AND tickets.estadoActual <> 'Anulado'
AND tickets.estadoActual <> 'Rechazado'
AND tickets.IdEstadoActual <> '6'
AND tickets.IdEstadoActual <> '9'
 */
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


$app->get('/api/getimgsticket/{idTicketServicio}', function (Request $request, Response $response) {

    $idTicketServicio = $request->getAttribute('idTicketServicio');
    $consulta = "SELECT * FROM ts_imgs_ticket_servicio WHERE idTicketServicio = $idTicketServicio";

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


$app->get('/api/imagenyaregistrada/{nombre_imagen}', function (Request $request, Response $response) {

    $nombre = $request->getAttribute('nombre_imagen');
    //$consulta = "SELECT * FROM ts_imgs_ticket_servicio WHERE nombre_imagen = '$nombre' LIMIT 1";

    $consulta = "SELECT * FROM ts_imgs_ticket_servicio im
                    INNER JOIN ts_ticket_servicio tk ON im.idTicketServicio = tk.idTicketServicio
                WHERE nombre_imagen = '$nombre' AND (tk.idEstadoActual <> 8 AND tk.idEstadoActual <> 7)
                LIMIT 1";

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

$app->post('/api/ticket', function (Request $request, Response $response) {

    $descripcion = $request->getParam('descripcion');
    $fechaRequerida = $request->getParam('fechaRequerida');
    $fechaEstimada = $request->getParam('fechaEstimada');
    $idEstadoActual = $request->getParam('idEstadoActual');
    $estadoActual = $request->getParam('estadoActual');
    $fechaEstadoActual = $request->getParam('fechaEstadoActual');

    $justificacionEstadoActual = $request->getParam('justificacionEstadoActual');
    $idGerenciaOrigen = $request->getParam('idGerenciaOrigen');
    $idGerenciaDestino = $request->getParam('idGerenciaDestino');
    $idSegUsuario = $request->getParam('idSegUsuario');
    $idServiciosGerencias = $request->getParam('idServiciosGerencias');
    $idAssets = $request->getParam('idAssets');
    $idSegUsuarioOrigen = $request->getParam('idSegUsuarioOrigen');

    $consulta = "INSERT INTO ts_ticket_servicio
                    (
                        descripcion,
                        fechaRequerida,
                        fechaEstimada,
                        idEstadoActual,
                        estadoActual,
                        fechaEstadoActual,
                        justificacionEstadoActual,
                        idGerenciaOrigen,
                        idGerenciaDestino,
                        idSegUsuario,
                        idServiciosGerencias,
                        idAssets,
                        idSegUsuarioOrigen
                    )
                    VALUES
                    (
                        :descripcion,
                        :fechaRequerida,
                        :fechaEstimada,
                        :idEstadoActual,
                        :estadoActual,
                        :fechaEstadoActual,
                        :justificacionEstadoActual,
                        :idGerenciaOrigen,
                        :idGerenciaDestino,
                        :idSegUsuario,
                        :idServiciosGerencias,
                        :idAssets,
                        :idSegUsuarioOrigen
                    )";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fechaRequerida', $fechaRequerida);
        $stmt->bindParam(':fechaEstimada', $fechaEstimada);
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);
        $stmt->bindParam(':estadoActual', $estadoActual);
        $stmt->bindParam(':fechaEstadoActual', $fechaEstadoActual);
        $stmt->bindParam(':justificacionEstadoActual', $justificacionEstadoActual);
        $stmt->bindParam(':idGerenciaOrigen', $idGerenciaOrigen);
        $stmt->bindParam(':idGerenciaDestino', $idGerenciaDestino);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idServiciosGerencias', $idServiciosGerencias);
        $stmt->bindParam(':idAssets', $idAssets);
        $stmt->bindParam(':idSegUsuarioOrigen', $idSegUsuarioOrigen);

        $stmt->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $rol = array('ObjectId' => $id_insertado);
        echo json_encode($rol);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/ticket/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    //$fechaAlta              = $request->getParam('fechaAlta');
    $descripcion = $request->getParam('descripcion');
    $fechaRequerida = $request->getParam('fechaRequerida');
    $fechaEstimada = $request->getParam('fechaEstimada');
    $idEstadoActual = $request->getParam('idEstadoActual');
    $estadoActual = $request->getParam('estadoActual');
    $fechaEstadoActual = $request->getParam('fechaEstadoActual');

    $justificacionEstadoActual = $request->getParam('justificacionEstadoActual');
    $idGerenciaOrigen = $request->getParam('idGerenciaOrigen');
    $idGerenciaDestino = $request->getParam('idGerenciaDestino');
    $idSegUsuario = $request->getParam('idSegUsuario');
    $idServiciosGerencias = $request->getParam('idServiciosGerencias');
    $idAssets = $request->getParam('idAssets');

    $consulta = "UPDATE ts_ticket_servicio SET
                    descripcion                 = :descripcion,
                    fechaRequerida              = :fechaRequerida,
                    fechaEstimada               = :fechaEstimada,
                    idEstadoActual              = :idEstadoActual,
                    estadoActual                = :estadoActual,
                    fechaEstadoActual           = :fechaEstadoActual,
                    justificacionEstadoActual   = :justificacionEstadoActual,
                    idGerenciaOrigen            = :idGerenciaOrigen,
                    idGerenciaDestino           = :idGerenciaDestino,
                    idSegUsuario                = :idSegUsuario,
                    idServiciosGerencias        = :idServiciosGerencias,
                    idAssets                    = :idAssets

                    WHERE idTicketServicio = :id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        //$stmt->bindParam(':fechaAlta', $fechaAlta);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fechaRequerida', $fechaRequerida);
        $stmt->bindParam(':fechaEstimada', $fechaEstimada);
        $stmt->bindParam(':idEstadoActual', $idEstadoActual);
        $stmt->bindParam(':estadoActual', $estadoActual);
        $stmt->bindParam(':fechaEstadoActual', $fechaEstadoActual);
        $stmt->bindParam(':justificacionEstadoActual', $justificacionEstadoActual);
        $stmt->bindParam(':idGerenciaOrigen', $idGerenciaOrigen);
        $stmt->bindParam(':idGerenciaDestino', $idGerenciaDestino);
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idServiciosGerencias', $idServiciosGerencias);
        $stmt->bindParam(':idAssets', $idAssets);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Ticket actualizado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/ticket/{id}', function (Request $request, Response $response) {

    $id_ticket = $request->getAttribute('id');

    $consulta = "DELETE FROM ts_ticket_servicio WHERE idTicketServicio = $id_ticket";

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

$app->post('/api/subirimagenesticket/{archAnterior}', function (Request $request, Response $response) {


    //printf("algo");

    $files = $request->getUploadedFiles();

    if (empty($files['imgsTickets'])) {
        throw new Exception('Expected a newfile');
    }

    foreach ($files['imgsTickets'] as $file) {

        if ($file->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile("../public/subidos/imgstickets/", $file);
            //$response->write('uploaded ' . $filename . '<br/>');
        }
        echo '{"nombreArchivo": "' . $filename . '"}';
        //return $response->withJson(json_decode('{"nombreArchivo": "' . $uploadFileName . '"}'));
    }
});

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    //$filename = sprintf('%s.%0.8s', $basename, $extension);
    $filename = $uploadedFile->getClientFilename();

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->post('/api/imagsdbticket', function (Request $request, Response $response) {

    $nombre_imagen = $request->getParam("nombre_imagen");
    $direccion = $request->getParam("direccion");
    $idTicketServicio = $request->getParam("idTicketServicio");
    $img = $request->getParam("img");


    $consulta = "INSERT INTO ts_imgs_ticket_servicio
                    (
                        nombre_imagen,
                        direccion,
                        idTicketServicio,
                        img
                    )
                    VALUES
                    (
                        :nombre_imagen,
                        :direccion,
                        :idTicketServicio,
                        :img
                    )
                ";

    try {

        $db = new db();
        $db = $db->conectar();



        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":nombre_imagen", $nombre_imagen);
        $sentencia->bindParam(":direccion", $direccion);
        $sentencia->bindParam(":idTicketServicio", $idTicketServicio);
        $sentencia->bindParam(":img", $img);

        $sentencia->execute();

        $id_insertado = $db->lastInsertId();
        $db = null;

        $rol = array('ObjectId' => $id_insertado);
        echo json_encode($rol);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/quitarimagenticket/{archAnterior}', function (Request $request, Response $response) {
    $archivoAnterior = $request->getAttribute('archAnterior');
    if (file_exists("../public/subidos/imgstickets/$archivoAnterior")) {
        unlink("../public/subidos/imgstickets/$archivoAnterior");
    }
    echo '{"nombreArchivo": "elimino archivo"}';
});
