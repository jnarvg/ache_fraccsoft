<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ExternosController extends Controller
{
    
    public function indexPlazos()
    {
      $idprospecto = auth()->user()->prospecto_id;

      $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado')
        ->where('prospecto_id','=',$idprospecto)
        ->get();

      $resultados = DB::table('plazos_pago')
        ->select('id_plazo_pago','estatus', DB::raw('count(*) as cantidad'))
        ->where('prospecto_id','=',$idprospecto)
        ->groupBy('estatus')
        ->get();

      return view('externos.plazos',compact('plazos_pago','resultados'));
    }
    public function indexDocs()
    {
      $idprospecto = auth()->user()->prospecto_id;

      $documentos = DB::table('documento')
        ->select('id_documento','fecha', 'notas','titulo','archivo')
        ->where('prospecto_id','=',$idprospecto)
        ->paginate(5);

      return view('externos.documentos',compact('documentos'));
    }
    public function showplazo($id)
    {
      $idprospecto = auth()->user()->prospecto_id;

      $plazo = DB::table('plazos_pago')
        ->select('id_plazo_pago','prospecto_id', 'fecha','estatus','num_plazo','total','saldo','pagado','monto_mora','dias_retraso','notas','interes','interes_acumulado','capital_acumulado','total_acumulado','deuda','amortizacion','capital','capital_inicial','moneda_id')
        ->where('id_plazo_pago','=',$id)
        ->first();

        $prospectos = DB::table('prospectos')
        ->select('id_prospecto', 'nombre')
        ->orderby('nombre','asc')
        ->get();
        $monedas = DB::table('moneda')
        ->get();

        $formas_pago = DB::table('forma_pago')
        ->select('id_forma_pago','forma_pago')
        ->get();
        $pagos_plazo = DB::table('pagos')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago','monto','fecha','estatus','forma_pago_id','f.forma_pago')
        ->where('plazo_pago_id','=',$id)
        ->get();

        return view('externos.show_plazo',compact('plazo','prospectos','procedencia','pagos_plazo','formas_pago','monedas'));
    }
}
