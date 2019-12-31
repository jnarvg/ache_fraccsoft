<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\DetalleEsquemaComision;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
class DetalleEsquemaComisionController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $detalles_comisiones = DB::table('detalle_esquema_comision')
        ->join('users as u','usuario','=','u.id','left',false)
        ->select('id_detalle_esquema_comision', 'rubro','factor','tipo','usuario','persona','esquema_id', 'u.name as nombre_usuario')
        ->paginate(10);
        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('catalogos.esquema.detalle_esquema.index',['detalles_comisiones'=>$detalles_comisiones,'rol'=>$rol,'id'=>$id]);
    }
    public function store(request $request, $id)
    {
        $detalle_esquema_comision = new DetalleEsquemaComision();
        $detalle_esquema_comision->rubro = $request->get('nuevo_rubro_mdl');
        $detalle_esquema_comision->factor = round($request->get('nuevo_factor_mdl'), 3);
        $detalle_esquema_comision->tipo = $request->get('nuevo_tipo_mdl');
        $detalle_esquema_comision->usuario = $request->get('nuevo_usuario_mdl');
        $detalle_esquema_comision->persona = $request->get('nuevo_persona_mdl');
        $detalle_esquema_comision->esquema_id = $id;
        $detalle_esquema_comision->save();
        return redirect()->route('esquema-show',['id'=>$id]);
    }
    public function show($id)
    {
        $detalle_comision = DB::table('detalle_esquema_comision')
        ->join('users as u','usuario','=','u.id','left',false)
        ->select('id_detalle_esquema_comision', 'rubro','factor','tipo','usuario','persona','esquema_id', 'u.name as nombre_usuario')
        ->where('id_detalle_esquema_comision',$id)
        ->first();

        $usuarios = DB::table('users')
        ->select('id', 'name')
        ->where('estatus','=','Activo')
        ->get();

        $tiposesquema = array('Asesor','Cerrador','Externo','Otro');

        $rol = auth()->user()->rol;
        $id = auth()->user()->id;
        return view('catalogos.esquema.detalle_esquema.show',['detalle_comision'=>$detalle_comision,'usuarios'=>$usuarios,'tiposesquema'=>$tiposesquema,'rol'=>$rol,'id'=>$id]);
    }
    public function update(request $request, $id)
    {
        $detalle_esquema_comision = DetalleEsquemaComision::findOrFail($id);
        $detalle_esquema_comision->rubro = $request->get('rubro');
        $detalle_esquema_comision->factor = round($request->get('factor'), 3);
        $detalle_esquema_comision->tipo = $request->get('tipo');
        $detalle_esquema_comision->usuario = $request->get('usuario');
        $detalle_esquema_comision->persona = $request->get('persona');

        $id_padre = $detalle_esquema_comision->esquema_id;
        $detalle_esquema_comision->update();

        return redirect()->route('esquema-show',['id'=>$id_padre]);
    }
    public function destroy($id)
    {
        $detalle_comision = DetalleEsquemaComision::find($id);
        $id_padre = $detalle_comision->esquema_id;
        $detalle_comision->delete();

        return redirect()->route('esquema-show',['id'=>$id_padre]);
    }
}
