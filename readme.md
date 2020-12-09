# Backend Sisglobal #
---
#### Rest API Usando Slim PHP

#### AdmnistraciónCatalogo :file_folder:

<details>
<summary><b>adm_activos</b></summary>

- /api/activos GET
- /api/activos/gerencia/{idgerencia} GET
- /api/activos/{id} GET
- /api/activos POST
- /api/activos/{id} PUT
- /api/activos/{id} DELETE

</details>

<details>
<summary><b>adm_aplicabilidad_producto</b></summary>

- /api/productos/{id}/aplicabilidad GET
- /api/aplicabilidad POST
- /api/aplicabilidad/{id} PUT
- /api/aplicabilidad/{id} DELETE

</details>


<details><summary><b>adm_areas_trabajo</b></summary>

- /api/areas_trabajo GET
- /api/areas_trabajo POST
- /api/areas_trabajo PUT
- /api/areasporproducto/{idConfigGerencia}/{codigo} GET
- /api/areas_trabajo/{idAreaTrabajo} PUT
- /api/areas_trabajo/{idAreaTrabajo} DELETE

</details>

<details><summary><b>adm_color_producto</b></summary>

- /api/colores GET
- /api/colores/{id} GET
- /api/colores POST
- /api/colores/{id} PUT
- /api/colores/{id} DELETE

</details>

<details>
<summary><b>adm_complementarias_producto</b></summary>

- /api/complementarias GET
-  /api/complementarias/{id} GET
- /api/complementarias POST
- /api/complementarias/{id} PUT
- /api/complementarias/{id} DELETE
</details>

<details><summary><b>adm_grupos</b></summary>

- /api/grupos GET
- /api/grupos/{id} GET
- /api/grupos/{id}/subgrupos GET
- /api/grupos POST
- /api/grupos/{id} PUT
- /api/grupos/{id} DELETE

</details>

<details><summary><b>adm_materiales_producto</b></summary>

- /api/materiales GET
- /api/materiales/{id} GET
- /api/materiales POST
- /api/materiales/{id} PUT
- /api/materiales/{id} DELETE

</details>

<details><summary><b>adm_modulos</b></summary>

- /api/modulos GET
- /api/modulos/{id} GET
- /api/modulos POST
- /api/modulos/{id} PUT
- /api/modulos/{id} DELETE

</details>

<details>
<summary><b>adm_producto_imagenes</b></summary>

- /api/productos/{id}/imagenes GET
- /api/productos/imagenes POST
-  /api/productos/{id}/imagenes DELETE
- /api/subirimagenesproducto/{archAnterior} POST
- /api/productos/imagenes/{id} DELETE


</details>
<details><summary><b>adm_productos</b></summary>

- /api/productos GET
- /api/productos/busqueda POST
- /api/productos/busqueda/json POST
- /api/productos/{id} GET
- /api/productossolped/{idConfigGerencia}/{idActivo}/{campo}/{valor} GET
- /api/productos/{id}/complementarias POST
- /api/productos POST
- /api/productos/{id} PUT
- /api/productos/{id} DELETE
- /api/productos/{id}/complementarias DELETE
- /api/productos/{id}/aplicabilidad DELETE

</details>

<details><summary><b>adm_propiedad_subTipo</b></summary>

- /api/propiedadsubtipo POST
- /api/propiedadsubtipo/{idAdmSubTipoClasificacion}/{idAdmPropiedad} DELETE
</details>

<details><summary><b>adm_propiedades</b></summary>

- /api/propiedades GET
- /api/propiedades/{id} GET
- /api/propiedades POST
- /api/propiedades/{id} PUT
- /api/propiedades/{id} DELETE

</details>

<details>
<summary><b>adm_relacion_areas_gerencia</b></summary>

- /api/relacion_areas_gerencia GET
- /api/relacion_areas_gerencia/{idAreaTrabajo} GET
- /api/relacion_areas_gerencia POST
- /api/relacion_areas_gerencia/{idAreaRelacionGerencia} PUT
- /api/relacion_areas_gerencia/{idAreaTrabajo} DELETE

</details>

<details>
<summary><b>adm_resp_funcionales</b></summary>

- /api/respofuncionales GET
- /api/respofunporprod/{idAdmProducto} GET
- /api/respofuncionales POST
- /api/respofuncionales/{id} PUT
- /api/respofuncionales/{id} DELETE
</details>



<details><summary><b>adm_respo_validacion</b></summary>

- /api/respovalidacion GET
- /api/respovalporprod/{idAdmProducto} GET
- /api/respovalidacion POST
- /api/respovalidacion/{id} PUT
- /api/respovalidacion/{id} DELETE
- /api/respovalporprod/{idAdmProducto} DELETE

</details>
<details><summary><b>adm_sub_grupos</b></summary>

- /api/subgrupos GET
- /api/subgrupos/{id} GET
- /api/subgrupos POST
- /api/subgrupos/{id} PUT
- /api/subgrupos/{id} DELETE

</details>

<details><summary><b>adm_sub_tipos</b></summary>

- /api/subtipos GET
- /api/subtipos/{id} GET
- /api/subtipos/{id}/propiedadesAsignadas GET
- /api/subtipos/{id}/propiedadesNoAsignadas GET
- /api/subtipos/{id}/propiedades GET
- /api/subtipos POST
- /api/subtipos/{id} PUT
- /api/subtipos/{id} DELETE

</details>

<details>
<summary><b>adm_tipo_medidas</b></summary>

- /api/tiposmedida GET
- /api/tiposmedida/{id} GET
- /api/tiposmedida/{id}/unidadmedidas GET
- /api/tiposmedida POST
- /api/tiposmedida/{id} PUT
- /api/tiposmedida/{id} DELETE

</details>

<details>
<summary><b>adm_tipos_desagregacion</b></summary>

- /api/tiposdesagregacion GET
- /api/tiposdesagregacion/{id} GET
- /api/tiposdesagregacion POST
- /api/tiposdesagregacion/{id} PUT
- /api/tiposdesagregacion/{id} DELETE

</details>


<details>
<summary><b>adm_tipos</b></summary>

- /api/tipos GET
- /api/tipos/{id} GET
- /api/tipos/{id}/subtipos GET
- /api/tipos POST
- /api/tipos/{id} PUT
- /api/tipos/{id} DELETE

</details>

<details>
<summary><b>adm_unidad_medidas</b></summary>

- /api/unidadmedidas GET
- /api/unidadmedidas/{id} GET
- /api/unidadmedidas POST
- /api/unidadmedidas/{id} PUT
- delete('/api/unidadmedidas/{id} DELETE

</details>

<details>
<summary><b>config_gerencias</b></summary>

- /api/config_gerencias GET
- /api/config_gerencias POST
- /api/config_gerencias/{idConfigGerencia} PUT
- /api/config_gerencias/{idConfigGerencia} DELETE

</details>



#### Compras  :file_folder:  
<details>
<summary><b>compras_empresa</b></summary>

- /api/empresacompras GET
- /api/empresacomprastodas GET
- /api/empresacompras/{id} GET
- /api/empresacompras/{id} GET
- /api/empresacompras POST
- /api/empresacompras/{id} PUT
- /api/empresacompras/{id} DELETE

</details>

<details>
<summary><b>compras_sol_ped_detale</b></summary>

- /api/solpeddetalle GET
- /api/solpeddetalle/{id} GET
- /api/solpeddetallets/{idTicket} GET
- /api/solpeddetalle POST
- /api/solpeddetalle/{id} PUT
- /api/solpeddetalle/{id} DELETE

</details>
<details>
<summary><b>compras_sol_ped_estados</b></summary>

- /api/estadosolped GET
- /api/estadosolped/{id} GET
- /api/estadosolped POST
- /api/estadosolped/{id} PUT
- /api/estadosolped/{id} DELETE

</details>
<details>
<summary><b>compras_sol_ped_trazas</b></summary>

- /api/solpedtraza GET
- /api/solpedtraza/{id} GET
- /api/solpedtrazaporsol/{id} GET
- /api/solpedtraza POST
- /api/solpedtraza/{id} PUT
- /api/solpedtraza/{id} DELETE 

</details>
<details>
<summary><b>compras_sol_ped</b></summary>

- /api/solped GET
- /api/solped/{id} GET
- /api/solpedticket/{id} GET
- /api/solped POST
- /api/solped/{id} PUT
- /api/solped/{id} DELETE 

</details>

#### Configuraciones :file_folder: 
<details>
<summary><b>config_areas_trabajo</b></summary>

- /api/areastrabajo GET
- /api/areastrabajo/{id} GET
- /api/areastrabajo POST
- /api/areastrabajo/{id} PUT
- /api/areastrabajo/{id} DELETE 

</details>

<details>

<summary><b>config_cargos</b></summary>

- /api/cargos GET
- /api/cargos/{id} GET
- /api/cargos POST
- /api/cargos/asociar POST
- /api/cargos/{id} PUT
- /api/cargos/{id} DELETE 

</details>
<details>

<summary><b>config_ciudades</b></summary>

- /api/ciudades GET
- /api/ciudades/{id} GET
- /api/estados/{id}/ciudades GET
- /api/estados/{idEstado}/ciudades/{idCiudad} GET
- /api/ciudades POST
- /api/ciudades/{id} PUT
- /api/ciudades/{id} DELETE 

</details>

<details>
<summary><b>config_empre_geren_area</b></summary>

- /api/empresagerenciaarea GET
- /api/consultaSiIngresado/{empre}/{geren}/{area} GET
- /api/empresagerenciaarea POST
- /api/empresagerenciaarea/{id} DELETE 

</details>

<details>
<summary><b>config_estados</b></summary>

- /api/estados GET
- /api/estados/{id} GET
- /api/estados POST
- /api/estados/{id} PUT 
- /api/estados/{id} DELETE 

</details>

<details>
<summary><b>config_gerencias_temporales</b></summary>

- /api/gerenciastemp GET
- /api/gerenciastempusuario/{idUsuario} GET
- /api/gerenciastempnousuario/{idUsuario}/{idcargo} GET
- /api/gerenciastemp POST
- /api/gerenciastemp/{id} PUT 
- /api/gerenciastemp/{idSegUsuario}/{idConfigGerencia} DELETE 

</details>

<details>
<summary><b>config_gerencias</b></summary>

- /api/gerencias GET
- /api/gerenciassinactual/{id} GET
- /api/gerencias/{idGerencia}/cargos GET
- /api/gerencias/{id}/areasTrabajo GET
- /api/gerencias POST
- /api/gerencias/{id} PUT 
- /api/gerencias/{id} DELETE 

</details>

<details>
<summary><b>config_municipios</b></summary>

<ul>
<li> /api/municipios GET</li>
<li> /api/municipios/{id} GET</li>
<li> /api/estados/{id}/municipios GET</li>
<li> /api/estados/{idEstado}/municipios/{idMunicipio} GET</li>
<li>/api/municipios POST</li>
<li> /api/municipios/{id} PUT</li> 
<li>/api/municipios/{id} DELETE</li> 

</ul>
</details>

<details>
<summary><b>config_noticias</b></summary>

<ul>
<li> /api/noticiasPublico GET</li>
<li>/api/noticias GET</li>
<li> /api/noticia/{id} GET</li>
<li>/api/subirimagen/{archAnterior} POST</li>
<li>/api/quitarimagen/{archAnterior} POST</li>
<li> /api/noticia/{id} PUT</li> 
<li>/api/noticia/{id} DELETE</li> 

</ul>
</details>

<details>
<summary><b>config_parametros_sistema</b></summary>
<ul>
<li> /api/parametros GET</li>
<li> /api/parametros PATCH </li>
<li>/api/parametros/{id} DELETE</li> 
</ul>
</details>

<details>
<summary><b>config_parroquias</b></summary>
<ul>
<li>/api/parroquias GET</li>
<li>/api/parroquias/{idParroquia} GET </li>
<li>/api/municipios/{idMunicipio}/parroquias GET </li>
<li>/api/municipios/{idMunicipio}/parroquias/{idParroquia} GET </li>
<li>/api/parroquias POST</li>
<li>/api/parroquias/{idParroquia} PUT</li>
<li>/api/parroquias/{idParroquia} POST</li> 
</ul>
</details>

<details>
<summary><b>config_servicios_gerencias</b></summary>
<ul>
<li>/api/serviciosgerencias GET</li>
<li>/api/serviciosgerencias/{id} GET </li>
<li>/api/serviciosporgerencias/{idGerencia} GET </li>
<li>/api/serviciosgerencias POST</li>
<li>/api/serviciosgerencias/{id} PUT</li>
<li>/api/serviciosgerencias/{id} DELETE</li> 
</ul>
</details>

<details>
<summary><b>config_zonas_postales</b></summary>
<ul>
<li>/api/zonas_postales GET</li>
<li>/api/zonas_postales/{idZonaPostal} GET </li>
<li>/api/estados/{idEstado}/zonas_postales GET </li>
<li>/api/estados/{idEstado}/zonas_postales/{idZonaPostal} GET </li>
<li>/api/zonas_postales POST</li>
<li>/api/zonas_postales/{idZonaPostal} PUT</li>
<li>/api/zonas_postales/{idZonaPostal} DELETE</li> 
</ul>
</details>

#### Generales :file_folder: 
<details>
<summary><b>gen_area_negocio</b></summary>
<ul>
<li>/api/areanegocio GET</li>
<li>/api/areanegocio/{id} GET </li>
<li>/api/areanegociogerencia/{id} GET </li>
<li>/api/areanegocio POST </li>
<li>/api/areanegocio/{id} PUT</li>
<li>/api/areanegocio/{id} DELETE</li> 
</ul>
</details>

<details>
<summary><b>gen_centro_costo</b></summary>
<ul>
<li>/api/centrocostos GET</li>
<li>/api/centrocostos/{id} GET </li>
<li>/api/centrocostosempregerencia/{idEmpre}/{idGerencia} GET </li>
<li>/api/centrocostos POST </li>
<li>/api/empreccgerencia POST </li>
<li>/api/centrocostos/{id} PUT</li>
<li>/api/centrocostos/{id} DELETE</li> 
</ul>
</details>

<details>
<summary><b>gen_empresa</b></summary>
<ul>
<li>/api/empresa GET</li>
<li>/api/empresa/{id} GET </li>
<li>/api/empresa POST </li>
<li>/api/empresa/{id} PUT</li>
<li>//api/empresa/{id}DELETE</li> 
</ul>
</details>

<details>
<summary><b>gen_preguntas</b></summary>
<ul>
<li>/api/preguntas/{idGerencia} GET</li>
</ul>
</details>

<details>
<summary><b>gen_respuestas</b></summary>
<ul>
<li>/api/respuestas/{id} GET</li>
<li>/api/respuestasserv/{idRefServicio} GET </li>
<li>/api/respuestas POST </li>
</ul>
</ul>
</details>

#### NsNotificacionesServicios :file_folder: 
<details>
<summary><b>NsNotificacionesServicios</b></summary>
<ul>
<li>/api/notificaciones/usuarios/{id}/todas GET</li>
<li>/api/notificaciones/usuarios/{id}/ultimas GET </li>
<li>/api/notificaciones/usuarios/{id} GET </li>
<li>/api/notificaciones POST </li>
<li>/api/notificaciones/recibe POST</li>
<li>/api/notificaciones/{id} PATCH</li>
<li>/api/notificaciones/enviar POST</li>
</ul>
</details>

#### seguridad :file_folder: 
<details>
<summary><b>seg_correos</b></summary>
<ul>
<li>/api/usuarios/correos POST</li>
<li>/api/usuarios/correos/{id} PUT </li>
<li>/api/usuarios/correos/todos DELETE </li>
<li>/api/usuarios/correos/{id} DELETE </li>
</ul>
</details>

<details>
<summary><b>seg_correos</b></summary>
<ul>
<li>/api/usuarios/direcciones POST</li>
<li>/api/usuarios/direcciones/{id} PUT </li>
<li>/api/usuarios/direcciones/todos DELETE </li>
<li>/api/usuarios/direcciones/{id} DELETE </li>
</ul>
</details>

<details>
<summary><b>seg_log_transac</b></summary>
<ul>
<li>/api/log POST</li>
<li>/api/log GET </li>
<li>/api/getlog/{modulo}/{accion}/{rol}/{desde}/{hasta} GET </li>
<li>/api/log/infocliente DELETE </li>
</ul>
</details>

<details>
<summary><b>seg_menus_aux</b></summary>
<ul>
<li>/api/menus GET</li>
<li>/api/menus/items GET </li>
<li>/api/menus/icons GET </li>
<li>/api/menus/{id} GET </li>
 <li>/api/menusitems GET </li>
 <li>/api/menus/obtenerBreadCrumb/{id} GET </li>
 <li>/api/menus/obtenerMenuUsuario/{id} GET </li>
 <li>/api/menus POST </li>
 <li>/api/menus/{id} PUT </li>
 <li>/api/menus/{id} DELETE </li>
<ul>
</details>

<details>
<summary><b>seg_menus</b></summary>
<ul>
<li>/api/menus/aux GET</li>
<li>/api/menus GET </li>
<li>/api/menus/items GET </li>
<li>/api/menus/icons GET </li>
 <li>/api/menus/{id} GET </li>
 <li>/api/menusitems{id} GET </li>
 <li>/api/menus/obtenerBreadCrumb/{id} GET </li>
 <li>/api/menus/obtenerMenuUsuario/{id} GET </li>
 <li>/api/menus/{id} POST </li>
  <li>/api/menus/{id} PUT </li>
 <li>/api/menus/{id} DELETE </li>
<ul>
</details>

<details>
<summary><b>seg_perfil_modulo</b></summary>
<ul>
<li>/api/perfilmodulo POST</li>
<li>/api/perfilmdulo DELETE </li>
<li>/api/perfilmodulos/{idSegPerfil} GET </li>
<ul>
</details>

<details>
<summary><b>seg_perfil_rol</b></summary>
<ul>
<li>/api/perfilrol POST</li>
<li>/api/perfilrol/{idSegPerfil}/{idSegRol} DELETE </li>
<li>/api/perfilroles/{idSegPerfil} GET </li>
<li>/api/noperfilroles/{idSegPerfil} GET </li>
<ul>
</details>

<details>
<summary><b>seg_perfil_usuario</b></summary>
<ul>
<li>/api/perfilusuario POST</li>
<li>/api/perfilusuario/{idSegPerfil}/{idSegUsuario} DELETE </li>
<li>/api/perfilesusuarios/{idSegUsuario} GET </li>
<li>/api/noperfilesusuario/{idSegPerfil} GET </li>
<li>/api/porperfil/{idSegPerfil} GET </li>
<ul>
</details>

<details>
<summary><b>seg_perfiles</b></summary>
<ul>
<li>/api/perfiles GET</li>
<li>/api/perfiles/{id} GET </li>
<li>/api/perfiles POST </li>
<li>/api/perfiles/{id} PUT </li>
<li>/api/perfiles/{id} DELETE </li>
<ul>
</details>

<details>
<summary><b>seg_roles</b></summary>
<ul>
<li>/api/roles GET</li>
<li>/api/tipoacciones GET </li>
<li>/api/rol/{id} GET </li>
<li>/api/rol POST </li>
<li>/api/rolesprocess POST </li>
<li>api/rol/{id} PUT </li>
<li>/api/rol/{id} DELETE </li>
<ul>
</details>

<details>
<summary><b>seg_telefonos</b></summary>
<ul>
<li>/api/usuarios/telefonos/{id} GET</li>
<li>/api/usuarios/telefonos POST </li>
<li>/api/usuarios/telefonos/{id} PUT </li>
<li>/api/usuarios/telefonos/todos DELETE </li>
<li>/api/usuarios/telefonos/{id} DELETE </li>
<ul>
</details>

<details>
<summary><b>seg_usuario_rol</b></summary>
<ul>
<li>/api/usuariorol POST</li>
<li>/api/usuariorol/{idSegUsuario}/{idSegRol} DELETE </li>
<li>/api/usuarioroles/{idSegUsuario} GET </li>
<li>/api/usuarios-por-roles/{codigoRol} GET </li>
<li>/api/nousuarioroles/{idSegUsuario} GET </li>
<ul>
</details>


<details>
<summary><b>seg_usuario</b></summary>
<ul>
<li>/api/usuarios GET</li>
<li>/api/usuariosgerencia/{idGerencia} GET </li>
<li>/api/ip GET </li>
<li>/api/usuarios/{id} GET </li>
<li>/api/usuarios/{id}/direcciones GET </li>
<li>/api/usuarios/{id}/telefonos GET </li>
<li>/api/usuarios/{id}/correos GET </li>
<li>/api/usuarios POST </li>
<li>/api/login POST </li>
<li>/api/usuarios/{id} PUT </li>
<li>/api/usuarios/{id} DELETE </li>
<li>/api/subirimagenusr/{archAnterior} POST </li>
<li>/api/subirimgpropia/{archAnterior} POST </li>
<li>/api/quitarimagenusr/{archAnterior} POST </li>
<li>api/usuariosverificagerencia/{idConfigGerencia} GET </li>
<ul>
</details>