<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\ComisionDetalle;
use App\PagoComision;
use App\Comision;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class ComisionDetalleController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }

    public function store(request $request, $id)
    {
        $nuevo_total_venta = floatval(str_replace(',', '', $request->get('nuevo_total_venta')));

        $comision_detalle = new ComisionDetalle();
        $comision_detalle->rubro = $request->get('nuevo_rubro');
        $comision_detalle->tipo = $request->get('nuevo_tipo');
        $comision_detalle->factor = $request->get('nuevo_factor');
        $comision_detalle->usuario_id = $request->get('nuevo_usuario');
        $comision_detalle->persona = $request->get('nuevo_persona');
        $comision_detalle->total_venta = $nuevo_total_venta;
        if ($request->get('nuevo_factor') > 0) {
            $comision_detalle->comision = round($nuevo_total_venta*($request->get('nuevo_factor')/100),2);
            $comision_detalle->saldo_comision = round($nuevo_total_venta*($request->get('nuevo_factor')/100),2);
        }else{
            $comision_detalle->comision = round($nuevo_total_venta*($request->get('nuevo_factor')),2);
            $comision_detalle->saldo_comision = round($nuevo_total_venta*($request->get('nuevo_factor')),2);
        }
        $comision_detalle->comision_id = $id;
        $comision_detalle->save();

        return redirect()->route('comision-show',['id'=>$id]);
    }
    public function show($id)
    {   

        $comision_detalle = DB::table('comision_detalle')
        ->join('users as u','usuario_id','=','u.id','left',false)
        ->select('id_comision_detalle', 'rubro','tipo','usuario_id','persona','factor','total_venta','comision','saldo_comision','comision_id','u.name as nombre_usuario')
        ->where('id_comision_detalle','=',$id)
        ->first();

        $pagos_comision = DB::table('pago_comision')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago_comision','monto','fecha_pago','monto','forma_pago_id','comision_detalle_id','comision_id','descripcion','estatus','f.forma_pago')
        ->where('comision_detalle_id','=',$id)
        ->paginate(10);

        $formas_pago = DB::table('forma_pago')
        ->select('id_forma_pago','forma_pago')
        ->get();
        $usuarios = DB::table('users')
        ->select('id', 'name')
        ->where('estatus','=','Activo')
        ->get();
        return view('comision.comision_detalle.show',['comision_detalle'=>$comision_detalle,'pagos_comision'=>$pagos_comision,'formas_pago'=>$formas_pago,'usuarios'=>$usuarios]);
    }
    public function update(request $request, $id)
    {
        $total_venta = floatval(str_replace(',', '', $request->get('total_venta')));
        $comision_detalle = ComisionDetalle::findOrFail($id);
        $comision_detalle->rubro = $request->get('rubro');
        $comision_detalle->tipo = $request->get('tipo');
        $comision_detalle->factor = $request->get('factor');
        $comision_detalle->usuario_id = $request->get('usuario');
        $comision_detalle->persona = $request->get('persona');
        $comision_detalle->total_venta = $total_venta;
        if ($request->get('factor') > 0) {
            $comision_detalle->comision = round($total_venta*($request->get('factor')/100),2);
            $comision_detalle->saldo_comision = round($total_venta*($request->get('factor')/100),2);
        }else{
            $comision_detalle->comision = round($total_venta*($request->get('factor')),2);
            $comision_detalle->saldo_comision = round($total_venta*($request->get('factor')),2);
        }
        $id_padre = $comision_detalle->comision_id;
        $comision_detalle->update();

        return redirect()->route('comision-show',['id'=>$id_padre]);
    }
    public function destroy($id)
    {
        $comision_detalle = ComisionDetalle::find($id);
        $id_padre = $comision_detalle->comision_id;
        $comision_detalle->delete();
        return redirect()->route('comision-show',['id'=>$id_padre]);
    }

    public function pagar(request $request, $id)
    {
        $monto_pagar = floatval(str_replace(',', '', $request->get('nuevo_monto')));
        $comision_detalle = ComisionDetalle::find($id);
        if ($monto_pagar > $comision_detalle->saldo_comision) {
            return redirect()->route('comision_detalle-show',['id'=>$id])->with('msj','El monto debe ser menor al saldo de la comision.');
        }else{

            if ($monto_pagar < $comision_detalle->comision ) {
                $descripcion = 'Pago parcial de comision por un monto de '.$monto_pagar;
            }else{
                $descripcion = 'Pago completo de comision por un monto de '.$monto_pagar;
            }
            $id_comision_padre = $comision_detalle->comision_id;
            $pago_comision = new PagoComision();
            $pago_comision->fecha_pago = $request->get('nuevo_fecha_pago');
            $pago_comision->monto = $monto_pagar;
            $pago_comision->forma_pago_id = $request->get('nuevo_forma_pago_id');
            $pago_comision->descripcion = $descripcion;
            $pago_comision->comision_id = $id_comision_padre;
            $pago_comision->comision_detalle_id = $id;
            $pago_comision->estatus = 'Aplicado';
            $pago_comision->save();

            $pagos_comision = DB::table('pago_comision')
            ->select('monto')
            ->where('estatus','=','Aplicado')
            ->where('comision_detalle_id','=',$id)
            ->get();
            $sumaMontos=0;
            foreach ($pagos_comision as $key) {
                $sumaMontos = $sumaMontos + $key->monto;
            }
            $comision_detalle->saldo_comision = $comision_detalle->comision - $sumaMontos;
            $comision_detalle->update();

            $comision_detalles = DB::table('comision_detalle')
            ->select('id_comision_detalle','comision','saldo_comision','comision_id')
            ->where('comision_id','=',$id_comision_padre)
            ->get();

            $sumaSaldo = 0;
            foreach ($comision_detalles as $key) {
                $sumaSaldo = $sumaSaldo + $key->saldo_comision;
            }

            $comision_padre = Comision::findOrFail($id_comision_padre);
            if ($sumaSaldo >0) {
                $comision_padre->estatus_pago = 'Pendiente pago';
            }else{
                $comision_padre->estatus_pago = 'Pagada';
            }
            $comision_padre->saldo_comision = $sumaSaldo;
            $comision_padre->update();
            return redirect()->route('comision_detalle-show',['id'=>$id]);
        }
    }
}
