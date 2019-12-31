<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Comision;
use App\ComisionDetalle;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class ComisionController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $estatus = $request->get('estatus_bs');
        $estatus_pago = $request->get('estatus_pago_bs');
        $cliente = $request->get('cliente_bs');
        $propiedad = $request->get('propiedad_bs');
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');
        if ($rows_page == '') {
            $rows_page = 10;
        }

        $comisiones = DB::table('comision as com')
        ->join('propiedad as p','propiedad_id','=','p.id_propiedad','left',false)
        ->join('prospectos as c','cliente_id','=','c.id_prospecto','left',false)
        ->select('id_comision', 'cliente_id', 'com.propiedad_id', 'com.estatus', 'com.estatus_pago', 'com.monto_operacion', 'com.fecha_venta', 'comision_total', 'saldo_comision', 'p.nombre as propiedad','c.nombre as prospecto')
        ->orderby('id_comision','DESC')
        ->where('com.estatus_pago','LIKE',"%$estatus_pago%")
        ->where('c.nombre','LIKE',"%$cliente%")
        ->where('p.nombre','LIKE',"%$propiedad%")
        ->paginate(10);

        $clientes =  DB::table('prospectos')
        ->join('estatus_crm as e','estatus','=','e.id_estatus_crm','left', false)
        ->select('id_prospecto','nombre','e.estatus_crm')
        ->where('estatus_crm','=','Pagando')
        ->get();

        $esquema_comision = DB::table('esquema_comision')
        ->select('id_esquema_comision','esquema_comision')
        ->get();

        $propiedades = DB::table('propiedad')
        ->select('id_propiedad','nombre')
        ->get();
        $estatus = ['Pendiente pago','Pagada'];
        return view('comision.index',['comisiones'=>$comisiones,'clientes'=>$clientes,'propiedades'=>$propiedades, 'esquema_comision'=>$esquema_comision,'request'=>$request,'estatus'=>$estatus,'rows_pagina'=>$rows_pagina]);
    }
    public function store(request $request)
    {
        $comision = new Comision();
        $comision->cliente_id = $request->get('nuevo_cliente');
        $comision->propiedad_id = $request->get('nuevo_propiedad');
        $comision->estatus = $request->get('nuevo_estatus');
        $comision->estatus_pago = $request->get('nuevo_estatus_pago');
        $comision->monto_operacion = floatval(str_replace(',', '', $request->get('nuevo_monto')));
        $comision->fecha_venta = $request->get('nuevo_fecha_venta');
        $esquema = $request->get('esquema_comision');

        $detalles_comisiones = DB::table('detalle_esquema_comision')
        ->select('id_detalle_esquema_comision', 'rubro','factor','tipo','usuario','persona','esquema_id')
        ->where('esquema_id','=',$esquema)
        ->get();
        $llave = uniqid();
        $comision->folio_llave = $llave;
        $comision->comision_total = 0;
        $comision->saldo_comision = 0;
        $comision->save();
        
        $comisiones = DB::table('comision')
        ->select('id_comision','folio_llave','monto_operacion')
        ->where('folio_llave' ,'=', $llave)
        ->first();
        $id = $comisiones->id_comision;
        $sumaTotal = 0;
        foreach ($detalles_comisiones as $key) {
            $comision_detalle = new ComisionDetalle;
            $comision_detalle->rubro = $key->rubro;
            $comision_detalle->tipo = $key->tipo;
            $comision_detalle->usuario_id = $key->usuario;
            $comision_detalle->persona = $key->persona;
            $comision_detalle->factor = $key->factor;
            $comision_detalle->total_venta = $comisiones->monto_operacion;
            if ($key->factor == 0) {
                $comision_total = round(($comisiones->monto_operacion* $key->factor),2);
            }else{
                $comision_total = round(($comisiones->monto_operacion*($key->factor/100)),2);
            }
            $comision_detalle->comision = $comision_total;
            $comision_detalle->saldo_comision = $comision_total;
            $sumaTotal = $sumaTotal + $comision_total;
            $comision_detalle->comision_id = $id;
            $comision_detalle->save();

        }
        $comision = Comision::findOrFail($id);
        $comision->comision_total = $sumaTotal;
        $comision->saldo_comision = $sumaTotal;
        $comision->save();

        return redirect()->route('comision');
    }
    public function show($id)
    {
        $comision = DB::table('comision as com')
        ->join('propiedad as p','propiedad_id','=','p.id_propiedad','left',false)
        ->join('prospectos as c','cliente_id','=','c.id_prospecto','left',false)
        ->select('id_comision', 'cliente_id', 'com.propiedad_id', 'com.estatus', 'com.estatus_pago', 'com.monto_operacion', 'com.fecha_venta', 'com.comision_total', 'com.saldo_comision', 'p.nombre as propiedad','c.nombre as prospecto')
        ->orderby('id_comision','ASC')
        ->where('id_comision',$id)
        ->first();

        $comisiones_detalle = DB::table('comision_detalle')
        ->select('id_comision_detalle', 'rubro','tipo', 'usuario_id', 'persona', 'factor', 'total_venta', 'comision', 'saldo_comision', 'comision_id')
        ->where('comision_id','=',$id)
        ->orderby('id_comision_detalle','ASC')
        ->paginate(5);
        return view('comision.show',['comision'=>$comision, 'comisiones_detalle'=>$comisiones_detalle]);
    }
    public function update(request $request, $id)
    {
        $comision = Comision::findOrFail($id);
        $comision->cliente_id = $request->get('cliente_id');
        $comision->propiedad_id = $request->get('propiedad_id');        
        $comision->update();
        return redirect()->route('comision',['id'=>$id]);
    }
    public function destroy($id)
    {
        $comision = Comision::find($id);
        $pagosactivo = DB::table ('pago_comision')
        ->where('comision_id',$id)
        ->where('estatus','Aplicado')
        ->select(DB::raw('count(*) as contador'))
        ->first();
        if ($pagosactivo->contador > 0) {
            return back()->with('msj','No se puede eliminar aun hay pagos activos');
        }else{
            DB::table('pago_comision')->where('comision_id',$id)->where('estatus', '=', 'Cancelado')->delete();
            DB::table('comision_detalle')->where('comision_id',$id)->delete();
            $comision->delete();
            return redirect()->route('comision');

        }
    }
    public function aprobar(request $request, $id)
    {
        $comision = Comision::findOrFail($id);
        $comision->estatus = 'Aprobada';    
        $comision->update();
        return redirect()->route('comision-show',['id'=>$id]);
    }

    public function recalcular(request $request, $id)
    {
        $comision_detalles = DB::table('comision_detalle')
        ->select('id_comision_detalle','comision','saldo_comision','comision_id')
        ->where('comision_id','=',$id)
        ->get();

        $sumaSaldo = 0;
        foreach ($comision_detalles as $key) {
            $id_detalle = $key->id_comision_detalle;
            $pagos_comision = DB::table('pago_comision')
            ->select('monto')
            ->where('estatus','=','Aplicado')
            ->where('comision_detalle_id','=',$id_detalle)
            ->get();
            $sumaMontos=0;
            foreach ($pagos_comision as $key) {
                $sumaMontos = $sumaMontos + $key->monto;
            }
            $comision_detalle = ComisionDetalle::findOrFail($id_detalle);
            $comision_detalle->saldo_comision =  $comision_detalle->comision - $sumaMontos;    
            $saldo_detalle =  $comision_detalle->comision - $sumaMontos;    
            $comision_detalle->update();
            $sumaSaldo = $sumaSaldo + $saldo_detalle;
        }

        $comision = Comision::findOrFail($id);
        if ($sumaSaldo >0) {
            $comision->estatus_pago = 'Pendiente pago';
        }else{
            $comision->estatus_pago = 'Pagada';
        }
        $comision->saldo_comision = $sumaSaldo;  
        $comision->update();
        return redirect()->route('comision-show',['id'=>$id]);
    }
}
