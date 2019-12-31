<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\User;
use App\DetalleCotizacion;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
use PDF;

class DetalleCotizacionController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function store(request $request)
    {
        $propiedad = DB::table('propiedad as prop')
        ->select('id_propiedad','nombre','precio')
        ->where('id_propiedad',  $request->get('propiedad_id_new'))->first();
        $precio = $propiedad->precio;
        $cotizacion = DB::table('cotizacion as c')
        ->join('users as u','c.asesor_id','=','u.id','left',false)
        ->select('c.createdDate','c.id_cotizacion', 'c.fecha_cotizacion', 'c.proyecto','c.proyecto_id', 'c.propiedad', 'c.plazos', 'c.cliente', 'c.correo', 'c.telefono', 'c.asesor_id', 'c.moneda_id', 'c.estatus', 'c.porcentaje_pago_inicial_a', 'c.porcentaje_pago_inicial_b', 'c.porcentaje_pago_inicial_c', 'c.porcentaje_pago_inicial_d', 'c.porcentaje_contraentrega_a', 'c.porcentaje_contraentrega_b', 'c.porcentaje_contraentrega_c', 'c.porcentaje_contraentrega_d', 'c.porcentaje_descuento_a', 'c.porcentaje_descuento_b', 'c.porcentaje_descuento_c', 'c.porcentaje_descuento_d', 'c.porcentaje_mensualidad_a', 'c.porcentaje_mensualidad_b', 'c.porcentaje_mensualidad_c', 'c.porcentaje_mensualidad_d', 'c.fecha_cierre', 'c.prospecto_id', 'c.monto_inicial_d', 'u.name as asesor_nombre')
        ->where('id_cotizacion',$request->get('cotizacion_id_new'))->first();
        $porcentaje_enganche_D = round( ($cotizacion->monto_inicial_d / $precio) * 100, 3);

        $detalle = new DetalleCotizacion;
        $detalle->propiedad_id = $request->get('propiedad_id_new');
        $detalle->cotizacion_id = $request->get('cotizacion_id_new');
        $detalle->precio_propiedad = $propiedad->precio;
        $detalle->plazos = $cotizacion->plazos;
        $detalle->inicial_a = $cotizacion->porcentaje_pago_inicial_a;
        $detalle->contraentrega_a = $cotizacion->porcentaje_contraentrega_a;
        $detalle->mensualidades_a = $cotizacion->porcentaje_mensualidad_a;
        $detalle->descuento_a = $cotizacion->porcentaje_descuento_a;
        $detalle->inicial_b = $cotizacion->porcentaje_pago_inicial_b;
        $detalle->contraentrega_b = $cotizacion->porcentaje_contraentrega_b;
        $detalle->mensualidades_b = $cotizacion->porcentaje_mensualidad_b;
        $detalle->descuento_b = $cotizacion->porcentaje_descuento_b;
        $detalle->inicial_c = $cotizacion->porcentaje_pago_inicial_c;
        $detalle->contraentrega_c = $cotizacion->porcentaje_contraentrega_c;
        $detalle->mensualidades_c = $cotizacion->porcentaje_mensualidad_c;
        $detalle->descuento_c = $cotizacion->porcentaje_descuento_c;
        $detalle->monto_inicial_d = $cotizacion->monto_inicial_d;
        $detalle->inicial_d = $porcentaje_enganche_D;
        $detalle->contraentrega_d = $cotizacion->porcentaje_contraentrega_d;
        $detalle->mensualidades_d = $cotizacion->porcentaje_mensualidad_d;
        $detalle->descuento_d = $cotizacion->porcentaje_descuento_d;
        $detalle->save();

        return back();
    }
    public function update($id, request $request)
    {
        $detalle = DetalleCotizacion::findOrFail($id);
        $detalle->propiedad_id = $request->get('propiedad_id');
        $detalle->precio_propiedad = $request->get('precio_propiedad');
        $detalle->plazos = $request->get('plazos');
        $detalle->inicial_a = $request->get('inicial_a');
        $detalle->contraentrega_a = $request->get('contraentrega_a');
        $detalle->mensualidades_a = $request->get('mensualidades_a');
        $detalle->descuento_a = $request->get('descuento_a');
        $detalle->inicial_b = $request->get('inicial_b');
        $detalle->contraentrega_b = $request->get('contraentrega_b');
        $detalle->mensualidades_b = $request->get('mensualidades_b');
        $detalle->descuento_b = $request->get('descuento_b');
        $detalle->inicial_c = $request->get('inicial_c');
        $detalle->contraentrega_c = $request->get('contraentrega_c');
        $detalle->mensualidades_c = $request->get('mensualidades_c');
        $detalle->descuento_c = $request->get('descuento_c');
        $detalle->monto_inicial_d = $request->get('monto_inicial_d');
        $detalle->inicial_d = $request->get('inicial_d');
        $detalle->contraentrega_d = $request->get('contraentrega_d');
        $detalle->mensualidades_d = $request->get('mensualidades_d');
        $detalle->descuento_d = $request->get('descuento_d');
        $detalle->update();

        return back();
    }

    public function destroy($id, request $request)
    {

        $id_detalle = $request->get('propiedad_id_delete');
        $detalle = DetalleCotizacion::findOrFail($id_detalle);
        $detalle->delete();

        return back();
    }

}
