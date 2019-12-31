<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Contactos;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class ContactosController extends Controller
{
    //
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

        $contactos = DB::table('contacto as c')
        ->join('prospectos as p','c.prospecto_id','=','p.id_prospecto','left',false)
        ->select('id_contacto', 'c.nombre', 'c.telefono','c.telefono_adicional','c.correo','c.prospecto_id','c.notas','c.puesto','p.nombre as nombre_prospecto')
        ->orderby('c.nombre','ASC')
        ->where('c.nombre','LIKE',"%$word%")
        ->paginate($rows_page);

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('contacto.index',['contactos'=>$contactos,'prospectos'=>$prospectos,'request'=>$request,'rows_pagina'=>$rows_pagina,'rol'=>$rol,'id'=>$id]);
    }
    public function store(request $request, $procedencia)
    {
        $contacto = new Contactos();
        $contacto->nombre = $request->get('nombre_contacto');
        $contacto->telefono = $request->get('telefono_contacto');
        $contacto->telefono_adicional = $request->get('telefono_adicional_contacto');
        $contacto->notas = $request->get('notas_contacto');
        $contacto->correo = $request->get('correo_contacto');
        $contacto->puesto = $request->get('puesto_contacto');
        $contacto->prospecto_id = $request->get('prospecto_contacto');
         $id_prospecto = $request->get('prospecto_contacto');
        $contacto->save();
        if ($procedencia == 'Menu') {
            return redirect()->route('contacto');
            
        }else{
            return back();
        }
    }
    public function show($id, $procedencia)
    {
        $contacto = DB::table('contacto')
        ->select('id_contacto', 'nombre', 'telefono','telefono_adicional','correo','prospecto_id','notas','puesto')
        ->where('id_contacto','=',$id)
        ->first();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('contacto.show',compact('contacto','prospectos','procedencia','rol','id'));
    }
    public function update(request $request, $id, $procedencia)
    {
        $contacto = Contactos::findOrFail($id);
        $contacto->nombre = $request->get('nombre');
        $contacto->telefono = $request->get('telefono');
        $contacto->telefono_adicional = $request->get('telefono_adicional');
        $contacto->notas = $request->get('notas');
        $contacto->correo = $request->get('correo');
        $contacto->puesto = $request->get('puesto');
        $contacto->prospecto_id = $request->get('prospecto');
        $id_prospecto = $request->get('prospecto');
        $contacto->save();
        if ($procedencia == 'Menu') {
            return redirect()->route('contacto');
            
        }else{
            return back();
        }
    }
    public function destroy($id, $procedencia)
    {
        $contacto = Contactos::find($id);
        $id_prospecto = $contacto->prospecto_id;
        $contacto->delete();
        if ($procedencia == 'Menu') {
            return redirect()->route('contacto');
            
        }else{
            return back();
        }
    }
}
