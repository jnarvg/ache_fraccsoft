<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Pagos;
use App\PlazosPago;
use App\Prospecto;
use App\Propiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;
use PDF;

class PagosController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $pagos = DB::table('pagos')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago', 'monto','fecha','forma_pago_id','estatus','forma_pago','fecha_cancelacion')
        ->orderby('id_pago','DESC')
        ->paginate(10);
        return view('plazos_pago.pagos.index',['pagos'=>$pagos]);
    }
    public function store(request $request)
    {
        return redirect()->route('plazos_pago.pagos.index');
    }
    public function show($id, $procedencia)
    {
        $pago = DB::table('pagos')
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago', 'monto','fecha','forma_pago_id','estatus','forma_pago','plazo_pago_id','fecha_cancelacion')
        ->where('id_pago','=',$id)
        ->first();
        return view('plazos_pago.pagos.show',compact('pago','procedencia'));
    }
    public function update(request $request, $id)
    {
        return redirect()->route('plazos_pago.pagos.index');
    }
    public function destroy($id,$procedencia)
    {
        $pago = Pagos::find($id);
        $id_padre = $pago->plazo_pago_id;
        if ($pago->estatus == 'Cancelado') {
            $pago->delete();
        }
        if ($procedencia ==  'Menu') {
            return redirect()->route('pagos');
        }else{
            return Redirect::to("plazos_pago/show/".$id_padre."/".$procedencia);

        }
    }
    public function cancelar($id, $procedencia)
    {
        $pago = Pagos::find($id);
        $pago->estatus = 'Cancelado';
        $pago->fecha_cancelacion = date('Y-m-d');
        $id_padre = $pago->plazo_pago_id;
        $pago->update();

        $pagos_plazo = DB::table('pagos')
        ->select('id_pago','monto','dias_retraso','mora')
        ->where('plazo_pago_id','=',$id)
        ->where('estatus','=','Aplicado')
        ->get();
        $sumapagos =0;
        $mora_pago =0;
        $dias_pago =0;
        foreach ($pagos_plazo as $key) {
            $sumapagos = $sumapagos + $key->monto;
            $mora_pago = $mora_pago + $key->mora;
            $dias_pago = $dias_pago + $key->dias_retraso;
        }
        $plazo_pago = PlazosPago::findOrFail($id_padre);
        $id_prospecto = $plazo_pago->prospecto_id;
        $prospecto = Prospecto::findOrFail($id_prospecto);
        if ($prospecto->porcentaje_interes > 0) {
            $interesCien = $prospecto->porcentaje_interes / 100;
        }else{
            $interesCien = 0;
        }
        if ($sumapagos >= $plazo_pago->total) {
            $plazo_pago->estatus = 'Pagado';
        }else{
            if ($plazo_pago->estatus == 'Vencido') {
                $plazo_pago->estatus = 'Vencido';
            }else{
                $plazo_pago->estatus = 'Pendiente';
            }
        }
        $plazo_pago->pagado = round($sumapagos,2);
        if ($sumapagos > ($plazo_pago->capital_inicial * $interesCien)) {
            $sumapagos = round($sumapagos - ($plazo_pago->capital_inicial * $interesCien), 2);
            $plazo_pago->interes = 0;
        }else{
            $plazo_pago->interes = round( ($plazo_pago->capital_inicial * $interesCien) - $sumapagos, 2);
            $sumapagos = 0;
        }
        if ($sumapagos > 0) {
            $plazo_pago->capital = round( $plazo_pago->capital_inicial - $sumapagos, 2);
            $sumapagos = 0;
        }else{
            $plazo_pago->capital = round( $plazo_pago->capital_inicial - $sumapagos, 2);
        }
        $plazo_pago->update();
        $plazo_pago->saldo = round($plazo_pago->capital + $plazo_pago->interes, 2);
        $plazo_pago->dias_retraso = $dias_pago;
        $plazo_pago->monto_mora = $mora_pago;
        $plazo_pago->update();
        
        $plazos_pago = DB::table('plazos_pago')
        ->select('id_plazo_pago','pagado')
        ->where('prospecto_id','=', $id_prospecto)
        ->get();
        $sumapagoshecho =0;
        foreach ($plazos_pago as $p) {
            $sumapagoshecho = $sumapagoshecho + $p->pagado;
        }
        $estatus_crm = DB::table('estatus_crm')
        ->select('id_estatus_crm')
        ->where('estatus_crm','=','Pagando')
        ->first();
        $id_estatus = $estatus_crm->id_estatus_crm;
        $prospecto->estatus = $id_estatus; /*Pagando*/
        $prospecto->pagado = $sumapagoshecho;
        if ($prospecto->porcentaje_interes > 0) {
            $interesCien = $prospecto->porcentaje_interes / 100;
        }else{
            $interesCien = 0;
        }
        $plazos_pago = DB::table('plazos_pago')
            ->select('id_plazo_pago','pagado','saldo','interes','capital')
            ->where('prospecto_id','=', $id_prospecto)
            ->where('estatus','!=', 'Pagado')
            ->get();
        $capitalnuevo =0;
        $interesnuevo =0;
        $saldonuevo = 0;
        foreach ($plazos_pago as $p) {
            $capitalnuevo = $capitalnuevo + ($p->capital);
            $interesnuevo = $interesnuevo + ($p->interes);
            $saldonuevo = $saldonuevo + $p->saldo;
        }

        $prospecto->saldo = $saldonuevo;
        $prospecto->capital = $capitalnuevo;
        $prospecto->interes = $interesnuevo;
        $prospecto->update();

        $notification = array(
            'msj' => 'Listo!!',
            'alert-type' => 'success'
        );
        if ($procedencia ==  'Menu') {
            return redirect()->route('pagos')->with($notification);
        }else{
            return Redirect::to("plazos_pago/show/".$id_padre."/".$procedencia)->with($notification);

        }
    }

    public function recibo($id, $procedencia)
    {
        $pago = DB::table('pagos as p')
        ->join('plazos_pago as pp','p.plazo_pago_id','=','pp.id_plazo_pago','left',false)
        ->join('prospectos as c','pp.prospecto_id','=','c.id_prospecto','left',false)
        ->join('forma_pago as f','forma_pago_id','=','f.id_forma_pago','left',false)
        ->select('id_pago', 'p.monto','p.fecha','p.forma_pago_id','p.estatus','forma_pago','plazo_pago_id','p.fecha_cancelacion','pp.prospecto_id','c.nombre as nombre_prospecto')
        ->where('id_pago','=',$id)
        ->first();

        $nombre_prospecto = $pago->nombre_prospecto;

        $year = date('Y', strtotime($pago->fecha));
        $dia = date('d', strtotime($pago->fecha));
        $mesletra = $this->getMesLetra(date('n', strtotime($pago->fecha)));
        $htmlencabezado = '<div style="color:#393939; font-weight:normal; font-size:12pt; font-family: "Trebuchet MS", Verdana, Arial, Helvetica, serif; " >
        <table >
        <tr><th colspan="5"></th></tr>
        <tr><th colspan="3"><h2>RECIBO</h2></th><th colspan="2" align="right">N°'.$pago->id_pago.'</th></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5" style="font-size:9pt;" >Lugar y fecha de expedicion</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5" style="font-size:12pt;" >El '.$dia.' de '.$mesletra.' de '.$year.'</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5">Recibi de '.$nombre_prospecto.' la cantidad de $'.number_format($pago->monto, 2 , "." , ",").'.</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5" style="font-size:9pt;" >Cantidad en letras</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"  align="center" border="1" style="background-color:#EAE9E9;">'.NumeroALetras::convertir($pago->monto,'Moneda Nacional').'</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5">Por concepto de pago de la mensualidad.</td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="5"></td></tr>
        <tr><td colspan="1"></td><td colspan="3" align="center">________________________</td><td colspan="1"></td></tr>
        <tr><td colspan="1"></td><td colspan="3" style="font-size:9pt;" align="center">Nombre y Firma de quien recibe</td><td colspan="1"></td></tr>
        <tr><td colspan="5"></td></tr>
        </table>
        </div>';
        PDF::SetAuthor('Nextapp');
        PDF::SetTitle('Recibo de pago');
        PDF::SetSubject('Recibo');
        PDF::SetKeywords('Recibo','pago');

        
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
        PDF::SetFont('helvetica', '', 12);
        PDF::writeHTMLCell(170, 15, 20, 25 , $htmlencabezado, 0, 0, 0, false, 'L', false);

        /////finalizar pdf
        //echo $htmltable;
        $namePDF = uniqid().'_recibo_pago.pdf';
        //Close and output PDF document
        PDF::Output($namePDF, 'I');

        //return view('plazos_pago.pagos.show',compact('pago','procedencia'));
    }
    public function getMesLetra($mes)
    {
        if ($mes == '1') {$mesletra = 'Enero';}
        if ($mes == '2') {$mesletra = 'Febrero';}
        if ($mes == '3') {$mesletra = 'Marzo';}
        if ($mes == '4') {$mesletra = 'Abril';}
        if ($mes == '5') {$mesletra = 'Mayo';}
        if ($mes == '6') {$mesletra = 'Junio';}
        if ($mes == '7') {$mesletra = 'Julio';}
        if ($mes == '8') {$mesletra = 'Agosto';}
        if ($mes == '9') {$mesletra = 'Septiembre';}
        if ($mes == '10') {$mesletra = 'Octubre';}
        if ($mes == '11') {$mesletra = 'Noviembre';}
        if ($mes == '12') {$mesletra = 'Diciembre';}
        return $mesletra;
    }
}
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr =  $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decimales = $div_decimales[1];
            }
            if(strlen($decNumberStr) == 1){
                $decimales = $div_decimales[1].'0';
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = '.00';
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            if ($moneda == 'year') {
                $valor_convertido = $converted;
            }else{
                $valor_convertido = $converted .'PESOS 00/100 '.strtoupper($moneda);
            }
        } else {
           $valor_convertido = $converted .' PESOS '.$decimales . '/100 '.strtoupper($moneda);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}