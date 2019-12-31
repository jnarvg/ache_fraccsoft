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
use DB;
use App\Http\Middleware\Authenticate;

class ReportesController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function cuenta_cobrar(request $request)
    {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_final = $request->get('fecha_final');
        $propiedad_bs = $request->get('propiedad_bs');
        $proyecto_bs =$request->get('proyecto_bs');

        if ($fecha_inicio != null and $fecha_final != null) {
            if ($proyecto_bs != 'Vacio' and $propiedad_bs != 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->get();
                $resultados_g = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'),DB::raw('SUM(pp.saldo) as saldo_group'),DB::raw('SUM(pp.pagado) as pagado_group'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->groupBy('prospecto_id')
                ->get();
            }elseif ($proyecto_bs != 'Vacio' and $propiedad_bs == 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->get();
                $resultados_g = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'),DB::raw('SUM(pp.saldo) as saldo_group'),DB::raw('SUM(pp.pagado) as pagado_group'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->groupBy('prospecto_id')
                ->get();
            }elseif ($propiedad_bs != 'Vacio' and $proyecto_bs == 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->get();
                $resultados_g = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'),DB::raw('SUM(pp.saldo) as saldo_group'),DB::raw('SUM(pp.pagado) as pagado_group'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->groupBy('prospecto_id')
                ->get();
            }else{
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->get();
                $resultados_g = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'),DB::raw('SUM(pp.saldo) as saldo_group'),DB::raw('SUM(pp.pagado) as pagado_group'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->groupBy('prospecto_id')
                ->get();
            }
        }else{
            $resultados = DB::table('plazos_pago as pp')
            ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
            ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
            ->get();
            $resultados_g = DB::table('plazos_pago as pp')
            ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
            ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'),DB::raw('SUM(pp.saldo) as saldo_group'),DB::raw('SUM(pp.pagado) as pagado_group'))
            ->groupBy('prospecto_id')
            ->get();
        }
        $sumasaldo = 0;
        $sumapagado = 0;
        foreach ($resultados as $key) {
            $sumasaldo = $sumasaldo + $key->saldo;
            $sumapagado = $sumapagado + $key->pagado;
        }

        // echo "<br/> pagado: ".$sumapagado;
        // echo "<br/> saldo: ".$sumasaldo;
        // echo "<br/>Resultados: <br/>".json_encode($resultados);
        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $propiedades = DB::table('propiedad')
        ->select('id_propiedad', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        return view('reportes.cuenta_cobrar.index',['resultados'=>$resultados, 'resultados_g'=>$resultados_g,'prospectos'=>$prospectos,'proyectos'=>$proyectos,'propiedades'=>$propiedades,'request'=>$request,'sumasaldo'=>$sumasaldo,'sumapagado'=>$sumapagado]);
    }
    public function semaforo(request $request)
    {
        $propiedad_bs = $request->get('propiedad_bs');
        $proyecto_bs =$request->get('proyecto_bs');

        if ($proyecto_bs != 'Vacio' and $propiedad_bs != 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.proyecto_id','=',$proyecto_bs)
            ->where('p.propiedad_id','=',$propiedad_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }elseif ($proyecto_bs != 'Vacio' and $propiedad_bs == 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.proyecto_id','=',$proyecto_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }elseif ($propiedad_bs != 'Vacio' and $proyecto_bs == 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.propiedad_id','=',$propiedad_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }else{
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.estatus','>=',6)
            ->get();
        }

        $sumasaldo = 0;
        $sumapagado = 0;
        foreach ($resultados as $key) {
            $sumasaldo = $sumasaldo + $key->saldo;
            $sumapagado = $sumapagado + $key->pagado;
        }
        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $propiedades = DB::table('propiedad')
        ->select('id_propiedad', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        return view('reportes.semaforo.index',['resultados'=>$resultados,'prospectos'=>$prospectos,'proyectos'=>$proyectos,'propiedades'=>$propiedades,'request'=>$request,'sumasaldo'=>$sumasaldo,'sumapagado'=>$sumapagado]);
    }
    public function estatus_propiedad( request $request)
    {
        $propiedad_bs = $request->get('propiedad_bs');
        $proyecto_bs =$request->get('proyecto_bs');

        if ($proyecto_bs != 'Vacio' and $proyecto_bs != '') {
            $resultados = DB::table('propiedad as prop')
            ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left', false)
            ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
            ->select('prop.nombre as nombre_propiedad','estatus_propiedad_id','ep.estatus_propiedad','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->where('prop.proyecto_id',$proyecto_bs)
            ->groupBy('prop.estatus_propiedad_id')
            ->get();
            $propiedades = DB::table('propiedad')
            ->select(DB::raw('count(*) as total_propiedades'))
            ->where('proyecto_id',$proyecto_bs)
            ->first();
        }else{
            $resultados = DB::table('propiedad as prop')
            ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left', false)
            ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
            ->select('prop.nombre as nombre_propiedad','estatus_propiedad_id','ep.estatus_propiedad','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->groupBy('prop.estatus_propiedad_id')
            ->get();
            $propiedades = DB::table('propiedad')
            ->select(DB::raw('count(*) as total_propiedades'))
            ->orderby('nombre','asc')
            ->first();
        }
        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");
        $total_propiedades = $propiedades->total_propiedades;
        return view('reportes.estatus_propiedad.index', compact('resultados','request','proyectos','total_propiedades','colores_A'));
    }
    public function medio_contacto( request $request)
    {
        $proyecto_bs =$request->get('proyecto_bs');

        if ($proyecto_bs != 'Vacio' and $proyecto_bs != '') {
            $resultados = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->where('p.proyecto_id',$proyecto_bs)
            ->groupBy('p.medio_contacto_id')
            ->get();

            $prospectos = DB::table('prospectos')
            ->select(DB::raw('count(*) as total_prospectos'))
            ->where('proyecto_id',$proyecto_bs)
            ->first();
        }else{
            $resultados = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->groupBy('p.medio_contacto_id')
            ->get();

            $prospectos = DB::table('prospectos')
            ->select(DB::raw('count(*) as total_prospectos'))
            ->orderby('nombre','asc')
            ->first();
        }
        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");
        $medios = DB::table('medio_contacto')->get();
        $total_prospectos = $prospectos->total_prospectos;
        return view('reportes.medio_contacto.index', compact('resultados','request','proyectos','total_prospectos','medios','colores_A'));
    }
    public function cliente_mes( request $request){
        $proyecto_bs =$request->get('proyecto_bs');
        $fecha_minima_bs = $request->get('fecha_minima_bs');
        $fecha_maxima_bs = $request->get('fecha_maxima_bs');

        if ($proyecto_bs != 'Vacio' and $proyecto_bs != '') {
            $resultados = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->where('p.proyecto_id',$proyecto_bs)
            ->where('p.fecha_registro','>=', $fecha_minima_bs)
            ->where('p.fecha_registro','<=', $fecha_maxima_bs)
            ->groupBy('p.medio_contacto_id')
            ->get();

            $prospectos = DB::table('prospectos')
            ->select(DB::raw('count(*) as total_prospectos'))
            ->where('proyecto_id',$proyecto_bs)
            ->first();
        }
        elseif($fecha_minima_bs != null and $fecha_maxima_bs != null){
            $resultados = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->where('p.fecha_registro','>=', $fecha_minima_bs)
            ->where('p.fecha_registro','<=', $fecha_maxima_bs)
            ->groupBy('p.medio_contacto_id')
            ->get();

            $prospectos = DB::table('prospectos')
            ->select(DB::raw('count(*) as total_prospectos'))
            ->first();
        }else{
            $resultados = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','py.nombre as nombre_proyecto', DB::raw('count(*) as cantidad'))
            ->groupBy('p.medio_contacto_id')
            ->get();

            $prospectos = DB::table('prospectos')
            ->select(DB::raw('count(*) as total_prospectos'))
            ->orderby('nombre','asc')
            ->first();
        }
        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $medios = DB::table('medio_contacto')->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");
        $total_prospectos = $prospectos->total_prospectos;
        return view('reportes.cliente_mes.index', compact('resultados','request','proyectos','total_prospectos','medios','colores_A'));

    }
    public function analisis_metros( request $request){
        $proyecto_bs =$request->get('proyecto_bs');
        $uso_propidad_bs =$request->get('uso_propiedad_bs');

        if ($proyecto_bs == 'Vacio' or $proyecto_bs == '') {
            $primer_proyecto = DB::table('proyecto as p')
            ->select('p.id_proyecto')
            ->first();
            $proyecto_bs = $primer_proyecto->id_proyecto;
        }
        $proyecto_data = DB::table('proyecto as p')
        ->select('p.id_proyecto','p.nombre as nombre_proyecto','p.metros_construccion','p.metros_terreno')
        ->where('id_proyecto',$proyecto_bs)
        ->first();
        if ($uso_propidad_bs != 'Todos' and $uso_propidad_bs != '' and $uso_propidad_bs != null) {
            $resultados = DB::table('propiedad as p')
            ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left', false)
            ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','p.mts_interior','p.mts_exterior','p.mts_total','up.uso_propiedad','ep.estatus_propiedad',DB::raw('count(*) as suma_mts_totales'), DB::raw('sum(p.mts_total) as suma_mts_totales'))
            ->groupBy('p.estatus_propiedad_id')
            ->where('p.proyecto_id',$proyecto_bs)
            ->where('up.uso_propiedad','=', $uso_propidad_bs)
            ->get();
        }else{
            $resultados = DB::table('propiedad as p')
            ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left', false)
            ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left', false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->select('p.nombre as nombre_prospecto','p.mts_interior','p.mts_exterior','p.mts_total','up.uso_propiedad','ep.estatus_propiedad', DB::raw('count(*) as suma_mts_totales'), DB::raw('sum(p.mts_total) as suma_mts_totales'))
            ->groupBy('p.estatus_propiedad_id')
            ->where('p.proyecto_id',$proyecto_bs)
            ->get();
        }
        $total_mts_totales_distribuidos = 0;
        $data_mts_total = array();
        foreach ($resultados as $key) {
            $porcentaje_mts_total = round( ($key->suma_mts_totales * 100) / $proyecto_data->metros_terreno, 2);
            $total_mts_totales_distribuidos = $total_mts_totales_distribuidos + $key->suma_mts_totales;
            /*Array paralas graficas*/
            $data_mts_total[] = array($key->estatus_propiedad, $key->suma_mts_totales, $porcentaje_mts_total );
        }
        $resto_proyecto_mts = round($proyecto_data->metros_terreno-$total_mts_totales_distribuidos , 2);

        $porcentaje_proyecto_resto = round( ($resto_proyecto_mts * 100) / $proyecto_data->metros_terreno, 2);
        /*Ultimo push al array para mostrarls metros no distirbudos es decir propdasdes no registradas o sin metros*/
        $data_mts_total[] = array('Otros usos', $resto_proyecto_mts, $porcentaje_proyecto_resto );
        /*obtenr totals para no hacer oepraciones en el blade*/
        $total_mts_totales = 0;
        $porcentaje_mts_total_total = 0;
        foreach ($data_mts_total as $key) {
            $porcentaje_mts_total_total = $porcentaje_mts_total_total + $key[2];
            $total_mts_totales = $total_mts_totales + $key[1];
        }

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");
        $usos_propiedad = DB::table('uso_propiedad')->get();
        return view('reportes.analisis_metros.index', compact('proyecto_data','resultados','request','proyectos','usos_propiedad','data_mts_total','total_mts_totales','porcentaje_mts_total_total','colores_A' ));
    }
    public function enganche_estimado( request $request){
        $proyecto_bs =$request->get('proyecto_bs');
        $fecha_minima_bs = $request->get('fecha_minima_bs');
        $fecha_maxima_bs = $request->get('fecha_maxima_bs');

        if ($proyecto_bs == 'Vacio' or $proyecto_bs == '') {
            $primer_proyecto = DB::table('proyecto as p')
            ->select('p.id_proyecto')
            ->first();
            $proyecto_bs = $primer_proyecto->id_proyecto;
        }
        $proyecto_data = DB::table('proyecto as p')
        ->select('p.id_proyecto','p.nombre as nombre_proyecto','p.metros_construccion','p.metros_terreno')
        ->where('id_proyecto',$proyecto_bs)
        ->first();
        $resultados = DB::table('prospectos as p')
        ->join('plazos_pago as pp','p.id_prospecto','=','pp.prospecto_id','left', false)
        ->select('p.nombre as prospecto','p.monto_venta','p.porcentaje_enganche','p.monto_enganche','pp.pagado','pp.estatus','pp.total')
        ->where('p.proyecto_id',$proyecto_bs)
        ->where('pp.num_plazo',1)
        ->where('p.porcentaje_enganche','>',0)
        ->whereBetween('pp.fecha', [$fecha_minima_bs, $fecha_maxima_bs])
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $total_enganche = 0;
        $total_pagado = 0;
        foreach ($resultados as $key) {
             $total_enganche = $total_enganche + $key->total;
             $total_pagado = $total_pagado + $key->pagado;
        } 
        return view('reportes.enganche_estimado.index', compact('proyecto_data','resultados','request','proyectos','total_enganche','total_pagado'));
    }
    public function comparativo( request $request)
    {
        $fecha_bs = $request->get('fecha_bs');
        if ($fecha_bs == '') {
            $fecha_bs = date('Y-m-d'); 
        }
        $year_bs = date('Y', strtotime($fecha_bs));
        $filtro = '';

        if ($fecha_bs != '') {
            $filtro = $filtro . " fecha_venta <= '".$fecha_bs."'";
        }

        $meses= array(['1','Enero'],['2','Febrero'],['3','Marzo'],['4','Abril'],['5','Mayo'],['6','Junio'],['7','Julio'],['8','Agosto'],['9','Septiembre'],['10','Octubre'],['11','Noviembre'],['12','Diciembre']);

        $anios = DB::table('prospectos as p')
                ->select(DB::raw('YEAR(p.fecha_venta) as anio'))
                ->whereYear('p.fecha_venta', '<=', $year_bs)
                ->whereRaw($filtro)
                ->groupBy(DB::raw('YEAR(p.fecha_venta)'))
                ->get();

        $tabla = '';
        $tabla = $tabla . '<thead class="bg-dark text-white center font-weight-bold"><tr><td>Month</td>';
        foreach ($anios as $keyanio) {
            $tabla = $tabla . '<td>$ Venta '.$keyanio->anio.'</td>';
        }
        $tabla = $tabla . '</tr></thead>';

        if ($filtro != '') {
            //Para venta mensual
            foreach ($meses as $keymes) {
                $tabla = $tabla . '<tbody><tr>';
                $tabla = $tabla . '<td class="center font-weight-bold">'.$keymes[1].'</td>';
                foreach ($anios as $keyanio) {
                    $ventas_mensuales = DB::table('prospectos as p')
                    ->select(DB::raw('count(*) as cantidad, sum(p.monto_venta) as suma_venta'))
                    ->where('p.fecha_venta','!=',null)
                    ->whereMonth('p.fecha_venta', $keymes[0])
                    ->whereYear('p.fecha_venta', $keyanio->anio)
                    ->whereRaw($filtro)
                    ->first();
                    $tabla = $tabla . '<td class="center">$'.number_format($ventas_mensuales->suma_venta , 2 , "." , ",").'</td>';
                    
                }
            }
            $tabla = $tabla . '</tr></tbody><tfoot class="font-weight-bold bg-info text-white center"><tr><td>Total</td>';
            foreach ($anios as $keyanio) {
                $ventas_mensuales = DB::table('prospectos as p')
                    ->select(DB::raw('count(*) as cantidad, sum(p.monto_venta) as suma_anual'))
                    ->where('p.fecha_venta','!=',null)
                    ->whereRaw($filtro)
                    ->whereYear('p.fecha_venta', $keyanio->anio)
                    ->first();
                $tabla = $tabla . '<td>$'.number_format($ventas_mensuales->suma_anual , 2 , "." , ",").'</td>';
            }
            $tabla = $tabla . '</tr></tfoot>';
        }

        $years = array();
        for ($i=2017; $i <= date('Y'); $i++) { 
            $years[] = $i;
        }

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        //echo $filtro."<br/> ";
        // echo json_encode($oportunidad_cotizacion);
        // echo json_encode($oportunidad_apartada);
        return view('reportes.comparativo.index', compact('estatus_prospecto','tipos_oportunidad','ventas_mensuales','request','proyectos','rol','id','meses','fecha_bs','tabla','anios'));
    }
    public function ventas( request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $yearMes = $request->get('mes_bs').'-'.$request->get('year_bs');
        $mes_bs = $request->get('mes_bs');
        $year_bs = $request->get('year_bs');
        $q_bs = $request->get('q_bs');
        /*Filtros*/
        $interesado_en_bs = $request->get('interesado_en_bs');
        $seccion_bs = $request->get('seccion_bs');

        if ($year_bs == '') {
            $year_bs = date('Y'); 
        }

        $filtro = '';
        $filtroseccion = '';
        $filtromes = '';
        $filtrosublote = '';
        if ($seccion_bs != '' and $seccion_bs != null) {
            $filtroseccion = ' AND n.nivel LIKE "%'.$seccion_bs.'%"';
        }
        if ($mes_bs != '' and $mes_bs != null) {
            $filtromes = ' AND MONTH(p.fecha_venta) = '.$mes_bs;
        }
        if ($q_bs != '' and $q_bs != null) {
            $filtromes = ' AND MONTH(p.fecha_venta) IN ('.$q_bs.') ';
        }
        $filtro = $filtro . ' year(p.fecha_venta) = '.$year_bs. $filtrosublote.$filtroseccion.$filtromes;
        

        if ($filtro != '') {
            $body_tabla = '<tbody>';
            $prospectos_venta = DB::table('prospectos as p')
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('nivel as n','prop.nivel_id','=','n.id_nivel','left',false)
            ->join('users as u','p.asesor_id','=','u.id','left',false)
            ->select('p.id_prospecto', 'p.nombre as nombre_prospecto','p.fecha_venta','p.propiedad_id','prop.nivel_id','p.monto_venta', 'n.nivel as seccion','prop.nombre as nombre_propiedad','p.fecha_apartado','p.fecha_registro','p.fecha_enganche','p.monto_apartado','p.monto_enganche','p.telefono','p.correo','p.mensualidad','p.num_plazos','u.name as nombre_asesor')
            ->whereNotIn('p.estatus',[6,11,5,1,2])
            ->where('p.fecha_venta','!=',null)
            ->where('p.monto_venta','!=',null)
            ->whereRaw($filtro)
            ->get();
            $venta_acumulada = 0;
            foreach ($prospectos_venta as $key) {
                $body_tabla = $body_tabla . '<tr ><td>'.date('Y-M-d',strtotime($key->fecha_venta)).'</td><td>'.$key->nombre_propiedad.'</td><td>'.$key->seccion.'</td><td><a href="#" data-toggle="modal" data-target="#modal_prospecto_'.$key->id_prospecto.'">'.$key->nombre_prospecto.'</a></td><td>$'.number_format($key->monto_venta , 2 , "." , ",").'</td></tr><div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_prospecto_'.$key->id_prospecto.'"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">'.$key->nombre_prospecto.'</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p>Mas informacion acerca de este prospecto</p><ul><li><b>Fecha apartado: </b>'.date('Y-M-d',strtotime($key->fecha_apartado)).'</li><li><b>Monto apartado: </b>$'.number_format($key->monto_apartado, 2 , "." , ",").'</li><li><b>Fecha enganche: </b>'.date('Y-M-d',strtotime($key->fecha_enganche)).'</li><li><b>Monto enganche: </b>$'.number_format($key->monto_enganche, 2 , "." , ",").'</li><li><b>Monto venta: </b>$'.number_format($key->monto_venta, 2 , "." , ",").'</li><li><b>Asesor: </b>'.$key->nombre_asesor.'</li><li><b>Mensualidad: </b>$'.number_format($key->mensualidad, 2 , "." , ",").'</li><li><b>Plazos: </b>'.$key->num_plazos.'</li><li><b>Telefono: </b>'.$key->telefono.'</li><li><b>Correo: </b>'.$key->correo.'</li><li><b>Propiedad: </b>'.$key->nombre_propiedad.'</li><li><b>Fecha venta: </b>'.date('Y-M-d',strtotime($key->fecha_venta)).'</li><li><b>Seccion: </b>'.$key->seccion.'</li></ul></div><div class="modal-footer">    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>    <button type="submit" class="btn btn-info">Confirmar</button></div></div></div></div>';

                $venta_acumulada = $venta_acumulada + $key->monto_venta;
            }
            $body_tabla = $body_tabla.'</tbody><tfoot><tr style="font-weight: bold;"><td colspan ="4" >Total</td><td>$'.number_format($venta_acumulada , 2 , "." , ",").'</td></tr></tfoot>';

        }
        //echo $filtro.'<br/>';
        /*Filtros*/
        $years = array();
        for ($i=2017; $i <= date('Y'); $i++) { 
            $years[] = $i;
        }

        $meses= array(['1','Enero'],['2','Febrero'],['3','Marzo'],['4','Abril'],['5','Mayo'],['6','Junio'],['7','Julio'],['8','Agosto'],['9','Septiembre'],['10','Octubre'],['11','Noviembre'],['12','Diciembre']);
        $ques = array(['Q1','01,02,03'],['Q2',"'04','05','06'"],['Q3',"'07','08','09'"],['Q4',"'10','11','12'"]);

        if ($yearMes == '-') {
            $yearMes = date('m-Y');
        }
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;

        $seccion = DB::table('nivel')->select('nivel')->get();
        //echo $filtro."<br/> ";
        // echo json_encode($comisiones_persona);
        //echo json_encode($comisiones_propiedad_detalle);
        return view('reportes.ventas.index', compact('comisiones_propiedad','comisiones_propiedad_detalle', 'comisiones_persona','request','rol','id','colores_A','years','meses','ques','yearMes','body_tabla','seccion'));
    }
    public function pagos( request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $year_bs = $request->get('year_bs');
        $estatus_bs = $request->get('estatus_bs');
        $estatus_oportunidad_bs = $request->get('estatus_oportunidad_bs');
        $nombre_bs = $request->get('nombre_bs');
        if ($year_bs == '' or $year_bs == null) {
            $year_bs = date('Y');
        }
        $filtro = 'p.fecha_venta is not null';
        $filtro_plazos = 'pp.saldo > 0 AND pp.estatus != "Inactivo"';
        
        if ($nombre_bs) {
            if ($filtro != '') {
              $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.id_prospecto IN (';
            $i =0;
            foreach ($nombre_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($estatus_oportunidad_bs != '' and $estatus_oportunidad_bs != null and $estatus_oportunidad_bs != 'all') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.estatus IN (';
            $i =0;
            foreach ($estatus_oportunidad_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($year_bs != '' and $year_bs != null) {
            if ($filtro_plazos != '') {
                $filtro_plazos = $filtro_plazos . ' AND ';
            }
            $filtro_plazos = $filtro_plazos . ' year(pp.fecha) = '.$year_bs;
        }
        if ($estatus_bs != '' and $estatus_bs != null and $estatus_bs != 'all') {
            if ($filtro_plazos != '') {
                $filtro_plazos = $filtro_plazos . ' AND ';
            }
            $filtro_plazos = $filtro_plazos . ' pp.estatus IN (';
            $i =0;
            foreach ($estatus_bs as $key) {
                if ($i == 0) {
                    $filtro_plazos = $filtro_plazos."'".$key."'";
                }else{
                    $filtro_plazos = $filtro_plazos.",'".$key."'";
                }
                $i++;
            }
            $filtro_plazos = $filtro_plazos .')';
        }
        $tabla = '';
        $meses= array(['1','Enero',0],['2','Febrero',0],['3','Marzo',0],['4','Abril',0],['5','Mayo',0],['6','Junio',0],['7','Julio',0],['8','Agosto',0],['9','Septiembre',0],['10','Octubre',0],['11','Noviembre',0],['12','Diciembre',0]);

        $oportunidades = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left', false)
        ->select('p.id_prospecto','p.nombre as nombre_prospecto')
        ->whereNotIn('e.estatus_crm',['Perdido','Inactivo','Lead','Prospecto','Contrato','Postergado'])
        ->whereRaw($filtro)
        ->orderby('p.nombre','ASC')
        ->get();
        $tabla = $tabla.'<thead class="bg-gray-600 center text-white"><tr><th valign="middle" rowspan="2">Nombre</th><th colspan="13">Importe</th></tr><tr>';
        foreach ($meses as $m) {
            $tabla = $tabla.'<th>'.$year_bs.'/'.$m[0].'</th>';
        } 
        $tabla = $tabla.'<th>Total</th></tr></thead><tbody>';
        $total_acumulado = 0;
        foreach ($oportunidades as $key) {
            $plazos_pagos_validar = DB::table('plazos_pago as pp')
            ->select('pp.id_plazo_pago','pp.saldo','pp.total', DB::raw('sum(pp.saldo) as saldo_validar'))
            ->whereRaw($filtro_plazos)
            ->whereYear('pp.fecha','=',$year_bs)
            ->where('pp.prospecto_id','=',$key->id_prospecto)
            ->first();
            if ($plazos_pagos_validar->saldo_validar > 0) {
                $tabla = $tabla . '<tr><td>'.$key->nombre_prospecto.'</td>';
                $total_por_cliente = 0;
                foreach ($meses as $m) {
                    $plazos_pagos = DB::table('plazos_pago as pp')
                    ->select('pp.id_plazo_pago','pp.saldo','pp.total', DB::raw('sum(pp.saldo) as saldo_plazo_mes'))
                    ->whereRaw($filtro_plazos)
                    ->whereYear('pp.fecha','=',$year_bs)
                    ->whereMonth('pp.fecha','=',$m[0])
                    ->where('pp.prospecto_id','=',$key->id_prospecto)
                    ->first();
                    if ($plazos_pagos) {
                        $tabla = $tabla . '<td>$'.number_format($plazos_pagos->saldo_plazo_mes, 2 , "." , ",").'</td>';
                        $total_por_cliente = $total_por_cliente + $plazos_pagos->saldo_plazo_mes;
                    }else{
                        $tabla = $tabla . '<td>$'.number_format(0, 2 , "." , ",").'</td>';
                        $total_por_cliente = $total_por_cliente + 0;
                    }
                }
                $tabla = $tabla . '<td style="font-weight: bold;">$'.number_format($total_por_cliente, 2 , "." , ",").'</td></tr>';
                $total_acumulado = $total_acumulado + $total_por_cliente;
            }
        }
        $tabla = $tabla . '</tbody><tfoot style="font-weight: bold;"><tr><td>Total</td>';
        foreach ($meses as $m) {
            $plazos_mes = DB::table('plazos_pago as pp')
            ->join('prospectos as p','pp.prospecto_id','=','p.id_prospecto')
            ->select(DB::raw('sum(pp.saldo) as suma_saldo, sum(pp.total) as suma_total'))
            ->whereRaw($filtro_plazos)
            ->whereNotIn('p.estatus',[1,2,3,5,6,11]) ///no sea lead, prospecto, contrato,postergado,perdido, inactivo
            ->whereYear('pp.fecha','=',$year_bs)
            ->whereMonth('pp.fecha','=',$m[0])
            ->where('pp.saldo','>',0)
            ->first();
            if ($plazos_mes) {
                $tabla = $tabla .'<td>$'.number_format($plazos_mes->suma_saldo, 2 , "." , ",").'</td>';
            }else{
                $tabla = $tabla .'<td>$'.number_format(0, 2 , "." , ",").'</td>';

            }
        }
        $tabla = $tabla . '<td>$'.number_format($total_acumulado, 2 , "." , ",").'</td></tr></tfoot>';
        

        $estatus = array('Pendiente','Vencido','En curso','Pagado');
        $estatus_oportunidad = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm as estatus_oportunidad')
        ->whereNotIn('estatus_crm',['Perdido','Contrato','Inactivo','Lead','Prospecto','Postergado'])
        ->orderby('nivel','asc')
        ->get();
        $clientes = DB::table('prospectos')
        ->select('id_prospecto','nombre as nombre_prospecto')
        ->get();
        $years = DB::table('plazos_pago')
        ->select(DB::raw('YEAR(fecha) as anio'))
        ->groupBy(DB::raw('YEAR(fecha)'))
        ->get();

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        //echo $filtro."<br/> ";
        // echo json_encode($oportunidad_cotizacion);
        // echo json_encode($oportunidad_apartada);
        return view('reportes.pagos.index', compact('tabla','estatus','estatus_oportunidad','clientes','request','rol','id','years'));
    }
    public function oportunidades( request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $yearMes = $request->get('mes_bs').'-'.$request->get('year_bs');
        $mes_bs = $request->get('mes_bs');
        $year_bs = $request->get('year_bs');
        $q_bs = $request->get('q_bs');
        if ($mes_bs == '') {
            $mes_bs = date('n'); 
        }
        if ($year_bs == '') {
            $year_bs = date('Y'); 
        }
        if ($q_bs == '') {
            $mes_bs = date('n'); 
            $q_bs = '';
        }
        $filtro = '';
        $filtros_alta= '';
        $filtros_cotizacion= '';
        $filtros_apartado= '';
        $filtros_venta= '';

        if ($q_bs != '') {
            $filtro = $filtro . 'year(p.fecha_registro) = '.$year_bs. ' AND MONTH(p.fecha_registro) IN ('.$q_bs.')';
            $filtros_cotizacion = $filtros_cotizacion . 'year(p.fecha_cotizacion) = '.$year_bs. ' AND MONTH(p.fecha_cotizacion) IN ('.$q_bs.')';
            $filtros_apartado = $filtros_apartado . 'year(p.fecha_apartado) = '.$year_bs. ' AND MONTH(p.fecha_apartado) IN ('.$q_bs.')';
            $filtros_venta = $filtros_venta . 'year(p.fecha_venta) = '.$year_bs. ' AND MONTH(p.fecha_venta) IN ('.$q_bs.')';
        }else{
            $filtro = $filtro . 'year(p.fecha_registro) = '.$year_bs. ' AND MONTH(p.fecha_registro) = '.$mes_bs;
            $filtros_cotizacion = $filtros_cotizacion . 'year(p.fecha_cotizacion) = '.$year_bs. ' AND MONTH(p.fecha_cotizacion) = '.$mes_bs;
            $filtros_apartado = $filtros_apartado . 'year(p.fecha_apartado) = '.$year_bs. ' AND MONTH(p.fecha_apartado) = '.$mes_bs;
            $filtros_venta = $filtros_venta . 'year(p.fecha_venta) = '.$year_bs. ' AND MONTH(p.fecha_venta) = '.$mes_bs;
        }

        if ($filtro != '') {
            $estatus_prospecto = DB::table('prospectos as p')
            ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left', false)
            ->select('p.nombre as nombre_prospecto','estatus','e.estatus_crm as label', DB::raw('count(*) as cantidad'))
            ->groupBy('p.estatus')
            ->whereRaw($filtro)
            ->get();

            //por asesor
            $por_asesor = DB::table('prospectos as p')
            ->join('users as e','p.asesor_id','=','e.id','left', false)
            ->select('p.nombre as nombre_prospecto','e.email as label', DB::raw('count(*) as cantidad'))
            ->groupBy('p.asesor_id')
            ->whereRaw($filtro)
            ->get();

            //Para ALta cotizada y apartada
            $oportunidad_alta = DB::table('prospectos as p')
            ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left', false)
            ->select('p.nombre as nombre_prospecto','e.estatus_crm as label', DB::raw('count(*) as cantidad'))
            ->where('p.fecha_registro','!=',null)
            ->whereRaw($filtro)
            ->get();
            $oportunidad_cotizacion = DB::table('prospectos as p')
            ->select('p.nombre as nombre_prospecto',DB::raw('count(*) as cantidad'))
            ->where('p.fecha_cotizacion','!=',null)
            ->whereRaw($filtros_cotizacion)
            ->get();
            $oportunidad_apartada = DB::table('prospectos as p')
            ->select('p.nombre as nombre_prospecto',DB::raw('count(*) as cantidad'))
            ->where('p.fecha_apartado','!=',null)
            ->whereRaw($filtros_apartado)
            ->get();
            //Para venta mensual
            $ventas_mensuales = DB::table('prospectos as p')
            ->select(DB::raw('count(*) as cantidad, sum(p.monto_venta) as suma_venta'))
            ->where('p.fecha_venta','!=',null)
            ->whereRaw($filtros_venta)
            ->get();
        }else{
            $estatus_prospecto = DB::table('prospectos as p')
            ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left', false)
            ->select('p.nombre as nombre_prospecto','estatus','e.estatus_crm as label', DB::raw('count(*) as cantidad'))
            ->groupBy('p.estatus')
            ->get();

            //por asesor
            $por_asesor = DB::table('prospectos as p')
            ->join('users as e','p.asesor_id','=','e.id','left', false)
            ->select('p.nombre as nombre_prospecto','e.email as label', DB::raw('count(*) as cantidad'))
            ->groupBy('p.asesor_id')
            ->get();

            //Para ALta cotizada y apartada
            $oportunidad_alta = DB::table('prospectos as p')
            ->select('p.nombre as nombre_prospecto', DB::raw('count(*) as cantidad'))
            ->where('p.fecha_registro','!=',null)
            ->get();
            $oportunidad_cotizacion = DB::table('prospectos as p')
            ->select('p.nombre as nombre_prospecto', DB::raw('count(*) as cantidad'))
            ->where('p.fecha_cotizacion','!=',null)
            ->get();
            $oportunidad_apartada = DB::table('prospectos as p')
            ->select('p.nombre as nombre_prospecto', DB::raw('count(*) as cantidad'))
            ->where('p.fecha_apartado','!=',null)
            ->get();

            //Para venta mensual
            $ventas_mensuales = DB::table('prospectos as p')
            ->select(DB::raw('count(*) as cantidad, sum(p.monto_venta) as suma_venta'))
            ->where('p.fecha_venta','!=',null)
            ->get();
        }

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $years = array();
        for ($i=2017; $i <= date('Y'); $i++) { 
            $years[] = $i;
        }
        $meses= array(['1','Enero'],['2','Febrero'],['3','Marzo'],['4','Abril'],['5','Mayo'],['6','Junio'],['7','Julio'],['8','Agosto'],['9','Septiembre'],['10','Octubre'],['11','Noviembre'],['12','Diciembre']);
        $ques = array(['Q1','01,02,03'],['Q2',"'04','05','06'"],['Q3',"'07','08','09'"],['Q4',"'10','11','12'"]);

        if ($yearMes == '-') {
            $yearMes = date('m-Y');
        }
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        //echo $filtro."<br/> ";
        // echo json_encode($oportunidad_cotizacion);
        // echo json_encode($oportunidad_apartada);
        return view('reportes.oportunidades.index', compact('estatus_prospecto','por_asesor','oportunidad_apartada','oportunidad_cotizacion','oportunidad_alta','ventas_mensuales','request','proyectos','rol','id','colores_A','years','meses','ques','yearMes'));
    }
    public function visitas( request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $year_bs = $request->get('year_bs');
        $estatus_propiedad_bs = $request->get('estatus_propiedad_bs');
        $nombre_bs = $request->get('nombre_bs');
        if ($year_bs == '' or $year_bs == null) {
            $year_bs = date('Y');
        }
        $filtro = 'p.fecha_visita is not null';
        $filtro_propiedad = 'prop.tipo_propiedad_id = 1';
        
        if ($nombre_bs) {
            if ($filtro_propiedad != '') {
              $filtro_propiedad = $filtro_propiedad . ' AND ';
            }
            $filtro_propiedad = $filtro_propiedad . ' prop.id_propiedad IN (';
            $i =0;
            foreach ($nombre_bs as $key) {
                if ($i == 0) {
                    $filtro_propiedad = $filtro_propiedad."'".$key."'";
                }else{
                    $filtro_propiedad = $filtro_propiedad.",'".$key."'";
                }
                $i++;
            }
            $filtro_propiedad = $filtro_propiedad .')';
        }
        if ($year_bs != '' and $year_bs != null) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' year(p.fecha_visita) = '.$year_bs;
        }
        if ($estatus_propiedad_bs != '' and $estatus_propiedad_bs != null and $estatus_propiedad_bs != 'all') {
            if ($filtro_propiedad != '') {
                $filtro_propiedad = $filtro_propiedad . ' AND ';
            }
            $filtro_propiedad = $filtro_propiedad . ' prop.estatus_propiedad_id IN (';
            $i =0;
            foreach ($estatus_propiedad_bs as $key) {
                if ($i == 0) {
                    $filtro_propiedad = $filtro_propiedad."'".$key."'";
                }else{
                    $filtro_propiedad = $filtro_propiedad.",'".$key."'";
                }
                $i++;
            }
            $filtro_propiedad = $filtro_propiedad .')';
        }
        if ($proyecto_bs != '' and $proyecto_bs != null and $proyecto_bs != 'all') {
            if ($filtro_propiedad != '') {
                $filtro_propiedad = $filtro_propiedad . ' AND ';
            }
            $filtro_propiedad = $filtro_propiedad . ' prop.proyecto_id IN (';
            $i =0;
            foreach ($proyecto_bs as $key) {
                if ($i == 0) {
                    $filtro_propiedad = $filtro_propiedad."'".$key."'";
                }else{
                    $filtro_propiedad = $filtro_propiedad.",'".$key."'";
                }
                $i++;
            }
            $filtro_propiedad = $filtro_propiedad .')';
        }
        $tabla = '';
        $meses= array(['1','Enero',0],['2','Febrero',0],['3','Marzo',0],['4','Abril',0],['5','Mayo',0],['6','Junio',0],['7','Julio',0],['8','Agosto',0],['9','Septiembre',0],['10','Octubre',0],['11','Noviembre',0],['12','Diciembre',0]);

        $propiedades = DB::table('propiedad as prop')
        ->join('estatus_propiedad as e','prop.estatus_propiedad_id','=','e.id_estatus_propiedad','left', false)
        ->select('prop.id_propiedad','prop.nombre as nombre_propiedad')
        ->whereRaw($filtro_propiedad)
        ->orderby('prop.nombre','ASC')
        ->get();
        $tabla = $tabla.'<thead class="bg-gray-600 center text-white"><tr><th valign="middle" rowspan="2">Propiedad</th><th colspan="13">Cantidad de visitas</th></tr><tr>';
        foreach ($meses as $m) {
            $tabla = $tabla.'<th>'.$year_bs.'/'.$m[0].'</th>';
        } 
        $tabla = $tabla.'<th>Total</th></tr></thead><tbody>';
        $total_acumulado = 0;
        foreach ($propiedades as $key) {
            $prospecto_validar = DB::table('prospectos as p')
            ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
            ->whereRaw($filtro)
            ->whereYear('p.fecha_visita','=',$year_bs)
            ->where('p.propiedad_id','=',$key->id_propiedad)
            ->first();
            if ($prospecto_validar->cantidad_visita > 0) {
                $tabla = $tabla . '<tr class="text-center"><td>'.$key->nombre_propiedad.'</td>';
                $total_por_propiedad = 0;
                foreach ($meses as $m) {
                    $prospecto_cta = DB::table('prospectos as p')
                    ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
                    ->whereRaw($filtro)
                    ->whereYear('p.fecha_visita','=',$year_bs)
                    ->whereMonth('p.fecha_visita','=',$m[0])
                    ->where('p.propiedad_id','=',$key->id_propiedad)
                    ->first();
                    if ($prospecto_cta) {
                        $tabla = $tabla . '<td>'.number_format($prospecto_cta->cantidad_visita, 2 , "." , ",").'</td>';
                        $total_por_propiedad = $total_por_propiedad + $prospecto_cta->cantidad_visita;
                    }else{
                        $tabla = $tabla . '<td>'.number_format(0, 2 , "." , ",").'</td>';
                        $total_por_propiedad = $total_por_propiedad + 0;
                    }
                }
                $tabla = $tabla . '<td style="font-weight: bold;">'.number_format($total_por_propiedad, 2 , "." , ",").'</td></tr>';
                $total_acumulado = $total_acumulado + $total_por_propiedad;
            }
        }
        $tabla = $tabla . '</tbody><tfoot style="font-weight: bold;"><tr class="text-center"><td>Total</td>';
        foreach ($meses as $m) {
            $prospecto_mes = DB::table('prospectos as p')
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad')
            ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
            ->whereRaw($filtro)
            ->whereRaw($filtro_propiedad)
            ->whereYear('p.fecha_visita','=',$year_bs)
            ->whereMonth('p.fecha_visita','=',$m[0])
            ->first();
            if ($prospecto_mes) {
                $tabla = $tabla .'<td>'.number_format($prospecto_mes->cantidad_visita, 2 , "." , ",").'</td>';
            }else{
                $tabla = $tabla .'<td>'.number_format(0, 2 , "." , ",").'</td>';

            }
        }
        $tabla = $tabla . '<td>'.number_format($total_acumulado, 2 , "." , ",").'</td></tr></tfoot>';
        

        $proyectos_filtro = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();
        $estatus_propiedad = DB::table('estatus_propiedad')
        ->select('id_estatus_propiedad', 'estatus_propiedad')
        ->get();

        $propiedades_filtro = DB::table('propiedad')
        ->select('id_propiedad','nombre')
        ->get();
        $years = DB::table('prospectos')
        ->select(DB::raw('YEAR(fecha_visita) as anio'))
        ->groupBy(DB::raw('YEAR(fecha_visita)'))
        ->get();

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        //echo $filtro."<br/> ";
        // echo json_encode($oportunidad_cotizacion);
        // echo json_encode($oportunidad_apartada);
        return view('reportes.visitas.index', compact('tabla','proyectos_filtro','estatus_propiedad','propiedades_filtro','request','rol','id','years'));
    }
    public function clientes_asesor( request $request){
        $asesor_bs =$request->get('asesor_bs');
        $fecha_minima_bs = $request->get('fecha_minima_bs');
        $fecha_maxima_bs = $request->get('fecha_maxima_bs');

        $filtro = 'p.fecha_registro is not null';
        if ($asesor_bs != '' and $asesor_bs != 'Vacio' and $asesor_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.asesor_id = '.$asesor_bs;
        }
        if ($fecha_maxima_bs != '' and $fecha_minima_bs != '' and $fecha_maxima_bs != null and $fecha_minima_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_registro between '".$fecha_minima_bs."' AND '". $fecha_maxima_bs."'";
        }


        $resultados = DB::table('prospectos as p')
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('p.asesor_id','u.name as nombre_asesor')
        ->whereRaw($filtro)
        ->groupBy('p.asesor_id')
        ->orderby('p.asesor_id')
        ->get();

        $tabla = '<thead class="bg-gray-600 text-white">
        <tr>
        <th>Asesor</th><th>Prospecto</th><th>Estatus</th><th>Fecha registro</th><th>Correo</th><th>Medio contacto</th><th>Propiedad</th>
        </tr>
        <thead><tbody>';

        foreach ($resultados as $k) {
            $resultados_asesor = DB::table('prospectos as p')
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left', false)
            ->join('estatus_crm as ep','p.estatus','=','ep.id_estatus_crm','left', false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('users as u','p.asesor_id','=','u.id','left',false)
            ->select('p.nombre as nombre_prospecto','medio_contacto_id','mc.medio_contacto','prop.nombre as nombre_propiedad','u.name as nombre_asesor','ep.estatus_crm','p.fecha_registro','p.correo')
            ->where('asesor_id', $k->asesor_id)
            ->whereRaw($filtro)
            ->orderby('p.fecha_registro')
            ->get();
            $tabla = $tabla.'
            <tr class="bg-gray-200 text-dark text-center" style="font-weight:bold;">
            <td colspan="7">'.$k->nombre_asesor.'</td>
            </tr>';
            $i = 0;
            foreach ($resultados_asesor as $a) {
                $tabla = $tabla.'
                <tr>
                <td>'.$a->nombre_asesor.'</td><td>'.$a->nombre_prospecto.'</td><td>'.$a->estatus_crm.'</td><td>'.date('Y-M-d',strtotime($a->fecha_registro)).'</td><td>'.$a->correo.'</td><td>'.$a->medio_contacto.'</td><td>'.$a->nombre_propiedad.'</td>
                </tr>';
                $i++;
            }
            $tabla = $tabla.'
            <tr class="bg-gray-100 text-dark text-center">
            <td colspan="5" style="text-align: right; font-weight:bold;">Total por asesor:</td>
            <td colspan="2">'.$i.'</td>
            </tr>';
        }

        $tabla = $tabla.'<tbody>';
        //por asesor
        $por_asesor = DB::table('prospectos as p')
        ->join('users as e','p.asesor_id','=','e.id','left', false)
        ->select('p.nombre as nombre_prospecto','e.email as label', DB::raw('count(*) as cantidad'))
        ->groupBy('p.asesor_id')
        ->whereRaw($filtro)
        ->get();

        $prospectos = DB::table('prospectos as p')
        ->select(DB::raw('count(*) as total_prospectos'))
        ->whereRaw($filtro)
        ->first();

        $asesores = DB::table('users')
        ->select('id', 'name','email')
        ->orderby('name','asc')
        ->get();

        $medios = DB::table('medio_contacto')->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");
        $total_prospectos = $prospectos->total_prospectos;

        return view('reportes.clientes_asesor.index', compact('resultados','request','asesores','tabla','total_prospectos','medios','colores_A','por_asesor'));

    }
}
