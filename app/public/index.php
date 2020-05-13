<?php
error_reporting(-1);
ini_set('display_errors', 1);
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';
require '../src/config/dbGlobal.php';
require '../src/config/util.php';
require '../src/config/resize.php';

// Instantiate the app
$settings = require '../src/config/settings.php';

$app = new \Slim\App($settings);

// Register middleware
require '../src/middleware.php';

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

require '../src/routes/seguridad/seg_usuarios.php';
require '../src/routes/seguridad/seg_menus.php';
require '../src/routes/seguridad/seg_roles.php';
require '../src/routes/seguridad/seg_perfiles.php';
require '../src/routes/seguridad/seg_telefonos.php';
require '../src/routes/seguridad/seg_correos.php';
require '../src/routes/seguridad/seg_direcciones.php';
require '../src/routes/seguridad/seg_log_transac.php';
require '../src/routes/seguridad/seg_perfil_rol.php';
require '../src/routes/seguridad/seg_usuario_rol.php';
require '../src/routes/seguridad/seg_perfiles_usuario.php';


//require '../src/routes/seguridad/seg_perfil_modulo.php';

require '../src/routes/configuraciones/config_ciudades.php';
require '../src/routes/configuraciones/config_estados.php';
require '../src/routes/configuraciones/config_municipios.php';
require '../src/routes/configuraciones/config_parroquias.php';
require '../src/routes/configuraciones/config_zonas_postales.php';
require '../src/routes/configuraciones/config_gerencias.php';
require '../src/routes/configuraciones/config_cargos.php';
require '../src/routes/configuraciones/config_noticias.php';
require '../src/routes/configuraciones/config_servicios_gerencias.php';
require '../src/routes/configuraciones/config_parametros_sistema.php';
require '../src/routes/configuraciones/config_areas_trabajo.php';
require '../src/routes/configuraciones/config_gerencias_temporales.php';
require '../src/routes/configuraciones/config_empre_geren_area.php';



//////////////////////////////////////////////////////////

// Recursos de Solicitudes
//
require '../src/routes/ticketservicio/ts_estados_ticket.php';
require '../src/routes/ticketservicio/ts_ticket_servicio.php';
require '../src/routes/ticketservicio/ts_traza_ticket_servicio.php';
//////////////////////////////////////////////////////////

// Recursos de Notificaciones
//

require '../src/routes/nsNotificacionServicio/nsNotificacionServicio.php';

require '../src/routes/sparrow_config.php';
require '../src/routes/conta_reports.php';
require '../src/routes/nomi_reports.php';
require '../src/routes/adm_ventas_facturacion.php';

// Catalogo de Productos
//

require '../src/routes/administracionCatalogo/adm_color_producto.php';
require '../src/routes/administracionCatalogo/adm_materiales_producto.php';
require '../src/routes/administracionCatalogo/adm_grupos.php';
require '../src/routes/administracionCatalogo/adm_sub_grupos.php';
require '../src/routes/administracionCatalogo/adm_tipos.php';
require '../src/routes/administracionCatalogo/adm_sub_tipos.php';
require '../src/routes/administracionCatalogo/adm_modulos.php';
require '../src/routes/administracionCatalogo/adm_tipo_medidas.php';
require '../src/routes/administracionCatalogo/adm_unidad_medidas.php';
require '../src/routes/administracionCatalogo/adm_propiedades.php';
require '../src/routes/administracionCatalogo/adm_tipos_desagregacion.php';
require '../src/routes/administracionCatalogo/adm_productos.php';
require '../src/routes/administracionCatalogo/adm_complementarias_producto.php';
require '../src/routes/administracionCatalogo/adm_activos.php';
require '../src/routes/administracionCatalogo/adm_aplicabilidad_producto.php';
require '../src/routes/administracionCatalogo/adm_propiedad_subTipo.php';
require '../src/routes/administracionCatalogo/adm_producto_imagenes.php';
require '../src/routes/administracionCatalogo/adm_respo_funcionales.php';
require '../src/routes/administracionCatalogo/adm_respo_validacion.php';
require '../src/routes/administracionCatalogo/adm_relacion_areas_gerencia.php';
require '../src/routes/administracionCatalogo/adm_areas_trabajo.php';
require '../src/routes/administracionCatalogo/config_gerencias.php';

//////////////////////////////////////////////////////////
//Modulo de Solped para ticket

require '../src/routes/compras/compras_sol_ped.php';
require '../src/routes/compras/compras_sol_ped_detalle.php';
require '../src/routes/compras/compras_sol_ped_trazas.php';
require '../src/routes/generales/gen_centro_costos.php';
require '../src/routes/generales/gen_empresa.php';
require '../src/routes/compras/compras_empresa.php';
require '../src/routes/generales/gen_area_negocio.php';
require '../src/routes/compras/compras_sol_ped_estados.php';


//////////////////////////////////////////////////////////

//Rutas tipo unidadmedidas

require '../src/routes/tipounidadmedidas/tipomedidas.php';
require '../src/routes/tipounidadmedidas/unidaddemedidas.php';


$app->run();

