<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/api/productos', function (Request $request, Response $response) {

	$consulta = "SELECT 
                            g.idAdmGrupoProducto,
                            um.idAdmTipoMedida,
                            g.nombre as grupo, 
                            sg.nombre as subgrupo, 
                            p.*,
                                (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioCreacion)  as usuarioCreacion, 
                                (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioModificacion)  as usuarioModificacion,
                                (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioValidacion)  as usuarioAprobacion,
                                (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioValInfo)  as usuarioValidacion

                            FROM 
                                adm_productos p 
                            LEFT JOIN 
                                adm_sub_grupos_productos sg ON p.idAdmSubGrupoProducto = sg.idAdmSubGrupoProducto
                            LEFT JOIN 
                                adm_grupos_productos g ON g.idAdmGrupoProducto = p.idAdmGrupoProducto
                            LEFT JOIN 
                                adm_unidad_medidas um ON p.idAdmUnidadMedida = um.idAdmUnidadMedida
                            WHERE p.activo = 1
                                    
                            ORDER BY  
                                p.fechaModificacion DESC, p.idAdmProducto";

	try {

		$db = new db();

		$db = $db->conectar();
		$ejecutar = $db->query($consulta);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->post('/api/productos/busqueda', function (Request $request, Response $response) {

	$campos = json_decode($request->getParam('campos'));
	$frase = $request->getParam('frase');

	$where = "";
	$condicion = "";
	$i = 0;

	foreach ($campos as $clave => $valor) {

		/*if(strtoupper($valor)=='CODIGO'){
            $condicion = "ps.serial" . " like '%" . $frase . "%'";
        }
        else{*/
		$condicion = "p." . $valor . " like '%" . $frase . "%'";
		//} 

		if ($i == 0)
			$where =  $condicion;
		else
			$where =  $where . " OR " . $condicion;

		$i = $i + 1;
	}

	$consulta = "SELECT 
    IF(ps.serial IS NULL,codigo, ps.serial) AS codigo,
        g.idAdmGrupoProducto,
        um.idAdmTipoMedida,
        g.nombre as grupo, 
        sg.nombre as subgrupo, 
        p.*,
        concat_ws(' - ', IF(ps.serial IS NULL,codigo, ps.serial), p.nombre, p.uso) as descripcionCompleta
          
    FROM 
            adm_productos p 
        LEFT JOIN
            adm_activos ps
        ON 
            p.idAdmProducto = ps.idAdmProducto 
        LEFT JOIN 
            adm_sub_grupos_productos sg ON p.idAdmSubGrupoProducto = sg.idAdmSubGrupoProducto
        LEFT JOIN 
            adm_grupos_productos g ON g.idAdmGrupoProducto = sg.idAdmGrupoProducto
        LEFT JOIN 
            adm_unidad_medidas um ON p.idAdmUnidadMedida = um.idAdmUnidadMedida
    WHERE " . $where . ' and p.activo=1';

	try {

		$db = new db();
		$db = $db->conectar();

		$ejecutar = $db->query($consulta);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});


$app->post('/api/productos/busqueda/json', function (Request $request, Response $response) {

	$campos = $request->getParam('campos');
	$frase = $request->getParam('frase');

	$where = "";
	$condicion = "";
	$i = 0;

	foreach ($campos as $clave => $valor) {

		if (strtoupper($valor) == 'CODIGO') {
			$condicion = "ps.serial" . " like '%" . $frase . "%'";
		} else {
			$condicion = "p." . $valor . " like '%" . $frase . "%'";
		}

		if ($i == 0)
			$where =  $condicion;
		else
			$where =  $where . " OR " . $condicion;

		$i = $i + 1;
	}

	$consulta = "SELECT 
    IF(ps.serial IS NULL,'Sin código', ps.serial) AS codigo,
        g.idAdmGrupoProducto,
        um.idAdmTipoMedida,
        g.nombre as grupo, 
        sg.nombre as subgrupo, 
        p.*,
        concat_ws(' - ', IF(ps.serial IS NULL,'Sin código', ps.serial), p.nombre, p.uso) as descripcionCompleta
          
    FROM 
            adm_productos p 
        LEFT JOIN
            adm_activos ps
        ON 
            p.idAdmProducto = ps.idAdmProducto
        INNER JOIN 
            adm_sub_grupos_productos sg ON p.idAdmSubGrupoProducto = sg.idAdmSubGrupoProducto
        INNER JOIN 
            adm_grupos_productos g ON g.idAdmGrupoProducto = sg.idAdmGrupoProducto
        LEFT JOIN 
            adm_unidad_medidas um ON p.idAdmUnidadMedida = um.idAdmUnidadMedida
    WHERE " . $where . ' and p.activo=1';

	try {

		$db = new db();
		$db = $db->conectar();

		$ejecutar = $db->query($consulta);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->get('/api/productos/{id}', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$consulta = "SELECT 
                        g.idAdmGrupoProducto,
                        um.idAdmTipoMedida,
                        g.nombre as grupo, 
                        sg.nombre as subgrupo, 
                        p.*,
                            (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioCreacion)  as usuarioCreacion, 
                            (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioModificacion)  as usuarioModificacion,
                            (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioValidacion)  as usuarioAprobacion,
                            (select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioValInfo)  as usuarioValidacion,
							(select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioAprobAlmacen) as nombreUsrAproboAlmacen,
							(select concat(primerNombre, ' ', primerApellido) from seg_usuarios u where u.idSegUsuario=p.idUsuarioModAlmacen) as nombreUsrModAlmacen
                        
                        FROM 
                            adm_productos p 
                        LEFT JOIN 
                            adm_sub_grupos_productos sg ON p.idAdmSubGrupoProducto = sg.idAdmSubGrupoProducto
                        LEFT JOIN 
                            adm_grupos_productos g ON g.idAdmGrupoProducto = p.idAdmGrupoProducto
                        LEFT JOIN 
                            adm_unidad_medidas um ON p.idAdmUnidadMedida = um.idAdmUnidadMedida
                        WHERE p.idAdmProducto = $idAdmProducto        
                        ORDER BY  
                            p.idAdmProducto";
	try {

		$db = new db();
		$db = $db->conectar();

		$ejecutar = $db->query($consulta);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->get('/api/productossolped/{idConfigGerencia}/{idActivo}/{campo}/{valor}', function (Request $request, Response $response) {

	$idConfigGerencia = $request->getAttribute('idConfigGerencia');
	$idActivo = $request->getAttribute('idActivo');
	$campo = $request->getAttribute('campo');
	$valor = $request->getAttribute('valor');

	$consulta = "SELECT apli.idConfigGerencia,
	                    apli.idAreaTrabajoGerencia,
	                    p.codigo,
	                    p.nombre,
	                    p.uso
                FROM adm_productos p
                INNER JOIN adm_aplicabilidad_producto apli ON apli.idAdmProductoPadre = p.idAdmProducto
                INNER JOIN
	                (SELECT area2.idAreaTrabajo 
	                FROM adm_areas_trabajo area2
	                INNER JOIN (
			            SELECT idGenAreaNegocio 
			            FROM compras_activo_gerencia_area_negocio
			            WHERE idConfigGerencia = $idConfigGerencia
				        AND idAdmActivo = $idActivo) negocios ON negocios.idGenAreaNegocio = area2.idGenAreaNegocio)
                areas ON areas.idAreaTrabajo = apli.idAreaTrabajoGerencia
                WHERE apli.idConfigGerencia = $idConfigGerencia";
	$group = " GROUP BY p.codigo";

	$sentencia = $consulta . " AND p." . $campo . " LIKE '%" . $valor . "%'" . $group;
	//$sentencia = $consulta.$group;

	try {

		$db = new db();
		$db = $db->conectar();

		$ejecutar = $db->query($sentencia);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->post('/api/productos/{id}/complementarias', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');
	$aplicaComplementaria = $request->getParam('aplicaComplementaria');

	$consulta = "SELECT 
                        c.*,
                        u.idAdmTipoMedida,
                        p.nombre as 'propiedad',
                        concat(u.nombre, concat(' ', u.abrev)) as 'unidadMedida',
                        (SELECT 
                            sub.nombre AS nombe_subclasi
                        FROM
                            adm_propiedad_sub_tipo_clasificacion sub_m
                        JOIN
                            adm_sub_tipos_clasificacion sub ON 
                                sub_m. idAdmSubTipoClasificacion = sub.idAdmSubTipoClasificacion
                        WHERE sub_m.idAdmPropiedad = c.idAdmPropiedad AND sub_m.idAdmSubTipoClasificacion = c.idAdmSubTipoClasificacion) AS nombre_subcla,

                        (SELECT 
                            tipo.nombre AS nombre
                        FROM
                        adm_propiedad_sub_tipo_clasificacion sub_m
                        JOIN
                            adm_sub_tipos_clasificacion sub ON 
                            sub_m. idAdmSubTipoClasificacion = sub.idAdmSubTipoClasificacion
                        JOIN 
                            adm_tipos_clasificacion tipo 
                            ON tipo.idAdmTipoClasificacion = sub.idAdmTipoClasificacion
                        WHERE sub_m.idAdmPropiedad = c.idAdmPropiedad AND sub_m.idAdmSubTipoClasificacion = c.idAdmSubTipoClasificacion) AS nombre_clasi
                        
                    FROM 
                        adm_complementarias_producto c 
                    LEFT JOIN
                        adm_propiedades p
                        ON
                        p.idAdmPropiedad = c.idAdmPropiedad
                    LEFT JOIN 
                        adm_unidad_medidas u
                        ON
                        u.idAdmUnidadMedida = c.idAdmUnidadMedida		
                    WHERE 
                        c.idAdmProducto = $idAdmProducto and c.aplicaComplementaria = $aplicaComplementaria                    
                    
                    ORDER BY 
                        c.idAdmComplementariaProducto ";

	try {

		$db = new db();
		$db = $db->conectar();

		$ejecutar = $db->query($consulta);
		$result = $ejecutar->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		$response->withJson($result);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->post('/api/productos', function (Request $request, Response $response) {

	$codigo = $request->getParam("codigo");
	$nombre = $request->getParam("nombre");
	$uso = $request->getParam("uso");
	$idAdmGrupoProducto = $request->getParam("idAdmGrupoProducto");
	$idAdmSubGrupoProducto = $request->getParam("idAdmSubGrupoProducto");
	$idAdmUnidadMedida = $request->getParam("idAdmUnidadMedida");
	$idAdmMaterialProducto = $request->getParam("idAdmMaterialProducto");
	$idAdmColorProducto = $request->getParam("idAdmColorProducto");
	$poseeAccesorios = $request->getParam("poseeAccesorios");
	$responsableFuncionalidad = $request->getParam("responsableFuncionalidad");
	$responsableValidacion = $request->getParam("responsableValidacion");
	$existenciaMaxima = $request->getParam("existenciaMaxima");
	$existenciaMinima = $request->getParam("existenciaMinima");
	$puntoPedido = $request->getParam("puntoPedido");
	$caducidad = $request->getParam("caducidad");
	$reciclable = $request->getParam("reciclable");
	$activo = $request->getParam("activo");
	$idAdmTipoDesagregacionProducto = $request->getParam("idAdmTipoDesagregacionProducto");
	$peligroso = $request->getParam("peligroso");
	$idUsuarioCreacion = $request->getParam("idUsuarioCreacion");
	$idUsuarioValidacion = $request->getParam("idUsuarioValidacion");
	$idUsuarioModificacion = $request->getParam("idUsuarioModificacion");
	$fechaAprobacion = $request->getParam("fechaAprobacion");
	$fechaModificacion = $request->getParam("fechaModificacion");
	$aprobado = $request->getParam("aprobado");
	$esservicio = $request->getParam("esservicio");
	$validado = $request->getParam("validado");
	$idUsuarioValInfo = $request->getParam("idUsuarioValInfo");
	$fechaValInfo = $request->getParam("fechaValInfo");

	$idGerenciaCreacion = $request->getParam("idGerenciaCreacion");
	$idGerenciaModificacion = $request->getParam("idGerenciaModificacion");
	$idGerenciaAprobacion = $request->getParam("idGerenciaAprobacion");
	$idGerenciaValidacion = $request->getParam("idGerenciaValidacion");

	$consulta = "INSERT INTO adm_productos
                    (
                        codigo,
                        nombre,
                        uso,
                        idAdmGrupoProducto,
                        idAdmSubGrupoProducto,
                        idAdmUnidadMedida,
                        idAdmMaterialProducto,
                        idAdmColorProducto,
                        poseeAccesorios,
                        responsableFuncionalidad,
                        responsableValidacion,
                        existenciaMaxima,
                        existenciaMinima,
                        puntoPedido,
                        caducidad,
                        reciclable,
                        activo,
                        idAdmTipoDesagregacionProducto,
                        peligroso,
                        idUsuarioCreacion,
                        idUsuarioValidacion,
                        idUsuarioModificacion,
                        fechaAprobacion,
                        fechaModificacion,
                        aprobado,
                        esservicio,
                        validado,
                        idUsuarioValInfo,
                        fechaValInfo,
                        idGerenciaCreacion,
	                    idGerenciaModificacion,
	                    idGerenciaAprobacion,
	                    idGerenciaValidacion
                    )
                    VALUES
                    (
                        :codigo,
                        :nombre,
                        :uso,
                        :idAdmGrupoProducto,
                        :idAdmSubGrupoProducto,
                        :idAdmUnidadMedida,
                        :idAdmMaterialProducto,
                        :idAdmColorProducto,
                        :poseeAccesorios,
                        :responsableFuncionalidad,
                        :responsableValidacion,
                        :existenciaMaxima,
                        :existenciaMinima,
                        :puntoPedido,
                        :caducidad,
                        :reciclable,
                        :activo,
                        :idAdmTipoDesagregacionProducto,
                        :peligroso,
                        :idUsuarioCreacion,
                        :idUsuarioValidacion,
                        :idUsuarioModificacion,
                        :fechaAprobacion,
                        :fechaModificacion,
                        :aprobado,
                        :esservicio,
                        :validado,
                        :idUsuarioValInfo,
                        :fechaValInfo,
                        :idGerenciaCreacion,
	                    :idGerenciaModificacion,
	                    :idGerenciaAprobacion,
	                    :idGerenciaValidacion
                    )";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->bindParam(":codigo", $codigo);
		$sentencia->bindParam(":nombre", $nombre);
		$sentencia->bindParam(":uso", $uso);
		$sentencia->bindParam(":idAdmGrupoProducto", $idAdmGrupoProducto);
		$sentencia->bindParam(":idAdmSubGrupoProducto", $idAdmSubGrupoProducto);
		$sentencia->bindParam(":idAdmUnidadMedida", $idAdmUnidadMedida);
		$sentencia->bindParam(":idAdmMaterialProducto", $idAdmMaterialProducto);
		$sentencia->bindParam(":idAdmColorProducto", $idAdmColorProducto);
		$sentencia->bindParam(":poseeAccesorios", $poseeAccesorios);
		$sentencia->bindParam(":responsableFuncionalidad", $responsableFuncionalidad);
		$sentencia->bindParam(":responsableValidacion", $responsableValidacion);
		$sentencia->bindParam(":existenciaMaxima", $existenciaMaxima);
		$sentencia->bindParam(":existenciaMinima", $existenciaMinima);
		$sentencia->bindParam(":puntoPedido", $puntoPedido);
		$sentencia->bindParam(":caducidad", $caducidad);
		$sentencia->bindParam(":reciclable", $reciclable);
		$sentencia->bindParam(":activo", $activo);
		$sentencia->bindParam(":idAdmTipoDesagregacionProducto", $idAdmTipoDesagregacionProducto);
		$sentencia->bindParam(":peligroso",  $peligroso);
		$sentencia->bindParam(":idUsuarioCreacion", $idUsuarioCreacion);
		$sentencia->bindParam(":idUsuarioValidacion", $idUsuarioValidacion);
		$sentencia->bindParam(":idUsuarioModificacion", $idUsuarioModificacion);
		$sentencia->bindParam(":fechaAprobacion", $fechaAprobacion);
		$sentencia->bindParam(":fechaModificacion", $fechaModificacion);
		$sentencia->bindParam(":aprobado", $aprobado);
		$sentencia->bindParam(":esservicio", $esservicio);
		$sentencia->bindParam(":validado", $validado);
		$sentencia->bindParam(":idUsuarioValInfo", $idUsuarioValInfo);
		$sentencia->bindParam(":fechaValInfo", $fechaValInfo);
		$sentencia->bindParam(":idGerenciaCreacion", $idGerenciaCreacion);
		$sentencia->bindParam(":idGerenciaModificacion", $idGerenciaModificacion);
		$sentencia->bindParam(":idGerenciaAprobacion", $idGerenciaAprobacion);
		$sentencia->bindParam(":idGerenciaValidacion", $idGerenciaValidacion);



		$sentencia->execute();

		$id_insertado = $db->lastInsertId();
		$db = null;

		$producto = array('ObjectId' => $id_insertado);
		$response->withJson($producto);
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->put('/api/productos/{id}', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');


	$codigo = $request->getParam("codigo");
	$nombre = $request->getParam("nombre");
	$uso = $request->getParam("uso");
	$idAdmGrupoProducto = $request->getParam("idAdmGrupoProducto");
	$idAdmSubGrupoProducto = $request->getParam("idAdmSubGrupoProducto");
	$idAdmUnidadMedida = $request->getParam("idAdmUnidadMedida");
	$idAdmMaterialProducto = $request->getParam("idAdmMaterialProducto");
	$idAdmColorProducto = $request->getParam("idAdmColorProducto");
	$poseeAccesorios = $request->getParam("poseeAccesorios");
	$responsableFuncionalidad = $request->getParam("responsableFuncionalidad");
	$responsableValidacion = $request->getParam("responsableValidacion");
	$existenciaMaxima = $request->getParam("existenciaMaxima");
	$existenciaMinima = $request->getParam("existenciaMinima");
	$puntoPedido = $request->getParam("puntoPedido");
	$caducidad = $request->getParam("caducidad");
	$reciclable = $request->getParam("reciclable");
	$activo = $request->getParam("activo");
	$idAdmTipoDesagregacionProducto = $request->getParam("idAdmTipoDesagregacionProducto");
	$peligroso = $request->getParam("peligroso");
	$idUsuarioCreacion = $request->getParam("idUsuarioCreacion");
	$idUsuarioValidacion = $request->getParam("idUsuarioValidacion");
	$idUsuarioModificacion = $request->getParam("idUsuarioModificacion");
	$fechaAprobacion = $request->getParam("fechaAprobacion");
	$fechaModificacion = $request->getParam("fechaModificacion");
	$aprobado = $request->getParam("aprobado");
	$esservicio = $request->getParam("esservicio");
	$validado = $request->getParam("validado");
	$idUsuarioValInfo = $request->getParam("idUsuarioValInfo");
	$fechaValInfo = $request->getParam("fechaValInfo");

	$idGerenciaCreacion = $request->getParam("idGerenciaCreacion");
	$idGerenciaModificacion = $request->getParam("idGerenciaModificacion");
	$idGerenciaAprobacion = $request->getParam("idGerenciaAprobacion");
	$idGerenciaValidacion = $request->getParam("idGerenciaValidacion");


	$consulta = "UPDATE adm_productos 
                    SET
                        codigo = :codigo,
                        nombre = :nombre,
                        uso = :uso,
                        idAdmGrupoProducto = :idAdmGrupoProducto,
                        idAdmSubGrupoProducto = :idAdmSubGrupoProducto,
                        idAdmUnidadMedida = :idAdmUnidadMedida,
                        idAdmMaterialProducto = :idAdmMaterialProducto,
                        idAdmColorProducto = :idAdmColorProducto,
                        poseeAccesorios = :poseeAccesorios,
                        responsableFuncionalidad = :responsableFuncionalidad,
                        responsableValidacion = :responsableValidacion,
                        existenciaMaxima = :existenciaMaxima,
                        existenciaMinima = :existenciaMinima,
                        puntoPedido = :puntoPedido,
                        caducidad = :caducidad,
                        reciclable = :reciclable,
                        activo = :activo,
                        idAdmTipoDesagregacionProducto = :idAdmTipoDesagregacionProducto,
                        peligroso = :peligroso,
                        idUsuarioCreacion = :idUsuarioCreacion,
                        idUsuarioValidacion = :idUsuarioValidacion,
                        idUsuarioModificacion = :idUsuarioModificacion,
                        fechaAprobacion = :fechaAprobacion,
                        fechaModificacion = :fechaModificacion,
                        aprobado = :aprobado,
                        esservicio = :esservicio,
                        validado = :validado,
                        idUsuarioValInfo = :idUsuarioValInfo,
                        fechaValInfo = :fechaValInfo,
                        idGerenciaCreacion= :idGerenciaCreacion,
                        idGerenciaModificacion = :idGerenciaModificacion,
                        idGerenciaAprobacion = :idGerenciaAprobacion,
                        idGerenciaValidacion = :idGerenciaValidacion

                 WHERE 
                    idAdmProducto = :idAdmProducto"; //left join adm_producto

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);

		$sentencia->bindParam(":codigo", $codigo);
		$sentencia->bindParam(":nombre", $nombre);
		$sentencia->bindParam(":uso", $uso);
		$sentencia->bindParam(":idAdmGrupoProducto", $idAdmGrupoProducto);
		$sentencia->bindParam(":idAdmSubGrupoProducto", $idAdmSubGrupoProducto);
		$sentencia->bindParam(":idAdmUnidadMedida", $idAdmUnidadMedida);
		$sentencia->bindParam(":idAdmMaterialProducto", $idAdmMaterialProducto);
		$sentencia->bindParam(":idAdmColorProducto", $idAdmColorProducto);
		$sentencia->bindParam(":poseeAccesorios", $poseeAccesorios);
		$sentencia->bindParam(":responsableFuncionalidad", $responsableFuncionalidad);
		$sentencia->bindParam(":responsableValidacion", $responsableValidacion);
		$sentencia->bindParam(":existenciaMaxima", $existenciaMaxima);
		$sentencia->bindParam(":existenciaMinima", $existenciaMinima);
		$sentencia->bindParam(":puntoPedido", $puntoPedido);
		$sentencia->bindParam(":caducidad", $caducidad);
		$sentencia->bindParam(":reciclable", $reciclable);
		$sentencia->bindParam(":activo", $activo);
		$sentencia->bindParam(":idAdmTipoDesagregacionProducto", $idAdmTipoDesagregacionProducto);
		$sentencia->bindParam(":peligroso",  $peligroso);
		$sentencia->bindParam(":idUsuarioCreacion", $idUsuarioCreacion);
		$sentencia->bindParam(":idUsuarioValidacion", $idUsuarioValidacion);
		$sentencia->bindParam(":idUsuarioModificacion", $idUsuarioModificacion);
		$sentencia->bindParam(":fechaAprobacion", $fechaAprobacion);
		$sentencia->bindParam(":fechaModificacion", $fechaModificacion);
		$sentencia->bindParam(":aprobado", $aprobado);
		$sentencia->bindParam(":esservicio", $esservicio);
		$sentencia->bindParam(":validado", $validado);
		$sentencia->bindParam(":idUsuarioValInfo", $idUsuarioValInfo);
		$sentencia->bindParam(":fechaValInfo", $fechaValInfo);

		$sentencia->bindParam(":idGerenciaCreacion", $idGerenciaCreacion);
		$sentencia->bindParam(":idGerenciaModificacion", $idGerenciaModificacion);
		$sentencia->bindParam(":idGerenciaAprobacion", $idGerenciaAprobacion);
		$sentencia->bindParam(":idGerenciaValidacion", $idGerenciaValidacion);

		$sentencia->bindParam(":idAdmProducto", $idAdmProducto);

		//echo $sentencia;

		$sentencia->execute();

		echo '{"message": {"text": "Producto actualizado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->put('/api/productos/soloalmacen/{id}', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$existenciaMaxima = $request->getParam("existenciaMaxima");
	$existenciaMinima = $request->getParam("existenciaMinima");
	$puntoPedido = $request->getParam("puntoPedido");
	$caducidad = $request->getParam("caducidad");
	$reciclable = $request->getParam("reciclable");
	$peligroso = $request->getParam("peligroso");
	

	$idUsuarioModAlmacen = $request->getParam("idUsuarioModAlmacen");
	$ultimaModAlmacen = $request->getParam("ultimaModAlmacen");


	$consulta = "UPDATE adm_productos 
                SET 
                        existenciaMaxima = :existenciaMaxima,
                        existenciaMinima = :existenciaMinima,
                        puntoPedido = :puntoPedido,
                        caducidad = :caducidad,
                        reciclable = :reciclable,
                        peligroso = :peligroso,
						ultimaModAlmacen = :ultimaModAlmacen,
						idUsuarioModAlmacen = :idUsuarioModAlmacen,

						aprobadoAlmacen = 0

				 WHERE   idAdmProducto = :idAdmProducto";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->bindParam(":existenciaMaxima", $existenciaMaxima);
		$sentencia->bindParam(":existenciaMinima", $existenciaMinima);
		$sentencia->bindParam(":puntoPedido", $puntoPedido);
		$sentencia->bindParam(":caducidad", $caducidad);
		$sentencia->bindParam(":reciclable", $reciclable);
		$sentencia->bindParam(":peligroso",  $peligroso);

		$sentencia->bindParam(":ultimaModAlmacen",  $ultimaModAlmacen);
		$sentencia->bindParam(":idUsuarioModAlmacen",  $idUsuarioModAlmacen);

		$sentencia->bindParam(":idAdmProducto", $idAdmProducto);

		$sentencia->execute();

		echo '{"message": {"text": "Producto actualizado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->put('/api/productos/aprobarAlmacen/{id}', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$aprobadoAlmacen = $request->getParam("aprobadoAlmacen");
	$idUsuarioAprobAlmacen = $request->getParam("idUsuarioAprobAlmacen");
	$fechaAproboAlmacen = $request->getParam("fechaAproboAlmacen");


	$consulta = "UPDATE adm_productos 
                SET 
						aprobadoAlmacen = :aprobadoAlmacen,
                        idUsuarioAprobAlmacen = :idUsuarioAprobAlmacen,
                        fechaAproboAlmacen = :fechaAproboAlmacen

				 WHERE   idAdmProducto = :idAdmProducto";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->bindParam(":aprobadoAlmacen", $aprobadoAlmacen);
		$sentencia->bindParam(":idUsuarioAprobAlmacen", $idUsuarioAprobAlmacen);
		$sentencia->bindParam(":fechaAproboAlmacen", $fechaAproboAlmacen);

		$sentencia->bindParam(":idAdmProducto", $idAdmProducto);

		$sentencia->execute();

		echo '{"message": {"text": "Producto actualizado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->delete('/api/productos/{id}', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$consulta = "DELETE FROM adm_productos WHERE idAdmProducto = $idAdmProducto";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->execute();

		$db = null;

		echo '{"message": {"text": "Producto eliminado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->delete('/api/productos/{id}/complementarias', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$consulta = "DELETE FROM 
                    adm_complementarias_producto 
                 WHERE idAdmProducto = $idAdmProducto AND aplicaComplementaria = 1";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->execute();

		$db = null;

		echo '{"message": {"text": "Producto eliminado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->delete('/api/productos/{id}/adicionales', function (Request $request, Response $response) {

	$idAdmProducto = $request->getAttribute('id');

	$consulta = "DELETE FROM 
                    adm_complementarias_producto 
                 WHERE idAdmProducto = $idAdmProducto AND aplicaComplementaria = 0";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->execute();

		$db = null;

		echo '{"message": {"text": "Producto eliminado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});

$app->delete('/api/productos/{id}/aplicabilidad', function (Request $request, Response $response) {

	$idAdmProductoPadre = $request->getAttribute('id');

	$consulta = "DELETE FROM 
                    adm_aplicabilidad_producto 
                 WHERE idAdmProductoPadre = $idAdmProductoPadre";

	try {

		$db = new db();
		$db = $db->conectar();

		$sentencia = $db->prepare($consulta);
		$sentencia->execute();

		$db = null;

		echo '{"message": {"text": "Producto eliminado correctamente"}}';
	} catch (PDOException $error) {
		echo '{"error": {"text":' . $error->getMessage() . '}}';
	}
});
