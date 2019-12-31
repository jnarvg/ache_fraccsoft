<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\RegimenFiscal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class RegimenFiscalController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $nombre = $request->get('nombre_bs');

        $regimen_fiscal = DB::table('regimen_fiscal as py')
        ->orderby('id_regimen_fiscal','ASC')
        ->where('py.regimen_fiscal','LIKE',"%$nombre%")
        ->orwhere('py.clave','LIKE',"%$nombre%")
        ->orwhere('py.concatenado','LIKE',"%$nombre%")
        ->get();

        return view('catalogos.regimen_fiscal.index',['regimen_fiscal'=>$regimen_fiscal,'request'=>$request]);
    }
    public function store(request $request)
    {
        $regimen_fiscal = new RegimenFiscal();
        $regimen_fiscal->regimen_fiscal = $request->get('regimen_fiscal');
        $regimen_fiscal->clave = $request->get('clave');
        $regimen_fiscal->concatenado = $request->get('clave').' - '.$request->get('regimen_fiscal');
        $regimen_fiscal->save();
        return redirect()->route('regimen_fiscal');
    }
    public function show($id)
    {
        $regimen = DB::table('regimen_fiscal as py')
        ->where('id_regimen_fiscal','=',$id)
        ->first();

        return view('catalogos.regimen_fiscal.show',['regimen'=>$regimen]);
    }
    public function update(request $request, $id)
    {
        $regimen_fiscal = RegimenFiscal::findOrFail($id);
        $regimen_fiscal->regimen_fiscal = $request->get('regimen_fiscal');
        $regimen_fiscal->clave = $request->get('clave');
        $regimen_fiscal->concatenado = $request->get('clave').' - '.$request->get('regimen_fiscal');
        $regimen_fiscal->update();
        
        //return json_encode($request);
        return back();
    }
    public function destroy($id)
    {   
        $empresas = DB::table('empresa')
        ->where('regimen_fiscal_id','=',$id)
        ->select(DB::raw('count(*) as cont_prop'))
        ->first();
        if ($empresas->cont_prop == 0) {
            $proyecto = RegimenFiscal::find($id);
            $proyecto->delete();
            return redirect()->route('regimen_fiscal');
        }else{
            return redirect()->route('regimen_fiscal')->with('msj','No se puede eliminar, aun hay empresas anidadas a este regimen.');
        }
    }
}
