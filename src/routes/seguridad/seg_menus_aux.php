<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/menus', function (Request $request, Response $response) {

    $consulta = "SELECT 
    idSegMenu, 
    idSegMenuPadre,
    titulo,  
    routeLink,
    nivel,
    ordenVisualizacion,
    expandedIcon,
    collapsedIcon FROM seg_menus_tmp order by ordenVisualizacion, idSegMenu";

    

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $result = $ejecutar->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        $util = new util();

        /*
        $res = array();
        while($row = $ejecutar->fetchAll(PDO::FETCH_OBJ))
        {
           $res = $row;     
        }*/
        $util->setData($result);
       
        //$tree = $util->buildTree($res, 0); 

        $tree = $util->generarMenu(1);
        //$tree = $result;

        $response->withJson($tree);
        //echo json_encode($result, JSON_FORCE_OBJECT);
        

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/menus/items', function (Request $request, Response $response) {

    $consulta = "SELECT titulo as label, idSegMenu as value, ordenVisualizacion FROM seg_menus_tmp where routeLink ='#'
    order by ordenVisualizacion, idSegMenu";

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

$app->get('/api/menus/icons', function (Request $request, Response $response) {

    $consulta = "SELECT concat('fa ', icon) as label, icon as value FROM seg_icons";

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

$app->get('/api/menus/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM seg_menus_tmp WHERE idSegMenu = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $menu = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($menu);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/menusitems', function (Request $request, Response $response) {

   /* $consulta = "SELECT idSegMenu, titulo, ()
                FROM seg_menus menu WHERE menu.routeLInk <> '#'
                ORDER by ordenVisualizacion, idSegMenu";*/

    $consulta = "SELECT idSegMenu, titulo, (SELECT titulo FROM seg_menus_tmp m WHERE m.idSegMenu = menu.idSegMenuPadre) padre
                FROM seg_menus_tmp menu WHERE menu.routeLInk <> '#'
                ORDER BY titulo";

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

$app->get('/api/menus/obtenerBreadCrumb/{id}', function (Request $request, Response $response) {

    $idSegMenu = $request->getAttribute('id');

    $consulta = "SELECT idSegMenu, idSegMenuPadre, titulo FROM seg_menus";
    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        if ($stmt) {
            $stmt->execute();
            //$result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        $db = null;
        $util = new util();
     
        $res = array();
        while($row = $stmt->fetchAll(PDO::FETCH_OBJ))
        {
           $res = $row;     
        }
        

        $tree = $util->obtenerBreakCrumb($res, $idSegMenu);
        $response->withJson($tree);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/menus/obtenerMenuUsuario/{id}', function (Request $request, Response $response) {

    $idUsuario = $request->getAttribute('id');

    $consulta = "CALL obtenerMenuPorUsuario(:idUsuario)";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        if ($stmt) {
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        $db = null;
        $util = new util();
        $util->setData($result);
        $tree = $util->generarMenu(0);

        echo json_encode($tree);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/menus', function (Request $request, Response $response) {

    $idSegMenu = $request->getParam('idSegMenu');
    $idSegMenuPadre = $request->getParam('idSegMenuPadre');
    $titulo = $request->getParam('titulo');
    $routeLink = $request->getParam('routeLink');
    $nivel = $request->getParam('nivel');
    $ordenVisualizacion = $request->getParam('ordenVisualizacion');
    $expandedIcon = $request->getParam('expandedIcon');
    $collapsedIcon = $request->getParam('collapsedIcon');

    $consulta = "CALL insertarItemMenuAux(
        :idHijo,
        :idPadre,
        :titulo,
        :rLink,
        :nivel,
        :ordVisual,
        :expIcon,
        :coIcon)";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idHijo', $idSegMenu);
        $stmt->bindParam(':idPadre', $idSegMenuPadre);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':rLink', $routeLink);
        $stmt->bindParam(':nivel', $nivel);
        $stmt->bindParam(':ordVisual', $ordenVisualizacion);
        $stmt->bindParam(':expIcon', $expandedIcon);
        $stmt->bindParam(':coIcon', $collapsedIcon);

        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->put('/api/menus/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $idSegMenuPadre = $request->getParam('idSegMenuPadre');
    $titulo = $request->getParam('titulo');
    $routeLink = $request->getParam('routeLink');
    $nivel = $request->getParam('nivel');
    $ordenVisualizacion = $request->getParam('ordenVisualizacion');
    $expandedIcon = $request->getParam('expandedIcon');
    $collapsedIcon = $request->getParam('collapsedIcon');

    $consulta = "UPDATE seg_menus_tmp SET

                        idSegMenuPadre  = :idSegMenuPadre,
                        titulo          = :titulo,
                        routeLink       = :routeLink,
                        nivel           = :nivel,
                        ordenVisualizacion = :ordenVisualizacion,
                        expandedIcon = :expandedIcon,
                        collapsedIcon = :collapsedIcon

                    WHERE idSegMenu = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':idSegMenuPadre', $idSegMenuPadre);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':routeLink', $routeLink);
        $stmt->bindParam(':nivel', $nivel);
        $stmt->bindParam(':ordenVisualizacion', $ordenVisualizacion);
        $stmt->bindParam(':expandedIcon', $expandedIcon);
        $stmt->bindParam(':collapsedIcon', $collapsedIcon);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Item menu actualizado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->delete('/api/menus/{id}', function (Request $request, Response $response) {

    $idSegMenu = $request->getAttribute('id');

    $consulta = "DELETE FROM seg_menus_tmp WHERE idSegMenu = $idSegMenu";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Nodo del Menú eliminado correctamente"}}';

    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
