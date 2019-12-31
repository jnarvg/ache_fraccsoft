<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\CondicionEntrega;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Http\Middleware\Authenticate;

class CondicionEntregaController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $word = $request->get('word_bs');

        $resultados = DB::table('condicion_entrega as c')
        ->join('proyecto as p','c.proyecto_id','=','p.id_proyecto','left',false)
        ->join('uso_propiedad as u','c.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
        ->select('id_condicion_entrega', 'c.titulo', 'c.uso_propiedad_id','c.proyecto_id','u.uso_propiedad','p.nombre as proyecto','c.created_date')
        ->orderby('c.id_condicion_entrega','DESC')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->orderby('uso_propiedad','asc')
        ->get();

        if (Auth::check()) {
          $rol = auth()->user()->rol;
          $id = auth()->user()->id;
          return view('catalogos.condicion_entrega.index', compact('resultados', 'proyectos','usos_propiedad','request','rol','id'));
        }else{
          return redirect()->route('welcome');
        }
    }
    public function store(request $request)
    {
        $condicion = new CondicionEntrega();
        $condicion->uso_propiedad_id = $request->get('uso_propiedad_id');
        $condicion->proyecto_id = $request->get('proyecto_id');
        $condicion->titulo = $request->get('titulo');
        $condicion->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function show($id)
    {
        $resultado = DB::table('condicion_entrega as c')
        ->select('id_condicion_entrega', 'c.titulo', 'c.uso_propiedad_id','c.proyecto_id','c.created_date')
        ->where('c.id_condicion_entrega',$id)
        ->first();

        $condicion_entrega_detalle = DB::table('condicion_entrega_detalle')
        ->select('id_condicion_entrega_detalle', 'tipo','condicion' ,'modified_date','subtitulo')
        ->orderby('id_condicion_entrega_detalle','asc')
        ->where('condicion_entrega_id', $id)
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->orderby('uso_propiedad','asc')
        ->get();

        $tipo_condicion = array('Bullet','Nota','Agregado','Subtitulo');

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('catalogos.condicion_entrega.show',compact('resultado','usos_propiedad','proyectos', 'condicion_entrega_detalle','tipo_condicion','procedencia','rol','id'));
    }
    public function update(request $request, $id)
    {
        $condicion = CondicionEntrega::findOrFail($id);
        $condicion->uso_propiedad_id = $request->get('uso_propiedad_id');
        $condicion->proyecto_id = $request->get('proyecto_id');
        $condicion->titulo = $request->get('titulo');
        $condicion->save();
        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
        
    }
    public function destroy($id)
    {
        $detalles = DB::table('condicion_entrega_detalle as ep')
        ->select('id_condicion_entrega_detalle')
        ->where('condicion_entrega_id', $id)
        ->get();

        if(count($detalles) > 0){
            $notification = array(
                'msj' => 'No se puede eliminar, aun hay detalles relacionados',
                'alert-type' => 'error'
            );
        }else{
            $contacto = CondicionEntrega::find($id);
            $contacto->delete();
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
        }
        return back()->with($notification);
    }
}
