<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\GrupoEsquema;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class GrupoEsquemaController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {

        $filtro = $this->build_filtro($request);
        $resultados = DB::table('grupo_esquema as ep')
        ->join('proyecto as p','ep.proyecto_id','=','p.id_proyecto','left',false)
        ->join('uso_propiedad as u','ep.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
        ->select('id_grupo_esquema', 'ep.uso_propiedad_id', 'ep.proyecto_id', 'grupo_esquema','p.nombre as proyecto','u.uso_propiedad','ep.created_at')
        ->orderby('id_grupo_esquema','ASC')
        ->whereRaw($filtro)
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad','uso_propiedad')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();

        return view('catalogos.grupo_esquema.index', compact('resultados','usos_propiedad','proyectos','request'));
    }
    public function store(request $request)
    {
        $nuevo = new GrupoEsquema();
        $nuevo->grupo_esquema = $request->get('grupo_esquema');
        $nuevo->proyecto_id = $request->get('proyecto_id');
        $nuevo->uso_propiedad_id = $request->get('uso_propiedad_id');

        $nuevo->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function show($id)
    {
        $resultado = DB::table('grupo_esquema as ep')
        ->select('id_grupo_esquema', 'ep.uso_propiedad_id', 'ep.proyecto_id', 'grupo_esquema','ep.created_at')
        ->where('id_grupo_esquema', $id)
        ->first();

        $usos_propiedad = DB::table('uso_propiedad')
        ->select('id_uso_propiedad','uso_propiedad')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();

        $resultados_detalle = DB::table('esquema_pago')
        ->select('id_esquema_pago', 'esquema_pago', 'porcentaje_descuento', 'incluir','grupo_esquema_id', 'created_at')
        ->where('grupo_esquema_id','=',$id)
        ->orderby('id_esquema_pago','ASC')
        ->get();

        return view('catalogos.grupo_esquema.show', compact('resultado','resultados_detalle','proyectos','usos_propiedad'));
    }
    public function update(request $request, $id)
    {
        $nuevo = GrupoEsquema::findOrFail($id);
        $nuevo->grupo_esquema = $request->get('grupo_esquema');
        $nuevo->proyecto_id = $request->get('proyecto_id');
        $nuevo->uso_propiedad_id = $request->get('uso_propiedad_id');

        $nuevo->update();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
    public function destroy($id)
    {
        $detalles = DB::table('esquema_pago as ep')
        ->select('id_esquema_pago')
        ->where('grupo_esquema_id', $id)
        ->get();

        if(count($detalles) > 0){
            $notification = array(
                'msj' => 'No se puede eliminar, aun hay detalles relacionados a este esquema',
                'alert-type' => 'error'
            );
        }else{
            $borrar = GrupoEsquema::find($id);
            $borrar->delete();
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );
        }
        return back()->with($notification);

    }

    public function build_filtro(request $request)
    {
        $proyecto_bs = $request->get('proyecto_bs');
        $uso_propiedad_bs = $request->get('uso_propiedad_bs');
        $esquema_bs = $request->get('esquema_bs');

        $filtro = ' id_grupo_esquema IS NOT NULL';

        if ($proyecto_bs != null and $proyecto_bs != '' and $proyecto_bs and $proyecto_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.proyecto_id = '.$proyecto_bs;
        }
        if ($uso_propiedad_bs != null and $uso_propiedad_bs != '' and $uso_propiedad_bs and $uso_propiedad_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' ep.uso_propiedad_id = '.$uso_propiedad_bs;
        }
        if ($esquema_bs != null and $esquema_bs != '' and $esquema_bs and $esquema_bs != 'Vacio') {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro .  'ep.grupo_esquema LIKE "%'.$esquema_bs.'%"';
        }
        return $filtro;
    }
}
