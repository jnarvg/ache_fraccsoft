<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\ConfiguracionGeneral ;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class ConfiguracionGeneralController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {

        $configuracion_general = DB::table('configuracion_general')
        ->join('empresa as e','empresa_principal_id','=','e.id_empresa','left',false)
        ->select('id_configuracion_general','nombre_cliente','empresa_principal_id','jefe_proyecto','correo_contacto','tasa_interes_mora','limite_usuarios','limite_propiedades','estatus', 'e.nombre_comercial')
        ->get();

        $empresas = DB::table('empresa')
        ->select('id_empresa', 'nombre_comercial')
        ->orderby('nombre_comercial','asc')
        ->get();

        $estatus = array('Activo','Inactivo','Suspendido');
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('configuracion_general.index',['configuracion_general'=>$configuracion_general,'empresas'=>$empresas,'id'=>$id, 'rol'=>$rol, 'estatus'=>$estatus]);
    }
    public function store(request $request)
    {
        $configGral = new ConfiguracionGeneral();
        $configGral->empresa_principal_id = $request->get('empresa_principal');
        $configGral->tasa_interes_mora = $request->get('tasa_interes_mora');
        $configGral->limite_propiedades = $request->get('limite_propiedades');
        $configGral->limite_usuarios = $request->get('limite_usuarios');
        $configGral->estatus = $request->get('estatus');
        $configGral->nombre_cliente = $request->get('nombre_cliente');
        $configGral->jefe_proyecto = $request->get('jefe_proyecto');
        $configGral->correo_contacto = $request->get('correo_contacto');
        $configGral->save();
        
        return back();
    }
    public function show($id)
    {
        $configuracion_general = DB::table('configuracion_general')
        ->join('empresa as e','empresa_principal_id','=','e.id_empresa','left',false)
        ->select('id_configuracion_general','nombre_cliente','empresa_principal_id','jefe_proyecto','correo_contacto','tasa_interes_mora','limite_usuarios','limite_propiedades','estatus', 'e.nombre_comercial')
        ->where('id_configuracion_general',$id)
        ->first();

        $empresas = DB::table('empresa')
        ->select('id_empresa', 'nombre_comercial')
        ->orderby('nombre_comercial','asc')
        ->get();

        $estatus = array('Activo','Inactivo','Suspendido');
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;       

        return view('configuracion_general.show',compact('configuracion_general','empresas','estatus','rol','id'));
    }
    public function update(request $request, $id)
    {
        $configGral = ConfiguracionGeneral::findOrFail($id);
        $configGral->empresa_principal_id = $request->get('empresa_principal');
        $configGral->tasa_interes_mora = $request->get('tasa_interes_mora');
        $configGral->limite_propiedades = $request->get('limite_propiedades');
        $configGral->limite_usuarios = $request->get('limite_usuarios');
        $configGral->estatus = $request->get('estatus');
        $configGral->nombre_cliente = $request->get('nombre_cliente');
        $configGral->jefe_proyecto = $request->get('jefe_proyecto');
        $configGral->correo_contacto = $request->get('correo_contacto');
        $configGral->update();
        
        return back();
    }
    public function destroy($id)
    {
        $contacto = ConfiguracionGeneral::find($id);
        $contacto->delete();
        
        return back();

    }
}
