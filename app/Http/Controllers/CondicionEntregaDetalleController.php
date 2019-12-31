<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\CondicionEntregaDetalle;
use App\CondicionEntrega;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Http\Middleware\Authenticate;

class CondicionEntregaDetalleController extends Controller
{
  //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $word = $request->get('word_bs');

        $resultados = DB::table('condicion_entrega_detalle as c')
        ->join('proyecto as p','c.proyecto_id','=','p.id_proyecto','left',false)
        ->join('uso_propiedad as u','c.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
        ->join('condicion_entrega as ce','c.condicion_entrega_id','=','ce.id_condicion_entrega','left',false)
        ->select('id_condicion_entrega_detalle', 'c.condicion','tipo','c.modified_date' , 'c.uso_propiedad_id','c.proyecto_id','u.uso_propiedad','p.nombre as proyecto','c.created_date','ce.titulo','subtitulo')
        ->orderby('c.id_condicion_entrega_detalle','DESC')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->orderby('uso_propiedad','asc')
        ->get();

        $tipo_condicion = array('Bullet','Nota','Agregado');
        $condicion_entrega = DB::table('condicion_entrega')
        ->select('id_condicion_entrega', 'titulo')
        ->get();
        if (Auth::check()) {
          $rol = auth()->user()->rol;
          $id = auth()->user()->id;
          return view('catalogos.condicion_entrega_detalle.index', compact('resultados', 'proyectos','usos_propiedad','condicion_entrega','request','rol','id','tipo_condicion'));
        }else{
          return redirect()->route('welcome');
        }
    }
    public function store(request $request)
    {
        $condicion = new CondicionEntregaDetalle();
        $condicion->uso_propiedad_id = $request->get('uso_propiedad_id');
        $condicion->proyecto_id = $request->get('proyecto_id');
        $condicion->condicion = $request->get('condicion');
        $condicion->tipo = $request->get('tipo');
        $condicion->subtitulo = $request->get('subtitulo');
        $condicion->condicion_entrega_id = $request->get('condicion_entrega_id');
        $condicion->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function show($id, $procedencia)
    {
        $resultado = DB::table('condicion_entrega_detalle as c')
        ->select('c.id_condicion_entrega_detalle', 'c.condicion','tipo','c.modified_date' , 'c.uso_propiedad_id','c.proyecto_id','c.created_date','c.modified_date','condicion_entrega_id','subtitulo')
        ->where('c.id_condicion_entrega_detalle', $id)
        ->first();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->orderby('uso_propiedad','asc')
        ->get();

        $condicion_entrega = DB::table('condicion_entrega')
        ->select('id_condicion_entrega', 'titulo')
        ->get();

        $tipo_condicion = array('Bullet','Nota','Agregado');

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('catalogos.condicion_entrega_detalle.show',compact('resultado','usos_propiedad','proyectos', 'condicion_entrega','procedencia','rol','id','tipo_condicion'));
    }
    public function update(request $request, $id)
    {
        $condicion = CondicionEntregaDetalle::findOrFail($id);
        $condicion->uso_propiedad_id = $request->get('uso_propiedad_id');
        $condicion->proyecto_id = $request->get('proyecto_id');
        $condicion->condicion = $request->get('condicion');
        $condicion->tipo = $request->get('tipo');
        $condicion->subtitulo = $request->get('subtitulo');
        $condicion->condicion_entrega_id = $request->get('condicion_entrega_id');
        $condicion->modified_date = date('Y-m-d H:i');
        $condicion->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function destroy($id)
    {
        $contacto = CondicionEntregaDetalle::find($id);
        $contacto->delete();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
