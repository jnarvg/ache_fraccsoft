<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\DetalleCotizacionPropiedad;
use App\DetalleCotizacionPropiedadRubro;
use App\Cotizacion;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class DetalleCotizacionPropiedadController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        return view('detalle_cotizacion_propiedad.index');
    }
    public function store(request $request)
    {
        $id_cotizacion = $request->get('cotizacion_id_new');
        $id_propiedad = $request->get('propiedad_id_new');
        $propiedad = DB::table('propiedad as p')
        ->select('id_propiedad','p.nombre','precio','uso_propiedad_id','proyecto_id')
        ->where('id_propiedad','=', $id_propiedad)
        ->first();

        $precio = $propiedad->precio;
        $id_proyecto = $propiedad->proyecto_id;
        $uso_propiedad_id = $propiedad->uso_propiedad_id;

        $esquema_pago_find = DB::table('esquema_pago')->select('id_esquema_pago','esquema_pago','porcentaje_descuento','incluir')
        ->where('proyecto_id', $id_proyecto)
        ->where('uso_propiedad_id', $uso_propiedad_id)
        ->where('tomar_encuenta_uso', 1)
        ->get();

        if (count($esquema_pago_find) <= 0) {
            $esquema_pago_find = DB::table('esquema_pago')->select('id_esquema_pago','esquema_pago','porcentaje_descuento','incluir')
            ->where('proyecto_id', $id_proyecto)
            ->where('tomar_encuenta_uso', 0)
            ->get();
        }
        foreach ($esquema_pago_find as $d) {
            $porcentaje_descuento = $d->porcentaje_descuento;
            $detalle = new DetalleCotizacionPropiedad;
            $detalle->esquema_pago = $d->esquema_pago;
            $detalle->propiedad_id = $id_propiedad;
            $detalle->precio = $precio;
            $detalle->porcentaje_descuento = $d->porcentaje_descuento;
            if ($d->porcentaje_descuento != null and $d->porcentaje_descuento != '') {
                $monto_descuento = $precio * ($d->porcentaje_descuento/100);
            }else{
                $monto_descuento = 0;
            }
            $precio_final = $precio - $monto_descuento;
            $folio_key = uniqid();
            $detalle->monto_descuento = $monto_descuento;
            $detalle->precio_final  = $precio_final;
            $detalle->incluir = $d->incluir;
            $detalle->cotizacion_id = $id_cotizacion;
            $detalle->proyecto_id = $id_proyecto;
            $detalle->folio_key = $folio_key;

            $detalle->save();

            ///AGREAMOS AHORA EL DETALLED E ESQUEMA DE PAGO

            $ultimo_detalle_propiedad = DB::table('detalle_cotizacion_propiedad')->select('id_detalle_cotizacion_propiedad')
            ->where('folio_key', $folio_key)
            ->first();
            $id_detalle_cotizacion_propiedad = $ultimo_detalle_propiedad->id_detalle_cotizacion_propiedad;

            $esquema_pago_detalle = DB::table('detalle_esquema_pago')
            ->where('esquema_pago_id', $d->id_esquema_pago)
            ->get();
            foreach ($esquema_pago_detalle as $e) {
                $detalle_rubro = new DetalleCotizacionPropiedadRubro;

                $detalle_rubro->alias = $e->alias;
                $detalle_rubro->fecha = date('Y-m-d');
                $detalle_rubro->tipo = $e->tipo;
                $detalle_rubro->tipo_calculo = $e->tipo_calculo;
                if ($e->tipo_calculo == 'Porcentaje') {
                    if ($e->porcentaje != null and $e->porcentaje != '') {
                        $monto = $precio_final * ($e->porcentaje/100);
                    }else{
                        $monto = 0;
                    }
                    $detalle_rubro->porcentaje = $e->porcentaje;
                    $detalle_rubro->monto = $monto;
                }
                if ($e->tipo_calculo == 'Monto') {
                    if ($e->monto != null and $e->monto != '') {
                        $porcentaje = ($monto  * 100 ) / $precio_final;
                    }else{
                        $porcentaje = 0;
                    }
                    $detalle_rubro->porcentaje = $porcentaje;
                    $detalle_rubro->monto = $e->monto;
                }
                $detalle_rubro->mensualidades = $e->mensualidades;
                $detalle_rubro->excluir_calculo = 0;
                $detalle_rubro->excluir_descuento = 0;
                $detalle_rubro->detalle_cotizacion_propiedad_id = $id_detalle_cotizacion_propiedad;
                $detalle_rubro->cotizacion_id = $id_cotizacion;

                $detalle_rubro->save();
            }

        }

        $listbtn = DB::table('detalle_cotizacion_propiedad as d')
        ->join('propiedad as p','d.propiedad_id','=','p.id_propiedad','left',false)
        ->select('p.nombre as nombre_propiedad')->where('cotizacion_id', $id_cotizacion)
        ->groupBy('propiedad_id')
        ->get();
        $i = 0;
        $nombres_propiedades ='';
        foreach ($listbtn as $value) {
            if ($i == 0) {
                $nombres_propiedades = $nombres_propiedades .$value->nombre_propiedad;
            }else{
                $nombres_propiedades = $nombres_propiedades .', '.$value->nombre_propiedad;
            }
            $i = $i +1;
        }
        $cotizacion = Cotizacion::find($id_cotizacion);
        $cotizacion->propiedad = $nombres_propiedades;
        $cotizacion->update();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function show($id)
    {
        return view('comision.show',['comision'=>$comision, 'comisiones_detalle'=>$comisiones_detalle]);
    }
    public function update(request $request, $id) ///CALCULAR
    {
        // $id es el detalle cotizacion propiedad
        /////VALIDACIONES
            ///Tenemos que ir por el formulario no lla tabla en si proque aun no esta guardado
            ///Guardamos los datos del padre
            $detallePropiedad = DetalleCotizacionPropiedad::find($id);
            $detallePropiedad->esquema_pago = $request->get('esquema_pago');
            $precio = floatval(str_replace(',', '', $request->get('precio')));
            $detallePropiedad->precio = $precio;
            $detallePropiedad->porcentaje_descuento = $request->get('porcentaje_descuento');
            $detallePropiedad->monto_descuento = $precio * ($request->get('porcentaje_descuento') / 100);
            $precio_final = $precio - ( $precio * ($request->get('porcentaje_descuento') / 100) );
            $detallePropiedad->precio_final  = $precio_final;
            if ( $request->get('incluir') ) {
                $detallePropiedad->incluir = 1;
            }else{
                $detallePropiedad->incluir = 0;        
            }
            $detallePropiedad->update();

            ////Actualizmaos los rubros
            $rubros_array = $request->get('id_rubro_');
            $i = 0;
            foreach ($rubros_array as $a) {

                //encontrar el modelo DetallePorpiedadRubro
                $detalleRubro = DetalleCotizacionPropiedadRubro::find($a);
                $detalleRubro->alias = $request->alias_[$i];
                $detalleRubro->fecha = $request->fecha_[$i];
                $detalleRubro->tipo = $request->tipo_[$i];
                $detalleRubro->tipo_calculo = $request->tipo_calculo_[$i];
                $detalleRubro->mensualidades = $request->mensualidades_[$i];
                $detalleRubro->abono_aplica_a_id = $request->abono_aplica_a_id_[$i];
                if ( $request->excluir_descuento[$i]) {
                    $detalleRubro->excluir_descuento = 1;
                }else{
                    $detalleRubro->excluir_descuento = 0;        
                }

                $monto_tabla = floatval(str_replace(',', '', $request->monto_[$i] ));
                if ($request->tipo_[$i] == 'No aplica' or $request->tipo_[$i] == 'Previo') {
                    if ( $request->tipo_calculo_[$i] == 'Porcentaje') {
                        $detalleRubro->porcentaje = $request->porcentaje_[$i];
                        $detalleRubro->monto = $precio_final * ($request->porcentaje_[$i] /100 );
                    }elseif( $request->tipo_calculo_[$i] == 'Monto' ){
                        $detalleRubro->porcentaje = ( $monto_tabla * 100) / $precio_final;
                        $detalleRubro->monto = $monto_tabla;
                    }elseif ( $request->tipo_calculo_[$i] == 'Autocompletar' ) {
                        $notification = array(
                            'msj' => 'HEY!! Los movimientos tipo PREVIO no se pueden Autocompletar',
                            'alert-type' => 'error'
                        );
                    }
                }
                if ($request->tipo_[$i] == 'Inicio') {
                    if ( $request->tipo_calculo_[$i] == 'Porcentaje') {
                        $detalleRubro->porcentaje = $request->porcentaje_[$i];
                        $detalleRubro->monto = $precio_final * ($request->porcentaje_[$i] /100 );

                    }elseif( $request->tipo_calculo_[$i] == 'Monto' ){
                        $detalleRubro->porcentaje = ( $monto_tabla * 100) / $precio_final;
                        $detalleRubro->monto = $monto_tabla;
                    }elseif ( $request->tipo_calculo_[$i] == 'Autocompletar' ) {
                        $notification = array(
                            'msj' => 'HEY!! Los movimientos tipo PREVIO no se pueden Autocompletar',
                            'alert-type' => 'error'
                        );
                    }
                }
                if ($request->tipo_[$i] == 'Mensualidad') {
                    if ( $request->tipo_calculo_[$i] == 'Porcentaje') {
                        $detalleRubro->porcentaje = $request->porcentaje_[$i];
                        $detalleRubro->monto = $precio_final * ($request->porcentaje_[$i] /100 );

                    }elseif( $request->tipo_calculo_[$i] == 'Monto' ){
                        $detalleRubro->porcentaje = ( $monto_tabla * 100) / $precio_final;
                        $detalleRubro->monto = $monto_tabla;
                    }elseif ( $request->tipo_calculo_[$i] == 'Autocompletar' ) {
                        $notification = array(
                            'msj' => 'HEY!! Los movimientos tipo PREVIO no se pueden Autocompletar',
                            'alert-type' => 'error'
                        );
                    }
                }
                if ($request->tipo_[$i] == 'Fin') {
                    if ( $request->tipo_calculo_[$i] == 'Porcentaje') {
                        $detalleRubro->porcentaje = $request->porcentaje_[$i];
                        $detalleRubro->monto = $precio_final * ($request->porcentaje_[$i] /100 );

                    }elseif( $request->tipo_calculo_[$i] == 'Monto' ){
                        $detalleRubro->porcentaje = ( $monto_tabla * 100) / $precio_final;
                        $detalleRubro->monto = $monto_tabla;
                    }elseif ( $request->tipo_calculo_[$i] == 'Autocompletar' ) {
                        $notification = array(
                            'msj' => 'HEY!! Los movimientos tipo PREVIO no se pueden Autocompletar',
                            'alert-type' => 'error'
                        );
                    }
                }
                if ($request->tipo_[$i] == 'Abono a capital') {
                    if ( $request->tipo_calculo_[$i] == 'Porcentaje') {
                        $detalleRubro->porcentaje = $request->porcentaje_[$i];
                        $detalleRubro->monto = $precio_final * ($request->porcentaje_[$i] /100 );

                    }elseif( $request->tipo_calculo_[$i] == 'Monto' ){
                        $detalleRubro->porcentaje = ( $monto_tabla * 100) / $precio_final;
                        $detalleRubro->monto = $monto_tabla;
                    }elseif ( $request->tipo_calculo_[$i] == 'Autocompletar' ) {
                        $notification = array(
                            'msj' => 'HEY!! Los movimientos tipo ABONO A CAPITAL no se pueden Autocompletar',
                            'alert-type' => 'error'
                        );
                    }

                    ///Afectar el ovmineto seleccionado y validar que sea mayor o  igual al abono
                    $rubroAfectado = DetalleCotizacionPropiedadRubro::find($request->abono_aplica_a_id_[$i]);
                    if ( $monto_tabla > $detalleRubro->monto ) {
                        $notification = array(
                            'msj' => 'HEY!! El abono que deseas hacer supera el monto del rubro seleccionado',
                            'alert-type' => 'error'
                        );
                    }

                }
                $detalleRubro->save();
                //// Para ir al siguiente renglon
                $i = $i + 1;
            }

            $detalles = DB::table('detalle_cotizacion_propiedad_rubro')
            ->select(DB::raw('SUM(monto) as monto_total, SUM(porcentaje) as porcentaje_total'))
            ->where('detalle_cotizacion_propiedad_id', $id)
            ->first();

            if ( $detalles->porcentaje_total > 100 ) {
                $notification = array(
                    'msj' => 'El porcentaje total es MAYOR al 100%',
                    'alert-type' => 'error'
                );
                
            }
            if ( $detalles->porcentaje_total < 100 ) {
                $notification = array(
                    'msj' => 'El porcentaje total es MENOR al 100%',
                    'alert-type' => 'error'
                );
            }
            elseif( round($detalles->monto_total, 2) > round($precio_final, 2) ){
                $notification = array(
                    'msj' => 'El monto total de los detalles supera al precio final',
                    'alert-type' => 'error'
                );
            }else{
                $notification = array(
                    'msj' => 'Se ha calculado correctamente',
                    'alert-type' => 'success'
                );
            }
        return back()->with($notification);
    }
    public function destroy(request $request, $id)
    {
        $propiedad_id = $request->get('propiedad_id_delete');


        //Recorreos los detalle spropiedad ya que ca auno tiene la propiedad
        $detalles_propiedad = DB::table('detalle_cotizacion_propiedad')
        ->select('id_detalle_cotizacion_propiedad')
        ->where('propiedad_id', $propiedad_id)
        ->where('cotizacion_id', $id)
        ->get();
        foreach ($detalles_propiedad as $key) {
            ////Eliminamos los rubros que pertencana a cad auno de los esquemas  detalles propeidad
            DB::table('detalle_cotizacion_propiedad_rubro')
            ->where('cotizacion_id', $id)
            ->where('detalle_cotizacion_propiedad_id', $key->id_detalle_cotizacion_propiedad)
            ->delete();
        }

        // ///Eliminamos el detalle propiedad
        DB::table('detalle_cotizacion_propiedad')
        ->where('propiedad_id', $propiedad_id)
        ->where('cotizacion_id', $id)
        ->delete();

        $listbtn = DB::table('detalle_cotizacion_propiedad as d')
        ->join('propiedad as p','d.propiedad_id','=','p.id_propiedad','left',false)
        ->select('p.nombre as nombre_propiedad')->where('cotizacion_id', $id)
        ->groupBy('propiedad_id')
        ->get();
        $i = 0;
        $nombres_propiedades ='';
        foreach ($listbtn as $value) {
            if ($i == 0) {
                $nombres_propiedades = $nombres_propiedades .$value->nombre_propiedad;
            }else{
                $nombres_propiedades = $nombres_propiedades .', '.$value->nombre_propiedad;
            }
            $i = $i +1;
        }
        $cotizacion = Cotizacion::find($id);
        $cotizacion->propiedad = $nombres_propiedades;
        $cotizacion->update();
        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
