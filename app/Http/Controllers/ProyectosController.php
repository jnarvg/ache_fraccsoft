<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Proyecto;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use Image;
use App\Http\Middleware\Authenticate;

class ProyectosController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index( request $request)
    {
        $nombre = $request->get('nombre_bs');
        $id = $request->get('id_bs');

        $proyectos = DB::table('proyecto as py')
        ->join('pais as p','py.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','py.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','py.ciudad_id','=','c.id_ciudad','left',false)
        ->select('id_proyecto', 'nombre','metros_construccion','metros_terreno','direccion','latitude','longitude','py.pais_id','py.estado_id','py.ciudad_id', 'p.pais','e.estado','c.ciudad',  'py.calle', 'py.num_exterior', 'py.num_interior', 'py.codigo_postal', 'py.colonia')
        ->orderby('nombre','ASC')
        ->where('py.nombre','LIKE',"%$nombre%")
        ->where('py.id_proyecto','LIKE',"%$id%")
        ->get();

        $paises = DB::table('pais')
        ->get();
        return view('proyectos.index',compact('proyectos','request','paises'));
    }
    public function store(request $request)
    {
        $proyecto = new Proyecto();
        $proyecto->nombre = $request->get('nombre');
        $proyecto->direccion = $request->get('direccion');
        $proyecto->calle = $request->get('calle');
        $proyecto->num_exterior = $request->get('num_exterior');
        $proyecto->num_interior = $request->get('num_interior');
        $proyecto->codigo_postal = $request->get('codigo_postal');
        $proyecto->colonia = $request->get('colonia');
        if ($request->get('pais') != 'Vacio') {
            $proyecto->pais_id = $request->get('pais');
        }
        if ($request->get('estado') != 'Vacio') {
            $proyecto->estado_id = $request->get('estado');
        }
        if ($request->get('ciudad') != 'Vacio') {
            $proyecto->ciudad_id = $request->get('ciudad');
        }

        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');
            $path = Storage::disk('public')->put('planos',$file);

            $proyecto->plano= $path;
        }
        $proyecto->cuenta_catastral = $request->get('cuenta_catastral');
        $proyecto->metros_construccion = $request->get('metros_construccion');
        $proyecto->metros_terreno = $request->get('metros_terreno');
        $proyecto->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
    public function show(request $request, $id)
    {
        $rows_page = 10;
        $rows_page = $request->get('rows_per_page');
        $estatus_bs = $request->get('estatus_bs');
        $word_bs = $request->get('word_bs');

        if ($rows_page == '') {
            $rows_page = 5;
        }
        $proyecto = DB::table('proyecto as py')
        ->join('pais as p','py.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','py.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','py.ciudad_id','=','c.id_ciudad','left',false)
        ->select('id_proyecto', 'nombre','metros_construccion','metros_terreno','direccion','latitude','longitude','py.pais_id','py.estado_id','py.ciudad_id', 'p.pais','e.estado','c.ciudad','plano','cuenta_catastral','plano_mapa','portada_cotizacion','logo_cotizacion','header_contrato','footer_contrato',  'py.calle', 'py.num_exterior', 'py.num_interior', 'py.codigo_postal', 'py.colonia')
        ->where('id_proyecto','=',$id)
        ->first();
        
        if ($estatus_bs != '' and $estatus_bs != 'Vacio') {
            $propiedades = DB::table('propiedad as prop')
            ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
            ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
            ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
            ->join('tipo_propiedad_bakt as tpb','prop.tipo_propiedad_bakt_id','=','tpb.id_tipo_propiedad','left',false)
            ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
            ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
            ->join('tipo_modelo as tp','prop.tipo_modelo_id','=','tp.id_tipo_modelo','left',false)
            ->select('id_propiedad','nombre','cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_modelo','up.uso_propiedad','manzana','tpb.tipo_modelo')
            ->where('proyecto_id','=',$id)
            ->where('nombre','LIKE', "%$word_bs%")
            ->where('ep.estatus_propiedad','=',$estatus_bs)
            ->orderby('id_propiedad','DESC')
            ->paginate($rows_page);
        }else{
            $propiedades = DB::table('propiedad as prop')
            ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
            ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
            ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
            ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
            ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
            ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
            ->join('tipo_modelo as tpb','prop.tipo_modelo_id','=','tpb.id_tipo_modelo','left',false)
            ->select('id_propiedad','nombre','cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_propiedad','up.uso_propiedad','manzana','tpb.tipo_modelo')
            ->where('proyecto_id','=',$id)
            ->where('nombre','LIKE', "%$word_bs%")
            ->orderby('id_propiedad','DESC')
            ->paginate($rows_page);
        }
        $nivel_bs = $request->get('nivel_bs');
        $niveles_proyecto = DB::table('nivel as e')
        ->join('proyecto as py','e.proyecto_id','=','py.id_proyecto','left',false)
        ->select('e.id_nivel','e.nivel','py.nombre as proyecto','e.proyecto_id','e.orden')
        ->orderBy('e.orden','ASC')
        ->where('nivel','LIKE',"%$nivel_bs%")
        ->where('e.proyecto_id',$id)
        ->paginate(10); 

        $paises = DB::table('pais')
        ->get();
        $estados = DB::table('estado')
        ->where('pais_id', $proyecto->pais_id)
        ->get();
        $ciudades = DB::table('ciudad')
        ->where('estado_id', $proyecto->estado_id)
        ->get();


        $tipo_propiedad = DB::table('tipo_propiedad')
        ->get();

        $tipo_modelo = DB::table('tipo_modelo')
        ->get();

        $estatus_propiedad = DB::table('estatus_propiedad')
        ->get();
        $niveles = DB::table('nivel')
        ->where('proyecto_id','=',$id)
        ->get();
        $rows_pagina = array('5','10','25','50','100');
        $monedas =DB::table('moneda')
        ->get();
        $uso_propiedad = DB::table('uso_propiedad')
        ->get();

        $procedencia = 'proyectos-show';
        return view('proyectos.show',compact('proyecto', 'propiedades','paises','tipo_propiedad','uso_propiedad','estatus_propiedad','niveles','rows_pagina','request','monedas','tipo_modelo','niveles_proyecto','procedencia','estados','ciudades'));
    }
    public function update(request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->nombre = $request->get('nombre');
        $proyecto->direccion = $request->get('direccion');
        $proyecto->calle = $request->get('calle');
        $proyecto->num_exterior = $request->get('num_exterior');
        $proyecto->num_interior = $request->get('num_interior');
        $proyecto->codigo_postal = $request->get('codigo_postal_py');
        $proyecto->colonia = $request->get('colonia');
        if ($request->get('pais_py') != 'Vacio') {
            $proyecto->pais_id = $request->get('pais_py');
        }
        if ($request->get('estado_py') != 'Vacio') {
            $proyecto->estado_id = $request->get('estado_py');
        }
        if ($request->get('ciudad_py') != 'Vacio') {
            $proyecto->ciudad_id = $request->get('ciudad_py');
        }
        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->plano;
            $proyecto->plano = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->plano, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }

        if (!empty($request->file('plano_mapa'))) 
        {   
            $file = $request->file('plano_mapa');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->plano_mapa;
            $proyecto->plano_mapa = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->plano_mapa, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        if (!empty($request->file('portada_cotizacion'))) 
        {   
            $file = $request->file('portada_cotizacion');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->portada_cotizacion;
            $proyecto->portada_cotizacion = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->portada_cotizacion, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        if (!empty($request->file('logo_cotizacion'))) 
        {   
            $file = $request->file('logo_cotizacion');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->logo_cotizacion;
            $proyecto->logo_cotizacion = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->logo_cotizacion, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        if (!empty($request->file('header_contrato'))) 
        {   
            $file = $request->file('header_contrato');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->header_contrato;
            $proyecto->header_contrato = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->header_contrato, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        if (!empty($request->file('footer_contrato'))) 
        {   
            $file = $request->file('footer_contrato');

            $path = Storage::disk('public')->put('planos',$file);
            $foto_eliminar = $proyecto->footer_contrato;
            $proyecto->footer_contrato = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($proyecto->footer_contrato, "planos/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('planos/'.$foto_eliminar);
                }
            }
        }
        $proyecto->cuenta_catastral = $request->get('cuenta_catastral');
        $proyecto->metros_construccion = $request->get('metros_construccion');
        $proyecto->metros_terreno = $request->get('metros_terreno');
        $proyecto->update();
        
        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
    public function destroy($id)
    {   
        $propiedades = DB::table('propiedad')
        ->where('proyecto_id','=',$id)
        ->select(DB::raw('count(*) as cont_prop'))
        ->first();
        if ($propiedades->cont_prop == 0) {
            $proyecto = Proyecto::find($id);
            $proyecto->delete();
            $notification = array(
                'msj' => 'Listo!!',
                'alert-type' => 'success'
            );

            return back()->with($notification);
        }else{
            $notification = array(
                'msj' => 'No se puede eliminar, aun hay propiedades anidadas a este proyecto',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
}
