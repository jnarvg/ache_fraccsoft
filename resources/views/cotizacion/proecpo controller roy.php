<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Prospecto;
use App\RequisitosProspecto;
use App\PlazosPago;
use App\Comision;
use App\Cotizacion;
use App\Propiedad;
use App\User;
use App\ComisionDetalle;
use App\DetalleCotizacion;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Exports\ProspectoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Middleware\Authenticate;
use PDF;
use Carbon\Carbon;

class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr =  $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decimales = $div_decimales[1];
            }
            if(strlen($decNumberStr) == 1){
                $decimales = $div_decimales[1].'0';
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = '.00';
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            if ($moneda == 'year') {
                $valor_convertido = $converted;
            }else{
                $valor_convertido = $converted .'PESOS 00/100 '.strtoupper($moneda);
            }
        } else {
           $valor_convertido = $converted .' PESOS '.$decimales . '/100 '.strtoupper($moneda);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}

class ProspectosController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function ultimodiaMes($fecha){

        $bisiesto = '0';
        $end = date('t', strtotime($fecha));

        return $end;
    }
    protected  function buscar_estatus_prospecto($estatus_str){
        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm')
        ->where('estatus_crm','=',$estatus_str)
        ->first();
        $id_estatus = $estatus_crm->id_estatus_crm;
        return $id_estatus;
    }
    protected  function buscar_estatus_propiedad($estatus_str){
        $estatus_propiedad = DB::table('estatus_propiedad')
            ->select('id_estatus_propiedad')
            ->where('estatus_propiedad','=',$estatus_str)
            ->first();
        $id_estatus = $estatus_propiedad->id_estatus_propiedad;
        return $id_estatus;
    }
    public function index(request $request)
    {
        $nombre = $request->get('nombre_bs');
        $estatus = $request->get('estatus_bs');
        $agente = $request->get('agente_bs');
        $correo = $request->get('correo_bs');
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($request);
        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.pagado','porcentaje_interes')
        ->orderby('fecha_registro','DESC')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        return view('prospectos.index', compact('prospectos','proyectos','medios_contacto','request','tipos','estatus_crm','rows_pagina','monedas','users'));
    }
    public function store(request $request)
    {
        $prospecto = new Prospecto();
        $prospecto->nombre = $request->get('nuevo_nombre_mdl');
        $prospecto->correo = $request->get('nuevo_correo_mdl');
        $prospecto->telefono = $request->get('nuevo_telefono_mdl');
        $prospecto->telefono_adicional = $request->get('nuevo_telefono_adicional_mdl');
        $prospecto->rfc = $request->get('nuevo_rfc_mdl');
        $prospecto->extension = $request->get('nuevo_extension_mdl');
        $prospecto->observaciones = $request->get('nuevo_observacion_mdl');
        $prospecto->fecha_registro = date('Y-m-d H:i:s');
        $prospecto->tipo = $request->get('tipo');
        $prospecto->razon_social = $request->get('razon_social');
        $prospecto->domicilio = $request->get('domicilio');
        $prospecto->interes = 0.00;
        $prospecto->capital = 0.00;
        $prospecto->mensualidad = 0.00;
        $prospecto->pagado = 0.00;
        $prospecto->porcentaje_interes = 0.00;
        $prospecto->medio_contacto_id = $request->get('nuevo_medio_contacto_mdl');
        if ($request->get('moneda') != 'Vacio') {
            $prospecto->moneda_id = $request->get('moneda');
        }
        if ($request->get('nuevo_proyecto_mdl') != 'sin proyecto') {
            $prospecto->proyecto_id = $request->get('nuevo_proyecto_mdl');
        }
        $prospecto->propiedad_id = $request->get('nuevo_propiedad_mdl');
        $prospecto->estatus = 1;
        $prospecto->asesor_id = auth()->user()->id;
        $prospecto->save();
        return back();
    }

    public function show($id, $ruta)
    {
        $prospecto = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
        ->join('moneda as m','p.moneda_id','=','m.id_moneda','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
        ->select('p.id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'fecha_recontacto','observaciones','fecha_visita','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','motivo_perdida','prop.precio','prop.enganche','p.esquema_comision_id','ec.esquema_comision','p.comision_id','extension','e.nivel', 'e.limite','p.razon_social','p.domicilio','p.interes','p.mensualidad','p.capital','p.tipo','p.pagado','fecha_contrato','num_contrato','vigencia_contrato','p.saldo','porcentaje_interes','p.moneda_id','m.siglas','p.nacionalidad','p.porcentaje_descuento','p.porcentaje_enganche','p.porcentaje_contraentrega','p.oficina_broker','p.nombre_broker','p.fecha_entrega_propiedad','p.esquema_pago','p.cotizacion_id','foto_prospecto', 'foto_prospecto_2','adicional_d','fecha_adicional','dia_pago')
        ->where('id_prospecto','=',$id)
        ->first();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $propiedades = DB::table('propiedad')
        ->select('id_propiedad', 'nombre')
        ->orderby('nombre','ASC')
        ->where('proyecto_id', $prospecto->proyecto_id)
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();

        $motivos_perdida = DB::table('motivo_perdida')
        ->select('id_motivo_perdida', 'motivo_perdida')
        ->orderby('motivo_perdida','ASC')
        ->get();

        $tipos_operacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->orderby('tipo_operacion','ASC')
        ->get();

        $mensajes = DB::table('mensaje as m')
        ->join('users as d','dirigido_id','=','d.id','left',false)
        ->join('users as r','creador_id','=','r.id','left',false)
        ->select('id_mensaje', 'titulo', 'nota','dirigido_id','creador_id','m.prospecto_id','m.fecha','m.estatus','d.name as dirigido','r.name as creador')
        ->orderby('fecha','DESC')
        ->where('m.prospecto_id', $id)
        ->get();

        $usuarios = DB::table('users')
        ->where('estatus','=','Activo')
        ->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm','estatus_crm')
        ->get();

        $requisitos = DB::table('requisitos')
        ->get();

        $requisitos_prospecto = DB::table('requisito_prospecto')
        ->select('id_requisito_prospecto','requisito','estatus')
        ->where('prospecto_id','=',$id)
        ->get();

        $actividades = DB::table('actividad')
        ->where('prospecto_id','=',$id)
        ->get();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto','nombre')
        ->where('estatus','!=',11)
        ->get();

        $documentos = DB::table('documento')
        ->select('id_documento','fecha', 'notas','titulo')
        ->where('prospecto_id','=',$id)
        ->get();

        $contactos = DB::table('contacto')
        ->select('id_contacto','nombre', 'telefono','correo')
        ->where('prospecto_id','=',$id)
        ->get();

        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado')
        ->where('prospecto_id','=',$id)
        ->get();

        $monedas = DB::table('moneda')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->get();

        $esquemas = DB::table('esquema_comision')
        ->select('id_esquema_comision','esquema_comision')
        ->get();

        $esquemas_pago = array('TRADICIONAL','CONTADO','CONSTRUCCIÓN','PERSONALIZADO MONTOS','PERSONALIZADO PORCENTAJE');
        $dias_pago = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','Ultimo dia del mes');
        $tipos = array('Fisica','Moral');
        $nacionalidades = array('Mexicana','Estadounidense','Colombiana','Hondureña','Cubana','Puertoriqueña');
        $id = auth()->user()->id;
        $rol = auth()->user()->rol;
        return view('prospectos.show', compact('prospecto','proyectos','medios_contacto','usuarios','tipos_operacion','motivos_perdida','requisitos','requisitos_prospecto','actividades','prospectos','documentos','contactos','plazos_pago','esquemas','estatus_crm','tipos','monedas','mensajes','nacionalidades','rol','id','ruta','propiedades','esquemas_pago','usos_propiedad','dias_pago'));
    }
    public function update(request $request, $id)
    {
        $prospecto = Prospecto::findOrFail($id);
        $prospecto->nombre = $request->get('nombre');
        $prospecto->correo = $request->get('correo');
        $prospecto->telefono = $request->get('telefono');
        $prospecto->telefono_adicional = $request->get('telefono_adicional');
        $prospecto->rfc = $request->get('rfc');
        $prospecto->extension = $request->get('extension');
        $prospecto->observaciones = $request->get('observaciones');
        $prospecto->tipo = $request->get('tipo');
        $prospecto->razon_social = $request->get('razon_social');
        $prospecto->domicilio = $request->get('domicilio');
        /*Numericos se deben de crar un strrepleace*/
        $prospecto->interes = floatval(str_replace(',', '', $request->get('interes'))); 
        $prospecto->capital = floatval(str_replace(',', '', $request->get('capital')));
        $prospecto->mensualidad = floatval(str_replace(',', '', $request->get('mensualidad')));
        $prospecto->pagado = floatval(str_replace(',', '', $request->get('pagado')));
        $prospecto->saldo = floatval(str_replace(',', '', $request->get('saldo')));
        $prospecto->porcentaje_interes = floatval(str_replace(',', '', $request->get('porcentaje_interes')));
        $prospecto->monto_apartado = floatval(str_replace(',', '', $request->get('monto_apartado')));
        /*fin numericos*/
        $prospecto->fecha_apartado = $request->get('fecha_apartado');
        $prospecto->fecha_entrega_propiedad = $request->get('fecha_entrega_propiedad');

        $prospecto->nacionalidad = $request->get('nacionalidad');
        $prospecto->medio_contacto_id = $request->get('medio_contacto');
        if ($request->get('moneda') != 'Vacio') {
            $prospecto->moneda_id = $request->get('moneda');
        }
        if ($request->get('proyecto') != 'sin proyecto') {
            $prospecto->proyecto_id = $request->get('proyecto');
        }
        $prospecto->propiedad_id = $request->get('propiedad');
        $prospecto->oficina_broker = $request->get('oficina_broker');
        $prospecto->nombre_broker = $request->get('nombre_broker');
        $prospecto->esquema_pago = $request->get('esquema_pago');
        $prospecto->dia_pago = $request->get('dia_pago');
        if (!empty($request->file('foto_prospecto'))) 
        {   
            $file = $request->file('foto_prospecto');
            $path = Storage::disk('public')->put('uploads',$file);

            $prospecto->foto_prospecto= $path;
        }
        if (!empty($request->file('foto_prospecto_2'))) 
        {   
            $file = $request->file('foto_prospecto_2');
            $path = Storage::disk('public')->put('uploads',$file);

            $prospecto->foto_prospecto_2 = $path;
        }
        $prospecto->update();
        //return back();
        return back();
    }
    public function destroy($id)
    {
        $prospecto = Prospecto::find($id);
        $estatus_prospecto = DB::table('estatus_crm')
        ->select('id_estatus_crm','estatus_crm')
        ->where('id_estatus_crm','=',$prospecto->estatus)
        ->first();
        if ($estatus_prospecto->estatus_crm == 'Prospecto' or $estatus_prospecto->estatus_crm == 'Inactivo' or $estatus_prospecto->estatus_crm == 'Perdido') {
            $prospecto->delete();
            return back();
        }else{
            return back()->with('msj','No se puede cancelar este prospecto, debe de estar estatus Inactivo o Prospecto');
        }
    }
    public function admin_estatus(request $request, $id)
    {
        $prospecto = Prospecto::findOrFail($id);
        $prospecto->estatus = $request->get('estatus_admin');
        $prospecto->update();
        return back();
    }
    public function regresar(request $request, $id)
    {
        $prospecto = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
        ->select('p.id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','u.name as nombre_agente', 'fecha_recontacto','observaciones','fecha_visita','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','prop.precio','prop.enganche','p.esquema_comision_id','ec.esquema_comision','p.comision_id','extension','e.nivel','porcentaje_interes')
        ->where('id_prospecto','=',$id)
        ->first();

        $estatusActual = $prospecto->estatus_prospecto; //obtenemos el estatus actual del prospecto
        $propiedad_id = $prospecto->propiedad_id;
        if ($estatusActual == 'Escriturado') {
            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('No escriturado');
            $prospecto->fecha_escrituracion = null;
            $propiedad = Propiedad::findOrFail($prospecto->propiedad_id);
                $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('No disponible');
                $propiedad->update();
            $prospecto->update();
        }elseif ($estatusActual == 'No escriturado') {
            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Pagando');
            $prospecto->update();
        }
        elseif ($estatusActual == 'Pagando') {
            $plazos_pago = DB::table('plazos_pago as si')
              ->where('si.prospecto_id','=',$id)
              ->where('si.estatus','Pagado')
              ->select(DB::raw('count(*) as contador'))
              ->first();
            if ($plazos_pago->contador >0){
                return back()->with('msj','Aun hay plazos con pagos activos, cancelelos primero.');
            }else{
                $prospecto = Prospecto::findOrFail($id);
                $prospecto->estatus = $this->buscar_estatus_prospecto('Contrato');
                $prospecto->fecha_venta = null;
                $prospecto->fecha_enganche = null;

                $propiedad = Propiedad::findOrFail($prospecto->propiedad_id);
                $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('No disponible');
                $propiedad->update();
                $prospecto->update();
                DB::table('plazos_pago')->where('estatus','!=','Pagado')->where('prospecto_id', '=', $id)->delete();
            }
        }
        elseif ($estatusActual == 'Contrato') {
            $propiedad = Propiedad::find($propiedad_id);
            $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('Apartada');
            $propiedad->update();

            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Recabando documentacion');
            $prospecto->fecha_contrato = null;
            $prospecto->vigencia_contrato = null;
            $prospecto->esquema_pago = null;
            $prospecto->porcentaje_interes = 0;
            $prospecto->porcentaje_descuento = 0;
            $prospecto->porcentaje_enganche = 0;
            $prospecto->porcentaje_contraentrega = 0;
            $prospecto->capital = 0;
            $prospecto->mensualidad = 0;
            $prospecto->num_plazos = 0;
            $prospecto->monto_venta = 0;
            $prospecto->monto_enganche = 0;
            $prospecto->dia_pago = null;
            $prospecto->update();
        }
        elseif ($estatusActual == 'Recabando documentacion') {

            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Apartado');
            $prospecto->fecha_venta = null;
            $prospecto->monto_venta = 0;
            $prospecto->monto_enganche = 0;
            $prospecto->fecha_enganche = null;

            $propiedad = Propiedad::findOrFail($prospecto->propiedad_id);
            $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('Apartada');
            $propiedad->update();
            $prospecto->update();
            DB::table('requisito_prospecto')->where('prospecto_id', '=', $id)->delete();
        }
        elseif ($estatusActual == 'Apartado') {
            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Cotizacion');
            $prospecto->fecha_apartado = null;
            $prospecto->monto_apartado = 0;
            $propiedad = Propiedad::findOrFail($prospecto->propiedad_id);
                $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('Disponible');
                $propiedad->update();
            $prospecto->update();
        }
        elseif ($estatusActual == 'Cotizacion') {

            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Visita');
            $prospecto->fecha_cotizacion = null;
            $prospecto->update();
        }
        elseif ($estatusActual == 'Visita') {
            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('Prospecto');
            $prospecto->fecha_visita = null;
            $prospecto->update();
        }
        else{
            return back()->with('msj','No se puede regresar más el prospecto.');
        }
        return back();
    }
    
    public function visita(request $request, $id)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Visita');
        $prospecto->propiedad_id = $request->get('propiedad_mdl_visita');
        if ($request->get('proyecto_mdl_visita') != 'sin proyecto') {
            $prospecto->proyecto_id = $request->get('proyecto_mdl_visita');
        }
        $prospecto->fecha_visita = date('Y-m-d H:i:s', strtotime($request->get('fecha_visita_mdl')));
        $prospecto->update();
        //return back();
        return back();
    }
    public function cotizar(request $request, $id){

        if ($request->get('propiedad_mdl_cotizar') == '' and $request->get('propiedad_mdl_cotizar') == null) {
            return back()->with('msj','Debe elegir almenos una propiedad primero.');
        }else{
            if (count( $request->get('propiedad_mdl_cotizar')) > 5) {
                return back()->with('msj','Esta limitado a 5 propiedades.');
            }else{
                $prospecto = Prospecto::find($id);
                $prospecto->estatus = $this->buscar_estatus_prospecto('Cotizacion');
                $prospecto->fecha_cotizacion = date('Y-m-d H:i:s', strtotime($request->get('fecha_cotizacion_cotizar')));
                $montoEnganche_D = floatval(str_replace(',', '', $request->get('monto_enganche_D')));
                $plazos = $request->get('plazos_cotizar');

                $id = auth()->user()->id;
                $agente = DB::table('users')
                ->where('id','=',$id)
                ->first();

                $porcentaje_enganche_A = round( $request->get('porcentaje_enganche_A'), 2);
                $porcentaje_enganche_B = round( $request->get('porcentaje_enganche_B'), 2);
                $porcentaje_enganche_C = round( $request->get('porcentaje_enganche_C'), 2);
                $porcentaje_enganche_D = 0;

                /*A CRÉDITO DE CONSTRUCCIÓN*/
                $porcentaje_descuento_A = round( $request->get('porcentaje_descuento_A'), 2);
                $porcentaje_contraentrega_A = round( $request->get('porcentaje_contraentrega_A'), 2);
                $porcentaje_mensualidad_A = round( 100 - ($porcentaje_contraentrega_A + $porcentaje_enganche_A), 2);

                /*B  ESQUEMA TRADICIONAL*/
                $porcentaje_descuento_B = round( $request->get('porcentaje_descuento_B'), 2);
                $porcentaje_contraentrega_B = round( $request->get('porcentaje_contraentrega_B'), 2);
                $porcentaje_mensualidad_B = round( 100 - ($porcentaje_contraentrega_B + $porcentaje_enganche_B), 2);

                /*C contado*/
                $porcentaje_descuento_C = round( $request->get('porcentaje_descuento_C'), 2);
                $porcentaje_contraentrega_C = round( $request->get('porcentaje_contraentrega_C'), 2);
                $porcentaje_mensualidad_C = round(100 - ($porcentaje_contraentrega_C + $porcentaje_enganche_C), 2);

                /*D PERSONALIZADO*/
                $porcentaje_descuento_D = round($request->get('porcentaje_descuento_D'), 2);
                $porcentaje_contraentrega_D = 0;
                $porcentaje_mensualidad_D = 0;
                $porcentaje_adicionales_D = 0;
                
                ///agente
                $nombreagente = $agente->name;
                $correoagente = $agente->email;
                $moneda  = $request->get('moneda_cotizar');
                $monedas = DB::table('moneda')
                ->select('siglas')
                ->where('id_moneda','=',$moneda)
                ->first();
                $siglas = $monedas->siglas;
                ////cliente
                $nombrecliente = $request->get('cliente_cotizar');
                $correocliente = $request->get('email_cotizar');
                $telefonocliente  = $request->get('telefono_cotizar');
                $fecha = $request->get('fecha_cotizacion_cotizar');

                ///propiedad
                $proyecto = DB::table('proyecto')
                ->select('id_proyecto','nombre','portada_cotizacion','logo_cotizacion')
                ->where('id_proyecto','=',$request->get('proyecto_mdl_cotizar'))
                ->first();

                $propiedades_request = $request->get('propiedad_mdl_cotizar');
                $nombres_propiedades = '';
                $hayOficina = 'No';
                $hayLocal = 'No';
                $hayDepartamento = 'No';

                foreach ($propiedades_request as $key) {
                    $propiedad = DB::table('propiedad as p')
                    ->select('p.nombre')
                    ->where('id_propiedad','=',$key)
                    ->first();

                    $nombres_propiedades = $nombres_propiedades.', '.$propiedad->nombre;
                    
                }
 
                
                /////   CREAR HISTORIAL
                    $folio_reconocimiento = uniqid();
                    $cotizacion = new Cotizacion;
                    $cotizacion->fecha_cotizacion = $fecha;
                    $cotizacion->proyecto = $proyecto->nombre;
                    $cotizacion->propiedad = substr($nombres_propiedades, 2);
                    $cotizacion->plazos = $plazos;
                    $cotizacion->cliente = $nombrecliente;
                    $cotizacion->correo = $correocliente;
                    $cotizacion->telefono = $telefonocliente;
                    $cotizacion->asesor_id = $id;
                    $cotizacion->moneda_id = $moneda;
                    $cotizacion->estatus = 'Abierta';
                    $cotizacion->porcentaje_pago_inicial_a = $porcentaje_enganche_A;
                    $cotizacion->porcentaje_pago_inicial_b = $porcentaje_enganche_B;
                    $cotizacion->porcentaje_pago_inicial_c = $porcentaje_enganche_C;
                    $cotizacion->porcentaje_pago_inicial_d = $porcentaje_enganche_D;
                    $cotizacion->monto_inicial_d = $montoEnganche_D;
                    $cotizacion->porcentaje_contraentrega_a = $porcentaje_contraentrega_A;
                    $cotizacion->porcentaje_contraentrega_b = $porcentaje_contraentrega_B;
                    $cotizacion->porcentaje_contraentrega_c = $porcentaje_contraentrega_C;
                    $cotizacion->porcentaje_contraentrega_d = $porcentaje_contraentrega_D;
                    $cotizacion->porcentaje_descuento_a = $porcentaje_descuento_A;
                    $cotizacion->porcentaje_descuento_b = $porcentaje_descuento_B;
                    $cotizacion->porcentaje_descuento_c = $porcentaje_descuento_C;
                    $cotizacion->porcentaje_descuento_d = $porcentaje_descuento_D;
                    $cotizacion->porcentaje_mensualidad_a = $porcentaje_mensualidad_A;
                    $cotizacion->porcentaje_mensualidad_b = $porcentaje_mensualidad_B;
                    $cotizacion->porcentaje_mensualidad_c = $porcentaje_mensualidad_C;
                    $cotizacion->porcentaje_mensualidad_d = $porcentaje_mensualidad_D;
                    $cotizacion->prospecto_id = $prospecto->id_prospecto;
                    $cotizacion->folio = $folio_reconocimiento;
                    $cotizacion->uso_propiedad_id = $request->get('uso_propiedad_id_mdl_cotizar');
                    $cotizacion->save();

                    $nueva = DB::table('cotizacion')
                    ->select('id_cotizacion')
                    ->where('folio',$folio_reconocimiento)->first();
                    $id_cotizacion_nueva = $nueva->id_cotizacion;
                    $prospecto->cotizacion_id = $id_cotizacion_nueva;
                    $prospecto->update();
                    foreach ($propiedades_request as $key) {
                        $propiedad = DB::table('propiedad as p')
                        ->select('id_propiedad','p.nombre','precio')
                        ->where('id_propiedad','=',$key)
                        ->first();

                        $nombres_propiedades = $nombres_propiedades.', '.$propiedad->nombre;
                        $precio = $propiedad->precio;
                        if ($montoEnganche_D != null and $montoEnganche_D != '') {
                            $porcentaje_enganche_D = round( ($montoEnganche_D / $precio) * 100, 2);
                        }else{
                            $porcentaje_enganche_D = $request->get('porcentaje_enganche_D');
                        }


                        $detalle = new DetalleCotizacion;
                        $detalle->propiedad_id = $propiedad->id_propiedad;
                        $detalle->precio_propiedad = $propiedad->precio;
                        $detalle->plazos = $plazos;
                        $detalle->inicial_a = $porcentaje_enganche_A;
                        $detalle->contraentrega_a = $porcentaje_contraentrega_A;
                        $detalle->mensualidades_a = $porcentaje_mensualidad_A;
                        $detalle->descuento_a = $porcentaje_descuento_A;

                        $detalle->inicial_b = $porcentaje_enganche_B;
                        $detalle->contraentrega_b = $porcentaje_contraentrega_B;
                        $detalle->mensualidades_b = $porcentaje_mensualidad_B;
                        $detalle->descuento_b = $porcentaje_descuento_B;

                        $detalle->inicial_c = $porcentaje_enganche_C;
                        $detalle->contraentrega_c = $porcentaje_contraentrega_C;
                        $detalle->mensualidades_c = $porcentaje_mensualidad_C;
                        $detalle->descuento_c = $porcentaje_descuento_C;

                        $detalle->monto_inicial_d = $montoEnganche_D;
                        $detalle->inicial_d = $porcentaje_enganche_D;
                        $detalle->contraentrega_d = $porcentaje_contraentrega_D;
                        $detalle->mensualidades_d = $porcentaje_mensualidad_D;
                        $detalle->adicionales_d = $porcentaje_adicionales_D;
                        $detalle->porcentaje_total_d = $porcentaje_enganche_D;
                        $detalle->descuento_d = $porcentaje_descuento_D;

                        $detalle->porcentaje_inicial_d = $porcentaje_enganche_D;
                        $detalle->porcentaje_contraentrega_d = 0;
                        $detalle->porcentaje_mensualidades_d = 0;
                        $detalle->porcentaje_adicionales_d = 0;
                        $detalle->porcentaje_descuento_d = $porcentaje_descuento_D;
                        $detalle->porcentaje2_total_d = $porcentaje_enganche_D;

                        $detalle->cotizacion_id = $id_cotizacion_nueva;
                        $detalle->save();
                    }

                //Lo mandamos a la cotzacionque acaba ee crear
                return redirect('/cotizacion/show/'.$id_cotizacion_nueva); 
            }
        }

    }
    public function apartar( request $request, $id)
    {   
        $prospecto = Prospecto::find($id);
        $propiedad_estatus = DB::table('prospectos')
        ->join('propiedad as prop','propiedad_id','=','id_propiedad','left',false)
        ->select('estatus_propiedad_id','propiedad_id')
        ->where('propiedad_id','=',$prospecto->propiedad_id)
        ->where('id_prospecto','!=',$prospecto->id_prospecto)
        ->where('estatus_propiedad_id','!=',1)
        ->get();

        if (count($propiedad_estatus) > 0)  {
            return back()->with('msj','No se puede cambiar a apartado, la propiedad elegida ya no esta disponible.');
        }else{

            $prospecto->estatus = $this->buscar_estatus_prospecto('Apartado');
            if ($request->get('proyecto_mdl_apartado') != 'sin proyecto') {
                $prospecto->proyecto_id = $request->get('proyecto_mdl_apartado');
            }
            $prospecto->propiedad_id = $request->get('propiedad_mdl');
            $prospecto->monto_apartado = floatval(str_replace(',', '', $request->get('montoapartado_mdl')));
            $prospecto->fecha_apartado = date('Y-m-d H:i:s');
            $prospecto->update();

            $estatus_propiedad = DB::table('estatus_propiedad')
            ->select('id_estatus_propiedad')
            ->where('estatus_propiedad','=','Apartada')
            ->first();

            $id_estatus_propiedad = $estatus_propiedad->id_estatus_propiedad;
            DB::table('propiedad')
            ->where('id_propiedad', $request->get('propiedad_mdl'))
            ->update(['estatus_propiedad_id' => $id_estatus_propiedad]);

            return back();

        }
    }

    public function contrato(request $request , $id)
    {
        $requisitos_pendientes = DB::table('requisito_prospecto')
        ->select('id_requisito_prospecto','estatus')
        ->where('estatus','=','Pendiente')
        ->where('prospecto_id','=',$id)
        ->get();
        $req_pendiente = 0;
        foreach ($requisitos_pendientes as $key) {
            $req_pendiente ++;
        }
        if ($req_pendiente == 0) { 
            $prospecto = Prospecto::find($id);
            if ($prospecto->propiedad_id != null and $prospecto->propiedad_id != '') {
                $propiedad_estatus = DB::table('prospectos')
                ->join('propiedad','propiedad_id','=','id_propiedad','left',false)
                ->select('estatus_propiedad_id','propiedad_id','id_prospecto')
                ->where('propiedad_id','=',$prospecto->propiedad_id)
                ->where('id_prospecto','!=',$prospecto->id_prospecto)
                ->whereNotIn('estatus', [1, 2, 3])/*Estatus prospecto igual  a prospecto*/
                ->where('estatus_propiedad_id','!=',1)
                ->get();

                if (count($propiedad_estatus) > 0)  {
                    ///si hay algun  prospeccto que tenga esa propiedad y que su estatus sea difernete a disponible ya no lo hace
                    return back()->with('msj','No se puede cambiar a contrato, la propiedad elegida ya no esta disponible.');
                }else{

                    $prospecto->estatus = $this->buscar_estatus_prospecto('Contrato');
                    $prospecto->num_contrato = $request->get('num_contrato_mdl');
                    $prospecto->fecha_contrato = $request->get('fecha_contrato_mdl');
                    $prospecto->vigencia_contrato = $request->get('fecha_contrato_mdl');
                    $prospecto->fecha_enganche = $request->get('fecha_enganche_mdl_c');
                    $prospecto->porcentaje_interes = $request->get('porcentaje_interes_mdl');
                    $prospecto->porcentaje_descuento = $request->get('porcentaje_descuento_mdl');
                    $prospecto->porcentaje_enganche = $request->get('porcentaje_enganche_mdl');
                    $prospecto->porcentaje_contraentrega = $request->get('porcentaje_contraentrega_mdl');
                    $prospecto->adicional_d = $request->get('porcentaje_adicional_mdl');
                    $prospecto->fecha_adicional = $request->get('fecha_adicional_mdl');
                    $prospecto->num_plazos = $request->get('num_plazos_mdl');
                    $prospecto->rfc = $request->get('rfc_mdl');
                    $prospecto->domicilio = $request->get('domicilio_mdl');
                    $prospecto->nacionalidad = $request->get('nacionalidad_mdl');
                    $prospecto->esquema_pago = $request->get('esquema_pago_mdl');
                    $prospecto->dia_pago = $request->get('dia_pago_mdl');

                    $monto_venta = floatval(str_replace(',', '', $request->get('monto_venta_mdl_contrato')));
                    $precio_final = round($monto_venta - ( $monto_venta * ( $request->get('porcentaje_descuento_mdl') / 100) ), 2);
                    $prospecto->monto_venta = $monto_venta;
                    /*oepraciones que se hacen en abse al precio final*/
                    $prospecto->capital = $precio_final;
                    $porcentaje_mensualidad = 100 - ($request->get('porcentaje_enganche_mdl') + $request->get('porcentaje_contraentrega_mdl') + $request->get('porcentaje_adicional_mdl'));
                    $prospecto->monto_enganche = round($precio_final * ($request->get('porcentaje_enganche_mdl') / 100), 2);
                    if ($request->get('num_plazos_mdl') > 0) {
                        $prospecto->mensualidad = round( ( $precio_final * ($porcentaje_mensualidad / 100)) / $request->get('num_plazos_mdl') , 2);
                    }else{
                       $prospecto->mensualidad = 0; 
                    }
                    $prospecto->update();
                    ///Cambiar la propeidad a no disponible
                    $propiedad = Propiedad::find($prospecto->propiedad_id);
                    $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('No disponible');
                    
                    $propiedad->update();

                    if ($request->get('crear_usuario') == 1) {
                        if ($prospecto->correo != 'correo@correo' and $prospecto->correo != '') {
                            $user = new User;
                            $user->name = $prospecto->nombre;//nombre_prospecto
                            $user->email = $prospecto->correo; //email_prospecto si no lo tiene no crear
                            $user->password = bcrypt('123456');
                            $user->rol = 5;//rol usuario externo
                            $folio_externo = uniqid();
                            $user->prospecto_id = $prospecto->id_prospecto;
                            $user->save();
                            $prospecto->cuenta_externa = 'Creada';
                            $prospecto->update();
                            return back()->with('msj','Se agrego usuario.');
                        }else{
                            return back()->with('msj','No se creo el usuario, no tiene un correo valido');
                        }
                    }else{
                        return back();
                    }

                }
            }else{
                return back()->with('msj','No se puede cambiar a contrato, el prospecto debe tener asignada una propiedad.');
            }
        }else{
            return back()->with('msj','No se puede cambiar a contrato, el prospecto aun tiene requisitos pendientes.');
        }
    }
    public function carga()
    {
        $prospectos = DB::table('prospectos')->where('id_prospecto','!=',3)->get();
        foreach ($prospectos as $key) {
            if ($key->estatus == 4 /*apartado*/) {
                $estatus_propiedad = DB::table('estatus_propiedad')
                ->select('id_estatus_propiedad')
                ->where('estatus_propiedad','=','Apartada')
                ->first();

                $id_estatus_propiedad = $estatus_propiedad->id_estatus_propiedad;
                DB::table('propiedad')
                ->where('id_propiedad', $key->propiedad_id)
                ->update(['estatus_propiedad_id' => $id_estatus_propiedad]);
            }elseif ($key->estatus == 6 /*Pagando*/) {
                $estatus_propiedad = DB::table('estatus_propiedad')
                ->select('id_estatus_propiedad')
                ->where('estatus_propiedad','=','No disponible')
                ->first();

                $id_estatus_propiedad = $estatus_propiedad->id_estatus_propiedad;
                DB::table('propiedad')
                ->where('id_propiedad', $key->propiedad_id)
                ->update(['estatus_propiedad_id' => $id_estatus_propiedad]);

                $num_plazos = $key->num_plazos;
                $fechaplazo = $key->fecha_venta;
                for ($i=0; $i < $num_plazos; $i++) {
                    $plazo_pago = new PlazosPago();
                    $plazo_pago->estatus = 'Programado';
                    $plazo_pago->total = $key->mensualidad;
                    $plazo_pago->fecha = $fechaplazo;
                    $plazo_pago->num_plazo = $i + 1;
                    $plazo_pago->prospecto_id = $key->id_prospecto;
                    $plazo_pago->saldo = $key->mensualidad;
                    $plazo_pago->monto_mora = 0;
                    $plazo_pago->pagado = 0;
                    $plazo_pago->save();
                    $actual = strtotime($fechaplazo);
                    $fechaplazo = date("Y-m-d", strtotime("+1 month", $actual));
                }
            }
        }

        return Redirect::to("prospectos");
    } 
    public function pagando(request $request, $id)
    {
        ///validamos que tenga sus pagos completados
        $requisitos_pendientes = DB::table('requisito_prospecto')
        ->select('id_requisito_prospecto','estatus')
        ->where('estatus','=','Pendiente')
        ->where('prospecto_id','=',$id)
        ->get();
        $req_pendiente = 0;
        foreach ($requisitos_pendientes as $key) {
            $req_pendiente ++;
        }
        if ($req_pendiente == 0) {
            $prospecto = Prospecto::find($id);
            if ($prospecto->propiedad_id != null) {
                //verifcamos que la propiedad este disponible aunque la tenga asganda otro prospecto
                $propiedad_estatus = DB::table('prospectos')
                ->join('propiedad','propiedad_id','=','id_propiedad','left',false)
                ->select('estatus_propiedad_id','propiedad_id')
                ->where('propiedad_id','=',$prospecto->propiedad_id)
                ->where('id_prospecto','!=',$prospecto->id_prospecto)
                ->where('estatus_propiedad_id','!=',1)
                ->whereNotIn('estatus', [1, 2, 3])/*Estatus prospecto igual  a prospecto*/
                ->get();

                if (count($propiedad_estatus) > 0)  {
                    ///si hay algun  prospeccto que tenga esa propiedad y que su estatus sea difernete a disponible ya no lo hace
                    return back()->with('msj','No se puede cambiar a pagando, la propiedad elegida ya no esta disponible.');
                }else{
                    $monto_venta_mdl = floatval(str_replace(',', '', $request->get('monto_venta_mdl')));

                    $monto_enganche_mdl = floatval(str_replace(',', '', $request->get('monto_enganche_mdl')));

                    $prospecto->estatus = $this->buscar_estatus_prospecto('Pagando');
                    $esquema = $request->get('esquema_comision_mdl');
                    $prospecto->fecha_venta = $request->get('fecha_venta_mdl');
                    $prospecto->monto_venta = $monto_venta_mdl;
                    $prospecto->fecha_enganche = $request->get('fecha_enganche_mdl');
                    /*Lo guardamos en variables mas cortas*/
                    $porcentaje_descuento = $request->get('porcentaje_descuento_mdl_pagando');
                    $porcentaje_enganche = $request->get('porcentaje_enganche_mdl_pagando');
                    $porcentaje_contraentrega = $request->get('porcentaje_contraentrega_mdl_pagando');
                    $porcentaje_adicional = $request->get('porcentaje_adicional_mdl_pagando');
                    $esquema_pago = $request->get('esquema_pago_mdl_pagando');
                    /*Asignamos*/
                    $prospecto->porcentaje_descuento = $porcentaje_descuento;
                    $prospecto->porcentaje_enganche = $porcentaje_enganche;
                    $prospecto->porcentaje_contraentrega = $porcentaje_contraentrega;
                    $prospecto->adicional_d = $porcentaje_adicional;
                    $num_plazos = $request->get('num_plazos_mdl');

                    if ($prospecto->porcentaje_interes > 0) {
                        $porcentaje_interes = $prospecto->porcentaje_interes / 100;
                    }else{
                        $porcentaje_interes = 0;
                    }
                    $monto_apartado = $prospecto->monto_apartado;
                    $fecha_apartado = $prospecto->fecha_apartado;
                    $fecha_enganche = $request->get('fecha_enganche_mdl');

                    /*Calcualndo los monots en base a porcentajes*/
                    $monto_venta = $monto_venta_mdl;
                    $precio_final = round($monto_venta - ( $monto_venta * ( $porcentaje_descuento / 100) ), 2);
                    /*oepraciones que se hacen en abse al precio final*/

                    $porcentaje_mensualidad = 100 - ($porcentaje_enganche + $porcentaje_contraentrega + $porcentaje_adicional);
                    $monto_enganche = round($precio_final * ($porcentaje_enganche / 100), 2);
                    $monto_adicional = round($precio_final * ($porcentaje_adicional / 100), 2);
                    $prospecto->monto_enganche = $monto_enganche;

                    if ($esquema_pago  == 'CONTADO' and $num_plazos >0 ) {
                        $mensualidad = round($monto_enganche / $num_plazos, 2);
                    }else{
                        $mensualidad = round( ( $precio_final * ($porcentaje_mensualidad / 100)) / $num_plazos , 2);
                    }
                    $prospecto->mensualidad = $mensualidad;
                    
                    if($request->get('considerar_apartado') == 1 and $request->get('considerar_enganche') == 1){
                        $restoventa = ($precio_final - $monto_apartado - $monto_enganche);
                        $montoInteres = $restoventa * $porcentaje_interes;
                        $total = $restoventa + $montoInteres;
                        $totalPlazo = $total / $num_plazos;
                        $capitalPlazo = $restoventa / $num_plazos;
                        $interesPlazo = $montoInteres / $num_plazos;
                    }
                    elseif($request->get('considerar_apartado') == 1)
                    {
                        $restoventa = ($precio_final - $monto_apartado);
                        $montoInteres = $restoventa * $porcentaje_interes;
                        $interesPlazo = $montoInteres / $num_plazos;
                    }
                    elseif($request->get('considerar_enganche') == 1)
                    {
                        $restoventa = ($precio_final - $monto_enganche);
                        $montoInteres = $restoventa * $porcentaje_interes;
                        $interesPlazo = $montoInteres / $num_plazos;
                    }
                    else
                    {
                        $restoventa = $precio_final;
                        $montoInteres = $restoventa * $porcentaje_interes;
                        $interesPlazo = $montoInteres / $num_plazos;
                    }

                    $prospecto->saldo = $precio_final + $montoInteres;
                    $prospecto->interes = $montoInteres;
                    $prospecto->capital = $precio_final;

                    $prospecto->tipo_operacion_id = $request->get('tipo_operacion_mdl');
                    if ($esquema != 'Sin comisiones') {
                        $prospecto->esquema_comision_id = $request->get('esquema_comision_mdl');
                    }
                    $prospecto->num_plazos = $request->get('num_plazos_mdl');
                    $prospecto->cerrador = $request->get('cerrador_mdl');
                    $prospecto->moneda_id = $request->get('moneda_mdl');
                    $prospecto->update();

                    $asesor_id = $prospecto->asesor_id;
                    $propiedad_id = $prospecto->propiedad_id;
                    ///Cambiar la propiedad a no disponible
                    $propiedad = Propiedad::find($propiedad_id);
                    $propiedad->estatus_propiedad_id = $this->buscar_estatus_propiedad('No disponible');
                    $propiedad->update();
                    $dia_pago_p = $prospecto->dia_pago;
                    if ($request->get('crear_plazos') == 'si') {
                        if ($num_plazos> 0) {
                            /*La fecha inicial debe ser de a fecha del enganche pero del dia 30 de ese mes y lsoq ue siguen dbene ser os demas dias 30*/
                            if ($dia_pago_p == 'Ultimo dia del mes') {
                                $dia_pago = $this->ultimodiaMes($fecha_enganche);
                            }else{
                                $dia_pago = $dia_pago_p;
                            }
                            $fecha = date('Y-m-'.$dia_pago,strtotime($fecha_enganche));
                            $inicio_plazo = 1;
                            if($request->get('considerar_apartado') == 1)
                            {
                                /*CREAMOS EL PLAZO CERO*/
                                $plazo_pago = new PlazosPago();
                                $plazo_pago->prospecto_id = $id;
                                $plazo_pago->fecha = $fecha_apartado;
                                $plazo_pago->estatus = 'Pendiente'; /*En lugar de programado*/
                                $plazo_pago->num_plazo = 0;
                                
                                $plazo_pago->total = round($monto_apartado, 2);
                                $plazo_pago->saldo = round($monto_apartado, 2);
                                $plazo_pago->pagado = 0;
                                $plazo_pago->interes = round(0, 2);
                                $plazo_pago->capital = round($monto_apartado, 2);
                                $plazo_pago->capital_inicial = round($monto_apartado, 2);
                                $plazo_pago->dias_retraso = 0;
                                $plazo_pago->monto_mora = 0;
                                $plazo_pago->notas = 'Apartado';
                            
                                $plazo_pago->moneda_id = $request->get('moneda_mdl');
                                $plazo_pago->save();
                            }
                            if($request->get('considerar_enganche') == 1 and $esquema_pago != 'CONTADO')
                            {
                                /*CREAMOS EL PLAZO UNO Y LOS RESTANTES SEGUIR DESDE EL DOS*/
                                $plazo_pago = new PlazosPago();
                                $plazo_pago->prospecto_id = $id;
                                $plazo_pago->fecha = $fecha; /*Fecha dia 30 conel mes del enganche*/
                                $plazo_pago->estatus = 'Pendiente'; /*En lugar de programado*/
                                $plazo_pago->num_plazo = 1;
                                
                                $plazo_pago->total = round($monto_adicional, 2);
                                $plazo_pago->saldo = round($monto_adicional, 2);
                                $plazo_pago->pagado = 0;
                                $plazo_pago->interes = round(0, 2);
                                $plazo_pago->capital = round($monto_adicional, 2);
                                $plazo_pago->capital_inicial = round($monto_adicional, 2);
                            
                                $plazo_pago->notas = 'Adicional';
                                $plazo_pago->dias_retraso = 0;
                                $plazo_pago->monto_mora = 0;
                                $plazo_pago->moneda_id = $request->get('moneda_mdl');
                                $plazo_pago->save();

                                $inicio_plazo = 2;
                            }
                            if($porcentaje_adicional > 0)
                            {
                                /*CREAMOS EL PLAZO UNO Y LOS RESTANTES SEGUIR DESDE EL DOS*/
                                $plazo_pago = new PlazosPago();
                                $plazo_pago->prospecto_id = $id;
                                $plazo_pago->fecha = $prospecto->fecha_adicional; /*Fecha dia 30 conel mes del enganche*/
                                $plazo_pago->estatus = 'Pendiente'; /*En lugar de programado*/
                                $plazo_pago->num_plazo = 1;
                                
                                $plazo_pago->total = round($monto_enganche, 2);
                                $plazo_pago->saldo = round($monto_enganche, 2);
                                $plazo_pago->pagado = 0;
                                $plazo_pago->interes = round(0, 2);
                                $plazo_pago->capital = round($monto_enganche, 2);
                                $plazo_pago->capital_inicial = round($monto_enganche, 2);
                            
                                $plazo_pago->notas = 'Pago adicional a capital';
                                $plazo_pago->dias_retraso = 0;
                                $plazo_pago->monto_mora = 0;
                                $plazo_pago->moneda_id = $request->get('moneda_mdl');
                                $plazo_pago->save();

                                $inicio_plazo = 3;
                            }else{
                                /*CREAMOS LOS PLAZOS DESDE UNO SIGNIFICA QUE NO TIENE ENGANCHE */
                                $inicio_plazo = 1;
                            }

                            if (date('m', strtotime($fecha)) == '01') {
                                $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
                                
                            }else{
                                if($dia_pago == '31'){
                                    $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
                                }
                                else{
                                    $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
                                }
                            }
                            //echo "<br/> Fecha entra: ".$fecha;
                            if ($dia_pago_p == 'Ultimo dia del mes') {
                                $dia_pago = $this->ultimodiaMes($fecha);
                                //echo "<br/>Dia pago FOR: ".$dia_pago;
                            }else{
                                $dia_pago = $dia_pago_p;
                            }

                            $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
                            //$fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
                            /*Le agregamos un ma suno para que si nos dibuje el numero de plazos aun que cosidere el encganhe*/
                            if ($num_plazos > 0 ) {
                                # code...
                                for ($i=$inicio_plazo; $i <= ($num_plazos+($inicio_plazo - 1)); $i++) 
                                {
                                    $plazo_pago = new PlazosPago();
                                    $plazo_pago->prospecto_id = $id;
                                    $plazo_pago->fecha = $fecha;
                                    $plazo_pago->estatus = 'Pendiente';
                                    $plazo_pago->num_plazo = $i;
                                    
                                    $plazo_pago->total = round($mensualidad + $interesPlazo, 2);
                                    $plazo_pago->saldo = round($mensualidad + $interesPlazo, 2);
                                    $plazo_pago->pagado = 0;
                                    $plazo_pago->interes = round($interesPlazo, 2);
                                    $plazo_pago->capital = round($mensualidad, 2);
                                    $plazo_pago->capital_inicial = round($mensualidad, 2);
                                    if ($esquema_pago == 'CONTADO') {
                                        # code...
                                        $plazo_pago->notas = 'Decidieron pagar el pago incial a mensualidades.';
                                    }
                                    $plazo_pago->dias_retraso = 0;
                                    $plazo_pago->monto_mora = 0;
                                    $plazo_pago->moneda_id = $request->get('moneda_mdl');
                                    $plazo_pago->save();

                                    if (date('m', strtotime($fecha)) == '01') {
                                        $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
                                        
                                    }else{
                                        if($dia_pago == '31'){
                                            $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
                                        }
                                        else{
                                            $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
                                        }
                                    }
                                    //echo "<br/> Fecha entra: ".$fecha;
                                    if ($dia_pago_p == 'Ultimo dia del mes') {
                                        $dia_pago = $this->ultimodiaMes($fecha);
                                        //echo "<br/>Dia pago FOR: ".$dia_pago;
                                    }else{
                                        $dia_pago = $dia_pago_p;
                                    }
                                    $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
                                }
                            }
                            /*Creamos el plazo de contra entrega*/
                            $contraentrega = round( $precio_final * ($porcentaje_contraentrega / 100), 2); 
                            $plazo_pago = new PlazosPago();
                            $plazo_pago->prospecto_id = $id;
                            $plazo_pago->fecha = $fecha;
                            $plazo_pago->estatus = 'Pendiente';
                            $plazo_pago->num_plazo = $i;
                            
                            $plazo_pago->total = round($contraentrega, 2);
                            $plazo_pago->saldo = round($contraentrega, 2);
                            $plazo_pago->pagado = 0;
                            $plazo_pago->interes = round(0, 2);
                            $plazo_pago->capital = round($contraentrega, 2);
                            $plazo_pago->capital_inicial = round($contraentrega, 2);
                        
                            $plazo_pago->dias_retraso = 0;
                            $plazo_pago->monto_mora = 0;
                            $plazo_pago->notas = 'Contra entrega';
                            $plazo_pago->moneda_id = $request->get('moneda_mdl');
                            $plazo_pago->save();
                        }
                    }
                    
                    if ($esquema != 'Sin comisiones') {
                        $comision = new Comision();
                        $comision->cliente_id = $id;
                        $comision->propiedad_id = $propiedad_id;
                        $comision->estatus = 'En aprobacion';
                        $comision->estatus_pago = 'Pendiente pago';
                        $comision->monto_operacion = $precio_final;
                        $comision->fecha_venta = $request->get('fecha_venta_mdl');
                        $esquema = $request->get('esquema_comision_mdl');

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
                        $id_comision_padre = $comisiones->id_comision;
                        $sumaTotal = 0;
                        foreach ($detalles_comisiones as $key) {
                            $comision_detalle = new ComisionDetalle;
                            $comision_detalle->rubro = $key->rubro;
                            $comision_detalle->tipo = $key->tipo;
                            $tipo = $key->tipo;
                            if ($tipo == 'Asesor') {
                                $comision_detalle->usuario_id = $asesor_id;
                                $persona = DB::table('users')
                                ->select('id','name')
                                ->where('id' ,'=', $asesor_id)
                                ->first();
                                $comision_detalle->persona = $persona->name;
                            }
                            elseif ($tipo == 'Cerrador') {
                                $comision_detalle->usuario_id = $request->get('cerrador_mdl');
                                $persona = DB::table('users')
                                ->select('id','name')
                                ->where('id' ,'=', $request->get('cerrador_mdl'))
                                ->first();
                                $comision_detalle->persona = $persona->name;
                            }
                            elseif ($tipo == 'Otro') {
                                $comision_detalle->usuario_id = $key->usuario;
                                $comision_detalle->persona = $key->persona;
                            }
                            elseif ($tipo == 'Externo') {
                                $comision_detalle->persona = $key->persona;
                            }
                            
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
                            $comision_detalle->comision_id = $id_comision_padre;
                            $comision_detalle->save();

                        }
                        $comision = Comision::findOrFail($id_comision_padre);
                        $comision->comision_total = $sumaTotal;
                        $comision->saldo_comision = $sumaTotal;
                        $comision->save();

                        $prospecto = Prospecto::find($id);
                        $prospecto->comision_id = $id_comision_padre;
                        $prospecto->update();
                    }

                    return back()->with('msj','Prospecto actualizado.');
                }
            }else{
                return back()->with('msj','Debe tener seleccionada la propiedad.');
            }
        }else{
            return back()->with('msj','No se puede cambiar a pagando, el prospecto aun tiene requisitos pendientes.');
            
        }
        //return json_encode($prospecto);
    }
    public function entregar_propiedad(request $request, $id)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->fecha_entrega_propiedad = $request->get('fecha_entrega_propiedad_mdl');
        $prospecto->update();
        //return back();
        return back();
    }
    public function escriturar($id)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Escriturado');
        $prospecto->fecha_escrituracion = date('Y-m-d H:i:s');
        $prospecto->update();
        //return back();
        return back();
    }
    public function no_escriturar($id)
    {
        $plazos= DB::table('plazos_pago')
          ->where('estatus','=','Programado')
          ->select(DB::raw('count(*) as plazospendientes'))
          ->first();
        $plazospendientes = $plazos->plazospendientes;
        if ($plazospendientes > 0) {
            return back()->with('msj','El prospecto aun tiene plazos pendientes de pago.');
        }else{

            $prospecto = Prospecto::find($id);
            $prospecto->estatus = $this->buscar_estatus_prospecto('No escriturado');
            $prospecto->update();
            return back();
        }
        //return back();
    }
    public function perder(request $request, $id)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Perdido');
        $prospecto->motivo_perdida_id = $request->get('motivo_perdida_mdl');
        $prospecto->update();
        return back();
        //return back();
    }
    public function postergar(request $request, $id)
    {
        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm')
        ->where('estatus_crm','=','Postergado')
        ->first();
        $id_estatus = $estatus_crm->id_estatus_crm;

        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Postergado');
        $prospecto->fecha_recontacto = $request->get('fecha_recontacto_mdl');
        $prospecto->update();
        //return json_encode($prospecto);
        //return back();
        return back();
    }
    public function reactivar($id)
    {

        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Prospecto');
        $prospecto->update();
        //return back();
        return back();
    }
    public function cambiar_asesor($id, request $request)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->asesor_id = $request->get('nuevo_asesor');
        $prospecto->update();
        return back();
        //return back();
    }
    public function cancelar($id)
    {
        $prospecto = Prospecto::find($id);
        $prospecto->estatus = $this->buscar_estatus_prospecto('Inactivo');

        $estatus_propiedad = DB::table('estatus_propiedad')
        ->select('id_estatus_propiedad')
        ->where('estatus_propiedad','=','Disponible')
        ->first();

        $id_estatus_propiedad = $this->buscar_estatus_prospecto('Disponible');
        DB::table('propiedad')
        ->where('id_propiedad', $prospecto->propiedad_id)
        ->update(['estatus_propiedad_id' => $id_estatus_propiedad]);

        DB::table('plazos_pago')
        ->where('prospecto_id', $prospecto->id_prospecto)
        ->update(['estatus' => 'Inactivo']);

        $prospecto->update();
        //return back();
        return back();
    }

    public function cargar_requisitos(request $request, $id)
    {   
        $requisitos_prospectos = DB::table('requisito_prospecto')
        ->select('id_requisito_prospecto','estatus')
        ->where('prospecto_id','=',$id)
        ->get();
        $req_pendiente = 0;
        foreach ($requisitos_prospectos as $key) {
            $req_pendiente ++;
        }
        if ($req_pendiente == 0) { 
            $requisitos_detalle = DB::table('requisitos_detalle')
            ->select('requisito')
            ->where('requisito_id','=',$request->get('requisitosmdl'))
            ->get();
            foreach ($requisitos_detalle as $requisito) {
                $requisito_prospecto = new RequisitosProspecto();
                $requisito_prospecto->requisito = $requisito->requisito;
                $requisito_prospecto->estatus = 'Pendiente';
                $requisito_prospecto->prospecto_id = $id;
                $requisito_prospecto->save();
            }
            $id_estatus = $this->buscar_estatus_prospecto('Recabando documentacion');
            $prospecto = Prospecto::findOrFail($id);
            $prospecto->estatus = $id_estatus;
            $prospecto->update();
            return back();
        }
        else{
            return back()->with('msj','Ya hay requisitos cargados para este prospecto.');
            
        }
        //return json_encode($requisitos_detalle);
    }
    public function crearContrato(request $request, $id)
    {
        $prospecto = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
        ->select('p.id_prospecto', 'p.nombre','razon_social','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'fecha_recontacto','observaciones','fecha_visita','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','motivo_perdida','prop.precio','prop.enganche','p.esquema_comision_id','ec.esquema_comision','p.comision_id','extension','e.nivel','p.razon_social','p.domicilio','p.interes','p.mensualidad','p.capital','p.tipo','p.pagado','fecha_contrato','num_contrato','vigencia_contrato','p.num_plazos','p.nacionalidad','porcentaje_enganche','porcentaje_descuento','porcentaje_contraentrega','esquema_pago','foto_prospecto','foto_prospecto_2','adicional_d','fecha_adicional','dia_pago','p.fecha_contraentrega')
        ->where('id_prospecto','=',$id)
        ->first();
        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado','monto_mora','dias_retraso','notas','interes','interes_acumulado','capital_acumulado','total_acumulado','deuda','amortizacion','capital','capital_inicial','moneda_id')
        ->where('prospecto_id','=',$id)
        ->get();

        ///propiedad
        $proyecto = DB::table('proyecto')
        ->select('id_proyecto','nombre','header_contrato','footer_contrato')
        ->where('id_proyecto','=',$prospecto->proyecto_id)
        ->first();
        $propiedad = DB::table('propiedad as p')
        ->join('pais as pa','p.pais_id','=','pa.id_pais','left',false)
        ->join('estado as e','p.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','p.ciudad_id','=','c.id_ciudad','left',false)
        ->join('nivel as n','p.nivel_id','=','n.id_nivel','left',false)
        ->join('tipo_modelo as tm','p.tipo_modelo_id','=','tm.id_tipo_modelo','left',false)
        ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->select('id_propiedad','nombre','direccion','pa.pais','e.estado','c.ciudad','p.precio','metros_contrato','terreno_metros','construccion_metros','enganche','tm.tipo_modelo','n.nivel','n.plano as nivel_foto','ep.estatus_propiedad','precio','mts_interior','mts_exterior','mts_total','up.uso_propiedad', 'cajones_estacionamiento')
        ->where('id_propiedad','=',$prospecto->propiedad_id)
        ->first();
        $empresa = DB::table('empresa')
        ->select('id_empresa','razon_social')
        ->first();

        if (empty($empresa)) {
            return back()->with('msj','Aun no has dado de alta una empresa');
        }
        $lugar = 'Guadalajara, Jalisco, México';
        $nombreagente = $prospecto->nombre_agente;
        $esquema_pago = $prospecto->esquema_pago;
        $dia_pago_p = $prospecto->dia_pago;

        
        $anexoC_1 = $prospecto->foto_prospecto;
        $anexoC_2 = $prospecto->foto_prospecto_2;
        $nombrepropiedad = $propiedad->nombre;
        $nivel_array = explode(' - ', $propiedad->nivel);
        $nivel = $nivel_array[0];
        $tipo_modelo = $propiedad->tipo_modelo;
        $mts_total = $propiedad->mts_total;
        $cajones_estacionamiento = $propiedad->cajones_estacionamiento;
        $direccionpropiedad = $propiedad->direccion.', '.$propiedad->ciudad.', '.$propiedad->estado.', '.$propiedad->pais;
        $precio_propiedad = round( $propiedad->precio - ($propiedad->precio * ($prospecto->porcentaje_descuento /100)), 2);
        $uso_propiedad = $propiedad->uso_propiedad;
        $imagen_propiedad = DB::table('imagen_propiedad')
            ->select('titulo','imagen_path')
            ->where('propiedad_id',$prospecto->propiedad_id)
            ->get();
        $imagen_path = '';
        $nivel_path = '';
        foreach ($imagen_propiedad as $key) {
            if (strpos($key->titulo, 'Info') !== false) {
                $imagen_path = $key->imagen_path;
            }
            if (strpos($key->titulo, 'Nivel') !== false) {
                $nivel_path = $key->imagen_path;
            }
        }
        /*Condiciones de entrega*/
        $condiciones_entrega = array('Puertas exteriores e interiores.','Piso tipo porcelanto.','Luminarias en plafón. No incluye luminarias decorativas.','Muros divisorios de block acabado liso y pintura.','Muros interiores de tablaroca, acabado liso y pintura','Zoclos.','Barandal de vidrio templado y aluminio en terraza.','Equipo de aire acondicionado.','Instalaciones hidráulicas y eléctricas.','Centro de carga y accesorios eléctricos.','Muebles y accesorios de baño.');
        if ($uso_propiedad == 'Local comercial') {
            $objetoAdjudicado = 'EL LOCAL';
            $condiciones_entrega = array('Puertas exteriores e interiores.','Piso tipo porcelanto.','Luminarias en plafón. No incluye luminarias decorativas.','Muros divisorios de block acabado liso y pintura.','Muros interiores de tablaroca, acabado liso y pintura','Zoclos.','Barandal de vidrio templado y aluminio en terraza.','Equipo de aire acondicionado.','Instalaciones hidráulicas y eléctricas.','Centro de carga y accesorios eléctricos.','Muebles y accesorios de baño.');
            $condiciones_entrega[] = 'Obra gris en el interior con fachada exterior terminada.'; 
            $condiciones_entrega[] = 'Puntas de los servicios al pie del local.';
            $li_condiciones ='';
            foreach ($condiciones_entrega as $key) {
                $li_condiciones = $li_condiciones.'<li>'.$key.'</li>';
            }
            $tercerpagina_html = '<div style="color: #150703;">
                        <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA LOCAL COMERCIAL</p>
                        <p></p>
                        <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>
                        </div>';
        }
        elseif ($uso_propiedad == 'Departamento') {
            $objetoAdjudicado = 'EL DEPARTAMENTO';
            $condiciones_entrega = array('Puertas exteriores e interiores.','Piso tipo porcelanto.','Luminarias en plafón. No incluye luminarias decorativas.','Muros divisorios de block acabado liso y pintura.','Muros interiores de tablaroca, acabado liso y pintura','Zoclos.','Barandal de vidrio templado y aluminio en terraza.','Equipo de aire acondicionado.','Instalaciones hidráulicas y eléctricas.','Centro de carga y accesorios eléctricos.','Muebles y accesorios de baño.','Sistema de doble ventana de control solar, térmico, ahorrador de energía y control de acústica.');
            $li_condiciones ='';
            foreach ($condiciones_entrega as $key) {
                $li_condiciones = $li_condiciones.'<li>'.$key.'</li>';
            }
            $tercerpagina_html = '<div style="color: #150703;">
                        <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA DEL DEPARTAMENTO</p>
                        <p></p>
                        <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>
                        </div>';
        }
        elseif ($uso_propiedad == 'Oficina') {
            $objetoAdjudicado = 'LA OFICINA';
            $condiciones_entrega = array('Obra gris en el interior.','Ventanas de fachada exterior terminadas.','Instalaciones eléctricas, voz y datos al pie del área privativa','Equipo de aire acondicionado.','Preparación para sistema contra incendio');
            $li_condiciones ='';
            foreach ($condiciones_entrega as $key) {
                $li_condiciones = $li_condiciones.'<li>'.$key.'</li>';
            }
            $tercerpagina_html = '<div style="color: #150703;">
                <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA OFICINA</p>
                <p></p>
                <ol type="a" style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>
                <p style="font-weight:bold;">-Mantemiento</p>
                <p style="font-weight:normal;">Por definir y de acuerdo a mercado</p>
                <p style="font-weight:bold;">-Estacionamiento</p>
                <p style="font-weight:normal;">Ratio de estacionamiento de 1 cajón cada 25m2.</p>
                </div>';
        }else{
            $objetoAdjudicado = 'OBJETO ADJUDICADO';
            $condiciones_entrega = array('Puertas exteriores e interiores.','Piso tipo porcelanto.','Luminarias en plafón. No incluye luminarias decorativas.','Muros divisorios de block acabado liso y pintura.','Muros interiores de tablaroca, acabado liso y pintura','Zoclos.','Barandal de vidrio templado y aluminio en terraza.','Equipo de aire acondicionado.','Instalaciones hidráulicas y eléctricas.','Centro de carga y accesorios eléctricos.','Muebles y accesorios de baño.');
            $li_condiciones ='';
            foreach ($condiciones_entrega as $key) {
                $li_condiciones = $li_condiciones.'<li>'.$key.'</li>';
            }
            $tercerpagina_html = '<div style="color: #150703;">
                        <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA</p>
                        <p></p>
                        <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>
                        </div>';
        }


        $precio_propiedad_letras = NumeroALetras::convertir($precio_propiedad,'Moneda Nacional');
        $monto_venta = $prospecto->monto_venta;
        $porcentaje_descuento = $prospecto->porcentaje_descuento;
        $porcentaje_enganche = $prospecto->porcentaje_enganche;
        $porcentaje_contraentrega = $prospecto->porcentaje_contraentrega;
        $porcentaje_adicional = $prospecto->adicional_d;
        $num_plazos = $prospecto->num_plazos;
        if ($num_plazos == '' or $num_plazos== null or $num_plazos == 0) {
            $num_plazos = 1;
        }

        $precio_final = round($monto_venta - ( $monto_venta * ( $porcentaje_descuento / 100) ), 2);
        /*oepraciones que se hacen en abse al precio final*/
        $porcentaje_mensualidad = 100 - ($porcentaje_enganche + $porcentaje_contraentrega + $porcentaje_adicional);
        $mensualidad = floor(round( ( $precio_final * ($porcentaje_mensualidad / 100))));
        //echo "porcentaje_mensualidad: ".$porcentaje_mensualidad;
        $montoenganche = round($precio_final * ($porcentaje_enganche / 100),2);
        $montoenganche = 400000;
        $montoenganche_letras = NumeroALetras::convertir($montoenganche,'Moneda Nacional');
        $plazos = $prospecto->num_plazos;
        if ($esquema_pago == 'CONTADO' and $plazos > 0) {
            $mensualidad = $montoenganche;
        }
        $mensualidad = 892133;
        $mensualidad_letras = NumeroALetras::convertir($mensualidad,'Moneda Nacional');

        if ($plazos > 0) {
           $mensualidades_descripcion = '$'.number_format($mensualidad, 2 , "." , ",").' ('.NumeroALetras::convertir($mensualidad,'Moneda Nacional').') se pagará en '.($plazos).' mensualidades;';
        }else{
            $mensualidades_descripcion = '';
        }
        $contraentrega =  2656118;//round( $precio_final * ($porcentaje_contraentrega / 100), 2);
        ///$contraentrega = $contraentrega - 20000; 
        $contraentrega_letras = NumeroALetras::convertir($contraentrega,'Moneda Nacional');

        $mts_terreno = $propiedad->terreno_metros;
        $mts_contruccion = $propiedad->construccion_metros;
        /*Prospecto*/
        $nombreprospecto = $prospecto->nombre;
        $domicilioprospecto = $prospecto->domicilio;
        $rfc_prospecto = $prospecto->rfc;
        $razon_social_prospecto = $prospecto->razon_social;
        $nacionalidad = $prospecto->nacionalidad;
        $fuente = 'Calibri';
        $nombreempresa = $empresa->razon_social;
        $fecha_contrato = $prospecto->fecha_contrato;
        $fecha_enganche = $prospecto->fecha_enganche;
        $dia_contrato = date('j', strtotime($fecha_contrato));
        $mes_contrato = date('m', strtotime($fecha_contrato));
        $year_contrato = date('Y', strtotime($fecha_contrato));
        if ($mes_contrato=='01') {$mescontrato_letra='Enero';}
        if ($mes_contrato=='02') {$mescontrato_letra='Febrero';}
        if ($mes_contrato=='03') {$mescontrato_letra='Marzo';}
        if ($mes_contrato=='04') {$mescontrato_letra='Abril';}
        if ($mes_contrato=='05') {$mescontrato_letra='Mayo';}
        if ($mes_contrato=='06') {$mescontrato_letra='Junio';}
        if ($mes_contrato=='07') {$mescontrato_letra='Julio';}
        if ($mes_contrato=='08') {$mescontrato_letra='Agosto';}
        if ($mes_contrato=='09') {$mescontrato_letra='Septiembre';}
        if ($mes_contrato=='10') {$mescontrato_letra='Octubre';}
        if ($mes_contrato=='11') {$mescontrato_letra='Noviembre';}
        if ($mes_contrato=='12') {$mescontrato_letra='Diciembre';}

        //////// PROMINENTE ADJUDICADO DECLARA
        $prominente_adjudicado_persona = '';
        if ($prospecto->tipo == 'Fisica') {
            $prominente_adjudicado_persona = 'Ser una persona física, de nacionalidad '.$nacionalidad.', mayor de edad, al corriente de sus obligaciones fiscales y en pleno uso y goce de sus derechos, así como con plena capacidad para contratar y obligarse.';
            $firma_adjudicado = $nombreprospecto;
        }elseif ($prospecto->tipo == 'Moral') {
            $prominente_adjudicado_persona = 'Ser una persona Moral, de nacionalidad '.$nacionalidad.', mayor de edad, al corriente de sus obligaciones fiscales y en pleno uso y goce de sus derechos, así como con plena capacidad para contratar y obligarse.';
            $firma_adjudicado = $nombreprospecto.' en representación de '.$razon_social_prospecto;
        }else{
            $prominente_adjudicado_persona = 'Ser una persona física, de nacionalidad '.$nacionalidad.', mayor de edad, al corriente de sus obligaciones fiscales y en pleno uso y goce de sus derechos, así como con plena capacidad para contratar y obligarse.';
        }
        ////////Pagos
        $calendario_pagos = '<table border="0">
            <tr style="background-color:#D7D6D6;"><th width="140">PAGOS</th><th width="100" align="center" valign="center">MENSUALIDAD</th><th align="center" valign="center">MONTO (MXN)</th><th align="center" valign="center" width="200">FECHA</th></tr>';
        /*Traemos todos los plazos de pago para e calendario*/
        $contador = 0;
        $suma_total = 0;
        $saltos = '';
        
        ///Simula calendario de pagos
        //$contraentrega = round( $precio_final * ($prospecto->porcentaje_contraentrega / 100), 2); 
        $monto_adicional = 0;//round( $precio_final * ($prospecto->adicional_d / 100), 2);
        if ($dia_pago_p == 'Ultimo dia del mes') {
            $dia_pago = $this->ultimodiaMes($fecha_enganche);
            //echo "<br/> Dia de pago U: ".$dia_pago ;
        }else{
            $dia_pago = $dia_pago_p;
        }

        $fecha = date('Y-m-'.$dia_pago,strtotime($fecha_enganche));
        ///$calendario_pagos = $calendario_pagos.'<tr><td>Abono a capital el dia</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format(20000, 2 , "." , ",").'</td><td align="center" valign="center">2019-01-27</td></tr>';
            //echo "<br/> Fecha pago Enchache: ".$fecha ;
        $calendario_pagos = $calendario_pagos.'<tr><td >Pago inicial</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format($montoenganche, 2 , "." , ",").'</td><td align="center" valign="center">'.date('Y-m-d',strtotime($fecha_enganche)).'</td></tr>';
        $suma_total = $suma_total + $montoenganche;
        $descripción_adicional = '';
        if ($monto_adicional > 0) {
            $calendario_pagos = $calendario_pagos.'<tr><td>Abono a capital el dia</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format($monto_adicional, 2 , "." , ",").'</td><td align="center" valign="center">'.date('Y-m-d',strtotime($prospecto->fecha_adicional)).'</td></tr>';
            $suma_total = $suma_total + $monto_adicional;
            $num_contraentrega = $plazos + 2;
            $monto_adicional = floor($monto_adicional);
            $monto_adicional_letras = NumeroALetras::convertir($monto_adicional,'Moneda Nacional');
            $dia_adicioal = date('d',strtotime($prospecto->fecha_adicional));
            $mes_adicioal = date('m',strtotime($prospecto->fecha_adicional));
            $year_adicioal = date('Y',strtotime($prospecto->fecha_adicional));
            if ($mes_adicioal=='01') {$mes_adicional_letra='Enero';}
            if ($mes_adicioal=='02') {$mes_adicional_letra='Febrero';}
            if ($mes_adicioal=='03') {$mes_adicional_letra='Marzo';}
            if ($mes_adicioal=='04') {$mes_adicional_letra='Abril';}
            if ($mes_adicioal=='05') {$mes_adicional_letra='Mayo';}
            if ($mes_adicioal=='06') {$mes_adicional_letra='Junio';}
            if ($mes_adicioal=='07') {$mes_adicional_letra='Julio';}
            if ($mes_adicioal=='08') {$mes_adicional_letra='Agosto';}
            if ($mes_adicioal=='09') {$mes_adicional_letra='Septiembre';}
            if ($mes_adicioal=='10') {$mes_adicional_letra='Octubre';}
            if ($mes_adicioal=='11') {$mes_adicional_letra='Noviembre';}
            if ($mes_adicioal=='12') {$mes_adicional_letra='Diciembre';}
            $descripción_adicional = '$'.number_format($monto_adicional, 2 , "." , ",").' ('.$monto_adicional_letras.') se abonarán el dia '.$dia_adicioal.' de '.$mes_adicional_letra.' de '.$year_adicioal.';';
        }
        $descripción_adicional = '$'.number_format(150000, 2 , "." , ",").' ('.NumeroALetras::convertir(150000,'Moneda Nacional').') se abonarán el dia 16 de junio del 2020 como anualidad; $'.number_format(150000, 2 , "." , ",").' ('.NumeroALetras::convertir(150000,'Moneda Nacional').') se abonarán el dia 16 de junio del 2021 como anualidad;';
        if (date('m', strtotime($fecha)) == '01') {
            $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
            
        }else{
            if($dia_pago == '31'){
                $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
            }
            else{
                $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
            }
        }
        //echo "<br/> Fecha entra: ".$fecha;
        if ($dia_pago_p == 'Ultimo dia del mes') {
            $dia_pago = $this->ultimodiaMes($fecha);
            //echo "<br/>Dia pago FOR: ".$dia_pago;
        }else{
            $dia_pago = $dia_pago_p;
        }

        $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
        if ($plazos > 0) {
            # code...
            for ($i=1; $i <= 12; $i++) {
                $contador ++;
                //$plazo_mensual =  ( $precio_final * ($porcentaje_mensualidad / 100)) / $plazos;
                $plazo_mensual =  20000;
                $suma_total = $suma_total + $plazo_mensual;
                if (date('m', strtotime($fecha)) == '01') {
                    $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
                    
                }else{
                    if($dia_pago == '31'){
                        $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
                    }
                    else{
                        $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
                    }
                }
                //echo "<br/> Fecha entra: ".$fecha;
                if ($dia_pago_p == 'Ultimo dia del mes') {
                    $dia_pago = $this->ultimodiaMes($fecha);
                    //echo "<br/>Dia pago FOR: ".$dia_pago;
                }else{
                    $dia_pago = $dia_pago_p;
                }

                $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
                /// echo "<br/> Fecha sale: ".$fecha;

                $calendario_pagos = $calendario_pagos.'<tr><td>Pago '.($i+1).'</td><td align="center" valign="center">'.($i).'</td><td align="center" valign="center">$'.number_format($plazo_mensual, 2 , "." , ",").'</td><td align="center" valign="center">'.date('Y-m-d',strtotime($fecha)).'</td></tr>';
            }
        }
        if ($plazos > 12) {
            # code...
            for ($i=13; $i <= $plazos; $i++) {
                $contador ++;
                //$plazo_mensual =  ( $precio_final * ($porcentaje_mensualidad / 100)) / $plazos;
                $plazo_mensual =  36230;
                $suma_total = $suma_total + $plazo_mensual;
                if (date('m', strtotime($fecha)) == '01') {
                    $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
                    
                }else{
                    if($dia_pago == '31'){
                        $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
                    }
                    else{
                        $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
                    }
                }
                //echo "<br/> Fecha entra: ".$fecha;
                if ($dia_pago_p == 'Ultimo dia del mes') {
                    $dia_pago = $this->ultimodiaMes($fecha);
                    //echo "<br/>Dia pago FOR: ".$dia_pago;
                }else{
                    $dia_pago = $dia_pago_p;
                }

                $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
                /// echo "<br/> Fecha sale: ".$fecha;

                $calendario_pagos = $calendario_pagos.'<tr><td>Pago '.($i+1).'</td><td align="center" valign="center">'.($i).'</td><td align="center" valign="center">$'.number_format($plazo_mensual, 2 , "." , ",").'</td><td align="center" valign="center">'.date('Y-m-d',strtotime($fecha)).'</td></tr>';
            }
        }

        if (date('m', strtotime($fecha)) == '01') {
            $fecha = date("Y-m-d", strtotime("+28 day", strtotime($fecha)));
            
        }else{
            if($dia_pago == '31'){
                $fecha = date("Y-m-d", strtotime("+29 day", strtotime($fecha)));
            }
            else{
                $fecha = date("Y-m-d", strtotime("+1 month", strtotime($fecha)));
            }
        }
        //echo "<br/> Fecha entra: ".$fecha;
        if ($dia_pago_p == 'Ultimo dia del mes') {
            $dia_pago = $this->ultimodiaMes($fecha);
            //echo "<br/>Dia pago FOR: ".$dia_pago;
        }else{
            $dia_pago = $dia_pago_p;
        }

        $fecha = date("Y-m-".$dia_pago, strtotime($fecha));
        $suma_total = $suma_total + ($contraentrega + 300000);
        $calendario_pagos = $calendario_pagos.'<tr><td>Anualidad Junio 2020</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format(150000, 2 , "." , ",").'</td><td align="center" valign="center">2020-06-16</td></tr>';
        $calendario_pagos = $calendario_pagos.'<tr><td>Anualidad Junio 2021</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format(150000, 2 , "." , ",").'</td><td align="center" valign="center">2021-06-16</td></tr>';
        //$fecha = date("Y-m-".$dia_pago, strtotime("+1 month", strtotime($fecha)));
        $calendario_pagos = $calendario_pagos.'<tr><td>Pago contra entrega</td><td align="center" valign="center">0</td><td align="center" valign="center">$'.number_format($contraentrega, 2 , "." , ",").'</td><td align="center" valign="center">a la fecha de escrituración</td></tr>';

        if ($contador < 5 ) {
           $saltos = '<P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P><P LANG="es-ES" ALIGN="center" STYLE="margin-bottom: 0in"><BR>
            </P>';
        }
        $calendario_pagos = $calendario_pagos.'<tr style="background-color:#D8FBC0;"><td>Valor total</td><td></td><td align="center" valign="center">'.number_format($suma_total, 2 , "." , ",").'</td><td></td></tr></table>'.$saltos;
        
        $img_file = $proyecto->header_contrato;
        // Custom Header
        PDF::setHeaderCallback(function($pdf) use ($img_file) {
            $path =  $img_file;
                // Title
            $pdf->Image($path, 5, 3, 140, 22, '', '', '', false, 300, '', false, false, 0);

        });
        PDF::SetAuthor('Marcela Carbajal');
        PDF::SetTitle('Contrato');
        PDF::SetSubject($nombreagente);
        PDF::SetKeywords('Contrato, PDF', 'compraventa');

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        // remove default footer
        PDF::setPrintFooter(false);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }
        /// add a page
        PDF::AddPage();
        // -- set new background ---

        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();

        
        // Print a text
        $htmlTITULO = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
            <P ALIGN="JUSTIFY" STYLE="margin-bottom: 0in">    <SPAN LANG="es-ES"><B>CONTRATO
            DE PROMESA DE ADJUDICACION</B></SPAN>
            QUE CELEBRAN POR UNA PARTE '.mb_strtoupper($nombreprospecto, 'UTF-8').', A QUIEN EN LO SUCESIVO SE LE
            DENOMINA COMO “EL PROMITENTE ADJUDICADO; Y POR LA OTRA PARTE
            EDISAMA S.A.P.I. DE C.V., REPRESENTADA EN ESTE ACTO POR MAURICIO
            ORTIZ MARGAIN, A QUIEN EN LO SUBSECUENTE SE LE DENOMINA “EL
            PROMITENTE ADJUDICADOR”; CUANDO SE HAGA REFERENCIA A AMBAS PARTES
            SE LES DENOMINARÁ “LAS PARTES, ASÍ SE SUJETAN AL TENOR DE LOS
            SIGUIENTES ANTECEDENTES, DECLARACIONES Y CLAUSULAS.</P>
            <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>A
            N T E C E D E N T E S</B></SPAN></P><OL TYPE="I"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%">
                Mediante
                Escritura Pública 35,719 (treinta y cinco mil setecientos
                diecinueve), de fecha 29 de noviembre de 2017, pasada ante la Fe del
                Notario Público Titular número 26, el Licenciado Gustavo Escamilla
                Flores, se protocolizó el Contrato de Fideicomiso de Administración
                y Desarrollo Inmobiliario con Reserva del Derecho de Revisión
                identificado con el número 74,516 (el “<B>FIDEICOMISO</B>”).
                En dicho instrumento compareció como <B>FIDUCIARIA</B>,
                Banca Afirma Sociedad Anónima Institución de Banca Múltiple Grupo
                Financiero (División Fiduciaria), por su parte compareció como
                <B>FIDEICOMITENTE </B>y <B>FIDEICOMISARIO</B>, la sociedad EDIFICACIONES CHIPINQUE S.A. DE C.V. (en lo sucesivo <B>“FIDEICOMITENTE CHIPINQUE”</B>)</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que el <B>FIDEICOMISO</B> se constituyó a fin de establecer los términos y condiciones de inversión para la adquisición, desarrollo, construcción y administración de cinco inmuebles (los <B>“INMUEBLES” </B>) ubicados en la ciudad de Monterrey, mismos que formarán un único proyecto inmobiliario OHRUS.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> En fecha 13 de febrero del 2019 se le giraron instrucciones a la <B>FIDUCIARIA,</B> para que celebraran un  “Convenio de Cesión Total de Derechos y Obligaciones de Fideicomitente y Fideicomisario Dentro del Contrato de Fideicomiso de Administración y Desarrollo Inmobiliario con Derecho de Revisión Identificado con el número 74,516”, respecto al <B>FIDEICOMISO</B>, misma que se adjunta como <B>ANEXO A</B>.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> En atención a dicha instrucción, mediante Escritura Pública número 38,599 (treinta y ocho mil quinientos noventa y nueve), de fecha 22 de febrero de 2019, pasada ante la Fe del Notario Público Titular número 26, el Licenciado Gustavo Escamilla Flores, se protocolizó el “Convenio de Cesión Total de Derechos y Obligaciones de Fideicomitente y Fideicomisario Dentro del Contrato de Fideicomiso de Administración y Desarrollo Inmobiliario con Derecho de Reversión Identificado con el número 74,516”, a través del cual el <B>PROMITENTE ADJUDICADOR</B> adquirió la calidad de CEDENTE de todos los Derechos y Obligaciones ahí estipulados, lo que le permite jurídica y materialmente comparecer a la presente celebración del Contrato.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <SPAN LANG="en-US">El proyecto inmobiliario OHRUS consta de un desarrollo de usos de suelos mixtos con los siguientes destinos: a. Comercial; b. Restaurantes; c. Oficinas; d. Habitacional </SPAN>(el “<B>PROYECTO INMOBILIARIO</B>”).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que a la fecha los <B>INMUEBLES</B> se encuentran en propiedad del <B>FIDEICOMISO</B>. </P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que  la descripción de los <B>INMUEBLES</B>  es la siguiente:</P>
                    <OL TYPE="a">
                        <LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Lote de Terreno número 4, marcado catastralmente con el número 13 trece, ubicado en la congregación de San Gerónimo, de la ciudad de Monterrey, Nuevo León y el cual tiene una superficie de 762.63  metros cuadrados (setecientos sesenta y dos metros cuadrados, sesenta y tres decímetros cuadrados).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Lote de terreno marcado con el número 5, marcado catastralmente con el número 28, ubicado en la congregación de San Gerónimo al Poniente, denominado Santa María, de la ciudad de Monterrey, Nuevo León, el cual tiene una superficie de 762.63 metros cuadrados (setecientos sesenta y dos metros cuadrados, sesenta y tres decímetros cuadrados).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Lote de terreno marcado con el número 1, y que se encuentra bajo el expediente catastral 22088022, ubicado en la Congregación de San Gerónimo al Poniente, denominado Santa María, de la Ciudad de Monterrey, Nuevo León y el cual tiene una superficie de 1,103.33 metros cuadrados (mil ciento tres metros cuadrados, treinta y tres decímetros cuadrados).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Lote de terreno marcado según técnica catastral con el número 23, registrado bajo el expediente catastral número 22088023, ubicado en la Congregación de San Gerónimo al Poniente de la ciudad de Monterrey, Nuevo León, denominado San María, y el cual tiene una superficie de 252.50 metros cuadrados (doscientos cincuenta y dos metros cuadrados, cincuenta decímetros cuadrados).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Lote de terreno marcado con el número 24, registrado bajo el expediente catastral número 22088024, ubicado en la Congregación San Gerónimo al Poniente de la ciudad de Monterrey, Nuevo León, denominado San María, y el cual tiene una superficie de 252.50 metros cuadrados (doscientos cincuenta y dos metros cuadrados, cincuenta decímetros cuadrados).</P></LI>
                    </OL></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que los <B>INMUEBLES</B> se encuentran libre de todo gravamen y responsabilidad, además que se encuentran al corriente de todas sus obligaciones fiscales.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que los <B>INMUEBLES</B> cuentan con todos los permisos y licencias requeridas para el desarrollo del <B>PROYECTO INMOBILIARIO</B>.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que al día de hoy el trámite para obtener la autorización del Régimen de Propiedad en Condominio correspondiente al <B>PROYECTO INMOBILIARIO</B> (el <B>“RÉGIMEN DE CONDOMINIO”</B>) no ha sido presentada ante la Secretaría de Desarrollo Urbano de Monterrey, Nuevo León, no obstante su autorización e inscripción representarán una condicionante para celebrar el contrato de compraventa que resulte del presente acuerdo.</P></LI>
                </OL>
                <P LANG="es-ES" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in">
                <BR>
                </P>
                <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>D E C L A R A C I O N E S</B></SPAN></P>
                <OL TYPE="I"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Declara el <B>PROMINETE ADJUDICADOR </B>que:</P><OL TYPE="a"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Es una persona moral legalmente constituida bajo la Póliza número 2.547 (dos mil quinientos cuarenta y siete), de fecha 28 de febrero de 2019, pasada ente la Fe del Corredor Público 26 en la Plaza de Nuevo León, el Licenciado Abelardo Pérez Ocañas.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que su R.F.C es <SPAN LANG="es-ES">EDI180228TQ0</SPAN>.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que su representante, el C. MAURICIO ORTIZ MARGAIN, se ostenta como Gerente General de la Sociedad EDISAMA S.A.P.I. de C.V., y que por lo tanto cuenta con todas las facultades necesarias y suficientes para celebrar el presente instrumento, y que las mismas no le han sido limitadas, modificadas y/o revocadas, tal como se desprende de la Póliza número 2.547 (dos mil quinientos cuarenta y siete), de fecha 28 de febrero de 2019, pasada ente la Fe del Corredor Público 26 en la Plaza de Nuevo León, el Licenciado Abelardo Pérez Ocañas.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que tal como lo disponen los ANTECEDENTES III y IV, cuenta con el 100% de los derechos y obligaciones con las que contaba FIDEICOMITENTE CHIPINQUE dentro del FIDEICOMISO, con lo que cuenta con facultades suficientes y necesarias para celebrar el presente instrumento.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que acredita lo anteriormente declarado con los documentos adjuntos en el<B> ANEXO B, </B>los cuales constan de: acta constitutiva, cédula fiscal, poder al que hace referencia el inciso c. de la presente declaración y documento de cesión de derechos al que hace referencia el inciso d. de la presente declaración.</P></LI>
                    </OL></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>EL PROMITENTE ADJUDICADO</B>, declara lo siguiente:</P><OL TYPE="a"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> '.$prominente_adjudicado_persona.'</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que su R.F.C. es '.strtoupper($rfc_prospecto).'</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que desea adquirir <B>'.$objetoAdjudicado.' </B>número '.$nombrepropiedad.' del '.strtolower($nivel).' , localizado en Santa María número 210, Colonia Santa María, en el municipio de Monterrey, Nuevo León, C.P. 64650, el cual se encuentra dentro del <B>PROYECTO INMOBILIARIO</B>.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que acredita lo anteriormente declarado con los documentos adjuntos en el<B> ANEXO C</B>, los cuales constan de: identificación oficial, cédula fiscal, y comprobante de domicilio.<B> </B></P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%">Que los recursos económicos con los que se obliga hacer el pago son de procedencia genuina y lícita.</P></LI></OL></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Las <B>PARTES</B> declaran conjuntamente:</P><OL TYPE="a"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que es de su intención celebrar el presente Contrato de Promesa de Adjudicación a fin de poder sujetarse a las condiciones que aquí se establezcan, y una vez cubiertas y satisfechas, se celebre el contrato de compraventa resultante del presente instrumento. </P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que se reconocen mutuamente su capacidad y la personalidad de sus representantes.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Que están de acuerdo en sujetarse a las siguientes:</P></LI></OL>
                    </OL><P LANG="es-MX" ALIGN="CENTER" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"> <B>C L A U S U L A S</B></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>PRIMERA.-</B><U><B> OBJETO</B></U></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%">Por virtud del presente instrumento, el <B>PROMITENTE ADJUDICADOR</B> promete vender y, por su parte el <B>PROMITENTE ADJUDICADO</B> se compromete y realiza la promesa expresa de adquirir en propiedad <B>'.$objetoAdjudicado.'</B>, ubicado en el <B>PROYECTO INMOBILIARIO. </B></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%">Las áreas exclusivas que el <B>PROMITENTE ADJUDICADOR</B> entregará se definen en el <B>ANEXO D </B>de este Contrato.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%">Que <B>'.$objetoAdjudicado.'</B> cuenta con las siguientes características generales:</P>
                        <table><tr><td width="30"></td><td width="300"><B>Clase de inmueble: </B></td><td>'.$uso_propiedad.'</td></tr>
                        <tr><td></td><td><B>Nivel: </B></td><td>'.$nivel.'</td></tr>
                        <tr><td></td><td><B>Superficie en metros cuadrados: </B></td><td>'.$mts_total.'</td></tr>
                        <tr><td></td><td><B>Número de estacionamientos: </B></td><td>'.$cajones_estacionamiento.'</td></tr>
                        </table>
            <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%">
            Se deja de manifiesto que pueden existir varaciaciones en las medidas y superficie en metros cuadrados de <B>'.$objetoAdjudicado.'</B>. En caso de que dicha variación sea igual o menor al 5% de la superficie original establecida anteriormente, el precio no sufrirá varición. En caso que dicha variación sea superior al 5% se ajustará el precio conforme al área final construida.</P>
            <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>SEGUNDA.-</B><U><B> PRECIO Y FORMA DE PAGO </B></U></P><OL TYPE="A"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%">
                <B>VALOR</B>.-El valor total '.$objetoAdjudicado.' es de <B>$'.number_format($precio_propiedad, 2 , "." , ",").'</B>
                (<B>'.$precio_propiedad_letras.'</B>), cantidad a la que se le deberá sumar el Impuesto al Valor Agregado que corresponda a la construcción (el <B>“PRECIO”</B>).</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>ANTICIPO Y CALENDARIO DE PAGOS</B>.- Para los efectos del presente instrumento, el <B>PROMITENTE ADJUDICADO </B>se obliga a liquidar el valor total <B>'.$objetoAdjudicado.'</B> de la siguiente manera: otorgará un pago inicial por el valor de $'.number_format($montoenganche, 2 , "." , ",").' ('.$montoenganche_letras.'); '.$mensualidades_descripcion.' '.$descripción_adicional.' y realizará un último pago con valor de $'.number_format($contraentrega, 2 , "." , ",").' ('.$contraentrega_letras.') que se entregará contra la firma de le escritura pública correspondiente.  Lo anterior se sujeta al calendario de pagos establecido en el <B>ANEXO E </B>de este contrato.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"><B>I.V.A.-</B> El pago que corresponda al Impuesto al Valor Agregado por <B>'.$objetoAdjudicado.'</B>, deberá efectuarse al llevarse a cabo la formalización en Escritura Pública del contrato que resulte del presente instrumento.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>PLAZO DE GRACIA.- </B>El <B>PROMITENTE ADJUDICADOR</B> le concede al <B>PROMITENTE ADJUDICADO</B> un plazo de gracia para el cumplimiento de sus obligaciones contractuales de 10- diez días hábiles contados a partir de la fecha en que debió de finiquitar su obligación (el “<B>PLAZO DE GRACIA</B>”)</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"><B>INCUMPLIMIENTO EN EL CALENDARIO DE PAGOS.- </B>En caso que el <B>PROMITENTE ADJUDICADO </B>incumpla en 2 -dos de los pagos estipulados en el inciso que antecede y se haya satisfecho el <B>PLAZO DE GRACIA, </B>se entenderá por rescindido el presente instrumento.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%">
                Además de todas las obligaciones y derechos que resulten de dicha recisión, el <B>PROMITENTE ADJUDICADOR</B> cobrará al <B>PROMITENTE ADJUDICADO</B> una Pena Convencional correspondiente al 20% (veinte por ciento) del valor total estipulado en el inicio A de la presente cláusula, dicha cantidad podrá ser retenida y adjudicada en favor del <B>PROMITENTE ADJUDICADOR</B> de las cantidades que se hayan erogado en cumplimiento del presente contrato.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"><BR><BR></P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"><B>GARANTÍA.-</B>Todos los pagos que realice el <B>PROMITENTE ADJUDICADO</B> en cumplimiento al presente instrumento, se entenderán hechos como garantía para el cumplimiento de las obligaciones derivadas del presente instrumento.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"><B>FORMA DE PAGO: </B>Los pagos realizados  por el <B>PROMITENTE ADJUDICADO</B> en cumplimiento al presente instrumento, se deberán por transferencia bancaria a la siguiente cuenta, propiedad del <B>PROMITENTE ADJUDICADOR</B>: </P>
                    <table><tr><td width="30"></td><td width="300"><B>BANCO: </B></td><td>BANORTE</td></tr>
                    <tr><td></td><td><B>CUENTA: </B></td><td>0592799812</td></tr>
                    <tr><td></td><td><B>CLABE: </B></td><td>072580005927998120</td></tr>
                    <tr><td></td><td><B>RAZÓN SOCIAL: </B></td><td>EDISAMA SAPI DE CV</td></tr>
                    <tr><td></td><td><B>R.F.C.: </B></td><td>EDI180228TQ0</td></tr>
                    <tr><td></td><td><B>CORREO: </B></td><td>srubio@achedesarrollos.com</td></tr>
                    </table>
                    <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.99in; margin-bottom: 0.11in; line-height: 107%"> Todos los documentos que acrediten la transferencia bancaria, deberán ser enviados vía correo electrónico a la cuenta señalada, o en su defecto a la cuenta señalada por personal de la empresa.</P></LI>
                </OL>
                <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>TERCERA.-</B><U><B> DEL RÉGIMEN EN CONDOMINIO</B></U></P><OL TYPE="A"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>CONDICIÓN SUSPENSIVA: </B><SPAN LANG="es-ES">La obligación de </SPAN><SPAN LANG="es-ES"><B>PARTES</B></SPAN><SPAN LANG="es-ES"> de celebrar el contrato de compraventa que resulte del presente instrumento, estará sujeta a la condición suspensiva de que el </SPAN><SPAN LANG="es-ES"><B>PROMITENTE ADJUDICADOR </B></SPAN><SPAN LANG="es-ES">obtenga de las autoridades competentes del municipio de Monterrey, Nuevo León, la autorización para constituir el </SPAN><SPAN LANG="es-ES"><B>RÉGIMEN EN CONDOMINIO </B></SPAN><SPAN LANG="es-ES">(la</SPAN><SPAN LANG="es-ES"><B> “CONDICIÓN</B></SPAN><SPAN LANG="es-ES">”). </SPAN> </P></LI> <LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>SUJECIÓN AL RÉGIMEN EN CONDOMINIO: </B><SPAN LANG="es-ES"><B>LAS PARTES </B></SPAN><SPAN LANG="es-ES">reconocen y están de acuerdo que </SPAN><B>'.$objetoAdjudicado.'</B><SPAN LANG="es-ES"> quedará sujeto a un Régimen de Propiedad en Condominio, motivo por el cual desde este momento </SPAN><SPAN LANG="es-ES"><B>EL PROMITENTE ADJUDICADO </B></SPAN><SPAN LANG="es-ES">acepta sujetarse a las disposiciones del propio Régimen y/o de su Reglamento.</SPAN></P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"><B>DEL CONTRATO DE COMPRAVENTA: </B><SPAN LANG="es-ES">Una vez cumplida la </SPAN><SPAN LANG="es-ES"><B>CONDICIÓN</B></SPAN><SPAN LANG="es-ES">, </SPAN><SPAN LANG="es-ES"><B>LAS PARTES </B></SPAN><SPAN LANG="es-ES">cuentan con la obligación ineludible de celebrar un Contrato de Compraventa respecto a </SPAN><SPAN LANG="es-ES"><B>EL</B></SPAN><B> DEPARTAMENTO</B>, en un plazo no mayor a 30 (treinta) días naturales contados a partir que se haya cumplida la <B>CONDICIÓN</B>.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"> Para tales efectos, el <B>PROMITENTE ADJUDICADOR </B>deberá de enviar un aviso por escrito al <B>PROMITENTE ADJUDICADO</B>, a fin de informarle del cumplimiento de la <B>CONDICIÓN</B> y la programación para la firma del Contrato de Compraventa. Dicho aviso se entenderá por recibido al día siguiente en que fue enviado.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"><BR><BR></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"> En caso que el <B>PROMITENTE ADJUDICADO</B> se niegue a firmar el Contrato de Compraventa, no se presente a la firma del Contrato de Compraventa o haya trascurrido el plazo señalado en el primer párrafo de este inciso y que por causas imputables al <B>PROMITENTE ADJUDICADO </B>no se haya podido celebrar el Contrato de Compraventa,  el <B>PROMITENTE ADJUDICADOR</B> cobrará al <B>PROMITENTE ADJUDICADO</B> una Pena Convencional correspondiente al 50% (cincuenta por ciento) del valor total estipulado en el inicio A de la cláusula SEGUNDA, dicha cantidad podrá ser retenida y adjudicada en favor del <B>PROMITENTE ADJUDICADOR</B> de las cantidades que se hayan erogado en cumplimiento del presente contrato.</P>
                </OL>
            <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>CUARTA-</B><U><B>
            ESCRITURACIÓN</B></U></P>
            <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"> La escrituración se hará a más tardar 90 -noventa- días naturales después de terminada la obra y se haya cumplido la “CONDICIÓN”, siendo a cargo del<B> PROMITENTE ADJUDICADO</B> y por su cuenta todos los gastos, impuestos y honorarios notariales que se motiven por la escritura de compraventa, con excepción del Impuesto Sobre la Renta que corresponderá al<B> PROMITENTE ADJUDICADOR</B>.</P>
            <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>QUINTA.- </B><U><B>REEMBOLSO DE LA GARANTÍA</B></U></P>
            <P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES">El
            </SPAN><SPAN LANG="es-ES"><B>PROMITENTE ADJUDICADOR</B></SPAN><SPAN LANG="es-ES"> se obliga a rembolsar al </SPAN><SPAN LANG="es-ES"><B>PROMITENTE ADJUDICADO</B></SPAN><SPAN LANG="es-ES">, las cantidades recibidas conforme a este Contrato, sólo en el supuesto que no se cumpla la </SPAN><SPAN LANG="es-ES"><B>CONDICION</B></SPAN><SPAN LANG="es-ES"> a más tardar 8-ocho- meses después de terminada la obra, en tal supuesto no habrá responsabilidad alguna para </SPAN><SPAN LANG="es-ES"><B>LAS PARTES</B></SPAN><SPAN LANG="es-ES">. Dicho reembolso deberá ser solicitado por escrito por el </SPAN><SPAN LANG="es-ES"><B>PROMITENTE ADJUDICADO</B></SPAN><SPAN LANG="es-ES">.</SPAN></P><P LANG="es-ES" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><BR></P><P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES">El </SPAN><SPAN LANG="es-ES"><B>PROMITENTE ADJUDICADO</B></SPAN><SPAN LANG="es-ES"> renuncia a reclamar el pago de intereses legales o de cualquier tipo si se cubre el reembolso luego de solicitarlo por escrito. </SPAN></P><P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES"><B>SEXTA.-</B></SPAN><SPAN LANG="es-ES"><U><B> ENTREGA DE '.$objetoAdjudicado.'</B></U></SPAN></P>
                <OL TYPE="A"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>ENTREGA JURÍDICA.-</B> El <B>PROMITENTE ADJUDICADOR</B>, entregará <B>'.$objetoAdjudicado.'</B> materia del presente contrato al <B>PROMITENTE ADJUDICADO</B>, en fecha de celebración de la escritura correspondiente y con la calidad que le corresponde conforme al Artículo 2209 del Código civil vigente del Estado de Nuevo León, incluyendo el sometimiento del mismo Régimen de Condominio y al reglamento respectivo.</P> <P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"> La entrega jurídica del bien, libre de toda carga, gravamen y sin reserva de dominio, se realizará, habiéndose cubierto el importe por parte del <B>PROMITENTE ADJUDICADO</B>, se termine la tramitación de Régimen de Condominio ante las autoridades Municipales, de Catastro y del Registro Público de la Propiedad y que <B>'.$objetoAdjudicado.'</B> esté en las condiciones de recepción establecidas en el <B>ANEXO F</B>. Esta entrega jurídica se hará mediante la escritura correspondiente otorgada ante Notario Público e inscrita en el Registro Público de la Propiedad.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>ENTREGA FÍSICA:</B> La entrega física de <B>'.$objetoAdjudicado.'</B> objeto del presente contrato se hará más tardar 30 -treinta- meses contados a partir de la fecha de inicio de construcción, considerando que la construcción comenzó en septiembre de 2019.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 1in; margin-bottom: 0.11in; line-height: 107%"> El <B>PROMITENTE ADJUDICADOR </B>contará con una prórroga de 8 -ocho- meses para realizar la entrega física del inmueble, en dicho plazo de prórroga el <B>PROMITENTE ADJUDICADO </B>no podrá solicitar la recisión o el pago de intereses o penas convencionales por dicha situación.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>SOLICITUD DE RECISIÓN</B>: Una vez transcurrida la prórroga estipulada en el inciso que antecede, el <B>PROMITENTE ADJUDICADO</B> podrá solicitar la recisión del contrato y la devolución de las cantidades erogadas. Dicha solicitud deberá de realizarse por escrito.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>CONDICIONES D'.$objetoAdjudicado.'</B><B>:</B><B> </B>Las <B>PARTES</B> convienen<B> </B>que <B>'.$objetoAdjudicado.'</B><SPAN LANG="es-ES"> será entregado en las condiciones descritas en el </SPAN><SPAN LANG="es-ES"><B>ANEXO F</B></SPAN><SPAN LANG="es-ES"> de este contrato.</SPAN></P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> <B>CUOTAS DE MANTENIMIENTO:</B> Las cuotas de mantenimiento iniciaran a partir de la misma fecha de entrega. El <B>PROMITENTE ADJUDICADO</B>, reconoce este pago como una de sus obligaciones generales en el Régimen de Condominio y que tienen que ser cubiertas oportunamente.</P></LI>
                </OL>
                <P></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>SEPTIMA.-</B><U><B>EVICCIÓN</B></U></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%">El <B>PROMITENTE ADJUDICADOR</B>, se obligará a responder del saneamiento para el caso de evicción conforme a Derecho.</P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>OCTAVA.-</B><U><B> CESIÓN DE DERECHOS</B></U></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"> El <B>PROMITENTE ADJUDICADO</B>, podrá en todo tiempo y hasta en tanto se celebre la Escritura Pública correspondiente, ceder sus derechos y obligaciones derivadas del presente contrato a terceras personas, debiendo para ello cumplir con todos los siguientes requisitos: </P><OL TYPE="a"><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Recabar la autorización escrita del <B>PROMITENTE ADJUDICADOR</B>;</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%"> Estar al corriente de sus obligaciones y programa de pagos;</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%">Estar cubierto, al menos, el 20% (veinte por ciento) del valor total de <B>'.$objetoAdjudicado.'</B>.</P></LI><LI><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-bottom: 0.11in; line-height: 107%">Someter la operación a la consideración de las autoridades fiscales conducentes, federales, estatales o municipales, en la inteligencia de que cualquier carga fiscal que se cause será exclusiva del <B>PROMITENTE ADJUDICADO.</B></P></LI>
                    </OL><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"><B>NOVENA.-</B><U><B>NOTIFICACIONES DOMICILIO CONVENCIONAL</B></U></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0.11in; line-height: 107%"> Las partes señalan como domicilios convencionales para oír y recibir cualquier clase de aviso o notificación relacionados con el presente contrato, los siguientes domicilios:  </P><P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; text-indent: 0.49in; margin-bottom: 0in"><SPAN LANG="es-ES"><B>EL PROMITENTE ADJUDICADOR:</B></SPAN></P>
                        <table><tr><td width="30"></td><td width="300"><SPAN LANG="es-ES"><B>Libertad #118 Pte.</B></SPAN></td></tr>
                        <tr><td></td><td><SPAN LANG="es-ES"><B>Col. Centro de San Pedro</B></SPAN></td></tr>
                        <tr><td></td><td><SPAN LANG="es-ES"><B>San Pedro Garza García N.L. 66230</B></SPAN></td></tr>
                        </table>
                        <P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; text-indent: 0.49in; margin-bottom: 0in"><SPAN LANG="es-ES"><B>EL PROMITENTE ADJUDICADO:</B></SPAN></P>
                        <table><tr><td width="30"></td><td width="300"><SPAN LANG="es-ES"><B>'.$domicilioprospecto.'</B></SPAN></td></tr>
                        </table>
                    <P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES">Todo
                    aviso, comunicación, notificación, requerimiento o pago deberá de
                    presentarse en el domicilio de la contraparte con un acuse de recibo,
                    mismo que será notificado. Todas las comunicaciones a las que se
                    refiere esta cláusula, se tendrán hechas en el mismo día y los
                    plazos que pudiese derivarse de las mismas, deberán de ser contados
                    a partir del día siguiente en el que se realizó la comunicación.</SPAN></P>
                    <P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES"><B>DÉCIMA. -</B></SPAN><SPAN LANG="es-ES"><U><B> ENCABEZADOS </B></U></SPAN></P><P LANG="es-MX" ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in">Los títulos de las cláusulas que aparecen en el presente instrumento, se han puesto con el exclusivo propósito de facilitar su lectura, por tanto, no definen ni limitan el contenido de las mismas. Para efectos de interpretación de cada cláusula deberá atenderse exclusivamente a su contenido y de ninguna manera a su título.</P><P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES"><B>DÉCIMA PRIMERA. - </B></SPAN><SPAN LANG="es-ES"><U><B>JURIDISCCIÓN Y COMPETENCIA</B></U></SPAN></P><P ALIGN="JUSTIFY" STYLE="margin-left: 0.5in; margin-bottom: 0in"><SPAN LANG="es-ES">Para cualesquier controversia que surja entre las partes y para todo lo relacionado con la existencia, validez, interpretación, cumplimiento y ejecución de este contrato y sus obligaciones </SPAN><SPAN LANG="es-ES"><B>LAS PARTES</B></SPAN><SPAN LANG="es-ES"> se someten expresamente a la jurisdicción y competencia de los tribunales competentes del Primer Distrito Judicial del Estado de Nuevo León, con residencia en la Ciudad de Monterrey, y a las leyes, reglamentos y demás disposiciones legales vigentes en dicho Estado, tanto en materia común como federal, renunciando expresamente a cualesquier otro fuero que por razón de su domicilio presente o futuro, o por cualesquier otra causa, pudiere corresponderles.</SPAN></P>
                        <P ALIGN="JUSTIFY" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES">Enteradas
                        </SPAN><SPAN LANG="es-ES"><B>LAS
                        PARTES</B></SPAN><SPAN LANG="es-ES">,
                        del contenido y alcances legales de los ANTECEDENTES, DECLARACIONES y
                        CLÁUSULAS anteriores, manifiestan expresamente que están de acuerdo
                        con las mismas, por no ser contrarias al derecho, la moral o las
                        buenas costumbres, y por no existir dolo, lesión, mala fe o vicio
                        alguno del consentimiento, y lo otorgan y firman en fecha '.$dia_contrato.' de '.$mescontrato_letra.' del '.$year_contrato.' por
                        estar debida y legalmente legitimados para ello en pleno uso de su
                        capacidad de goce y ejercicio. </SPAN>
                        </P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P>
                        <table ALIGN="CENTER">
                        <tr>
                        <td colspan="2">“EL PROMITENTE ADJUDICADOR”</td>
                        <td colspan="2">“EL PROMITENTE ADJUDICADO”</td>
                        </tr>
                        <tr>
                        <td colspan="2">Mauricio Ortiz Margain</td>
                        <td colspan="2">'.$firma_adjudicado.'</td>
                        </tr>
                        <tr>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        </tr>
                        <tr>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        </tr>
                        <tr>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        </tr>
                        <tr>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        </tr>
                        <tr>
                        <td colspan="2">______________________________</td>
                        <td colspan="2">______________________________</td>
                        </tr>
                        <tr>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        </tr>
                        </table>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                        
                    $AnexoA_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ANEXO
                        A</B></SPAN></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in">“<SPAN LANG="es-ES"><B>CONVENIO
                        DE CESIÓN TOTAL DE DERECHOS Y OBLIGACIONES DE FIDEICOMITENTE Y
                        FIDEICOMISARIO DENTRO DEL CONTRATO DE FIDEICOMISO DE ADMINISTRACIÓN
                        Y DESARROLLO INMOBILIARIO CON DERECHO DE REVISIÓN IDENTIFICADO CON
                        EL NÚMERO 74,516”</B></SPAN></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="assets/images/anexoA.png" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="410"></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="assets/images/anexoA_2.png" NAME="Imagen 17" ALIGN=BOTTOM WIDTH="405"></P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                    $AnexoB_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;"><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ANEXO
                        B</B></SPAN></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>PERSONALIDAD
                        PROMITENTE ADJUDICADOR</B></SPAN></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="assets/images/anexoA_2.png" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="429"></P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                    $AnexoC_1_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;"><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ANEXO
                        C</B></SPAN></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>PERSONALIDAD
                        PROMITENTE ADJUDICADO</B></SPAN></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="'.$anexoC_1.'" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="429"></P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                    $AnexoC_2_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="'.$anexoC_2.'" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="429"></P>
                        <P LANG="es-ES" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';

                    $AnexoD_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ANEXO
                        D</B></SPAN></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ÁREAS
                        EXCLUSIVAS (PLANO DE DISTRIBUCIONES)</B></SPAN></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="'.$nivel_path.'" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="500"></P>
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><IMG SRC="'.$imagen_path.'" NAME="Imagen 15" ALIGN=BOTTOM WIDTH="500"></P>
                        <P LANG="es-ES" ALIGN="CENTER" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                    $AnexoE_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><B>ANEXO
                        E</B></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>CALENDARIO DE PAGOS</B></SPAN></P>
                        <P LANG="es-ES" ALIGN="CENTER" STYLE="margin-bottom: 0in"><BR>
                        </P>
                        '.$calendario_pagos.'
                        <P LANG="es-ES" ALIGN="CENTER" STYLE="margin-bottom: 0in"><BR>
                        </P></div>';
                    $AnexoF_html = '<div style="color:#393939; font-weight:normal; font-size:11pt;">
                        <P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>ANEXO
                        F</B></SPAN></P><P ALIGN="CENTER" STYLE="margin-bottom: 0in"><SPAN LANG="es-ES"><B>CONDICIONES
                        DE ENTREGA OHRUS</B></SPAN></P>
                        '.$tercerpagina_html.'
                        </div>';

            
        
        //PDF::writeHTML($html, true, false, true, false, '');
        PDF::SetTextColor(0, 0, 0, 0);
        // set font
        PDF::SetFont('Helvetica', '', 12);
        PDF::writeHTMLCell(170, 15, 20, 30 , $htmlTITULO, 0, 0, 0, false, 'L', false);
        // reset pointer to the last page
        PDF::lastPage();

        /// add a page ANEXO A
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoA_html, 0, 0, 0, false, 'L', false);
        PDF::lastPage();

        /// add a page ANEXO b
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 30 , $AnexoB_html, 0, 0, 0, false, 'L', false);
        
        /// add a page ANEXO c
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoC_1_html, 0, 0, 0, false, 'L', false);
        if ($anexoC_2 != null and $anexoC_2 != '') {
            /// add a page ANEXO C 2
            PDF::AddPage();
            // get the current page break margin
            $bMargin = PDF::getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = PDF::getAutoPageBreak();
            // disable auto-page-break
            PDF::SetAutoPageBreak(false, 0);
            // set bacground image
            // restore auto-page-break status
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            PDF::setPageMark();
            PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoC_2_html, 0, 0, 0, false, 'L', false);
        }
        PDF::lastPage();

        /// add a page ANEXO d
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoD_html, 0, 0, 0, false, 'L', false);

        /// add a page ANEXO E
        PDF::SetTextColor(0, 0, 0, 0);
        // set font
        PDF::SetFont('Helvetica', '', 12);
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        //PDF::Image($img_file_footer, 0, 257, 210, 40, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoE_html, 0, 0, 0, false, 'L', false);

        PDF::lastPage();
        /// add a page ANEXO F
        PDF::AddPage();
        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // set bacground image
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        PDF::writeHTMLCell(170, 15, 20, 20 , $AnexoF_html, 0, 0, 0, false, 'L', false);


        
        /////finalizar pdf

            $namePDF = uniqid().'contrato_compraventa_'.$nombreprospecto.'.pdf';
            //Close and output PDF document
            PDF::Output($namePDF, 'I');
        return back();
    }

    public function crearContratoEnglish(request $request, $id)
    {
        $prospecto = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
        ->select('p.id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'fecha_recontacto','observaciones','fecha_visita','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','motivo_perdida','prop.precio','prop.enganche','p.esquema_comision_id','ec.esquema_comision','p.comision_id','extension','e.nivel','p.razon_social','p.domicilio','p.interes','p.mensualidad','p.capital','p.tipo','p.pagado','fecha_contrato','num_contrato','vigencia_contrato','p.num_plazos','p.nacionalidad','porcentaje_enganche','porcentaje_descuento','porcentaje_contraentrega','p.oficina_broker','p.fecha_entrega_propiedad','p.esquema_pago')
        ->where('id_prospecto','=',$id)
        ->first();
        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado','monto_mora','dias_retraso','notas','interes','interes_acumulado','capital_acumulado','total_acumulado','deuda','amortizacion','capital','capital_inicial','moneda_id')
        ->where('prospecto_id','=',$id)
        ->get();

        ///propiedad
        $proyecto = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->where('id_proyecto','=',$prospecto->proyecto_id)
        ->first();
        $propiedad = DB::table('propiedad as p')
        ->join('pais as pa','p.pais_id','=','pa.id_pais','left',false)
        ->join('estado as e','p.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','p.ciudad_id','=','c.id_ciudad','left',false)
        ->join('nivel as n','p.nivel_id','=','n.id_nivel','left',false)
        ->join('tipo_modelo as tm','p.tipo_modelo_id','=','tm.id_tipo_modelo','left',false)
        ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->select('id_propiedad','nombre','direccion','pa.pais','e.estado','c.ciudad','p.precio','metros_contrato','terreno_metros','construccion_metros','enganche','tm.tipo_modelo','n.nivel','n.plano as nivel_foto','ep.estatus_propiedad','precio','mts_interior','mts_exterior','mts_total','up.uso_propiedad', 'cajones_estacionamiento','area_rentable_metros')
        ->where('id_propiedad','=',$prospecto->propiedad_id)
        ->first();
        $empresa = DB::table('empresa')
        ->select('id_empresa','razon_social')
        ->first();

        if (empty($empresa)) {
            return back()->with('msj','Aun no has dado de alta una empresa');
        }
        $lugar = 'Guadalajara, Jalisco, México';
        $nombreagente = $prospecto->nombre_agente;
        $nombrepropiedad = $propiedad->nombre;
        $nivel = $propiedad->nivel;
        $tipo_modelo = $propiedad->tipo_modelo;
        $mts_total = $propiedad->mts_total;
        $cajones_estacionamiento = $propiedad->cajones_estacionamiento;
        $direccionpropiedad = $propiedad->direccion.', '.$propiedad->ciudad.', '.$propiedad->estado.', '.$propiedad->pais;
        $precio_propiedad = round( $propiedad->precio - ($propiedad->precio * ($prospecto->porcentaje_descuento /100)), 2);
        $uso_propiedad = $propiedad->uso_propiedad;
        $imagen_propiedad = DB::table('imagen_propiedad')
            ->select('titulo','imagen_path')
            ->where('propiedad_id',$prospecto->propiedad_id)
            ->get();
        $imagen_path = '';
        $nivel_path = '';
        foreach ($imagen_propiedad as $key) {
            if (strpos($key->titulo, 'Info') !== false) {
                $imagen_path = $key->imagen_path;
            }
            if (strpos($key->titulo, 'Nivel') !== false) {
                $nivel_path = $key->imagen_path;
            }
        }
        /*Condiciones de entrega*/
        $condiciones_entrega = array('Puertas exteriores e interiores.','Piso tipo porcelanto.','Luminarias en plafón. No incluye luminarias decorativas.','Muros divisorios de block acabado liso y pintura.','Muros interiores de tablaroca, acabado liso y pintura','Zoclos.','Barandal de vidrio templado y aluminio en terraza.','Equipo de aire acondicionado.','Instalaciones hidráulicas y eléctricas.','Centro de carga y accesorios eléctricos.','Muebles y accesorios de baño.');
        if ($uso_propiedad == 'Local comercial') {
            $objetoAdjudicado = 'EL LOCAL';
            $condiciones_entrega[] = 'Obra gris en el interior con fachada exterior terminada.'; 
        }
        elseif ($uso_propiedad == 'Departamento') {
            $objetoAdjudicado = 'EL DEPARTAMENTO';
        }
        elseif ($uso_propiedad == 'Oficina') {
            $objetoAdjudicado = 'LA OFICINA';
            $condiciones_entrega[] = 'Obra gris en el interior.';
            $condiciones_entrega[] = 'Puntas de los servicios al pie de la oficina';
        }else{
            $objetoAdjudicado = 'OBJETO ADJUDICADO';
        }
        
        $li_condiciones ='';
        foreach ($condiciones_entrega as $key) {
            $li_condiciones = $li_condiciones.'<li>'.$key.'</li>';
        }
        $tercerpagina_html = '<div style="color: #150703;"><ol>'.$li_condiciones.'</ol>
            <p style="font-weight:normal; font-size:12pt; text-align:left;">** Los valores presentados en esta cotización son informativos y podrán cambiar sin aviso previo</p>
            <p style="font-weight:normal; font-size:12pt; text-align:left;">**La vigencia de la presente propuesta será de 15 días a partir de la elaboración de la misma.</p>
            </div>';
        $precio_propiedad_letras = NumeroALetras::convertir($precio_propiedad,'Moneda Nacional');
        $monto_venta = $prospecto->monto_venta;
        $porcentaje_descuento = $prospecto->porcentaje_descuento;
        $porcentaje_enganche = $prospecto->porcentaje_enganche;
        $porcentaje_contraentrega = $prospecto->porcentaje_contraentrega;
        $num_plazos = $prospecto->num_plazos;
        if ($num_plazos == '' or $num_plazos== null or $num_plazos == 0) {
            $num_plazos = 1;
        }
        $precio_final = round($monto_venta - ( $monto_venta * ( $porcentaje_descuento / 100) ), 2);
        /*oepraciones que se hacen en abse al precio final*/
        $porcentaje_mensualidad = 100 - ($porcentaje_enganche + $porcentaje_contraentrega);
        $mensualidad_cliente = round( ( $precio_final * ($porcentaje_mensualidad / 100)) / $num_plazos , 2);
        $mensualidades_cliente = round( ( $precio_final * ($porcentaje_mensualidad / 100)) , 2);
        $mensualidad_letras = NumeroALetras::convertir($mensualidad_cliente,'Moneda Nacional');
        $montoenganche = round($precio_final * ($porcentaje_enganche / 100), 2);
        $montoenganche_letras = NumeroALetras::convertir($montoenganche,'Moneda Nacional');
        $contraentrega_cliente = round( $precio_final * ($porcentaje_contraentrega / 100), 2); 
        $contraentrega_letras = NumeroALetras::convertir($contraentrega_cliente,'Moneda Nacional');
        $contraentrega_estatus = 'Pendiente'; 
        $mensualidad_estatus ='Pendiente';
        $mensualidades_estatus ='Pendiente';
        $estatus_enganche = 'Pendiente';

        $mts_terreno = $propiedad->terreno_metros;
        $mts_contruccion = $propiedad->construccion_metros;
        $mts_total = $propiedad->mts_total;
        $mts_interior = $propiedad->mts_interior;
        $mts_exterior = $propiedad->mts_exterior;
        $valor_cajon_propiedad = '0.00';
        $valor_cajones_propiedad = '0.00';
        $num_cajones_propiedad = $propiedad->cajones_estacionamiento;
        $area_rentable = $propiedad->area_rentable_metros;
        $iva_cajones = '0.00';
        /*Comisiones*/
        $porcentaje_comision = '0.00';
        $total_comision = '0.00';
        $fecha_pago_comision = 'NA';
        /*Prospecto*/
        $nombrecliente = $prospecto->nombre;
        $telefonocliente = $prospecto->telefono;
        $correocliente = $prospecto->correo;
        $broker_cliente = $prospecto->oficina_broker;
        $vendedor_cliente = $prospecto->nombre_agente;
        $domicilioprospecto = $prospecto->domicilio;
        $comentarios_cliente = $prospecto->observaciones;
        $rfc_cliente = $prospecto->rfc;
        $plazos = $prospecto->num_plazos;
        $nacionalidad = $prospecto->nacionalidad;
        $fecha_contrato_cliente = $prospecto->fecha_contrato;
        $fecha_entrega_propiedad = $prospecto->fecha_entrega_propiedad;
        $fuente = 'Calibri';
        $nombreempresa = $empresa->razon_social;
        $dia_contrato = date('j', strtotime($fecha_contrato_cliente));
        $mes_contrato = date('m', strtotime($fecha_contrato_cliente));
        $year_contrato = date('Y', strtotime($fecha_contrato_cliente));
        if ($mes_contrato=='01') {$mescontrato_letra='Enero';}
        if ($mes_contrato=='02') {$mescontrato_letra='Febrero';}
        if ($mes_contrato=='03') {$mescontrato_letra='Marzo';}
        if ($mes_contrato=='04') {$mescontrato_letra='Abril';}
        if ($mes_contrato=='05') {$mescontrato_letra='Mayo';}
        if ($mes_contrato=='06') {$mescontrato_letra='Junio';}
        if ($mes_contrato=='07') {$mescontrato_letra='Julio';}
        if ($mes_contrato=='08') {$mescontrato_letra='Agosto';}
        if ($mes_contrato=='09') {$mescontrato_letra='Septiembre';}
        if ($mes_contrato=='10') {$mescontrato_letra='Octubre';}
        if ($mes_contrato=='11') {$mescontrato_letra='Noviembre';}
        if ($mes_contrato=='12') {$mescontrato_letra='Diciembre';}
        $calendario_pagos = '<table border="0">
            <tr style="background-color:#D7D6D6;"><th>PAGOS</th><th align="center" valign="center">MENSUALIDAD</th><th align="center" valign="center">MONTO (MXN)</th><th align="center" valign="center">FECHA</th></tr>';
        /*Traemos todos los plazos de pago para e calendario*/
        $contador = 0;
        $suma_total = 0;
        foreach ($plazos_pago as $key) {
            $contador ++;
            $suma_total = $suma_total + $key->total;
            $calendario_pagos = $calendario_pagos.'<tr><td>Pago '.$key->num_plazo.'</td><td align="center" valign="center">'.($key->num_plazo - 1).'</td><td align="center" valign="center">$'.number_format($key->total, 2 , "." , ",").'</td><td align="center" valign="center">'.date('Y-m-d',strtotime($key->fecha)).'</td></tr>';
        }
        $calendario_pagos = $calendario_pagos.'<tr style="background-color:#D8FBC0;"><td>Valor total</td><td></td><td align="center" valign="center">'.number_format($suma_total, 2 , "." , ",").'</td><td></td></tr></table>';

        PDF::SetAuthor('Nextapp');
        PDF::SetTitle('Cedula');
        PDF::SetSubject($nombreagente);
        PDF::SetKeywords('Contrato, PDF', 'compraventa');

        // set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        // remove default footer
        PDF::setPrintFooter(false);

        // set auto page breaks
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        PDF::SetFont('times', '', 48);

        // add a page
        PDF::AddPage();

        // -- set new background ---

        // get the current page break margin
        $bMargin = PDF::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = PDF::getAutoPageBreak();
        // disable auto-page-break
        PDF::SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        PDF::setPageMark();
        /*Documentacion*/
        // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
        // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
        $logo_file = 'assets/images/logo.jpg';
        /*Logo*/
        PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Print a text
        $htmlTITULO = '<div style="color: #000; font-weight:normal; font-size:10pt;">
            <TABLE WIDTH="100%"  style="border: 1px solid #000;">
                <tr><th colspan="4" valign="middle" style="font-weight:bold; border:1px solid #000;" align="center">CÉDULA CONTRATO PROMESA DE ADJUDICACIÓN</th></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Cliente</td><td colspan="2" align="center" valign="middle">'.$nombrecliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Teléfono</td><td colspan="2" align="center" valign="middle">'.$telefonocliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">@</td><td colspan="2" align="center" valign="middle">'.$correocliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">RFC</td><td colspan="2" align="center" valign="middle">'.$rfc_cliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Broker</td><td colspan="2" align="center" valign="middle">'.$broker_cliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Vendedor</td><td colspan="2" align="center" valign="middle">'.$vendedor_cliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Fecha de firma de contrato</td><td colspan="2" align="center" valign="middle">'.$fecha_contrato_cliente.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Área</td><td colspan="2" align="center" valign="middle"></td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">No. Unidad</td><td colspan="2" align="center" valign="middle">'.$nombrepropiedad.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Tipo</td><td colspan="2" align="center" valign="middle">'.$tipo_modelo.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Nivel</td><td colspan="2" align="center" valign="middle">'.$nivel.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">M2</td><td colspan="2" align="center" valign="middle">'.$mts_total.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">M2 Interior</td><td colspan="2" align="center" valign="middle">'.$mts_interior.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">M2 Terraza</td><td colspan="2" align="center" valign="middle">'.$mts_exterior.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Valor cajón de estacionamiento</td><td colspan="2" align="center" valign="middle">'.number_format($valor_cajon_propiedad, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">No. De cajones</td><td colspan="2" align="center" valign="middle">'.$num_cajones_propiedad.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Valor de cajones</td><td colspan="2" align="center" valign="middle">'.number_format($valor_cajones_propiedad, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Valor de la unidad</td><td colspan="2" align="center" valign="middle">'.number_format($precio_propiedad, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Valor total de la unidad</td><td colspan="2" align="center" valign="middle">'.number_format($precio_propiedad, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">IVA Cajones</td><td colspan="2" align="center" valign="middle">'.number_format($iva_cajones, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Fecha de entrega</td><td colspan="2" align="center" valign="middle">'.$fecha_entrega_propiedad.'</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold; border:1px solid #000;">ESQUEMA DE PAGO</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Valor de la Operación</td><td colspan="2" align="center" valign="middle">'.number_format($precio_final, 2 , "." , ",").'</td></tr>
                <tr style="background-color:#E7E9E6;"><td style="font-weight:bold; text-indent: 10px;"></td><td align="center" valign="middle">%</td><td align="center" valign="middle">$</td><td align="center" valign="middle">Estatus</td></tr>
                <tr><td style="font-weight:bold; text-indent: 10px;">Enganche</td><td align="center" valign="middle">'.$porcentaje_enganche.'</td><td align="center" valign="middle">'.number_format($montoenganche, 2 , "." , ",").'</td><td align="center" valign="middle">'.$estatus_enganche.'</td></tr>
                <tr><td style="font-weight:bold; text-indent: 10px;">Mensualidades</td><td align="center" valign="middle">'.$porcentaje_mensualidad.'</td><td align="center" valign="middle">'.number_format($mensualidades_cliente, 2 , "." , ",").'</td><td align="center" valign="middle">'.$mensualidades_estatus.'</td></tr>
                <tr><td style="font-weight:bold; text-indent: 10px;">No. mensualidades</td><td align="center" valign="middle">'.$plazos.'</td><td align="center" valign="middle">'.number_format($mensualidad_cliente, 2 , "." , ",").'</td><td align="center" valign="middle">'.$mensualidad_estatus.'</td></tr>
                <tr><td style="font-weight:bold; text-indent: 10px;">Contra entrega</td><td align="center" valign="middle">'.$porcentaje_contraentrega.'</td><td align="center" valign="middle">'.number_format($contraentrega_cliente, 2 , "." , ",").'</td><td align="center" valign="middle">'.$contraentrega_estatus.'</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold; border:1px solid #000;">COMISIÓN DE  BROKER EXTERNO</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                 <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Porcentaje</td><td colspan="2" align="center" valign="middle">'.$porcentaje_comision.'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Total de comisión</td><td colspan="2" align="center" valign="middle">'.number_format($total_comision, 2 , "." , ",").'</td></tr>
                <tr><td colspan="2" style="font-weight:bold; text-indent: 10px;">Fecha de pago</td><td colspan="2" align="center" valign="middle">'.$fecha_pago_comision.'</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold; border:1px solid #000;">COMENTARIOS ADICIONALES</td></tr>
                <tr><td valign="middle" align="center" height="220" colspan="4" style="font-weight:bold; border:1px solid #000;">'.$comentarios_cliente.'</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold; border:1px solid #000;">CALENDARIO DE PAGOS</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;">'.$calendario_pagos.'</td></tr>
                <tr><td valign="middle" align="center" colspan="4" style="font-weight:bold;"></td></tr>

            </TABLE>
        </div>';

        //PDF::writeHTML($html, true, false, true, false, '');
        PDF::SetTextColor(0, 0, 0, 0);
        PDF::SetFont('helveticaB', '', 24);
        PDF::writeHTMLCell(165, 30, 20, 30 , $htmlTITULO, 0, 0, 0, false, 'L', false);
        /*logo pagina*/
            PDF::AddPage();
            // get the current page break margin
            $bMargin = PDF::getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = PDF::getAutoPageBreak();
            // disable auto-page-break
            PDF::SetAutoPageBreak(false, 0);
            // restore auto-page-break status
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            PDF::setPageMark();
            // Logo
            /*Logo*/
            PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        /////finalizar pdf

        $namePDF = uniqid().'cedula_compraventa.pdf';
        //Close and output PDF document
        PDF::Output($namePDF, 'I');
        return back();
    }
    public function v_apartado(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }

        $filtro = $this->build_filtro($request);
        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.fecha_apartado','p.monto_apartado')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','Apartado')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();

        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.apartado',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_pagando(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }

        $filtro = $this->build_filtro($request);

        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.monto_ultimo_pago','p.fecha_ultimo_pago','p.num_plazos','p.pagado')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','Pagando')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();

        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.pagando',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_contrato(request $request)
    {
        $nombre = $request->get('nombre_bs');
        $agente = $request->get('agente_bs');
        $correo = $request->get('correo_bs');
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }

        $filtro = $this->build_filtro($request);
        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','Contrato')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.contrato',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_escriturado(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }

        $filtro = $this->build_filtro($request);
        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.fecha_escrituracion')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','Escriturado')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();

        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.escriturado',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_no_escriturado(request $request)
    {
       
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($request);

        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','No escriturado')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.no_escriturado',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_prospecto(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($request);

        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel')
        ->orderby('fecha_registro','DESC')
        ->whereRaw($filtro)
        ->where('e.estatus_crm','=','Prospecto')
        ->orwhere('e.estatus_crm','=','Visita')
        ->orwhere('e.estatus_crm','=','Cotizacion')
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.prospecto',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_perdido(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($request);

        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','mp.motivo_perdida')
        ->orderby('fecha_registro','DESC')
        ->whereRaw($filtro)
        ->where('e.estatus_crm','=','Perdido')
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.perdido',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function v_postergado(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $filtro = $this->build_filtro($filtro);
        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
        ->join('users as u','p.asesor_id','=','u.id','left',false)
        ->select('id_prospecto', 'p.nombre','rfc','correo','telefono','telefono_adicional','p.asesor_id','p.propiedad_id','p.proyecto_id','folio','p.estatus','p.fecha_registro','observaciones','fecha_visita','medio_contacto_id', 'e.estatus_crm as estatus_prospecto', 'prop.nombre as nombre_propiedad','py.nombre as nombre_proyecto','mc.medio_contacto','u.name as nombre_agente', 'extension','e.nivel','p.fecha_recontacto')
        ->orderby('fecha_registro','DESC')
        ->where('e.estatus_crm','=','Postergado')
        ->whereRaw($filtro)
        ->paginate($rows_page);

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get();

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        $tipos = array('Fisica','Moral');
        $monedas = DB::table('moneda')
        ->get();

        $users = DB::table('users')->join('prospectos', 'asesor_id','=','id')->select('id','name')->groupby('asesor_id')->get();

        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm')
        ->orderby('estatus_crm','ASC')
        ->get();

        return view('prospectos.postergado',compact('prospectos','proyectos','medios_contacto','request','tipos','monedas','rows_pagina','users','estatus_crm') );
    }
    public function exportExcel(request $request)
    {   
        $nombre_bs = $request->get('nombre_excel');
        $estatus_bs = $request->get('estatus_excel');
        $agente_bs = $request->get('agente_excel');
        $medio_contacto_bs = $request->get('tipo_operacion_excel');
        $fecha_escrituracion_min_bs = $request->get('fecha_escrituracion_min_excel');
        $fecha_escrituracion_max_bs = $request->get('fecha_escrituracion_max_excel');
        $fecha_venta_min_bs = $request->get('fecha_venta_min_excel');
        $fecha_venta_max_bs = $request->get('fecha_venta_max_excel');
        $fecha_visita_min_bs = $request->get('fecha_visita_min_excel');
        $fecha_visita_max_bs = $request->get('fecha_visita_max_excel');
        $fecha_registro_min_bs = $request->get('fecha_registro_min_excel');
        $fecha_registro_max_bs = $request->get('fecha_registro_max_excel');

        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');


        $filtro = ' p.nombre LIKE "%'.$nombre_bs.'%"';
        if ($estatus_bs != '' and $estatus_bs != 'Vacio' and $estatus_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.estatus IN (';
            $i =0;
            foreach ($estatus_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($agente_bs != '' and $agente_bs != 'Vacio' and $agente_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.asesor_id IN (';
            $i =0;
            foreach ($agente_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($medio_contacto_bs != '' and $medio_contacto_bs != 'Vacio' and $medio_contacto_bs!= null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.medio_contacto_id IN (';
            $i =0;
            foreach ($medio_contacto_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($fecha_escrituracion_max_bs != '' and $fecha_escrituracion_min_bs != '' and $fecha_escrituracion_max_bs != null and $fecha_escrituracion_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_escrituracion between '".$fecha_escrituracion_min_bs."' AND '". $fecha_escrituracion_max_bs."'";
        }
        if ($fecha_venta_max_bs != '' and $fecha_venta_min_bs != '' and $fecha_venta_max_bs != null and $fecha_venta_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_venta between '".$fecha_venta_min_bs."' AND '". $fecha_venta_max_bs."'";
        }
        if ($fecha_registro_max_bs != '' and $fecha_registro_min_bs != '' and $fecha_registro_max_bs != null and $fecha_registro_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_registro between '".$fecha_registro_min_bs."' AND '". $fecha_registro_max_bs."'";
        }
        if ($fecha_visita_max_bs != '' and $fecha_visita_min_bs != '' and $fecha_visita_max_bs != null and $fecha_visita_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_visita between '".$fecha_visita_min_bs."' AND '". $fecha_visita_max_bs."'";
        }
        $data_result = DB::table('prospectos as p')
            ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('medio_contacto as mc','p.medio_contacto_id','=','mc.id_medio_contacto','left',false)
            ->join('motivo_perdida as mp','p.motivo_perdida_id','=','mp.id_motivo_perdida','left',false)
            ->join('tipo_operacion as to','p.tipo_operacion_id','=','to.id_tipo_operacion','left',false)
            ->join('moneda as m','p.moneda_id','=','m.id_moneda','left',false)
            ->join('users as u','p.asesor_id','=','u.id','left',false)
            ->join('users as uc','p.cerrador','=','uc.id','left',false)
            ->join('esquema_comision as ec','p.esquema_comision_id','=','ec.id_esquema_comision','left',false)
            ->select('p.id_prospecto', 'p.nombre', 'p.rfc', 'p.correo', 'p.telefono', 'p.telefono_adicional', 'p.extension', 'u.name as asesor_id', 'p.razon_social', 'p.domicilio', 'p.tipo', 'prop.nombre as propiedad_id', 'py.nombre as proyecto_id', 'p.folio', 'e.estatus_crm as estatus', 'p.fecha_registro', 'p.fecha_recontacto', 'p.observaciones', 'p.fecha_visita', 'mc.medio_contacto as medio_contacto_id', 'p.fecha_cotizacion', 'p.fecha_apartado', 'p.monto_apartado', 'p.fecha_venta', 'p.monto_venta', 'p.fecha_enganche', 'p.monto_enganche', 'to.tipo_operacion as tipo_operacion_id', 'mp.motivo_perdida as motivo_perdida_id', 'uc.name as cerrador', 'p.num_plazos', 'p.fecha_ultimo_pago', 'p.monto_ultimo_pago', 'p.fecha_escrituracion', 'ec.esquema_comision as esquema_comision_id', 'p.comision_id', 'p.interes', 'p.mensualidad', 'p.capital', 'p.pagado', 'p.vigencia_contrato', 'p.fecha_contrato', 'p.num_contrato', 'p.saldo', 'p.porcentaje_interes', 'm.siglas as moneda_id', 'p.cuenta_externa', 'p.nacionalidad')
            ->orderby('id_prospecto','ASC')
            ->whereRaw($filtro)
            ->get();
        $campos = array('id_prospecto','nombre','rfc','correo','telefono','telefono_adicional','extension','asesor_id','razon_social','domicilio','tipo','propiedad_id','proyecto_id','folio','estatus','fecha_registro','fecha_recontacto','observaciones','fecha_visita','medio_contacto_id','fecha_cotizacion','fecha_apartado','monto_apartado','fecha_venta','monto_venta','fecha_enganche','monto_enganche','tipo_operacion_id','motivo_perdida_id','cerrador','num_plazos','fecha_ultimo_pago','monto_ultimo_pago','fecha_escrituracion','esquema_comision_id','comision_id','interes','mensualidad','capital','pagado','vigencia_contrato','fecha_contrato','num_contrato','saldo','porcentaje_interes','moneda_id','cuenta_externa','nacionalidad');

        ob_end_clean();
        return Excel::download(new ProspectoExport("exports.prospectos", $data_result, $campos),'Prospectos.xlsx');
    }
    protected  function build_filtro($request){
        $nombre_bs = $request->get('nombre_bs');
        $estatus_bs = $request->get('estatus_bs');
        $medio_contacto_bs = $request->get('medio_contacto_bs');
        $agente_bs = $request->get('agente_bs');
        $interesado_en_bs = $request->get('interesado_en_bs');
        $fecha_venta_min_bs = $request->get('fecha_venta_min_bs');
        $fecha_venta_max_bs = $request->get('fecha_venta_max_bs');
        $fecha_visita_min_bs = $request->get('fecha_visita_min_bs');
        $fecha_visita_max_bs = $request->get('fecha_visita_max_bs');
        $fecha_registro_min_bs = $request->get('fecha_registro_min_bs');
        $fecha_registro_max_bs = $request->get('fecha_registro_max_bs');
        $fecha_escrituracion_min_bs = $request->get('fecha_escrituracion_min_bs');
        $fecha_escrituracion_max_bs = $request->get('fecha_escrituracion_max_bs');

        $agente_actual_bs = auth()->user()->id;
        $filtro = ' p.nombre LIKE "%'.$nombre_bs.'%"';
        if (auth()->user()->rol != 3 /*Gerente*/ and auth()->user()->rol != 6 /*Cobranza*/) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro.' (p.asesor_id = '.$agente_actual_bs. ')';
        }
        if ($estatus_bs != '' and $estatus_bs != 'Vacio' and $estatus_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.estatus IN (';
            $i =0;
            foreach ($estatus_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($agente_bs != '' and $agente_bs != 'Vacio' and $agente_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.asesor_id IN (';
            $i =0;
            foreach ($agente_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($medio_contacto_bs != '' and $medio_contacto_bs != 'Vacio' and $medio_contacto_bs!= null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' p.medio_contacto_id IN (';
            $i =0;
            foreach ($medio_contacto_bs as $key) {
                if ($i == 0) {
                    $filtro = $filtro."'".$key."'";
                }else{
                    $filtro = $filtro.",'".$key."'";
                }
                $i++;
            }
            $filtro = $filtro .')';
        }
        if ($fecha_escrituracion_max_bs != '' and $fecha_escrituracion_min_bs != '' and $fecha_escrituracion_max_bs != null and $fecha_escrituracion_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_escrituracion between '".$fecha_escrituracion_min_bs."' AND '". $fecha_escrituracion_max_bs."'";
        }
        if ($fecha_venta_max_bs != '' and $fecha_venta_min_bs != '' and $fecha_venta_max_bs != null and $fecha_venta_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_venta between '".$fecha_venta_min_bs."' AND '". $fecha_venta_max_bs."'";
        }
        if ($fecha_registro_max_bs != '' and $fecha_registro_min_bs != '' and $fecha_registro_max_bs != null and $fecha_registro_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_registro between '".$fecha_registro_min_bs."' AND '". $fecha_registro_max_bs."'";
        }
        if ($fecha_visita_max_bs != '' and $fecha_visita_min_bs != '' and $fecha_visita_max_bs != null and $fecha_visita_min_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . " p.fecha_visita between '".$fecha_visita_min_bs."' AND '". $fecha_visita_max_bs."'";
        }

        return $filtro;
    }
}
