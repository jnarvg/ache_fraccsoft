<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\ConfiguracionCampos ;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Http\Middleware\Authenticate;


class ConfiguracionCamposController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {

        $campos_configuracion = DB::table('campos_configuracion')
        ->select('id_campos_configuracion', 'tabla', 'campo', 'label', 'tipo_dato', 'pk', 'unique', 'readonly', 'hidden', 'fk_tabla', 'fk_campo', 'importable','fk_pk','requerido','actualizable')
        ->get();

        $tablas = DB::select('SHOW TABLES');
        $siorno = array('SI','NO');
        $tipos = array('int','varchar','date','datetime','text','double','time');

        if (Auth::check()){
          $id = auth()->user()->id;
          return view('configuracion_campos.index',compact('campos_configuracion','tablas','siorno','id','tipos'));
        }else{
          $id = null;
          return redirect()->route('welcome');
        }

    }
    public function store(request $request)
    {
        $configCampos = new ConfiguracionCampos();
        $configCampos->tabla = $request->get('tabla');
        $configCampos->campo = $request->get('campo');
        $configCampos->label = $request->get('label');
        $configCampos->tipo_dato = $request->get('tipo_dato');
        $configCampos->pk = $request->get('pk');
        $configCampos->unique = $request->get('unique');
        $configCampos->readonly = $request->get('readonly');
        $configCampos->hidden = $request->get('hidden');
        $configCampos->requerido = $request->get('requerido');
        $configCampos->fk_tabla = $request->get('fk_tabla');
        $configCampos->fk_campo = $request->get('fk_campo');
        $configCampos->fk_pk = $request->get('fk_pk');
        $configCampos->importable = $request->get('importable');
        $configCampos->actualizable = $request->get('actualizable');
        $configCampos->save();
        
        return back();
    }
    public function show($id)
    {
        $campos_configuracion = DB::table('campos_configuracion')
        ->select('id_campos_configuracion', 'tabla', 'campo', 'label', 'tipo_dato', 'pk', 'unique', 'readonly', 'hidden', 'fk_tabla', 'fk_campo', 'importable','fk_pk','requerido','actualizable')
        ->where('id_campos_configuracion',$id)
        ->first();
        $tipos = array('int','varchar','date','datetime','text','double','time');
        $tablas = DB::select('SHOW TABLES');
        $siorno = array('SI','NO');
        if (Auth::check()){
          $id = auth()->user()->id;
          return view('configuracion_campos.show',compact('campos_configuracion','tablas','siorno','id','tipos'));
        }else{
          $id = null;
          return redirect()->route('welcome');
        }

    }
    public function update(request $request, $id)
    {
        $configCampos = ConfiguracionCampos::findOrFail($id);
        $configCampos->tabla = $request->get('tabla');
        $configCampos->campo = $request->get('campo');
        $configCampos->label = $request->get('label');
        $configCampos->tipo_dato = $request->get('tipo_dato');
        $configCampos->pk = $request->get('pk');
        $configCampos->unique = $request->get('unique');
        $configCampos->readonly = $request->get('readonly');
        $configCampos->hidden = $request->get('hidden');
        $configCampos->requerido = $request->get('requerido');
        $configCampos->fk_tabla = $request->get('fk_tabla');
        $configCampos->fk_campo = $request->get('fk_campo');
        $configCampos->fk_pk = $request->get('fk_pk');
        $configCampos->importable = $request->get('importable');
        $configCampos->actualizable = $request->get('actualizable');
        $configCampos->update();
        
        return back();
    }
    public function destroy($id)
    {
        $contacto = ConfiguracionCampos::find($id);
        $contacto->delete();
        
        return back();

    }
}
