<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/usuarios', function (Request $request, Response $response) {

    $consulta = "SELECT * FROM seg_usuarios";

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



$app->get('/api/usuariosgerencia/{idGerencia}', function (Request $request, Response $response) {

    $idGerencia = $request->getAttribute('idGerencia');

    $consulta = "SELECT seg_usuarios.*,
                (cargos.idConfigGerencia)  idGerencia,
                (CONCAT(seg_usuarios.primerNombre, ' ', seg_usuarios.primerApellido)) nombre_completo
                 FROM seg_usuarios 
                    JOIN config_cargos cargos ON cargos.idConfigCargo = seg_usuarios.idConfigCargo
                 WHERE cargos.idConfigGerencia = $idGerencia";

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

$app->get('/api/ip', function (Request $request, Response $response) {


    $ip = 'No se consiguio el IP';

    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip =  $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        $ip =  $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        $ip =  $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        $ip =  $_SERVER["HTTP_FORWARDED"];
    } else {
        $ip =  $_SERVER["REMOTE_ADDR"];
    }
    echo json_encode($ip);
});

$app->get('/api/usuarios/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT *, 
    (SELECT cargos.idConfigGerencia FROM config_cargos cargos WHERE cargos.idConfigCargo = seg_usuarios.idConfigCargo) idGerencia 
        FROM seg_usuarios WHERE idSegUsuario = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/usuarios/{id}/direcciones', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM seg_direcciones WHERE idSegUsuario = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->get('/api/usuarios/{id}/telefonos', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM seg_telefonos WHERE idSegUsuario = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->get('/api/usuarios/{id}/correos', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM seg_correos WHERE idSegUsuario = $id";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->post('/api/usuarios', function (Request $request, Response $response) {

    $primerNombre           = $request->getParam('primerNombre');
    $segundoNombre          = $request->getParam('segundoNombre');
    $primerApellido         = $request->getParam('primerApellido');
    $segundoApellido        = $request->getParam('segundoApellido');
    $fechaNacimiento        = $request->getParam('fechaNacimiento');
    $sexo                   = $request->getParam('sexo');
    $usuario                = $request->getParam('usuario');
    $contrasenia            = $request->getParam('contrasenia');
    $foto                   = $request->getParam('foto');
    $estadoCivil            = $request->getParam('estadoCivil');
    $cargo                  = $request->getParam('idConfigCargo');
    $estatus                = $request->getParam('estatus');
    $rutaImagen                = $request->getParam('rutaImagen');



    $consulta = "INSERT INTO seg_usuarios 
                    (   
                        primerNombre,
                        segundoNombre,
                        primerApellido,
                        segundoApellido,
                        fechaNacimiento,
                        sexo,
                        usuario,
                        contrasenia,
                        foto,
                        estatus,
                        estadoCivil,
                        idConfigCargo,
                        rutaImagen
                    ) 
                VALUES 
                    (   
                        :primerNombre,
                        :segundoNombre,
                        :primerApellido,
                        :segundoApellido,
                        :fechaNacimiento,
                        :sexo,
                        :usuario,
                        :contrasenia,
                        :foto,
                        :estatus,
                        :estadoCivil,
                        :idConfigCargo,
                        :rutaImagen
                    ) ";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':primerNombre', $primerNombre);
        $stmt->bindParam(':segundoNombre', $segundoNombre);
        $stmt->bindParam(':primerApellido', $primerApellido);
        $stmt->bindParam(':segundoApellido', $segundoApellido);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasenia', $contrasenia);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':estadoCivil', $estadoCivil);
        $stmt->bindParam(':idConfigCargo', $cargo);
        $stmt->bindParam(':estatus', $estatus);
        $stmt->bindParam(':rutaImagen', $rutaImagen);


        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;

        $user = array('ObjectId' => $id);
        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/login', function (Request $request, Response $response) {

    $usuario = $request->getParam('usuario');
    $contrasenia = $request->getParam('contrasenia');

    $consulta = "SELECT u.*,
                    (SELECT cargos.idConfigGerencia FROM config_cargos cargos WHERE cargos.idConfigCargo = u.idConfigCargo) idGerencia
                 FROM seg_usuarios as u WHERE 
                u.usuario='$usuario' and u.contrasenia='$contrasenia'";

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

$app->put('/api/usuarios/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $primerNombre           = $request->getParam('primerNombre');
    $segundoNombre          = $request->getParam('segundoNombre');
    $primerApellido         = $request->getParam('primerApellido');
    $segundoApellido        = $request->getParam('segundoApellido');
    $fechaNacimiento        = $request->getParam('fechaNacimiento');
    $sexo                   = $request->getParam('sexo');
    $usuario                = $request->getParam('usuario');
    $contrasenia            = $request->getParam('contrasenia');
    $foto                   = $request->getParam('foto');
    $estadoCivil            = $request->getParam('estadoCivil');
    $cargo                  = $request->getParam('idConfigCargo');
    $estatus                = $request->getParam('estatus');
    $rutaImagen                = $request->getParam('rutaImagen');

    $consulta = "UPDATE seg_usuarios SET 

                        primerNombre    = :primerNombre,
                        segundoNombre   = :segundoNombre,
                        primerApellido  = :primerApellido,
                        segundoApellido = :segundoApellido,
                        fechaNacimiento = :fechaNacimiento,
                        sexo            = :sexo,
                        usuario         = :usuario,
                        contrasenia     = :contrasenia,
                        foto            = :foto,
                        estatus         = :estatus,
                        estadoCivil     = :estadoCivil,
                        idConfigCargo   = :idConfigCargo,
                        rutaImagen   = :rutaImagen
                    
                    WHERE idSegUsuario = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':primerNombre', $primerNombre);
        $stmt->bindParam(':segundoNombre', $segundoNombre);
        $stmt->bindParam(':primerApellido', $primerApellido);
        $stmt->bindParam(':segundoApellido', $segundoApellido);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasenia', $contrasenia);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':estatus', $estatus);
        $stmt->bindParam(':estadoCivil', $estadoCivil);
        $stmt->bindParam(':idConfigCargo', $cargo);
        $stmt->bindParam(':rutaImagen', $rutaImagen);

        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Usuario actualizado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});


$app->delete('/api/usuarios/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $consulta = "DELETE FROM seg_usuarios WHERE idSegUsuario = $id";

    try {

        $db = new db();
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        $db = null;

        echo '{"message": {"text": "Usuario eliminado correctamente"}}';
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});

$app->post('/api/subirimagenusr/{archAnterior}', function (Request $request, Response $response) {
    $files = $request->getUploadedFiles();
    $archivoAnterior = $request->getAttribute('archAnterior');
    if (empty($files['myfile2'])) {
        throw new Exception('Expected a newfile');
    }

    $newfile = $files['myfile2'];

    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        //echo $newfile->getClientMediaType();
        if ($archivoAnterior != "-1") {
            if (file_exists("../public/subidos/fotosusers/$archivoAnterior")) {
                unlink("../public/subidos/fotosusers/$archivoAnterior");
            }
        }
        $newfile->moveTo("../public/subidos/fotosusers/$uploadFileName");

        //Cambiar el tamaño de la imagen una vez guardada en la carpeta
        $resize = new ResizeImage("../public/subidos/fotosusers/$uploadFileName");
        $resize->resizeTo(200, 200);
        $resize->saveImage("../public/subidos/fotosusers/$uploadFileName");
    }
    //echo json_encode($newfile);
    echo '{"nombreArchivo": "' . $uploadFileName . '"}';
});


$app->post('/api/subirimgpropia/{archAnterior}', function (Request $request, Response $response) {

    $files = $request->getUploadedFiles();
    $archivoAnterior = $request->getAttribute('archAnterior');

    if (empty($files['myfile3'])) {
        throw new Exception('Expected a newfile');
    }

    $newfile = $files['myfile3'];

    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        //echo $newfile->getClientMediaType();
        if ($archivoAnterior != "-1") {
            if (file_exists("../public/subidos/fotosusers/$archivoAnterior")) {
                unlink("../public/subidos/fotosusers/$archivoAnterior");
            }
        }
        $newfile->moveTo("../public/subidos/fotosusers/$uploadFileName");

        //Cambiar el tamaño de la imagen una vez guardada en la carpeta
        $resize = new ResizeImage("../public/subidos/fotosusers/$uploadFileName");
        $resize->resizeTo(200, 200);
        $resize->saveImage("../public/subidos/fotosusers/$uploadFileName");
    }
    //echo json_encode($newfile);
    echo '{"nombreArchivo": "' . $uploadFileName . '"}';
});


$app->post('/api/quitarimagenusr/{archAnterior}', function (Request $request, Response $response) {
    $archivoAnterior = $request->getAttribute('archAnterior');
    if (file_exists("../public/subidos/fotosusers/$archivoAnterior")) {
        unlink("../public/subidos/fotosusers/$archivoAnterior");
    }
    echo '{"nombreArchivo": "elimino archivo"}';
});


$app->get('/api/usuariosverificagerencia/{idConfigGerencia}', function (Request $request, Response $response) {

    $idConfigGerencia = $request->getAttribute('idConfigGerencia');

    $consulta = "SELECT 	usr.idSegUsuario,
	                        usr.primerNombre,
	                        usr.usuario,
	                        usr.idConfigCargo,
	                        cargos.nombre,
	                        gerencia.idConfigGerencia,
	                        gerencia.nombre,
	                        perfiles.idSegPerfil,
	                        rolper.idSegRol AS roles_perfil,
	                        roles_dir.idSegRol AS roles_directos,
	                        roles.nombre, roles.codigo 
                FROM seg_usuarios AS usr
	            INNER JOIN config_cargos cargos ON cargos.idConfigCargo = usr.idConfigCargo
	            INNER JOIN config_gerencias gerencia ON gerencia.idConfigGerencia = cargos.idConfigGerencia
	            LEFT JOIN seg_perfiles_usuarios perfiles ON usr.idSegUsuario = perfiles.idSegUsuario
	            LEFT JOIN seg_roles_perfiles rolper ON perfiles.idSegPerfil = rolper.idSegPerfil
	            LEFT JOIN seg_roles_usuarios roles_dir ON roles_dir.idSegUsuario = usr.idSegUsuario
	            LEFT JOIN seg_roles roles ON roles.idSegRol = roles_dir.idSegRol OR roles.idSegRol = rolper.idSegRol
                WHERE gerencia.idConfigGerencia = $idConfigGerencia
                    AND roles.codigo = 'ROL-VTS'
                GROUP BY usr.usuario";

    try {

        $db = new db();

        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $user = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($user);
    } catch (PDOException $error) {
        echo '{"error": {"text":' . $error->getMessage() . '}}';
    }
});
