<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\MotivoPerdida;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class MotivoPerdidaController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $motivos_perdida = DB::table('motivo_perdida')
        ->select('id_motivo_perdida', 'motivo_perdida')
        ->orderby('motivo_perdida','ASC')
        ->get();
        return view('catalogos.motivo_perdida.index',['motivos_perdida'=>$motivos_perdida]);
    }
    public function store(request $request)
    {
        $motivo_perdida = new MotivoPerdida();
        $motivo_perdida->motivo_perdida = $request->get('nuevo_motivo_mdl');
        $motivo_perdida->save();
        return redirect()->route('motivo_perdida');
    }
    public function show($id)
    {
        $motivo_perdida = DB::table('motivo_perdida')
        ->select('id_motivo_perdida', 'motivo_perdida')
        ->where('id_motivo_perdida','=',$id)
        ->first();
        return view('catalogos.motivo_perdida.show',['motivo_perdida'=>$motivo_perdida]);
    }
    public function update(request $request, $id)
    {
        $motivo_perdida = MotivoPerdida::findOrFail($id);
        $motivo_perdida->motivo_perdida = $request->get('motivo_perdida');
        $motivo_perdida->update();
        return redirect()->route('motivo_perdida',['id'=>$id]);
    }
    public function destroy($id)
    {
        $motivo_perdida = MotivoPerdida::find($id);
        $motivo_perdida->delete();
        return redirect()->route('motivo_perdida');
    }
}
