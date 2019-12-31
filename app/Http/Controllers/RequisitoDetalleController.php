<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\RequisitoDetalle;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class RequisitoDetalleController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $requisitos_detalle = DB::table('requisitos_detalle')
        ->select('id_requisitos_detalle', 'requisito','requisito_id')
        ->orderby('requisito','ASC')
        ->paginate(10);
        return view('catalogos.requisito.requisitos_detalle.index',['requisitos_detalle'=>$requisitos_detalle]);
    }
    public function store(request $request, $id)
    {
        $requisito_detalle = new RequisitoDetalle();
        $requisito_detalle->requisito = $request->get('nuevo_requisito_mdl');
        $requisito_detalle->requisito_id = $id;
        $requisito_detalle->save();

        return redirect()->route('requisito-show',["id"=>$id]);
    }
    public function show($id)
    {
        $requisito_detalle = DB::table('requisitos_detalle')
        ->select('id_requisito_detalle', 'requisito','requisito_id')
        ->where('id_requisito_detalle','=',$id)
        ->first();
        return view('catalogos.requisito.requisitos_detalle.show',['requisito_detalle'=>$requisito_detalle]);
    }
    public function update(request $request, $id)
    {
        $requisito_detalle = RequisitoDetalle::findOrFail($id);
        $requisito_detalle->requisito = $request->get('requisito_detalle');
        $id_padre = $requisito_detalle->requisito_id;
        $requisito_detalle->update();
        return redirect()->route('requisito-show',["id"=>$id_padre]);
    }
    public function destroy($id)
    {
        $requisito_detalle = RequisitoDetalle::find($id);
        $id_padre = $requisito_detalle->requisito_id;
        $requisito_detalle->delete();
        return redirect()->route('requisito-show',["id"=>$id_padre]);
    }
}
