<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\EstatusProspecto;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EstatusProspectoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $estatus_prospectos = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm','nivel','limite')
        ->orderby('id_estatus_crm','ASC')
        ->get();
        return view('catalogos.estatus_prospecto.index',['estatus_prospectos'=>$estatus_prospectos]);
    }
    public function store(request $request)
    {
        $estatus_prospecto = new EstatusProspecto();
        $estatus_prospecto->estatus_crm = $request->get('nuevo_estatus_mdl');
        $estatus_prospecto->nivel = $request->get('nivel');
        $estatus_prospecto->limite = $request->get('limite');
        $estatus_prospecto->save();
        return redirect()->route('estatus_prospecto');
    }
    public function show($id)
    {
        $estatus_prospecto = DB::table('estatus_crm')
        ->select('id_estatus_crm', 'estatus_crm','nivel','limite')
        ->where('id_estatus_crm','=',$id)
        ->first();
        return view('catalogos.estatus_prospecto.show',['estatus_prospecto'=>$estatus_prospecto]);
    }
    public function update(request $request, $id)
    {
        $estatus_prospecto = EstatusProspecto::findOrFail($id);
        $estatus_prospecto->estatus_crm = $request->get('estatus_prospecto');
        $estatus_prospecto->nivel = $request->get('nivel');
        $estatus_prospecto->limite = $request->get('limite');
        $estatus_prospecto->update();
        return redirect()->route('estatus_prospecto',['id'=>$id]);
    }
    public function destroy($id)
    {
        $estatus_prospecto = EstatusProspecto::find($id);
        $estatus_prospecto->delete();
        return redirect()->route('estatus_prospecto');
    }
}
