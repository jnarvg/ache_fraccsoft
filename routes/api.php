<?php

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('prospectos', function( request $request){
    $nombre_bs = $request->get('nombre_bs');
    $filtro = ' p.nombre LIKE "%'.$nombre_bs.'%"';
    return datatables( DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.pagado','porcentaje_interes')
        ->orderby('fecha_registro','DESC')
        ->whereRaw($filtro))
      ->addColumn('btn','prospectos.actions')
      ->rawColumns(['btn'])
      ->toJson();
});
