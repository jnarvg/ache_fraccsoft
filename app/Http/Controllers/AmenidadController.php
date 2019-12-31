<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Amenidad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class AmenidadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $amenidades = DB::table('amenidad')
        ->select('id_amenidad', 'amenidad','grubsa_id')
        ->orderby('id_amenidad','ASC')
        ->get();
        return view('catalogos.amenidad.index',['amenidades'=>$amenidades]);


    }
    public function store(request $request)
    {
        $amenidad = new Amenidad();
        $amenidad->amenidad = $request->get('nuevo_amenidad_mdl');
        $amenidad->save();
        return back();
    }
    public function show($id)
    {
        $amenidad = DB::table('amenidad')
        ->select('id_amenidad', 'amenidad','grubsa_id')
        ->where('id_amenidad','=',$id)
        ->first();
        return view('catalogos.amenidad.show',['amenidad'=>$amenidad]);
    }
    public function update(request $request, $id)
    {
        $amenidad = Amenidad::findOrFail($id);
        $amenidad->amenidad = $request->get('amenidad');
        $amenidad->update();
        return back();
    }
    public function destroy($id)
    {
        $amenidad = Amenidad::find($id);
        $amenidad->delete();
        return back();
    }
}
