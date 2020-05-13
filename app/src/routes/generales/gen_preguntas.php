<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/preguntas/{idGerencia}', function(Request $request, Response $response){

    $id = $request->getAttribute('idGerencia');
    $consulta = "SELECT * FROM gen_preguntas_gerencias WHERE idConfigGerencia = $id";

    try{

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $users = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($users);

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});