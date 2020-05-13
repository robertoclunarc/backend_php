<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/respuestas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $consulta = "SELECT re.*,
                (SELECT descripcion FROM gen_preguntas_gerencias ger WHERE ger.idPregunta = re.idPregunta) desc_pregunta
             FROM gen_respuestas_valoracion re WHERE idPregunta = $id";

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

$app->get('/api/respuestasserv/{idRefServicio}', function(Request $request, Response $response){

    $id = $request->getAttribute('idRefServicio');
    $consulta = "SELECT re.*,
                (SELECT descripcion FROM gen_preguntas_gerencias ger WHERE ger.idPregunta = re.idPregunta) desc_pregunta
             FROM gen_respuestas_valoracion re WHERE idRefServicio = $id";

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


$app->post('/api/respuestas', function(Request $request, Response $response){


    $idSegUsuario  = $request->getParam('idSegUsuario');
    $idPregunta    = $request->getParam('idPregunta');
    $valoracion_text    = $request->getParam('valoracion_text');
    $valoracion    = $request->getParam('valoracion');
    $idRefServicio    = $request->getParam('idRefServicio');
    

    $consulta = "INSERT INTO gen_respuestas_valoracion 
                    (   
                        idSegUsuario,
                        idPregunta,
                        valoracion_text,
                        valoracion,
                        idRefServicio                
                    ) 
                VALUES 
                    (   
                        :idSegUsuario,
                        :idPregunta,
                        :valoracion_text,
                        :valoracion,
                        :idRefServicio
                    ) ";

    try{

        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':idSegUsuario', $idSegUsuario);
        $stmt->bindParam(':idPregunta', $idPregunta);        
        $stmt->bindParam(':valoracion_text', $valoracion_text); 
        $stmt->bindParam(':valoracion', $valoracion); 
        $stmt->bindParam(':idRefServicio', $idRefServicio); 

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo); 

    }
    catch(PDOException $error){
        echo '{"error": {"text":' .$error->getMessage() .'}}';  
    }
});
