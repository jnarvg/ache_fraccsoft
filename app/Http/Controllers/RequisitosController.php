<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Requisitos;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class RequisitosController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $requisitos = DB::table('requisitos')
        ->select('id_requisito', 'requisito')
        ->orderby('requisito','ASC')
        ->paginate(10);
        return view('catalogos.requisito.index',['requisitos'=>$requisitos]);
    }
    public function store(request $request)
    {
        $requisito = new Requisitos();
        $requisito->requisito = $request->get('nuevo_requisito_mdl');
        $requisito->save();
        return redirect()->route('requisito');
    }
    public function show($id)
    {
        $requisito = DB::table('requisitos')
        ->select('id_requisito', 'requisito as requisito_padre')
        ->where('id_requisito','=',$id)
        ->first();

        $requisitos_detalles = DB::table('requisitos_detalle')
        ->select('id_requisito_detalle','requisito','requisito_id')
        ->where('requisito_id','=',$id)
        ->get();

        return view('catalogos.requisito.show',['requisito'=>$requisito,'requisitos_detalles'=>$requisitos_detalles]);
    }
    public function update(request $request, $id)
    {
        $requisito = Requisitos::findOrFail($id);
        $requisito->requisito = $request->get('requisito');
        $requisito->update();
        return redirect()->route('requisito',['id'=>$id]);
    }
    public function destroy($id)
    {
        $requisito = Requisitos::find($id);
        $requisito->delete();

        
        return redirect()->route('requisito');
    }
}
