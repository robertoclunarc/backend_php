<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/contaConfig', function(Request $request, Response $response){

    $consulta = "SELECT cod_empresa, nombre_empresa, rif, base_de_datos FROM conta_configuracion_empresas";

    try{
        $db = new dbGlobal();
        $db->changeDB("test");
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/contaCompanyLogo/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $consulta = "SELECT logo FROM conta_configuracion_empresas WHERE cod_empresa = $id";

    try{
        $db = new dbGlobal();
        $db->changeDB("test");
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

$app->get('/api/nomiConfig', function(Request $request, Response $response){

    $consulta = "SELECT cod_empresa, nombre AS nombre_empresa, rif, base_de_datos  FROM nomi_configuracion_empresas";

    try{

        $db = new dbGlobal();
        $db->changeDB("test");
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});


$app->get('/api/admConfig', function(Request $request, Response $response){

    $consulta = "SELECT id AS cod_empresa, nombre AS nombre_empresa, '' AS rif, base_de_datos, tipo_factura FROM empresas_administrativo";

    try{
        $db = new dbGlobal();
        $db->changeDB("test");
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});

/*
$app->post('/api/login', function(Request $request, Response $response) {

    $login              = $request->getParam('login');
    $password           = $request->getParam('password');
    
    $consulta = "select u.nombre, u.login, u.cod_perfil, pu.descripcion from sparrow_tabla_usuarios as u inner join sparrow_tabla_perfil_usuario as pu on u.cod_perfil = pu.cod_perfil where u.login='$login' and u.pass='$password'";

    try {
        $db = new dbGlobal();

        $db->changeDB("test");
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);

        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($result);
    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';
    }
});
*/

?>