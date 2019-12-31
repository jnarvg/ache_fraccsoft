<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\User;
use App\Cotizacion;
use App\DetalleCotizacion;
use App\DetalleCotizacionPropiedad;
use App\DetalleCotizacionPropiedadRubro;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
use PDF;


class CotizacionController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $proyectos = DB::table('proyecto as py')
        ->select('id_proyecto', 'nombre')
        ->orderby('nombre','ASC')
        ->get(); 

        $propiedad = DB::table('propiedad as p')
        ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
        ->select('id_propiedad','p.nombre')
        ->whereIn('ep.estatus_propiedad',['Disponible','Bloqueado'])
        ->get();

        $id = auth()->user()->id;
        $agente = DB::table('users')
        ->where('id','=',$id)
        ->first();
        $monedas = DB::table('moneda')
        ->get();

        $usos_propiedad = DB::table('uso_propiedad')
        ->get();

        $grupo_esquema = DB::table('grupo_esquema')
        ->select('id_grupo_esquema','grupo_esquema')
        ->get();

        return view('cotizacion.index', compact('proyectos','agente','monedas','usos_propiedad', 'grupo_esquema'));
    }
    public function continuar(request $request, $procedencia)
    {
        if ($request->get('propiedad') == '' and $request->get('propiedad') == null) {
            $notification = array(
                'msj' => 'Debe elegir almenos una propiedad primero.',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }else{
            if (count( $request->get('propiedad')) > 5) {
                $notification = array(
                    'msj' => 'Esta limitado a 5 propiedades.',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }else{
                if ($request->get('grupo_esquema_id') == '' and $request->get('grupo_esquema_id') == null  ) {
                    $notification = array(
                        'msj' => 'Debe seleccionar un grupo de esquemas',
                        'alert-type' => 'error'
                    );
                    return back()->with($notification);
                }else{
                
                    $uso_propiedad_id = $request->get('uso_propiedad_id');
                    $grupo_esquema_id = $request->get('grupo_esquema_id');
                    $id = auth()->user()->id;
                    $agente = DB::table('users')
                    ->where('id','=',$id)
                    ->first();
                    ///agente
                    $nombreagente = $agente->name;
                    $correoagente = $agente->email;
                    $moneda  = $request->get('moneda');
                    $monedas = DB::table('moneda')
                    ->select('siglas')
                    ->where('id_moneda','=',$moneda)
                    ->first();

                    $siglas = $monedas->siglas;
                    ////cliente
                    $nombrecliente = $request->get('cliente');
                    $correocliente = $request->get('correo');
                    $telefonocliente  = $request->get('telefono');
                    $fecha = date('Y-m-d');

                    ///propiedad
                    $proyecto = DB::table('proyecto')
                    ->select('id_proyecto','nombre')
                    ->where('id_proyecto','=',$request->get('proyecto'))
                    ->first();
                    $nombre_proyecto = $proyecto->nombre;
                    $id_proyecto = $proyecto->id_proyecto;
                    $propiedades_request = $request->get('propiedad');
                    $nombres_propiedades = '';
                    $folio_reconocimiento = uniqid();
                    /////   CREAR HISTORIAL
                    $cotizacion = new Cotizacion;
                    $cotizacion->fecha_cotizacion = $fecha;
                    $cotizacion->proyecto = $nombre_proyecto;
                    $cotizacion->proyecto_id = $id_proyecto;
                    $cotizacion->cliente = $nombrecliente;
                    $cotizacion->correo = $correocliente;
                    $cotizacion->telefono = $telefonocliente;
                    $cotizacion->asesor_id = $id;
                    $cotizacion->moneda_id = $moneda;
                    $cotizacion->estatus = 'Abierta';
                    $cotizacion->folio = $folio_reconocimiento;
                    $cotizacion->uso_propiedad_id = $uso_propiedad_id;
                    $cotizacion->grupo_esquema_id = $grupo_esquema_id;
                    $cotizacion->save();

                    $nueva = DB::table('cotizacion')
                    ->select('id_cotizacion')
                    ->where('folio',$folio_reconocimiento)->first();
                    $id_cotizacion_nueva = $nueva->id_cotizacion;

                    foreach ($propiedades_request as $key) {
                        $propiedad = DB::table('propiedad as p')
                        ->select('id_propiedad','p.nombre','precio')
                        ->where('id_propiedad','=',$key)
                        ->first();

                        $nombres_propiedades = $nombres_propiedades.', '.$propiedad->nombre;
                        $precio = $propiedad->precio;

                        $esquema_pago_find = DB::table('esquema_pago')->select('id_esquema_pago','esquema_pago','porcentaje_descuento','incluir')
                        ->where('grupo_esquema_id', $grupo_esquema_id)
                        ->get();

                        foreach ($esquema_pago_find as $d) {
                            $porcentaje_descuento = $d->porcentaje_descuento;
                            $detalle = new DetalleCotizacionPropiedad;
                            $detalle->esquema_pago = $d->esquema_pago;
                            $detalle->propiedad_id = $key;
                            $detalle->precio = $precio;
                            $detalle->porcentaje_descuento = $d->porcentaje_descuento;
                            if ($d->porcentaje_descuento != null and $d->porcentaje_descuento != '') {
                                $monto_descuento = $precio * ($d->porcentaje_descuento/100);
                            }else{
                                $monto_descuento = 0;
                            }
                            $precio_final = $precio - $monto_descuento;
                            $folio_key = uniqid();
                            $detalle->monto_descuento = $monto_descuento;
                            $detalle->precio_final  = $precio_final;
                            $detalle->incluir = $d->incluir;
                            $detalle->cotizacion_id = $id_cotizacion_nueva;
                            $detalle->proyecto_id = $id_proyecto;
                            $detalle->folio_key = $folio_key;

                            $detalle->save();

                            ///AGREAMOS AHORA EL DETALLED E ESQUEMA DE PAGO

                            $ultimo_detalle_propiedad = DB::table('detalle_cotizacion_propiedad')->select('id_detalle_cotizacion_propiedad')
                            ->where('folio_key', $folio_key)
                            ->first();
                            $id_detalle_cotizacion_propiedad = $ultimo_detalle_propiedad->id_detalle_cotizacion_propiedad;

                            $esquema_pago_detalle = DB::table('detalle_esquema_pago')
                            ->where('esquema_pago_id', $d->id_esquema_pago)
                            ->get();
                            foreach ($esquema_pago_detalle as $e) {
                                $detalle_rubro = new DetalleCotizacionPropiedadRubro;

                                $detalle_rubro->alias = $e->alias;
                                $detalle_rubro->fecha = date('Y-m-d');
                                $detalle_rubro->tipo = $e->tipo;
                                $detalle_rubro->tipo_calculo = $e->tipo_calculo;
                                if ($e->tipo_calculo == 'Porcentaje') {
                                    if ($e->porcentaje != null and $e->porcentaje != '') {
                                        $monto = $precio_final * ($e->porcentaje/100);
                                    }else{
                                        $monto = 0;
                                    }
                                    $detalle_rubro->porcentaje = $e->porcentaje;
                                    $detalle_rubro->monto = $monto;
                                }
                                if ($e->tipo_calculo == 'Monto') {
                                    if ($e->monto != null and $e->monto != '') {
                                        $porcentaje = ($monto  * 100 ) / $precio_final;
                                    }else{
                                        $porcentaje = 0;
                                    }
                                    $detalle_rubro->porcentaje = $porcentaje;
                                    $detalle_rubro->monto = $e->monto;
                                }
                                $detalle_rubro->mensualidades = $e->mensualidades;
                                $detalle_rubro->excluir_calculo = 0;
                                $detalle_rubro->excluir_descuento = 0;
                                $detalle_rubro->detalle_cotizacion_propiedad_id = $id_detalle_cotizacion_propiedad;
                                $detalle_rubro->cotizacion_id = $id_cotizacion_nueva;

                                $detalle_rubro->save();
                            }

                        }

                    }
                    /////   CREAR HISTORIAL
                    $cotizacion = Cotizacion::findOrFail($id_cotizacion_nueva);
                    $cotizacion->propiedad = substr($nombres_propiedades, 2);
                    $cotizacion->update();

                    $notification = array(
                        'msj' => 'Listo!!',
                        'alert-type' => 'success'
                    );

                    return redirect('/cotizacion/show/'.$id_cotizacion_nueva.'/'.$procedencia)->with($notification);
                }
            }
        }
    }

    public function update($id, request $request)
    {

        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->uso_propiedad_id = $request->get('uso_propiedad_id');

        $agente = DB::table('users')
        ->where('id','=',$cotizacion->asesor_id)
        ->first();
        ///agente
        $nombreagente = $agente->name;
        $correoagente = $agente->email;
        $cotizacion->moneda_id  = $request->get('moneda');
        $monedas = DB::table('moneda')
        ->select('siglas')
        ->where('id_moneda','=',$cotizacion->moneda_id)
        ->first();
        $siglas = $monedas->siglas;
        ////cliente
        $cotizacion->cliente = $request->get('cliente');
        $cotizacion->correo = $request->get('correo');
        $cotizacion->telefono  = $request->get('telefono');
        $cotizacion->update();

        return back(); 
    }
    public function show($id, $procedencia){
        $resultado = DB::table('cotizacion as c')
        ->join('users as u','c.asesor_id','=','u.id','left',false)
        ->select('c.createdDate','c.id_cotizacion', 'c.fecha_cotizacion', 'c.proyecto','c.proyecto_id', 'c.propiedad', 'c.cliente', 'c.correo', 'c.telefono', 'c.asesor_id', 'c.moneda_id', 'c.estatus', 'c.fecha_cierre', 'c.prospecto_id', 'u.name as asesor_nombre','c.uso_propiedad_id')
        ->where('id_cotizacion',$id)->first();

        $listbtn = DB::table('detalle_cotizacion_propiedad as d')
        ->join('propiedad as p','d.propiedad_id','=','p.id_propiedad','left',false)
        ->select('id_detalle_cotizacion_propiedad','id_propiedad','p.nombre as nombre_propiedad','d.propiedad_id')->where('cotizacion_id', $id)
        ->groupBy('propiedad_id')
        ->get();

        $detalle_propiedad = DB::table('detalle_cotizacion_propiedad as d')
        ->join('propiedad as p','d.propiedad_id','=','p.id_propiedad','left',false)
        ->select('id_detalle_cotizacion_propiedad', 'd.esquema_pago','d.precio','d.monto_descuento', 'd.precio_final', 'd.incluir', 'd.porcentaje_descuento' ,'d.propiedad_id','p.nombre as nombre_propiedad')->where('cotizacion_id', $id)
        ->get();

        $detalles_rubro = DB::table('detalle_cotizacion_propiedad_rubro as d')
        ->select('d.id_detalle_cotizacion_propiedad_rubro','d.alias','d.fecha','d.tipo','d.tipo_calculo','d.monto','d.porcentaje','d.mensualidades','d.excluir_calculo','d.excluir_descuento','d.abono_aplica_a_id','d.detalle_cotizacion_propiedad_id','d.cotizacion_id')
        ->orderBy('d.fecha')
        ->where('cotizacion_id', $id)
        ->get();

        $propiedades = DB::table('propiedad as prop')
        ->select('id_propiedad','nombre')
        ->where('proyecto_id', $resultado->proyecto_id)
        ->where('uso_propiedad_id', $resultado->uso_propiedad_id)->get();

        $tipo_calculo = array('Porcentaje','Monto','Autocompletar');
        $tipo = array('Inicio','Mensualidad','Fin','Abono a capital','No aplica');

        $monedas = DB::table('moneda as m')
        ->select('id_moneda','siglas')->get();
        $usos_propiedad = DB::table('uso_propiedad')
        ->get();

        return view('cotizacion.show', compact('resultado','listbtn','detalle_propiedad','detalles_rubro','propiedades','monedas','usos_propiedad', 'procedencia','tipo','tipo_calculo'));
    }
    public function generarpdf($id, request $request)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $plazos = $cotizacion->plazos;
        
        $agente = DB::table('users')
        ->where('id','=',$cotizacion->asesor_id)
        ->first();
        
        ///agente
        $nombreagente = $agente->name;
        $correoagente = $agente->email;
        $moneda  = $cotizacion->moneda_id;
        $monedas = DB::table('moneda')
        ->select('siglas')
        ->where('id_moneda','=',$moneda)
        ->first();
        $siglas = $monedas->siglas;
        ////cliente
        $nombrecliente = $cotizacion->cliente;
        $correocliente = $cotizacion->correo;
        $telefonocliente  = $cotizacion->telefono;
        $fecha = $cotizacion->fecha_cotizacion;

        ///propiedad
        $proyecto = DB::table('proyecto')
        ->select('id_proyecto','nombre', 'portada_cotizacion','logo_cotizacion')
        ->where('nombre','=',$cotizacion->proyecto)
        ->first();

        ///// Empieza PDF
            PDF::SetAuthor('Next app');
            PDF::SetTitle('Cotizacion');
            PDF::SetSubject($nombreagente);
            PDF::SetKeywords('Cotizacion, PDF');

            // set header and footer fonts
            PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

            // set default monospaced font
            PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            PDF::SetHeaderMargin(0);
            PDF::SetFooterMargin(0);

            // remove default footer
            PDF::setPrintFooter(false);

            // set auto page breaks
            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                PDF::setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            PDF::SetFont('times', '', 48);
            // add a page
            PDF::AddPage();
            // -- set new background ---

            // get the current page break margin
            $bMargin = PDF::getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = PDF::getAutoPageBreak();
            // disable auto-page-break
            PDF::SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = $proyecto->portada_cotizacion;
            PDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            PDF::setPageMark();

            $detalles = DB::table('detalle_cotizacion_propiedad')
            ->where('cotizacion_id',$cotizacion->id_cotizacion)
            ->groupBy('propiedad_id')
            ->get();

            // $propiedades_request = $request->get('propiedad');
            // $nombres_propiedades = '';
            $hayOficina = 'No';
            $hayLocal = 'No';
            $hayDepartamento = 'No';
            foreach ($detalles as $key) {
                $propiedad = DB::table('propiedad as p')
                ->join('nivel as n','p.nivel_id','=','n.id_nivel','left',false)
                ->join('tipo_modelo as tm','p.tipo_modelo_id','=','tm.id_tipo_modelo','left',false)
                ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
                ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
                ->select('id_propiedad','p.nombre','tm.tipo_modelo','n.nivel','n.plano as nivel_foto','ep.estatus_propiedad','precio','mts_interior','mts_exterior','mts_total','up.uso_propiedad')
                ->where('id_propiedad','=',$key->propiedad_id)
                ->first();

                $nombres_propiedades = $propiedad->nombre;
                $precio = $propiedad->precio;


                $imagen_propiedad = DB::table('imagen_propiedad')
                ->select('titulo','imagen_path')
                ->where('propiedad_id',$key->propiedad_id)
                ->get();
                $imagen_path = '';
                $nivel_path = '';
                foreach ($imagen_propiedad as $imgn) {
                    if (strpos($imgn->titulo, 'Info') !== false) {
                        $imagen_path = $imgn->imagen_path;
                    }
                    if (strpos($imgn->titulo, 'Nivel') !== false) {
                        $nivel_path = $imgn->imagen_path;
                    }
                }
                $nombrepropiedad = $propiedad->nombre;
                $nivel = $propiedad->nivel;
                $estatus_propiedad = $propiedad->estatus_propiedad;
                $uso_propiedad = $propiedad->uso_propiedad;
                $mts_total = $propiedad->mts_total;
                $mts_exterior = $propiedad->mts_exterior;
                $mts_interior = $propiedad->mts_interior;
                $tipo_modelo = $propiedad->tipo_modelo;

                $detalles_propiedades = DB::table('detalle_cotizacion_propiedad')
                ->where('cotizacion_id', $key->cotizacion_id)
                ->where('propiedad_id', $key->propiedad_id)
                ->get();

                $esquemas_html = '<div style="color: #150703;">
                    <table width="44%" border="0" cellspacing="0" cellpadding="0">';
                foreach ($detalles_propiedades as $a){
                    $esquemas_html = $esquemas_html.'
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;">'.$a->esquema_pago.'</td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>';
                    $detalle_rubros = DB::table('detalle_cotizacion_propiedad_rubro')
                    ->where('detalle_cotizacion_propiedad_id', $a->id_detalle_cotizacion_propiedad)
                    ->where('cotizacion_id', $a->cotizacion_id)
                    ->orderBy('fecha')
                    ->get();
                    $esquemas_rubro = '';
                    foreach ($detalle_rubros as $b) {
                        $esquemas_rubro = $esquemas_rubro. '<tr>
                            <td style="text-align:left; font-size:10; font-weight:normal;">'.$b->alias.'</td>
                            <td style="text-align:center; font-size:10; font-weight:normal;">'.number_format($b->porcentaje, 2 , "." , ",").'%</td>
                            <td style="text-align:right; font-size:10; font-weight:normal; background-color: #E3E3E3;">$'.number_format($b->monto, 2 , "." , ",").'</td>
                            </tr>';
                    }
                    $esquemas_html = $esquemas_html.$esquemas_rubro.'<tr>
                        <td style="text-align:left; font-size:10; font-weight:normal;">Descuento</td>
                        <td style="text-align:center; font-size:10; font-weight:normal;">'.number_format($a->porcentaje_descuento, 2 , "." , ",").'%</td>
                        <td style="text-align:right; font-size:10; font-weight:normal; background-color: #E3E3E3;">$'.number_format($a->monto_descuento, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td colspan="2" style="text-align:left; font-size:10; font-weight:bolder; background-color: #BBE787;">PRECIO FINAL</td>
                        <td style="text-align:right; font-size:10; font-weight:bolder; background-color: #BBE787;">$'.number_format($a->precio_final, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>';
                }
                $esquemas_html = $esquemas_html. '</table>
                </div>';
                /*SEGUNDA PAGINA empieza el documento*/
                // add a page
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();

                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                $personalizado = '';

                $head_html = '<div style="color: #150703;">
                    <table width="44%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        <td>Fecha</td><td colspan="3" style="text-align:center; font-weight: normal;">'.$fecha.'</td>
                        </tr>
                        <tr>
                        <td >Cliente</td><td colspan="3"style="text-align:center; font-weight: normal;">'.$nombrecliente.'</td>
                        </tr>
                        <tr>
                        <td>No. de Departamento</td><td style="text-align:center; font-weight: normal;">'.$nombrepropiedad.'</td>
                        <td>Plazos</td><td style="text-align:center; font-weight: normal;">'.$plazos.'</td>
                        </tr>
                        <tr>
                        <td>Nivel</td><td style="text-align:center; font-weight: normal;">'.$nivel.'</td>
                        <td>Precio</td><td style="text-align:center; font-weight: normal;">$'.number_format($precio, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td>Tipo Departamento</td><td style="text-align:center; font-weight: normal;">'.$tipo_modelo.'</td>
                        <td>Estatus</td><td style="text-align:center; font-weight: normal;">'.$estatus_propiedad.'</td>
                        </tr>
                        <tr>
                        <td>M2</td><td style="text-align:center; font-weight: normal;">'.$mts_total.'</td>
                        <td></td><td></td>
                        </tr>
                        <tr>
                        <td>m2 Interior</td><td style="text-align:center; font-weight: normal;">'.$mts_interior.'</td>
                        <td></td><td></td>
                        </tr>
                        <tr>
                        <td>m2 Exterior</td><td style="text-align:center; font-weight: normal;">'.$mts_exterior.'</td>
                        <td></td><td></td>
                        </tr>
                    </table>
                </div>';
                $body_html = '<div style="color: #150703;">
                    <table width="44%" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                        <td colspan="4" style="text-align:center; font-size:14;">ESQUEMAS DE PAGO</td>
                        </tr>
                    </table>
                </div>';
                
                //PDF::writeHTML($html, true, false, true, false, '');
                PDF::SetTextColor(0, 0, 0, 0); 
                PDF::SetFont('helveticaB', '', 10);
                PDF::writeHTMLCell(400, 15, 15, 30 , $head_html, 0, 0, 0, false, 'L', false);
                PDF::writeHTMLCell(400, 15, 15, 68 , $body_html, 0, 0, 0, false, 'L', false);
                PDF::writeHTMLCell(300, 15, 35, 75 , $esquemas_html, 0, 0, 0, false, 'L', false);
                
                //agregando las fotos
                /*tercer pagina*/
                $encontrofotos = 'NO';
                if (($nivel_path != null and $nivel_path != '') or ($imagen_path != null and $imagen_path != '')) {
                    $encontrofotos = 'SI';
                }else{
                    $encontrofotos = 'NO';
                }
                if ($encontrofotos == 'SI') {
                    PDF::AddPage();
                    // get the current page break margin
                    $bMargin = PDF::getBreakMargin();
                    // get current auto-page-break mode
                    $auto_page_break = PDF::getAutoPageBreak();
                    // disable auto-page-break
                    PDF::SetAutoPageBreak(false, 0);
                    // restore auto-page-break status
                    PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                    // set the starting point for the page content
                    PDF::setPageMark();
                    // Logo
                    $logo_file = $proyecto->logo_cotizacion;
                    /*Logo*/
                    PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                    if ($nivel_path != null and $nivel_path != '') {
                        
                        PDF::Image($nivel_path, '25', '30', 160, 120, '', '', '', false, 500, '', false, false, 2, false, false, false);
                    }
                    if ($imagen_path != null and $imagen_path != '') {
                        PDF::Image($imagen_path, '25', '150', 160, 90, '', '', '', false, 500, '', false, false, 2, false, false, false);
                    }
                }

                if ($uso_propiedad == 'Oficina') {
                    $hayOficina = 'Si';
                }elseif ($uso_propiedad== 'Local comercial') {
                    $hayLocal = 'Si';
                }elseif ($uso_propiedad== 'Departamento') {
                    $hayDepartamento = 'Si';
                }else{
                    $hayDepartamento = 'Si';
                }
            }
            ///Pues como estan moleste y moleste con las condiciones vamos a agregar una hoja si hay algun local, oficina o departamneto y cada una con sus condiiciones e.e
            if ($hayDepartamento == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Departamento')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Departamento')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Departamento')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                    }
                    $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$key->condicion.'</p>';
                }
                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA DEPARTAMENTO</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }
            if ($hayLocal == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                    }
                    $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$key->condicion.'</p>';
                }
                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA LOCAL COMERCIAL</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }
            if ($hayOficina == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Oficina')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Oficina')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Oficina')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                    }
                    $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$key->condicion.'</p>';
                }

                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA OFICINA</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }   
        
        ///// finalizar pdf
            $namePDF = uniqid().'_'.$nombrecliente.' cotizacion.pdf';
            //Close and output PDF document
            PDF::Output($namePDF, 'I');
    }
    public function generarpdf_ixuh($id, request $request)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $plazos = $cotizacion->plazos;
        
        $agente = DB::table('users')
        ->where('id','=',$cotizacion->asesor_id)
        ->first();
        
        ///agente
        $nombreagente = $agente->name;
        $correoagente = $agente->email;
        $moneda  = $cotizacion->moneda_id;
        $monedas = DB::table('moneda')
        ->select('siglas')
        ->where('id_moneda','=',$moneda)
        ->first();
        $siglas = $monedas->siglas;
        ////cliente
        $nombrecliente = $cotizacion->cliente;
        $correocliente = $cotizacion->correo;
        $telefonocliente  = $cotizacion->telefono;
        $fecha = $cotizacion->fecha_cotizacion;

        ///propiedad
        $proyecto = DB::table('proyecto')
        ->select('id_proyecto','nombre', 'portada_cotizacion','logo_cotizacion')
        ->where('nombre','=',$cotizacion->proyecto)
        ->first();

        ///// Empieza PDF
            PDF::SetAuthor('Ache desarrollos');
            PDF::SetTitle('Cotizacion IXUH');
            PDF::SetSubject($nombreagente);
            PDF::SetKeywords('Cotizacion, PDF, IXUH');

            // set header and footer fonts
            PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

            // set default monospaced font
            PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            PDF::SetHeaderMargin(0);
            PDF::SetFooterMargin(0);

            // remove default footer
            PDF::setPrintFooter(false);

            // set auto page breaks
            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                PDF::setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            PDF::SetFont('times', '', 48);
            // add a page
            PDF::AddPage();
            // -- set new background ---

            // get the current page break margin
            $bMargin = PDF::getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = PDF::getAutoPageBreak();
            // disable auto-page-break
            PDF::SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = $proyecto->portada_cotizacion;
            PDF::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            PDF::setPageMark();

            $detalles = DB::table('detalle_cotizacion_propiedad')
            ->where('cotizacion_id',$cotizacion->id_cotizacion)
            ->groupBy('propiedad_id')
            ->get();

            // $propiedades_request = $request->get('propiedad');
            // $nombres_propiedades = '';
            $hayOficina = 'No';
            $hayLocal = 'No';
            $hayDepartamento = 'No';
            foreach ($detalles as $key) {
                $propiedad = DB::table('propiedad as p')
                ->join('nivel as n','p.nivel_id','=','n.id_nivel','left',false)
                ->join('tipo_modelo as tm','p.tipo_modelo_id','=','tm.id_tipo_modelo','left',false)
                ->join('uso_propiedad as up','p.uso_propiedad_id','=','up.id_uso_propiedad','left',false)
                ->join('estatus_propiedad as ep','p.estatus_propiedad_id','=','ep.id_estatus_propiedad','left',false)
                ->select('id_propiedad','p.nombre','tm.tipo_modelo','n.nivel','n.plano as nivel_foto','ep.estatus_propiedad','precio','mts_interior','mts_exterior','mts_total','up.uso_propiedad')
                ->where('id_propiedad','=',$key->propiedad_id)
                ->first();

                $nombres_propiedades = $propiedad->nombre;
                $precio = $propiedad->precio;


                $imagen_propiedad = DB::table('imagen_propiedad')
                ->select('titulo','imagen_path')
                ->where('propiedad_id',$key->propiedad_id)
                ->get();
                $imagen_path = '';
                $nivel_path = '';
                foreach ($imagen_propiedad as $imgn) {
                    if (strpos($imgn->titulo, 'Info') !== false) {
                        $imagen_path = $imgn->imagen_path;
                    }
                    if (strpos($imgn->titulo, 'Nivel') !== false) {
                        $nivel_path = $imgn->imagen_path;
                    }
                }
                $nombrepropiedad = $propiedad->nombre;
                $nivel = $propiedad->nivel;
                $estatus_propiedad = $propiedad->estatus_propiedad;
                $uso_propiedad = $propiedad->uso_propiedad;
                $mts_total = $propiedad->mts_total;
                $mts_exterior = $propiedad->mts_exterior;
                $mts_interior = $propiedad->mts_interior;
                $tipo_modelo = $propiedad->tipo_modelo;

                $detalles_propiedades = DB::table('detalle_cotizacion_propiedad')
                ->where('cotizacion_id', $key->cotizacion_id)
                ->where('propiedad_id', $key->propiedad_id)
                ->get();

                $esquemas_html = '<div style="color: #150703;">
                    <table width="44%" border="0" cellspacing="0" cellpadding="0">';
                foreach ($detalles_propiedades as $a){
                    $esquemas_html = $esquemas_html.'
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;">'.$a->esquema_pago.'</td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>
                        <tr >
                        <td colspan="1" style="text-align:left; font-size:10; font-weight:normal;">% Descuento</td>
                        <td align="center" colspan="2" style="text-align:left; font-size:10; font-weight:normal;">'.number_format($a->porcentaje_descuento, 2 , "." , ",").'%</td>
                        </tr>
                        <tr>
                        <td colspan="1" style="text-align:left; font-size:10; font-weight:normal;">Total</td>
                        <td align="center" colspan="2" style="text-align:left; font-size:10; font-weight:normal;">$'.number_format($a->precio_final, 2 , "." , ",").'</td>
                        </tr>';
                    $detalle_rubros = DB::table('detalle_cotizacion_propiedad_rubro')
                    ->where('detalle_cotizacion_propiedad_id', $a->id_detalle_cotizacion_propiedad)
                    ->where('cotizacion_id', $a->cotizacion_id)
                    ->orderBy('fecha')
                    ->get();
                    $esquemas_rubro = '';
                    foreach ($detalle_rubros as $b) {
                        $esquemas_rubro = $esquemas_rubro. '<tr>
                            <td style="text-align:left; font-size:10; font-weight:normal;">'.$b->alias.'</td>
                            <td style="text-align:center; font-size:10; font-weight:normal;">'.number_format($b->porcentaje, 2 , "." , ",").'%</td>
                            <td style="text-align:right; font-size:10; font-weight:normal; background-color: #E3E3E3;">$'.number_format($b->monto, 2 , "." , ",").'</td>
                            </tr>';
                    }
                    $esquemas_html = $esquemas_html.$esquemas_rubro.'<tr>
                        <td style="text-align:left; font-size:10; font-weight:normal;">Descuento</td>
                        <td style="text-align:center; font-size:10; font-weight:normal;">'.number_format($a->porcentaje_descuento, 2 , "." , ",").'%</td>
                        <td style="text-align:right; font-size:10; font-weight:normal; background-color: #E3E3E3;">$'.number_format($a->monto_descuento, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td colspan="2" style="text-align:left; font-size:10; font-weight:bolder; background-color: #B4D6F5;">PRECIO FINAL</td>
                        <td style="text-align:right; font-size:10; font-weight:bolder; background-color: #B4D6F5;">$'.number_format($a->precio_final, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td colspan="3" style="text-align:left; font-size:11;"></td>
                        </tr>';
                }
                $esquemas_html = $esquemas_html. '</table>
                </div>';
                /*SEGUNDA PAGINA empieza el documento*/
                // add a page
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();

                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                $personalizado = '';

                $head_html = '<div style="color: #150703;">
                    <table width="44%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        <td>Fecha</td><td colspan="3" style="text-align:center; font-weight: normal;">'.$fecha.'</td>
                        </tr>
                        <tr>
                        <td >Cliente</td><td colspan="3"style="text-align:center; font-weight: normal;">'.$nombrecliente.'</td>
                        </tr>
                        <tr>
                        <td>No. de Departamento</td><td style="text-align:center; font-weight: normal;">'.$nombrepropiedad.'</td>
                        <td>Plazos</td><td style="text-align:center; font-weight: normal;">'.$plazos.'</td>
                        </tr>
                        <tr>
                        <td>Nivel</td><td style="text-align:center; font-weight: normal;">'.$nivel.'</td>
                        <td>Precio</td><td style="text-align:center; font-weight: normal;">$'.number_format($precio, 2 , "." , ",").'</td>
                        </tr>
                        <tr>
                        <td>Tipo Departamento</td><td style="text-align:center; font-weight: normal;">'.$tipo_modelo.'</td>
                        <td>Estatus</td><td style="text-align:center; font-weight: normal;">'.$estatus_propiedad.'</td>
                        </tr>
                        <tr>
                        <td>M2</td><td style="text-align:center; font-weight: normal;">'.$mts_total.'</td>
                        <td></td><td></td>
                        </tr>
                        <tr>
                        <td>m2 Interior</td><td style="text-align:center; font-weight: normal;">'.$mts_interior.'</td>
                        <td></td><td></td>
                        </tr>
                        <tr>
                        <td>m2 Exterior</td><td style="text-align:center; font-weight: normal;">'.$mts_exterior.'</td>
                        <td></td><td></td>
                        </tr>
                    </table>
                </div>';
                $body_html = '<div style="color: #150703;">
                    <table width="44%" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                        <td colspan="4" style="text-align:center; font-size:14;">ESQUEMAS DE PAGO</td>
                        </tr>
                    </table>
                </div>';
                
                //PDF::writeHTML($html, true, false, true, false, '');
                PDF::SetTextColor(0, 0, 0, 0); 
                PDF::SetFont('helveticaB', '', 10);
                PDF::writeHTMLCell(400, 15, 15, 30 , $head_html, 0, 0, 0, false, 'L', false);
                PDF::writeHTMLCell(400, 15, 15, 68 , $body_html, 0, 0, 0, false, 'L', false);
                PDF::writeHTMLCell(300, 15, 35, 75 , $esquemas_html, 0, 0, 0, false, 'L', false);
                
                //agregando las fotos
                /*tercer pagina*/
                $encontrofotos = 'NO';
                if (($nivel_path != null and $nivel_path != '') or ($imagen_path != null and $imagen_path != '')) {
                    $encontrofotos = 'SI';
                }else{
                    $encontrofotos = 'NO';
                }
                if ($encontrofotos == 'SI') {
                    PDF::AddPage();
                    // get the current page break margin
                    $bMargin = PDF::getBreakMargin();
                    // get current auto-page-break mode
                    $auto_page_break = PDF::getAutoPageBreak();
                    // disable auto-page-break
                    PDF::SetAutoPageBreak(false, 0);
                    // restore auto-page-break status
                    PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                    // set the starting point for the page content
                    PDF::setPageMark();
                    // Logo
                    $logo_file = $proyecto->logo_cotizacion;
                    /*Logo*/
                    PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                    if ($nivel_path != null and $nivel_path != '') {
                        
                        PDF::Image($nivel_path, '25', '30', 160, 120, '', '', '', false, 500, '', false, false, 2, false, false, false);
                    }
                    if ($imagen_path != null and $imagen_path != '') {
                        PDF::Image($imagen_path, '25', '150', 160, 90, '', '', '', false, 500, '', false, false, 2, false, false, false);
                    }
                }

                if ($uso_propiedad == 'Oficina') {
                    $hayOficina = 'Si';
                }elseif ($uso_propiedad== 'Local comercial') {
                    $hayLocal = 'Si';
                }elseif ($uso_propiedad== 'Departamento') {
                    $hayDepartamento = 'Si';
                }else{
                    $hayDepartamento = 'Si';
                }
            }
            ///Pues como estan moleste y moleste con las condiciones vamos a agregar una hoja si hay algun local, oficina o departamneto y cada una con sus condiiciones e.e
            if ($hayDepartamento == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Departamento')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Departamento')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados_sub = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Departamento')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->groupBy('subtitulo')
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados_sub as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                        $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                        ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                        ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                        ->where('u.uso_propiedad', 'Departamento')
                        ->whereIn('cd.tipo',['Agregado'])
                        ->where('proyecto_id', $cotizacion->proyecto_id)
                        ->where('cd.subtitulo', $key->subtitulo)
                        ->orderby('cd.condicion')
                        ->get();
                        foreach ($condiciones_entrega_agregados as $agg) {
                            $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$agg->condicion.'</p>';
                        }
                    }
                }
                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA DEPARTAMENTO</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }
            if ($hayLocal == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Local comercial')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                    }
                    $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$key->condicion.'</p>';
                }
                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA LOCAL COMERCIAL</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }
            if ($hayOficina == 'Si') {
                /*cuarta pagina CONDICIONES*/
                PDF::AddPage();
                // get the current page break margin
                $bMargin = PDF::getBreakMargin();
                // get current auto-page-break mode
                $auto_page_break = PDF::getAutoPageBreak();
                // disable auto-page-break
                PDF::SetAutoPageBreak(false, 0);
                // restore auto-page-break status
                PDF::SetAutoPageBreak($auto_page_break, $bMargin);
                // set the starting point for the page content
                PDF::setPageMark();
                // Logo
                $logo_file = $proyecto->logo_cotizacion;
                /*Logo*/
                PDF::Image($logo_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $condiciones_entrega_bullet = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Oficina')
                ->where('cd.tipo','Bullet')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $li_condiciones ='';
                foreach ($condiciones_entrega_bullet as $key) {
                    $li_condiciones = $li_condiciones.'<li>'.$key->condicion.'</li>';
                }
                $condiciones_entrega_nota = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo')
                ->where('u.uso_propiedad', 'Oficina')
                ->where('cd.tipo','Nota')
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones = '';
                foreach ($condiciones_entrega_nota as $key) {
                    $p_condiciones = $p_condiciones.'<p style="font-weight:normal; font-size:11pt; text-align:left;">** '.$key->condicion.'</p>';
                }
                $condiciones_entrega_agregados = DB::table('condicion_entrega_detalle as cd')
                ->join('uso_propiedad as u','cd.uso_propiedad_id','=','u.id_uso_propiedad','left',false)
                ->select('cd.id_condicion_entrega_detalle','cd.condicion','cd.tipo','cd.subtitulo')
                ->where('u.uso_propiedad', 'Oficina')
                ->whereIn('cd.tipo',['Agregado'])
                ->where('proyecto_id', $cotizacion->proyecto_id)
                ->get();
                $p_condiciones_a = '';
                foreach ($condiciones_entrega_agregados as $key) {
                    if($key->subtitulo != '' and $key->subtitulo != null){
                        $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:bold;">'.$key->subtitulo.'</p>';
                    }
                    $p_condiciones_a = $p_condiciones_a.'<p style="font-weight:normal;">'.$key->condicion.'</p>';
                }

                $tercerpagina_html = '<div style="color: #150703;">
                    <p style="font-weight:bolder; font-size:18pt; text-align:center;">CONDICIONES DE ENTREGA OFICINA</p>
                    <p></p>
                    <ol style="font-weight:normal; font-size:12pt;">'.$li_condiciones.'</ol>'.$p_condiciones_a.$p_condiciones.'</div>';
                PDF::writeHTMLCell(170, 15, 20, 30 , $tercerpagina_html, 0, 0, 0, false, 'L', false);

            }   
        
        ///// finalizar pdf
            $namePDF = uniqid().'_'.$nombrecliente.' cotizacion.pdf';
            //Close and output PDF document
            PDF::Output($namePDF, 'I');
    }
    public function todas(Request $request){
        /*paginacion*/
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }

        $cliente_bs = $request->get('cliente_bs');
        $propiedad_bs = $request->get('propiedad_bs');
        $fecha_bs = $request->get('fecha_bs');
        $estatus_bs = $request->get('estatus_bs');

        $filtro = "c.estatus is not NULL";
        if ($cliente_bs) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro.' c.cliente LIKE "%'.$cliente_bs.'%"';
        }
        if ($propiedad_bs) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro.' c.propiedad LIKE "%'.$propiedad_bs.'%"';
        }
        if ($fecha_bs) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro." c.fecha_cotizacion = '".$fecha_bs."'";
        }
        if ($estatus_bs != 'Vacio' and $estatus_bs != '' and $estatus_bs != null) {
            if ($filtro != '') {
                $filtro = $filtro . ' AND ';
            }
            $filtro = $filtro." c.estatus = '".$estatus_bs."'";
        }
        $cotizaciones = DB::table('cotizacion as c')
        ->join('moneda as m','c.moneda_id','=','m.id_moneda','left',false)
        ->join('users as u','c.asesor_id','=','u.id','left',false)
        ->select('id_cotizacion','fecha_cotizacion','proyecto','propiedad','cliente','correo','telefono','asesor_id','moneda_id','c.estatus', 'u.name as asesor','m.siglas as moneda')
        ->whereRaw($filtro)
        ->orderBy('id_cotizacion','DESC')
        ->get();

        $resultados = DB::table('cotizacion as c')
        ->join('moneda as m','c.moneda_id','=','m.id_moneda','left',false)
        ->join('users as u','c.asesor_id','=','u.id','left',false)
        ->select('c.estatus as label', DB::raw('count(*) as cantidad'))
        ->orderBy('id_cotizacion','DESC')
        ->whereRaw($filtro)
        ->groupBy('c.estatus')
        ->get();

        $estatus = array('Abierta','Cerrada');
        return view('cotizacion.todas',compact('estatus','cotizaciones', 'request','resultados','rows_pagina'));

    }
    public function cerrar(Request $request, $id){
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->estatus = 'Cerrada';
        $cotizacion->fecha_cierre = $request->get('fecha_cierre');
        $cotizacion->update();
        return back();
    }
    public function abierta($id){
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->estatus = 'Abierta';
        $cotizacion->update();
        return back();
    }
    public function destroy($id){
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();
        return back();
    }

}
