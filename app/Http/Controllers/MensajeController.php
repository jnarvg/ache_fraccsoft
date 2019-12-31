<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Mensaje;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use Mail;


class MensajeController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $rows_pagina = array('10','25','50','100');
        $word = $request->get('word_bs');
        $rows_page = $request->get('rows_per_page');
        if ($rows_page == '') {
            $rows_page = 10;
        }

        $mensajes = DB::table('mensaje as m')
        ->join('users as d','dirigido_id','=','d.id','left',false)
        ->join('users as r','creador_id','=','r.id','left',false)
        ->select('id_mensaje', 'titulo', 'nota','dirigido_id','creador_id','m.prospecto_id','m.fecha','m.estatus','d.name as destinatario','r.name as remitente')
        ->orderby('fecha','DESC')
        ->where('titulo','LIKE',"%$word%")
        ->paginate($rows_page);

        return view('mensaje.index',['mensajes'=>$mensajes, 'request'=>$request,'rows_pagina'=>$rows_pagina]);
    }
    public function store(request $request)
    {
        $mensaje = new Mensaje();
        $mensaje->titulo = $request->get('titulo');
        $mensaje->fecha = $request->get('fecha');
        $mensaje->nota = $request->get('nota');
        $mensaje->estatus = $request->get('estatus');
        $mensaje->dirigido_id = $request->get('dirigido');
        $mensaje->creador_id = $request->get('creador');
        $mensaje->prospecto_id = $request->get('prospecto');
        $id_prospecto = $request->get('prospecto');

        $mensaje->save();
        return Redirect::to("prospectos/show/".$id_prospecto);
    }
    public function show($id, $ruta)
    {
        $m = DB::table('mensaje')
        ->select('id_mensaje', 'titulo', 'nota','dirigido_id','creador_id','prospecto_id','fecha','estatus')
        ->where('id_mensaje','=',$id)
        ->first();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usuarios = DB::table('users')
        ->select('id', 'name')
        ->orderby('name','asc')
        ->get();

        $estatus = array('Borrador','Enviado');

        return view('mensaje.show',compact('m','prospectos','usuarios', 'estatus','ruta'));
    }
    public function update(request $request, $id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $mensaje->titulo = $request->get('titulo');
        $mensaje->fecha = $request->get('fecha');
        $mensaje->nota = $request->get('nota');
        $mensaje->dirigido_id = $request->get('dirigido');
        $mensaje->creador_id = $request->get('creador');
        $mensaje->prospecto_id = $request->get('prospecto');

        $id_prospecto = $request->get('prospecto');
        $mensaje->save();
        return back();
    }
    public function destroy($id)
    {
        $mensaje = Mensaje::find($id);

        $id_prospecto = $mensaje->prospecto_id;
        if ($mensaje->estatus == 'Borrador') {
            $mensaje->delete();
            return back()->with('msj','Mensaje eliminado');
        }else{
            return back()->with('msj','No se puede eliminar el mensaje, ya ha sido enviado');
        }
    }
    public function enviar($id)
    {
        $mensaje = DB::table('mensaje')
        ->select('id_mensaje', 'titulo', 'nota','dirigido_id','creador_id','prospecto_id','fecha')
        ->where('id_mensaje','=',$id)
        ->first();
        $prospecto = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->where('id_prospecto',$mensaje->prospecto_id)
        ->first();
        /*Destinatario*/
        $usser_d = DB::table('users')
        ->select('id', 'name','email')
        ->where('id',$mensaje->dirigido_id)
        ->first();
        $destinatarioCorreo = $usser_d->email;
        $destinatario = $usser_d->name;

        /*Remitente*/
        $usser_r = DB::table('users')
        ->select('id', 'name','email')
        ->where('id',$mensaje->creador_id)
        ->first(); 
        $remitente = $usser_r->name;
        /*Envio de correo a las personas*/
        $CorreEmisor = 'bismoservicios@gmail.com';
        $data = array(
            'titulo' => $mensaje->titulo,
            'nota' => $mensaje->nota,
            'remitente' => $remitente,
            'destinatario' => $destinatario,
            'fecha' => $mensaje->fecha,
            'prospecto' => $prospecto->nombre,
        );

        Mail::send('emails.mensaje', $data, function ($message) use ($CorreEmisor, $destinatarioCorreo, $remitente) {
            $message->from($CorreEmisor, $remitente);

            $message->to($destinatarioCorreo)->subject('Nuevo mensaje en fraccsoft');
        });
        $mensaje = Mensaje::find($id);
        $mensaje->estatus = 'Enviado';
        $mensaje->update();
        return back()->with('msj','Mensaje enviado');
    }
}
