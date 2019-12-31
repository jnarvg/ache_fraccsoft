<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Nivel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Http\Middleware\Authenticate;

class NivelController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $nivel = $request->get('nombre_bs');
        $proyecto = $request->get('proyecto_bs');

        $niveles = DB::table('nivel as e')
        ->join('proyecto as py','e.proyecto_id','=','py.id_proyecto','left',false)
        ->select('e.id_nivel','e.nivel','py.nombre as proyecto','e.proyecto_id','e.orden')
        ->orderBy('proyecto_id','ASC')
        ->where('nivel','LIKE',"%$nivel%")
        ->where('py.nombre','LIKE',"%$proyecto%")
        ->get(); 

        $proyectos = DB::table('proyecto as p')
        ->select('p.id_proyecto','p.nombre')
        ->orderBy('p.nombre','ASC')
        ->get(); 
        $procedencia = 'nivel';
        return view('catalogos.nivel.index',['niveles'=>$niveles, 'proyectos'=>$proyectos,'request'=>$request, 'procedencia'=>$procedencia]);
    }
    public function store(request $request)
    {
        $nivel = new Nivel();
        $nivel->nivel = $request->get('nivel');
        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');

            $path = Storage::disk('public')->put('planos',$file);
            $nivel->plano= $path;
        }
        $nivel->proyecto_id = $request->get('proyecto');
        $nivel->orden = $request->get('orden');
        $nivel->save();
        return back();
    }
    public function show($id, $procedencia ='')
    {
        $nivel = DB::table('nivel as e')
        ->join('proyecto as py','e.proyecto_id','=','py.id_proyecto','left',false)
        ->select('e.id_nivel','e.nivel','py.nombre as proyecto','e.plano','e.proyecto_id','e.orden')
        ->where('e.id_nivel','=',$id)
        ->first();

        $propiedades = DB::table('propiedad as p')
        ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
        ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->select('p.id_propiedad','p.nombre','py.nombre as proyecto','ep.estatus_propiedad','precio','enganche','p.cuenta_catastral')
        ->orderBy('p.nombre','ASC')
        ->where('nivel_id','=',$id)
        ->get();
        $proyectos = DB::table('proyecto as p')
        ->select('p.id_proyecto','p.nombre')
        ->orderBy('p.nombre','ASC')
        ->get(); 
        if ($procedencia == '' or $procedencia == null) {
            $procedencia = 'nivel';
        }
        return view('catalogos.nivel.show',['nivel'=>$nivel,'propiedades'=>$propiedades, 'proyectos'=>$proyectos, 'procedencia'=>$procedencia]);
    }
    public function update(request $request, $id)
    {
        $nivel = Nivel::findOrFail($id);
        $nivel->nivel = $request->get('nivel');
        $nivel->orden = $request->get('orden');
        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $nivel->plano;
            $nivel->plano= $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($nivel->plano, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        $nivel->proyecto_id = $request->get('proyecto');
        $nivel->update();
        return back();
    }
    public function destroy($id)
    {
        $nivel = Nivel::find($id);
        $nivel->delete();
        return redirect()->route('nivel');
    }
}
