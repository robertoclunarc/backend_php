<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/adm/ventas/facturas', function(Request $request, Response $response){

    $database = $request->getParam('database');
    $consulta = "SELECT f.*, u.nombre AS vendedor FROM adm_ventas_factura1 f INNER JOIN adm_tabla_usuarios u ON f.ven_codigo = u.cod_usuario";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        foreach ($result as &$factura) {
            $consulta = "select * from adm_ventas_factura2 where numero = ".$factura->num_factura;
            $ejecutar = $db->query($consulta);
            $detalle = $ejecutar->fetchAll(PDO::FETCH_OBJ);
            $factura->detalle = $detalle;
        }

        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/adm/ventas/facturas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $database = $request->getParam('database');
    $consulta = "SELECT f.*, u.nombre AS vendedor FROM adm_ventas_factura1 f 
                    INNER JOIN adm_tabla_usuarios u ON f.ven_codigo = u.cod_usuario where f.num_factura = '".$id."'";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        foreach ($result as &$factura) {
            $consulta = "select * from adm_ventas_factura2 where numero = ".$factura->num_factura;
            $ejecutar = $db->query($consulta);
            $detalle = $ejecutar->fetchAll(PDO::FETCH_OBJ);
            $factura->detalle = $detalle;
        }

        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/adm/ventas/facturas/{desde}/{hasta}', function(Request $request, Response $response){

    $desde = $request->getAttribute('desde');
    $hasta = $request->getAttribute('hasta');
    $database = $request->getParam('database');
    $consulta = "SELECT f.*, u.nombre AS vendedor FROM adm_ventas_factura1 f INNER JOIN adm_tabla_usuarios u ON f.ven_codigo = u.cod_usuario where f.fecha_emision between '".$desde."' and '".$hasta."'";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        foreach ($result as &$factura) {
            $consulta = "select * from adm_ventas_factura2 where numero = ".$factura->num_factura;
            $ejecutar = $db->query($consulta);
            $detalle = $ejecutar->fetchAll(PDO::FETCH_OBJ);
            $factura->detalle = $detalle;
        }

        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});
