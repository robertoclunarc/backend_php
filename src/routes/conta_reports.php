<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/*$app->get('/api/conta/balance/check1', function(Request $request, Response $response) {

    $database = $request->getParam('database');
    $since = $request->getParam('since');
    $until = $request->getParam('until');

    $consulta = "CALL balance_comprobacion(:desde,:hasta)";

    try {

        $db = new db();
        $db->changeDB($database);
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':desde', $since, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $until, PDO::PARAM_STR);    
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }
        
        $db = null;
       
        echo json_encode($result);
    
    }
    catch(PDOException $error) {
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }


});
*/
$app->get('/api/conta/balance/check1', function(Request $request, Response $response) {

    /*$database = $request->getParam('database');
    $since = $request->getParam('since');
    $until = $request->getParam('until');
    $consulta = "SELECT * FROM (select cod_contable, descripcion, saldo as saldo_inicial, nivel, debe, haber from conta_plan_de_cuentas where nivel < 5 UNION ALL SELECT p.cod_contable, p.descripcion, p.saldo AS saldo_inicial, p.nivel, SUM(if(d.debe is null,0,d.debe)) AS debe, SUM(if(d.haber is null,0,d.haber)) AS haber FROM conta_plan_de_cuentas AS p LEFT JOIN (SELECT cod_contable, debe, haber FROM conta_asientos_detalles WHERE fecha_documento BETWEEN '$since' AND '$until') AS d ON p.cod_contable = d.cod_contable WHERE p.nivel = 5 AND (d.debe > 0 OR d.haber > 0) GROUP BY p.cod_contable) AS balance ORDER BY cod_contable";
*/
    $database = $request->getParam('database');
    $since = $request->getParam('since');
    $until = $request->getParam('until');

    $consulta = "CALL balance_comprobacion(:desde,:hasta)";


    try {

        $db = new dbGlobal();
        $db->changeDB($database);
        $db = $db->conectar();

        $stmt = $db->prepare($consulta); 
        if($stmt){
            $stmt->bindParam(':desde', $since, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $until, PDO::PARAM_STR);    
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }

        $balance_totalized = [];
        $total1 = [];
        $nivel1 = "";
        $total2 = [];
        $nivel2 = "";
        $total3 = [];
        $nivel3 = "";
        $total4 = [];
        $nivel4 = "";
        $total5 = [];
        $nivel5 = "";
        foreach ($result as $cuenta) {
            switch ($cuenta->nivel) {
                case '1':
                    if($nivel1 <> $cuenta->cuenta_con) {
                        $nivel1 = $cuenta->cuenta_con;
                        $total1 = [];
                        $total1["inicial"] = floatval($cuenta->saldo_inicial);
                        $total1["debe"] = floatval($cuenta->debe);
                        $total1["haber"] = floatval($cuenta->haber);
                        $total1["final"] = $total1["inicial"] +  $total1["debe"] - $total1["haber"];
                    }
                    $balance_totalized[$nivel1]["cuenta"] = $nivel1;
                    $balance_totalized[$nivel1]["descripcion"] = $cuenta->descripcion_cuenta;
                    $balance_totalized[$nivel1]["nivel"] = $cuenta->nivel;
                    $balance_totalized[$nivel1]["total"] = $total1;
                    break;
                case '2':
                    if($nivel2 <> $cuenta->cuenta_con) {
                        $nivel2 = $cuenta->cuenta_con;
                        $total2 = [];
                        $total2["inicial"] = floatval($cuenta->saldo_inicial);
                        $total2["debe"] = floatval($cuenta->debe);
                        $total2["haber"] = floatval($cuenta->haber);
                        $total2["final"] = $total2["inicial"] +  $total2["debe"] - $total2["haber"];
                    }
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["cuenta"] = $nivel2;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["descripcion"] = $cuenta->descripcion_cuenta;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["nivel"] = $cuenta->nivel;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["total"] = $total2;
                    break;
                case '3':
                    if($nivel3 <> $cuenta->cuenta_con) {
                        $nivel3 = $cuenta->cuenta_con;
                        $total3 = [];
                        $total3["inicial"] = floatval($cuenta->saldo_inicial);
                        $total3["debe"] = floatval($cuenta->debe);
                        $total3["haber"] = floatval($cuenta->haber);
                        $total3["final"] = $total3["inicial"] +  $total3["debe"] - $total3["haber"];
                    }
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["cuenta"] = $nivel3;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["descripcion"] = $cuenta->descripcion_cuenta;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["nivel"] = $cuenta->nivel;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["total"] = $total3;
                    break;
                case '4':
                    if($nivel4 != $cuenta->cuenta_con) {
                        $nivel4 = $cuenta->cuenta_con;
                        $total4 = [];
                        $total4["inicial"] = floatval($cuenta->saldo_inicial);
                        $total4["debe"] = floatval($cuenta->debe);
                        $total4["haber"] = floatval($cuenta->haber);
                        $total4["final"] = $total4["inicial"] +  $total4["debe"] - $total4["haber"];
                    }
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["cuenta"] = $nivel4;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["descripcion"] = $cuenta->descripcion_cuenta;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["nivel"] = $cuenta->nivel;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["total"] = $total4;
                 
                    break;
                case '5':
                    if($nivel5 <> $cuenta->cuenta_con) {
                        $nivel5 = $cuenta->cuenta_con;
                        $total5 = [];
                        $total5["inicial"] = floatval($cuenta->saldo_inicial);
                        $total5["debe"] = floatval($cuenta->debe);
                        $total5["haber"] = floatval($cuenta->haber);
                        $total5["final"] = $total5["inicial"] +  $total5["debe"] - $total5["haber"];
                    } else {
                        $total5["inicial"] = $total5["inicial"] +  floatval($cuenta->saldo_inicial);
                        $total5["debe"] = $total5["debe"] +  floatval($cuenta->debe);
                        $total5["haber"] = $total5["haber"] +  floatval($cuenta->haber);
                        $total5["final"] = $total5["inicial"] +  $total5["debe"] - $total5["haber"];
                    }
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["subcuenta"][$nivel5]["cuenta"] = $nivel5;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["subcuenta"][$nivel5]["descripcion"] = $cuenta->descripcion_cuenta;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["subcuenta"][$nivel5]["nivel"] = $cuenta->nivel;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["subcuenta"][$nivel5]["total"] = $total5;
                    //print_r($cuenta->saldo_inicial);
                    //print_r("<br>");
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["total"]["inicial"] += $cuenta->saldo_inicial;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["total"]["debe"] +=  $cuenta->debe;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["total"]["haber"] +=  $cuenta->haber;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["subcuenta"][$nivel4]["total"]["final"] +=  $total5["final"];

                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["total"]["inicial"] +=  $cuenta->saldo_inicial;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["total"]["debe"] +=  $cuenta->debe;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["total"]["haber"] +=  $cuenta->haber;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["subcuenta"][$nivel3]["total"]["final"] +=  $total5["final"];

                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["total"]["inicial"] +=  $cuenta->saldo_inicial;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["total"]["debe"] +=  $cuenta->debe;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["total"]["haber"] +=  $cuenta->haber;
                    $balance_totalized[$nivel1]["subcuenta"][$nivel2]["total"]["final"] +=  $total5["final"];

                    $balance_totalized[$nivel1]["total"]["inicial"] +=  $cuenta->saldo_inicial;
                    $balance_totalized[$nivel1]["total"]["debe"] +=  $cuenta->debe;
                    $balance_totalized[$nivel1]["total"]["haber"] +=  $cuenta->haber;
                    $balance_totalized[$nivel1]["total"]["final"] +=  $total5["final"];
                    break;
            }
        }
        $db = null;
        //echo json_encode($balance_totalized);
        $clean_balance = [];
        $index1 = 0;
        foreach ($balance_totalized as &$level1) {
            $addLevel1 = true;
            if($level1["total"]["inicial"]  == 0 && $level1["total"]["debe"]  == 0 && $level1["total"]["haber"]  == 0 && $level1["total"]["final"]  == 0){
                $addLevel1 = false;
            } else {
                $level1["total"]["inicial"] = number_format($level1["total"]["inicial"],2,",",".");
                $level1["total"]["debe"] = number_format($level1["total"]["debe"],2,",",".");
                $level1["total"]["haber"] = number_format($level1["total"]["haber"],2,",",".");
                $level1["total"]["final"] = number_format($level1["total"]["final"],2,",",".");
            }
            if($addLevel1 == true) {
                $clean_balance[$index1] = $level1;
                $accounts2 = [];
                if(isset($level1["subcuenta"])) {
                    $index2 = 0;
                    foreach ($level1["subcuenta"] as &$level2) {
                        $addLevel2 = true;
                        if($level2["total"]["inicial"]  == 0 && $level2["total"]["debe"]  == 0 && $level2["total"]["haber"]  == 0 && $level2["total"]["final"]  == 0){
                            $addLevel2 = false;
                        } else {
                            $level2["total"]["inicial"] = number_format($level2["total"]["inicial"],2,",",".");
                            $level2["total"]["debe"] = number_format($level2["total"]["debe"],2,",",".");
                            $level2["total"]["haber"] = number_format($level2["total"]["haber"],2,",",".");
                            $level2["total"]["final"] = number_format($level2["total"]["final"],2,",",".");
                        }
                        if($addLevel2 == true) {
                            $accounts2[$index2] = $level2;
                            $accounts3 = [];
                            if(isset($level2["subcuenta"])) {
                                $index3 = 0;
                                foreach ($level2["subcuenta"] as &$level3) {
                                    $addLevel3 = true;
                                    if($level3["total"]["inicial"]  == 0 && $level3["total"]["debe"]  == 0 && $level3["total"]["haber"]  == 0 && $level3["total"]["final"]  == 0){
                                        $addLevel3 = false;
                                    } else {
                                        $level3["total"]["inicial"] = number_format($level3["total"]["inicial"],2,",",".");
                                        $level3["total"]["debe"] = number_format($level3["total"]["debe"],2,",",".");
                                        $level3["total"]["haber"] = number_format($level3["total"]["haber"],2,",",".");
                                        $level3["total"]["final"] = number_format($level3["total"]["final"],2,",",".");
                                    }
                                    if($addLevel3 == true) {
                                        $accounts3[$index3] = $level3;
                                        $accounts4 = [];
                                        if(isset($level3["subcuenta"])) {
                                            $index4 = 0;
                                            foreach ($level3["subcuenta"] as &$level4) {
                                                $addLevel4 = true;
                                                if($level4["total"]["inicial"]  == 0 && $level4["total"]["debe"]  == 0 && $level4["total"]["haber"]  == 0 && $level4["total"]["final"]  == 0){
                                                    $addLevel4 = false;
                                                } else {
                                                    $level4["total"]["inicial"] = number_format($level4["total"]["inicial"],2,",",".");
                                                    $level4["total"]["debe"] = number_format($level4["total"]["debe"],2,",",".");
                                                    $level4["total"]["haber"] = number_format($level4["total"]["haber"],2,",",".");
                                                    $level4["total"]["final"] = number_format($level4["total"]["final"],2,",",".");
                                                }
                                                if($addLevel4 == true) {
                                                    $accounts4[$index4] = $level4;
                                                    foreach ($level4["subcuenta"] as &$level5) {
                                                        $level5["total"]["inicial"] = number_format($level5["total"]["inicial"],2,",",".");
                                                        $level5["total"]["debe"] = number_format($level5["total"]["debe"],2,",",".");
                                                        $level5["total"]["haber"] = number_format($level5["total"]["haber"],2,",",".");
                                                        $level5["total"]["final"] = number_format($level5["total"]["final"],2,",",".");
                                                    }
                                                    $accounts4[$index4]["cantsubcuenta"] = count($level4["subcuenta"]);
                                                    $accounts4[$index4]["subcuenta"] = array_values($level4["subcuenta"]);
                                                    $index4++;
                                                }
                                            }
                                        }
                                        $accounts3[$index3]["cantsubcuenta"] = count($accounts4);
                                        $accounts3[$index3]["subcuenta"] = $accounts4;
                                        $index3++;
                                    }
                                }
                            }
                            $accounts2[$index2]["cantsubcuenta"] = count($accounts3);
                            $accounts2[$index2]["subcuenta"] = $accounts3;
                            $index2++;
                        }
                    }
                }
                $clean_balance[$index1]["cantsubcuenta"] = count($accounts2);
                $clean_balance[$index1]["subcuenta"] = $accounts2;
                $index1++;
            }
        }
        echo json_encode($clean_balance);
    }
    catch(PDOException $error) {
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


?>