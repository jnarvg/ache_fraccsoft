<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Moneda;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Http\Middleware\Authenticate;

class MonedaController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $word = $request->get('word_bs');

        $monedas = DB::table('moneda as e')
        ->select('e.id_moneda','e.moneda', 'e.siglas','e.tipo_cambio')
        ->orderBy('id_moneda','ASC')
        ->where('moneda','LIKE',"%$word%")
        ->where('siglas','LIKE',"%$word%")
        ->paginate(10); 

        return view('catalogos.moneda.index',['monedas'=>$monedas, 'request'=>$request]);
    }
    public function store(request $request)
    {
        $moneda = new Moneda();
        $moneda->moneda = $request->get('moneda');
        $moneda->tipo_cambio = $request->get('tipo_cambio');
        $moneda->siglas = $request->get('siglas');
        $moneda->save();
        return redirect()->route('moneda');
    }
    public function show($id)
    {
        $moneda = DB::table('moneda as e')
        ->select('e.id_moneda','e.moneda', 'e.siglas','e.tipo_cambio')
        ->orderBy('id_moneda','ASC')
        ->where('e.id_moneda','=',$id)
        ->first();

        return view('catalogos.moneda.show',['moneda'=>$moneda]);
    }
    public function update(request $request, $id)
    {
        $moneda = Moneda::findOrFail($id);
        $moneda->moneda = $request->get('moneda');
        $moneda->tipo_cambio = $request->get('tipo_cambio');
        $moneda->siglas = $request->get('siglas');
        $moneda->update();
        return redirect()->route('moneda');
    }
    public function destroy($id)
    {
        $moneda = Moneda::find($id);
        $moneda->delete();
        return redirect()->route('moneda');
    }
}
