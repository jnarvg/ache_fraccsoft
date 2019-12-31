<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\TipoPropiedadBakt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class TipoPropiedadBaktController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $tipos_modelo = DB::table('tipo_modelo')
        ->select('id_tipo_modelo', 'tipo_modelo')
        ->orderby('tipo_modelo','ASC')
        ->get();
        return view('catalogos.tipo_modelo.index',['tipos_modelo'=>$tipos_modelo]);
    }
    public function store(request $request)
    {
        $tipo_modelo = new TipoPropiedadBakt();
        $tipo_modelo->tipo_modelo = $request->get('nuevo_tipo_mdl');
        $tipo_modelo->save();
        return redirect()->route('tipo_modelo');
    }
    public function show($id)
    {
        $tipo_modelo = DB::table('tipo_modelo')
        ->select('id_tipo_modelo', 'tipo_modelo')
        ->where('id_tipo_modelo','=',$id)
        ->first();
        return view('catalogos.tipo_modelo.show',['tipo_modelo'=>$tipo_modelo]);
    }
    public function update(request $request, $id)
    {
        $tipo_modelo = TipoPropiedadBakt::findOrFail($id);
        $tipo_modelo->tipo_modelo = $request->get('tipo_modelo');
        $tipo_modelo->update();
        return redirect()->route('tipo_modelo');
    }
    public function destroy($id)
    {
        $tipo_modelo = TipoPropiedadBakt::find($id);
        $tipo_modelo->delete();
        return redirect()->route('tipo_modelo');
    }
}
