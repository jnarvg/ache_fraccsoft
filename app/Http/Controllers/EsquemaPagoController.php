<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\EsquemaPago;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EsquemaPagoController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {

        $filtro = $this->build_filtro($request);
        $resultados = DB::table('esquema_pago as ep')
        ->join('proyecto as p','ep.proyecto_id','=','p.id_proyecto','left',false)
        ->join('uso_propiedad as u','ep.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
        ->select('id_esquema_pago', 'ep.uso_propiedad_id', 'ep.proyecto_id', 'esquema_pago','ep.incluir','p.nombre as proyecto','u.uso_propiedad','ep.created_at','ep.porcentaje_descuento','ep.grupo_esquema_id')
        ->orderby('id_esquema_pago','ASC')
        ->whereRaw($filtro)
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad','uso_propiedad')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();

        $grupo_esquema = DB::table('grupo_esquema')
        ->select('id_grupo_esquema','grupo_esquema')
        ->get();

        $incluir = array('1','0');

        return view('catalogos.esquema_pago.index', compact('resultados','usos_propiedad','proyectos','incluir','request','grupo_esquema'));
    }
    public function store(request $request)
    {
        $nuevo = new EsquemaPago();
        $nuevo->esquema_pago = $request->get('esquema_pago');
        $nuevo->proyecto_id = $request->get('proyecto_id');
        $nuevo->uso_propiedad_id = $request->get('uso_propiedad_id');
        $nuevo->porcentaje_descuento = $request->get('porcentaje_descuento');
        $nuevo->grupo_esquema_id = $request->get('grupo_esquema_id');
        if ($request->get('incluir')) {
            $nuevo->incluir = 1;
        }else{
            $nuevo->incluir = 0;        
        }
        $nuevo->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function show($id, $procedencia)
    {
        $resultado = DB::table('esquema_pago as ep')
        ->select('id_esquema_pago', 'ep.uso_propiedad_id', 'ep.proyecto_id', 'esquema_pago','ep.incluir','ep.created_at','ep.porcentaje_descuento','ep.grupo_esquema_id')
        ->where('id_esquema_pago', $id)
        ->first();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad','uso_propiedad')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();

        $grupo_esquema = DB::table('grupo_esquema')
        ->select('id_grupo_esquema','grupo_esquema')
        ->get();

        $tipo_calculo = array('Porcentaje','Monto','Autocompletar');
        $tipo = array('Inicio','Mensualidad','Fin','Abono a capital','No aplica');
        $incluir = array('1','0');
        $resultados_detalle = DB::table('detalle_esquema_pago')
        ->select('id_detalle_esquema_pago', 'alias','tipo', 'tipo_calculo', 'porcentaje', 'monto', 'mensualidades', 'esquema_pago_id', 'created_at')
        ->where('esquema_pago_id','=',$id)
        ->orderby('id_detalle_esquema_pago','ASC')
        ->get();

        return view('catalogos.esquema_pago.show', compact('resultado','resultados_detalle','proyectos','usos_propiedad','tipo_calculo','tipo','incluir', 'procedencia', 'grupo_esquema'));
    }
    public function update(request $request, $id)
    {
        $nuevo = EsquemaPago::findOrFail($id);
        $nuevo->esquema_pago = $request->get('esquema_pago');
        $nuevo->proyecto_id = $request->get('proyecto_id');
        $nuevo->uso_propiedad_id = $request->get('uso_propiedad_id');
        $nuevo->porcentaje_descuento = $request->get('porcentaje_descuento');
        $nuevo->grupo_esquema_id = $request->get('grupo_esquema_id');

        if ($request->get('incluir')) {
            $nuevo->incluir = 1;
        }else{
            $nuevo->incluir = 0;        
        }
        $nuevo->update();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
    public function destroy($id)
    {
        $detalles = DB::table('detalle_esquema_pago as ep')
        ->select('id_detalle_esquema_pago')
        ->where('esquema_pago_id', $id)
        ->get();

        if(count($detalles) > 0){
            $notification = array(
                'msj' => 'No se puede eliminar, aun hay detalles relacionados a este esquema',
                'alert-type' => 'error'
            );
        }else{
            $borrar = EsquemaPago::find($id);
            $borrar->delete();
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
        }
        return back()->with($notification);

    }

    public function build_filtro(request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $uso_propiedad_bs = $request->get('uso_propiedad_bs');
        $esquema_bs = $request->get('esquema_bs');

        $filtro = ' id_esquema_pago IS NOT NULL';

        if ($proyecto_bs != null and $proyecto_bs != '' and $proyecto_bs and $proyecto_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.proyecto_id = '.$proyecto_bs;
        }
        if ($uso_propiedad_bs != null and $uso_propiedad_bs != '' and $uso_propiedad_bs and $uso_propiedad_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.uso_propiedad_id = '.$uso_propiedad_bs;
        }
        if ($esquema_bs != null and $esquema_bs != '' and $esquema_bs and $esquema_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro .  'ep.esquema_pago LIKE "%'.$esquema_bs.'%"';
        }
        return $filtro;
    }
}
