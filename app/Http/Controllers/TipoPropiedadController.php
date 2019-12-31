<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\TipoPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class TipoPropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $TiposPropiedad = DB::table('tipo_propiedad')
        ->select('id_tipo_propiedad', 'tipo_propiedad')
        ->orderby('tipo_propiedad','ASC')
        ->get();
        return view('catalogos.tipo_propiedad.index',['TiposPropiedad'=>$TiposPropiedad]);
    }
    public function store(request $request)
    {
        $tipo_propiedad = new TipoPropiedad();
        $tipo_propiedad->tipo_propiedad = $request->get('nuevo_tipo_mdl');
        $tipo_propiedad->save();
        return redirect()->route('tipo_propiedad');
    }
    public function show($id)
    {
        $tipoPropiedad = DB::table('tipo_propiedad')
        ->select('id_tipo_propiedad', 'tipo_propiedad')
        ->where('id_tipo_propiedad','=',$id)
        ->first();
        return view('catalogos.tipo_propiedad.show',['tipoPropiedad'=>$tipoPropiedad]);
    }
    public function update(request $request, $id)
    {
        $tipo_propiedad = TipoPropiedad::findOrFail($id);
        $tipo_propiedad->tipo_propiedad = $request->get('tipo_propiedad');
        $tipo_propiedad->update();
        return redirect()->route('tipo_propiedad',['id'=>$id]);
    }
    public function destroy($id)
    {
        $tipo_propiedad = TipoPropiedad::find($id);
        $tipo_propiedad->delete();
        return redirect()->route('tipo_propiedad');
    }
}
