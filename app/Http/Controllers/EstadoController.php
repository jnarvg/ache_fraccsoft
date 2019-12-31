<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Estado;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EstadoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $estado = $request->get('nombre_bs');
        $clave = $request->get('clave_bs');
        $pais = $request->get('pais_bs');

        $estados = DB::table('estado as e')
        ->join('pais as p','e.pais_id','=','p.id_pais')
        ->select('e.id_estado','e.estado','p.pais','e.clave')
        ->orderBy('e.estado','ASC')
        ->where('estado','LIKE',"%$estado%")
        ->where('p.pais','LIKE',"%$pais%")
        ->where('e.clave','LIKE',"%$clave%")
        ->get(); 

        $paises = DB::table('pais as p')
        ->select('p.id_pais','p.pais')
        ->orderBy('p.pais','ASC')
        ->get(); 

        return view('catalogos.estado.index',['estados'=>$estados, 'paises'=>$paises,'request'=>$request]);
    }
    public function store(request $request)
    {
        $estado = new Estado();
        $estado->estado = $request->get('nuevo_estado_mdl');
        $estado->clave = $request->get('nuevo_clave_mdl');
        $estado->pais_id = $request->get('nuevo_pais_mdl');
        $estado->save();
        return redirect()->route('estado');
    }
    public function show($id)
    {
        $estado = DB::table('estado as e')
        ->join('pais as p','e.pais_id','=','p.id_pais')
        ->select('e.id_estado', 'e.estado','e.pais_id','p.pais','e.clave')
        ->where('e.id_estado','=',$id)
        ->first();

        $paises = DB::table('pais as p')
        ->select('p.id_pais','p.pais')
        ->orderBy('p.pais','ASC')
        ->get(); 
        return view('catalogos.estado.show',['estado'=>$estado, 'paises'=>$paises]);
    }
    public function update(request $request, $id)
    {
        $estado = Estado::findOrFail($id);
        $estado->estado = $request->get('estado');
        $estado->clave = $request->get('clave');
        $estado->pais_id = $request->get('pais');
        $estado->update();
        return redirect()->route('estado');
    }
    public function destroy($id)
    {
        $estado = Estado::find($id);
        $estado->delete();
        return redirect()->route('estado');
    }
}
