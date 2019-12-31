<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\MedioContacto;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class MedioContactoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        return view('catalogos.medio_contacto.index',['medios_contacto'=>$medios_contacto]);
    }
    public function store(request $request)
    {
        $medio_contacto = new MedioContacto();
        $medio_contacto->medio_contacto = $request->get('nuevo_medio_mdl');
        $medio_contacto->save();
        return redirect()->route('medio_contacto');
    }
    public function show($id)
    {
        $medio_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->where('id_medio_contacto','=',$id)
        ->first();
        return view('catalogos.medio_contacto.show',['medio_contacto'=>$medio_contacto]);
    }
    public function update(request $request, $id)
    {
        $medio_contacto = MedioContacto::findOrFail($id);
        $medio_contacto->medio_contacto = $request->get('medio_contacto');
        $medio_contacto->update();
        return redirect()->route('medio_contacto',['id'=>$id]);
    }
    public function destroy($id)
    {
        $medio_contacto = MedioContacto::find($id);
        $medio_contacto->delete();
        return redirect()->route('medio_contacto');
    }
}
