<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Actividad;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Http\Middleware\Authenticate;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Carbon\Carbon;

class ActividadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $titulo = $request->get('titulo_bs');
        $tipo = $request->get('tipo_bs');
        $estatus = $request->get('estatus_bs');
        $fecha = $request->get('fecha_bs');
        $prospecto = $request->get('prospecto_bs');
        $rows_page = $request->get('rows_per_page');
        if ($rows_page == '') {
            $rows_page = 10;
        }

        
        if ($prospecto) {
            if (auth()->user()->rol == 3 /*Gerente*/) {
                $actividades = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto','a.fecha_recordatorio')
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.fecha','LIKE',"%$fecha%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->where('p.nombre','LIKE',"%$prospecto%")
                ->orderby('fecha','DESC')
                ->paginate($rows_page);
            }else{
                $actividades = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto','a.fecha_recordatorio')
                ->where('a.agente_id','=',auth()->user()->id)
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.fecha','LIKE',"%$fecha%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->where('p.nombre','LIKE',"%$prospecto%")
                ->orderby('fecha','DESC')
                ->paginate($rows_page);
            }
        }else{
            if (auth()->user()->rol == 3 /*Gerente*/) {
                $actividades = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto','a.fecha_recordatorio')
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.fecha','LIKE',"%$fecha%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->orderby('fecha','DESC')
                ->paginate($rows_page);
            }else{
                $actividades = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto','a.fecha_recordatorio')
                ->where('a.agente_id','=',auth()->user()->id)
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.fecha','LIKE',"%$fecha%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->orderby('fecha','DESC')
                ->paginate($rows_page);
            }
        }

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $estatus = array('Pendiente','Cancelada','Postergada','Completada');
        $tipos = array('Tarea','Llamada','Cita','Correo');
        if (Auth::check())
        {
            $rol = auth()->user()->rol;
            $id = auth()->user()->id;

            return view('actividad.index',['actividades'=>$actividades,'prospectos'=>$prospectos,'request'=>$request,'rows_pagina'=>$rows_pagina,'estatus'=>$estatus,'tipos'=>$tipos,'rol'=>$rol,'id'=>$id]);
        }else{
            $rol = null;
            $id = null;
            return redirect()->route('welcome');
        }
    }
    public function calendario( request $request)
    {
        $proyecto = $request->get('proyecto_bs');
        $titulo = $request->get('titulo_bs');
        $tipo = $request->get('tipo_bs');
        $estatus = $request->get('estatus_bs');
        $prospecto = $request->get('prospecto_bs');

        $events = [];
        if ($prospecto) {
            if (auth()->user()->rol == 3 /*Gerente*/) {
                $data = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto', 'a.fecha_recordatorio')
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->where('p.nombre','LIKE',"%$prospecto%")
                ->orderby('fecha','DESC')
                ->get();
            }else{
                $data = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto', 'a.fecha_recordatorio')
                ->where('a.agente_id','=',auth()->user()->id)
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->where('p.nombre','LIKE',"%$prospecto%")
                ->orderby('fecha','DESC')
                ->get();
            }
        }else{
            if (auth()->user()->rol == 3 /*Gerente*/) {
                $data = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto', 'a.fecha_recordatorio')
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->orderby('fecha','DESC')
                ->get();
            }else{
                $data = DB::table('actividad as a')
                ->join('proyecto as py','a.proyecto_id','=','py.id_proyecto','left',false)
                ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
                ->join('users as u','a.agente_id','=','u.id','left',false)
                ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','py.nombre as proyecto', 'a.fecha_recordatorio')
                ->where('a.agente_id','=',auth()->user()->id)
                ->where('titulo','LIKE',"%$titulo%")
                ->where('a.estatus','LIKE',"%$estatus%")
                ->where('tipo_actividad','LIKE',"%$tipo%")
                ->orderby('fecha','DESC')
                ->get();
            }
        }
        
        if($data->count()) {
            foreach ($data as $key => $row) {
                if($row->estatus=='Pendiente'){
                    $color='#EE4E4E';
                }
                elseif($row->estatus=='Completada'){
                   $color='#4EEE88'; 
                }
                elseif($row->estatus=='Postergada'){
                   $color='#FFD433'; 
                }
                elseif($row->estatus=='Cancelada'){
                   $color='#303131'; 
                }
                $events[] = Calendar::event(
                    $row->titulo,
                    true,
                    new \DateTime($row->fecha),
                    new \DateTime($row->fecha.' +1 day'),
                    null,
                    // Add color and link on event
                    [
                        'color' => $color,
                        'url' => '/actividad/show/'.$row->id_actividad.'/Calendario',                    
                    ]
                );
            }
        }
        $calendar = Calendar::addEvents($events);

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->orderby('id_proyecto','ASC')
        ->get();
        if (Auth::check())
        {
            $rol = auth()->user()->rol;
            $id = auth()->user()->id;
            return view('actividad.calendarioActividades', compact('calendar','prospectos','proyectos','request','rol','id'));
        }else{
            $rol = null;
            $id = null;
            return redirect()->route('welcome');
        }

    }
    public function storec(request $request)
    {

        $actividad = new Actividad();
        $actividad->titulo = $request->get('nuevo_actividad_mdl');
        $actividad->tipo_actividad = $request->get('nuevo_tipo_mdl');
        $actividad->fecha = $request->get('nuevo_fecha_mdl');
        $actividad->fecha_recordatorio = $request->get('nuevo_fecha_recordatorio_mdl');
        $actividad->hora = $request->get('nuevo_hora_mdl');
        $actividad->duracion = $request->get('nuevo_duracion_mdl');
        $actividad->descripcion = $request->get('nuevo_desc_mdl');
        $actividad->agente_id = auth()->user()->id;
        $actividad->prospecto_id = $request->get('nuevo_prospecto_mdl');
        $actividad->save();
        return redirect()->route('calendario');
    }
    public function crear(request $request)
    {
        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        return view('actividad.CrearActividad',['prospectos'=>$prospectos]);
    }
    public function store(request $request, $procedencia)
    {
        $actividad = new Actividad();
        $actividad->titulo = $request->get('nuevo_actividad_mdl');
        $actividad->tipo_actividad = $request->get('nuevo_tipo_mdl');
        $actividad->fecha = $request->get('nuevo_fecha_mdl');
        $actividad->fecha_recordatorio = $request->get('nuevo_fecha_recordatorio_mdl');
        $actividad->hora = $request->get('nuevo_hora_mdl');
        $actividad->duracion = $request->get('nuevo_duracion_mdl');
        $actividad->descripcion = $request->get('nuevo_desc_mdl');
        $actividad->agente_id = auth()->user()->id;
        $actividad->prospecto_id = $request->get('nuevo_prospecto_mdl');
        $actividad->estatus = 'Pendiente';
        $id_prospecto= $request->get('nuevo_prospecto_mdl');
        $actividad->save();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }else{
            return back();
        }
    }

    public function show($id, $procedencia)
    {
        $actividad = DB::table('actividad as a')
        ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
        ->join('users as u','a.agente_id','=','u.id','left',false)
        ->select('id_actividad', 'titulo', 'fecha', 'hora','tipo_actividad','duracion','descripcion', 'a.agente_id','a.prospecto_id','a.estatus', 'p.nombre as nombre_prospecto', 'u.name as nombre_user','a.fecha_recordatorio')
        ->where('id_actividad','=',$id)
        ->first();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usuarios = DB::table('users')
        ->orderby('name','asc')
        ->get();
        if (Auth::check())
        {
            $rol = auth()->user()->rol;
            $id = auth()->user()->id;
            return view('actividad.show',compact('actividad','prospectos','usuarios','procedencia','rol','id'));
        }else{
            $rol = null;
            $id = null;
            return redirect()->route('welcome');
        }
    }
    public function update(request $request, $id, $procedencia)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->titulo = $request->get('titulo');
        $actividad->tipo_actividad = $request->get('tipo_actividad');
        $actividad->fecha_recordatorio = $request->get('fecha_recordatorio');
        $actividad->fecha = $request->get('fecha');
        $actividad->hora = $request->get('hora');
        $actividad->duracion = $request->get('duracion');
        $actividad->descripcion = $request->get('descripcion');
        $actividad->prospecto_id = $request->get('prospecto_id');
        $id_prospecto = $actividad->prospecto_id;
        $actividad->update();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }
        elseif($procedencia == 'Calendario'){
            return redirect()->route('calendario');
        }
        else{
            return back();
        }
    }
    public function destroy($id, $procedencia)
    {
        $actividad = Actividad::find($id);
        $id_prospecto = $actividad->prospecto_id;
        $actividad->delete();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }else{
            return back();
        }
    }
    public function completar($id, $procedencia)
    {
        $actividad = Actividad::find($id);
        $actividad->estatus = 'Completada';
        $id_prospecto = $actividad->prospecto_id;
        $actividad->update();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }
        elseif($procedencia == 'Calendario'){
            return redirect()->route('calendario');
        }
        else{
            return back();
        }
    }
    public function pendiente($id,  $procedencia)
    {
        $actividad = Actividad::find($id);
        $actividad->estatus = 'Pendiente';
        $id_prospecto = $actividad->prospecto_id;
        $actividad->update();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }
        elseif($procedencia == 'Calendario'){
            return redirect()->route('calendario');
        }
        else{
            return back();
        }
    }
    public function postergar($id, $procedencia)
    {
        $actividad = Actividad::find($id);
        $actividad->estatus = 'Postergada';
        $id_prospecto = $actividad->prospecto_id;
        $actividad->update();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }
        elseif($procedencia == 'Calendario'){
            return redirect()->route('calendario');
        }
        else{
            return back();
        }
    }
    public function cambiar_agente(request $request, $id, $procedencia)
    {
        $actividad = Actividad::find($id);
        $actividad->agente_id = $request->get('agente_cambiar');
        $id_prospecto = $actividad->prospecto_id;
        $actividad->update();
        if ($procedencia == 'Menu') {
            return redirect()->route('actividad');
            
        }
        elseif($procedencia == 'Calendario'){
            return redirect()->route('calendario');
        }
        else{
            return back();
        }
    }
    public function aviso_actividad()
    {
        //las actividades
        $configuracion = DB::table('configuracion_general')
        ->first();
        $CorreoEmisor = 'bismoservicios@gmail.com';
        $remitente = 'Notificaciones '.$configuracion->nombre_cliente;
        ///Seleccionamos todas las tarea de actividades
        $actividades_pendientes = \DB::table('actividad as a')
        ->join('users as u','a.agente_id','=','u.id','left',false)
        ->join('prospectos as p','a.prospecto_id','=','p.id_prospecto','left',false)
        ->select('a.id_actividad','a.titulo','a.fecha','a.hora','a.descripcion','a.tipo_actividad','a.fecha_recordatorio','u.name as responsable','u.email as correo_responsable','p.nombre as prospecto')
        ->where('a.estatus','Pendiente')
        ->where('a.estatus_alerta','Pendiente')
        ->where('a.fecha_recordatorio','=',date('Y-m-d'))
        ->get();

        foreach ($actividades_pendientes as $key) { 
            $destinatarioCorreo = $key->correo_responsable;
            $datework = Carbon::createFromDate($key->hora);
            $hora_atras = $datework->subMinute(30);
            // echo "<br/>.HORA-30: ".$hora_atras;
            // echo "<br/>.HORA: ".date('Y-m-d H:i');
            if (date('Y-m-d H:i', strtotime($hora_atras)) <= date('Y-m-d H:i') AND $key->fecha == date('Y-m-d') ) {
                $data = array(
                    'id_actividad' => $key->id_actividad,
                    'titulo' => $key->titulo,
                    'fecha' => $key->fecha,
                    'hora' => $key->hora,
                    'fecha_recordatorio' => $key->fecha_recordatorio,
                    'descripcion' => $key->descripcion,
                    'tipo_actividad' => $key->tipo_actividad,
                    'responsable' => $key->responsable,
                    'prospecto' => $key->prospecto,
                );
                Mail::send('emails.aviso_actividad', $data, function ($message) use ($CorreoEmisor, $destinatarioCorreo, $remitente) {
                    $message->from($CorreoEmisor, $remitente);
                    $message->to($destinatarioCorreo)->subject('Actividades pendientes');
                });

                $actividad = Actividad::findOrFail($key->id_actividad);
                $actividad->estatus_alerta = 'Enviada';
                $actividad->update();
            }
        }
        
        ///Mail::to('jgarcia@nextapp.com.mx')->send(new SendMailable($totalActividadesPendientes));
    }

}
