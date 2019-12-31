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
use App\Exports\Rep_EngacheRecibir;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Middleware\Authenticate;
use PDF;

class ReportesExcelController extends Controller
{
    public function exportExcelEnganches(request $request)
    {   
        $proyecto_bs = $request->get('proyecto_excel');
        $fecha_maxima_bs = $request->get('fecha_maxima_excel');
        $fecha_minima_bs = $request->get('fecha_minima_excel');

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

        $total_engache = 0;
        $total_pagado = 0;
        foreach ($resultados as $key) {
             $total_engache = $total_engache + $key->total;
             $total_pagado = $total_pagado + $key->pagado;
        } 

        ob_end_clean();
        return Excel::download(new Rep_EngacheRecibir("exports.rep_engache_recibir", $resultados, $total_engache,$total_pagado),'Enganche a recibir.xlsx');
    }
}
