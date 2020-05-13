<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/solpeddetalle', function (Request $request, Response $response) {

    $consulta = "SELECT
                        det.*,
                        (SELECT nombre_empresa FROM compras_empresa empre WHERE det.IdComprasEmpresa = empre.IdComprasEmpresa) nombre_empresa
                FROM compras_detalle_solped det";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $users = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($users);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/solpeddetalle/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM compras_detalle_solped WHERE idDetalleSolped = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/solpeddetallets/{idTicket}', function (Request $request, Response $response) {

    $id = $request->getAttribute('idTicket');

    $consulta = " SELECT detsolped.*,
                         (SELECT nombre_empresa FROM compras_empresa empre WHERE empre.IdComprasEmpresa = detsolped.IdComprasEmpresa) AS nombre_empresa,
                         (SELECT descripcion FROM gen_area_negocio an  WHERE an.idGenAreaNegocio = detsolped.idGenAreaNegocio) AS nombre_an,
                         (SELECT descripcion FROM gen_centro_costos cc WHERE detsolped.idGenCentroCostos = cc.idGenCentroCostos) AS nombre_cc
                  FROM compras_detalle_solped detsolped
                  INNER JOIN compras_solped solped ON solped.idSolpedCompras = detsolped.idSolpedCompras
                  INNER JOIN ts_ticket_servicio ts ON ts.idTicketServicio = solped.idTicketServicio
                 WHERE ts.idTicketServicio = $id
                 ORDER BY idDetalleSolped";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/solpeddetalle', function (Request $request, Response $response) {

    $codigo = $request->getParam('codigo');
    $descripcion = $request->getParam('descripcion');
    $nombre = $request->getParam('nombre');
    $unidadMedidaC = $request->getParam('unidadMedidaC');
    $cantidad = $request->getParam('cantidad');
    $nroActivo = $request->getParam('nroActivo');
    $fechaRequerida = $request->getParam('fechaRequerida');
    $justificacion = $request->getParam('justificacion');
    $idSolpedCompras = $request->getParam('idSolpedCompras');
    $idGenEmpresa = $request->getParam('IdComprasEmpresa');
    $idGenAreaNegocio = $request->getParam('idGenAreaNegocio');
    $idGenCentroCostos = $request->getParam('idGenCentroCostos');
    $idAreaTrabajo = $request->getParam('idAreaTrabajo');
    

    $consulta = "INSERT INTO compras_detalle_solped
                    (
                        codigo,
                        nombre,
                        descripcion,
                        unidadMedidaC,
                        cantidad,
                        nroActivo,
                        fechaRequerida,
                        justificacion,
                        idSolpedCompras,
                        IdComprasEmpresa,
                        idGenAreaNegocio,
                        idGenCentroCostos,
                        idAreaTrabajo
                    )
                VALUES
                    (
                        :codigo,
                        :nombre,
                        :descripcion,
                        :unidadMedidaC,
                        :cantidad,
                        :nroActivo,
                        :fechaRequerida,
                        :justificacion,
                        :idSolpedCompras,
                        :idGenEmpresa,
                        :idGenAreaNegocio,
                        :idGenCentroCostos,
                        :idAreaTrabajo
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':unidadMedidaC', $unidadMedidaC);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':nroActivo', $nroActivo);
        $stmt->bindParam(':fechaRequerida', $fechaRequerida);
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idSolpedCompras', $idSolpedCompras);
        $stmt->bindParam(':idGenEmpresa', $idGenEmpresa);
        $stmt->bindParam(':idGenAreaNegocio', $idGenAreaNegocio);
        $stmt->bindParam(':idGenCentroCostos', $idGenCentroCostos);
        $stmt->bindParam(':idAreaTrabajo', $idAreaTrabajo);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/solpeddetalle/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $codigo = $request->getParam('codigo');
    $descripcion = $request->getParam('descripcion');
    $nombre = $request->getParam('nombre');
    $unidadMedidaC = $request->getParam('unidadMedidaC');
    $cantidad = $request->getParam('cantidad');
    $nroActivo = $request->getParam('nroActivo');
    $fechaRequerida = $request->getParam('fechaRequerida');
    $justificacion = $request->getParam('justificacion');
    $idSolpedCompras = $request->getParam('idSolpedCompras');
    $idGenEmpresa = $request->getParam('idGenEmpresa');
    $idGenAreaNegocio = $request->getParam('idGenAreaNegocio');
    $idGenCentroCostos = $request->getParam('idGenCentroCostos');
    $idAreaTrabajo = $request->getParam('idAreaTrabajo');

    $consulta = "UPDATE compras_detalle_solped SET

                        codigo = :codigo,
                        nombre = :nombre,
                        descripcion = :descripcion,
                        unidadMedidaC = :unidadMedidaC,
                        nroActivo = :nroActivo,
                        fechaRequerida = :fechaRequerida,
                        justificacion = :justificacion,
                        idSolpedCompras = :idSolpedCompras,
                        idComprasEmpresa = :idGenEmpresa,
                        idGenAreaNegocio = :idGenAreaNegocio,
                        idGenCentroCostos = :idGenCentroCostos,
                        idAreaTrabajo = :idAreaTrabajo

                        WHERE idDetalleSolped = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':unidadMedidaC', $unidadMedidaC);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':nroActivo', $nroActivo);
        $stmt->bindParam(':fechaRequerida', $fechaRequerida);
        $stmt->bindParam(':justificacion', $justificacion);
        $stmt->bindParam(':idSolpedCompras', $idSolpedCompras);
        $stmt->bindParam(':idGenEmpresa', $idGenEmpresa);
        $stmt->bindParam(':idGenAreaNegocio', $idGenAreaNegocio);
        $stmt->bindParam(':idGenCentroCostos', $idGenCentroCostos);
        $stmt->bindParam(':idAreaTrabajo', $idAreaTrabajo);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Detalle Solped actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/solpeddetalle/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "DELETE FROM compras_detalle_solped WHERE idDetalleSolped = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Detalle Solped eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
