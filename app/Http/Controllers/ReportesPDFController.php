<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\PlazosPago;
use App\Prospecto;
use App\Pagos;
use App\Propiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
use PDF;
use App\Exports\ReportesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportesPDFController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function cuenta_cobrar_pdf(request $request)
    {
        $fecha_inicio = $request->get('fecha_inicio_pdf');
        $fecha_final = $request->get('fecha_final_pdf');
        $propiedad_bs = $request->get('propiedad_pdf');
        $proyecto_bs =$request->get('proyecto_pdf');

        if ($fecha_inicio != null and $fecha_final != null) {
            if ($proyecto_bs != 'Vacio' and $propiedad_bs != 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->get();
            }elseif ($proyecto_bs != 'Vacio' and $propiedad_bs == 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.proyecto_id','=',$proyecto_bs)
                ->get();
            }elseif ($propiedad_bs != 'Vacio' and $proyecto_bs == 'Vacio') {
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->where('p.propiedad_id','=',$propiedad_bs)
                ->get();
            }else{
                $resultados = DB::table('plazos_pago as pp')
                ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
                ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
                ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
                ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
                ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
                ->where('pp.fecha','>=', $fecha_inicio)
                ->where('pp.fecha','<=', $fecha_final)
                ->get();
            }
        }else{
            $resultados = DB::table('plazos_pago as pp')
            ->join('prospectos as p','prospecto_id','=','id_prospecto','left',false)
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->join('pagos as pz','pz.plazo_pago_id','=','pp.id_plazo_pago','left',false)
            ->select('id_plazo_pago','prospecto_id', 'pp.fecha','pp.estatus','num_plazo','total','pp.saldo','pp.pagado','monto_mora','p.nombre as cliente','pp.interes_acumulado','pp.interes','pp.total_acumulado','pp.capital_acumulado','py.nombre as proyecto','prop.nombre as propiedad','pz.fecha as fecha_pago',DB::raw('MONTH(pp.fecha) as mes'))
            ->get();
        }
        $sumasaldo = 0;
        $sumapagado = 0;
        foreach ($resultados as $key) {
            $sumasaldo = $sumasaldo + $key->saldo;
            $sumapagado = $sumapagado + $key->pagado;
        }
        $htmlencabezado = '<div style="color: #000;" align="center">
        <h3>Cuentas por cobrar</h3><p align="left" style="color: #000; font-weight:normal;">De '.$fecha_inicio.' a '.$fecha_final.'</p></div> ';
        $htmltable ='';
        $htmltable = $htmltable. '
            <div style="color: #000;" align="center">
            <table id="dataExport" cellspacing="0" cellpadding="1" >
                <tr style="background-color: #BEDAEA;  font-size:10pt;">
                    <th align="center" width="180" border="1" >
                      Proyecto
                    </th>
                    <th align="center" width="120" border="1" >
                      Propiedad
                    </th>
                    <th align="center" border="1" width="100">
                      Cliente
                    </th>
                    <th align="center" border="1" >
                      Mes
                    </th>
                    <th align="center" border="1" >
                      Pagado
                    </th>
                    <th align="center" border="1" >
                      F. Pago
                    </th>
                    <th align="center" border="1" >
                      Saldo
                    </th>
                </tr>';
            foreach ($resultados as $r) {
              if ($r->mes=='01') {$mesLetra='Ene';}
              if ($r->mes=='02') {$mesLetra='Feb';}
              if ($r->mes=='03') {$mesLetra='Mar';}
              if ($r->mes=='04') {$mesLetra='Abr';}
              if ($r->mes=='05') {$mesLetra='May';}
              if ($r->mes=='06') {$mesLetra='Jun';}
              if ($r->mes=='07') {$mesLetra='Jul';}
              if ($r->mes=='08') {$mesLetra='Ago';}
              if ($r->mes=='09') {$mesLetra='Sep';}
              if ($r->mes=='10') {$mesLetra='Oct';}
              if ($r->mes=='11') {$mesLetra='Nov';}
              if ($r->mes=='12') {$mesLetra='Dic';}
              if ($r->fecha_pago != null){
                $fecha = date('Y-m-d',strtotime($r->fecha_pago));
              }else{
                $fecha = '';
              }
              $htmltable = $htmltable .'<tr>
                  <td align="center" style="font-weight:normal; font-size:8pt;" width="180">'.
                     $r->proyecto .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;" width="120">'.
                     $r->propiedad .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;" width="100">'.
                     $r->cliente .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;">'.
                    $mesLetra .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;">'.
                    number_format($r->pagado , 2 , "." , ",") .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;">'.
                     $fecha .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:8pt;">'.
                     number_format($r->saldo , 2 , "." , ",") .'
                  </td>
                </tr>';
            }
            $htmltable = $htmltable . 
              '
                <tr>
                  <th align="center">
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">'.
                    number_format($sumapagado , 2 , "." , ",") .'
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">'.
                    number_format($sumasaldo , 2 , "." , ",") .'
                  </th>
                </tr>
            </table>
          </div>';

            PDF::SetAuthor('Nextapp');
            PDF::SetTitle('Reporte cuenta por cobrar');
            PDF::SetSubject('Cobranza');
            PDF::SetKeywords('Reporte cuenta por cobrar, PDF');

            
            PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

            PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            PDF::SetHeaderMargin(0);
            PDF::SetFooterMargin(0);

            PDF::setPrintFooter(false);

            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                PDF::setLanguageArray($l);
            }

            PDF::SetFont('times', '', 48);

            PDF::AddPage();

            $bMargin = PDF::getBreakMargin();
            $auto_page_break = PDF::getAutoPageBreak();
            PDF::SetAutoPageBreak(false, 0);
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            PDF::setPageMark();
            PDF::SetTextColor(0, 0, 0, 0);
            PDF::SetFont('helveticaB', '', 12);
            PDF::writeHTMLCell(180, 15, 15, 10 , $htmlencabezado, 0, 0, 0, false, 'L', false);
            PDF::SetFont('helveticaB', '', 11);
            PDF::writeHTMLCell(150, 15, 3, 40 , $htmltable, 0, 0, 0, false, 'L', false);

            /////finalizar pdf
            //echo $htmltable;
            $namePDF = uniqid().'_cuentaporcobrar.pdf';
            //Close and output PDF document
            PDF::Output($namePDF, 'I');
        //return view('reportes.cuenta_cobrar.index',['resultados'=>$resultados,'prospectos'=>$prospectos,'proyectos'=>$proyectos,'propiedades'=>$propiedades,'request'=>$request,'sumasaldo'=>$sumasaldo,'sumatotal'=>$sumatotal]);
    }
    public function semaforo_pdf(request $request)
    {
        $propiedad_bs = $request->get('propiedad_pdf');
        $proyecto_bs =$request->get('proyecto_pdf');

        if ($proyecto_bs != 'Vacio' and $propiedad_bs != 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.proyecto_id','=',$proyecto_bs)
            ->where('p.propiedad_id','=',$propiedad_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }elseif ($proyecto_bs != 'Vacio' and $propiedad_bs == 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.proyecto_id','=',$proyecto_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }elseif ($propiedad_bs != 'Vacio' and $proyecto_bs == 'Vacio') {
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.propiedad_id','=',$propiedad_bs)
            ->where('p.estatus','>=',6)
            ->get();
        }else{
            $resultados = DB::table('prospectos as p')
            ->join('proyecto as py','p.proyecto_id','=','py.id_proyecto','left',false)
            ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
            ->select('id_prospecto', 'p.fecha_registro','p.estatus', 'p.monto_venta','p.mensualidad','p.capital','p.interes','p.pagado','py.nombre as proyecto','prop.nombre as propiedad','p.nombre as cliente','p.saldo')
            ->where('p.estatus','>=',6)
            ->get();
        }

        $sumasaldo = 0;
        $sumapagado = 0;
        foreach ($resultados as $key) {
            $sumasaldo = $sumasaldo + $key->saldo;
            $sumapagado = $sumapagado + $key->pagado;
        }
        $htmlencabezado = '<div style="color: #000;" align="center">
        <h3>Reprote de semaforo</h3><p align="left" style="color: #000; font-weight:normal;">Dia '.date('Y-m-d').'</p></div> ';
        $htmltable ='';
        $htmltable = $htmltable. '
            <div style="color: #000;" align="center">
            <table id="dataExport" cellspacing="0" cellpadding="1" >
                <tr style="background-color: #BEDAEA;">
                    <th align="center" width="180" border="1" >
                      Proyecto
                    </th>
                    <th align="center" width="120" border="1" >
                      Propiedad
                    </th>
                    <th align="center" border="1" >
                      Cliente
                    </th>
                    <th align="center" border="1" >
                       pagado
                    </th>
                    <th align="center" border="1" >
                      Saldo
                    </th>
                </tr>';
            foreach ($resultados as $r) {
                if ($r->saldo <= 0.5) {
                    $fondo = '#82F787';
                }else{
                    $fondo = '#F78B82';
                }
                $htmltable = $htmltable .'<tr>
                  <td align="center" style="font-weight:normal; font-size:9pt;" width="180">'.
                     $r->proyecto .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:9pt;" width="120">'.
                     $r->propiedad .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:9pt;">'.
                     $r->cliente .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:9pt;">'.
                     number_format($r->pagado , 2 , "." , ",") .'
                  </td>
                  <td align="center" style="font-weight:normal; font-size:9pt; background-color: '.$fondo.'; color: #fff;">'.
                     number_format($r->saldo , 2 , "." , ",") .'
                  </td>
                </tr>';
            }
            $htmltable = $htmltable . 
              '
                <tr>
                  <th align="center">
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">
                  </th>
                  <th align="center">'.
                    number_format($sumapagado , 2 , "." , ",") .'
                  </th>
                  <th align="center">'.
                    number_format($sumasaldo , 2 , "." , ",") .'
                  </th>
                </tr>
            </table>
          </div>';

            PDF::SetAuthor('Nextapp');
            PDF::SetTitle('Reporte semaforo');
            PDF::SetSubject('Cobranza');
            PDF::SetKeywords('reporte de semaforo, prospectos, PDF');

            
            PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

            PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            PDF::SetHeaderMargin(0);
            PDF::SetFooterMargin(0);

            PDF::setPrintFooter(false);

            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                PDF::setLanguageArray($l);
            }

            PDF::SetFont('times', '', 48);

            PDF::AddPage();

            $bMargin = PDF::getBreakMargin();
            $auto_page_break = PDF::getAutoPageBreak();
            PDF::SetAutoPageBreak(false, 0);
            PDF::SetAutoPageBreak($auto_page_break, $bMargin);
            PDF::setPageMark();
            PDF::SetTextColor(0, 0, 0, 0);
            PDF::SetFont('helveticaB', '', 12);
            PDF::writeHTMLCell(180, 15, 15, 10 , $htmlencabezado, 0, 0, 0, false, 'L', false);
            PDF::SetFont('helveticaB', '', 11);
            PDF::writeHTMLCell(150, 15, 15, 40 , $htmltable, 0, 0, 0, false, 'L', false);

            /////finalizar pdf
            //echo $htmltable;
            $namePDF = uniqid().'_semaforo_tbi.pdf';
            //Close and output PDF document
            PDF::Output($namePDF, 'I');
    }
    public function enganche_estimado_pdf(request $request){
      $proyecto_bs =$request->get('proyecto_pdf');
      $fecha_minima_bs = $request->get('fecha_minima_pdf');
      $fecha_maxima_bs = $request->get('fecha_maxima_pdf');

      if ($proyecto_bs == 'Vacio' or $proyecto_bs == '') {
          $primer_proyecto = DB::table('proyecto as p')
          ->select('p.id_proyecto')
          ->first();
          $proyecto_bs = $primer_proyecto->id_proyecto;
      }
      $proyecto_data = DB::table('proyecto as p')
      ->select('p.id_proyecto','p.nombre as nombre_proyecto','p.metros_construccion','p.metros_terreno')
      ->where('id_proyecto',$proyecto_bs)
      ->first();
      $resultados = DB::table('prospectos as p')
      ->join('plazos_pago as pp','p.id_prospecto','=','pp.prospecto_id','left', false)
      ->select('p.nombre as prospecto','p.monto_venta','p.porcentaje_enganche','p.monto_enganche','pp.pagado','pp.estatus','pp.total')
      ->where('p.proyecto_id',$proyecto_bs)
      ->where('pp.num_plazo',1)
      ->where('p.porcentaje_enganche','>',0)
      ->whereBetween('pp.fecha', [$fecha_minima_bs, $fecha_maxima_bs])
      ->get();

      $total_enganche = 0;
      $total_pagado = 0;
      foreach ($resultados as $key) {
           $total_enganche = $total_enganche + $key->total;
           $total_pagado = $total_pagado + $key->pagado;
      } 
      $htmlencabezado = '<div style="color: #000;" align="center">
        <h3>Reporte Enganches a Recibir</h3><p align="left" style="color: #000; font-weight:normal;">Dia '.date('Y-m-d').'</p><hr></div> ';
      $tabla = '<div style="color: #000;"><table class="table">
                <thead class="">
                <tr>
                  <th></th>
                  <th class="center" style="font-weight:bold;">Total estimado</th>
                  <th class="center" style="font-weight:bold;">Recibido</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td style="font-weight:bold;">Resumen</td>
                  <td class="center">$ '.number_format($total_enganche , 2 , "." , ",").'</td>
                  <td class="center">$ '.number_format($total_pagado , 2 , "." , ",").'</td>
                </tr>
                </tbody>
            </table><br><br><p style="font-weight:bold; font-size:10pt;">Proyecto '.$proyecto_data->nombre_proyecto.'</p><table id="tblData" border="0">
                <thead class="">
                    <tr>
                      <th style="font-weight:bold;">Prospecto</th>
                      <th width="250" style="font-weight:bold;">Enganche estimado</th>
                      <th class="center" style="font-weight:bold;">Recibido</th>
                      <th class="center" style="font-weight:bold;">Estatus</th>
                    </tr>
                </thead>
                <tbody>';
      foreach ($resultados as $ele){
        $tabla = $tabla.'<tr>
          <td>'.$ele->prospecto.'</td>
          <td width="250">$ '.number_format($ele->total , 2 , "." , ",").'</td>
          <td class="center">$ '.number_format($ele->pagado , 2 , "." , ",").'</td>
          <td class="center">'.$ele->estatus.'</td>
        </tr>';
      }
     $tabla = $tabla.'</tbody>
                <tfoot>
                    <tr>
                      <td style="font-weight:bold;">Total </td>
                      <td width="250">$ '.number_format($total_enganche , 2 , "." , ",").'</td>
                      <td class="center">$ '.number_format($total_pagado , 2 , "." , ",").'</td>
                    </tr>
                </tfoot>
            </table></div>';
      PDF::SetAuthor('Nextapp');
      PDF::SetTitle('Reporte Enganche a recibir');
      PDF::SetSubject('Cobranza');
      PDF::SetKeywords('reporte de semaforo, prospectos, PDF');

      
      PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

      PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      PDF::SetHeaderMargin(0);
      PDF::SetFooterMargin(0);

      PDF::setPrintFooter(false);

      PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          PDF::setLanguageArray($l);
      }

      PDF::SetFont('times', '', 48);

      PDF::AddPage();

      $bMargin = PDF::getBreakMargin();
      $auto_page_break = PDF::getAutoPageBreak();
      PDF::SetAutoPageBreak(false, 0);
      PDF::SetAutoPageBreak($auto_page_break, $bMargin);
      PDF::setPageMark();
      PDF::SetTextColor(0, 0, 0, 0);
      PDF::SetFont('helveticaB', '', 10);
      PDF::writeHTMLCell(180, 15, 15, 10 , $htmlencabezado, 0, 0, 0, false, 'L', false);
      PDF::SetFont('helvetica', '', 9);
      PDF::writeHTMLCell(150, 15, 15, 40 , $tabla, 0, 0, 0, false, 'L', false);

      /////finalizar pdf
      //echo $htmltable;
      $namePDF = uniqid().'_enganche a recibir.pdf';
      //Close and output PDF document
      PDF::Output($namePDF, 'I');
    }
    public function exportar_ventas(request $request)
    {
      $type_export = $request->get('type_export');
      $proyecto_bs = $request->get('proyecto_bs');
      $yearMes = $request->get('mes_bs').'-'.$request->get('year_bs');
      $mes_bs = $request->get('mes_bs');
      $year_bs = $request->get('year_bs');
      $q_bs = $request->get('q_bs');
      /*Filtros*/
      $seccion_bs = $request->get('seccion_bs');

      if ($year_bs == '') {
          $year_bs = date('Y'); 
      }

      $filtro = '';
      $filtroseccion = '';
      $filtromes = '';
      $filtrosublote = '';
      if ($seccion_bs != '' and $seccion_bs != null) {
          $filtroseccion = ' AND n.nivel LIKE "%'.$seccion_bs.'%"';
      }
      if ($mes_bs != '' and $mes_bs != null) {
          $filtromes = ' AND MONTH(p.fecha_venta) = '.$mes_bs;
      }
      if ($q_bs != '' and $q_bs != null) {
          $filtromes = ' AND MONTH(p.fecha_venta) IN ('.$q_bs.') ';
      }
      $filtro = $filtro . ' year(p.fecha_venta) = '.$year_bs.$filtroseccion.$filtromes;
      

      if ($filtro != '') {
          $body_tabla = '<thead class="bg-dark text-white">
                  <tr>
                    <th>Fecha venta</th>
                    <th>Propiedad</th>
                    <th>Seccion</th>
                    <th width="30%">Cliente</th>
                    <th>$ Venta</th>
                  </tr>
                </thead><tbody>';
          $prospectos_venta = DB::table('prospectos as p')
          ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad','left',false)
          ->join('nivel as n','prop.nivel_id','=','n.id_nivel','left',false)
          ->join('users as u','p.asesor_id','=','u.id','left',false)
          ->select('p.id_prospecto', 'p.nombre as nombre_prospecto','p.fecha_venta','p.propiedad_id','p.nivel_id','p.monto_venta', 'n.nivel as seccion','prop.nombre as nombre_propiedad','p.fecha_apartado','p.fecha_registro','p.fecha_enganche','p.monto_apartado','p.monto_enganche','p.telefono','p.correo','p.mensualidad','p.num_plazos','u.name as nombre_asesor')
          ->where('p.fecha_venta','!=',null)
          ->where('p.monto_venta','!=',null)
          ->whereRaw($filtro)
          ->get();
          $venta_acumulada = 0;
          foreach ($prospectos_venta as $key) {
            $body_tabla = $body_tabla . '<tr ><td>'.date('Y-M-d',strtotime($key->fecha_venta)).'</td><td>'.$key->nombre_propiedad.'</td><td>'.$key->seccion.'</td><td><a href="#" data-toggle="modal" data-target="#modal_prospecto_'.$key->id_prospecto.'">'.$key->nombre_prospecto.'</a></td><td>$'.number_format($key->monto_venta , 2 , "." , ",").'</td></tr><div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_prospecto_'.$key->id_prospecto.'"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">'.$key->nombre_prospecto.'</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p>Mas informacion acerca de este prospecto</p><ul><li><b>Fecha apartado: </b>'.date('Y-M-d',strtotime($key->fecha_apartado)).'</li><li><b>Monto apartado: </b>$'.number_format($key->monto_apartado, 2 , "." , ",").'</li><li><b>Fecha enganche: </b>'.date('Y-M-d',strtotime($key->fecha_enganche)).'</li><li><b>Monto enganche: </b>$'.number_format($key->monto_enganche, 2 , "." , ",").'</li><li><b>Monto venta: </b>$'.number_format($key->monto_venta, 2 , "." , ",").'</li><li><b>Asesor: </b>'.$key->nombre_asesor.'</li><li><b>Mensualidad: </b>$'.number_format($key->mensualidad, 2 , "." , ",").'</li><li><b>Plazos: </b>'.$key->num_plazos.'</li><li><b>Telefono: </b>'.$key->telefono.'</li><li><b>Correo: </b>'.$key->correo.'</li><li><b>Propiedad: </b>'.$key->nombre_propiedad.'</li><li><b>Fecha venta: </b>'.date('Y-M-d',strtotime($key->fecha_venta)).'</li><li><b>Seccion: </b>'.$key->seccion.'</li></ul></div><div class="modal-footer">    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>    <button type="submit" class="btn btn-info">Confirmar</button></div></div></div></div>';

              $venta_acumulada = $venta_acumulada + $key->monto_venta;
          }
          $body_tabla = $body_tabla.'</tbody><tfoot><tr style="font-weight: bold;"><td colspan ="4" >Total</td><td>$'.number_format($venta_acumulada , 2 , "." , ",").'</td></tr></tfoot>';

      }
      if ($type_export == 'PDF') {
        ////// PDF
        PDF::SetAuthor('Nextapp');
        PDF::SetTitle('Reporte ventas');
        PDF::SetSubject('Pagos');
        PDF::SetKeywords('reporte de ventas, prospectos, PDF');

        
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        PDF::setPrintFooter(false);

        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        PDF::SetFont('times', '', 48);

        PDF::AddPage();

        $bMargin = PDF::getBreakMargin();
        $auto_page_break = PDF::getAutoPageBreak();
        PDF::SetAutoPageBreak(false, 0);
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        PDF::setPageMark();
        PDF::SetTextColor(0, 0, 0, 0);
        PDF::SetFont('helveticaB', '', 11);
        PDF::writeHTMLCell(150, 15, 15, 40 , $body_tabla, 0, 0, 0, false, 'L', false);

        /////finalizar pdf
        //echo $htmltable;
        $namePDF = uniqid().'_reporte_ventas.pdf';
        //Close and output PDF document
        PDF::Output($namePDF, 'I');
      
      }elseif ($type_export == 'EXCEL'){
        ob_end_clean();

        return Excel::download(new ReportesExport("exports.reporte", $body_tabla),'Reporte ventas.xlsx');
      } 
    }
    public function exportar_pagos(request $request)
    {
      $type_export = $request->get('type_export');
      $proyecto_bs = $request->get('proyecto_export');
      $year_bs = $request->get('year_export');
      $estatus_bs = $request->get('estatus_export');
      $estatus_oportunidad_bs = $request->get('estatus_oportunidad_export');
      $nombre_bs = $request->get('nombre_export');
      if ($year_bs == '' or $year_bs == null) {
          $year_bs = date('Y');
      }
      $filtro = 'p.fecha_venta is not null';
      $filtro_plazos = 'pp.estatus != "Inactivo"';
      
      if ($nombre_bs) {
          if ($filtro != '') {
            $filtro = $filtro . ' AND ';
          }
          $filtro = $filtro . ' p.id_prospecto IN (';
          $i =0;
          foreach ($nombre_bs as $key) {
              if ($i == 0) {
                  $filtro = $filtro."'".$key."'";
              }else{
                  $filtro = $filtro.",'".$key."'";
              }
              $i++;
          }
          $filtro = $filtro .')';
      }
      if ($estatus_oportunidad_bs != '' and $estatus_oportunidad_bs != null and $estatus_oportunidad_bs != 'all') {
          if ($filtro != '') {
              $filtro = $filtro . ' AND ';
          }
          $filtro = $filtro . ' p.estatus IN (';
          $i =0;
          foreach ($estatus_oportunidad_bs as $key) {
              if ($i == 0) {
                  $filtro = $filtro."'".$key."'";
              }else{
                  $filtro = $filtro.",'".$key."'";
              }
              $i++;
          }
          $filtro = $filtro .')';
      }
      if ($year_bs != '' and $year_bs != null) {
          if ($filtro_plazos != '') {
              $filtro_plazos = $filtro_plazos . ' AND ';
          }
          $filtro_plazos = $filtro_plazos . ' year(pp.fecha) = '.$year_bs;
      }
      if ($estatus_bs != '' and $estatus_bs != null and $estatus_bs != 'all') {
          if ($filtro_plazos != '') {
              $filtro_plazos = $filtro_plazos . ' AND ';
          }
          $filtro_plazos = $filtro_plazos . ' pp.estatus IN (';
          $i =0;
          foreach ($estatus_bs as $key) {
              if ($i == 0) {
                  $filtro_plazos = $filtro_plazos."'".$key."'";
              }else{
                  $filtro_plazos = $filtro_plazos.",'".$key."'";
              }
              $i++;
          }
          $filtro_plazos = $filtro_plazos .')';
      }
      $tabla = '';
      $meses= array(['1','Enero',0],['2','Febrero',0],['3','Marzo',0],['4','Abril',0],['5','Mayo',0],['6','Junio',0],['7','Julio',0],['8','Agosto',0],['9','Septiembre',0],['10','Octubre',0],['11','Noviembre',0],['12','Diciembre',0]);

      $oportunidades = DB::table('prospectos as p')
      ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left', false)
      ->select('p.id_prospecto','p.nombre as nombre_prospecto')
      ->whereRaw($filtro)
      ->orderby('p.nombre','ASC')
      ->get();
      $tabla = $tabla.'<tr ><th valign="middle" rowspan="2">Nombre</th><th colspan="13">Importe</th></tr><tr style="background-color: #454545; color: #FFF;" align="center">';
      foreach ($meses as $m) {
          $tabla = $tabla.'<th>'.$year_bs.'/'.$m[0].'</th>';
      } 
      $tabla = $tabla.'<th>Total</th></tr>';
      $total_acumulado = 0;
      foreach ($oportunidades as $key) {
          $tabla = $tabla . '<tr><td>'.$key->nombre_prospecto.'</td>';
          $total_por_cliente = 0;
          foreach ($meses as $m) {
              $plazos_pagos = DB::table('plazos_pago as pp')
              ->select('pp.id_plazo_pago','pp.saldo','pp.total', DB::raw('sum(pp.saldo) as total_plazo_mes'))
              ->whereRaw($filtro_plazos)
              ->whereYear('pp.fecha','=',$year_bs)
              ->whereMonth('pp.fecha','=',$m[0])
              ->where('pp.prospecto_id','=',$key->id_prospecto)
              ->first();
              if ($plazos_pagos) {
                  $tabla = $tabla . '<td>$'.number_format($plazos_pagos->total_plazo_mes, 2 , "." , ",").'</td>';
                  $total_por_cliente = $total_por_cliente + $plazos_pagos->total_plazo_mes;
              }else{
                  $tabla = $tabla . '<td>$'.number_format(0, 2 , "." , ",").'</td>';
                  $total_por_cliente = $total_por_cliente + 0;
              }
          }
          $tabla = $tabla . '<td style="font-weight: bold;">$'.number_format($total_por_cliente, 2 , "." , ",").'</td></tr>';
          $total_acumulado = $total_acumulado + $total_por_cliente;
      }
      $tabla = $tabla . '<tr style="font-weight: bold;"><td>Total</td>';
      foreach ($meses as $m) {
          $plazos_mes = DB::table('plazos_pago as pp')
          ->join('prospectos as p','pp.prospecto_id','=','p.id_prospecto')
          ->select(DB::raw('sum(pp.saldo) as suma_saldo, sum(pp.total) as suma_total'))
          ->whereRaw($filtro_plazos)
          ->whereNotIn('p.estatus',[1,2,3,5,6,11]) ///no sea lead, prospecto, contrato,postergado,perdido, inactivo
          ->whereYear('pp.fecha','=',$year_bs)
          ->whereMonth('pp.fecha','=',$m[0])
          ->first();
          if ($plazos_mes) {
              $tabla = $tabla .'<td>$'.number_format($plazos_mes->suma_saldo, 2 , "." , ",").'</td>';
          }else{
              $tabla = $tabla .'<td>$'.number_format(0, 2 , "." , ",").'</td>';

          }
      }
      $tabla = $tabla . '<td>$'.number_format($total_acumulado, 2 , "." , ",").'</td></tr>';
      if ($type_export == 'PDF') {
        ////// PDF
        PDF::SetAuthor('Nextapp');
        PDF::SetTitle('Reporte Pagos');
        PDF::SetSubject('Pagos');
        PDF::SetKeywords('reporte de pagos, prospectos, PDF');

        
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        PDF::setPrintFooter(false);

        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        PDF::SetFont('times', '', 48);

        PDF::AddPage();

        $bMargin = PDF::getBreakMargin();
        $auto_page_break = PDF::getAutoPageBreak();
        PDF::SetAutoPageBreak(false, 0);
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        PDF::setPageMark();
        PDF::SetTextColor(0, 0, 0, 0);
        PDF::SetFont('helveticaB', '', 11);
        PDF::writeHTMLCell(150, 15, 15, 40 , $tabla, 0, 0, 0, false, 'L', false);

        /////finalizar pdf
        //echo $htmltable;
        $namePDF = uniqid().'_reporte_pagos.pdf';
        //Close and output PDF document
        PDF::Output($namePDF, 'I');
      
      }elseif ($type_export == 'EXCEL'){
        ob_end_clean();

        return Excel::download(new ReportesExport("exports.reporte", $tabla),'Reporte pagos.xlsx');
      }
    }
    public function exportar_visitas(request $request)
    {
      $type_export = $request->get('type_export');
      $proyecto_bs = $request->get('proyecto_export');
      $year_bs = $request->get('year_export');
      $estatus_propiedad_bs = $request->get('estatus_propiedad_export');
      $nombre_bs = $request->get('nombre_export');
      if ($year_bs == '' or $year_bs == null) {
          $year_bs = date('Y');
      }
      $filtro = 'p.fecha_visita is not null';
      $filtro_propiedad = 'prop.tipo_propiedad_id = 1';
      
      if ($nombre_bs) {
          if ($filtro_propiedad != '') {
            $filtro_propiedad = $filtro_propiedad . ' AND ';
          }
          $filtro_propiedad = $filtro_propiedad . ' prop.id_propiedad IN (';
          $i =0;
          foreach ($nombre_bs as $key) {
              if ($i == 0) {
                  $filtro_propiedad = $filtro_propiedad."'".$key."'";
              }else{
                  $filtro_propiedad = $filtro_propiedad.",'".$key."'";
              }
              $i++;
          }
          $filtro_propiedad = $filtro_propiedad .')';
      }
      if ($year_bs != '' and $year_bs != null) {
          if ($filtro != '') {
              $filtro = $filtro . ' AND ';
          }
          $filtro = $filtro . ' year(p.fecha_visita) = '.$year_bs;
      }
      if ($estatus_propiedad_bs != '' and $estatus_propiedad_bs != null and $estatus_propiedad_bs != 'all') {
          if ($filtro_propiedad != '') {
              $filtro_propiedad = $filtro_propiedad . ' AND ';
          }
          $filtro_propiedad = $filtro_propiedad . ' prop.estatus_propiedad_id IN (';
          $i =0;
          foreach ($estatus_propiedad_bs as $key) {
              if ($i == 0) {
                  $filtro_propiedad = $filtro_propiedad."'".$key."'";
              }else{
                  $filtro_propiedad = $filtro_propiedad.",'".$key."'";
              }
              $i++;
          }
          $filtro_propiedad = $filtro_propiedad .')';
      }
      if ($proyecto_bs != '' and $proyecto_bs != null and $proyecto_bs != 'all') {
          if ($filtro_propiedad != '') {
              $filtro_propiedad = $filtro_propiedad . ' AND ';
          }
          $filtro_propiedad = $filtro_propiedad . ' prop.proyecto_id IN (';
          $i =0;
          foreach ($proyecto_bs as $key) {
              if ($i == 0) {
                  $filtro_propiedad = $filtro_propiedad."'".$key."'";
              }else{
                  $filtro_propiedad = $filtro_propiedad.",'".$key."'";
              }
              $i++;
          }
          $filtro_propiedad = $filtro_propiedad .')';
      }
      $tabla = '';
      $meses= array(['1','Enero',0],['2','Febrero',0],['3','Marzo',0],['4','Abril',0],['5','Mayo',0],['6','Junio',0],['7','Julio',0],['8','Agosto',0],['9','Septiembre',0],['10','Octubre',0],['11','Noviembre',0],['12','Diciembre',0]);

      $propiedades = DB::table('propiedad as prop')
      ->join('estatus_propiedad as e','prop.estatus_propiedad_id','=','e.id_estatus_propiedad','left', false)
      ->select('prop.id_propiedad','prop.nombre as nombre_propiedad')
      ->whereRaw($filtro_propiedad)
      ->orderby('prop.nombre','ASC')
      ->get();
      $tabla = $tabla.'<thead class="bg-gray-600 center text-white"><tr><th valign="middle" rowspan="2">Propiedad</th><th colspan="13">Cantidad de visitas</th></tr><tr>';
      foreach ($meses as $m) {
          $tabla = $tabla.'<th>'.$year_bs.'/'.$m[0].'</th>';
      } 
      $tabla = $tabla.'<th>Total</th></tr></thead><tbody>';
      $total_acumulado = 0;
      foreach ($propiedades as $key) {
          $prospecto_validar = DB::table('prospectos as p')
          ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
          ->whereRaw($filtro)
          ->whereYear('p.fecha_visita','=',$year_bs)
          ->where('p.propiedad_id','=',$key->id_propiedad)
          ->first();
          if ($prospecto_validar->cantidad_visita > 0) {
              $tabla = $tabla . '<tr class="text-center"><td>'.$key->nombre_propiedad.'</td>';
              $total_por_propiedad = 0;
              foreach ($meses as $m) {
                  $prospecto_cta = DB::table('prospectos as p')
                  ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
                  ->whereRaw($filtro)
                  ->whereYear('p.fecha_visita','=',$year_bs)
                  ->whereMonth('p.fecha_visita','=',$m[0])
                  ->where('p.propiedad_id','=',$key->id_propiedad)
                  ->first();
                  if ($prospecto_cta) {
                      $tabla = $tabla . '<td>'.number_format($prospecto_cta->cantidad_visita, 2 , "." , ",").'</td>';
                      $total_por_propiedad = $total_por_propiedad + $prospecto_cta->cantidad_visita;
                  }else{
                      $tabla = $tabla . '<td>'.number_format(0, 2 , "." , ",").'</td>';
                      $total_por_propiedad = $total_por_propiedad + 0;
                  }
              }
              $tabla = $tabla . '<td style="font-weight: bold;">'.number_format($total_por_propiedad, 2 , "." , ",").'</td></tr>';
              $total_acumulado = $total_acumulado + $total_por_propiedad;
          }
      }
      $tabla = $tabla . '</tbody><tfoot style="font-weight: bold;"><tr class="text-center"><td>Total</td>';
      foreach ($meses as $m) {
          $prospecto_mes = DB::table('prospectos as p')
          ->join('propiedad as prop','p.propiedad_id','=','prop.id_propiedad')
          ->select('p.id_prospecto', DB::raw('count(*) as cantidad_visita'))
          ->whereRaw($filtro)
          ->whereRaw($filtro_propiedad)
          ->whereYear('p.fecha_visita','=',$year_bs)
          ->whereMonth('p.fecha_visita','=',$m[0])
          ->first();
          if ($prospecto_mes) {
              $tabla = $tabla .'<td>'.number_format($prospecto_mes->cantidad_visita, 2 , "." , ",").'</td>';
          }else{
              $tabla = $tabla .'<td>'.number_format(0, 2 , "." , ",").'</td>';

          }
      }
      $tabla = $tabla . '<td>'.number_format($total_acumulado, 2 , "." , ",").'</td></tr></tfoot>';
      if ($type_export == 'PDF') {
        ////// PDF
        PDF::SetAuthor('Nextapp');
        PDF::SetTitle('Reporte Pagos');
        PDF::SetSubject('Pagos');
        PDF::SetKeywords('reporte de pagos, prospectos, PDF');

        
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetHeaderMargin(0);
        PDF::SetFooterMargin(0);

        PDF::setPrintFooter(false);

        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            PDF::setLanguageArray($l);
        }

        PDF::SetFont('times', '', 48);

        PDF::AddPage();

        $bMargin = PDF::getBreakMargin();
        $auto_page_break = PDF::getAutoPageBreak();
        PDF::SetAutoPageBreak(false, 0);
        PDF::SetAutoPageBreak($auto_page_break, $bMargin);
        PDF::setPageMark();
        PDF::SetTextColor(0, 0, 0, 0);
        PDF::SetFont('helveticaB', '', 11);
        PDF::writeHTMLCell(150, 15, 15, 40 , $tabla, 0, 0, 0, false, 'L', false);

        /////finalizar pdf
        //echo $htmltable;
        $namePDF = uniqid().'_reporte_pagos.pdf';
        //Close and output PDF document
        PDF::Output($namePDF, 'I');
      
      }elseif ($type_export == 'EXCEL'){
        ob_end_clean();

        return Excel::download(new ReportesExport("exports.reporte", $tabla),'Reporte visitas.xlsx');
      }
    }
}
