<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\DetalleCotizacionPropiedadRubro;
use App\DetalleCotizacionPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class DetalleCotizacionPropiedadRubroController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        
        return view('detalle_cotizacion_propiedad_rubro.index');
    }
    public function store($padre, request $request)
    {
        $detalle = new DetalleCotizacionPropiedadRubro();
        $detalle->alias = $request->get('alias_new_'.$padre);
        $detalle->fecha = $request->get('fecha_new_'.$padre);
        $detalle->tipo = $request->get('tipo_new_'.$padre);
        $detalle->tipo_calculo = $request->get('tipo_calculo_new_'.$padre);
        $new_monto = floatval(str_replace(',', '', $request->get('monto_new_'.$padre)));

        $detallepadre = DetalleCotizacionPropiedad::find($padre);
        if ( $request->get('monto_new_'.$padre) ) {
            $detalle->porcentaje = ($new_monto * 100) / $detallepadre->precio_final;
            $detalle->monto = $new_monto;
        }elseif( $request->get('porcentaje_new_'.$padre) ){
            $detalle->monto = $detallepadre->precio_final * ( $request->get('porcentaje_new_'.$padre)/100 );
            $detalle->porcentaje = $request->get('porcentaje_new_'.$padre);
        }else{
            $detalle->monto = $new_monto;
            $detalle->porcentaje = $request->get('porcentaje_new_'.$padre);
        }
        $detalle->mensualidades = $request->get('mensualidades_new_'.$padre);
        if ($request->get('excluir_calculo_new_'.$padre)) {
             $detalle->excluir_calculo = 1;
        }else{
             $detalle->excluir_calculo = 0;        
        }

        if ($request->get('excluir_descuento_new_'.$padre)) {
             $detalle->excluir_descuento = 1;
        }else{
             $detalle->excluir_descuento = 0;        
        }
        $detalle->abono_aplica_a_id = $request->get('abono_aplica_a_id_new_'.$padre);
        if ($request->get('abono_aplica_a_id_new_'.$padre)) {
            $detalleAfectado = DetalleCotizacionPropiedadRubro::find($request->get('abono_aplica_a_id_new_'.$padre));
            $detalleAfectado->monto = $detalleAfectado->monto - $detalle->monto;
            $detalleAfectado->porcentaje = $detalleAfectado->porcentaje - $detalle->porcentaje;
            $detalleAfectado->save();
        }
        $detalle->detalle_cotizacion_propiedad_id = $padre;
        $detalle->cotizacion_id = $request->get('cotizacion_id_new_'.$padre);
        $detalle->save();

        $detalles_rubro = DB::table('detalle_cotizacion_propiedad_rubro as d')
        ->join('detalle_cotizacion_propiedad as p', 'd.detalle_cotizacion_propiedad_id','=','p.id_detalle_cotizacion_propiedad')
        ->select('d.id_detalle_cotizacion_propiedad_rubro','d.alias','d.fecha','d.tipo','d.tipo_calculo','d.monto','d.porcentaje','d.mensualidades','d.excluir_calculo','d.excluir_descuento','d.abono_aplica_a_id','d.detalle_cotizacion_propiedad_id','d.cotizacion_id')
        ->where('detalle_cotizacion_propiedad_id', $padre)
        ->get();
        $suma_porcentaje = 0;
        foreach ($detalles_rubro as $s) {
            $suma_porcentaje = $suma_porcentaje + $s->porcentaje;
        }
        if ($suma_porcentaje > 100 ) {
            $notification = array(
                'msj' => 'El porcentaje excede al 100 verifica tus montos',
                'alert-type' => 'warning'
            );
        }
        elseif ($suma_porcentaje < 100 ) {
            $notification = array(
                'msj' => 'El porcentaje es menor a 100 verifica tus montos',
                'alert-type' => 'warning'
            );
        }
        else{
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
        }
        return back()->with($notification);
    }
    public function show($id)
    {
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
    public function destroy(request $request, $id)
    {
        $id_rubro = $request->get('rubro_eliminar'.$id);
        $detalle = DetalleCotizacionPropiedadRubro::find($id_rubro);
        $detalle->delete();
        $notification = array(
            'msj' => 'Listo.',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
