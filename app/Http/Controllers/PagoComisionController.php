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


class PagoComisionController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $pagos = DB::table('pago_comision')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago_comision','monto','fecha_pago','monto','forma_pago_id','comision_detalle_id','comision_id','descripcion','estatus','f.forma_pago')
        ->orderby('fecha_pago','DESC')
        ->paginate(10);

        return view('comision.pago_comision.index',['pagos'=>$pagos]);
    }
    public function store(request $request)
    {
        return redirect()->route('pago_comision');
    }
    public function show($id)
    {
        $pago_comision = DB::table('pago_comision')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago_comision','monto','fecha_pago','monto','forma_pago_id','comision_detalle_id','comision_id','descripcion','estatus','f.forma_pago')
        ->where('id_pago_comision','=',$id)
        ->first();

        return view('comision.pago_comision.show',['pago_comision'=>$pago_comision]);
    }
    public function destroy($id)
    {
        $pago_comision = PagoComision::find($id);
        if ($pago_comision->estatus == 'Cancelado') {
            $pago_comision->delete();
        }else{
            $pago_comision->update();
        }
        return redirect()->route('pago_comision');
    }

    public function cancelar($id)
    {
        $pago_comision = PagoComision::find($id);
        $pago_comision->estatus = 'Cancelado';
        $id_comision_padre= $pago_comision->comision_id;
        $id_comision_detalle= $pago_comision->comision_detalle_id;
        $pago_comision->update();

        $comision_detalles = DB::table('comision_detalle')
        ->select('id_comision_detalle','comision','saldo_comision','comision_id')
        ->where('comision_id','=',$id_comision_padre)
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

        $comision = Comision::findOrFail($id_comision_padre);
        if ($sumaSaldo >0) {
            $comision->estatus_pago = 'Pendiente pago';
        }else{
            $comision->estatus_pago = 'Pagada';
        }    
        $comision->saldo_comision = $sumaSaldo;
        $comision->update();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

}
