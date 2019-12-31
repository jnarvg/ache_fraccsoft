<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\TipoOperacion;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class TipoOperacionController extends Controller
{
//
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $TiposOperacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->orderby('tipo_operacion','ASC')
        ->get();
        return view('catalogos.tipo_operacion.index',['TiposOperacion'=>$TiposOperacion]);
    }
    public function store(request $request)
    {
        $tipo_operacion = new TipoOperacion();
        $tipo_operacion->tipo_operacion = $request->get('nuevo_tipo_mdl');
        $tipo_operacion->save();
        return redirect()->route('tipo_operacion');
    }
    public function show($id)
    {
        $TipoOperacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->where('id_tipo_operacion','=',$id)
        ->first();
        return view('catalogos.tipo_operacion.show',['TipoOperacion'=>$TipoOperacion]);
    }
    public function update(request $request, $id)
    {
        $tipo_operacion = TipoOperacion::findOrFail($id);
        $tipo_operacion->tipo_operacion = $request->get('tipo_operacion');
        $tipo_operacion->update();
        return redirect()->route('tipo_operacion',['id'=>$id]);
    }
    public function destroy($id)
    {
        $tipo_operacion = TipoOperacion::find($id);
        $tipo_operacion->delete();
        return redirect()->route('tipo_operacion');
    }
}
