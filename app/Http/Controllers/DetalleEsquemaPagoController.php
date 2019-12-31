<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\DetalleEsquemaPago;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class DetalleEsquemaPagoController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
     public function index(request $request)
    {
        $filtro = $this->build_filtro($request);
        $resultados = DB::table('detalle_esquema_pago as ep')
        ->join('esquema_pago as p','ep.esquema_pago_id','=','p.id_esquema_pago','left',false)
        ->select('id_detalle_esquema_pago', 'ep.alias', 'ep.esquema_pago_id', 'ep.tipo','ep.tipo_calculo','p.esquema_pago','ep.porcentaje','ep.monto','ep.mensualidades','ep.created_at')
        ->orderby('id_detalle_esquema_pago','DESC')
        ->whereRaw($filtro)
        ->get();

        $esquemas = DB::table('esquema_pago')
        ->select('id_esquema_pago','esquema_pago')
        ->get();

        $tipo_calculo = array('Porcentaje','Monto','Autocompletar');
        $tipo = array('Inicio','Mensualidad','Fin','Abono a capital','No aplica');

        return view('catalogos.detalle_esquema_pago.index', compact('resultados','esquemas','tipo_calculo','tipo', 'request'));
    }
    public function store(request $request)
    {
        $nuevo = new DetalleEsquemaPago();
        $nuevo->alias = $request->get('alias');
        $nuevo->esquema_pago_id = $request->get('esquema_pago_id');
        $nuevo->tipo_calculo = $request->get('tipo_calculo');
        $nuevo->tipo = $request->get('tipo');
        $nuevo->monto =  floatval(str_replace(',', '', $request->get('monto')));
        $nuevo->porcentaje = $request->get('porcentaje');
        $nuevo->mensualidades = $request->get('mensualidades');
        $nuevo->save();
        $sumaporcentajes = DB::table('detalle_esquema_pago')
        ->select( DB::raw('SUM(porcentaje) as total_porcentaje'))
        ->where('tipo_calculo','Porcentaje')
        ->where('tipo','!=','No aplica')
        ->where('esquema_pago_id', $request->get('esquema_pago_id'))
        ->first();
        $total_porcentaje = $sumaporcentajes->total_porcentaje + $request->get('porcentaje');
        if ($total_porcentaje > 100) {
            $notification = array(
                'msj' => 'La suma es MAYOR al 100%!!',
                'alert-type' => 'error'
            );
        }else{
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );

        }
        return back()->with($notification);
    }
    public function show($id, $procedencia)
    {
        $resultado = DB::table('detalle_esquema_pago as ep')
        ->select('id_detalle_esquema_pago', 'ep.alias', 'ep.esquema_pago_id', 'ep.tipo','ep.tipo_calculo','ep.porcentaje','ep.monto','ep.mensualidades','ep.created_at')
        ->where('id_detalle_esquema_pago',$id)
        ->first();

        $esquemas = DB::table('esquema_pago')
        ->select('id_esquema_pago','esquema_pago')
        ->get();

        $tipo_calculo = array('Porcentaje','Monto','Autocompletar');
        $tipo = array('Inicio','Mensualidad','Fin','Abono a capital','No aplica');


        return view('catalogos.detalle_esquema_pago.show', compact('resultado','esquemas','tipo_calculo','tipo','procedencia'));
    }
    public function update(request $request, $id)
    {
        $nuevo = DetalleEsquemaPago::findOrFail($id);
        $nuevo->alias = $request->get('alias');
        $nuevo->esquema_pago_id = $request->get('esquema_pago_id');
        $nuevo->tipo_calculo = $request->get('tipo_calculo');
        $nuevo->tipo = $request->get('tipo');
        $nuevo->monto = floatval(str_replace(',', '', $request->get('monto')));
        $nuevo->porcentaje = $request->get('porcentaje');
        $nuevo->mensualidades = $request->get('mensualidades');     
        $nuevo->update();

        $sumaporcentajes = DB::table('detalle_esquema_pago')
        ->select( DB::raw('SUM(porcentaje) as total_porcentaje'))
        ->where('tipo_calculo','Porcentaje')
        ->where('tipo','!=','No aplica')
        ->where('esquema_pago_id', $request->get('esquema_pago_id'))
        ->first();
        $total_porcentaje = $sumaporcentajes->total_porcentaje + $request->get('porcentaje');
        if ($total_porcentaje > 100) {
            $notification = array(
                'msj' => 'La suma es MAYOR al 100%!!',
                'alert-type' => 'error'
            );
        }else{
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
        }
        return back()->with($notification);
    }
    public function destroy($id)
    {
        $borrar = DetalleEsquemaPago::find($id);
        $borrar->delete();
        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);

    }

    public function build_filtro(request $request)
    {
        $tipo_bs = $request->get('tipo_bs');
        $tipo_calculo_bs = $request->get('tipo_calculo_bs');
        $alias_bs = $request->get('alias_bs');

        $filtro = ' id_detalle_esquema_pago IS NOT NULL';

        if ($tipo_bs != null and $tipo_bs != '' and $tipo_bs and $tipo_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.tipo = "'.$tipo_bs.'"';
        }
        if ($tipo_calculo_bs != null and $tipo_calculo_bs != '' and $tipo_calculo_bs and $tipo_calculo_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.tipo_calculo = "'.$tipo_calculo_bs.'"';
        }
        if ($alias_bs != null and $alias_bs != '' and $alias_bs and $alias_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro .  'ep.alias LIKE "%'.$alias_bs.'%"';
        }
        return $filtro;
    }

}
