<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\EsquemaComision;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class EsquemaComisionController extends Controller
{
    //XXXX-XXSDSDSD123
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $esquemas_comision = DB::table('esquema_comision')
        ->select('id_esquema_comision', 'esquema_comision')
        ->orderby('esquema_comision','ASC')
        ->paginate(10);
        return view('catalogos.esquema.index',['esquemas_comision'=>$esquemas_comision]);
    }
    public function store(request $request)
    {
        $esquema_comision = new EsquemaComision();
        $esquema_comision->esquema_comision = $request->get('esquema_comision_mdl');
        $esquema_comision->save();
        return redirect()->route('esquema');
    }
    public function show($id)
    {
        $esquema_comision = DB::table('esquema_comision')
        ->select('id_esquema_comision', 'esquema_comision')
        ->where('id_esquema_comision','=',$id)
        ->first();

        $detalles_comisiones = DB::table('detalle_esquema_comision')
        ->join('users as u','usuario','=','u.id','left',false)
        ->select('id_detalle_esquema_comision', 'rubro','factor','tipo','usuario','persona','esquema_id', 'u.name as nombre_usuario')
        ->where('esquema_id','=',$id)
        ->paginate(10);

        $usuarios = DB::table('users')
        ->select('id', 'name')
        ->where('estatus','=','Activo')
        ->get();

        return view('catalogos.esquema.show',['esquema_comision'=>$esquema_comision, 'detalles_comisiones'=>$detalles_comisiones,'usuarios'=>$usuarios]);
    }
    public function update(request $request, $id)
    {
        $esquema_comision = EsquemaComision::findOrFail($id);
        $esquema_comision->esquema_comision = $request->get('esquema_comision');
        $esquema_comision->update();
        return redirect()->route('esquema');
    }
    public function destroy($id)
    {
        $esquema_comision = EsquemaComision::find($id);
        DB::table('detalle_esquema_comision')->where('esquema_id', '=', $id)->delete();
        $esquema_comision->delete();
        return redirect()->route('esquema');
    }
}
