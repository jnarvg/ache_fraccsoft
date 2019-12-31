<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      if (auth()->user()->rol != 5) {
        $mes = date('m');
        $hoy = date('Y-m-d');
        $year = date('Y');
        DB::table('plazos_pago')
            ->where('estatus','Programado')
            ->whereMonth('fecha','=',$mes)
            ->whereYear('fecha','=',$year)
            ->where('saldo','>', 0)
            ->update(['estatus' => 'En curso']);
            
        DB::table('plazos_pago')
            ->whereIn('estatus',['Programado', 'En curso'])
            ->where('fecha','<',$hoy)
            ->where('saldo','>', 0)
            ->update(['estatus' => 'Vencido']);

        $prospecto_apartado = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Apartado')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_contrato = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Contrato')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_pagando = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Pagando')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_escriturado = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Escriturado')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_perdido = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Perdido')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_noescriturado = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','No escriturado')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_postergado = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->where('e.estatus_crm','=','Postergado')
          ->select(DB::raw('count(*) as contador'))
          ->first();
        $prospecto_prospecto = DB::table('prospectos as si')
          ->join('estatus_crm as e','si.estatus','=','e.id_estatus_crm','left',false)
          ->whereIn('e.estatus_crm', ['Cotizacion', 'Prospecto', 'Visita'])
          ->select(DB::raw('count(*) as contador'))
          ->first();

        $resultados = DB::table('prospectos')
        ->select('id_prospecto', DB::raw('count(*) as cantidad, MONTH(fecha_registro) as label '))
        ->whereYear('fecha_registro',$year)
        ->groupBy(DB::raw("MONTH(fecha_registro)"))
        ->get();

        ///echo "prospectos: ".$prospecto_prospecto->contador;
        return view('welcome',compact('prospecto_prospecto','prospecto_postergado','prospecto_apartado','prospecto_perdido','prospecto_noescriturado','prospecto_escriturado','prospecto_pagando','prospecto_contrato','resultados'));
      }elseif (auth()->user()->rol == 5) {
        $idprospecto = auth()->user()->prospecto_id;

        $prospecto = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
        ->join('moneda as m','p.moneda_id','=','m.id_moneda','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
        ->select('p.id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'fecha_recontacto','observaciones','fecha_visita','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','motivo_perdida','prop.precio','prop.enganche','p.esquema_comision_id','ec.esquema_comision','p.comision_id','extension','e.nivel','p.razon_social','p.domicilio','p.interes','p.mensualidad','p.capital','p.tipo','p.pagado','fecha_contrato','num_contrato','vigencia_contrato','p.saldo','porcentaje_interes','p.moneda_id','m.siglas')
        ->where('id_prospecto',$idprospecto)
        ->first();

        $documentos = DB::table('documento')
        ->select('id_documento','fecha', 'notas','titulo','archivo')
        ->where('prospecto_id','=',$idprospecto)
        ->get();

        $contactos = DB::table('contacto')
        ->select('id_contacto','nombre', 'telefono','correo')
        ->where('prospecto_id','=',$idprospecto)
        ->get();

        $tipos_operacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->orderby('tipo_operacion','ASC')
        ->get();

        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado')
        ->where('prospecto_id','=',$idprospecto)
        ->paginate(10);

        $resultados = DB::table('plazos_pago')
        ->select('id_plazo_pago','estatus', DB::raw('count(*) as cantidad'))
        ->where('prospecto_id','=',$idprospecto)
        ->groupBy('estatus')
        ->get();
        $colores_A = array("#AFEEEE","#7FFFD4","#40E0D0","#48D1CC","#00CED1","#5F9EA0","#4682B4","#B0C4DE","#B0E0E6","#ADD8E6","#87CEEB","#87CEFA","#00BFFF","#1E90FF","#6495ED","#7B68EE","#4169E1","#0000FF","#00008B","#000080","#191970");

        return view('externos.show',compact('prospecto','documentos','contactos','plazos_pago','tipos_operacion','resultados','colores_A'));
      }
    }
    public function catalogos()
    {
        return view('catalogos.index');
    }

    public function reportes()
    {
        return view('reportes.index');
    }
}
