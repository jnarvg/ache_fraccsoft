<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\RequisitosProspecto;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
class RequisitosProspectoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function show($id)
    {
        $requisito_prospecto = DB::table('requisito_prospecto')
        ->select('id_requisito_prospecto', 'requisito','estatus','comentario','prospecto_id','archivo')
        ->where('id_requisito_prospecto','=',$id)
        ->first();
        return view('prospectos.requisito_prospecto.show',['requisito_prospecto'=>$requisito_prospecto]);
    }
    public function update(request $request, $id)
    {
        $requisito_prospecto = RequisitosProspecto::findOrFail($id);
        $requisito_prospecto->requisito = $request->get('requisito');
        $requisito_prospecto->comentario = $request->get('comentario');
        $fechah = date("Y-m-d H:i:s");
        $path = public_path().'/uploads/';
        $files = $request->archivo;
        if(!empty($files)){
          $fileName = time().'_'.$files->getClientOriginalName();
          $requisito_prospecto->archivo=$fileName;
          $files->move($path, $fileName);
        }
        $id_prospecto = $requisito_prospecto->prospecto_id;
        $requisito_prospecto->update();
        return back();
    }
    public function completar($id)
    {
        $requisito_prospecto = RequisitosProspecto::find($id);
        $requisito_prospecto->estatus ='Completado';
        $id_prospecto = $requisito_prospecto->prospecto_id;
        $requisito_prospecto->update();
        $notification = array(
            'msj' => 'Completado!!',
            'alert-type' => 'info'
        );

        return back()->with($notification);
    }
    public function pendiente($id)
    {
        $requisito_prospecto = RequisitosProspecto::find($id);
        $requisito_prospecto->estatus ='Pendiente';
        $id_prospecto = $requisito_prospecto->prospecto_id;
        $requisito_prospecto->update();
        return back();

        $notification = array(
            'msj' => 'Pendiente!!',
            'alert-type' => 'info'
        );

        return back()->with($notification);
    }

    public function completar_todos($id)
    {
        $completar_todo = DB::table('requisito_prospecto')
                ->where('prospecto_id', $id)
                ->update(['estatus' => 'Completado' ]);

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}
