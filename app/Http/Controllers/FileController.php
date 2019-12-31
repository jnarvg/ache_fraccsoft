<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Documentos;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\User;
use DB;
use App\Http\Middleware\Authenticate;

class FileController extends Controller
{
    //  
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $titulo = $request->get('titulo_bs');
        $archivo = $request->get('archivo_bs');
        $rows_page = $request->get('rows_per_page');
        $rows_pagina = array('10','25','50','100');
        if ($rows_page == '') {
            $rows_page = 10;
        }

        $documentos = DB::table('documento')
        ->select('id_documento','titulo' ,'notas','fecha','archivo','prospecto_id')
        ->orderby('id_documento','ASC')
        ->where('titulo','LIKE',"%$titulo%")
        ->where('archivo','LIKE',"%$archivo%")
        ->paginate($rows_page);

        $prospectos = DB::table('prospectos')
          ->select('id_prospecto','nombre')
          ->get();

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('documentos.index',['documentos'=>$documentos, 'prospectos'=>$prospectos,'request'=>$request,'rows_pagina'=>$rows_pagina,'rol'=>$rol,'id'=>$id]);
    }

    public function store(Request $request, $procedencia)
     {
          $fechah=date("Y-m-d H:i:s");
          $path = public_path().'/uploads/';
          $files = $request->archivo;
          $documento = new Documentos();
          if(!empty($files)){
              $fileName = time().'_'.$files->getClientOriginalName();
              $documento->archivo=$fileName;
              $files->move($path, $fileName);
          }
        
        $id = auth()->user()->id;
        
        $documento->titulo = $request->get('titulo');
        $documento->notas = $request->get('notas');
        $documento->fecha = $fechah;
        $documento->agente_id = $id;
        $documento->prospecto_id = $request->get('nuevo_prospecto_mdl');
        $id_prospecto= $request->get('nuevo_prospecto_mdl');
        $documento->save();
        if ($procedencia == 'Menu') {
            return redirect()->route('documentos');
            
        }else{
            return back();
        }
     }
     public function show($id, $procedencia)
      {
          $documento = DB::table('documento')
          ->select('id_documento','titulo','notas','fecha','archivo','prospecto_id')
          ->where('id_documento','=',$id)
          ->first();

          $prospectos = DB::table('prospectos')
          ->select('id_prospecto','nombre')
          ->get();
          $rol = auth()->user()->rol;
          $id = auth()->user()->id;
          return view('documentos.show',compact('documento','prospectos','procedencia','rol','id'));
      }
    public function update(request $request, $id, $procedencia)
    {
      $fechah=date("Y-m-d H:i:s");

      $documento = Documentos::findOrFail($id);
      
      $documento->titulo = $request->get('titulo');
      $documento->notas= $request->get('notas');
      $documento->fecha= $fechah;
      $documento->prospecto_id = $request->get('prospecto');
      $id_prospecto= $request->get('prospecto');
      $documento->update();
      if ($procedencia == 'Menu') {
            return redirect()->route('documentos');
            
      }else{
          return back();
      }
    }
    public function destroy($id, $procedencia)
    {
        $documento = Documentos::find($id);
        $path = public_path().'/uploads/';
        $direccionborrar = $path.$documento->archivo;
        unlink($direccionborrar);
        $id_prospecto= $documento->prospecto_id;
        $documento->delete();

        if ($procedencia == 'Menu') {
            return redirect()->route('documentos');
            
        }else{
            return back();
        }
    }
}
