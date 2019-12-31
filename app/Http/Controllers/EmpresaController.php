<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Empresa;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EmpresaController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $nombre = $request->get('nombre_bs');

        $empresas = DB::table('empresa as py')
        ->join('pais as p','py.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','py.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','py.ciudad_id','=','c.id_ciudad','left',false)
        ->join('banco as b','py.banco_id','=','b.id_banco','left',false)
        ->join('regimen_fiscal as r','py.regimen_fiscal_id','=','r.id_regimen_fiscal','left',false)
        ->select('id_empresa', 'nombre_comercial','py.razon_social','py.rfc','correo','py.banco_id','codigo_postal','rfc_banco','cuenta_bancaria','correo','colonia','calle','n_exterior','n_interior','py.regimen_fiscal_id','py.pais_id','py.estado_id','py.ciudad_id', 'p.pais','e.estado','c.ciudad','r.concatenado as regimen_fiscal','b.banco','py.tipo')
        ->orderby('nombre_comercial','ASC')
        ->where('py.nombre_comercial','LIKE',"%$nombre%")
        ->orwhere('py.razon_social','LIKE',"%$nombre%")
        ->paginate(10);

        $paises = DB::table('pais')
        ->get();
        $regimen_fiscales = DB::table('regimen_fiscal')
        ->get();
        $bancos = DB::table('banco')
        ->get();

        $tipos = array('Moral','Fisica');

        return view('catalogos.empresa.index',['empresas'=>$empresas,'request'=>$request,'paises'=>$paises,'regimen_fiscales'=>$regimen_fiscales,'bancos'=>$bancos, 'tipos'=>$tipos]);
    }
    public function store(request $request)
    {
        $empresa = new Empresa();
        $empresa->nombre_comercial = $request->get('nombre_comercial');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->n_interior = $request->get('n_interior');
        $empresa->n_exterior = $request->get('n_exterior');
        $empresa->calle = $request->get('calle');
        $empresa->colonia = $request->get('colonia');
        $empresa->rfc_banco = $request->get('rfc_banco');
        $empresa->cuenta_bancaria = $request->get('cuenta_bancaria');
        $empresa->correo = $request->get('correo');
        $empresa->codigo_postal = $request->get('codigo_postal');
        $empresa->rfc = $request->get('rfc');
        $empresa->tipo = $request->get('tipo');
        if ($request->get('pais') != 'Vacio') {
            $empresa->pais_id = $request->get('pais');
        }
        if ($request->get('estado') != 'Vacio') {
            $empresa->estado_id = $request->get('estado');
        }
        if ($request->get('ciudad') != 'Vacio') {
            $empresa->ciudad_id = $request->get('ciudad');
        }
        if ($request->get('banco') != 'Vacio') {
            $empresa->banco_id = $request->get('banco');
        }
        if ($request->get('regimen_fiscal') != 'Vacio') {
            $empresa->regimen_fiscal_id = $request->get('regimen_fiscal');
        }
        $empresa->save();
        return redirect()->route('empresa');
    }
    public function show($id)
    {
        $empresa = DB::table('empresa as py')
        ->join('pais as p','py.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','py.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','py.ciudad_id','=','c.id_ciudad','left',false)
        ->join('banco as b','py.banco_id','=','b.id_banco','left',false)
        ->join('regimen_fiscal as r','py.regimen_fiscal_id','=','r.id_regimen_fiscal','left',false)
        ->select('id_empresa', 'nombre_comercial','py.razon_social','py.rfc','correo','py.banco_id','codigo_postal','rfc_banco','cuenta_bancaria','correo','colonia','calle','n_exterior','n_interior','py.regimen_fiscal_id','py.pais_id','py.estado_id','py.ciudad_id', 'p.pais','e.estado','c.ciudad','r.concatenado as regimen_fiscal','b.banco','py.tipo')
        ->where('id_empresa','=',$id)
        ->first();

        $paises = DB::table('pais')
        ->get();
        $estados = DB::table('estado')
        ->where('pais_id',$empresa->pais_id)
        ->get();
        $ciudades = DB::table('ciudad')
        ->where('estado_id',$empresa->estado_id)
        ->get();

        $regimen_fiscales = DB::table('regimen_fiscal')
        ->get();
        $bancos = DB::table('banco')
        ->get();

        $tipos = array('Moral','Fisica');
        return view('catalogos.empresa.show',['empresa'=>$empresa,'paises'=>$paises,'regimen_fiscales' => $regimen_fiscales, 'bancos' => $bancos, 'tipos'=>$tipos, 'ciudades'=>$ciudades, 'estados'=>$estados]);
    }
    public function update(request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->nombre_comercial = $request->get('nombre_comercial');
        $empresa->razon_social = $request->get('razon_social');
        $empresa->n_interior = $request->get('n_interior');
        $empresa->n_exterior = $request->get('n_exterior');
        $empresa->calle = $request->get('calle');
        $empresa->colonia = $request->get('colonia');
        $empresa->rfc_banco = $request->get('rfc_banco');
        $empresa->cuenta_bancaria = $request->get('cuenta_bancaria');
        $empresa->correo = $request->get('correo');
        $empresa->codigo_postal = $request->get('codigo_postal');
        $empresa->rfc = $request->get('rfc');
        $empresa->tipo = $request->get('tipo');
        if ($request->get('pais') != 'Vacio') {
            $empresa->pais_id = $request->get('pais');
        }
        if ($request->get('estado') != 'Vacio') {
            $empresa->estado_id = $request->get('estado');
        }
        if ($request->get('ciudad') != 'Vacio') {
            $empresa->ciudad_id = $request->get('ciudad');
        }
        if ($request->get('banco') != 'Vacio') {
            $empresa->banco_id = $request->get('banco');
        }
        if ($request->get('regimen_fiscal') != 'Vacio') {
            $empresa->regimen_fiscal_id = $request->get('regimen_fiscal');
        }
        $empresa->update();
        
        //return json_encode($request);
        return back();
    }
    public function destroy($id)
    {   
        $empresa = Empresa::find($id);
        $empresa->delete();
        return redirect()->route('empresa');
    }
}
