<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class PlanoController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $proyecto = $request->get('proyecto_bs');

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre','plano_mapa')
        ->orderby('nombre','ASC')
        ->get();
        if (count($proyectos) == 1) {
            foreach ($proyectos as $key) {
                $proyecto = $key->id_proyecto;
            }
        }

        $propiedades = DB::table('propiedad as prop')
        ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
        ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
        ->select('id_propiedad','nombre','cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_propiedad','up.uso_propiedad','ep.codigo_hx','coordenadas','construccion_metros','terreno_metros','prop.proyecto_id')
        ->orderby('nombre','ASC')
        ->where('proyecto_id',$proyecto)
        ->get();

        $one_proyecto = DB::table('proyecto')
        ->select('id_proyecto','nombre','plano_mapa')
        ->orderby('nombre','ASC')
        ->where('id_proyecto',$proyecto)
        ->first();

        $niveles = DB::table('nivel')
        ->select('id_nivel','nivel','plano')
        ->orderby('orden','ASC')
        ->where('proyecto_id',$proyecto)
        ->get();
        $tipos = array('Fisica','Moral');

        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();
        //echo "filtro: ".$proyecto."<br/>";
        //return json_encode($niveles);
        return view('plano.index',['propiedades'=>$propiedades,'proyectos'=>$proyectos,'one_proyecto'=>$one_proyecto, 'niveles'=>$niveles,'request'=>$request,'tipos'=>$tipos,'medios_contacto'=>$medios_contacto]);
    }
    public function show($id)
    {
        $propiedad = DB::table('propiedad as prop')
        ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
        ->join('moneda as m','prop.moneda','=','m.id_moneda','left',false)
        ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
        ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
        ->select('id_propiedad','prop.nombre','prop.cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_propiedad','up.uso_propiedad','proyecto_id','py.nombre as proyecto','py.direccion','construccion_metros','terreno_metros','descripcion_corta','condicion','prop.moneda','codigo_postal', 'prop.latitude','prop.longitude','recamaras','banos','vigilancia','area_rentable_metros','prop.cajones_estacionamiento','sala_tv','cuarto_servicio','infraestructura','terreno','construccion','area_rentable','estacionamiento','acabados','pdf','fecha_registro','coordenadas','m.siglas')
        ->where('id_propiedad','=',$id)
        ->first();

        $amenidades_propiedad = DB::table('amenidad_propiedad as ap')
        ->join('amenidad as a','ap.amenidad_id','=','a.id_amenidad','left',false)
        ->select('id_amenidad_propiedad','propiedad_id','amenidad_id','a.amenidad')
        ->where('propiedad_id','=',$id)
        ->paginate(5);

        $imagenes_propiedad = DB::table('imagen_propiedad')
        ->select('id_imagen','propiedad_id','imagen_path')
        ->where('propiedad_id','=',$id)
        ->paginate(5);
        return view('plano.show',['propiedad'=>$propiedad,'amenidades_propiedad'=>$amenidades_propiedad,'imagenes_propiedad'=>$imagenes_propiedad]);
    }

    public function nivel(request $request, $id)
    {
        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre','plano_mapa')
        ->orderby('nombre','ASC')
        ->get();

        $propiedades = DB::table('propiedad as prop')
        ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
        ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
        ->select('id_propiedad','nombre','cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_propiedad','up.uso_propiedad','ep.codigo_hx','coordenadas','construccion_metros','terreno_metros','prop.proyecto_id')
        ->orderby('nombre','ASC')
        ->where('nivel_id',$id)
        ->get();

        $one_nivel = DB::table('nivel as n')
        ->join('proyecto as p','p.id_proyecto','=','n.proyecto_id','left',false)
        ->select('id_nivel','n.nivel','n.plano','n.proyecto_id','p.nombre')
        ->orderby('nivel','ASC')
        ->where('id_nivel',$id)
        ->first();

        $niveles = DB::table('nivel')
        ->select('id_nivel','nivel','plano')
        ->orderby('orden','ASC')
        ->where('proyecto_id',$one_nivel->proyecto_id)
        ->get();
        $medios_contacto = DB::table('medio_contacto')
        ->select('id_medio_contacto', 'medio_contacto')
        ->orderby('medio_contacto','ASC')
        ->get();

        $tipos = array('Fisica','Moral');
        //echo "filtro: ".$proyecto."<br/>";
        //echo json_encode($propiedades);
        return view('plano.nivel',['propiedades'=>$propiedades,'proyectos'=>$proyectos,'one_nivel'=>$one_nivel, 'niveles'=>$niveles,'tipos'=>$tipos,'medios_contacto'=>$medios_contacto]);
    }
}
