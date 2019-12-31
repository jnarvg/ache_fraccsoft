<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\EstatusPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EstatusPropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $estatus_propiedades = DB::table('estatus_propiedad')
        ->join('color','color_id','=','id_color','left',false)
        ->select('id_estatus_propiedad', 'estatus_propiedad','codigo_hx','color_id','color')
        ->orderby('estatus_propiedad','ASC')
        ->get();

        $colores = DB::table('color')
        ->select('id_color', 'color','codigo_hexadecimal')
        ->get();

        return view('catalogos.estatus_propiedad.index',compact('estatus_propiedad','colores'));
    }
    public function store(request $request)
    {
        $estatus_propiedad = new EstatusPropiedad();
        $estatus_propiedad->estatus_propiedad = $request->get('nuevo_estatus_mdl');
        $estatus_propiedad->color_id = $request->get('nuevo_color');
        $estatus_propiedad->codigo_hx = $request->get('nuevo_codigo');
        $estatus_propiedad->save();
        return redirect()->route('estatus_propiedad');
    }
    public function show($id)
    {
        $EstatusPropiedad = DB::table('estatus_propiedad')
        ->join('color','color_id','=','id_color','left',false)
        ->select('id_estatus_propiedad', 'estatus_propiedad','codigo_hx','color_id','color')
        ->where('id_estatus_propiedad','=',$id)
        ->first();

        $colores = DB::table('color')
        ->select('id_color', 'color','codigo_hexadecimal')
        ->get();
        return view('catalogos.estatus_propiedad.show',compact('EstatusPropiedad', 'colores'));
    }
    public function update(request $request, $id)
    {
        $estatus_propiedad = EstatusPropiedad::findOrFail($id);
        $estatus_propiedad->estatus_propiedad = $request->get('estatus_propiedad');
        $estatus_propiedad->color_id = $request->get('color_id');
        $estatus_propiedad->codigo_hx = $request->get('codigo_hx');
        $estatus_propiedad->update();
        return redirect()->route('estatus_propiedad',['id'=>$id]);
    }
    public function destroy($id)
    {
        $estatus_propiedad = EstatusPropiedad::find($id);
        $estatus_propiedad->delete();
        return redirect()->route('estatus_propiedad');
    }
}
