<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\UsoPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class UsoPropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $usosPropiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->orderby('uso_propiedad','ASC')
        ->get();
        return view('catalogos.uso_propiedad.index',['usosPropiedad'=>$usosPropiedad]);
    }
    public function store(request $request)
    {
        $uso_propiedad = new UsoPropiedad();
        $uso_propiedad->uso_propiedad = $request->get('nuevo_uso_mdl');
        $uso_propiedad->save();
        return redirect()->route('uso-propiedad');
    }
    public function show($id)
    {
        $usoPropiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad', 'uso_propiedad')
        ->where('id_uso_propiedad','=',$id)
        ->first();
        return view('catalogos.uso_propiedad.show',['usoPropiedad'=>$usoPropiedad]);
    }
    public function update(request $request, $id)
    {
        $uso_propiedad = UsoPropiedad::findOrFail($id);
        $uso_propiedad->uso_propiedad = $request->get('uso_propiedad');
        $uso_propiedad->update();
        return redirect()->route('uso-propiedad',['id'=>$id]);
    }
    public function destroy($id)
    {
        $uso_propiedad = UsoPropiedad::find($id);
        $uso_propiedad->delete();
        return redirect()->route('uso-propiedad');
    }
}
