<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/','HomeController@index');

Auth::routes();
///////////////// RUTAS WEB
    Route::get('/comandos/aviso_actividad', 'ActividadController@aviso_actividad')->name('aviso_actividad');
///////////////// RUTAS PRINCIPALES
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/welcome', 'HomeController@index')->name('welcome');

    /*configuracion general*/
    Route::get('/configuracion_general', 'ConfiguracionGeneralController@index')->name('configuracion_general');
    Route::get('/configuracion_general/show/{id}', 'ConfiguracionGeneralController@show')->name('configuracion_general_show');
    Route::post('/configuracion_general/update/{id}', 'ConfiguracionGeneralController@update')->name('configuracion_general_update');
    Route::post('/configuracion_general/store', 'ConfiguracionGeneralController@store')->name('configuracion_general_store');

    /*configuracion campos*/
    Route::get('/campos_configuracion', 'ConfiguracionCamposController@index')->name('campos_configuracion');
    Route::get('/campos_configuracion/show/{id}', 'ConfiguracionCamposController@show')->name('campos_configuracion_show');
    Route::post('/campos_configuracion/update/{id}', 'ConfiguracionCamposController@update')->name('campos_configuracion_update');
    Route::post('/campos_configuracion/store', 'ConfiguracionCamposController@store')->name('configuracion_general_store');
    Route::post('/campos_configuracion/destroy/{id}', 'ConfiguracionCamposController@destroy')->name('campos_configuracion_destroy');

    /*Propiedad*/
    Route::get('/propiedades', 'PropiedadController@index')->name('propiedades');
    Route::post('/propiedades/store', 'PropiedadController@store')->name('propiedades-store');
    Route::get('/propiedades/show/{id}/{procedencia}/{padre}', 'PropiedadController@show')->name('propiedades-show');
    Route::post('/propiedades/update/{id}', 'PropiedadController@update')->name('propiedades-update');
    Route::post('/propiedades/update/{id}', 'PropiedadController@update')->name('propiedades-update');
    Route::get('/propiedades/destroy/{id}', 'PropiedadController@destroy')->name('propiedades-destroy');
    Route::get('/propiedades/updateinfo', 'PropiedadController@updateinfo')->name('propiedades-updateinfo');

    /*Proyectos*/
    Route::get('/proyectos', 'ProyectosController@index')->name('proyectos');
    Route::post('/proyectos/store', 'ProyectosController@store')->name('proyectos-store');
    Route::get('/proyectos/show/{id}', 'ProyectosController@show')->name('proyectos-show');
    Route::post('/proyectos/update/{id}', 'ProyectosController@update')->name('proyectos-update');
    Route::get('/proyectos/destroy/{id}', 'ProyectosController@destroy')->name('proyectos-destroy');

    /*Imagenes propiedad*/
    Route::post('/imagenes_propiedad/store', 'ImagenesPropiedadController@store')->name('imagenes_propiedad-store');
    Route::get('/imagenes_propiedad/destroy/{id}', 'ImagenesPropiedadController@destroy')->name('imagenes_propiedad-destroy');
    Route::post('/imagenes_propiedad/update/{id}', 'ImagenesPropiedadController@update')->name('imagenes_propiedad-update');
    Route::get('/imagenes_propiedad/show/{id}', 'ImagenesPropiedadController@show')->name('imagenes_propiedad-show');

    /*amenidades propiedad*/
    Route::post('/amenidades_propiedad/store', 'AmenidadPropiedadController@store')->name('amenidades_propiedad-store');
    Route::get('/amenidades_propiedad/show/{id}', 'AmenidadPropiedadController@show')->name('amenidades_propiedad-show');
    Route::get('/amenidades_propiedad/destroy/{id}', 'AmenidadPropiedadController@destroy')->name('amenidades_propiedad-destroy');
    Route::post('/amenidades_propiedad/update/{id}', 'AmenidadPropiedadController@update')->name('amenidades_propiedad-update');

    /*Comision*/
    Route::get('/comision', 'ComisionController@index')->name('comision');
    Route::post('/comision/store', 'ComisionController@store')->name('comision-store');
    Route::get('/comision/show/{id}', 'ComisionController@show')->name('comision-show');
    Route::post('/comision/update/{id}', 'ComisionController@update')->name('comision-update');
    Route::get('/comision/aprobar/{id}', 'ComisionController@aprobar')->name('comision-aprobar');
    Route::get('/comision/recalcular/{id}', 'ComisionController@recalcular')->name('comision-recalcular');
    Route::get('/comision/destroy/{id}', 'ComisionController@destroy')->name('comision-destroy');

    /*Detalle de comision*/

    Route::post('/comision_detalle/store/{id}', 'ComisionDetalleController@store')->name('comision_detalle-store');
    Route::get('/comision_detalle/show/{id}', 'ComisionDetalleController@show')->name('comision_detalle-show');
    Route::post('/comision_detalle/update/{id}', 'ComisionDetalleController@update')->name('comision_detalle-update');
    Route::get('/comision_detalle/destroy/{id}', 'ComisionDetalleController@destroy')->name('comision_detalle-destroy');
    Route::post('/comision_detalle/pagar/{id}', 'ComisionDetalleController@pagar')->name('comision_detalle-pagar');

    /*Pago de comision*/

    Route::post('/pago_comision/store/{id}', 'PagoComisionController@store')->name('pago_comision-store');
    Route::get('/pago_comision/show/{id}', 'PagoComisionController@show')->name('pago_comision-show');
    Route::post('/pago_comision/update/{id}', 'PagoComisionController@update')->name('pago_comision-update');
    Route::get('/pago_comision/destroy/{id}', 'PagoComisionController@destroy')->name('pago_comision-destroy');
    Route::post('/pago_comision/cancelar/{id}', 'PagoComisionController@pagar')->name('pago_comision-pagar');

    Route::get('/pago_comision/{id}', [
        'as' => 'cancelar_pago_comision',
        'uses' => 'PagoComisionController@cancelar',
    ]);

    /*Contacto*/
    Route::get('/contacto', 'ContactosController@index')->name('contacto');
    Route::post('/contacto/store/{procedencia}', 'ContactosController@store')->name('contacto-store');
    Route::get('/contacto/show/{id}/{procedencia}', 'ContactosController@show')->name('contacto-show');
    Route::post('/contacto/update/{id}/{procedencia}', 'ContactosController@update')->name('contacto-update');
    Route::get('/contacto/destroy/{id}/{procedencia}', 'ContactosController@destroy')->name('contacto-destroy');

    /*Prospecto*/
    Route::get('/prospectos', 'ProspectosController@index')->name('prospectos');
    Route::get('/v_prospecto', 'ProspectosController@v_prospecto')->name('v_prospecto');
    Route::get('/v_apartado', 'ProspectosController@v_apartado')->name('v_apartado');
    Route::get('/v_contrato', 'ProspectosController@v_contrato')->name('v_contrato');
    Route::get('/v_pagando', 'ProspectosController@v_pagando')->name('v_pagando');
    Route::get('/v_escriturado', 'ProspectosController@v_escriturado')->name('v_escriturado');
    Route::get('/v_no_escriturado', 'ProspectosController@v_no_escriturado')->name('v_no_escriturado');
    Route::get('/v_perdido', 'ProspectosController@v_perdido')->name('v_perdido');
    Route::get('/v_postergado', 'ProspectosController@v_postergado')->name('v_postergado');
    Route::post('/prospectos/store', 'ProspectosController@store')->name('prospectos-store');
    Route::get('/prospectos/show/{id}/{ruta}', 'ProspectosController@show')->name('prospectos-show');
    Route::post('/prospectos/update/{id}', 'ProspectosController@update')->name('prospectos-update');
    Route::get('/prospectos/destroy/{id}', 'ProspectosController@destroy')->name('prospectos-destroy');
    Route::get('/prospectos/cotizar/{id}', 'ProspectosController@cotizar')->name('prospectos-cotizar');
    Route::get('/visita/{id}', [
        'as' => 'visita',
        'uses' => 'ProspectosController@visita',
    ]);
    Route::post('/postergar/{id}', [
        'as' => 'postergar',
        'uses' => 'ProspectosController@postergar',
    ]);
    Route::post('/entregar_propiedad/{id}', [
        'as' => 'entregar_propiedad',
        'uses' => 'ProspectosController@entregar_propiedad',
    ]);
    Route::post('/cambiar_asesor/{id}', [
        'as' => 'cambiar_asesor',
        'uses' => 'ProspectosController@cambiar_asesor',
    ]);
    Route::get('/carga', [
        'as' => 'carga',
        'uses' => 'ProspectosController@carga',
    ]);
    Route::get('/perder/{id}', [
        'as' => 'perder',
        'uses' => 'ProspectosController@perder',
    ]);
    Route::get('/reactivar/{id}', [
        'as' => 'reactivar',
        'uses' => 'ProspectosController@reactivar',
    ]);
    Route::post('/admin_estatus/{id}', [
        'as' => 'admin_estatus',
        'uses' => 'ProspectosController@admin_estatus',
    ]);
    Route::get('/regresar/{id}', [
        'as' => 'regresar',
        'uses' => 'ProspectosController@regresar',
    ]);
    Route::get('/contrato/{id}', [
        'as' => 'contrato',
        'uses' => 'ProspectosController@contrato',
    ]);

    Route::get('/crearContrato/{id}', [
        'as' => 'crearContrato',
        'uses' => 'ProspectosController@crearContrato',
    ]);
    Route::get('/crearContratoEnglish/{id}', [
        'as' => 'crearContratoEnglish',
        'uses' => 'ProspectosController@crearContratoEnglish',
    ]);

    Route::get('/apartado/{id}', [
        'as' => 'apartado',
        'uses' => 'ProspectosController@apartar',
    ]);
    Route::post('/pagando/{id}', [
        'as' => 'pagando',
        'uses' => 'ProspectosController@pagando',
    ]);
    Route::get('/no_escriturar/{id}', [
        'as' => 'no_escriturar',
        'uses' => 'ProspectosController@no_escriturar',
    ]);
    Route::get('/escriturar/{id}', [
        'as' => 'escriturar',
        'uses' => 'ProspectosController@escriturar',
    ]);
    Route::post('/cargar_requisitos/{id}', [
       'as' => 'cargar_requisitos',
      'uses' => 'ProspectosController@cargar_requisitos',
    ]);

    Route::get('/cancelar/{id}', [
       'as' => 'cancelar',
      'uses' => 'ProspectosController@cancelar',
    ]);

    /*Plano*/
    Route::get('/plano', 'PlanoController@index')->name('plano');
    Route::post('/plano/store', 'PlanoController@store')->name('plano-store');
    Route::get('/plano/show/{id}', 'PlanoController@show')->name('plano-show');
    Route::get('/plano/nivel/{id}', 'PlanoController@nivel')->name('plano-nivel');
    Route::post('/plano/update/{id}', 'PlanoController@update')->name('plano-update');
    Route::get('/plano/destroy/{id}', 'PlanoController@destroy')->name('plano-destroy');

    /*Cotizacion*/
    Route::get('/cotizacion', 'CotizacionController@index')->name('cotizacion');
    Route::get('/cotizacion/todas', 'CotizacionController@todas')->name('cotizacion-todas');
    Route::post('/cotizacion/continua/{procedencia}', 'CotizacionController@continuar')->name('cotizacion-continuar');
    Route::get('/cotizacion/show/{id}/{procedencia}', 'CotizacionController@show')->name('cotizacion-show');
    Route::post('/cotizacion/update/{id}', 'CotizacionController@update')->name('cotizacion-update');
    Route::post('/cotizacion/calcular/{id}', 'CotizacionController@calcular')->name('cotizacion-calcular');
    Route::get('/cotizacion/destroy/{id}', 'CotizacionController@destroy')->name('cotizacion-destroy');
    Route::get('/cotizacion/generarpdf/{id}', 'CotizacionController@generarpdf')->name('cotizacion-generarpdf');
    Route::get('/cotizacion/generarpdf_ixuh/{id}', 'CotizacionController@generarpdf_ixuh')->name('cotizacion-generarpdf_ixuh');
    Route::get('/cotizacion/cerrar/{id}', 'CotizacionController@cerrar')->name('cotizacion-cerrar');
    Route::get('/cotizacion/abierta/{id}', 'CotizacionController@abierta')->name('cotizacion-abierta');
    Route::post('/cotizacion_detalle/store', 'DetalleCotizacionController@store')->name('cotizacion-store');
    Route::post('/cotizacion_detalle/destroy/{id}', 'DetalleCotizacionController@destroy')->name('cotizacion-destroy');


    /*Detalle Cotizacion propiedad*/
    Route::get('/detalle_cotizacion_propiedad', 'DetalleCotizacionPropiedadController@index')->name('detalle_cotizacion_propiedad');
    Route::post('/detalle_cotizacion_propiedad/store', 'DetalleCotizacionPropiedadController@store')->name('detalle_cotizacion_propiedad-store');
    Route::get('/detalle_cotizacion_propiedad/show/{id}/{procedencia}', 'DetalleCotizacionPropiedadController@show')->name('detalle_cotizacion_propiedad-show');
    Route::post('/detalle_cotizacion_propiedad/update/{id}', 'DetalleCotizacionPropiedadController@update')->name('detalle_cotizacion_propiedad-update');
    Route::post('/detalle_cotizacion_propiedad/destroy/{id}', 'DetalleCotizacionPropiedadController@destroy')->name('detalle_cotizacion_propiedad-destroy');

    /*Detalle cotizacion propiedad rubro*/
    Route::get('/detalle_cotizacion_propiedad_rubro', 'DetalleCotizacionPropiedadRubroController@index')->name('detalle_cotizacion_propiedad_rubro');
    Route::post('/detalle_cotizacion_propiedad_rubro/store/{padre}', 'DetalleCotizacionPropiedadRubroController@store')->name('detalle_cotizacion_propiedad_rubro-store');
    Route::get('/detalle_cotizacion_propiedad_rubro/show/{id}/{procedencia}', 'DetalleCotizacionPropiedadRubroController@show')->name('detalle_cotizacion_propiedad_rubro-show');
    Route::post('/detalle_cotizacion_propiedad_rubro/update/{id}/{procedencia}', 'DetalleCotizacionPropiedadRubroController@update')->name('detalle_cotizacion_propiedad_rubro-update');
    Route::post('/detalle_cotizacion_propiedad_rubro/destroy/{id}', 'DetalleCotizacionPropiedadRubroController@destroy')->name('detalle_cotizacion_propiedad_rubro-destroy');

    /*Actividades*/
    Route::get('/calendario', 'ActividadController@calendario')->name('calendario');
    Route::get('/actividad', 'ActividadController@index')->name('actividad');
    Route::get('/actividad/nueva', 'ActividadController@crear')->name('actividad-nueva');
    Route::post('/actividad/nuevastore', 'ActividadController@storec')->name('actividad-nueva-tore');
    Route::post('/actividad/store/{procedencia}', 'ActividadController@store')->name('actividad-store');
    Route::get('/actividad/show/{id}/{procedencia}', 'ActividadController@show')->name('actividad-show');
    Route::post('/actividad/update/{id}/{procedencia}', 'ActividadController@update')->name('actividad-update');
    Route::get('/actividad/destroy/{id}/{procedencia}', 'ActividadController@destroy')->name('actividad-destroy');
    Route::get('/actividad/completada/{id}/{procedencia}', 'ActividadController@completar')->name('actividad-completada');
    Route::get('/actividad/pendiente/{id}/{procedencia}', 'ActividadController@pendiente')->name('actividad-pendiente');
    Route::get('/actividad/postergar/{id}/{procedencia}', 'ActividadController@postergar')->name('actividad-postergar');
    Route::get('/actividad/cambiar_agente/{id}/{procedencia}', 'ActividadController@cambiar_agente')->name('actividad-postergar');

    /*Requisitos Prospecto*/
    Route::get('/prospectos/requisito_prospecto/show/{id}', 'RequisitosProspectoController@show')->name('requisito_prospecto-show');
    Route::post('/prospectos/requisito_prospecto/update/{id}', 'RequisitosProspectoController@update')->name('requisito_prospecto-update');
    Route::get('/prospectos/requisito_prospecto/completar/{id}', 'RequisitosProspectoController@completar')->name('requisito_prospecto-completar');
    Route::get('/prospectos/requisito_prospecto/pendiente/{id}', 'RequisitosProspectoController@pendiente')->name('requisito_prospecto-pendiente');
    Route::post('/prospectos/requisito_prospecto/completar_todos/{id}', 'RequisitosProspectoController@completar_todos')->name('requisito_prospecto-completar_todos');

    /*Documentos*/
    Route::get('/documentos/documento', 'FileController@index')->name('documentos');
    Route::post('/documentos/documento/store/{procedencia}', 'FileController@store')->name('documento-store');
    Route::get('/documentos/documento/show/{id}/{procedencia}', 'FileController@show')->name('documento-show');
    Route::post('/documentos/documento/update/{id}/{procedencia}', 'FileController@update')->name('documento-update');
    Route::get('/documentos/documento/destroy/{id}/{procedencia}', 'FileController@destroy')->name('documento-destroy');
    Route::get('storage/{archivo}', function ($archivo) {
         $public_path = public_path();
         $url = $public_path.'/uploads/'.$archivo;
         //verificamos si el archivo existe y lo retornamos

           return response()->download($url);
    });

    /*Plazos de pago*/
    Route::get('/plazos_pago', 'PlazosPagoController@index')->name('plazos_pago');
    Route::post('/plazos_pago/store/{procedencia}', 'PlazosPagoController@store')->name('plazos_pago-store');
    Route::get('/plazos_pago/show/{id}/{procedencia}', 'PlazosPagoController@show')->name('plazos_pago-show');
    Route::post('/plazos_pago/update/{id}/{procedencia}', 'PlazosPagoController@update')->name('plazos_pago-update');
    Route::get('/plazos_pago/destroy/{id}', 'PlazosPagoController@destroy')->name('plazos_pago-destroy');
    Route::post('/plazos_pago/pagar/{id}/{procedencia}', 'PlazosPagoController@pagar')->name('plazos_pago-pagar');

    /*Pago de plazo*/
    Route::get('/plazos_pago/pagos', 'PagosController@index')->name('pagos');
    Route::post('/plazos_pago/pagos/store', 'PagosController@store')->name('pagos-store');
    Route::get('/plazos_pago/pagos/show/{id}/{procedencia}', 'PagosController@show')->name('pagos-show');
    Route::post('/plazos_pago/pagos/update/{id}/{procedencia}', 'PagosController@update')->name('pagos-update');
    Route::get('/plazos_pago/pagos/destroy/{id}/{procedencia}', 'PagosController@destroy')->name('pagos-destroy');
    Route::get('/plazos_pago/pagos/cancelar/{id}/{procedencia}', 'PagosController@cancelar')->name('pagos-cancelar');
    Route::get('/plazos_pago/pagos/recibo/{id}/{procedencia}', 'PagosController@recibo')->name('pagos-recibo');

//////////////// AJAX
    Route::get('/catalogo-propiedades/{id}', 'CatalogosAjaxController@CatalogosPropiedades');
    Route::get('/catalogo-estados/{id}', 'CatalogosAjaxController@CatalogosEstados');

    Route::get('/catalogo-ciudades/{id}', 'CatalogosAjaxController@CatalogosCiudades');
    Route::get('/catalogo-bancos/{id}', 'CatalogosAjaxController@CatalogosBancos');
    Route::get('/catalogo-niveles/{id}', 'CatalogosAjaxController@CatalogosNiveles');
    Route::get('/catalogo-proyectos/{id}', 'CatalogosAjaxController@CatalogosProyectos');
    Route::get('/catalogo-prospecto/{id}', 'CatalogosAjaxController@CatalogosCliente');
    Route::get('/catalogo-colores/{id}', 'CatalogosAjaxController@CatalogosColores');
    Route::get('/catalogo-propiedades-desarrollo/{id}', 'CatalogosAjaxController@CatalogosPropiedadesDesarrollo');
    Route::get('/catalogo-propiedades-desarrollo-estatus/{id}', 'CatalogosAjaxController@CatalogosPropiedadesDesarrolloSinEstatus');
    Route::get('/catalogo-grupo-esquema/{id}/{uso}', 'CatalogosAjaxController@CatalogosGrupoEsquema');
    Route::get('/catalogo-esquema_detalle_cotizacion_propiedad/{id}/{propiedad}', 'CatalogosAjaxController@CatalogosDetalleCotizacionPropiedad');
    /*Actividades y mensajes*/
    Route::get('/actividades-hoy', 'CatalogosAjaxController@actividades_hoy');
    Route::get('/mensajes-nuevos', 'CatalogosAjaxController@mensajes_nuevos');
    Route::get('/catalogo-cotizaciones-contrato/{prospecto}', 'CatalogosAjaxController@CatalogosCotizacionesContrato'); //DEprecated
//////////////// EXPORTAR IMPORTAR EXCEL

    Route::get('/exportar_prospectos', 'ProspectosController@exportExcel');
    Route::get('/exportar_plazos_pago', 'PlazosPagoController@exportExcel'); 
    Route::get('/exportar_propiedades', 'PropiedadController@exportExcel'); 
    Route::get('/exportar_enganches_recibir', 'ReportesExcelController@exportExcelEnganches'); 
    /* Hibrido */
    Route::get('/exportar_pagos', 'ReportesPDFController@exportar_pagos')->name('exportar_pagos');
    Route::get('/exportar_ventas', 'ReportesPDFController@exportar_ventas')->name('exportar_ventas');
    Route::get('/exportar_visitas', 'ReportesPDFController@exportar_visitas')->name('exportar_visitas');
    /*IMPORTAR*/
    Route::get('/importar', 'ImportController@index')->name('importar'); //Nos lleva al vista index
    Route::post('/importar_excel', 'ImportController@importar_excel')->name('importar_excel'); // Nos lleva al excel ya cargado
    Route::post('/importar_excel_finally', 'ImportController@importar_excel_finally')->name('importar_excel_finally');//Proceso final para guardarlo
//////////////// CORREOS
    /*MENSAJES*/
    Route::get('/mensaje', 'MensajeController@index')->name('mensaje');
    Route::post('/mensaje/store', 'MensajeController@store')->name('mensaje-store');
    Route::get('/mensaje/show/{id}/{ruta}', 'MensajeController@show')->name('mensaje-show');
    Route::get('/mensaje/enviar/{id}', 'MensajeController@enviar')->name('mensaje-enviar');
    Route::post('/mensaje/update/{id}', 'MensajeController@update')->name('mensaje-update');
    Route::get('/mensaje/destroy/{id}', 'MensajeController@destroy')->name('mensaje-destroy');
//////////////// REPORTES
    Route::get('/reportes', 'HomeController@reportes')->name('reportes');
    Route::get('/cuenta_cobrar', 'ReportesController@cuenta_cobrar')->name('cuenta_cobrar');
    Route::get('/cuenta_cobrar_pdf', 'ReportesPDFController@cuenta_cobrar_pdf')->name('cuenta_cobrar_pdf');
    Route::get('/semaforo', 'ReportesController@semaforo')->name('semaforo');
    Route::get('/semaforo_pdf', 'ReportesPDFController@semaforo_pdf')->name('semaforo_pdf');
    Route::get('/reportes/estatus_propiedad', 'ReportesController@estatus_propiedad')->name('reportes_estatus_propiedad');
    Route::get('/reportes/medio_contacto', 'ReportesController@medio_contacto')->name('reportes_medio_contacto');
    Route::get('/reportes/cliente_mes', 'ReportesController@cliente_mes')->name('reportes_cliente_mes');
    Route::get('/reportes/analisis_metros', 'ReportesController@analisis_metros')->name('reportes_analisis_metros');
    Route::get('/reportes/enganche_estimado', 'ReportesController@enganche_estimado')->name('reportes_enganche_estimado');
    Route::get('/reportes/enganche_estimado_pdf', 'ReportesPDFController@enganche_estimado_pdf')->name('reportes_enganche_estimado_pdf');
    Route::get('/reportes/oportunidades', 'ReportesController@oportunidades')->name('reportes_oportunidades');
    Route::get('/reportes/ventas', 'ReportesController@ventas')->name('reportes_ventas');
    Route::get('/reportes/comparativo', 'ReportesController@comparativo')->name('reportes_comparativo');
    Route::get('/reportes/pagos', 'ReportesController@pagos')->name('reportes_pagos');
    Route::get('/reportes/visitas', 'ReportesController@visitas')->name('reportes_visitas');
    Route::get('/reportes/clientes_asesor', 'ReportesController@clientes_asesor')->name('reportes_clientes_asesor');
//////////////// CATALOGOS
    Route::get('/catalogos', 'HomeController@catalogos')->name('catalogos');
    
    /*uso propiedad*/
    Route::get('/catalogos/uso-propiedad', 'UsoPropiedadController@index')->name('uso-propiedad');
    Route::post('/catalogos/uso-propiedad/store', 'UsoPropiedadController@store')->name('uso-propiedad-store');
    Route::get('/catalogos/uso-propiedad/show/{id}', 'UsoPropiedadController@show')->name('uso-propiedad-show');
    Route::post('/catalogos/uso-propiedad/update/{id}', 'UsoPropiedadController@update')->name('uso-propiedad-update');
    Route::get('/catalogos/uso-propiedad/destroy/{id}', 'UsoPropiedadController@destroy')->name('uso-propiedad-destroy');
    /*Condicion entrega*/
    Route::get('/catalogos/condicion_entrega', 'CondicionEntregaController@index')->name('condicion_entrega');
    Route::post('/catalogos/condicion_entrega/store', 'CondicionEntregaController@store')->name('condicion_entrega-store');
    Route::get('/catalogos/condicion_entrega/show/{id}', 'CondicionEntregaController@show')->name('condicion_entrega-show');
    Route::post('/catalogos/condicion_entrega/update/{id}', 'CondicionEntregaController@update')->name('condicion_entrega-update');
    Route::get('/catalogos/condicion_entrega/destroy/{id}', 'CondicionEntregaController@destroy')->name('condicion_entrega-destroy');

    /*Condicion entrega detalle*/
    Route::get('/catalogos/condicion_entrega_detalle', 'CondicionEntregaDetalleController@index')->name('condicion_entrega_detalle');
    Route::post('/catalogos/condicion_entrega_detalle/store', 'CondicionEntregaDetalleController@store')->name('condicion_entrega_detalle-store');
    Route::get('/catalogos/condicion_entrega_detalle/show/{id}/{procedencia}', 'CondicionEntregaDetalleController@show')->name('condicion_entrega_detalle-show');
    Route::post('/catalogos/condicion_entrega_detalle/update/{id}', 'CondicionEntregaDetalleController@update')->name('condicion_entrega_detalle-update');
    Route::get('/catalogos/condicion_entrega_detalle/destroy/{id}', 'CondicionEntregaDetalleController@destroy')->name('condicion_entrega_detalle-destroy');

    /*grupo Esquema de pago*/
    Route::get('/catalogos/grupo_esquema', 'GrupoEsquemaController@index')->name('grupo_esquema');
    Route::post('/catalogos/grupo_esquema/store', 'GrupoEsquemaController@store')->name('grupo_esquema-store');
    Route::get('/catalogos/grupo_esquema/show/{id}', 'GrupoEsquemaController@show')->name('grupo_esquema-show');
    Route::post('/catalogos/grupo_esquema/update/{id}', 'GrupoEsquemaController@update')->name('grupo_esquema-update');
    Route::get('/catalogos/grupo_esquema/destroy/{id}', 'GrupoEsquemaController@destroy')->name('grupo_esquema-destroy');

    /*Esquema de pago*/
    Route::get('/catalogos/esquema_pago', 'EsquemaPagoController@index')->name('esquema_pago');
    Route::post('/catalogos/esquema_pago/store', 'EsquemaPagoController@store')->name('esquema_pago-store');
    Route::get('/catalogos/esquema_pago/show/{id}/{procedencia}', 'EsquemaPagoController@show')->name('esquema_pago-show');
    Route::post('/catalogos/esquema_pago/update/{id}', 'EsquemaPagoController@update')->name('esquema_pago-update');
    Route::get('/catalogos/esquema_pago/destroy/{id}', 'EsquemaPagoController@destroy')->name('esquema_pago-destroy');


    /*Detalle esquema de pago*/
    Route::get('/catalogos/detalle_esquema_pago', 'DetalleEsquemaPagoController@index')->name('detalle_esquema_pago');
    Route::post('/catalogos/detalle_esquema_pago/store', 'DetalleEsquemaPagoController@store')->name('detalle_esquema_pago-store');
    Route::get('/catalogos/detalle_esquema_pago/show/{id}/{procedencia}', 'DetalleEsquemaPagoController@show')->name('detalle_esquema_pago-show');
    Route::post('/catalogos/detalle_esquema_pago/update/{id}', 'DetalleEsquemaPagoController@update')->name('detalle_esquema_pago-update');
    Route::get('/catalogos/detalle_esquema_pago/destroy/{id}', 'DetalleEsquemaPagoController@destroy')->name('detalle_esquema_pago-destroy');

    /*Tipo propiedad*/
    Route::get('/catalogos/tipo-propiedad', 'TipoPropiedadController@index')->name('tipo_propiedad');
    Route::post('/catalogos/tipo-propiedad/store', 'TipoPropiedadController@store')->name('tipo-propiedad-store');
    Route::get('/catalogos/tipo-propiedad/show/{id}', 'TipoPropiedadController@show')->name('tipo-propiedad-show');
    Route::post('/catalogos/tipo-propiedad/update/{id}', 'TipoPropiedadController@update')->name('tipo-propiedad-update');
    Route::get('/catalogos/tipo-propiedad/destroy/{id}', 'TipoPropiedadController@destroy')->name('tipo-propiedad-destroy');

    /*Tipo propiedad bakt*/
    Route::get('/catalogos/tipo_modelo', 'TipoPropiedadBaktController@index')->name('tipo_modelo');
    Route::post('/catalogos/tipo_modelo/store', 'TipoPropiedadBaktController@store')->name('tipo_modelo-store');
    Route::get('/catalogos/tipo_modelo/show/{id}', 'TipoPropiedadBaktController@show')->name('tipo_modelo-show');
    Route::post('/catalogos/tipo_modelo/update/{id}', 'TipoPropiedadBaktController@update')->name('tipo_modelo-update');
    Route::get('/catalogos/tipo_modelo/destroy/{id}', 'TipoPropiedadBaktController@destroy')->name('tipo_modelo-destroy');

    /*Amenidades*/
    Route::get('/catalogos/amenidad', 'AmenidadController@index')->name('amenidades');
    Route::post('/catalogos/amenidad/store', 'AmenidadController@store')->name('amenidad-store');
    Route::get('/catalogos/amenidad/show/{id}', 'AmenidadController@show')->name('amenidad-show');
    Route::post('/catalogos/amenidad/update/{id}', 'AmenidadController@update')->name('amenidad-update');
    Route::get('/catalogos/amenidad/destroy/{id}', 'AmenidadController@destroy')->name('amenidad-destroy');

    /*NIVELES*/
    Route::get('/catalogos/nivel', 'NivelController@index')->name('nivel');
    Route::post('/catalogos/nivel/store', 'NivelController@store')->name('nivel-store');
    Route::get('/catalogos/nivel/show/{id}/{procedencia}', 'NivelController@show')->name('nivel-show');
    Route::post('/catalogos/nivel/update/{id}', 'NivelController@update')->name('nivel-update');
    Route::get('/catalogos/nivel/destroy/{id}', 'NivelController@destroy')->name('nivel-destroy');

    /*Agentes*/
    Route::get('/catalogos/usuarios', 'AgenteController@index')->name('usuarios');
    Route::post('/catalogos/usuarios/store', 'AgenteController@store')->name('usuarios-store');
    Route::get('/catalogos/usuarios/show/{id}', 'AgenteController@show')->name('usuarios-show');
    Route::get('/catalogos/usuarios/profile/{id}', 'AgenteController@profile')->name('usuarios-profile');
    Route::post('/catalogos/usuarios/update/{id}', 'AgenteController@update')->name('usuarios-update');
    Route::post('/catalogos/usuarios/updateprofile/{id}', 'AgenteController@updateprofile')->name('usuarios-updateprofile');
    Route::get('/catalogos/usuarios/destroy/{id}', 'AgenteController@destroy')->name('usuarios-destroy');
    Route::get('/catalogos/usuarios/activa/{id}', 'AgenteController@activa')->name('usuarios-activa');
    Route::get('/catalogos/usuarios/inactiva/{id}', 'AgenteController@inactiva')->name('usuarios-inactiva');

    /*usuarios externos*/
    Route::get('/catalogos/usuarios_externos', 'UsuariosExternosController@index')->name('usuarios_externos');
    Route::post('/catalogos/usuarios_externos/store', 'UsuariosExternosController@store')->name('usuarios_externos-store');
    Route::get('/catalogos/usuarios_externos/show/{id}', 'UsuariosExternosController@show')->name('usuarios_externos-show');
    Route::post('/catalogos/usuarios_externos/update/{id}', 'UsuariosExternosController@update')->name('usuarios_externos-update');
    Route::get('/catalogos/usuarios_externos/destroy/{id}', 'UsuariosExternosController@destroy')->name('usuarios_externos-destroy');

    /*roles*/
    Route::get('/catalogos/rol', 'RolController@index')->name('rol');
    Route::post('/catalogos/rol/store', 'RolController@store')->name('rol-store');
    Route::get('/catalogos/rol/show/{id}', 'RolController@show')->name('rol-show');
    Route::post('/catalogos/rol/update/{id}', 'RolController@update')->name('rol-update');
    Route::get('/catalogos/rol/destroy/{id}', 'RolController@destroy')->name('rol-destroy');

    /*Tipo operacion*/
    Route::get('/catalogos/tipo-operacion', 'TipoOperacionController@index')->name('tipo_operacion');
    Route::post('/catalogos/tipo-operacion/store', 'TipoOperacionController@store')->name('tipo-operacion-store');
    Route::get('/catalogos/tipo-operacion/show/{id}', 'TipoOperacionController@show')->name('tipo-operacion-show');
    Route::post('/catalogos/tipo-operacion/update/{id}', 'TipoOperacionController@update')->name('tipo-operacion-update');
    Route::get('/catalogos/tipo-operacion/destroy/{id}', 'TipoOperacionController@destroy')->name('tipo-operacion-destroy');

    /*Ciudades*/
    Route::get('/catalogos/ciudad', 'CiudadController@index')->name('ciudades');
    Route::post('/catalogos/ciudad/store', 'CiudadController@store')->name('ciudad-store');
    Route::get('/catalogos/ciudad/show/{id}', 'CiudadController@show')->name('ciudad-show');
    Route::post('/catalogos/ciudad/update/{id}', 'CiudadController@update')->name('ciudad-update');
    Route::get('/catalogos/ciudad/destroy/{id}', 'CiudadController@destroy')->name('ciudad-destroy');

    /*Pais*/
    Route::get('/catalogos/pais', 'PaisController@index')->name('pais');
    Route::post('/catalogos/pais/store', 'PaisController@store')->name('pais-store');
    Route::get('/catalogos/pais/show/{id}', 'PaisController@show')->name('pais-show');
    Route::post('/catalogos/pais/update/{id}', 'PaisController@update')->name('pais-update');
    Route::get('/catalogos/pais/destroy/{id}', 'PaisController@destroy')->name('pais-destroy');

    /*Estado*/
    Route::get('/catalogos/estado', 'EstadoController@index')->name('estado');
    Route::post('/catalogos/estado/store', 'EstadoController@store')->name('estado-store');
    Route::get('/catalogos/estado/show/{id}', 'EstadoController@show')->name('estado-show');
    Route::post('/catalogos/estado/update/{id}', 'EstadoController@update')->name('estado-update');
    Route::get('/catalogos/estado/destroy/{id}', 'EstadoController@destroy')->name('estado-destroy');

    /*Estatus prospecto*/
    Route::get('/catalogos/estatus_prospecto', 'EstatusProspectoController@index')->name('estatus_prospecto');
    Route::post('/catalogos/estatus_prospecto/store', 'EstatusProspectoController@store')->name('estatus_prospecto-store');
    Route::get('/catalogos/estatus_prospecto/show/{id}', 'EstatusProspectoController@show')->name('estatus_prospecto-show');
    Route::post('/catalogos/estatus_prospecto/update/{id}', 'EstatusProspectoController@update')->name('estatus_prospecto-update');
    Route::get('/catalogos/estatus_prospecto/destroy/{id}', 'EstatusProspectoController@destroy')->name('estado-destroy');

    /*Estatus propiedad*/
    Route::get('/catalogos/estatus_propiedad', 'EstatusPropiedadController@index')->name('estatus_propiedad');
    Route::post('/catalogos/estatus_propiedad/store', 'EstatusPropiedadController@store')->name('estatus_propiedad-store');
    Route::get('/catalogos/estatus_propiedad/show/{id}', 'EstatusPropiedadController@show')->name('estatus_propiedad-show');
    Route::post('/catalogos/estatus_propiedad/update/{id}', 'EstatusPropiedadController@update')->name('estatus_propiedad-update');
    Route::get('/catalogos/estatus_propiedad/destroy/{id}', 'EstatusPropiedadController@destroy')->name('estatus_propiedad-destroy');

    /*Estatus propiedad*/
    Route::get('/catalogos/estatus_propiedad', 'EstatusPropiedadController@index')->name('estatus_propiedad');
    Route::post('/catalogos/estatus_propiedad/store', 'EstatusPropiedadController@store')->name('estatus_propiedad-store');
    Route::get('/catalogos/estatus_propiedad/show/{id}', 'EstatusPropiedadController@show')->name('estatus_propiedad-show');
    Route::post('/catalogos/estatus_propiedad/update/{id}', 'EstatusPropiedadController@update')->name('estatus_propiedad-update');
    Route::get('/catalogos/estatus_propiedad/destroy/{id}', 'EstatusPropiedadController@destroy')->name('estatus_propiedad-destroy');

    /*Forma de pago*/
    Route::get('/catalogos/forma_pago', 'FormaPagoController@index')->name('forma_pago');
    Route::post('/catalogos/forma_pago/store', 'FormaPagoController@store')->name('forma_pago-store');
    Route::get('/catalogos/forma_pago/show/{id}', 'FormaPagoController@show')->name('forma_pago-show');
    Route::post('/catalogos/forma_pago/update/{id}', 'FormaPagoController@update')->name('forma_pago-update');
    Route::get('/catalogos/forma_pago/destroy/{id}', 'FormaPagoController@destroy')->name('forma_pago-destroy');

    /*Colores*/
    Route::get('/catalogos/color', 'ColoresController@index')->name('color');
    Route::post('/catalogos/color/store', 'ColoresController@store')->name('color-store');
    Route::get('/catalogos/color/show/{id}', 'ColoresController@show')->name('color-show');
    Route::post('/catalogos/color/update/{id}', 'ColoresController@update')->name('color-update');
    Route::get('/catalogos/color/destroy/{id}', 'ColoresController@destroy')->name('color-destroy');

    /*Monedas*/
    Route::get('/catalogos/moneda', 'MonedaController@index')->name('moneda');
    Route::post('/catalogos/moneda/store', 'MonedaController@store')->name('moneda-store');
    Route::get('/catalogos/moneda/show/{id}', 'MonedaController@show')->name('moneda-show');
    Route::post('/catalogos/moneda/update/{id}', 'MonedaController@update')->name('moneda-update');
    Route::get('/catalogos/moneda/destroy/{id}', 'MonedaController@destroy')->name('moneda-destroy');

    /*Motivo de perdida*/
    Route::get('/catalogos/motivo_perdida', 'MotivoPerdidaController@index')->name('motivo_perdida');
    Route::post('/catalogos/motivo_perdida/store', 'MotivoPerdidaController@store')->name('motivo_perdida-store');
    Route::get('/catalogos/motivo_perdida/show/{id}', 'MotivoPerdidaController@show')->name('motivo_perdida-show');
    Route::post('/catalogos/motivo_perdida/update/{id}', 'MotivoPerdidaController@update')->name('motivo_perdida-update');
    Route::get('/catalogos/motivo_perdida/destroy/{id}', 'MotivoPerdidaController@destroy')->name('motivo_perdida-destroy');

    /*Medio de contacto*/
    Route::get('/catalogos/medio_contacto', 'MedioContactoController@index')->name('medio_contacto');
    Route::post('/catalogos/medio_contacto/store', 'MedioContactoController@store')->name('medio_contacto-store');
    Route::get('/catalogos/medio_contacto/show/{id}', 'MedioContactoController@show')->name('medio_contacto-show');
    Route::post('/catalogos/medio_contacto/update/{id}', 'MedioContactoController@update')->name('medio_contacto-update');
    Route::get('/catalogos/medio_contacto/destroy/{id}', 'MedioContactoController@destroy')->name('medio_contacto-destroy');

    /*Requisitos*/
    Route::get('/catalogos/requisito', 'RequisitosController@index')->name('requisito');
    Route::post('/catalogos/requisito/store', 'RequisitosController@store')->name('requisito-store');
    Route::get('/catalogos/requisito/show/{id}', 'RequisitosController@show')->name('requisito-show');
    Route::post('/catalogos/requisito/update/{id}', 'RequisitosController@update')->name('requisito-update');
    Route::get('/catalogos/requisito/destroy/{id}', 'RequisitosController@destroy')->name('requisito-destroy'); 

    /*Requisitos*/
    Route::get('/catalogos/requisitos_detalle', 'RequisitoDetalleController@index')->name('requisitos_detalle');
    Route::post('/catalogos/requisitos_detalle/store/{id}', 'RequisitoDetalleController@store')->name('requisitos_detalle-store');
    Route::get('/catalogos/requisitos_detalle/show/{id}', 'RequisitoDetalleController@show')->name('requisitos_detalle-show');
    Route::post('/catalogos/requisitos_detalle/update/{id}', 'RequisitoDetalleController@update')->name('requisitos_detalle-update');
    Route::get('/catalogos/requisitos_detalle/destroy/{id}', 'RequisitoDetalleController@destroy')->name('requisitos_detalle-destroy'); 

    /*Esquem a de comisiones*/
    Route::get('/catalogos/esquema', 'EsquemaComisionController@index')->name('esquema');
    Route::post('/catalogos/esquema/store', 'EsquemaComisionController@store')->name('esquema-store');
    Route::get('/catalogos/esquema/show/{id}', 'EsquemaComisionController@show')->name('esquema-show');
    Route::post('/catalogos/esquema/update/{id}', 'EsquemaComisionController@update')->name('esquema-update');
    Route::get('/catalogos/esquema/destroy/{id}', 'EsquemaComisionController@destroy')->name('esquema-destroy'); 

    /*Detalle esquema de comisiones*/
    Route::get('/catalogos/esquema/detalle_esquema', 'DetalleEsquemaComisionController@index')->name('detalle_esquema');
    Route::post('/catalogos/esquema/detalle_esquema/store/{id}', 'DetalleEsquemaComisionController@store')->name('detalle_esquema-store');
    Route::get('/catalogos/esquema/detalle_esquema/show/{id}', 'DetalleEsquemaComisionController@show')->name('detalle_esquema-show');
    Route::post('/catalogos/esquema/detalle_esquema/update/{id}', 'DetalleEsquemaComisionController@update')->name('detalle_esquema-update');
    Route::get('/catalogos/esquema/detalle_esquema/destroy/{id}', 'DetalleEsquemaComisionController@destroy')->name('detalle_esquema-destroy'); 
    /*Empresa*/
    Route::get('/catalogos/empresa', 'EmpresaController@index')->name('empresa');
    Route::post('/catalogos/empresa/store', 'EmpresaController@store')->name('empresa-store');
    Route::get('/catalogos/empresa/show/{id}', 'EmpresaController@show')->name('empresa-show');
    Route::post('/catalogos/empresa/update/{id}', 'EmpresaController@update')->name('empresa-update');
    Route::get('/catalogos/empresa/destroy/{id}', 'EmpresaController@destroy')->name('empresa-destroy');

    /*Regimen fiscal*/
    Route::get('/catalogos/regimen_fiscal', 'RegimenFiscalController@index')->name('regimen_fiscal');
    Route::post('/catalogos/regimen_fiscal/store', 'RegimenFiscalController@store')->name('regimen_fiscal-store');
    Route::get('/catalogos/regimen_fiscal/show/{id}', 'RegimenFiscalController@show')->name('regimen_fiscal-show');
    Route::post('/catalogos/regimen_fiscal/update/{id}', 'RegimenFiscalController@update')->name('regimen_fiscal-update');
    Route::get('/catalogos/regimen_fiscal/destroy/{id}', 'RegimenFiscalController@destroy')->name('regimen_fiscal-destroy');

    /*Bancos*/
    Route::get('/catalogos/banco', 'BancoController@index')->name('banco');
    Route::post('/catalogos/banco/store', 'BancoController@store')->name('banco-store');
    Route::get('/catalogos/banco/show/{id}', 'BancoController@show')->name('banco-show');
    Route::post('/catalogos/banco/update/{id}', 'BancoController@update')->name('banco-update');
    Route::get('/catalogos/banco/destroy/{id}', 'BancoController@destroy')->name('banco-destroy');
//////////////// EXTERNOS
    /*uso propiedad*/
    Route::get('/externos/plazos', 'ExternosController@indexPlazos')->name('plazos-externos');
    Route::get('/externos/plazos/show/{id}', 'ExternosController@showplazo')->name('plazosShow-externos');
    Route::get('/externos/documentos/', 'ExternosController@indexDocs')->name('documentos-externos');
/////////////// CARGA E INFORMACION
    Route::get('/carga/imagen/', 'CargaInfoController@subirImagenes')->name('subir-imagenes');
