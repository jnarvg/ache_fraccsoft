<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Ciudad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class CiudadController extends Controller
{
  //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $ciudad = $request->get('nombre_bs');
        $clave = $request->get('pais_bs');
        $estado = $request->get('estado_bs');

        $ciudades = DB::table('ciudad as c')
        ->join('estado as e','c.estado_id','=','e.id_estado')
        ->join('pais as p','c.clave','=','p.clave','left', false)
        ->select('id_ciudad','estado_id' ,'ciudad','e.estado','c.clave','p.pais')
        ->orderby('id_ciudad','ASC')
        ->where('ciudad','LIKE',"%$ciudad%")
        ->where('e.estado','LIKE',"%$estado%")
        ->where('p.pais','LIKE',"%$clave%")
        ->paginate(10);

        /*Esta variable es para el select en el modal */
        $estados = DB::table('estado as e')
        ->select('e.id_estado','e.estado')
        ->orderBy('e.estado','ASC')
        ->get(); 
        
        return view('catalogos.ciudad.index',['ciudades'=>$ciudades,"estados"=>$estados,'request'=>$request]);
    }
    public function store(request $request)
    {

        $ciudad = new Ciudad();
        $ciudad->ciudad = $request->get('nueva_ciudad');
        $ciudad->clave = $request->get('nueva_clave');
        $ciudad->estado_id = $request->get('estado');
        $ciudad->save();
        return redirect()->route('ciudades');
    }
    public function show($id)
    {
        $estados = DB::table('estado as e')
        ->select('e.id_estado','e.estado')
        ->orderBy('e.estado','asc')
        ->get(); 
        $ciudad = DB::table('ciudad as c')
        ->join('estado as e','c.estado_id','=','e.id_estado')
        ->select('id_ciudad','estado_id' ,'ciudad','e.estado','c.clave')
        ->where('id_ciudad','=',$id)
        ->first();
        return view('catalogos.ciudad.show',['ciudad'=>$ciudad,"estados"=>$estados]);
    }
    public function update(request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->ciudad = $request->get('ciudad');
        $ciudad->clave = $request->get('clave');
        $ciudad->estado_id= $request->get('estado');
        $ciudad->update();
        return redirect()->route('ciudades',['id'=>$id]);
    }
    public function destroy($id)
    {
        $ciudad = Ciudad::find($id);
        $ciudad->delete();
        return redirect()->route('ciudades');
    }
}
