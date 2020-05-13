<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/nomina/otrastrans', function(Request $request, Response $response) {
    $database = $request->getParam('database');
    $consulta = "SELECT wc.*, c.descripcion AS desc_cargo, d.descripcion AS desc_departamento, s.valor AS sueldo FROM nomi_detalle_otras_transacciones wc LEFT JOIN nomi_tabla_cargos c ON wc.cod_cargo = c.cod_cargo LEFT JOIN nomi_tabla_departamento d ON wc.cod_departamento = d.cod_departamento LEFT JOIN (select cod_integrante, valor from nomi_detalle_constantes where cod_constante = 'SUELDO') s ON wc.cod_integrante = s.cod_integrante order by wc.cod_integrante, wc.tipo_concepto";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $workers = array();
        if($result) {
            $i = 0;
            $k = -1;
            $total = 0;
            $totalAsignacion = 0;
            $totalDeduccion = 0;
            $concepts = array();
            $ficha = $result[0]->cod_integrante;
            foreach ($result as $worker) {
                if($worker->cod_integrante == $ficha) {
                    $k = $k + 1;
                } else {
                    $ficha = $worker->cod_integrante;
                    $concepts = array();
                    $total = 0;
                    $totalAsignacion = 0;
                    $totalDeduccion = 0;
                    $k = 0;
                    $i = $i + 1;
                }
                $concepts[$k]["cod_concepto"] = $worker->cod_concepto;
                $concepts[$k]["descripcion_concepto"] = $worker->descripcion_concepto;
                $concepts[$k]["tipo_concepto"] = $worker->tipo_concepto;
                $concepts[$k]["unidad"] = $worker->valor;
                $concepts[$k]["factor"] = number_format($worker->factor,2,",",".");
                $concepts[$k]["monto"] = number_format($worker->monto,2,",",".");
                $workers[$i]["cod_integrante"] = $worker->cod_integrante;
                $workers[$i]["nombre_completo"] = $worker->apellidos. ' '. $worker->nombres;
                $workers[$i]["periodo_desde"] = $worker->periodo_desde;
                $workers[$i]["periodo_hasta"] = $worker->periodo_hasta;
                $workers[$i]["cod_cargo"] = $worker->cod_cargo;
                $workers[$i]["desc_cargo"] = $worker->desc_cargo;
                $workers[$i]["cod_departamento"] = $worker->cod_departamento;
                $workers[$i]["desc_departamento"] = $worker->desc_departamento;
                $workers[$i]["cedula_identidad"] = $worker->cedula_identidad;
                $workers[$i]["fecha_ingreso"] = $worker->fecha_ingreso;
                $workers[$i]["sueldo_basico"] = number_format($worker->sueldo,2,",",".");
                if($worker->tipo_concepto == "Asignacion") {
                    $totalAsignacion = $totalAsignacion + $worker->monto;
                    $total = $total + $worker->monto;
                } else {
                    $totalDeduccion = $totalDeduccion + $worker->monto;
                    $total = $total - $worker->monto;
                }
                $workers[$i]["total"] = number_format($total,2,",",".");
                $workers[$i]["subtotalA"] = number_format($totalAsignacion,2,",",".");
                $workers[$i]["subtotalD"] = number_format($totalDeduccion,2,",",".");
                $workers[$i]["conceptos"] = $concepts;
            }    
        }
        $db = null;

        echo json_encode($workers);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/nomina/cestaticket', function(Request $request, Response $response) {

    $database = $request->getParam('database');
    $consulta = "SELECT wc.*, 
                        c.descripcion AS desc_cargo, 
                        d.descripcion AS desc_departamento, 
                        w.fecha_ingreso, 
                        (select valor_cesta_ticket from nomi_configuracion_nomina) AS valor_diario_cesta_ticket 
                        FROM nomi_detalle_cestaticket wc 
                        LEFT JOIN nomi_tabla_cargos c ON wc.cod_cargo = c.cod_cargo 
                        LEFT JOIN nomi_tabla_departamento d ON wc.cod_departamento = d.cod_departamento 
                        INNER JOIN nomi_tabla_integrantes AS w ON wc.cod_integrante = w.cod_integrante 
                        ORDER by wc.cod_integrante, wc.tipo_concepto";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $workers = array();
        if($result) {
            $i = 0;
            $k = -1;
            $total = 0;
            $totalAsignacion = 0;
            $totalDeduccion = 0;
            $concepts = array();
            $ficha = $result[0]->cod_integrante;
            foreach ($result as $worker) {
                if($worker->cod_integrante == $ficha) {
                    $k = $k + 1;
                } else {
                    $ficha = $worker->cod_integrante;
                    $concepts = array();
                    $total = 0;
                    $totalAsignacion = 0;
                    $totalDeduccion = 0;
                    $k = 0;
                    $i = $i + 1;
                }
                $concepts[$k]["cod_concepto"] = $worker->cod_concepto;
                $concepts[$k]["descripcion_concepto"] = $worker->descripcion_concepto;
                $concepts[$k]["tipo_concepto"] = $worker->tipo_concepto;
                $concepts[$k]["factor"] = number_format($worker->factor,2,",",".");
                $concepts[$k]["monto"] = number_format($worker->monto,2,",",".");
                $workers[$i]["cod_integrante"] = $worker->cod_integrante;
                $workers[$i]["nombre_completo"] = $worker->apellidos. ' '. $worker->nombres;
                $workers[$i]["periodo_desde"] = $worker->periodo_desde;
                $workers[$i]["periodo_hasta"] = $worker->periodo_hasta;
                $workers[$i]["cod_cargo"] = $worker->cod_cargo;
                $workers[$i]["desc_cargo"] = $worker->desc_cargo;
                $workers[$i]["cod_departamento"] = $worker->cod_departamento;
                $workers[$i]["desc_departamento"] = $worker->desc_departamento;
                $workers[$i]["cedula_identidad"] = $worker->cedula_identidad;
                $workers[$i]["fecha_ingreso"] = $worker->fecha_ingreso;
                $workers[$i]["nro_cuenta_banco"] = $worker->nro_cuenta_banco;
                if($worker->tipo_concepto == "Asignacion") {
                    $totalAsignacion = $totalAsignacion + $worker->monto;
                    $total = $total + $worker->monto;
                } else {
                    $totalDeduccion = $totalDeduccion + $worker->monto;
                    $total = $total - $worker->monto;
                }
                $workers[$i]["total"] = number_format($total,2,",",".");
                $workers[$i]["subtotalA"] = number_format($totalAsignacion,2,",",".");
                $workers[$i]["subtotalD"] = number_format($totalDeduccion,2,",",".");
                $workers[$i]["valor_diario"] = number_format($worker->valor_diario_cesta_ticket,2,",",".");
                $workers[$i]["valor_mensual"] = number_format(ceil($worker->valor_diario_cesta_ticket * 30),2,",",".");

                $workers[$i]["conceptos"] = $concepts;
            }    
        }
        $db = null;

        echo json_encode($workers);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->post('/api/nomina/cestaticket', function(Request $request, Response $response){

    $database = $request->getParam('database');
    $since = $request->getParam('since');
    $until = $request->getParam('until');
    $consulta = "CALL calcular_cestaticket(:desde,:hasta)";

    try{
        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':desde', $since, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $until, PDO::PARAM_STR);    
            $stmt->execute();
        }
        $db = null;

        echo '{"message": {"text": "Calculo ejecutado con exito"}}';
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});

?>