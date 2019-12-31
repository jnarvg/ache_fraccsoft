<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Propiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Exports\PropiedadExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Http\Middleware\Authenticate;

class PropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index(request $request)
    {
        $nombre = $request->get('nombre_bs');
        $estatus = $request->get('estatus_bs');
        $proyecto = $request->get('proyecto_bs');

        $filtro = $this->build_filtro($request);
        $propiedades = DB::table('propiedad as prop')
        ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
        ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('tipo_modelo as tp','prop.tipo_modelo_id','=','tp.id_tipo_modelo','left',false)
        ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
        ->select('id_propiedad','prop.nombre','prop.cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_modelo','up.uso_propiedad','manzana','py.nombre as proyecto','enganche')
        ->whereRaw($filtro)
        ->get();
        $paises = DB::table('pais')
        ->get();

        $tipo_propiedad = DB::table('tipo_propiedad')
        ->get();
        $tipo_modelo = DB::table('tipo_modelo')
        ->get();

        $estatus_propiedad = DB::table('estatus_propiedad')
        ->get();

        $uso_propiedad = DB::table('uso_propiedad')
        ->get();

        $monedas = DB::table('moneda')
        ->get();

        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();
        $procedencia = 'propiedades';

        return view('propiedades.index',compact('propiedades','request','paises','proyectos','tipo_propiedad','uso_propiedad','estatus_propiedad','monedas','tipo_modelo','procedencia'));
    }
    public function store(request $request)
    {
        $propiedad = new Propiedad();
        $propiedad->nombre = $request->get('nombre');
        $propiedad->construccion_metros = $request->get('construccion_metros');
        $propiedad->direccion = $request->get('direccion');
        $propiedad->manzana = $request->get('manzana');
        $propiedad->numero = $request->get('numero');
        $propiedad->terreno_metros = $request->get('terreno_metros');
        $propiedad->metros_contrato = $request->get('metros_contrato');
        $propiedad->metros_frente = $request->get('metros_frente');
        $propiedad->metros_fondo = $request->get('metros_fondo');
        $propiedad->mts_interior = $request->get('mts_interior');
        $propiedad->mts_exterior = $request->get('mts_exterior');
        $propiedad->precio_mts_interior = $request->get('precio_mts_interior');
        $propiedad->precio_mts_exterior = $request->get('precio_mts_exterior');
        $propiedad->cuenta_catastral = $request->get('cuenta_catastral');
        if ($request->get('proyecto') != 'Vacio') {
            $propiedad->proyecto_id = $request->get('proyecto');
        }
        if ($request->get('nivel') != 'Vacio') {
            $propiedad->nivel_id = $request->get('nivel');
        }
        if ($request->get('tipo_propiedad') != 'Vacio') {
            $propiedad->tipo_propiedad_id = $request->get('tipo_propiedad');
        }
        if ($request->get('tipo_modelo') != 'Vacio') {
            $propiedad->tipo_modelo_id = $request->get('tipo_modelo');
        }
        if ($request->get('uso_propiedad') != 'Vacio') {
            $propiedad->uso_propiedad_id = $request->get('uso_propiedad');
        }
        if ($request->get('estatus_propiedad') != 'Vacio') {
            $propiedad->estatus_propiedad_id = $request->get('estatus_propiedad');
        }
        $propiedad->descripcion_corta = $request->get('descripcion_corta');
        $propiedad->condicion = $request->get('condicion');
        $propiedad->precio = floatval(str_replace(',', '', $request->get('precio')));
        $propiedad->enganche = floatval(str_replace(',', '', $request->get('enganche')));
        $propiedad->moneda = $request->get('moneda');
        if ($request->get('pais') != 'Vacio') {
            $propiedad->pais_id = $request->get('pais');
        }
        if ($request->get('estado') != 'Vacio') {
            $propiedad->estado_id = $request->get('estado');
        }
        if ($request->get('ciudad') != 'Vacio') {
            $propiedad->ciudad_id = $request->get('ciudad');
        }
        $propiedad->codigo_postal = $request->get('codigo_postal');
        $propiedad->recamaras = $request->get('recamaras');
        $propiedad->banos = $request->get('banos');
        $propiedad->vigilancia = $request->get('vigilancia');
        $propiedad->area_rentable_metros = $request->get('area_rentable_metros');
        $propiedad->cajones_estacionamiento = $request->get('cajones_estacionamiento');
        $propiedad->sala_tv = $request->get('sala_tv');
        $propiedad->cuarto_servicio = $request->get('cuarto_servicio');
        $propiedad->infraestructura = $request->get('infraestructura');
        $propiedad->terreno = $request->get('terreno');
        $propiedad->construccion = $request->get('construccion');
        $propiedad->area_rentable = $request->get('area_rentable');
        $propiedad->estacionamiento = $request->get('estacionamiento');
        $propiedad->acabados = $request->get('acabados');
        $propiedad->fecha_registro = $request->get('fecha_registro');

        $propiedad->save();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);

    }
    public function show($id, $procedencia = '', $padre)
    {
        $propiedad = DB::table('propiedad as prop')
        ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
        ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
        ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
        ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
        ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
        ->join('tipo_modelo as tpb','prop.tipo_modelo_id','=','tpb.id_tipo_modelo','left',false)
        ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
        ->select('id_propiedad','prop.nombre','prop.cuenta_catastral','p.pais as pais','e.estado as estado','c.ciudad as ciudad','precio','ep.estatus_propiedad','tp.tipo_propiedad','up.uso_propiedad','proyecto_id','py.nombre as proyecto','construccion_metros','terreno_metros','descripcion_corta','condicion','moneda','prop.codigo_postal', 'prop.latitude','prop.longitude','recamaras','banos','vigilancia','area_rentable_metros','prop.cajones_estacionamiento','sala_tv','cuarto_servicio','infraestructura','terreno','construccion','area_rentable','estacionamiento','acabados','fecha_registro','coordenadas','tipo_propiedad_id','estatus_propiedad_id','uso_propiedad_id','prop.direccion','prop.pais_id','prop.estado_id', 'prop.ciudad_id','manzana','enganche','nivel_id','prop.numero','prop.metros_frente', 'prop.metros_fondo','prop.metros_contrato','prop.tipo_modelo_id','tpb.tipo_modelo','prop.precio_mts_interior','prop.precio_mts_exterior','prop.mts_interior','prop.mts_exterior')
        ->where('id_propiedad','=',$id)
        ->first();

        $amenidades_propiedad = DB::table('amenidad_propiedad as ap')
        ->join('amenidad as a','ap.amenidad_id','=','a.id_amenidad','left',false)
        ->select('id_amenidad_propiedad','propiedad_id','amenidad_id','a.amenidad')
        ->where('propiedad_id','=',$id)
        ->paginate(5);

        $imagenes_propiedad = DB::table('imagen_propiedad')
        ->select('id_imagen','propiedad_id','imagen_path','titulo')
        ->where('propiedad_id','=',$id)
        ->paginate(5);

        $paises = DB::table('pais')
        ->get();
        $estados = DB::table('estado')
        ->where('pais_id','=',$propiedad->pais_id)
        ->get();
        $ciudades = DB::table('ciudad')
        ->where('estado_id','=',$propiedad->estado_id)
        ->get();

        $tipo_propiedad = DB::table('tipo_propiedad')
        ->get();

        $tipo_modelo = DB::table('tipo_modelo')
        ->get();

        $estatus_propiedad = DB::table('estatus_propiedad')
        ->get();

        $niveles = DB::table('nivel')
        ->where('proyecto_id','=',$propiedad->proyecto_id)
        ->get();

        $uso_propiedad = DB::table('uso_propiedad')
        ->get();
        $amenidades = DB::table('amenidad')
        ->get();
        $proyectos = DB::table('proyecto')
        ->select('id_proyecto','nombre')
        ->get();
        $monedas = DB::table('moneda')
        ->get();

        if ($procedencia == '' or $procedencia == null) {
            $procedencia = 'propiedades';
        }

        return view('propiedades.show',['propiedad'=>$propiedad,'amenidades_propiedad'=>$amenidades_propiedad,'imagenes_propiedad'=>$imagenes_propiedad, 'paises' => $paises, 'proyectos' => $proyectos, 'tipo_propiedad' => $tipo_propiedad, 'uso_propiedad' => $uso_propiedad,'estatus_propiedad' => $estatus_propiedad, 'estados' => $estados, 'ciudades' => $ciudades, 'amenidades'=>$amenidades, 'niveles'=>$niveles,'monedas'=>$monedas,'tipo_modelo'=>$tipo_modelo,'procedencia'=>$procedencia, 'padre'=>$padre]);
    }
    public function update(request $request, $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->coordenadas = $request->get('coordenadas');
        $propiedad->nombre = $request->get('nombre');
        $propiedad->manzana = $request->get('manzana');
        $propiedad->numero = $request->get('numero');
        $propiedad->construccion_metros = $request->get('construccion_metros');
        $propiedad->metros_contrato = $request->get('metros_contrato');
        $propiedad->metros_frente = $request->get('metros_frente');
        $propiedad->metros_fondo = $request->get('metros_fondo');
        $propiedad->precio_mts_interior = $request->get('precio_mts_interior');
        $propiedad->precio_mts_exterior = $request->get('precio_mts_exterior');
        $propiedad->mts_interior = $request->get('mts_interior');
        $propiedad->mts_exterior = $request->get('mts_exterior');
        $propiedad->direccion = $request->get('direccion');
        $propiedad->terreno_metros = $request->get('terreno_metros');
        $propiedad->cuenta_catastral = $request->get('cuenta_catastral');

        $propiedad->proyecto_id = null;
        if ($request->get('proyecto') != 'Vacio') {
            $propiedad->proyecto_id = $request->get('proyecto');
        }
        if ($request->get('nivel') != 'Vacio') {
            $propiedad->nivel_id = $request->get('nivel');
        }
        if ($request->get('tipo_propiedad') != 'Vacio') {
            $propiedad->tipo_propiedad_id = $request->get('tipo_propiedad');
        }

        if ($request->get('tipo_modelo') != 'Vacio') {
            $propiedad->tipo_modelo_id = $request->get('tipo_modelo');
        }
        if ($request->get('uso_propiedad') != 'Vacio') {
            $propiedad->uso_propiedad_id = $request->get('uso_propiedad');
        }
        if ($request->get('estatus_propiedad') != 'Vacio') {
            $propiedad->estatus_propiedad_id = $request->get('estatus_propiedad');
        }
        $propiedad->descripcion_corta = $request->get('descripcion_corta');
        $propiedad->condicion = $request->get('condicion');
        $propiedad->precio = floatval(str_replace(',', '', $request->get('precio')));
        $propiedad->enganche = floatval(str_replace(',', '', $request->get('enganche')));
        $propiedad->moneda = $request->get('moneda');
        if ($request->get('pais') != 'Vacio') {
            $propiedad->pais_id = $request->get('pais');
        }
        if ($request->get('estado') != 'Vacio') {
            $propiedad->estado_id = $request->get('estado');
        }
        if ($request->get('ciudad') != 'Vacio') {
            $propiedad->ciudad_id = $request->get('ciudad');
        }
        $propiedad->codigo_postal = $request->get('codigo_postal');
        $propiedad->recamaras = $request->get('recamaras');
        $propiedad->banos = $request->get('banos');
        $propiedad->vigilancia = $request->get('vigilancia');
        $propiedad->area_rentable_metros = $request->get('area_rentable_metros');
        $propiedad->cajones_estacionamiento = $request->get('cajones_estacionamiento');
        $propiedad->sala_tv = $request->get('sala_tv');
        $propiedad->cuarto_servicio = $request->get('cuarto_servicio');
        $propiedad->infraestructura = $request->get('infraestructura');
        $propiedad->terreno = $request->get('terreno');
        $propiedad->construccion = $request->get('construccion');
        $propiedad->area_rentable = $request->get('area_rentable');
        $propiedad->estacionamiento = $request->get('estacionamiento');
        $propiedad->acabados = $request->get('acabados');
        $propiedad->update();
        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    public function destroy($id)
    {
        $propiedad = Propiedad::find($id);

        if ($propiedad->estatus_propiedad_id == 1 /*Disponible*/) {
            $prospectos = DB::table('prospectos as si')
              ->where('si.propiedad_id','=',$id)
              ->select(DB::raw('count(*) as contador'))
              ->first();
            if ($prospectos->contador >0){
                $notification = array(
                    'msj' => 'No se puede eliminar esta propiedad, hay prospectos que la tienen ligada.',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }else{
                $propiedad->delete();
                $notification = array(
                    'msj' => 'Listo!!',
                    'alert-type' => 'success'
                );
                return back()->with($notification);
            }
        }else{
            $notification = array(
                'msj' => 'No se puede eliminar esta propiedad, debe de estar Disponible.',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        
    }

    public function build_filtro(request $request)
    {
        $nombre_bs = $request->get('nombre_bs');
        $estatus_bs = $request->get('estatus_bs');
        $proyecto_bs = $request->get('proyecto_bs');
        $uso_propiedad_bs = $request->get('uso_propiedad_bs');

        $filtro = 'id_propiedad  is not null';
        if ($nombre_bs != '' and $nombre_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' prop.nombre LIKE "%'.$nombre_bs.'%"';
        }
        if ($estatus_bs != '' and $estatus_bs != 'Vacio' and $estatus_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' prop.estatus_propiedad_id = '.$estatus_bs;
        }

        if ($proyecto_bs != '' and $proyecto_bs != 'Vacio' and $proyecto_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' prop.proyecto_id = '.$proyecto_bs;
        }

        if ($uso_propiedad_bs != '' and $uso_propiedad_bs != 'Vacio' and $uso_propiedad_bs != null){
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro . ' prop.uso_propiedad_id = '.$uso_propiedad_bs;
        }

        return $filtro;
    }
    public function exportExcel(request $request)
    {   
        $nombre = $request->get('nombre_excel');
        $estatus = $request->get('estatus_excel');
        $proyecto = $request->get('proyecto_excel');
        $uso_propiedad = $request->get('uso_propiedad_excel');
        $id_proyecto = $request->get('id_proyecto_excel');
        $query ='prop.nombre LIKE "%'.$nombre.'%"';
        if ($id_proyecto != 'Vacio' and $id_proyecto != '' and $id_proyecto != null) {
            $query = $query. 'and prop.proyecto_id = '. $id_proyecto;
        }
        if ($estatus != 'Vacio' and $estatus != '') {
            $query = $query." and ep.estatus_propiedad = '".$estatus."'";
        }
        if ($uso_propiedad != '' and $uso_propiedad != 'Vacio' and $uso_propiedad != null){
            if ($query != '') {
                $query = $query . ' AND ';
            }
            $query = $query . ' prop.uso_propiedad_id = '.$uso_propiedad;
        }
        if ($proyecto != '' and $proyecto != 'Vacio' and $proyecto != null){
            if ($query != '') {
                $query = $query . ' AND ';
            }
            $query = $query . ' prop.proyecto_id = '.$proyecto;
        }
        //echo "_resulto query: ".$query;
        $data_result = DB::table('propiedad as prop')
            ->join('pais as p','prop.pais_id','=','p.id_pais','left',false)
            ->join('estado as e','prop.estado_id','=','e.id_estado','left',false)
            ->join('ciudad as c','prop.ciudad_id','=','c.id_ciudad','left',false)
            ->join('moneda as m','prop.moneda','=','m.id_moneda','left',false)
            ->join('nivel as n','prop.nivel_id','=','n.id_nivel','left',false)
            ->join('estatus_propiedad as ep','prop.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
            ->join('uso_propiedad as up','prop.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
            ->join('tipo_propiedad as tp','prop.tipo_propiedad_id','=','tp.id_tipo_propiedad','left',false)
            ->join('tipo_modelo as tm','prop.tipo_modelo_id','=','tm.id_tipo_modelo','left',false)
            ->join('proyecto as py','prop.proyecto_id','=','py.id_proyecto','left',false)
            ->select('prop.id_propiedad','prop.nombre','tp.tipo_propiedad as tipo_propiedad_id','n.nivel as nivel_id','py.nombre as proyecto_id','prop.construccion_metros','prop.terreno_metros','prop.cuenta_catastral','up.uso_propiedad as uso_propiedad_id','ep.estatus_propiedad as estatus_propiedad_id','prop.descripcion_corta','prop.condicion','prop.enganche','prop.precio','m.siglas as moneda','p.pais as pais_id','e.estado as estado_id','c.ciudad as ciudad_id','prop.codigo_postal','prop.latitude','prop.longitude','prop.recamaras','prop.banos','prop.vigilancia','prop.area_rentable_metros','prop.cajones_estacionamiento','prop.sala_tv','prop.cuarto_servicio','prop.infraestructura','prop.terreno','prop.construccion','prop.area_rentable','prop.estacionamiento','prop.acabados','prop.pdf','prop.fecha_registro','prop.coordenadas','prop.direccion','prop.manzana','prop.numero','prop.metros_fondo','prop.metros_frente','prop.metros_contrato','prop.metros_plano','tm.tipo_modelo as tipo_modelo_id','prop.precio_mts_interior','prop.precio_mts_exterior','prop.mts_interior','prop.mts_exterior','prop.mts_total')
            ->orderby('id_propiedad','DESC')
            ->whereRaw($query)
            ->get();
       
        $campos = array('nombre', 'tipo propiedad', 'nivel', 'proyecto', 'uso propiedad', 'estatus propiedad', 'enganche', 'precio', 'moneda', 'pais', 'estado', 'ciudad', 'codigo postal', 'recamaras','cajones estacionamiento','fecha registro', 'coordenadas', 'direccion', 'manzana', 'numero', 'tipo modelo', 'precio mts interior', 'precio mts exterior', 'mts interior', 'mts exterior', 'mts total');

       ob_end_clean();
       return Excel::download(new PropiedadExport("exports.propiedad", $data_result, $campos),'Propiedad.xlsx');
    }

    public function updateinfo()
    {
        $propiedades = array('NZ - MZ 1 - L1A','NZ - MZ 1 - L1B','NZ - MZ 1 - L2A','NZ - MZ 1 - L2B','NZ - MZ 1 - L2C','NZ - MZ 2 - L1A','NZ - MZ 2 - L1B','NZ - MZ 2 - L2A','NZ - MZ 2 - L2B','NZ - MZ 2 - L3B','NZ - MZ 2 - L4B','NZ - MZ 3 - L1A','NZ - MZ 3 - L1B','NZ - MZ 3 - L2A','NZ - MZ 3 - L2B','NZ - MZ 3 - L3A','NZ - MZ 3 - L3B','NZ - MZ 3 - L4A','NZ - MZ 3 - L4B','NZ - MZ 4 - L1A','NZ - MZ 4 - L1B','NZ - MZ 4 - L2A','NZ - MZ 4 - L2B','NZ - MZ 4 - L3A','MS - EDIF A - A1','MS - EDIF A - A2','MS - EDIF A - A3','MS - EDIF A - A4','MS - EDIF A - A5','MS - EDIF A - A6','MS - EDIF A - A7','MS - EDIF A - A8','MS - EDIF A - A9','MS - EDIF A - A10','MS - EDIF A - A11','MS - EDIF A - A12','MS - EDIF B - B1','MS - EDIF B - B2','MS - EDIF B - B3','MS - EDIF B - B4','MS - EDIF B - B5','MS - EDIF B - B6','MS - EDIF B - B7','MS - EDIF B - B8','MS - EDIF B - B9','MS - EDIF B - B10','MS - EDIF B - B11','MS - EDIF B - B12','MS - EDIF C - C1','MS - EDIF C - C2','MS - EDIF C - C3','MS - EDIF C - C4','MS - EDIF C - C5','MS - EDIF C - C6','MS - EDIF C - C7','MS - EDIF C - C8','MS - EDIF C - C9','MS - EDIF C - C10','MS - EDIF C - C11','MS - EDIF C - C12','LC - MZ 1 - L6 - 1','LC - MZ 1 - L4 - 3','LC - MZ 1 - L3 - 4','LC - MZ 1 - L2 - 5','LC - MZ 1 - L1 - 6','LC - MZ 2 - L13 - 7','LC - MZ 2 - L12 - 8','LC - MZ 2 - L11 - 9','LC - MZ 2 - L10 - 10','LC - MZ 2 - L9 - 11','LC - MZ 2 - L8 - 12','LC - MZ 2 - L7 - 13','LC - MZ 2 - L6 - 14','LC - MZ 2 - L5 - 15','LC - MZ 3 - L17 - 28','LC - MZ 3 - L16 - 29','LC - MZ 3 - L15 - 30','LC - MZ 3 - L14 - 31','LC - MZ 3 - L13 - 32','LC - MZ 3 - L12 - 33','AZ - MZ 5 - L1 - 46','AZ - MZ 5 - L2 - 47','AZ - MZ 5 - L4 - 49','GZ - MZ 3 - 9A - 36-A','GZ - MZ 3 - 9B - 36-B','GZ - MZ 3 - 9G - 36-G','GZ - MZ 3 - 9H - 36-H','GZ - MZ 3 - 9C - 36-C','GZ - MZ 3 - 9D - 36-D','GZ - MZ 3 - 9I - 36-I','GZ - MZ 3 - 9J - 36-J','GZ - MZ 3 - 9E - 36-E','GZ - MZ 3 - 9F - 36-F','GZ - MZ 3 - 9K - 36-K','GZ - MZ 3 - 9L - 36-L');

        $coordenadas = array('1014424.83','920000.00','862564.83','849488.57','849488.57','870000.00','870000.00','870000.00','890000.00','870000.00','870000.00','920000.00','870000.00','920000.00','920000.00','940000.00','870000.00','870000.00','870000.00','920000.00','870000.00','820000.00','920000.00','940000.00','486660.00','480580.80','491400.00','493540.00','475000.00','475000.00','475000.00','450000.00','475000.00','475000.00','475000.00','475000.00','475000.00','505580.80','527000.00','527600.00','475000.00','475000.00','475000.00','475000.00','475000.00','475000.00','475000.00','470000.00','486490.80','486300.00','468540.00','468540.00','450000.00','450000.00','499460.00','450000.00','450000.00','450000.00','450000.00','450000.00','1270000.00','1225500.00','1218000.00','1211500.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1247000.00','1217000.00','1247000.00','1247000.00','1217000.00','1247000.00','1220000.00','1070000.00','1070000.00','1070000.00','745000.00','744000.00','715000.00','683500.00','749000.00','669830.00','749000.00','749000.00','475000.00','671500.00','715000.00','683500.00');
        $i = 0;
        foreach ($propiedades as $key) {
            DB::table('propiedad')
            ->where('nombre', $key)
            ->update(['precio' => $coordenadas[$i]]);
            $i++;   
        }
        return redirect()->route('propiedades');
    }
}
