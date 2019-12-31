<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\PlazosPago;
use App\Prospecto;
use App\Pagos;
use App\Propiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Exports\PlazosPagoExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Http\Middleware\Authenticate;
use Carbon\Carbon;


class PlazosPagoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $rows_pagina = array('10','25','50','100');

        $rows_page = $request->get('rows_per_page');
        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($request);
        $plazos_pago = DB::table('plazos_pago as pp')
        ->join('prospectos','prospecto_id','=','id_prospecto','left',false)
        ->select('id_plazo_pago','prospecto_id', 'fecha','pp.estatus','num_plazo','pp.total','pp.saldo','pp.pagado','monto_mora','nombre','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','pp.tipo','pp.descripcion')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $monedas = DB::table('moneda')
        ->get();
        $estatus = DB::table('plazos_pago as pp')
            ->select('pp.estatus')
            ->groupBy('pp.estatus')
            ->whereRaw($filtro)
            ->get();
        $tipos = DB::table('plazos_pago as pp')
            ->select('pp.tipo')
            ->groupBy('pp.tipo')
            ->whereRaw($filtro)
            ->get();

        return view('plazos_pago.index', compact('plazos_pago','prospectos','request','monedas','rows_pagina','estatus','tipos'));
    }
    public function store(request $request, $procedencia)
    {
        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','num_plazo')
        ->where('prospecto_id','=', $request->get('nuevo_prospecto_mdl'))
        ->where('num_plazo','=',$request->get('nuevo_num'))
        ->get();
        $result =0;
        foreach ($plazos_pago as $key) {
            $result ++;
        }
        if($result == 0){
            if ($request->get('nuevo_interes') > 0) {
                $porcentaje_interes = $request->get('nuevo_interes') / 100;
            }else{
                $porcentaje_interes = 0;
            }
            $plazo_pago = new PlazosPago();
            $nuevo_total = floatval(str_replace(',', '', $request->get('nuevo_total')));
            $interes = round( $nuevo_total * $porcentaje_interes, 2);
            $total = round( $nuevo_total + $interes, 2);
            $plazo_pago->estatus = $request->get('nuevo_estatus');
            $plazo_pago->total = $total;
            $plazo_pago->saldo = $total;
            $plazo_pago->capital = $nuevo_total;
            $plazo_pago->capital_inicial = $nuevo_total;
            $plazo_pago->interes = $interes;
            $plazo_pago->interes_acumulado = $interes;
            $plazo_pago->fecha = $request->get('nuevo_fecha');
            $plazo_pago->descripcion = $request->get('descripcion');
            $plazo_pago->notas = $request->get('nuevo_notas');
            $plazo_pago->num_plazo = $request->get('nuevo_num');
            $plazo_pago->moneda_id = $request->get('nuevo_moneda');
            $plazo_pago->prospecto_id = $request->get('nuevo_prospecto_mdl');
            $plazo_pago->monto_mora = 0;
            $plazo_pago->pagado = 0;
            $plazo_pago->save();
            if ($procedencia == 'Menu') {
                $notification = array(
                    'msj' => 'Listo!!',
                    'alert-type' => 'success'
                );
                return redirect()->route('plazos_pago')->with($notification);
                
            }else{
                $notification = array(
                    'msj' => 'Listo!!',
                    'alert-type' => 'success'
                );
                return back()->with($notification);
            }
        }else{
            if ($procedencia == 'Menu') {
                $notification = array(
                    'msj' => 'Ya hay un plazo con ese numero',
                    'alert-type' => 'error'
                );
                return redirect()->route('plazos_pago')->with($notification);
            }else{
                $notification = array(
                    'msj' => 'Ya hay un plazo con ese numero',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
        }
        
    }
    public function show($id, $procedencia)
    {
        $plazo = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado','monto_mora','dias_retraso','notas','interes','interes_acumulado','capital_acumulado','total_acumulado','deuda','amortizacion','capital','capital_inicial','moneda_id','tipo','descripcion')
        ->where('id_plazo_pago','=',$id)
        ->first();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $monedas = DB::table('moneda')
        ->get();

        $formas_pago = DB::table('forma_pago')
        ->select('id_forma_pago','forma_pago')
        ->get();
        $pagos_plazo = DB::table('pagos')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago','monto','fecha','estatus','forma_pago_id','f.forma_pago','plazo_pago_id','mora','dias_retraso')
        ->where('plazo_pago_id','=',$id)
        ->get();

        return view('plazos_pago.show',compact('plazo','prospectos','procedencia','pagos_plazo','formas_pago','monedas'));
    }
    public function update(request $request, $id, $procedencia)
    {
        $plazo_pago = PlazosPago::findOrFail($id);
        $plazo_pago->estatus = $request->get('estatus');
        $plazo_pago->fecha = $request->get('fecha');
        $plazo_pago->notas = $request->get('notas');
        $plazo_pago->descripcion = $request->get('descripcion');
        $plazo_pago->dias_retraso = $request->get('dias_retraso');
        $plazo_pago->monto_mora = $request->get('monto_mora');
        $plazo_pago->num_plazo = $request->get('num_plazo');
        $plazo_pago->prospecto_id = $request->get('prospecto');
        $plazo_pago->moneda_id = $request->get('moneda');
        /*fin numericos*/
        $plazo_pago->total_acumulado = floatval(str_replace(',', '', $request->get('total_acumulado')));
        $plazo_pago->interes = floatval(str_replace(',', '', $request->get('interes')));
        $plazo_pago->interes_acumulado = floatval(str_replace(',', '', $request->get('interes_acumulado')));
        $plazo_pago->capital_acumulado = floatval(str_replace(',', '', $request->get('capital_acumulado')));
        $plazo_pago->capital = floatval(str_replace(',', '', $request->get('capital')));
        $plazo_pago->capital_inicial = floatval(str_replace(',', '', $request->get('capital_inicial')));
        $plazo_pago->monto_mora = floatval(str_replace(',', '', $request->get('monto_mora')));
        /*fn numericos*/
        $plazo_pago->update();
        if ($procedencia == 'Menu') {
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
            return redirect()->route('plazos_pago')->with($notification);
            
        }else{
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        }
    }
    public function destroy($id)
    {   
        $plazo_pago = PlazosPago::findOrFail($id);
        $id_padre = $plazo_pago->prospecto_id;
        $pagos = DB::table('pagos')
        ->select('id_pago')
        ->where('plazo_pago_id','=', $id)
        ->where('estatus','=', 'Aplicado')
        ->get();
        $result =0;
        foreach ($pagos as $key) {
            $result ++;
        }
        if($result == 0){
            $plazo_pago->delete();
            return back();
        }else{
            $plazo_pago->update();
            $notification = array(
                'msj' => 'No se puede eliminar, aun hay pagos activos',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }   
    }

    public function pagar(request $request, $id, $procedencia)
    {
        $configuracion = DB::table('configuracion_general')
        ->select('tasa_interes_mora')->first();

        $plazo_pago = PlazosPago::findOrFail($id);
        $id_prospecto = $plazo_pago->prospecto_id;
        $nuevo_monto = floatval(str_replace(',', '', $request->get('nuevo_monto')));
        if($nuevo_monto <= $plazo_pago->saldo ){
            $pago = new Pagos();
            $pago->estatus = 'Aplicado';
            $pago->fecha = $request->get('nuevo_fecha');
            $pago->monto = $nuevo_monto;
            $pago->forma_pago_id = $request->get('nuevo_forma_pago');
            $pago->plazo_pago_id = $id;
            $fechaLimitePlazo = Carbon::parse($plazo_pago->fecha);
            $fechaUltimoPago = Carbon::parse($request->get('nuevo_fecha'));
            if ($request->get('nuevo_fecha') > $plazo_pago->fecha) {
                $diff = $fechaUltimoPago->diffInDays($fechaLimitePlazo);
                $pago->dias_retraso = $diff;
                $tasa_interes_cien = $configuracion->tasa_interes_mora / 100;
                $pago->mora = round((($tasa_interes_cien/30) *  $plazo_pago->saldo) * $diff, 2);
            }else{
                $pago->dias_retraso = 0;
                $pago->mora = 0;
            }
            $pago->save();

            $prospecto = Prospecto::findOrFail($id_prospecto);
            if ($prospecto->porcentaje_interes > 0) {
                $interesCien = $prospecto->porcentaje_interes / 100;
            }else{
                $interesCien = 0;
            }
            $pagos_plazo = DB::table('pagos')
            ->select('id_pago','monto','dias_retraso','mora')
            ->where('plazo_pago_id','=',$id)
            ->where('estatus','=','Aplicado')
            ->get();
            $sumapagos =0;
            $mora_pago =0;
            $dias_pago =0;
            foreach ($pagos_plazo as $key) {
                $sumapagos = $sumapagos + $key->monto;
                $mora_pago = $mora_pago + $key->mora;
                $dias_pago = $dias_pago + $key->dias_retraso;
            }
            if ($sumapagos >= $plazo_pago->total) {
                $plazo_pago->estatus = 'Pagado';
            }else{
                if ($plazo_pago->estatus == 'Vencido') {
                    $plazo_pago->estatus = 'Vencido';
                }else{
                    $plazo_pago->estatus = 'Pendiente';
                }
            }
            $plazo_pago->pagado = round($sumapagos,2);
            if ($sumapagos > ($plazo_pago->capital_inicial * $interesCien)) {
                $sumapagos = round($sumapagos - ($plazo_pago->capital_inicial * $interesCien), 2);
                $plazo_pago->interes = 0;
            }else{
                $plazo_pago->interes = round( ($plazo_pago->capital_inicial * $interesCien) - $sumapagos, 2);
                $sumapagos = 0;
            }
            if ($sumapagos > 0) {
                $plazo_pago->capital = round( $plazo_pago->capital_inicial - $sumapagos);
                $sumapagos = 0;
            }
            $plazo_pago->update();
            $plazo_pago->saldo = round($plazo_pago->capital + $plazo_pago->interes, 2);
            $plazo_pago->dias_retraso = $dias_pago;
            $plazo_pago->monto_mora = $mora_pago;
            $plazo_pago->update();

            $plazos_pago = DB::table('plazos_pago')
            ->select('id_plazo_pago','pagado')
            ->where('prospecto_id','=', $id_prospecto)
            ->where('estatus','=','Pagado')
            ->get();
            $sumaplazos =0;
            foreach ($plazos_pago as $p) {
                $sumaplazos = $sumaplazos + $p->pagado;
            }
            $plazos_pago = DB::table('plazos_pago')
            ->select('id_plazo_pago','pagado')
            ->where('prospecto_id','=', $id_prospecto)
            ->get();
            $sumapagoshecho =0;
            foreach ($plazos_pago as $p) {
                $sumapagoshecho = $sumapagoshecho + $p->pagado;
            }
            
            $prospecto->fecha_ultimo_pago = $request->get('nuevo_fecha');
            $prospecto->monto_ultimo_pago = $nuevo_monto;
            $prospecto->pagado = $sumapagoshecho;
            
            //echo "<br/> interes cien: ".$interesCien;
            $plazos_pago = DB::table('plazos_pago')
            ->select('id_plazo_pago','pagado','saldo','interes','capital')
            ->where('prospecto_id','=', $id_prospecto)
            ->where('estatus','!=', 'Pagado')
            ->get();
            $capitalnuevo =0;
            $interesnuevo =0;
            $saldonuevo = 0;
            foreach ($plazos_pago as $p) {
                $capitalnuevo = $capitalnuevo + ($p->capital);
                $interesnuevo = $interesnuevo + ($p->interes);
                $saldonuevo = $saldonuevo + $p->saldo;
            }

            $prospecto->saldo = $saldonuevo;
            $prospecto->capital = $capitalnuevo;
            $prospecto->interes = $interesnuevo;
            $prospecto->update();

            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
            if ($procedencia == 'Menu') {
                return redirect()->route('plazos_pago')->with($notification);
            }else{
                return back()->with($notification);
            }
        }else{
            $notification = array(
                'msj' => 'El monto debe ser menor o igual al saldo del plazo.',
                'alert-type' => 'error'
            );

            if ($procedencia == 'Menu') {
                return redirect()->route('plazos_pago')->with($notification);
            }else{
                return back()->with($notification);
                //return Redirect::to("plazos_pago/show/".$id."/".$procedencia )->with('msj','el monto debe ser menor o igual al saldo del plazo.');
            }
        }
    }
    public function exportExcel(request $request, $id="")
    {   

        $numero_bs = $request->get('numero_excel');
        $estatus_bs = $request->get('estatus_excel');
        $tipo_bs = $request->get('tipo_excel');
        $fecha_max_bs = $request->get('fecha_max_excel');
        $fecha_min_bs = $request->get('fecha_min_excel');
        $prospecto_bs =$request->get('prospecto_excel');
        $id_bs =$request->get('id_excel');

        $filtro = ' pp.id_plazo_pago is not NULL';
        if ($id_bs != '' and $id_bs != 'Vacio' and $id_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.id_plazo_pago = '.$id_bs;
        }
        if ($numero_bs != '' and $numero_bs != 'Vacio' and $numero_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.num_plazo LIKE "%'.$numero_bs.'%"';
        }
        if ($prospecto_bs != '' and $prospecto_bs != 'Vacio' and $prospecto_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' nombre LIKE "%'.$prospecto_bs.'%"';
        }
        if ($estatus_bs != '' and $estatus_bs != 'Vacio' and $estatus_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.estatus = "'.$estatus_bs.'"';
        }
        if ($tipo_bs != '' and $tipo_bs != 'Vacio' and $tipo_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.tipo = "'.$tipo_bs.'"';
        }
        if ($fecha_max_bs != '' and $fecha_min_bs != '' and $fecha_max_bs != null and $fecha_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " pp.fecha between '".$fecha_min_bs."' AND '". $fecha_max_bs."'";
        }

        $resultados = DB::table('plazos_pago as pp')
        ->join('prospectos as p','pp.prospecto_id','=','p.id_prospecto','left',false)
        ->join('moneda as m','pp.moneda_id','=','m.id_moneda','left',false)
        ->select('pp.id_plazo_pago','p.nombre as prospecto_id','pp.fecha','pp.estatus','pp.num_plazo','pp.total','pp.saldo','pp.pagado','pp.dias_retraso','pp.monto_mora','pp.notas','pp.deuda','pp.amortizacion','pp.capital_inicial','pp.capital','pp.interes','pp.interes_acumulado','pp.capital_acumulado','pp.total_acumulado','m.siglas as moneda_id','pp.descripcion','pp.tipo')
        ->whereRaw($filtro)
        ->get();

        $campos = array('id plazo pago', 'prospecto', 'fecha', 'estatus', 'num plazo', 'total', 'saldo', 'pagado', 'dias retraso', 'monto mora', 'notas', 'capital inicial', 'capital', 'interes', 'moneda', 'fecha ultimo pago', 'forma pago str', 'nombre propiedad', 'importe real pago', 'saldo favor plazo','descripcion','tipo');

        ob_end_clean();
        return Excel::download(new PlazosPagoExport("exports.plazos_pago", $resultados, $campos),'Plazos Pago.xlsx');
    }

    public function aviso_pago()
    {
        //las actividades
        $configuracion = DB::table('configuracion_general')
        ->first();
        $CorreoEmisor = 'bismoservicios@gmail.com';
        $remitente = 'Notificaciones '.$configuracion->nombre_cliente;
        ///Seleccionamos todas las tarea de actividades
        $plazos = DB::table('plazos_pago as pp')
        ->join('prospectos as pr','pp.prospecto_id','=','pr.id_prospecto','left',false)
        ->join('propiedad as prop','pr.propiedad_id','=','prop.id_propiedad','left',false)
        ->select('id_plazo_pago','pp.prospecto_id', 'pp.fecha','pp.estatus','pp.num_plazo','pp.total','pp.saldo','pp.pagado','pp.monto_mora','pp.dias_retraso','pp.notas','pp.interes','pp.interes_acumulado','pp.capital_acumulado','pp.total_acumulado','pp.deuda','pp.amortizacion','pp.capital','pp.capital_inicial','pp.moneda_id','pr.nombre as nombre_prospecto','prop.nombre as nombre_propiedad','pr.correo as correo_prospecto','pp.estatus_alerta')
        ->where('pp.estatus_alerta','!=','Enviada')
        ->where('pp.estatus','=','Pendiente')
        ->whereMonth('pp.fecha',date('m'))
        ->get();

        foreach ($plazos as $key) { 
            $destinatarioCorreo = $key->correo_prospecto;
            if (!empty($destinatarioCorreo)) {
                # code...
                $datework = Carbon::createFromDate($key->fecha);
                $hora_atras = $datework->subDay(3);
                // echo "<br/>.HORA-30: ".$hora_atras;
                //echo "<br/>Correo lleno: ".$destinatarioCorreo;

                if (date('Y-m-d', strtotime($hora_atras)) == date('Y-m-d') ) {
                    $data = array(
                        'id_plazo_pago' => $key->id_plazo_pago,
                        'nombre_prospecto' => $key->nombre_prospecto,
                        'fecha' => $key->fecha,
                        'total' => $key->total,
                        'saldo' => $key->saldo,
                        'nombre_propiedad' => $key->nombre_propiedad,
                        'num_plazo' => $key->num_plazo,
                        'msj' => 'Estimado(a) '.$key->nombre_prospecto.', se le recuerda que tiene que realizar el pago correspondiente a este mes, proximo a vencerse dentro de 3 dias.',
                    );
                    Mail::send('emails.aviso_pago', $data, function ($message) use ($CorreoEmisor, $destinatarioCorreo, $remitente) {
                        $message->from($CorreoEmisor, $remitente);
                        $message->to($destinatarioCorreo)->subject('Aviso proximo pago');
                    });
                    //echo "<br/>Correo enviado: ".$hora_atras;
                    $plazo = PlazosPago::findOrFail($key->id_plazo_pago);
                    $plazo->estatus_alerta = 'Pendiente hoy';
                    $plazo->update();
                }
                if (date('Y-m-d', strtotime($key->fecha)) == date('Y-m-d') and $key->estatus_alerta == 'Pendiente hoy' ) {
                    $data = array(
                        'id_plazo_pago' => $key->id_plazo_pago,
                        'nombre_prospecto' => $key->nombre_prospecto,
                        'fecha' => $key->fecha,
                        'total' => $key->total,
                        'saldo' => $key->saldo,
                        'nombre_propiedad' => $key->nombre_propiedad,
                        'num_plazo' => $key->num_plazo,
                        'msj' => 'Estimado(a) '.$key->nombre_prospecto.', se le recuerda que tiene que realizar el pago correspondiente a este mes, el cual esta proximo a vencerse el dia de hoy.',
                    );
                    Mail::send('emails.aviso_pago', $data, function ($message) use ($CorreoEmisor, $destinatarioCorreo, $remitente) {
                        $message->from($CorreoEmisor, $remitente);
                        $message->to($destinatarioCorreo)->subject('Aviso proximo pago');
                    });
                    //echo "<br/>Correo enviado: ".$hora_atras;
                    $plazo = PlazosPago::findOrFail($key->id_plazo_pago);
                    $plazo->estatus_alerta = 'Enviada';
                    $plazo->update();
                }
            }
        }
        
    }

    public function build_filtro( request $request)
    {
        $numero_bs = $request->get('numero_bs');
        $estatus_bs = $request->get('estatus_bs');
        $tipo_bs = $request->get('tipo_bs');
        $fecha_max_bs = $request->get('fecha_max_bs');
        $fecha_min_bs = $request->get('fecha_min_bs');
        $prospecto_bs =$request->get('prospecto_bs');

        $filtro = ' pp.id_plazo_pago is not NULL';
        if ($numero_bs != '' and $numero_bs != 'Vacio' and $numero_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.num_plazo LIKE "%'.$numero_bs.'%"';
        }
        if ($prospecto_bs != '' and $prospecto_bs != 'Vacio' and $prospecto_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' nombre LIKE "%'.$prospecto_bs.'%"';
        }
        if ($estatus_bs != '' and $estatus_bs != 'Vacio' and $estatus_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.estatus = "'.$estatus_bs.'"';
        }
        if ($tipo_bs != '' and $tipo_bs != 'Vacio' and $tipo_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' pp.tipo = "'.$tipo_bs.'"';
        }
        if ($fecha_max_bs != '' and $fecha_min_bs != '' and $fecha_max_bs != null and $fecha_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " pp.fecha between '".$fecha_min_bs."' AND '". $fecha_max_bs."'";
        }
        return $filtro;
    }

}
