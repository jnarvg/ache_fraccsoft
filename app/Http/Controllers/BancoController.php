<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Banco;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class BancoController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $nombre = $request->get('nombre_bs');

        $bancos = DB::table('banco as py')
        ->orderby('banco','ASC')
        ->where('py.banco','LIKE',"%$nombre%")
        ->orwhere('py.rfc','LIKE',"%$nombre%")
        ->orwhere('py.razon_social','LIKE',"%$nombre%")
        ->get();

        return view('catalogos.banco.index',['bancos'=>$bancos,'request'=>$request]);
    }
    public function store(request $request)
    {
        $banco = new Banco();
        $banco->banco = $request->get('banco');
        $banco->razon_social = $request->get('razon_social');
        $banco->rfc = $request->get('rfc');
        $banco->save();
        return redirect()->route('banco');
    }
    public function show($id)
    {
        $banco = DB::table('banco as py')
        ->where('id_banco','=',$id)
        ->first();

        return view('catalogos.banco.show',['banco'=>$banco]);
    }
    public function update(request $request, $id)
    {
        $banco = Banco::findOrFail($id);
        $banco->banco = $request->get('banco');
        $banco->razon_social = $request->get('razon_social');
        $banco->rfc = $request->get('rfc');
        
        $banco->update();
        
        //return json_encode($request);
        return redirect()->route('banco-show',['id'=>$id]);
    }
    public function destroy($id)
    {   
        $empresas = DB::table('empresa')
        ->where('banco_id','=',$id)
        ->select(DB::raw('count(*) as cont_prop'))
        ->first();
        if ($empresas->cont_prop == 0) {
            $banco = Banco::find($id);
            $banco->delete();
            return redirect()->route('banco');
        }else{
            return redirect()->route('banco')->with('msj','No se puede eliminar, aun hay empresas anidadas a este banco');
        }
    }
}
