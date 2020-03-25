<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/servicio2', function (Request $request, Response $response) {
   //$consulta = "select * from ts_ticket_servicio where fechaAlta = curdate()";
    $consulta = "SELECT * FROM ts_ticket_servicio ";
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
