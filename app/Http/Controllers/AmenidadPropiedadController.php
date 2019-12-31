<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\AmenidadPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class AmenidadPropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $TiposOperacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->orderby('tipo_operacion','ASC')
        ->paginate(10);
        return view('catalogos.tipo_operacion.index',['TiposOperacion'=>$TiposOperacion]);
    }
    public function store(request $request)
    {
        $amenidad_propiedad = new AmenidadPropiedad();
        $amenidad_propiedad->propiedad_id = $request->get('propiedad_mdl_amenidad');
        $amenidad_propiedad->amenidad_id = $request->get('amenidad_mdl');
        $amenidad_propiedad->save();
        return back();
    }
    public function show($id)
    {
        $a = DB::table('amenidad_propiedad')
        ->select('id_amenidad_propiedad', 'amenidad_id','propiedad_id')
        ->where('id_amenidad_propiedad','=',$id)
        ->first();
        $amenidades = DB::table('amenidad')->get();
        return view('propiedades.amenidad',['a'=>$a,'amenidades'=>$amenidades]);
    }
    public function update(request $request, $id)
    {
        $ame = AmenidadPropiedad::findOrFail($id);
        $ame->amenidad_id = $request->get('amenidad_edit');
        $ame->update();
        return redirect()->route('propiedades-show',['id'=>$ame->propiedad_id]);
    }
    public function destroy($id)
    {
        $amenidad_propiedad = AmenidadPropiedad::find($id);
        $amenidad_propiedad->delete();
        return back();
    }
}
