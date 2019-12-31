<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Pais;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class PaisController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {   
        $clave = $request->get('clave_bs');
        $nombre = $request->get('nombre_bs');
        $paises = DB::table('pais')
        ->select('id_pais', 'pais','clave')
        ->orderby('pais','ASC')
        ->where('pais','LIKE',"%$nombre%")
        ->where('clave','LIKE',"%$clave%")
        ->paginate(10);
        return view('catalogos.pais.index',['paises'=>$paises, 'request'=>$request]);
    }
    public function store(request $request)
    {
        $pais = new Pais();
        $pais->pais = $request->get('nuevo_pais_mdl');
        $pais->clave = $request->get('nuevo_clave_mdl');
        $pais->save();
        return redirect()->route('pais');
    }
    public function show($id)
    {
        $pais = DB::table('pais')
        ->select('id_pais', 'pais','clave')
        ->where('id_pais','=',$id)
        ->first();
        return view('catalogos.pais.show',['pais'=>$pais]);
    }
    public function update(request $request, $id)
    {
        $pais = Pais::findOrFail($id);
        $pais->pais = $request->get('pais');
        $pais->clave = $request->get('clave');
        $pais->update();
        return redirect()->route('pais',['id'=>$id]);
    }
    public function destroy($id)
    {
        $pais = Pais::find($id);
        $pais->delete();
        return redirect()->route('pais');
    }
}
