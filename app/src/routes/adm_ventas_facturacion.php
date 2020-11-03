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

$app->put('/api/adm/ventas/facturas', function(Request $request, Response $response){
    $database = $request->getParam('database');
    $numero = $request->getParam('numero');
    $valor_dolar = $request->getParam('valor_dolar');
    $tasa = $request->getParam('tasa');

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();

        $consulta = "UPDATE adm_ventas_factura1 SET
                    valor_dolar = :valor_dolar,
                    tasa = :tasa
                    WHERE numero = :numero";

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":numero", $numero);
        $sentencia->bindParam(":valor_dolar", $valor_dolar);
        $sentencia->bindParam(":tasa", $tasa);

        $sentencia->execute();
        
        $data = array('msg' => 'Actualizado Correctamente!');

        $db = null;
        
    }
    catch(PDOException $error){
        $data = array('error' => $error->getMessage());         
    }

    $payload = json_encode($data);   
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->put('/api/adm/ventas/facturas/detalles', function(Request $request, Response $response){
    $database = $request->getParam('database');
    $numero = $request->getParam('numero');
    $descripcion_larga = $request->getParam('descripcion_larga');
    $cambio_moneda = $request->getParam('cambio_moneda');

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();

        $consulta = "UPDATE adm_ventas_factura2 SET
                    descripcion_larga = :descripcion_larga,
                    cambio_moneda = :cambio_moneda
                    WHERE numero = :numero";

        $sentencia = $db->prepare($consulta);
        $sentencia->bindParam(":numero", $numero);
        $sentencia->bindParam(":descripcion_larga", $descripcion_larga);
        $sentencia->bindParam(":cambio_moneda", $cambio_moneda);

        $sentencia->execute();
        
        $data = array('msg' => 'Actualizado Detalle Correctamente!');

        $db = null;
        
    }
    catch(PDOException $error){
        $data = array('error' => $error->getMessage());         
    }

    $payload = json_encode($data);   
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


