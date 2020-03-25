<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/empresagerenciaarea', function(Request $request, Response $response){
    $consulta = "SELECT 	gea.idGenEmpreAreaGeren,
	gea.IdComprasEmpresa,
	(SELECT nombre_empresa FROM compras_empresa em WHERE em.IdComprasEmpresa = gea.IdComprasEmpresa) AS nombre_empresa,
	gea.idConfigGerencia,
	(SELECT nombre FROM config_gerencias g WHERE g.idConfigGerencia = gea.idConfigGerencia) AS nombre_gerencia,
	gea.idGenAreaNegocio,
	(SELECT nombre FROM gen_area_negocio a WHERE a.idGenAreaNegocio = gea.idGenAreaNegocio) AS nombre_area
FROM 
 gen_empre_area_gerencia gea";

    try {
        $cnn = new db();

        $db = $cnn->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($result);

        $cnn = null;

    } catch (PDOException $error) {
        //throw $th;
    }

});

$app->get('/api/consultaSiIngresado/{empre}/{geren}/{area}', function(Request $request, Response $response){

    $empre = $request->getAttribute("empre");
    $geren = $request->getAttribute("geren");
    $area = $request->getAttribute("area");

    $consulta = "SELECT gea.* 
            FROM gen_empre_area_gerencia gea
            WHERE gea.IdComprasEmpresa = $empre AND gea.idConfigGerencia = $geren AND gea.idGenAreaNegocio = $area";

    try {
        $cnn = new db();

        $db = $cnn->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($result);

        $cnn = null;

    } catch (PDOException $error) {
        //throw $th;
    }

});

$app->post('/api/empresagerenciaarea', function(Request $request, Response $response){

    $idempre = $request->getParam("IdComprasEmpresa");
    $idgerencia = $request->getParam("idConfigGerencia");
    $idarea = $request->getParam("idGenAreaNegocio");

    $consulta = "INSERT INTO gen_empre_area_gerencia 
                    (   
                        IdComprasEmpresa,
                        idConfigGerencia,
                        idGenAreaNegocio                    
                    ) 
                VALUES 
                    (   
                        :IdComprasEmpresa,
                        :idConfigGerencia,
                        :idGenAreaNegocio
                    ) ";

    try {

    
        $db = new db();
        $db = $db->conectar();
        
        $stmt = $db->prepare($consulta); 
        $stmt->bindParam(':IdComprasEmpresa', $idempre);
        $stmt->bindParam(':idConfigGerencia', $idgerencia);        
        $stmt->bindParam(':idGenAreaNegocio', $idarea); 
      
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo); 

       /* $cnn = new db();

        $db = $cnn->conectar();
      
        $ejecutar = $db->prepare($consulta);
        $ejecutar->bindParam(':IdComprasEmpresa', $idempre);
        $ejecutar->bindParam(':idConfigGerencia', $idgerencia);        
        $ejecutar->bindParam(':idGenAreaNegocio', $idarea); 
        $ejecutar->execute();

        $id = $ejecutar->lastInsertId();
      

        $cargo = array('ObjectId' => $id);
        echo json_encode($cargo); 

        $cnn = null;
        $db = null;*/

    } catch (PDOException $error) {
        //throw $th;
    }

});

$app->delete('/api/empresagerenciaarea/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute("id");

    $consulta = "DELETE FROM gen_empre_area_gerencia WHERE idGenEmpreAreaGeren = $id";

    try {
        $cnn = new db();

        $db = $cnn->conectar();
        $ejecutar = $db->prepare($consulta);
        $ejecutar->execute();

        echo '{"message": {"text": "Centro de costos eliminado correctamente"}}';  

        $cnn = null;

    } catch (PDOException $error) {
        //throw $th;
    }

});